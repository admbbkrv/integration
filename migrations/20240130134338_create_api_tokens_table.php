<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Миграция для таблицы хранения токенов
 */
class CreateApiTokensTable extends Migration
{
    /**
     * Do the migration
     * @return void
     */
    public function up(): void
    {
        Capsule::schema()->create('api_tokens', function (Blueprint $table) {
            $table->id();
            //внешний ключ связанный с таблицей users
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->text('access_token');
            $table->integer('expires');
            $table->text('refresh_token');
            $table->string('base_domain')->nullable();
            $table->text('api_key')->nullable();
            $table->timestampsTz();
        });
    }

    /**
     * Undo the migration
     * @return void
     */
    public function down(): void
    {
        Capsule::schema()->drop('api_tokens');
    }
}
