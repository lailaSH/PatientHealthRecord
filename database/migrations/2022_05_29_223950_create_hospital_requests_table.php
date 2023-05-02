<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('HospitalName');
            $table->string('OwnerName');
            $table->string('city');
            $table->string('region');
            $table->string('phoneNumber');
            $table->string('telephone');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            //صورة هوية مالك المشفى
            $table->string('ipersonal_identification_img');
            //ترخيص المشفى
            $table->string('HospitalLicense');
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
        Schema::dropIfExists('hospital_requests');
    }
}
