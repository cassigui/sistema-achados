<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionsTable extends Migration
{
    public function up()
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id');
            $table->string('name', 200);
            $table->string('type', 200);
            $table->unsignedInteger('user_permission_category_id');

            $table->foreign('user_permission_category_id')->references('id')->on('user_permission_categories');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_permissions');
    }
}
