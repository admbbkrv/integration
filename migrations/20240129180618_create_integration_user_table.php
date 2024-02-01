<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Миграция для таблицы связей users n - n integrations
 */
class CreateIntegrationUserTable extends Migration
{
    /**
     * Do the migration
     * @return void
     */
    public function up(): void
    {
        Capsule::schema()->create('integration_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('integration_id')
                ->constrained()
                ->references('id')
                ->on('integrations')
                ->onDelete('cascade');

            $table->timestampsTz();
        });
    }

    /**
     * Undo the migration
     * @return void
     */
    public function down(): void
    {
        Capsule::schema()->drop('integration_user');
    }
}
