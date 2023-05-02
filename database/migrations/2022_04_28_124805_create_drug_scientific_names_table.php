<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrugScientificNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Drug_Scientific_Name', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ScientificID')->unsigned();
            $table->integer('DrugID')->unsigned();
            $table->foreign('ScientificID')->references('id')->on('Scientific_Name')
                ->onDelete('cascade');
                $table->foreign('DrugID')->references('docid')->on('drug_content')
                ->onDelete('cascade');
            $table->index('ScientificID');
            $table->index('DrugID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Drug_Scientific_Name');
    }
}
