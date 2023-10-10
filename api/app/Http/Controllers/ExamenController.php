<?php

namespace App\Http\Controllers;

use App\Models\Examen;
// use App\Models\Eleve;
use App\Models\Periode;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Requests\StoreExamenRequest;
use App\Http\Requests\UpdateExamenRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class ExamenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Examen::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExamenRequest $request)
    {
        $data = $request->validated();
        Examen::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Examen  $examen
     * @return \Illuminate\Http\Response
     */
    public function show(Examen $examen)
    {
        return $examen;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Examen  $examen
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExamenRequest $request, Examen $examen)
    {
        $data = $request->validated();
        $examen->fill($data);
        $examen->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Examen  $examen
     * @return \Illuminate\Http\Response
     */
    public function destroy(Examen $examen)
    {
        $examen->delete();
    }

    public function moyennes(Classe $classe, Periode $periode)
    {
        $compo = Examen::where('code', 'comp')->first();
        $totalcoef = $classe->niveau->matieres->sum('coef');

        $moyennes = [];
        foreach ($classe->eleves as $eleve) {
            $notes = Note::where('eleve_id', $eleve->id)
        ->where('periode_id', $periode->id)
        ->where('examen_id', $compo->id)->get();
            $totalnote = 0; // $notes->sum('valeur');
            foreach ($notes as $note) {
                $mat = $note->matiere;
                $totalnote += ($note->valeur * $mat->coef);
            }
            $moyenne[$eleve->id] = round((float)$totalnote / $totalcoef, 2);
        }
        return $moyenne;
    }

    public function print(Request $request)
    {
        $classe = $request->classe;
        $niveau = Classe::findOrFail($classe)->niveau;
        $matieres = Matiere::where('niveau_id', $niveau->id)->orderBy('code')->get();
        $periode = Periode::where('ouvert', true)->firstOrFail();
        $ev1 = Examen::where('code', 'ev1')->first();
        $ev2 = Examen::where('code', 'ev2')->first();
        $ev3 = Examen::where('code', 'ev3')->first();
        $comp = Examen::where('code', 'comp')->first();

        $sexe = $request->sexe;
        $classe =  Classe::with(['eleves' => function ($query) use ($sexe) {
            $query->whereNull('dateDepart')->with(['notes' => function ($query) {
                $periode = Periode::where('ouvert', true)->firstOrFail();
                $query->where('periode_id', $periode->id)->with('matiere')->with('examen');
            }]);
        }])
        ->where('id', $classe)->first();
        $matiere = Matiere::where('classe_id');
    
        //return $classe;

        // return view('bulletins.t1', c);
        $pdf = null;
        if ($periode->libelle == 'Premier trimestre') {
            $pdf = Pdf::loadView('bulletins.t1', compact('classe', 'matieres', 'periode', 'ev1', 'ev2', 'ev3', 'comp'));
        } elseif ($periode->libelle == 'Deuxieme trimestre') {
            $moyennest1 = $this->moyennes(Classe::find($request->classe), Periode::where('libelle', 'Premier trimestre')->first());
            $pdf = Pdf::loadView('bulletins.t2', compact('classe', 'matieres', 'periode', 'ev1', 'ev2', 'ev3', 'comp', 'moyennest1'));
        } else {
            $moyennest1 = $this->moyennes(Classe::find($request->classe), Periode::where('libelle', 'Premier trimestre')->first());
            $moyennest2 = $this->moyennes(Classe::find($request->classe), Periode::where('libelle', 'Deuxieme trimestre')->first());
            $pdf = Pdf::loadView('bulletins.t3', compact('classe', 'matieres', 'periode', 'ev1', 'ev2', 'ev3', 'comp', 'moyennest1', 'moyennest2'));
        }
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('rapport.pdf', ['Attachment' => false]);
    }
}
