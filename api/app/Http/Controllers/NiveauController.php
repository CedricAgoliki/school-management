<?php

namespace App\Http\Controllers;

use App\Models\Niveau;
use App\Models\Eleve;
// use Illuminate\Http\Request;

use App\Http\Requests\StoreNiveauRequest;
use App\Http\Requests\UpdateNiveauRequest;

class NiveauController extends Controller
{
    public function index()
    {
        return Niveau::all();
    }

    public function store(StoreNiveauRequest $request)
    {
        $data = $request->validated();
        Niveau::create($data);
    }

    public function show(Niveau $niveau)
    {
        return $niveau;
    }

    public function update(UpdateNiveauRequest $request, Niveau $niveau)
    {
        $data = $request->validated();
        $niveau->fill($data);
        $niveau->save();
    }

    public function destroy(Niveau $niveau)
    {
        $niveau->delete();
    }

    public function eleves(Niveau $niveau)
    {
        return Eleve::where('niveau_id', $niveau->id)->whereNull('dateDepart')->get();
        /*$eleves = [];
        $classes = $niveau->classes()->whereNull('dateDepart')->get();

        foreach ($classes as $classe) {
            $eleves = array_merge($eleves, $classe->eleves()->get()->all());
        }

        return $eleves;*/
    }
}
