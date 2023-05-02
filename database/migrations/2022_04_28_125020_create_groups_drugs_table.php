<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsDrugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GroupsDrug', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('DrugID')->unsigned();
            $table->integer('GroupID')->unsigned();
            $table->foreign('GroupID')->references('docid')->on('groups')
            ->onDelete('cascade');
            $table->foreign('DrugID')->references('docid')->on('drug_content')
            ->onDelete('cascade');
            $table->index('DrugID');
            $table->index('GroupID');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GroupsDrug');
    }
}
