<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Periode;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClasseRequest;
use App\Http\Requests\UpdateClasseRequest;
use Illuminate\Support\Facades\DB;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Classe::with(['niveau' => function ($q) {
            $q->orderBy('libelle');
        }])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClasseRequest $request)
    {
        $data = $request->validated();
        Classe::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Classe  $classe
     * @return \Illuminate\Http\Response
     */
    public function show(Classe $classe)
    {
        return $classe;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Classe  $classe
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClasseRequest $request, Classe $classe)
    {
        $data = $request->validated();
        $classe->fill($data);
        $classe->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classe  $classe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classe $classe)
    {
        $classe->delete();
    }

    public function eleves(Classe $classe)
    {
        return $classe->eleves()->whereNull('dateDepart')->get();
    }

    public function updateEleves(Request $request, Classe $classe)
    {
        DB::begintransaction();

        $eleves = $classe->eleves()->get();
        foreach ($eleves as $eleve) {
            $eleve->classe_id = null;
            $eleve->niveau_id = null;
            $eleve->save();
        }

        $data = $request->input('data');

        $eleves = explode(',', $data);

        if (strlen($data) > 0) {
            foreach ($eleves as /*$key => */ $eleve) {
                // $eleves[$key] = (int)(trim($eleve));
                // Eleve::find($eleves[$key])->classe_id = $classe->id;
                $eleve_id = (int)(trim($eleve));
                $current_eleve = Eleve::findOrFail($eleve_id);
                $current_eleve->classe_id = $classe->id;
                $current_eleve->niveau_id = $classe->niveau->id;
                $current_eleve->save();
            }
        }
        DB::commit();
    }

    public function matieres(Classe $classe)
    {
        // ne pas envoyer la liste si aucune periode n'est ouverte
        Periode::where('ouvert', true)->firstOrFail();
        return $classe->niveau->matieres()->get()->all();
    }
}
