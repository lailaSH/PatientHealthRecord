<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActicityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acticity_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('doctor_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('family_name')->nullable();
            $table->string('operation_type')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('acticity_logs');
    }
}
