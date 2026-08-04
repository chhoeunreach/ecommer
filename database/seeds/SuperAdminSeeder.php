<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Create or update the local super-admin login.
     *
     * Set SUPER_ADMIN_EMAIL and SUPER_ADMIN_PASSWORD in the environment to
     * override these development defaults for a deployment.
     */
    public function run()
    {
        $admin = User::firstOrNew([
            'email' => env('SUPER_ADMIN_EMAIL', 'admin@example.com'),
        ]);

        $admin->name = 'Super Admin';
        $admin->user_type = 'admin';
        $admin->password = Hash::make(env('SUPER_ADMIN_PASSWORD', 'Admin@123456'));
        $admin->email_verified_at = $admin->email_verified_at ?: now();
        $admin->save();

        $role = Role::findOrCreate('Super Admin', 'web');
        $admin->syncRoles([$role]);
    }
}
