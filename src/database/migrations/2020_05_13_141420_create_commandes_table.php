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
            $table->string('status')->nullable();
            $table->string('valide')->nullable();
            $table->date('date_commande')->nullable();
            $table->float('prix_total')->nullable();
            $table->float('prix_final')->nullable();
            $table->unsignedInteger('utilisateur_id');
            $table->unsignedInteger('boutique_id');
            $table->unsignedInteger('livraison_id');
            $table->unsignedInteger('paiement_id');
            $table->unsignedInteger('reduction_id');
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs');
            $table->foreign('boutique_id')->references('id')->on('boutiques');
            $table->foreign('livraison_id')->references('id')->on('livraisons');
            $table->foreign('paiement_id')->references('id')->on('mode_paiements');
            $table->foreign('reduction_id')->references('id')->on('reductions');
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
