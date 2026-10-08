<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Criar o Usuário Administrador
        $adminId = DB::table('users')->insertGetId([
            'name'        => 'Administrador UTFPR',
            'username'    => 'admin_utfpr',
            'email'       => 'admin@utfpr.br',
            'password'    => Hash::make('SenhaTeste123!'),
            'active'      => true,
            'super_admin' => true,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ]);

        // 2. Criar o Usuário Comum
        $userId = DB::table('users')->insertGetId([
            'name'        => 'Usuário Comum UTFPR',
            'username'    => 'usuario_utfpr',
            'email'       => 'usuario@utfpr.br',
            'password'    => Hash::make('SenhaTeste123!'),
            'active'      => true,
            'super_admin' => false,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ]);

        // 3. Criar 5 Itens sem fotos (image_path como null)
        $items = [
            [
                'title'       => 'Garrafa Térmica Stanley',
                'description' => 'Garrafa térmica verde em ótimo estado, encontrada no bloco de Engenharias.',
                'image_path'  => null,
                'category'    => 'outros',
                'status'      => 'encontrado',
                'user_id'     => $userId,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Calculadora Científica Casio',
                'description' => 'Calculadora modelo fx-991ES encontrada na biblioteca.',
                'image_path'  => null,
                'category'    => 'eletronicos',
                'status'      => 'encontrado',
                'user_id'     => $userId,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Chaveiro com Chaves Automotivas',
                'description' => 'Chaveiro contendo chaves de carro e controle de portão, achado no estacionamento.',
                'image_path'  => null,
                'category'    => 'outros',
                'status'      => 'encontrado',
                'user_id'     => $adminId,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Guarda-Chuva Preto Grande',
                'description' => 'Guarda-chuva automático esquecido no auditório principal.',
                'image_path'  => null,
                'category'    => 'outros',
                'status'      => 'encontrado',
                'user_id'     => $userId,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Fone de Ouvido Bluetooth JBL',
                'description' => 'Fone sem fio preto dentro do estojo de carregamento, encontrado na cantina.',
                'image_path'  => null,
                'category'    => 'eletronicos',
                'status'      => 'encontrado',
                'user_id'     => $adminId,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ];

        DB::table('items')->insert($items);
    }
}