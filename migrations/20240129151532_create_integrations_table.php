<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Миграция для таблицы хранения данных интеграции
 */
class CreateIntegrationsTable extends Migration
{
    /**
     * Do the migration
     * @return void
     */
    public function up(): void
    {
        Capsule::schema()->create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('client_id');
            $table->string('client_secret');
            $table->string('redirect_uri');
            $table->timestampsTz();
        });
    }

    /**
     * Undo the migration
     * @return void
     */
    public function down(): void
    {
        Capsule::schema()->drop('integrations');
    }
}
