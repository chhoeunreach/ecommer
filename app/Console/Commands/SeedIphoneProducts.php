<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\BrandTranslation;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductTranslation;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Seed real iPhone product records with local generated product images.
 */
class SeedIphoneProducts extends Command
{
    protected $signature = 'products:seed-iphones {--fresh : Delete previously seeded iPhone products first}';

    protected $description = 'Seed iPhone products with local images without wiping unrelated products or database tables.';

    /**
     * Seed iPhone products.
     */
    public function handle(): int
    {
        if ($this->option('fresh')) {
            $this->deleteSeededIphones();
        }

        $admin = User::where('user_type', 'admin')->first();
        if (!$admin) {
            $this->error('No admin user found. Restore/import users before seeding products.');
            return self::FAILURE;
        }

        $category = $this->firstOrCreateCategory('iPhone');
        $brand = $this->firstOrCreateBrand('Apple');
        $created = 0;
        $updated = 0;

        DB::transaction(function () use ($admin, $category, $brand, &$created, &$updated): void {
            foreach ($this->catalog() as $index => $item) {
                $image = $this->imageFor($item, $admin->id, $index);
                $slug = Str::slug($item['name']);
                $barcode = 'IPHONE-SEED-' . Str::upper(str_replace('-', '', $slug));

                $product = Product::where('barcode', $barcode)->first();
                $wasExisting = (bool) $product;
                $product = $product ?: new Product();

                $product->forceFill([
                    'name' => $item['name'],
                    'added_by' => 'admin',
                    'user_id' => $admin->id,
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'photos' => (string) $image->id,
                    'thumbnail_img' => (string) $image->id,
                    'tags' => 'iphone,apple,smartphone,ios,' . strtolower((string) $item['year']),
                    'description' => $this->descriptionFor($item),
                    'unit_price' => $item['price'],
                    'purchase_price' => round($item['price'] * 0.82, 2),
                    'variant_product' => 0,
                    'attributes' => '[]',
                    'choice_options' => '[]',
                    'colors' => '[]',
                    'todays_deal' => $index < 4 ? 1 : 0,
                    'published' => 1,
                    'draft' => 0,
                    'approved' => 1,
                    'stock_visibility_state' => 'quantity',
                    'cash_on_delivery' => 1,
                    'featured' => $index < 8 ? 1 : 0,
                    'current_stock' => $item['stock'],
                    'unit' => 'pc',
                    'weight' => $item['weight'],
                    'min_qty' => 1,
                    'low_stock_quantity' => 3,
                    'discount' => 0,
                    'discount_type' => 'percent',
                    'tax' => 0,
                    'tax_type' => 'percent',
                    'shipping_type' => 'flat_rate',
                    'shipping_cost' => 0,
                    'is_quantity_multiplied' => 0,
                    'est_shipping_days' => '2-5',
                    'show_estimated_shipping_time' => 1,
                    'num_of_sale' => 0,
                    'meta_title' => $item['name'],
                    'meta_description' => Str::limit(strip_tags($this->descriptionFor($item)), 155, ''),
                    'meta_keywords' => 'Apple iPhone, ' . $item['name'],
                    'meta_img' => (string) $image->id,
                    'slug' => $slug,
                    'rating' => 4.8,
                    'barcode' => $barcode,
                    'digital' => 0,
                    'auction_product' => 0,
                    'wholesale_product' => 0,
                    'frequently_bought_selection_type' => 'product',
                    'has_warranty' => 0,
                ]);
                $product->save();

                DB::table('product_categories')->updateOrInsert([
                    'product_id' => $product->id,
                    'category_id' => $category->id,
                ]);

                ProductStock::updateOrCreate(
                    ['product_id' => $product->id, 'variant' => ''],
                    [
                        'sku' => 'IPH-' . Str::upper(str_replace('-', '', $slug)),
                        'price' => $item['price'],
                        'qty' => $item['stock'],
                        'image' => null,
                    ]
                );

                ProductTranslation::updateOrCreate(
                    ['product_id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE', 'en')],
                    [
                        'name' => $item['name'],
                        'unit' => 'pc',
                        'description' => $this->descriptionFor($item),
                    ]
                );

                $wasExisting ? $updated++ : $created++;
            }
        });

        $this->info("Seeded iPhone products. Created: {$created}. Updated: {$updated}.");
        return self::SUCCESS;
    }

    /**
     * Delete only products created by this command.
     */
    private function deleteSeededIphones(): void
    {
        $products = Product::where('barcode', 'like', 'IPHONE-SEED-%')->get();

        foreach ($products as $product) {
            DB::table('product_categories')->where('product_id', $product->id)->delete();
            ProductStock::where('product_id', $product->id)->delete();
            ProductTranslation::where('product_id', $product->id)->delete();
            $product->delete();
        }
    }

    /**
     * Create or return the iPhone category.
     */
    private function firstOrCreateCategory(string $name): Category
    {
        $category = Category::where('slug', Str::slug($name))->orWhere('name', $name)->first();

        if (!$category) {
            $category = new Category();
            $category->forceFill([
                'name' => $name,
                'parent_id' => 0,
                'level' => 0,
                'order_level' => 0,
                'commision_rate' => 0,
                'featured' => 1,
                'top' => 1,
                'digital' => 0,
                'slug' => Str::slug($name),
                'meta_title' => $name,
                'meta_description' => 'Apple iPhone products.',
            ]);
            $category->save();
        }

        CategoryTranslation::updateOrCreate(
            ['category_id' => $category->id, 'lang' => env('DEFAULT_LANGUAGE', 'en')],
            ['name' => $name]
        );

        return $category;
    }

    /**
     * Create or return the Apple brand.
     */
    private function firstOrCreateBrand(string $name): Brand
    {
        $brand = Brand::where('slug', Str::slug($name))->orWhere('name', $name)->first();

        if (!$brand) {
            $brand = new Brand();
            $brand->forceFill([
                'name' => $name,
                'top' => 1,
                'slug' => Str::slug($name),
                'meta_title' => $name,
                'meta_description' => 'Apple iPhone product collection.',
            ]);
            $brand->save();
        }

        BrandTranslation::updateOrCreate(
            ['brand_id' => $brand->id, 'lang' => env('DEFAULT_LANGUAGE', 'en')],
            ['name' => $name]
        );

        return $brand;
    }

    /**
     * Create a local SVG image and matching upload record.
     */
    private function imageFor(array $item, int $userId, int $index): Upload
    {
        $fileName = 'iphone_seed_' . Str::slug($item['name']) . '.svg';
        $relativePath = 'uploads/all/' . $fileName;
        $absolutePath = public_path($relativePath);

        if (!is_dir(dirname($absolutePath))) {
            mkdir(dirname($absolutePath), 0755, true);
        }

        file_put_contents($absolutePath, $this->svgFor($item, $index));

        return Upload::updateOrCreate(
            ['file_name' => $relativePath],
            [
                'file_original_name' => $item['name'],
                'user_id' => $userId,
                'extension' => 'svg',
                'type' => 'image',
                'file_size' => filesize($absolutePath) ?: 0,
            ]
        );
    }

    /**
     * Build a clean product SVG for the product gallery.
     */
    private function svgFor(array $item, int $index): string
    {
        $palettes = [
            ['#111827', '#d1d5db', '#f8fafc'],
            ['#1f2937', '#c7b8a5', '#fff7ed'],
            ['#0f172a', '#93c5fd', '#eff6ff'],
            ['#312e81', '#c4b5fd', '#f5f3ff'],
            ['#14532d', '#86efac', '#f0fdf4'],
            ['#7f1d1d', '#fca5a5', '#fff1f2'],
        ];
        [$dark, $accent, $bg] = $palettes[$index % count($palettes)];
        $name = htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8');
        $chip = htmlspecialchars($item['chip'], ENT_QUOTES, 'UTF-8');
        $display = htmlspecialchars($item['display'], ENT_QUOTES, 'UTF-8');

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="900" height="900" viewBox="0 0 900 900">
  <rect width="900" height="900" fill="{$bg}"/>
  <rect x="245" y="92" width="410" height="686" rx="58" fill="{$dark}"/>
  <rect x="268" y="118" width="364" height="632" rx="42" fill="#020617"/>
  <rect x="292" y="152" width="316" height="564" rx="28" fill="{$accent}"/>
  <rect x="390" y="134" width="120" height="22" rx="11" fill="#020617"/>
  <circle cx="550" cy="226" r="38" fill="#f8fafc" opacity="0.95"/>
  <circle cx="550" cy="226" r="20" fill="{$dark}"/>
  <circle cx="455" cy="470" r="132" fill="#ffffff" opacity="0.22"/>
  <text x="450" y="825" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="42" font-weight="700" fill="{$dark}">{$name}</text>
  <text x="450" y="865" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="24" fill="#475569">{$display} &#183; {$chip}</text>
</svg>
SVG;
    }

    /**
     * Build product description HTML.
     */
    private function descriptionFor(array $item): string
    {
        return '<p><strong>' . e($item['name']) . '</strong> is an Apple iPhone model added for product catalog seeding.</p>'
            . '<ul>'
            . '<li>Release year: ' . e((string) $item['year']) . '</li>'
            . '<li>Display: ' . e($item['display']) . '</li>'
            . '<li>Chip: ' . e($item['chip']) . '</li>'
            . '<li>Storage template: ' . e($item['storage']) . '</li>'
            . '</ul>'
            . '<p>Local generated product image included. Stock and prices are seed defaults and can be edited after import.</p>';
    }

    /**
     * iPhone catalog based on Apple's current compare/buy model list.
     */
    private function catalog(): array
    {
        return [
            ['name' => 'iPhone 17 Pro Max', 'year' => 2025, 'display' => '6.9 inches', 'chip' => 'A19 Pro', 'storage' => '256GB / 512GB / 1TB', 'price' => 1199, 'stock' => 24, 'weight' => 0.23],
            ['name' => 'iPhone 17 Pro', 'year' => 2025, 'display' => '6.3 inches', 'chip' => 'A19 Pro', 'storage' => '256GB / 512GB / 1TB', 'price' => 1099, 'stock' => 28, 'weight' => 0.21],
            ['name' => 'iPhone Air', 'year' => 2025, 'display' => '6.5 inches', 'chip' => 'A19 Pro', 'storage' => '256GB / 512GB / 1TB', 'price' => 999, 'stock' => 30, 'weight' => 0.17],
            ['name' => 'iPhone 17', 'year' => 2025, 'display' => '6.3 inches', 'chip' => 'A19', 'storage' => '256GB / 512GB', 'price' => 799, 'stock' => 36, 'weight' => 0.18],
            ['name' => 'iPhone 17e', 'year' => 2026, 'display' => '6.1 inches', 'chip' => 'A18', 'storage' => '128GB / 256GB / 512GB', 'price' => 599, 'stock' => 32, 'weight' => 0.17],
            ['name' => 'iPhone 16 Pro Max', 'year' => 2024, 'display' => '6.9 inches', 'chip' => 'A18 Pro', 'storage' => '256GB / 512GB / 1TB', 'price' => 1099, 'stock' => 22, 'weight' => 0.23],
            ['name' => 'iPhone 16 Pro', 'year' => 2024, 'display' => '6.3 inches', 'chip' => 'A18 Pro', 'storage' => '128GB / 256GB / 512GB / 1TB', 'price' => 999, 'stock' => 22, 'weight' => 0.20],
            ['name' => 'iPhone 16 Plus', 'year' => 2024, 'display' => '6.7 inches', 'chip' => 'A18', 'storage' => '128GB / 256GB / 512GB', 'price' => 799, 'stock' => 30, 'weight' => 0.20],
            ['name' => 'iPhone 16', 'year' => 2024, 'display' => '6.1 inches', 'chip' => 'A18', 'storage' => '128GB / 256GB / 512GB', 'price' => 699, 'stock' => 36, 'weight' => 0.17],
            ['name' => 'iPhone 16e', 'year' => 2025, 'display' => '6.1 inches', 'chip' => 'A18', 'storage' => '128GB / 256GB / 512GB', 'price' => 599, 'stock' => 30, 'weight' => 0.17],
            ['name' => 'iPhone 15 Pro Max', 'year' => 2023, 'display' => '6.7 inches', 'chip' => 'A17 Pro', 'storage' => '256GB / 512GB / 1TB', 'price' => 999, 'stock' => 18, 'weight' => 0.22],
            ['name' => 'iPhone 15 Pro', 'year' => 2023, 'display' => '6.1 inches', 'chip' => 'A17 Pro', 'storage' => '128GB / 256GB / 512GB / 1TB', 'price' => 899, 'stock' => 18, 'weight' => 0.19],
            ['name' => 'iPhone 15 Plus', 'year' => 2023, 'display' => '6.7 inches', 'chip' => 'A16 Bionic', 'storage' => '128GB / 256GB / 512GB', 'price' => 699, 'stock' => 24, 'weight' => 0.20],
            ['name' => 'iPhone 15', 'year' => 2023, 'display' => '6.1 inches', 'chip' => 'A16 Bionic', 'storage' => '128GB / 256GB / 512GB', 'price' => 599, 'stock' => 26, 'weight' => 0.17],
            ['name' => 'iPhone 14 Pro Max', 'year' => 2022, 'display' => '6.7 inches', 'chip' => 'A16 Bionic', 'storage' => '128GB / 256GB / 512GB / 1TB', 'price' => 799, 'stock' => 14, 'weight' => 0.24],
            ['name' => 'iPhone 14 Pro', 'year' => 2022, 'display' => '6.1 inches', 'chip' => 'A16 Bionic', 'storage' => '128GB / 256GB / 512GB / 1TB', 'price' => 699, 'stock' => 16, 'weight' => 0.21],
            ['name' => 'iPhone 14 Plus', 'year' => 2022, 'display' => '6.7 inches', 'chip' => 'A15 Bionic', 'storage' => '128GB / 256GB / 512GB', 'price' => 599, 'stock' => 20, 'weight' => 0.20],
            ['name' => 'iPhone 14', 'year' => 2022, 'display' => '6.1 inches', 'chip' => 'A15 Bionic', 'storage' => '128GB / 256GB / 512GB', 'price' => 499, 'stock' => 24, 'weight' => 0.17],
            ['name' => 'iPhone 13 Pro Max', 'year' => 2021, 'display' => '6.7 inches', 'chip' => 'A15 Bionic', 'storage' => '128GB / 256GB / 512GB / 1TB', 'price' => 699, 'stock' => 12, 'weight' => 0.24],
            ['name' => 'iPhone 13 Pro', 'year' => 2021, 'display' => '6.1 inches', 'chip' => 'A15 Bionic', 'storage' => '128GB / 256GB / 512GB / 1TB', 'price' => 599, 'stock' => 12, 'weight' => 0.20],
            ['name' => 'iPhone 13 mini', 'year' => 2021, 'display' => '5.4 inches', 'chip' => 'A15 Bionic', 'storage' => '128GB / 256GB / 512GB', 'price' => 399, 'stock' => 16, 'weight' => 0.14],
            ['name' => 'iPhone 13', 'year' => 2021, 'display' => '6.1 inches', 'chip' => 'A15 Bionic', 'storage' => '128GB / 256GB / 512GB', 'price' => 449, 'stock' => 22, 'weight' => 0.17],
            ['name' => 'iPhone SE (3rd generation)', 'year' => 2022, 'display' => '4.7 inches', 'chip' => 'A15 Bionic', 'storage' => '64GB / 128GB / 256GB', 'price' => 299, 'stock' => 20, 'weight' => 0.14],
            ['name' => 'iPhone 12 Pro Max', 'year' => 2020, 'display' => '6.7 inches', 'chip' => 'A14 Bionic', 'storage' => '128GB / 256GB / 512GB', 'price' => 549, 'stock' => 10, 'weight' => 0.23],
            ['name' => 'iPhone 12 Pro', 'year' => 2020, 'display' => '6.1 inches', 'chip' => 'A14 Bionic', 'storage' => '128GB / 256GB / 512GB', 'price' => 499, 'stock' => 10, 'weight' => 0.19],
            ['name' => 'iPhone 12 mini', 'year' => 2020, 'display' => '5.4 inches', 'chip' => 'A14 Bionic', 'storage' => '64GB / 128GB / 256GB', 'price' => 299, 'stock' => 14, 'weight' => 0.14],
            ['name' => 'iPhone 12', 'year' => 2020, 'display' => '6.1 inches', 'chip' => 'A14 Bionic', 'storage' => '64GB / 128GB / 256GB', 'price' => 349, 'stock' => 18, 'weight' => 0.16],
            ['name' => 'iPhone 11 Pro Max', 'year' => 2019, 'display' => '6.5 inches', 'chip' => 'A13 Bionic', 'storage' => '64GB / 256GB / 512GB', 'price' => 449, 'stock' => 8, 'weight' => 0.23],
            ['name' => 'iPhone 11 Pro', 'year' => 2019, 'display' => '5.8 inches', 'chip' => 'A13 Bionic', 'storage' => '64GB / 256GB / 512GB', 'price' => 399, 'stock' => 8, 'weight' => 0.19],
            ['name' => 'iPhone 11', 'year' => 2019, 'display' => '6.1 inches', 'chip' => 'A13 Bionic', 'storage' => '64GB / 128GB / 256GB', 'price' => 299, 'stock' => 16, 'weight' => 0.19],
            ['name' => 'iPhone SE (2nd generation)', 'year' => 2020, 'display' => '4.7 inches', 'chip' => 'A13 Bionic', 'storage' => '64GB / 128GB / 256GB', 'price' => 199, 'stock' => 16, 'weight' => 0.15],
            ['name' => 'iPhone XS Max', 'year' => 2018, 'display' => '6.5 inches', 'chip' => 'A12 Bionic', 'storage' => '64GB / 256GB / 512GB', 'price' => 299, 'stock' => 6, 'weight' => 0.21],
            ['name' => 'iPhone XS', 'year' => 2018, 'display' => '5.8 inches', 'chip' => 'A12 Bionic', 'storage' => '64GB / 256GB / 512GB', 'price' => 249, 'stock' => 6, 'weight' => 0.18],
            ['name' => 'iPhone XR', 'year' => 2018, 'display' => '6.1 inches', 'chip' => 'A12 Bionic', 'storage' => '64GB / 128GB / 256GB', 'price' => 229, 'stock' => 10, 'weight' => 0.19],
            ['name' => 'iPhone X', 'year' => 2017, 'display' => '5.8 inches', 'chip' => 'A11 Bionic', 'storage' => '64GB / 256GB', 'price' => 229, 'stock' => 6, 'weight' => 0.17],
            ['name' => 'iPhone 8 Plus', 'year' => 2017, 'display' => '5.5 inches', 'chip' => 'A11 Bionic', 'storage' => '64GB / 256GB', 'price' => 199, 'stock' => 8, 'weight' => 0.20],
            ['name' => 'iPhone 8', 'year' => 2017, 'display' => '4.7 inches', 'chip' => 'A11 Bionic', 'storage' => '64GB / 256GB', 'price' => 179, 'stock' => 8, 'weight' => 0.15],
            ['name' => 'iPhone 7 Plus', 'year' => 2016, 'display' => '5.5 inches', 'chip' => 'A10 Fusion', 'storage' => '32GB / 128GB / 256GB', 'price' => 159, 'stock' => 6, 'weight' => 0.19],
            ['name' => 'iPhone 7', 'year' => 2016, 'display' => '4.7 inches', 'chip' => 'A10 Fusion', 'storage' => '32GB / 128GB / 256GB', 'price' => 129, 'stock' => 6, 'weight' => 0.14],
            ['name' => 'iPhone SE (1st generation)', 'year' => 2016, 'display' => '4.0 inches', 'chip' => 'A9', 'storage' => '16GB / 32GB / 64GB / 128GB', 'price' => 119, 'stock' => 5, 'weight' => 0.11],
            ['name' => 'iPhone 6s Plus', 'year' => 2015, 'display' => '5.5 inches', 'chip' => 'A9', 'storage' => '16GB / 32GB / 64GB / 128GB', 'price' => 119, 'stock' => 5, 'weight' => 0.19],
            ['name' => 'iPhone 6s', 'year' => 2015, 'display' => '4.7 inches', 'chip' => 'A9', 'storage' => '16GB / 32GB / 64GB / 128GB', 'price' => 109, 'stock' => 5, 'weight' => 0.14],
            ['name' => 'iPhone 6 Plus', 'year' => 2014, 'display' => '5.5 inches', 'chip' => 'A8', 'storage' => '16GB / 64GB / 128GB', 'price' => 99, 'stock' => 4, 'weight' => 0.17],
            ['name' => 'iPhone 6', 'year' => 2014, 'display' => '4.7 inches', 'chip' => 'A8', 'storage' => '16GB / 32GB / 64GB / 128GB', 'price' => 89, 'stock' => 4, 'weight' => 0.13],
            ['name' => 'iPhone 5s', 'year' => 2013, 'display' => '4.0 inches', 'chip' => 'A7', 'storage' => '16GB / 32GB / 64GB', 'price' => 79, 'stock' => 4, 'weight' => 0.11],
            ['name' => 'iPhone 5c', 'year' => 2013, 'display' => '4.0 inches', 'chip' => 'A6', 'storage' => '8GB / 16GB / 32GB', 'price' => 69, 'stock' => 4, 'weight' => 0.13],
            ['name' => 'iPhone 5', 'year' => 2012, 'display' => '4.0 inches', 'chip' => 'A6', 'storage' => '16GB / 32GB / 64GB', 'price' => 69, 'stock' => 4, 'weight' => 0.11],
            ['name' => 'iPhone 4s', 'year' => 2011, 'display' => '3.5 inches', 'chip' => 'A5', 'storage' => '8GB / 16GB / 32GB / 64GB', 'price' => 59, 'stock' => 3, 'weight' => 0.14],
            ['name' => 'iPhone 4', 'year' => 2010, 'display' => '3.5 inches', 'chip' => 'A4', 'storage' => '8GB / 16GB / 32GB', 'price' => 49, 'stock' => 3, 'weight' => 0.14],
            ['name' => 'iPhone 3GS', 'year' => 2009, 'display' => '3.5 inches', 'chip' => 'Samsung S5PC100', 'storage' => '8GB / 16GB / 32GB', 'price' => 39, 'stock' => 2, 'weight' => 0.14],
            ['name' => 'iPhone 3G', 'year' => 2008, 'display' => '3.5 inches', 'chip' => 'Samsung 32-bit RISC ARM', 'storage' => '8GB / 16GB', 'price' => 35, 'stock' => 2, 'weight' => 0.13],
            ['name' => 'iPhone (1st generation)', 'year' => 2007, 'display' => '3.5 inches', 'chip' => 'Samsung 32-bit RISC ARM', 'storage' => '4GB / 8GB / 16GB', 'price' => 35, 'stock' => 2, 'weight' => 0.14],
        ];
    }
}
