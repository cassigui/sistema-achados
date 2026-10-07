<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('user_permission_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id');
            $table->string('name', 200);
            $table->string('type', 200);
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_permission_categories');
    }
}
