<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marques', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom');
            $table->string('description');
            $table->string('image');
            $table->unsignedInteger('boutique_id');
            $table->foreign('boutique_id')->references('id')->on('boutiques');
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
        Schema::dropIfExists('marques');
    }
}
