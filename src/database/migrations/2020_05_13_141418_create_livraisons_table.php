<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLivraisonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('livraisons', function (Blueprint $table) {
            $table->increments('id');
            $table->float('prix');
            $table->unsignedInteger('transporteur_id');
            $table->unsignedInteger('zone_id')->nullable();
            $table->unsignedInteger('pays_id')->nullable();
            $table->unsignedInteger('ville_id')->nullable();
            $table->foreign('transporteur_id')->references('id')->on('transporteurs');
            $table->foreign('zone_id')->references('id')->on('zones');
            $table->foreign('pays_id')->references('id')->on('pays');
            $table->foreign('ville_id')->references('id')->on('villes');
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
        Schema::dropIfExists('livraisons');
    }
}
