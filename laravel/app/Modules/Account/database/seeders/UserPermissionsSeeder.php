<?php

namespace App\Modules\Account\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPermissionsSeeder extends Seeder
{
    public function run()
    {
        $rows = [
            ['id' => 1, 'name' => 'Visualizar', 'type' => 'view', 'user_permission_category_id' => 1],
            ['id' => 2, 'name' => 'Criar', 'type' => 'create', 'user_permission_category_id' => 1],
            ['id' => 3, 'name' => 'Atualizar', 'type' => 'update', 'user_permission_category_id' => 1],
            ['id' => 4, 'name' => 'Remover', 'type' => 'delete', 'user_permission_category_id' => 1],
            ['id' => 5, 'name' => 'Visualizar', 'type' => 'view', 'user_permission_category_id' => 2],
            ['id' => 6, 'name' => 'Criar', 'type' => 'create', 'user_permission_category_id' => 2],
            ['id' => 7, 'name' => 'Atualizar', 'type' => 'update', 'user_permission_category_id' => 2],
            ['id' => 8, 'name' => 'Remover', 'type' => 'delete', 'user_permission_category_id' => 2],
            ['id' => 9, 'name' => 'Visualizar', 'type' => 'view', 'user_permission_category_id' => 3],
            ['id' => 10, 'name' => 'Criar', 'type' => 'create', 'user_permission_category_id' => 3],
            ['id' => 11, 'name' => 'Atualizar', 'type' => 'update', 'user_permission_category_id' => 3],
            ['id' => 12, 'name' => 'Remover', 'type' => 'delete', 'user_permission_category_id' => 3],
            ['id' => 21, 'name' => 'Visualizar', 'type' => 'view', 'user_permission_category_id' => 6],
            ['id' => 22, 'name' => 'Criar', 'type' => 'create', 'user_permission_category_id' => 6],
            ['id' => 23, 'name' => 'Atualizar', 'type' => 'update', 'user_permission_category_id' => 6],
            ['id' => 24, 'name' => 'Remover', 'type' => 'delete', 'user_permission_category_id' => 6],
            ['id' => 25, 'name' => 'Visualizar', 'type' => 'view', 'user_permission_category_id' => 7],
            ['id' => 26, 'name' => 'Criar', 'type' => 'create', 'user_permission_category_id' => 7],
            ['id' => 27, 'name' => 'Atualizar', 'type' => 'update', 'user_permission_category_id' => 7],
            ['id' => 28, 'name' => 'Remover', 'type' => 'delete', 'user_permission_category_id' => 7],
            ['id' => 29, 'name' => 'Visualizar', 'type' => 'view', 'user_permission_category_id' => 8],
            ['id' => 30, 'name' => 'Criar', 'type' => 'create', 'user_permission_category_id' => 8],
            ['id' => 31, 'name' => 'Atualizar', 'type' => 'update', 'user_permission_category_id' => 8],
            ['id' => 32, 'name' => 'Remover', 'type' => 'delete', 'user_permission_category_id' => 8],
            ['id' => 33, 'name' => 'Visualizar', 'type' => 'view', 'user_permission_category_id' => 9],
            ['id' => 34, 'name' => 'Criar', 'type' => 'create', 'user_permission_category_id' => 9],
            ['id' => 35, 'name' => 'Atualizar', 'type' => 'update', 'user_permission_category_id' => 9],
            ['id' => 36, 'name' => 'Remover', 'type' => 'delete', 'user_permission_category_id' => 9],
            ['id' => 37, 'name' => 'Visualizar', 'type' => 'view', 'user_permission_category_id' => 10],
            ['id' => 38, 'name' => 'Criar', 'type' => 'create', 'user_permission_category_id' => 10],
            ['id' => 39, 'name' => 'Atualizar', 'type' => 'update', 'user_permission_category_id' => 10],
            ['id' => 40, 'name' => 'Remover', 'type' => 'delete', 'user_permission_category_id' => 10],
            ['id' => 41, 'name' => 'Visualizar', 'type' => 'view', 'user_permission_category_id' => 11],
            ['id' => 42, 'name' => 'Criar', 'type' => 'create', 'user_permission_category_id' => 11],
            ['id' => 43, 'name' => 'Atualizar', 'type' => 'update', 'user_permission_category_id' => 11],
            ['id' => 44, 'name' => 'Remover', 'type' => 'delete', 'user_permission_category_id' => 11],
            ['id' => 45, 'name' => 'Visualizar', 'type' => 'view', 'user_permission_category_id' => 12],
            ['id' => 46, 'name' => 'Criar', 'type' => 'create', 'user_permission_category_id' => 12],
            ['id' => 47, 'name' => 'Atualizar', 'type' => 'update', 'user_permission_category_id' => 12],
            ['id' => 48, 'name' => 'Remover', 'type' => 'delete', 'user_permission_category_id' => 12],
            ['id' => 49, 'name' => 'Visualizar', 'type' => 'view', 'user_permission_category_id' => 13],
            ['id' => 50, 'name' => 'Criar', 'type' => 'create', 'user_permission_category_id' => 13],
            ['id' => 51, 'name' => 'Atualizar', 'type' => 'update', 'user_permission_category_id' => 13],
            ['id' => 52, 'name' => 'Remover', 'type' => 'delete', 'user_permission_category_id' => 13],
            ['id' => 53, 'name' => 'Visualizar', 'type' => 'view', 'user_permission_category_id' => 14],
            ['id' => 54, 'name' => 'Criar', 'type' => 'create', 'user_permission_category_id' => 14],
            ['id' => 55, 'name' => 'Atualizar', 'type' => 'update', 'user_permission_category_id' => 14],
            ['id' => 56, 'name' => 'Remover', 'type' => 'delete', 'user_permission_category_id' => 14],
            ['id' => 57, 'name' => 'Visualizar', 'type' => 'view', 'user_permission_category_id' => 15],
            ['id' => 58, 'name' => 'Criar', 'type' => 'create', 'user_permission_category_id' => 15],
            ['id' => 59, 'name' => 'Atualizar', 'type' => 'update', 'user_permission_category_id' => 15],
            ['id' => 60, 'name' => 'Remover', 'type' => 'delete', 'user_permission_category_id' => 15],
        ];

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('user_permissions')->insert($chunk);
        }
    }
}
