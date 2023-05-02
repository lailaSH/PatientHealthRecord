<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_doctors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('DoctorID')->unsigned();
            $table->foreign('DoctorID')->references('id')->on('users')
                ->onDelete('cascade');
            $table->integer('HospitalID')->unsigned();
            $table->foreign('HospitalID')->references('id')->on('hospitals')
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
        Schema::dropIfExists('hospital_doctors');
    }
}
