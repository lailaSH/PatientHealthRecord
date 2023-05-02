<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrugsNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DrugName', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('DrugID')->unsigned();
            $table->string('marketName');
            $table->foreign('DrugID')->references('docid')->on('drug_content')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drugs_names');
    }
}
