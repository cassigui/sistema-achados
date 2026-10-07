<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMfaFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('google2fa_enabled')->default(0)->after('super_admin')->comment('MFA (TOTP) ativa para o usuário');
            $table->string('google2fa_secret', 100)->nullable()->after('google2fa_enabled')->comment('Secret TOTP; nunca é exposto pela API');
            $table->json('backup_codes')->nullable()->after('google2fa_secret')->comment('Códigos de recuperação de uso único');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google2fa_enabled', 'google2fa_secret', 'backup_codes']);
        });
    }
}
