<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Classe;
use App\Models\Annee;
use App\Models\Periode;
use App\Models\Examen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        DB::begintransaction();
    
        $annee = Annee::where('ouvert', true)->first();
        $periode = Periode::where('ouvert', true)->where('annee_id', $annee->id)->first();
        // TODO: effacer toute les notes de la classe avant de reenregistrer
        foreach ($data as $eleve_id => $eleve) {
            foreach ($eleve as $matiere_id => $matiere) {
                foreach ($matiere as $exam_code => $note_val) {
                    $note = Note::where('periode_id', $periode->id)
            ->where('eleve_id', $eleve_id)
            ->where('matiere_id', $matiere_id)
            ->whereHas('examen', function ($query) use ($exam_code) {
                $query->where('code', $exam_code);
            })->first();

                    if ($note == null) {
                        $note = new Note();
                    }

                    $note->eleve_id = $eleve_id;
                    $note->matiere_id = $matiere_id;
                    $note->periode_id = $periode->id;
                    $note->examen_id = Examen::where('code', $exam_code)->firstOrFail()->id;
                    $note->valeur = $note_val;

                    $note->save();
                }
            }
        }
        
        DB::commit();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        //
    }

    public function classe(Classe $classe)
    {
        $periode = Periode::where('ouvert', true)->firstOrFail();
        return Note::with('examen')
            ->where('periode_id', $periode->id)
            ->whereHas('eleve', function ($query) use ($classe) {
                $query->where('classe_id', $classe->id);
            })
            ->get();
    }
}
