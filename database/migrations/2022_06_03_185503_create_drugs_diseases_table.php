<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrugsDiseasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drugs_diseases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('NameDrug')->nullable();
            $table->string('CodeDisease');
            $table->string('NameGruop')->nullable();
            $table->longText('descrpition')->nullable();
            $table->index('NameDrug');
            $table->index('CodeDisease');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drugs_diseases');
    }
}
