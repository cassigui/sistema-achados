<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessLevelsTable extends Migration
{
    public function up()
    {
        Schema::create('access_levels', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id');
            $table->string('name', 200)->comment('Nome do nível de acesso do usuário como \'Administrador\', \'Supervisor\', \'Básico\';');
            $table->boolean('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('access_levels');
    }
}
