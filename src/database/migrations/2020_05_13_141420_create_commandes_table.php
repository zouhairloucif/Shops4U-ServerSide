<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommandesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status');
            $table->string('valide');
            $table->date('date');
            $table->unsignedInteger('utilisateur_id');
            $table->unsignedInteger('boutique_id');
            $table->unsignedInteger('livraison_id');
            $table->unsignedInteger('paiement_id');
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs');
            $table->foreign('boutique_id')->references('id')->on('boutiques');
            $table->foreign('livraison_id')->references('id')->on('livraisons');
            $table->foreign('paiement_id')->references('id')->on('mode_paiements');
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
        Schema::dropIfExists('commandes');
    }
}
