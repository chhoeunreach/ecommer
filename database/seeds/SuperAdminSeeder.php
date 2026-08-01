<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Promote the primary administrator without changing login credentials.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::where('user_type', 'admin')
            ->orderBy('id')
            ->first();

        if ($admin === null) {
            $this->command->warn('Super admin was not seeded because no admin user exists.');

            return;
        }

        $admin->name = 'Super Admin';
        $admin->save();

        $role = Role::findOrCreate('Super Admin', 'web');
        $admin->syncRoles([$role]);
    }
}
