<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_requests', function (Blueprint $table) {
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
            $table->string('ipersonal_identification_img');
            $table->string('CertificateImage');
            $table->string('VacuumCard')->nullable();
            $table->string('CertificateSpecialty')->nullable();
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
        Schema::dropIfExists('lab_requests');
    }
}
