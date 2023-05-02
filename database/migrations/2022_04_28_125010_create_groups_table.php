<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('docid');
            $table->integer('id')->nullable();
            $table->integer('drugGID')->nullable();
            $table->string('lang');
            $table->longText('groupName');
            $table->integer('parentID')->nullable();
            $table->integer('branch')->nullable();
            $table->index('groupName');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
