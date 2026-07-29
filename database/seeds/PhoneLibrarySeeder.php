<?php

use App\Repositories\PhoneLibraryRepository;
use Illuminate\Database\Seeder;

/**
 * Seeds starter mobile phone library data without creating products or inventory.
 */
class PhoneLibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $repository = app(PhoneLibraryRepository::class);

        foreach ($this->phones() as $phone) {
            $repository->upsertModel($phone);
        }
    }

    /**
     * Get starter phone data for known released models.
     */
    private function phones(): array
    {
        return [
            [
                'brand' => ['name' => 'Apple', 'country' => 'United States', 'website' => 'https://www.apple.com'],
                'model_name' => 'iPhone 15 Pro Max',
                'marketing_name' => 'iPhone 15 Pro Max',
                'year_released' => 2023,
                'model_number' => 'A2849/A3105/A3106/A3108',
                'category' => 'Smartphones',
                'status' => 'active',
                'description' => 'Apple flagship phone with A17 Pro, titanium design, Pro camera system, and USB-C.',
                'specification' => [
                    'display_size' => '6.7 inches',
                    'display_resolution' => '2796 x 1290',
                    'refresh_rate' => '120Hz',
                    'brightness' => 'Up to 2000 nits outdoor',
                    'display_protection' => 'Ceramic Shield',
                    'chipset' => 'Apple A17 Pro',
                    'cpu' => 'Hexa-core',
                    'gpu' => 'Apple 6-core GPU',
                    'ram' => '8GB',
                    'storage' => '256GB / 512GB / 1TB',
                    'rear_cameras' => ['48MP wide', '12MP ultra wide', '12MP 5x telephoto'],
                    'front_camera' => '12MP TrueDepth',
                    'video_recording' => '4K up to 60fps',
                    'battery_capacity' => '4441 mAh',
                    'charging_speed' => 'USB-C fast charging',
                    'wireless_charging' => true,
                    'reverse_charging' => false,
                    'has_5g' => true,
                    'wifi' => 'Wi-Fi 6E',
                    'bluetooth' => '5.3',
                    'nfc' => true,
                    'usb_type' => 'USB-C',
                    'operating_system' => 'iOS',
                    'dimensions' => '159.9 x 76.7 x 8.25 mm',
                    'weight' => '221 g',
                    'sim_type' => 'Nano SIM / eSIM',
                    'water_resistance' => 'IP68',
                    'color_options' => ['Black Titanium', 'White Titanium', 'Blue Titanium', 'Natural Titanium'],
                    'warranty' => '1 year limited warranty',
                ],
                'variants' => $this->variants('APL-IP15PM', ['256GB', '512GB', '1TB'], ['Black Titanium', 'White Titanium', 'Natural Titanium']),
            ],
            [
                'brand' => ['name' => 'Samsung', 'country' => 'South Korea', 'website' => 'https://www.samsung.com'],
                'model_name' => 'Galaxy S24 Ultra',
                'marketing_name' => 'Samsung Galaxy S24 Ultra',
                'year_released' => 2024,
                'model_number' => 'SM-S928',
                'category' => 'Smartphones',
                'status' => 'active',
                'description' => 'Samsung flagship phone with S Pen, Galaxy AI features, and quad camera system.',
                'specification' => [
                    'display_size' => '6.8 inches',
                    'display_resolution' => '3120 x 1440',
                    'refresh_rate' => '120Hz',
                    'brightness' => 'Up to 2600 nits',
                    'display_protection' => 'Corning Gorilla Armor',
                    'chipset' => 'Snapdragon 8 Gen 3 for Galaxy',
                    'cpu' => 'Octa-core',
                    'gpu' => 'Adreno 750',
                    'ram' => '12GB',
                    'storage' => '256GB / 512GB / 1TB',
                    'rear_cameras' => ['200MP wide', '12MP ultra wide', '50MP periscope telephoto', '10MP telephoto'],
                    'front_camera' => '12MP',
                    'video_recording' => '8K up to 30fps',
                    'battery_capacity' => '5000 mAh',
                    'charging_speed' => '45W wired',
                    'wireless_charging' => true,
                    'reverse_charging' => true,
                    'has_5g' => true,
                    'wifi' => 'Wi-Fi 7',
                    'bluetooth' => '5.3',
                    'nfc' => true,
                    'usb_type' => 'USB-C',
                    'operating_system' => 'Android',
                    'dimensions' => '162.3 x 79.0 x 8.6 mm',
                    'weight' => '232 g',
                    'sim_type' => 'Nano SIM / eSIM',
                    'water_resistance' => 'IP68',
                    'color_options' => ['Titanium Black', 'Titanium Gray', 'Titanium Violet', 'Titanium Yellow'],
                    'warranty' => '1 year limited warranty',
                ],
                'variants' => $this->variants('SMS-S24U', ['256GB', '512GB', '1TB'], ['Titanium Black', 'Titanium Gray', 'Titanium Violet']),
            ],
            [
                'brand' => ['name' => 'Google', 'country' => 'United States', 'website' => 'https://store.google.com'],
                'model_name' => 'Pixel 8 Pro',
                'marketing_name' => 'Google Pixel 8 Pro',
                'year_released' => 2023,
                'model_number' => 'GC3VE/G1MNW',
                'category' => 'Smartphones',
                'status' => 'active',
                'description' => 'Google flagship phone with Tensor G3, advanced camera tools, and long software support.',
                'specification' => [
                    'display_size' => '6.7 inches',
                    'display_resolution' => '2992 x 1344',
                    'refresh_rate' => '120Hz',
                    'brightness' => 'Up to 2400 nits peak',
                    'display_protection' => 'Gorilla Glass Victus 2',
                    'chipset' => 'Google Tensor G3',
                    'cpu' => 'Nona-core',
                    'gpu' => 'Immortalis-G715s MC10',
                    'ram' => '12GB',
                    'storage' => '128GB / 256GB / 512GB / 1TB',
                    'rear_cameras' => ['50MP wide', '48MP ultra wide', '48MP telephoto'],
                    'front_camera' => '10.5MP',
                    'video_recording' => '4K up to 60fps',
                    'battery_capacity' => '5050 mAh',
                    'charging_speed' => '30W wired',
                    'wireless_charging' => true,
                    'reverse_charging' => true,
                    'has_5g' => true,
                    'wifi' => 'Wi-Fi 7',
                    'bluetooth' => '5.3',
                    'nfc' => true,
                    'usb_type' => 'USB-C',
                    'operating_system' => 'Android',
                    'dimensions' => '162.6 x 76.5 x 8.8 mm',
                    'weight' => '213 g',
                    'sim_type' => 'Nano SIM / eSIM',
                    'water_resistance' => 'IP68',
                    'color_options' => ['Obsidian', 'Porcelain', 'Bay'],
                    'warranty' => '1 year limited warranty',
                ],
                'variants' => $this->variants('GOO-P8P', ['128GB', '256GB', '512GB'], ['Obsidian', 'Porcelain', 'Bay']),
            ],
            [
                'brand' => ['name' => 'OnePlus', 'country' => 'China', 'website' => 'https://www.oneplus.com'],
                'model_name' => 'OnePlus 12',
                'marketing_name' => 'OnePlus 12',
                'year_released' => 2023,
                'model_number' => 'CPH2581/PJD110',
                'category' => 'Smartphones',
                'status' => 'active',
                'description' => 'OnePlus flagship phone with Snapdragon 8 Gen 3, Hasselblad cameras, and fast charging.',
                'specification' => [
                    'display_size' => '6.82 inches',
                    'display_resolution' => '3168 x 1440',
                    'refresh_rate' => '120Hz',
                    'brightness' => 'Up to 4500 nits peak',
                    'display_protection' => 'Gorilla Glass Victus 2',
                    'chipset' => 'Snapdragon 8 Gen 3',
                    'cpu' => 'Octa-core',
                    'gpu' => 'Adreno 750',
                    'ram' => '12GB / 16GB',
                    'storage' => '256GB / 512GB',
                    'rear_cameras' => ['50MP wide', '64MP periscope telephoto', '48MP ultra wide'],
                    'front_camera' => '32MP',
                    'video_recording' => '8K up to 24fps',
                    'battery_capacity' => '5400 mAh',
                    'charging_speed' => '100W wired / 80W wired market-dependent',
                    'wireless_charging' => true,
                    'reverse_charging' => true,
                    'has_5g' => true,
                    'wifi' => 'Wi-Fi 7',
                    'bluetooth' => '5.4',
                    'nfc' => true,
                    'usb_type' => 'USB-C',
                    'operating_system' => 'Android',
                    'dimensions' => '164.3 x 75.8 x 9.2 mm',
                    'weight' => '220 g',
                    'sim_type' => 'Nano SIM',
                    'water_resistance' => 'IP65',
                    'color_options' => ['Flowy Emerald', 'Silky Black'],
                    'warranty' => '1 year limited warranty',
                ],
                'variants' => $this->variants('ONE-12', ['256GB', '512GB'], ['Flowy Emerald', 'Silky Black']),
            ],
            [
                'brand' => ['name' => 'Nothing', 'country' => 'United Kingdom', 'website' => 'https://nothing.tech'],
                'model_name' => 'Phone (2)',
                'marketing_name' => 'Nothing Phone (2)',
                'year_released' => 2023,
                'model_number' => 'A065',
                'category' => 'Smartphones',
                'status' => 'active',
                'description' => 'Nothing Phone with Glyph Interface, Snapdragon 8+ Gen 1, and transparent design.',
                'specification' => [
                    'display_size' => '6.7 inches',
                    'display_resolution' => '2412 x 1080',
                    'refresh_rate' => '120Hz',
                    'brightness' => 'Up to 1600 nits peak',
                    'display_protection' => 'Gorilla Glass',
                    'chipset' => 'Snapdragon 8+ Gen 1',
                    'cpu' => 'Octa-core',
                    'gpu' => 'Adreno 730',
                    'ram' => '8GB / 12GB',
                    'storage' => '128GB / 256GB / 512GB',
                    'rear_cameras' => ['50MP wide', '50MP ultra wide'],
                    'front_camera' => '32MP',
                    'video_recording' => '4K up to 60fps',
                    'battery_capacity' => '4700 mAh',
                    'charging_speed' => '45W wired',
                    'wireless_charging' => true,
                    'reverse_charging' => true,
                    'has_5g' => true,
                    'wifi' => 'Wi-Fi 6',
                    'bluetooth' => '5.3',
                    'nfc' => true,
                    'usb_type' => 'USB-C',
                    'operating_system' => 'Android',
                    'dimensions' => '162.1 x 76.4 x 8.6 mm',
                    'weight' => '201.2 g',
                    'sim_type' => 'Nano SIM',
                    'water_resistance' => 'IP54',
                    'color_options' => ['White', 'Dark Gray'],
                    'warranty' => '1 year limited warranty',
                ],
                'variants' => $this->variants('NTH-P2', ['128GB', '256GB', '512GB'], ['White', 'Dark Gray']),
            ],
        ];
    }

    /**
     * Build simple variant templates.
     */
    private function variants(string $prefix, array $storages, array $colors): array
    {
        $variants = [];

        foreach ($storages as $storage) {
            foreach ($colors as $color) {
                $code = strtoupper(str_replace([' ', '(', ')'], ['', '', ''], $storage . '-' . $color));
                $variants[] = [
                    'color' => $color,
                    'storage' => $storage,
                    'sku_template' => $prefix . '-' . $code,
                    'barcode_template' => $prefix . '-' . $code,
                    'currency' => 'USD',
                    'is_active' => true,
                ];
            }
        }

        return $variants;
    }
}
