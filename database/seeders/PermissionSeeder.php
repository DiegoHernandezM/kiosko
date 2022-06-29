<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        \Artisan::call('cache:forget spatie.permission.cache');
        $arrays = [
            [
                'name' => 'Full Access'
            ],
            [
                'name' => 'manager'
            ],
            [
                'name' => 'associate'
            ]
        ];

        foreach ($arrays as $valor) {
            Permission::create([
                'guard_name' => 'web',
                'name' => $valor['name']
            ]);
        }
    }
}
