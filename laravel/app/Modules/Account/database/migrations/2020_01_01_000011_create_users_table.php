<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id')->comment('ID do usuário');
            $table->string('name', 200)->comment('Nome completo do usuário');
            $table->string('email', 255)->nullable()->comment('E-mail do usuário (opcional)');
            $table->string('username', 100)->comment('Identificação do usuário');
            $table->string('password', 255)->comment('Senha do usuário');
            $table->boolean('active')->default(1)->comment('Usuário ativo (1) ou inativo (0)');
            $table->boolean('super_admin')->default(0);
            $table->unsignedInteger('access_level_id')->nullable();
            $table->string('authenticable_type', 100)->nullable();
            $table->integer('authenticable_id')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}