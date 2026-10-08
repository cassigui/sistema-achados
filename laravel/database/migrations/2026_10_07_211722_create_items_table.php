<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id')->comment('ID do item');
            $table->unsignedInteger('user_id')->comment('ID do autor que registrou o item');
            $table->string('title', 150)->comment('Título do item (ex: Pendrive Kingston 32GB)');
            $table->text('description')->comment('Descrição detalhada do item e local onde foi achado/perdido');
            $table->string('image_path', 255)->nullable()->comment('Caminho da foto enviada');
            
            $table->enum('category', ['eletronicos', 'documentos', 'vestuario', 'outros'])
                  ->default('outros')
                  ->comment('Categoria do item');

            $table->enum('status', ['perdido', 'encontrado', 'devolvido'])
                  ->default('encontrado')
                  ->comment('Estado atual do item');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}