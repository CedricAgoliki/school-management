<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
	    $table->integer('valeur')->nullable();
            $table->timestamps();

	    $table->foreignId('eleve_id')->constrained();
	    $table->foreignId('matiere_id')->constrained();
	    $table->foreignId('examen_id')->constrained();
	    $table->foreignId('periode_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notes');
    }
}
