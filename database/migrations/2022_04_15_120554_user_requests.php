<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('UserRequests', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('FirstName');
            $table->string('FatherName');
            $table->string('LastName');
            $table->date('DateOfBirth');
            $table->string('city');
            $table->string('region');
            $table->string('phoneNumber');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('type');
            $table->string('specialty')->nullable();
            $table->string('ipersonal_identification_img');
            $table->string('CertificateImage');
            $table->string('CertificateSpecialty')->nullable();
            $table->string('VacuumCard')->nullable();
            $table->string('ApprovalOpenClinic')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('UserRequests');
    }
    }




