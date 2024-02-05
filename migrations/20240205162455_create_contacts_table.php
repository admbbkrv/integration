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
