<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcressNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procress_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('physical_health')->nullable();
            $table->string('mental_heealth')->nullable();
            $table->string('body_temperature')->nullable();
            $table->string('pulse_rate')->nullable();
            $table->string('respiration_rate')->nullable();
            $table->string('blood_pressure')->nullable();
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
        Schema::dropIfExists('procress_notes');
    }
}
