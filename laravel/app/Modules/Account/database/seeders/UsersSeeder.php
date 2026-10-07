<?php
namespace App\Modules\Account\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $rows = [
            ['id' => 1, 'name' => 'Nataq', 'email' => 'contato@webfloat.com.br', 'username' => 'root', 'password' => '$2y$10$ulnLv2wfshetU.PyiHLcD.Ky/WgGj0sTA4HAzotcWVbXriPjpiZFC', 'active' => 1, 'super_admin' => 0, 'access_level_id' => 1, 'authenticable_type' => null, 'authenticable_id' => null, 'remember_token' => null, 'created_at' => '2019-08-13 16:19:29', 'updated_at' => '2026-04-23 08:44:46', 'deleted_at' => null],
            ['id' => 2, 'name' => 'Nome1', 'email' => 'Email@mail.com', 'username' => 'user', 'password' => '$2y$10$YO5MJU3u/bO8XlIRFiZXVeUEjCiONs5LsceQghHCL4YYe/oKT21im', 'active' => 1, 'super_admin' => 0, 'access_level_id' => 1, 'authenticable_type' => null, 'authenticable_id' => null, 'remember_token' => null, 'created_at' => '2026-04-03 22:55:24', 'updated_at' => '2026-04-03 22:56:01', 'deleted_at' => null],
            ['id' => 3, 'name' => 'Teste', 'email' => 'XXxx@email.com', 'username' => 'nataqq', 'password' => '$2y$10$UoFoWVU8RGDkaJ3G2fry1OYf2WA4HAi1ZH7ZlC.r3hQOmAzhZaoMm', 'active' => 1, 'super_admin' => 0, 'access_level_id' => 1, 'authenticable_type' => null, 'authenticable_id' => null, 'remember_token' => null, 'created_at' => '2026-04-03 23:01:31', 'updated_at' => '2026-04-03 23:01:31', 'deleted_at' => null],
            ['id' => 4, 'name' => 'teste', 'email' => 'edersonrenan2003@hotmail.com', 'username' => '78788787', 'password' => '$2y$10$mUkmFEQ0DTFGLfTGsMVGreS444FvyOigZHYQQZE2nrwmRnbbMdx12', 'active' => 1, 'super_admin' => 0, 'access_level_id' => 1, 'authenticable_type' => null, 'authenticable_id' => null, 'remember_token' => null, 'created_at' => '2026-04-09 20:37:37', 'updated_at' => '2026-04-09 20:37:37', 'deleted_at' => null],
        ];

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('users')->insert($chunk);
        }
    }
}
