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
            $table->string('nom');
            $table->string('description');
            $table->float('prix');
            $table->unsignedInteger('utilisateur_id');
            $table->unsignedInteger('fournisseur_id');
            $table->unsignedInteger('marque_id');
            $table->unsignedInteger('stock_id');
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs');
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
