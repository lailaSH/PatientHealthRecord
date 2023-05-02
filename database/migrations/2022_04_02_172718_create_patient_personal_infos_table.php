<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientPersonalInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_personal_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('father_name');
            $table->string('family_name');
            $table->integer('phone_number');
            $table->integer('phone_number2')->nullable();
            $table->string('city');
            //الرقم الوطني
            $table->bigInteger('ID_number')->unique();
            //الهوية الشخصية 
            $table->string('ipersonal_identification_img');
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('gender')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('patient_personal_infos');
    }
}
