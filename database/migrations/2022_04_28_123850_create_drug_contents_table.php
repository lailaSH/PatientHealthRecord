<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrugContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drug_content', function (Blueprint $table) {
            $table->integer('docid')->primary()->unsigned();
            $table->longText('factName')->nullable();
            $table->longText('factoryCountry')->nullable();
            $table->longText('lang')->nullable();
            $table->longText('marketName')->nullable();
            $table->longText('gauge')->nullable();
            $table->longText('dosage')->nullable();
            $table->longText('effects')->nullable();
            $table->longText('interaction')->nullable();
            $table->longText('warnings')->nullable();
            $table->longText('form1')->nullable();
            $table->longText('formThum')->nullable();
            $table->longText('indication')->nullable();
            $table->longText('antiIndication')->nullable();
            $table->index('marketName');
            $table->index('interaction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drug_content');
    }
}
