<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $this->command->info('Truncating Roles table');
        $this->truncateLaratrustTables();

        $config = config('laratrust_seeder.roles_structure');
        $userPermission = config('laratrust_seeder.permission_structure');
        $mapPermission = collect(config('laratrust_seeder.permissions_map'));
        $mapDescriptions = config('laratrust_seeder.roles_description_map');

        foreach ($config as $key => $modules) {

            // Create a new role
            $role = \App\Models\Role::create([
                'name' => $key,
                'display_name' => ucwords(str_replace('_', ' ', $key)),
                'description' => $mapDescriptions[$key]
            ]);
            $permissions = [];

            $this->command->info('Creating Roles ' . strtoupper($key));
        }
    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return    void
     */
    public function truncateLaratrustTables()
    {
        Schema::disableForeignKeyConstraints();
        \App\Models\Role::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
