<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientPharmacistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_pharmacists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pharmacistID')->unsigned();
            $table->foreign('pharmacistID')->references('id')->on('pharmacists')
                ->onDelete('cascade');
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
        Schema::dropIfExists('patient_pharmacists');
    }
}
