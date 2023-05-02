<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('FirstName');
            $table->string('FatherName');
            $table->string('LastName');
            $table->date('DateOfBirth');
            $table->string('city');
            $table->string('region');
            $table->string('phoneNumber');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('CertificateImage');
            $table->string('ipersonal_identification_img');
            $table->string('VacuumCard')->nullable();
            $table->string('ApprovalOpenClinic')->nullable();
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
        Schema::dropIfExists('pharmacists');
    }
}
