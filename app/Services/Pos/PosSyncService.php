<?php

namespace App\Services\Pos;

use App\Models\Brand;
use App\Models\BrandTranslation;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Color;
use App\Models\Order;
use App\Models\PosApiSetting;
use App\Models\PosOrderExport;
use App\Models\PosSyncMapping;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductStock;
use App\Models\ProductTranslation;
use App\Models\Unit;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PosSyncService
{
    protected PosApiClient $client;
    protected PosApiSetting $setting;

    public function __construct(?PosApiSetting $setting = null)
    {
        $this->setting = $setting ?: PosApiSetting::current();
        $this->client = new PosApiClient($this->setting);
    }

    public function testConnection(): array
    {
        return $this->client->settings();
    }

    public function syncCategories(): array
    {
        $items = $this->flattenCategories($this->collection($this->client->categories()));
        $count = 0;

        DB::transaction(function () use ($items, &$count) {
            foreach ($items as $item) {
                $posId = $this->value($item, ['id', 'category_id']);
                $name = $this->value($item, ['name', 'category_name']);

                if (!$posId || !$name) {
                    continue;
                }

                $categoryId = PosSyncMapping::ecommerceId('category', $posId);
                $category = $categoryId ? Category::find($categoryId) : null;
                $category = $category ?: new Category();
                $parentPosId = $this->value($item, ['parent_id', '_pos_parent_id']);
                $parentId = $parentPosId ? PosSyncMapping::ecommerceId('category', $parentPosId) : null;
                $category->name = Str::limit($name, 50, '');
                $category->order_level = $category->order_level ?? 0;
                $category->digital = 0;
                $category->parent_id = $parentId ?: 0;
                $category->level = $parentId ? ((int) optional(Category::find($parentId))->level + 1) : 0;
                $category->slug = $category->slug ?: $this->uniqueSlug(Category::class, $name);
                $category->meta_title = $category->meta_title ?: $name;
                $category->save();

                CategoryTranslation::updateOrCreate(
                    ['category_id' => $category->id, 'lang' => env('DEFAULT_LANGUAGE', 'en')],
                    ['name' => $category->name]
                );

                PosSyncMapping::remember('category', $posId, $category->id);
                $count++;
            }
        });

        $this->markSynced();

        return ['count' => $count];
    }

    public function syncBrands(): array
    {
        $items = $this->collection($this->client->brands());
        $count = 0;

        DB::transaction(function () use ($items, &$count) {
            foreach ($items as $item) {
                $posId = $this->value($item, ['id', 'brand_id']);
                $name = $this->value($item, ['name', 'brand_name']);

                if (!$posId || !$name) {
                    continue;
                }

                $brandId = PosSyncMapping::ecommerceId('brand', $posId);
                $brand = $brandId ? Brand::find($brandId) : null;
                $brand = $brand ?: new Brand();
                $brand->name = Str::limit($name, 50, '');
                $brand->slug = $brand->slug ?: $this->uniqueSlug(Brand::class, $name);
                $brand->meta_title = $brand->meta_title ?: $name;
                $brand->save();

                BrandTranslation::updateOrCreate(
                    ['brand_id' => $brand->id, 'lang' => env('DEFAULT_LANGUAGE', 'en')],
                    ['name' => $brand->name]
                );

                PosSyncMapping::remember('brand', $posId, $brand->id);
                $count++;
            }
        });

        $this->markSynced();

        return ['count' => $count];
    }

    public function syncProducts(int $limit = 50, bool $syncDependencies = true): array
    {
        if ($syncDependencies) {
            $this->syncCategories();
            $this->syncBrands();
        }

        $page = 1;
        $count = 0;

        do {
            $response = $this->client->products($limit, $page);
            $items = $this->collection($response);
            $lastPage = $this->lastPage($response, $page);

            DB::transaction(function () use ($items, &$count) {
                foreach ($items as $item) {
                    if ($this->syncProduct($item)) {
                        $count++;
                    }
                }
            });

            $page++;
        } while ($page <= $lastPage && count($items) > 0);

        $this->markSynced();

        return ['count' => $count];
    }

    public function syncSelectedProducts(array $posProductIds): array
    {
        $ids = array_values(array_unique(array_filter($posProductIds)));
        $count = 0;

        if (empty($ids)) {
            return ['count' => 0];
        }

        $this->syncCategories();
        $this->syncBrands();

        foreach ($ids as $id) {
            $item = $this->client->product($id);
            $item = data_get($item, 'data', $item);

            DB::transaction(function () use ($item, &$count) {
                if (is_array($item) && $this->syncProduct($item)) {
                    $count++;
                }
            });
        }

        $this->markSynced();

        return ['count' => $count];
    }

    public function removeImportedProducts(?array $posProductIds = null): array
    {
        $query = PosSyncMapping::where('entity_type', 'product');

        if ($posProductIds !== null) {
            $ids = array_values(array_unique(array_filter($posProductIds)));
            if (empty($ids)) {
                return ['count' => 0];
            }
            $query->whereIn('pos_id', array_map('strval', $ids));
        }

        $mappings = $query->get();
        $count = 0;

        DB::transaction(function () use ($mappings, &$count) {
            foreach ($mappings as $mapping) {
                $product = Product::find($mapping->ecommerce_id);

                if ($product) {
                    $stockIds = $product->stocks()->pluck('id')->all();

                    $product->product_translations()->delete();
                    $product->categories()->detach();
                    $product->stocks()->delete();
                    $product->taxes()->delete();
                    $product->frequently_bought_products()->delete();
                    $product->last_viewed_products()->delete();
                    $product->flash_deal_products()->delete();
                    deleteProductReview($product);
                    $product->carts()->delete();
                    $product->wishlists()->delete();
                    $product->delete();

                    PosSyncMapping::where('entity_type', 'variation')->whereIn('ecommerce_id', $stockIds)->delete();
                    $count++;
                }

                $mapping->delete();
            }
        });

        return ['count' => $count];
    }

    public function productManagerPage(int $limit = 50, int $page = 1, ?string $search = null): array
    {
        $limit = max(1, $limit);
        $page = max(1, $page);
        $search = trim((string) $search);

        if ($search !== '') {
            $pageData = $this->searchedProductManagerPage($limit, $page, $search);
            $items = $pageData['items'];
            $page = $pageData['page'];
            $lastPage = $pageData['last_page'];
            $total = $pageData['total'];
        } else {
            $response = $this->client->products($limit, $page);
            $items = $this->collection($response);
            $lastPage = $this->lastPage($response, $page);
            $total = (int) data_get($response, 'total', data_get($response, 'data.total', count($items)));
        }

        $posIds = array_values(array_filter(array_map(fn ($item) => (string) $this->value($item, ['id', 'product_id']), $items)));
        $mappings = PosSyncMapping::where('entity_type', 'product')
            ->whereIn('pos_id', $posIds)
            ->get()
            ->keyBy('pos_id');

        $rows = array_map(function ($item) use ($mappings) {
            $posId = (string) $this->value($item, ['id', 'product_id']);
            $mapping = $mappings->get($posId);
            $variations = $this->productVariations($item);

            return [
                'pos_id' => $posId,
                'name' => $this->value($item, ['name', 'product_name'], ''),
                'sku' => $this->value($item, ['sku', 'sub_sku', 'product_variations.0.variations.0.sub_sku'], ''),
                'color' => implode(', ', $this->productColors($item, $variations)),
                'print' => implode(', ', $this->productPrints($item, $variations)),
                'category' => $this->value($item, ['category.name', 'sub_category.name'], ''),
                'brand' => $this->value($item, ['brand.name'], ''),
                'price' => $this->productPrice($item, $variations),
                'qty' => $this->productQty($item, $variations),
                'imported' => (bool) $mapping,
                'ecommerce_id' => $mapping ? (int) $mapping->ecommerce_id : null,
                'updated_at' => $mapping ? $mapping->updated_at : null,
            ];
        }, $items);

        return [
            'rows' => $rows,
            'page' => $page,
            'limit' => $limit,
            'search' => $search,
            'last_page' => $lastPage,
            'total' => $total,
            'imported_total' => PosSyncMapping::where('entity_type', 'product')->count(),
        ];
    }

    public function syncAll(): array
    {
        $categories = $this->syncCategories();
        $brands = $this->syncBrands();
        $products = $this->syncProducts(50, false);

        return compact('categories', 'brands', 'products');
    }

    public function sendOrder(Order $order): array
    {
        $existing = PosOrderExport::where('order_id', $order->id)->where('status', 'sent')->first();
        if ($existing) {
            return ['success' => true, 'message' => 'Order already sent to POS.'];
        }

        $order->load(['orderDetails.product', 'user']);
        $customer = $this->client->createCustomer([
            'name' => $order->user->name ?? 'Guest Customer',
            'email' => $order->user->email ?? ('guest-' . $order->id . '@example.com'),
        ]);

        $posCustomerId = $this->value($customer, ['id', 'contact.id', 'customer.id', 'data.id']);
        if (!$posCustomerId) {
            throw new \RuntimeException('POS customer response did not include an id.');
        }

        $products = [];
        foreach ($order->orderDetails as $detail) {
            $stock = $this->stockForOrderDetail($detail);
            $variationId = $stock ? PosSyncMapping::posId('variation', $stock->id) : null;

            if (!$variationId) {
                throw new \RuntimeException('Order product #' . $detail->product_id . ' is not mapped to a POS variation.');
            }

            $products[$variationId] = [
                'variation_id' => (int) $variationId,
                'quantity' => (int) $detail->quantity,
                'product_name' => $detail->product->name ?? 'Product #' . $detail->product_id,
            ];
        }

        $response = $this->client->createOrder([
            'customer_id' => (int) $posCustomerId,
            'addresses' => [
                'shipping' => $this->formatAddress($order->shipping_address),
                'billing' => $this->formatAddress($order->billing_address ?: $order->shipping_address),
            ],
            'products' => $products,
        ]);

        PosOrderExport::updateOrCreate(
            ['order_id' => $order->id],
            [
                'pos_transaction_id' => $this->value($response, ['transaction.id', 'data.transaction.id', 'id']),
                'pos_customer_id' => (string) $posCustomerId,
                'status' => !empty($response['success']) ? 'sent' : 'failed',
                'message' => $response['message'] ?? json_encode(Arr::only($response, ['error_messages'])),
                'sent_at' => !empty($response['success']) ? now() : null,
            ]
        );

        return $response;
    }

    protected function stockForOrderDetail($detail): ?ProductStock
    {
        if (!$detail->product) {
            return null;
        }

        if ($detail->variation !== null && $detail->variation !== '') {
            $stock = $detail->product->stocks()->where('variant', $detail->variation)->first();
            if ($stock) {
                return $stock;
            }
        }

        return $detail->product->stocks()->first();
    }

    protected function syncProduct(array $item): bool
    {
        $posId = $this->value($item, ['id', 'product_id']);
        $name = $this->value($item, ['name', 'product_name']);

        if (!$posId || !$name) {
            return false;
        }

        $variations = $this->productVariations($item);
        $categoryId = $this->mappedCategoryId($item) ?: $this->fallbackCategoryId();
        $brandId = $this->mappedBrandId($item);
        $price = $this->productPrice($item, $variations);
        $purchasePrice = $this->productPurchasePrice($item, $variations, $price);
        $stockQty = $this->productQty($item, $variations);
        $description = $this->value($item, ['product_description', 'description'], '');
        $imageId = $this->externalUploadId($this->imageUrl($item), $name);
        $unit = $this->unitValue($this->value($item, ['unit.short_name', 'unit.actual_name', 'unit'], 'Pc'));
        $colorNames = $this->productColors($item, $variations);
        $printNames = $this->productPrints($item, $variations);
        $colors = $this->productColorCodes($item, $variations);
        $choiceOptions = $this->productChoiceOptions($item, $variations);

        $productId = PosSyncMapping::ecommerceId('product', $posId);
        $product = $productId ? Product::find($productId) : null;
        $product = $product ?: $this->findProductByImportedSku($item, $variations);
        $product = $product ?: new Product();
        $product->name = $name;
        $product->added_by = 'admin';
        $product->user_id = $product->user_id ?: $this->adminUserId();
        $product->category_id = $categoryId;
        $product->brand_id = $brandId;
        $product->description = $description;
        $product->unit_price = $price;
        $product->purchase_price = $purchasePrice;
        $product->current_stock = $stockQty;
        $product->unit = $unit;
        $product->variant_product = count($variations) > 1 || !empty($colorNames) || !empty($colors) || !empty($printNames) ? 1 : 0;
        $product->published = (int) !$this->value($item, ['is_inactive'], 0);
        $product->approved = 1;
        $product->attributes = $this->attributeIdsJson($choiceOptions, $product->attributes);
        $product->choice_options = $choiceOptions ? json_encode($choiceOptions, JSON_UNESCAPED_UNICODE) : ($product->choice_options ?: '[]');
        $product->colors = $colors ? json_encode($colors) : ($product->colors ?: '[]');
        $product->slug = $product->slug ?: $this->uniqueSlug(Product::class, $name);
        $product->thumbnail_img = $imageId ?: $product->thumbnail_img;
        $product->photos = $imageId ? (string) $imageId : $product->photos;
        $product->meta_title = $product->meta_title ?: $name;
        $product->meta_description = $product->meta_description ?: strip_tags($description);
        $product->save();

        DB::table('product_categories')->updateOrInsert([
            'product_id' => $product->id,
            'category_id' => $categoryId,
        ]);

        ProductTranslation::updateOrCreate(
            ['product_id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE', 'en')],
            ['name' => $product->name, 'unit' => $unit, 'description' => $description]
        );

        PosSyncMapping::remember('product', $posId, $product->id);
        $this->syncProductStocks($product, $item, $variations, $price, $stockQty);

        return true;
    }

    protected function syncProductStocks(Product $product, array $item, array $variations, float $fallbackPrice, int $fallbackQty): void
    {
        if (empty($variations)) {
            $stock = $this->findOrNewStock($product, $this->value($item, ['sku', 'sub_sku', 'product_sku', 'id']), '');
            $stock->product_id = $product->id;
            $stock->variant = '';
            $stock->sku = $this->value($item, ['sku', 'sub_sku', 'product_sku']);
            $stock->price = $fallbackPrice;
            $stock->qty = $fallbackQty;
            $stock->image = $this->externalUploadId($this->imageUrl($item), $product->name) ?: $stock->image;
            $stock->save();
            PosSyncMapping::remember('variation', $this->value($item, ['variation_id', 'id']), $stock->id);
            return;
        }

        foreach ($variations as $variation) {
            $posVariationId = $this->value($variation, ['id', 'variation_id']);
            $sku = $this->value($variation, ['sub_sku', 'sku', 'product_sku']);
            $variantName = $this->variationName($variation);
            $stock = $this->findOrNewStock($product, $sku, $variantName, $posVariationId);
            $stock->product_id = $product->id;
            $stock->variant = $variantName;
            $stock->sku = $sku;
            $stock->price = $this->variationPrice($variation, $fallbackPrice);
            $stock->qty = $this->variationQty($variation, $fallbackQty);
            $stock->image = $this->externalUploadId($this->imageUrl($variation), $product->name . ' ' . $variantName) ?: $stock->image;
            $stock->save();

            if ($posVariationId) {
                PosSyncMapping::remember('variation', $posVariationId, $stock->id);
            }
        }
    }

    protected function mappedCategoryId(array $item): ?int
    {
        $posId = $this->value($item, ['sub_category_id', 'sub_category.id', 'category_id', 'category.id']);
        return $posId ? PosSyncMapping::ecommerceId('category', $posId) : null;
    }

    protected function mappedBrandId(array $item): ?int
    {
        $posId = $this->value($item, ['brand_id', 'brand.id']);
        return $posId ? PosSyncMapping::ecommerceId('brand', $posId) : null;
    }

    protected function fallbackCategoryId(): int
    {
        $category = Category::where('name', 'POS Imported')->first();
        if (!$category) {
            $category = new Category();
            $category->name = 'POS Imported';
            $category->parent_id = 0;
            $category->level = 0;
            $category->digital = 0;
            $category->slug = $this->uniqueSlug(Category::class, 'POS Imported');
            $category->save();
        }

        return $category->id;
    }

    protected function externalUploadId(?string $url, string $name): ?int
    {
        if (!$url) {
            return null;
        }

        $upload = Upload::where('external_link', $url)->first();
        if ($upload) {
            return $upload->id;
        }

        $upload = new Upload();
        $upload->file_original_name = Str::limit($name, 100, '');
        $upload->external_link = $url;
        $upload->type = 'image';
        $upload->extension = pathinfo(parse_url($url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION) ?: 'jpg';
        $upload->user_id = $this->adminUserId();
        $upload->save();

        return $upload->id;
    }

    protected function imageUrl(array $item): ?string
    {
        $image = $this->value($item, [
            'image_url',
            'image',
            'display_url',
            'media.0.display_url',
            'media.0.url',
            'media.0.thumbnail',
            'product_variations.0.variations.0.media.0.display_url',
            'product_variations.0.variations.0.media.0.url',
            'product_variations.0.variations.0.media.0.thumbnail',
        ]);

        if (!$image) {
            return null;
        }

        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }

        return rtrim($this->setting->pos_base_url, '/') . '/' . ltrim($image, '/');
    }

    protected function formatAddress($address): string
    {
        if (is_array($address)) {
            return implode(', ', array_filter($address));
        }

        $decoded = json_decode($address ?: '', true);
        if (is_array($decoded)) {
            return implode(', ', array_filter($decoded));
        }

        return (string) $address;
    }

    protected function collection(array $response): array
    {
        $items = data_get($response, 'data.data', data_get($response, 'data', $response));
        if (!is_array($items)) {
            return [];
        }

        return array_values(array_filter($items, 'is_array'));
    }

    protected function lastPage(array $response, int $default): int
    {
        return (int) data_get($response, 'last_page', data_get($response, 'data.last_page', $default));
    }

    protected function productVariations(array $item): array
    {
        $variations = [];

        foreach ((array) data_get($item, 'variations', []) as $variation) {
            if (is_array($variation)) {
                $variations[] = $variation;
            }
        }

        foreach ((array) data_get($item, 'product_variations', []) as $productVariation) {
            foreach ((array) data_get($productVariation, 'variations', []) as $variation) {
                if (is_array($variation)) {
                    $variation['_product_variation_name'] = data_get($productVariation, 'name');
                    $variations[] = $variation;
                }
            }
        }

        return $this->uniqueVariations($variations);
    }

    protected function searchedProductManagerPage(int $limit, int $page, string $search): array
    {
        $scanLimit = max($limit, 100);
        $scanPage = 1;
        $maxScanPages = 100;
        $items = [];

        do {
            $response = $this->client->products($scanLimit, $scanPage);
            $pageItems = $this->collection($response);
            $items = array_merge($items, $pageItems);
            $lastScanPage = $this->lastPage($response, $scanPage);
            $scanPage++;
        } while ($scanPage <= $lastScanPage && $scanPage <= $maxScanPages && count($pageItems) > 0);

        $filtered = $this->filterProducts($items, $search);
        $total = count($filtered);
        $lastPage = max(1, (int) ceil($total / max($limit, 1)));
        $page = min(max($page, 1), $lastPage);
        $offset = ($page - 1) * $limit;

        return [
            'items' => array_slice($filtered, $offset, $limit),
            'page' => $page,
            'last_page' => $lastPage,
            'total' => $total,
        ];
    }

    protected function filterProducts(array $items, string $search): array
    {
        $tokens = $this->searchTokens($search);

        if (empty($tokens)) {
            return $items;
        }

        return array_values(array_filter($items, function ($item) use ($tokens) {
            $variations = $this->productVariations($item);
            $haystack = implode(' ', array_filter([
                $this->value($item, ['id', 'product_id']),
                $this->value($item, ['name', 'product_name']),
                $this->value($item, ['sku', 'sub_sku', 'product_sku', 'product_variations.0.variations.0.sub_sku']),
                implode(' ', array_map(fn ($variation) => $this->value($variation, ['sub_sku', 'sku', 'product_sku']), $variations)),
                implode(' ', array_map(fn ($variation) => $this->variationName($variation), $variations)),
                implode(' ', $this->productColors($item, $variations)),
                implode(' ', $this->productPrints($item, $variations)),
                $this->value($item, ['category.name', 'sub_category.name']),
                $this->value($item, ['brand.name']),
            ]));

            $haystack = $this->normalizeSearchText($haystack);

            foreach ($tokens as $token) {
                if (!Str::contains($haystack, $token)) {
                    return false;
                }
            }

            return true;
        }));
    }

    protected function searchTokens(string $search): array
    {
        $search = $this->normalizeSearchText($search);
        $tokens = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return array_values(array_unique($tokens));
    }

    protected function normalizeSearchText(string $text): string
    {
        $text = Str::lower($text);
        $text = preg_replace('/[^a-z0-9#]+/i', ' ', $text);

        return trim(preg_replace('/\s+/', ' ', $text));
    }

    protected function productPrice(array $item, array $variations): float
    {
        $prices = array_filter(array_map(fn ($variation) => $this->variationPrice($variation, null), $variations), fn ($price) => $price !== null);

        return (float) ($this->value($item, ['unit_price', 'default_sell_price', 'sell_price_inc_tax'], count($prices) ? min($prices) : 0));
    }

    protected function productPurchasePrice(array $item, array $variations, float $fallback): float
    {
        $prices = array_filter(array_map(fn ($variation) => $this->value($variation, ['default_purchase_price', 'dpp_inc_tax']), $variations), fn ($price) => $price !== null);

        return (float) $this->value($item, ['purchase_price', 'default_purchase_price'], count($prices) ? min($prices) : $fallback);
    }

    protected function productQty(array $item, array $variations): int
    {
        if (empty($variations)) {
            return (int) $this->value($item, ['current_stock', 'qty_available', 'qty', 'quantity', 'stock'], 0);
        }

        return array_sum(array_map(fn ($variation) => $this->variationQty($variation, 0), $variations));
    }

    protected function variationName(array $variation): string
    {
        $parts = $this->uniqueValues([
            $this->variationColor($variation),
            $this->variationPrint($variation),
        ]);

        $name = $this->cleanVariantPart($this->value($variation, [
            'name',
            'value',
            'variation_value',
            'variation_template_value.name',
            'variation_value.name',
            'option_name',
        ]));

        if ($name) {
            $parts[] = $name;
        }

        $group = $this->cleanVariantPart($this->value($variation, ['_product_variation_name']));
        if ($group && empty($parts)) {
            $parts[] = $group;
        }

        return implode('-', $this->uniqueValues($parts));
    }

    protected function variationPrice(array $variation, $fallback): ?float
    {
        $price = $this->value($variation, ['sell_price_inc_tax', 'default_sell_price', 'default_sell_price_inc_tax'], $fallback);

        return $price === null ? null : (float) $price;
    }

    protected function variationQty(array $variation, int $fallback): int
    {
        $details = (array) data_get($variation, 'variation_location_details', []);
        if (empty($details)) {
            return (int) $this->value($variation, ['qty_available', 'current_stock', 'qty', 'quantity', 'stock'], $fallback);
        }

        return (int) array_sum(array_map(fn ($detail) => (float) data_get($detail, 'qty_available', 0), $details));
    }

    protected function productColors(array $item, array $variations): array
    {
        return $this->uniqueValues(array_merge(
            [$this->variationColor($item)],
            array_map(fn ($variation) => $this->variationColor($variation), $variations)
        ));
    }

    protected function productPrints(array $item, array $variations): array
    {
        return $this->uniqueValues(array_merge(
            [$this->variationPrint($item)],
            array_map(fn ($variation) => $this->variationPrint($variation), $variations)
        ));
    }

    protected function productColorCodes(array $item, array $variations): array
    {
        $codes = [];

        foreach (array_merge([$item], $variations) as $source) {
            if (is_array($source)) {
                $code = $this->colorCodeForSource($source);
                if ($code) {
                    $codes[] = $code;
                }
            }
        }

        if (!empty($codes)) {
            return $this->uniqueValues($codes);
        }

        return $this->uniqueValues(array_map(fn ($color) => $this->colorCodeForName($color), $this->productColors($item, $variations)));
    }

    protected function productChoiceOptions(array $item, array $variations): array
    {
        $prints = $this->productPrints($item, $variations);

        if (empty($prints)) {
            return [];
        }

        $attributeId = $this->attributeIdForName('Print');
        if (!$attributeId) {
            return [];
        }

        return [[
            'attribute_id' => (string) $attributeId,
            'values' => $prints,
        ]];
    }

    protected function unitValue($unit)
    {
        $unit = trim((string) $unit) ?: 'Pc';

        if (!Schema::hasTable('units')) {
            return $unit;
        }

        $record = Unit::where('name', $unit)->first();
        if (!$record) {
            $record = new Unit();
            $record->name = $unit;
            $record->save();
        }

        return $record->id;
    }

    protected function attributeIdsJson(array $choiceOptions, ?string $existing): string
    {
        if (empty($choiceOptions)) {
            return $existing ?: '[]';
        }

        return json_encode(array_values(array_map(fn ($option) => (string) $option['attribute_id'], $choiceOptions)));
    }

    protected function attributeIdForName(string $name): ?int
    {
        if (!class_exists(\App\Models\Attribute::class) || !Schema::hasTable('attributes')) {
            return null;
        }

        $attribute = \App\Models\Attribute::where('name', $name)->first();
        if (!$attribute) {
            $attribute = new \App\Models\Attribute();
            $attribute->name = $name;
            $attribute->save();
        }

        return $attribute ? (int) $attribute->id : null;
    }

    protected function colorCodeForName(?string $name): ?string
    {
        if (!$name) {
            return null;
        }

        $color = Schema::hasTable('colors')
            ? Color::where('name', $name)->orWhere('code', $name)->first()
            : null;

        if ($color) {
            return $color->code;
        }

        $code = $this->commonColorCode($name);
        if ($code) {
            $this->ensureColor($name, $code);
        }

        return $code;
    }

    protected function colorCodeForSource(array $source): ?string
    {
        $explicitCode = $this->value($source, [
            'color.code',
            'colour.code',
            'color_code',
            'colour_code',
            'hex_color',
            'hex',
        ]);

        if ($explicitCode && preg_match('/^#?[0-9a-fA-F]{6}$/', (string) $explicitCode)) {
            $explicitCode = (string) $explicitCode;
            $code = Str::startsWith($explicitCode, '#') ? strtoupper($explicitCode) : '#' . strtoupper($explicitCode);
            $this->ensureColor($this->variationColor($source) ?: $code, $code);

            return $code;
        }

        return $this->colorCodeForName($this->variationColor($source));
    }

    protected function ensureColor(string $name, string $code): void
    {
        if (!Schema::hasTable('colors')) {
            return;
        }

        $color = Color::where('code', $code)->first();
        if (!$color) {
            $color = new Color();
            $color->code = $code;
        }

        if (empty($color->name)) {
            $color->name = $name;
        }

        $color->save();
    }

    protected function commonColorCode(string $name): ?string
    {
        $colors = [
            'black' => '#000000',
            'white' => '#FFFFFF',
            'red' => '#FF0000',
            'green' => '#008000',
            'blue' => '#0000FF',
            'yellow' => '#FFFF00',
            'orange' => '#FFA500',
            'purple' => '#800080',
            'pink' => '#FFC0CB',
            'brown' => '#A52A2A',
            'gray' => '#808080',
            'grey' => '#808080',
            'silver' => '#C0C0C0',
            'gold' => '#FFD700',
            'beige' => '#F5F5DC',
            'navy' => '#000080',
            'teal' => '#008080',
            'maroon' => '#800000',
            'lime' => '#00FF00',
            'cyan' => '#00FFFF',
        ];

        return $colors[Str::lower(trim($name))] ?? null;
    }

    protected function variationColor(array $variation): ?string
    {
        return $this->cleanVariantPart($this->value($variation, [
            'color.name',
            'colour.name',
            'color',
            'colour',
            'color_name',
            'colour_name',
            'variation_color',
            'attributes.color',
            'attributes.colour',
        ]));
    }

    protected function variationPrint(array $variation): ?string
    {
        return $this->cleanVariantPart($this->value($variation, [
            'print.name',
            'print',
            'print_name',
            'printing',
            'printing_name',
            'design',
            'design_name',
            'attributes.print',
            'attributes.printing',
            'attributes.design',
        ]));
    }

    protected function cleanVariantPart($value): ?string
    {
        if (is_array($value)) {
            $value = $this->value($value, ['name', 'value', 'label']);
        }

        $value = trim((string) $value);

        return $value === '' || strtoupper($value) === 'DUMMY' ? null : $value;
    }

    protected function uniqueValues(array $values): array
    {
        $unique = [];
        foreach ($values as $value) {
            $value = $this->cleanVariantPart($value);
            if ($value && !in_array($value, $unique, true)) {
                $unique[] = $value;
            }
        }

        return $unique;
    }

    protected function uniqueVariations(array $variations): array
    {
        $unique = [];
        $seen = [];

        foreach ($variations as $variation) {
            $key = implode('|', array_filter([
                $this->value($variation, ['id', 'variation_id']),
                $this->value($variation, ['sub_sku', 'sku', 'product_sku']),
                $this->variationName($variation),
            ]));

            $key = $key ?: md5(json_encode($variation));

            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $unique[] = $variation;
            }
        }

        return $unique;
    }

    protected function findProductByImportedSku(array $item, array $variations): ?Product
    {
        $sku = $this->value($item, ['sku', 'sub_sku', 'product_sku']);
        if (!$sku && !empty($variations)) {
            $sku = $this->value($variations[0], ['sub_sku', 'sku', 'product_sku']);
        }

        $stock = $sku ? ProductStock::where('sku', $sku)->first() : null;

        return $stock ? $stock->product : null;
    }

    protected function findOrNewStock(Product $product, ?string $sku, string $variant, $posVariationId = null): ProductStock
    {
        $stockId = $posVariationId ? PosSyncMapping::ecommerceId('variation', $posVariationId) : null;
        $stock = $stockId ? ProductStock::find($stockId) : null;

        if (!$stock && $sku) {
            $stock = ProductStock::where('product_id', $product->id)->where('sku', $sku)->first();
        }

        if (!$stock) {
            $stock = ProductStock::where('product_id', $product->id)->where('variant', $variant)->first();
        }

        return $stock ?: new ProductStock();
    }

    protected function flattenCategories(array $categories, $parentPosId = null): array
    {
        $flat = [];

        foreach ($categories as $category) {
            $children = $category['sub_categories'] ?? $category['children'] ?? [];
            unset($category['sub_categories'], $category['children']);

            if ($parentPosId) {
                $category['_pos_parent_id'] = $parentPosId;
            }

            $flat[] = $category;

            if (is_array($children) && count($children) > 0) {
                $flat = array_merge($flat, $this->flattenCategories($children, $this->value($category, ['id', 'category_id'])));
            }
        }

        return $flat;
    }

    protected function value(array $item, array $keys, $default = null)
    {
        foreach ($keys as $key) {
            $value = data_get($item, $key);
            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        return $default;
    }

    protected function uniqueSlug(string $modelClass, string $name): string
    {
        $slug = Str::slug($name) ?: Str::random(8);
        $base = $slug;
        $i = 1;

        while ($modelClass::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    protected function adminUserId(): int
    {
        return (int) (auth()->id() ?: optional(User::where('user_type', 'admin')->first())->id ?: 1);
    }

    protected function markSynced(): void
    {
        $this->setting->last_sync_at = now();
        $this->setting->save();
    }
}
