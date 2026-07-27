<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductTranslation;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateRealisticProducts extends Command
{
    protected $signature = 'products:generate-realistic {count=24 : Number of products to create} {--fresh : Delete previously generated products first}';

    protected $description = 'Generate realistic demo products for local testing and storefront population.';

    private array $catalog = [
        ['Wireless Noise Cancelling Headphones', 'Electronics', 'SoundCore', 89, 169, ['bluetooth', 'audio', 'travel']],
        ['Smart Fitness Watch Pro', 'Electronics', 'FitPulse', 59, 139, ['fitness', 'watch', 'health']],
        ['USB-C Fast Charging Power Bank 20000mAh', 'Electronics', 'Voltix', 29, 69, ['charger', 'mobile', 'power']],
        ['4K Action Camera Waterproof Kit', 'Electronics', 'PeakView', 79, 189, ['camera', 'outdoor', 'video']],
        ['Slim Laptop Backpack with USB Port', 'Fashion', 'UrbanTrail', 24, 64, ['bag', 'travel', 'office']],
        ['Premium Cotton Oversized T-Shirt', 'Fashion', 'NorthLoom', 12, 34, ['cotton', 'streetwear', 'casual']],
        ['Classic Leather Wallet RFID Blocking', 'Fashion', 'Oak & Vale', 18, 49, ['wallet', 'leather', 'gift']],
        ['Lightweight Running Shoes', 'Fashion', 'StrideLab', 39, 95, ['running', 'shoes', 'sport']],
        ['Ceramic Non-Stick Cookware Set', 'Home & Kitchen', 'CasaCraft', 49, 129, ['cookware', 'kitchen', 'home']],
        ['Stainless Steel Insulated Water Bottle', 'Home & Kitchen', 'HydraNest', 11, 32, ['bottle', 'travel', 'steel']],
        ['Modern LED Desk Lamp with Dimmer', 'Home & Kitchen', 'LumaDesk', 22, 58, ['lamp', 'desk', 'office']],
        ['Memory Foam Pillow for Neck Support', 'Home & Kitchen', 'SleepWell', 19, 54, ['pillow', 'sleep', 'comfort']],
        ['Vitamin C Brightening Face Serum', 'Beauty', 'GlowTheory', 14, 42, ['skincare', 'serum', 'beauty']],
        ['Hydrating Matte Lipstick Set', 'Beauty', 'VelvetBloom', 10, 28, ['makeup', 'lipstick', 'beauty']],
        ['Argan Oil Repair Shampoo', 'Beauty', 'PureRoot', 8, 24, ['haircare', 'shampoo', 'repair']],
        ['Daily Moisturizing Sunscreen SPF 50', 'Beauty', 'SunKind', 9, 26, ['sunscreen', 'skincare', 'daily']],
        ['Adjustable Dumbbell Pair 20kg', 'Sports', 'IronFlex', 55, 145, ['fitness', 'weights', 'home gym']],
        ['Eco Yoga Mat with Carry Strap', 'Sports', 'ZenForm', 16, 46, ['yoga', 'fitness', 'eco']],
        ['Camping Lantern Rechargeable', 'Sports', 'TrailBeam', 17, 44, ['camping', 'outdoor', 'light']],
        ['Insulated Lunch Bag for Work', 'Home & Kitchen', 'MealMate', 13, 35, ['lunch', 'work', 'storage']],
    ];

    public function handle(): int
    {
        $count = max(1, (int) $this->argument('count'));

        if ($this->option('fresh')) {
            $this->deleteGeneratedProducts();
        }

        $admin = User::where('user_type', 'admin')->first();
        if (!$admin) {
            $this->error('No admin user found. Create/import users before generating products.');
            return self::FAILURE;
        }

        $created = 0;
        $imageSets = $this->localProductImageSets();

        for ($i = 0; $i < $count; $i++) {
            $template = $this->catalog[$i % count($this->catalog)];
            $variant = intdiv($i, count($this->catalog)) + 1;
            $name = $variant > 1 ? $template[0] . ' ' . $variant : $template[0];
            $category = $this->firstOrCreateCategory($template[1]);
            $brand = $this->firstOrCreateBrand($template[2]);
            $price = random_int($template[3] * 100, $template[4] * 100) / 100;
            $stock = random_int(18, 180);
            $tags = implode(',', $template[5]);
            $description = $this->descriptionFor($name, $template[1], $brand->name);
            $slugBase = Str::slug($name);
            $slug = $slugBase . '-' . Str::lower(Str::random(6));
            $images = $this->imageIdsFor($imageSets[$i % count($imageSets)] ?? [], $admin->id);

            $product = Product::create([
                'name' => $name,
                'added_by' => 'admin',
                'user_id' => $admin->id,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'photos' => $images['photos'],
                'thumbnail_img' => $images['thumbnail'],
                'tags' => $tags,
                'description' => $description,
                'unit_price' => $price,
                'purchase_price' => round($price * 0.68, 2),
                'variant_product' => 0,
                'attributes' => '[]',
                'choice_options' => '[]',
                'colors' => '[]',
                'todays_deal' => $i % 7 === 0 ? 1 : 0,
                'published' => 1,
                'draft' => 0,
                'approved' => 1,
                'stock_visibility_state' => 'quantity',
                'cash_on_delivery' => 1,
                'featured' => $i % 5 === 0 ? 1 : 0,
                'current_stock' => $stock,
                'unit' => 'pc',
                'weight' => random_int(20, 250) / 100,
                'min_qty' => 1,
                'low_stock_quantity' => 5,
                'discount' => $i % 4 === 0 ? random_int(5, 20) : 0,
                'discount_type' => 'percent',
                'tax' => 0,
                'tax_type' => 'percent',
                'shipping_type' => 'flat_rate',
                'shipping_cost' => random_int(0, 6),
                'is_quantity_multiplied' => 0,
                'est_shipping_days' => (string) random_int(2, 7),
                'show_estimated_shipping_time' => 1,
                'num_of_sale' => random_int(0, 35),
                'meta_title' => $name,
                'meta_description' => Str::limit(strip_tags($description), 155, ''),
                'meta_keywords' => $tags,
                'meta_img' => $images['thumbnail'],
                'slug' => $slug,
                'rating' => random_int(38, 50) / 10,
                'barcode' => 'SKU-' . Str::upper(Str::random(10)),
                'digital' => 0,
                'auction_product' => 0,
                'wholesale_product' => 0,
                'frequently_bought_selection_type' => 'product',
                'has_warranty' => 0,
            ]);

            DB::table('product_categories')->insert([
                'product_id' => $product->id,
                'category_id' => $category->id,
            ]);

            ProductStock::create([
                'product_id' => $product->id,
                'variant' => '',
                'sku' => 'REAL-' . Str::upper(Str::random(8)),
                'price' => $price,
                'qty' => $stock,
                'image' => null,
            ]);

            ProductTranslation::create([
                'product_id' => $product->id,
                'name' => $name,
                'unit' => 'pc',
                'description' => $description,
                'lang' => env('DEFAULT_LANGUAGE', 'en'),
            ]);

            $created++;
        }

        $this->info("Created {$created} realistic products.");
        return self::SUCCESS;
    }

    private function deleteGeneratedProducts(): void
    {
        $products = Product::where('barcode', 'like', 'SKU-%')->get();

        foreach ($products as $product) {
            DB::table('product_categories')->where('product_id', $product->id)->delete();
            ProductStock::where('product_id', $product->id)->delete();
            ProductTranslation::where('product_id', $product->id)->delete();
            $product->delete();
        }
    }

    private function firstOrCreateCategory(string $name): Category
    {
        $category = Category::where('name', $name)->first();

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
                'meta_description' => "Shop {$name} products.",
            ]);
            $category->save();
        }

        return $category;
    }

    private function firstOrCreateBrand(string $name): Brand
    {
        $brand = Brand::where('name', $name)->first();

        if (!$brand) {
            $brand = new Brand();
            $brand->forceFill([
                'name' => $name,
                'top' => 1,
                'slug' => Str::slug($name),
                'meta_title' => $name,
                'meta_description' => "{$name} product collection.",
            ]);
            $brand->save();
        }

        return $brand;
    }

    private function localProductImageSets(): array
    {
        $sets = [];

        foreach (glob(public_path('uploads/all/product_*_thumb.*')) ?: [] as $thumbPath) {
            if (!preg_match('/product_(\d+)_thumb\.[a-z0-9]+$/i', basename($thumbPath), $matches)) {
                continue;
            }

            $number = $matches[1];
            $paths = [$thumbPath];
            $paths = array_merge($paths, glob(public_path("uploads/all/product_{$number}_main.*")) ?: []);
            $paths = array_merge($paths, glob(public_path("uploads/all/product_{$number}_gal*.*")) ?: []);

            $sets[] = array_values(array_unique(array_filter($paths)));
        }

        return $sets ?: [[]];
    }

    private function imageIdsFor(array $paths, int $userId): array
    {
        $ids = [];

        foreach ($paths as $path) {
            $upload = $this->uploadForPath($path, $userId);
            if ($upload) {
                $ids[] = $upload->id;
            }
        }

        return [
            'thumbnail' => $ids[0] ?? null,
            'photos' => !empty($ids) ? implode(',', $ids) : null,
        ];
    }

    private function uploadForPath(string $path, int $userId): ?Upload
    {
        if (!is_file($path)) {
            return null;
        }

        $publicPath = rtrim(str_replace('\\', '/', public_path()), '/') . '/';
        $normalizedPath = str_replace('\\', '/', $path);
        $relativePath = ltrim(Str::after($normalizedPath, $publicPath), '/');
        $upload = Upload::where('file_name', $relativePath)->first();

        if ($upload) {
            return $upload;
        }

        return Upload::create([
            'file_original_name' => pathinfo($path, PATHINFO_FILENAME),
            'file_name' => $relativePath,
            'user_id' => $userId,
            'extension' => strtolower(pathinfo($path, PATHINFO_EXTENSION)),
            'type' => 'image',
            'file_size' => filesize($path) ?: 0,
        ]);
    }

    private function descriptionFor(string $name, string $category, string $brand): string
    {
        return "<p>The {$name} from {$brand} is built for everyday use with a clean design, dependable materials, and practical details customers can feel right away.</p>"
            . "<ul>"
            . "<li>Carefully selected for the {$category} category</li>"
            . "<li>Comfortable, durable, and easy to use</li>"
            . "<li>Suitable for gifting, daily shopping, and repeat orders</li>"
            . "</ul>"
            . "<p>Each item includes quality packaging and is ready for fast local delivery.</p>";
    }
}
