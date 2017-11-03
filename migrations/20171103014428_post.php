<?php
use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class post extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('post', function($table)
        {
            $table->increments('id');
            
            $table->timestamps();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('post');
    }
}