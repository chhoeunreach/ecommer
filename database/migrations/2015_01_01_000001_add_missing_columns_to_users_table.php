<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'referred_by')) {
                $table->unsignedInteger('referred_by')->nullable()->after('id');
            }
            if (! Schema::hasColumn('users', 'provider')) {
                $table->string('provider')->nullable()->after('referred_by');
            }
            if (! Schema::hasColumn('users', 'provider_id')) {
                $table->string('provider_id')->nullable()->after('provider');
            }
            if (! Schema::hasColumn('users', 'refresh_token')) {
                $table->text('refresh_token')->nullable()->after('provider_id');
            }
            if (! Schema::hasColumn('users', 'access_token')) {
                $table->text('access_token')->nullable()->after('refresh_token');
            }
            if (! Schema::hasColumn('users', 'user_type')) {
                $table->string('user_type')->default('customer')->after('access_token');
            }
            if (! Schema::hasColumn('users', 'verification_code')) {
                $table->string('verification_code')->nullable()->after('email_verified_at');
            }
            if (! Schema::hasColumn('users', 'new_email_verificiation_code')) {
                $table->string('new_email_verificiation_code')->nullable()->after('verification_code');
            }
            if (! Schema::hasColumn('users', 'verification_status')) {
                $table->boolean('verification_status')->default(0)->after('new_email_verificiation_code');
            }
            if (! Schema::hasColumn('users', 'device_token')) {
                $table->text('device_token')->nullable()->after('remember_token');
            }
            if (! Schema::hasColumn('users', 'avatar')) {
                $table->unsignedBigInteger('avatar')->nullable()->after('device_token');
            }
            if (! Schema::hasColumn('users', 'avatar_original')) {
                $table->unsignedBigInteger('avatar_original')->nullable()->after('avatar');
            }
            if (! Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('avatar_original');
            }
            if (! Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable()->after('address');
            }
            if (! Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable()->after('country');
            }
            if (! Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('state');
            }
            if (! Schema::hasColumn('users', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('city');
            }
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('postal_code');
            }
            if (! Schema::hasColumn('users', 'balance')) {
                $table->decimal('balance', 20, 2)->default(0)->after('phone');
            }
            if (! Schema::hasColumn('users', 'banned')) {
                $table->boolean('banned')->default(0)->after('balance');
            }
            if (! Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code')->nullable()->after('banned');
            }
            if (! Schema::hasColumn('users', 'customer_package_id')) {
                $table->unsignedBigInteger('customer_package_id')->nullable()->after('referral_code');
            }
            if (! Schema::hasColumn('users', 'remaining_uploads')) {
                $table->integer('remaining_uploads')->default(0)->after('customer_package_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'referred_by', 'provider', 'provider_id', 'refresh_token', 'access_token',
                'user_type', 'verification_code', 'new_email_verificiation_code', 'verification_status',
                'device_token', 'avatar', 'avatar_original', 'address', 'country', 'state', 'city',
                'postal_code', 'phone', 'balance', 'banned', 'referral_code', 'customer_package_id',
                'remaining_uploads',
            ]);
        });
    }
};
