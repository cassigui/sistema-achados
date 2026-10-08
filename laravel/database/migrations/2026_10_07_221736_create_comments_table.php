<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Limpa a tabela residual se foi criada parcialmente na tentativa anterior
        Schema::dropIfExists('comments');

        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // Se o id da tabela items for INT comum (unsigned), declare assim:
            $table->unsignedInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

            // Para user_id (caso users use BIGINT padrão ou INT):
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};