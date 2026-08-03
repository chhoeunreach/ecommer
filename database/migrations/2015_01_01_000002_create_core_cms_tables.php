<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // This legacy application's core CMS tables (products, categories, etc.)
    // use signed INT primary keys, not Laravel's default bigint unsigned —
    // see database/migrations/2026_08_02_000003_create_product_free_accessories_tables.php.
    public function up(): void
    {
        if (! Schema::hasTable('uploads')) {
            Schema::create('uploads', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('file_original_name')->nullable();
                $table->string('file_name');
                $table->unsignedInteger('user_id')->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->string('extension')->nullable();
                $table->string('type')->nullable();
                $table->string('external_link')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('parent_id')->default(0);
                $table->unsignedInteger('level')->default(0);
                $table->string('name');
                $table->integer('order_level')->default(0);
                $table->decimal('commision_rate', 8, 2)->nullable();
                $table->integer('banner')->nullable();
                $table->integer('icon')->nullable();
                $table->integer('cover_image')->nullable();
                $table->boolean('featured')->default(0);
                $table->boolean('top')->default(0);
                $table->boolean('digital')->default(0);
                $table->decimal('discount', 20, 2)->nullable();
                $table->date('discount_start_date')->nullable();
                $table->date('discount_end_date')->nullable();
                $table->string('slug')->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('category_translations')) {
            Schema::create('category_translations', function (Blueprint $table) {
                $table->id();
                $table->integer('category_id');
                $table->string('name')->nullable();
                $table->string('lang', 10);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('brands')) {
            Schema::create('brands', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('name');
                $table->integer('logo')->nullable();
                $table->boolean('top')->default(0);
                $table->string('slug')->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('meta_keywords')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('brand_translations')) {
            Schema::create('brand_translations', function (Blueprint $table) {
                $table->id();
                $table->integer('brand_id');
                $table->string('name')->nullable();
                $table->string('lang', 10);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('shops')) {
            Schema::create('shops', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->unsignedInteger('user_id');
                $table->string('name')->nullable();
                $table->integer('logo')->nullable();
                $table->text('sliders')->nullable();
                $table->integer('top_banner')->nullable();
                $table->integer('banner_full_width_1')->nullable();
                $table->text('banners_half_width')->nullable();
                $table->integer('banner_full_width_2')->nullable();
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->decimal('rating', 8, 2)->default(0);
                $table->integer('num_of_reviews')->default(0);
                $table->integer('num_of_sale')->default(0);
                $table->integer('seller_package_id')->nullable();
                $table->integer('product_upload_limit')->default(0);
                $table->timestamp('package_invalid_at')->nullable();
                $table->boolean('verification_status')->default(0);
                $table->text('verification_info')->nullable();
                $table->boolean('cash_on_delivery_status')->default(1);
                $table->decimal('admin_to_pay', 20, 2)->default(0);
                $table->string('facebook')->nullable();
                $table->string('instagram')->nullable();
                $table->string('google')->nullable();
                $table->string('twitter')->nullable();
                $table->string('youtube')->nullable();
                $table->string('slug')->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->integer('pick_up_point_id')->nullable();
                $table->decimal('shipping_cost', 20, 2)->default(0);
                $table->string('delivery_pickup_latitude')->nullable();
                $table->string('delivery_pickup_longitude')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('bank_acc_name')->nullable();
                $table->string('bank_acc_no')->nullable();
                $table->string('bank_routing_no')->nullable();
                $table->boolean('bank_payment_status')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('blog_categories')) {
            Schema::create('blog_categories', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('category_name');
                $table->string('slug')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('blogs')) {
            Schema::create('blogs', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('category_id')->nullable();
                $table->string('title');
                $table->string('slug')->nullable();
                $table->text('short_description')->nullable();
                $table->longText('description')->nullable();
                $table->integer('banner')->nullable();
                $table->string('meta_title')->nullable();
                $table->integer('meta_img')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('meta_keywords')->nullable();
                $table->boolean('status')->default(1);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('flash_deals')) {
            Schema::create('flash_deals', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('title')->nullable();
                $table->bigInteger('start_date')->nullable();
                $table->bigInteger('end_date')->nullable();
                $table->boolean('status')->default(1);
                $table->boolean('featured')->default(0);
                $table->string('background_color')->nullable();
                $table->string('text_color')->nullable();
                $table->integer('banner')->nullable();
                $table->string('slug')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('flash_deal_translations')) {
            Schema::create('flash_deal_translations', function (Blueprint $table) {
                $table->id();
                $table->integer('flash_deal_id');
                $table->string('title')->nullable();
                $table->string('lang', 10);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('name');
                $table->string('added_by')->nullable();
                $table->unsignedInteger('user_id')->nullable();
                $table->integer('category_id')->nullable();
                $table->integer('brand_id')->nullable();
                $table->text('photos')->nullable();
                $table->integer('thumbnail_img')->nullable();
                $table->string('video_provider')->nullable();
                $table->text('video_link')->nullable();
                $table->text('tags')->nullable();
                $table->longText('description')->nullable();
                $table->decimal('unit_price', 20, 2)->default(0);
                $table->decimal('purchase_price', 20, 2)->nullable();
                $table->boolean('variant_product')->default(0);
                $table->text('attributes')->nullable();
                $table->text('choice_options')->nullable();
                $table->text('colors')->nullable();
                $table->text('variations')->nullable();
                $table->boolean('todays_deal')->default(0);
                $table->boolean('published')->default(1);
                $table->boolean('approved')->default(1);
                $table->string('stock_visibility_state')->default('quantity');
                $table->boolean('cash_on_delivery')->default(1);
                $table->boolean('featured')->default(0);
                $table->boolean('seller_featured')->default(0);
                $table->integer('current_stock')->default(0);
                $table->string('unit')->nullable();
                $table->decimal('weight', 8, 2)->nullable();
                $table->integer('min_qty')->default(1);
                $table->integer('low_stock_quantity')->nullable();
                $table->decimal('discount', 20, 2)->default(0);
                $table->string('discount_type')->nullable();
                $table->bigInteger('discount_start_date')->nullable();
                $table->bigInteger('discount_end_date')->nullable();
                $table->decimal('tax', 20, 2)->nullable();
                $table->string('tax_type')->nullable();
                $table->string('shipping_type')->nullable();
                $table->decimal('shipping_cost', 20, 2)->default(0);
                $table->boolean('is_quantity_multiplied')->default(0);
                $table->integer('est_shipping_days')->nullable();
                $table->integer('num_of_sale')->default(0);
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->integer('meta_img')->nullable();
                $table->string('pdf')->nullable();
                $table->string('slug')->nullable();
                $table->decimal('rating', 8, 2)->default(0);
                $table->string('barcode')->nullable();
                $table->boolean('digital')->default(0);
                $table->boolean('auction_product')->default(0);
                $table->string('file_name')->nullable();
                $table->string('file_path')->nullable();
                $table->string('external_link')->nullable();
                $table->string('external_link_btn')->nullable();
                $table->boolean('wholesale_product')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('product_translations')) {
            Schema::create('product_translations', function (Blueprint $table) {
                $table->id();
                $table->integer('product_id');
                $table->string('name')->nullable();
                $table->string('unit')->nullable();
                $table->longText('description')->nullable();
                $table->string('lang', 10);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('product_categories')) {
            Schema::create('product_categories', function (Blueprint $table) {
                $table->id();
                $table->integer('product_id');
                $table->integer('category_id');
            });
        }

        if (! Schema::hasTable('product_stocks')) {
            Schema::create('product_stocks', function (Blueprint $table) {
                $table->id();
                $table->integer('product_id');
                $table->string('variant')->nullable();
                $table->string('sku')->nullable();
                $table->decimal('price', 20, 2)->nullable();
                $table->integer('qty')->default(0);
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('product_taxes')) {
            Schema::create('product_taxes', function (Blueprint $table) {
                $table->id();
                $table->integer('product_id');
                $table->integer('tax_id')->nullable();
                $table->decimal('tax', 20, 2)->nullable();
                $table->string('tax_type')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('flash_deal_products')) {
            Schema::create('flash_deal_products', function (Blueprint $table) {
                $table->id();
                $table->integer('flash_deal_id');
                $table->integer('product_id');
                $table->decimal('discount', 20, 2)->default(0);
                $table->string('discount_type')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_deal_products');
        Schema::dropIfExists('product_taxes');
        Schema::dropIfExists('product_stocks');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('products');
        Schema::dropIfExists('flash_deal_translations');
        Schema::dropIfExists('flash_deals');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('shops');
        Schema::dropIfExists('brand_translations');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('category_translations');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('uploads');
    }
};
