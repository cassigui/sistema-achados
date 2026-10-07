<?php

namespace App\Modules\Base\Utilities;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UtilityService
{
    public function verifyDOC(string $value, array $tables = [], string $column = 'doc', $id = null)
    {

        if(strlen($value) == 11){
            if(!$this->validateCPF($value)){
                throw new UtilityException(422, 'O CPF inserido é inválido');
            }
        }else if(strlen($value) == 14){
            if(!$this->validateCNPJ($value)){
                throw new UtilityException(422, 'O CNPJ inserido é inválido.');
            };
        }

        foreach ($tables as $table) {
            if ($this->exists($table, $column, $value, $id)) {
                throw new UtilityException(422, 'O CPF/CNPJ inserido já existe.');
            }
        }

        return true;
    }

    /**
     * Checa se a tabela contém uma coluna com o valor inserido
     *
     * @param string $table
     * @param string $column
     * @param mixed $value
     * @return bool
     */
    public function exists(string $table, string $column, $value, $id)
    {
        return DB::table($table)->where($column, $value)->where('id', '!=', $id)->exists();
    }

    public function validateCNPJ(string $cnpj){
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

        if (strlen($cnpj) != 14) {
            return false;
        }

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    public function validateCPF(string $cpf){
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

}