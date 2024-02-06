<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateContactsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('contacts', function (Blueprint $table) {
            $table->id();

            //внешний ключ связанный с таблицей contacts
            $table->foreignId('user_id')
                ->constrained()
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->integer('contact_id');
            $table->timestampsTz();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('contacts');
    }
}
