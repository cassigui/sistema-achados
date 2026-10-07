<?php

namespace App\Modules\Account\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPermissionCategoriesSeeder extends Seeder
{
    public function run()
    {
        $rows = [
            ['id' => 1, 'name' => 'Usuários', 'type' => 'users', 'deleted_at' => null],
            ['id' => 2, 'name' => 'Permissões', 'type' => 'access_levels', 'deleted_at' => null],
            ['id' => 3, 'name' => 'Configurações', 'type' => 'configs', 'deleted_at' => null],
            ['id' => 6, 'name' => 'Auditoria', 'type' => 'auditings', 'deleted_at' => null],
            ['id' => 7, 'name' => 'Temas', 'type' => 'themes', 'deleted_at' => null],
            ['id' => 8, 'name' => 'Fluxo de Caixa', 'type' => 'cashflows', 'deleted_at' => null],
            ['id' => 9, 'name' => 'Contas Bancárias', 'type' => 'cashflow_accounts', 'deleted_at' => null],
            ['id' => 10, 'name' => 'Categorias Financeiras', 'type' => 'cashflow_categories', 'deleted_at' => null],
            ['id' => 11, 'name' => 'Planos de Conta', 'type' => 'cashflow_classifications', 'deleted_at' => null],
            ['id' => 12, 'name' => 'Centros de Custo', 'type' => 'cashflow_cost_centers', 'deleted_at' => null],
            ['id' => 13, 'name' => 'Departamentos Financeiros', 'type' => 'cashflow_departaments', 'deleted_at' => null],
            ['id' => 14, 'name' => 'Pessoas do Financeiro', 'type' => 'cashflow_people', 'deleted_at' => null],
            ['id' => 15, 'name' => 'Métodos de Pagamento', 'type' => 'payment_methods', 'deleted_at' => null],
        ];

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('user_permission_categories')->insert($chunk);
        }
    }
}
