<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProduitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom')->nullable();
            $table->string('description')->nullable();
            $table->float('montant_HT')->nullable();
            $table->float('montant_TTC')->nullable();
            $table->float('tva')->nullable();
            $table->float('prix_final')->nullable();
            $table->string('status')->nullable();
            $table->unsignedInteger('boutique_id')->nullable();
            $table->unsignedInteger('fournisseur_id')->nullable();
            $table->unsignedInteger('marque_id')->nullable();
            $table->unsignedInteger('stock_id')->nullable();
            $table->foreign('boutique_id')->references('id')->on('boutiques');
            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs');
            $table->foreign('marque_id')->references('id')->on('marques');
            $table->foreign('stock_id')->references('id')->on('stocks');
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
        Schema::dropIfExists('produits');
    }
}
