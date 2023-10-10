<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Paiement;
use App\Models\Annee;
use App\Models\Periode;
use App\Models\Matiere;
use App\Models\Examen;
use App\Models\Niveau;
use App\Models\Note;
use Carbon\Carbon;

class Eleve extends Model
{
    use HasFactory;

    public $fillable = [
        'nom',
        'prenom',
        'matricule',
        'naissance',
        'sexe',
        'classe_id',
        'niveau_id',
        'ethnie',
        'adresse',

        'nomMere',
        'prenomMere',
        'telephoneMere',
        'professionMere',

        'nomPere',
        'prenomPere',
        'telephonePere',
        'professionPere',

        'frereSoeur1',
        'frereSoeur2',
        'frereSoeur3',
        'frereSoeur4',
        'frereSoeur5',
        'frereSoeur6',

        'etablissement1',
        'etablissement2',
        'etablissement3',
        'etablissement4',
        'etablissement5',
        'etablissement6',

        'dateDepart',
        'raisonDepart',
    ];

    protected $appends = ['age', 'montantTotalPaiement'];

    public static $storeRules = [
        'nom' => 'required',
        'prenom' => 'required',
        'naissance' => 'required',
        'sexe' => 'required',
        'niveau_id' => 'required',
        'classe_id' => '',
        'matricule' => '',
        'ethnie' => '',
        'adresse' => '',

        'nomMere' => '',
        'prenomMere' => '',
        'telephoneMere' => '',
        'professionMere' => '',

        'nomPere' => '',
        'prenomPere' => '',
        'telephonePere' => '',
        'professionPere' => '',

        'frereSoeur1' => '',
        'frereSoeur2' => '',
        'frereSoeur3' => '',
        'frereSoeur4' => '',
        'frereSoeur5' => '',
        'frereSoeur6' => '',

        'etablissement1' => '',
        'etablissement2' => '',
        'etablissement3' => '',
        'etablissement4' => '',
        'etablissement5' => '',
        'etablissement6' => '',
    ];

    public static $updateRules = [
        'nom' => 'required',
        'prenom' => 'required',
        'naissance' => 'required',
        'sexe' => 'required',
        'classe_id' => 'required',
        'niveau_id' => 'required',
        'matricule' => '',
        'ethnie' => '',
        'adresse' => '',

        'nomMere' => '',
        'prenomMere' => '',
        'telephoneMere' => '',
        'professionMere' => '',

        'nomPere' => '',
        'prenomPere' => '',
        'telephonePere' => '',
        'professionPere' => '',

        'frereSoeur1' => '',
        'frereSoeur2' => '',
        'frereSoeur3' => '',
        'frereSoeur4' => '',
        'frereSoeur5' => '',
        'frereSoeur6' => '',

        'etablissement1' => '',
        'etablissement2' => '',
        'etablissement3' => '',
        'etablissement4' => '',
        'etablissement5' => '',
        'etablissement6' => '',
    ];

    public function setNomAttribute($value)
    {
        $this->attributes['nom'] = strtoupper(trim($value));
    }

    public function setPrenomAttribute($value)
    {
        $this->attributes['prenom'] = trim($value);
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->naissance)->age;
    }

    public function getMontantTotalPaiementAttribute()
    {
        $annee = Annee::where('ouvert', true)->first();
        if ($annee !== null) {
            return $this->paiements()->where('annee_id', $annee->id)
                ->get()
                ->sum('montant');
        }
        return null;
    }

    public function getMoyenneAttribute()
    {
        // return $this->classe->niveau_id;
        $annee = Annee::where('ouvert', true)->first();
        $periode = Periode::where('ouvert', true)->where('annee_id', $annee->id)->first();
        if ($periode !== null) {
            $niveau_id = $this->classe->niveau_id;
            $compo = Examen::where('code', 'comp')->first();
            // $nb_matiere = Matiere::where('niveau_id', $niveau_id)->count();
            $matieres = Matiere::where('niveau_id', $niveau_id)->get();
            $notes = Note::where('eleve_id', $this->id)
        ->where('periode_id', $periode->id)
        ->where('examen_id', $compo->id)
        ->get();
            $totalcoef = $matieres->sum('coef');
            // $totalnotes = $notes->sum('valeur');
            $totalnotes = 0;
            foreach ($notes as $note) {
                $mat = $note->matiere;
                $totalnotes += ($note->valeur * $mat->coef);
            }

            $moyenne = 0;
            if ($totalcoef > 0) {
                $moyenne = round((float) $totalnotes / $totalcoef, 2);
            }
            if ($periode->libelle == "Premier trimestre" || $periode->libelle == "Deuxieme trimestre") {
                return $moyenne;
            }

            $ec = new \App\Http\Controllers\ExamenController();
            $moyennest1 = $ec->moyennes($this->classe, Periode::where('libelle', 'Premier trimestre')->first());
            $moyennest2 = $ec->moyennes($this->classe, Periode::where('libelle', 'Deuxieme trimestre')->first());

            return round((float)($moyenne + $moyennest1[$this->id] + $moyennest2[$this->id]) / 3, 2);
        
            /*if ($nb_matiere === $notes->count()) {
            return $notes->avg('valeur');
            }*/
        }
        return null;
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
