<?php

namespace App\Http\Controllers;

use App\Models\Periode;

// use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Periode::whereHas('annee', function ($query) {
            $query->where('ouvert', true);
        })->get();
    }

    public function ouverture(Periode $periode)
    {
        $p = Periode::where('ouvert', true)->first();
        if ($p == null) {
            $periode->ouvert = true;
            $periode->save();
        } else {
            return response()->json(["message" => "Cloturez d'abord la periode en cours"], 500);
        }
    }

    public function fermeture()
    {
        $periode = Periode::where('ouvert', true)->firstOrFail();
        $periode->ouvert = false;
        $periode->save();
    }
}
