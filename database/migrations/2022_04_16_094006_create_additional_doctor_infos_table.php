<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CreateAdditionalDoctorInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_doctor_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idUser')->unsigned();
            $table->foreign('idUser')->references('id')->on('users')
                ->onDelete('cascade');
                $table->string('specialty')->nullable();
                //بطاقة النقابة
            $table->string('VacuumCard')->nullable();
            //شهادة اختصاص
            $table->string('CertificateSpecialty')->nullable();
            //موافقة وزارة الصحة
            $table->string('ApprovalOpenClinic')->nullable();
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
        Schema::dropIfExists('additional_doctor_infos');
    }
  

}
