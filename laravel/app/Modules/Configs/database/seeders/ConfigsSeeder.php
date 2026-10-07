<?php
namespace App\Modules\Configs\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigsSeeder extends Seeder
{
    public function run()
    {
        $rows = [
            ['key' => 'fantasy_name', 'value' => 'Bricks'],
            ['key' => 'email', 'value' => 'email@email.com.br'],
            ['key' => 'address', 'value' => 'endereço'],
            ['key' => 'scripts', 'value' => null],
            ['key' => 'exemplo', 'value' => 'exemplo'],
            ['key' => 'scripts_head', 'value' => null],
            ['key' => 'scripts_body_start', 'value' => null],
            ['key' => 'scripts_body_end', 'value' => null],
            ['key' => 'dark_mode_toggle', 'value' => 'true'],
            ['key' => 'dark_mode_default', 'value' => 'light'],
            ['key' => 'cashflow_dre_target_profit_percentage', 'value' => '10'],
        ];

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('configs')->insert($chunk);
        }
    }
}
