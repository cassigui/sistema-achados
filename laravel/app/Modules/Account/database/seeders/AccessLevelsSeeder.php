<?php

namespace App\Modules\Account\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessLevelsSeeder extends Seeder
{
    public function run()
    {
        $rows = [
            ['id' => 1, 'name' => 'Administrador', 'active' => 1, 'created_at' => '2020-04-30 18:11:24', 'updated_at' => '2020-04-30 18:11:24', 'deleted_at' => null],
            ['id' => 2, 'name' => 'Nn', 'active' => 1, 'created_at' => '2026-04-10 01:57:38', 'updated_at' => '2026-04-10 01:57:38', 'deleted_at' => null],
            ['id' => 3, 'name' => 'aaa', 'active' => 1, 'created_at' => '2026-04-10 01:58:08', 'updated_at' => '2026-04-10 01:58:08', 'deleted_at' => null],
            ['id' => 4, 'name' => 'eeex', 'active' => 1, 'created_at' => '2026-04-10 01:58:59', 'updated_at' => '2026-04-10 01:59:03', 'deleted_at' => null],
            ['id' => 5, 'name' => 'testex', 'active' => 1, 'created_at' => '2026-04-10 14:52:53', 'updated_at' => '2026-04-10 14:53:03', 'deleted_at' => null],
        ];

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('access_levels')->insert($chunk);
        }
    }
}
