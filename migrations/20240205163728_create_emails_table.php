<?php

use Illuminate\Database\Schema\Blueprint;
use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateEmailsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('emails', function (Blueprint $table) {
            $table->id();
            //внешний ключ связанный с таблицей contacts
            $table->foreignId('contact_id')
                ->constrained()
                ->references('id')
                ->on('contacts')
                ->onDelete('cascade');

            $table->string('email');
            $table->timestampsTz();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('emails');
    }
}
