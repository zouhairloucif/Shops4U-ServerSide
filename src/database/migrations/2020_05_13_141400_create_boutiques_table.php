<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoutiquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boutiques', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom')->nullable();
            $table->string('logo')->nullable();
            $table->string('url')->nullable();
            $table->string('devise')->nullable();
            $table->string('langue')->nullable();
            $table->string('status')->nullable();
            $table->integer('maintenance_id')->unsigned()->nullable();
            $table->foreign('maintenance_id')->references('id')->on('maintenances');
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
        Schema::dropIfExists('boutiques');
    }
}
