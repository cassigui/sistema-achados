<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessLevelUserPermissionTable extends Migration
{
    public function up()
    {
        Schema::create('access_level_user_permission', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->unsignedInteger('access_level_id');
            $table->unsignedInteger('user_permission_id');
            $table->boolean('allow');

            $table->primary(['access_level_id', 'user_permission_id']);

            $table->foreign('access_level_id')->references('id')->on('access_levels');
            $table->foreign('user_permission_id')->references('id')->on('user_permissions');
        });
    }

    public function down()
    {
        Schema::dropIfExists('access_level_user_permission');
    }
}
