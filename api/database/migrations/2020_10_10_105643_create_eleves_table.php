<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElevesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eleves', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('matricule')->nullable();
            $table->date('naissance')->nullable();
            $table->char('sexe', 2)->nullable();
            $table->string('ethnie')->nullable();
	    $table->text('adresse')->nullable();

	    $table->string('nomMere')->nullable();
	    $table->string('prenomMere')->nullable();
	    $table->string('telephoneMere')->nullable();
	    $table->string('professionMere')->nullable();

	    $table->string('nomPere')->nullable();
	    $table->string('prenomPere')->nullable();
	    $table->string('telephonePere')->nullable();
	    $table->string('professionPere')->nullable();

	    $table->string('frereSoeur1')->nullable();
	    $table->string('frereSoeur2')->nullable();
	    $table->string('frereSoeur3')->nullable();
	    $table->string('frereSoeur4')->nullable();
	    $table->string('frereSoeur5')->nullable();
	    $table->string('frereSoeur6')->nullable();

	    $table->string('etablissement1')->nullable();
	    $table->string('etablissement2')->nullable();
	    $table->string('etablissement3')->nullable();
	    $table->string('etablissement4')->nullable();
	    $table->string('etablissement5')->nullable();
	    $table->string('etablissement6')->nullable();

	    $table->date('dateDepart')->nullable();
	    $table->text('raisonDepart')->nullable();

	    $table->timestamps();

	    $table->foreignId('classe_id')->nullable()->constrained();
	    $table->foreignId('niveau_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eleves');
    }
}
