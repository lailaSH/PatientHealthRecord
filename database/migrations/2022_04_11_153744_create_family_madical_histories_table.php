<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamilyMadicalHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_madical_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('describtion');
            $table->integer('health_record_id')->unsigned();
            $table->foreign('health_record_id')->references('id')->on('healthrecords')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('family_madical_histories');
    }
}
