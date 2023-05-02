<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMadicalTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('madical_tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('description')->nullable();
            $table->string('date')->nullable();
            $table->string('executor')->nullable();
            $table->string('number')->nullable();
            $table->string('spec')->nullable();
            $table->integer('review_id')->unsigned();
            $table->foreign('review_id')->references('id')->on('reviews')
                ->onDelete('cascade');
            ////////
            $table->string('executor_id')->nullable();
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
        Schema::dropIfExists('madical_tests');
    }
}
