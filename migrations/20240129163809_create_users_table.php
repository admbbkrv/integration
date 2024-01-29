<?php

use Illuminate\Database\Schema\Blueprint;
use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Миграция для таблицы Users
 */
class CreateUsersTable extends Migration
{
    /**
     * Do the migration
     */
    public function up(): void
    {
        Capsule::schema()->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_email');
            $table->string('user_password');
            $table->timestampsTz();
        });
    }

    /**
     * Undo the migration
     */
    public function down(): void
    {
        Capsule::schema()->drop('users');
    }
}
