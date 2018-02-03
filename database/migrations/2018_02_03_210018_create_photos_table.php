<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tracking_code', 60);
            $table->dateTime('creation_date');
            $table->integer('width')->unsigned();
            $table->integer('height')->unsigned();
            $table->integer('likes')->unsigned();
            $table->string('description', 255)->nullable();
            $table->string('thumbnail', 500);
            $table->string('small', 500);
            $table->string('regular', 500);
            $table->string('full', 500);
            $table->string('raw', 500);
            $table->string('user_id', 60);
            $table->longText('object');
            $table->boolean('classified');
            $table->text('tags')->nullable();
            $table->dateTime('classified_date')->nullable;
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
        Schema::dropIfExists('photos');
    }
}
