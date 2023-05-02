<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('drug_id')->unsigned();
            $table->foreign('drug_id')->references('docid')->on('drug_content');
            $table->string('drug_name')->nullable();
            $table->text('Description')->nullable();
            $table->date('EndDateUse')->nullable();
            $table->string('permanent')->nullable();
            $table->string('status')->default('yes');
            $table->integer('review_id')->unsigned();
            $table->foreign('review_id')->references('id')->on('reviews')
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
        Schema::dropIfExists('prescriptions');
    }
}
