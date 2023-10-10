<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Annee;
// use Illuminate\Http\Request;
use App\Http\Requests\StorePaiementRequest;
use App\Http\Requests\UpdatePaiementRequest;

class PaiementController extends Controller
{
    public function index()
    {
        return Paiement::all();
    }

    public function store(StorePaiementRequest $request)
    {
        $data = $request->validated();
        $paiement = new Paiement;
        $paiement->fill($data);

        $annee = Annee::where('ouvert', true)->first();
        $paiement->annee_id = $annee->id;

        $paiement->save();
    }

    public function show(Paiement $paiement)
    {
        return $paiement;
    }

    public function update(UpdatePaiementRequest $request, Paiement $paiement)
    {
        $data = $request->validated();
        $paiement->fill($data);
        $paiement->save();
    }

    public function destroy(Paiement $paiement)
    {
        $paiement->delete();
    }
}
