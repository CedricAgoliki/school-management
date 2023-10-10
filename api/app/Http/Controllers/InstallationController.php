<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\Examen;

class InstallationController extends Controller
{
    /*
     * Fonction d'initialisation de la base de donnees
     * Certains donnees comme la liste des examen
     * sont fixe par defaut.
     *
     * Cette fonction est execute une seule fois lors de l'installation
     * de l'application pour initialiser la base
     * */
    public function init()
    {
        
        //Examens
        Examen::create(['code' => 'ev1', 'libelle' => 'Evaluation 1']);
        Examen::create(['code' => 'ev2', 'libelle' => 'Evaluation 2']);
        Examen::create(['code' => 'ev3', 'libelle' => 'Evaluation 3']);
        Examen::create(['code' => 'comp', 'libelle' => 'Composition']);
    }
}
