<?php

declare(strict_types=1);

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
     * @return void
     */
    public function up(): void
    {
        Capsule::schema()->create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id');
            $table->timestampsTz();
        });
    }

    /**
     * Undo the migration
     * @return void
     */
    public function down(): void
    {
        Capsule::schema()->drop('users');
    }
}
