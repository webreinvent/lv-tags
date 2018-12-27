<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLvTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('lv_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();

            $table->integer('order_number')->nullable();
            $table->integer('usage')->nullable();


            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists('lv_tags');
    }
}
