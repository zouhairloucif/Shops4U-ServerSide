<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigPaiementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_paiements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status')->nullable();
            $table->unsignedInteger('boutique_id');
            $table->unsignedInteger('mode_paiement_id');
            $table->foreign('boutique_id')->references('id')->on('boutiques');
            $table->foreign('mode_paiement_id')->references('id')->on('mode_paiements');
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
        Schema::dropIfExists('config_paiements');
    }
}
