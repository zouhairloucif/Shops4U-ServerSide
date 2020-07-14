<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransporteursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transporteurs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom')->nullable();
            $table->string('Délai')->nullable();
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
        Schema::dropIfExists('transporteurs');
    }
}
