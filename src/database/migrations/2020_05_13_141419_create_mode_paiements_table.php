<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModePaiementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mode_paiements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom')->nullable();
            $table->string('image')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        // Insertio :

        DB::table('mode_paiements')->insert(
            array(['nom' => 'Virement bancaire', 'image' => 'ModePaiement-1png', 'description' => 'Accepter les paiements par virement en personne. Aussi connu sous le nom de transfert bancaire.'],
                ['nom' => 'Paiements par chèque', 'image' => 'ModePaiement-2png', 'description' => 'Accepter les paiements par chèque en personne. Cette passerelle hors-ligne peut être utile pour tester les achats.'],
                ['nom' => 'Paiement à la livraison', 'image' => 'ModePaiement-3png', 'description' => 'Demandez à vos clients de payer en espèces (ou par tout autre moyen) à la livraison.'],
                ['nom' => 'PayPal', 'image' => 'ModePaiement-4png', 'description' => 'PayPal Standard redirige les clients vers PayPal afin qu’ils saisissent leurs informations de paiement.'],
            )
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mode_paiements');
    }
}
