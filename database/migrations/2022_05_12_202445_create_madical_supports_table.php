<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMadicalSupportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('madical_supports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Supporter_Specialty');
            $table->string('Description');
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
        Schema::dropIfExists('madical_supports');
    }
}
