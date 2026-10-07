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
            $table->collation = 'utf8mb4_general_ci';

            $table->increments('id')->comment('ID do usuário');
            $table->string('name', 200)->charset('latin1')->collation('latin1_swedish_ci')->comment('Nome completo do usuário;');
            $table->string('email', 255)->charset('latin1')->collation('latin1_swedish_ci')->nullable()->comment('E-mail do usuário (opcional)');
            $table->string('username', 100)->charset('latin1')->collation('latin1_swedish_ci')->comment('Identificação do usuário');
            $table->string('password', 255)->charset('latin1')->collation('latin1_swedish_ci')->comment('Senha do usuário');
            $table->boolean('active')->default(0)->comment('Usuário ativo ou inativo: inativo não conseguem realizar o login');
            $table->boolean('super_admin')->default(0);
            $table->unsignedInteger('access_level_id')->nullable();
            $table->string('authenticable_type', 100)->nullable();
            $table->integer('authenticable_id')->nullable();
            $table->string('remember_token', 100)->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->timestamp('created_at')->nullable()->comment('Data/Hora de registro');
            $table->timestamp('updated_at')->nullable()->comment('Data/Hora da ultima alteração realizada');
            $table->softDeletes();

            $table->foreign('access_level_id')->references('id')->on('access_levels')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
