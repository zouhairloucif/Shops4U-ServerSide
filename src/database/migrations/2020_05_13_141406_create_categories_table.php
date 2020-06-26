<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->nullable();
            $table->unsignedInteger('boutique_id')->nullable();;
            $table->unsignedInteger('parent')->nullable();
            $table->foreign('boutique_id')->references('id')->on('boutiques');
            $table->foreign('parent')->references('id')->on('categories');
            $table->timestamps();
        });

        // Insert Categories
        DB::table('categories')->insert(
            array(['nom' => 'Accueil', 'description' => 'Accueil (Catégorie racine)'])
        );
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
