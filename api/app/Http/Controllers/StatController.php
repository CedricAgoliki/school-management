<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Annee;
use App\Models\Paiement;
use App\Models\Periode;
use App\Models\Professeur;

class StatController extends Controller
{
    public function stats()
    {

    // nombre d'eleve
        $eleve = Eleve::whereNull('dateDepart')->count();
        $garcon = Eleve::whereNull('dateDepart')->where('sexe', 'M')->count();
        $fille = Eleve::whereNull('dateDepart')->where('sexe', 'F')->count();

        //eleves partis
        $eleveParti = Eleve::whereNotNull('dateDepart')->count();
        $garconParti = Eleve::whereNotNull('dateDepart')->where('sexe', 'M')->count();
        $filleParti = Eleve::whereNotNull('dateDepart')->where('sexe', 'F')->count();

        //classe
        $classe = Classe::count();

        // nombre de profs
        $profs = Professeur::count();

        // annee et periode actuelle
        $annee = Annee::where('ouvert', true)->first();
        $periode = Periode::where('ouvert', true)->first();

        //montant ecolages
        $ecolage = null;
        $nbPaiement = null;
        if ($annee !== null) {
            $ecolage = Paiement::where('annee_id', $annee->id)->sum('montant');
            $nbPaiement = Paiement::where('annee_id', $annee->id)->count();
        }
    

        return compact(
            'eleve',
            'garcon',
            'fille',
            'eleveParti',
            'garconParti',
            'filleParti',
            'profs',
            'ecolage',
            'nbPaiement',
            'annee',
            'periode',
            'classe',
        );
    }
}
