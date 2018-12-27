<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLvTagModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('lv_tag_models', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('lv_tag_id')->nullable();
            $table->integer('lv_tag_model_id')->nullable();
            $table->string('lv_tag_model_type')->nullable();

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
        Schema::dropIfExists('lv_tag_models');
    }
}
