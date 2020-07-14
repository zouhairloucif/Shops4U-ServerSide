<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reductions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom')->nullable();
            $table->string('description')->nullable();
            $table->string('code')->nullable();
            $table->integer('quantite_disponible')->nullable();
            $table->string('type_reduction')->nullable();
            $table->integer('valeur_reduction')->nullable();
            $table->string('status')->nullable();
            $table->unsignedInteger('boutique_id')->nullable();
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
        Schema::dropIfExists('reductions');
    }
}
