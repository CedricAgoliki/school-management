<?php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnneeController extends Controller
{
    public function ouverture(Request $request)
    {
        $annee = Annee::where('ouvert', true)->count();
        if ($annee == 0) {
            DB::begintransaction();
            $annee = Annee::where('annee', $request->annee)->first();
            if ($annee == null) {
                $annee = new Annee();
            }
            $annee->annee = $request->annee;
            $annee->ouvert = true;
            $annee->save();

            $t1 = Periode::where('annee_id', $annee->id)->where('libelle', 'Premier trimestre')->first();
            if ($t1 == null) {
                $t1 = new Periode;
            }

            $t2 = Periode::where('annee_id', $annee->id)->where('libelle', 'Deuxieme trimestre')->first();
            if ($t2 == null) {
                $t2 = new Periode;
            }

            $t3 = Periode::where('annee_id', $annee->id)->where('libelle', 'Troisieme trimestre')->first();
            if ($t3 == null) {
                $t3 = new Periode;
            }

            $t1->libelle = 'Premier trimestre';
            $t2->libelle = 'Deuxieme trimestre';
            $t3->libelle = 'Troisieme trimestre';

            $t1->ouvert = false;
            $t2->ouvert = false;
            $t3->ouvert = false;

            $t1->annee_id = $annee->id;
            $t2->annee_id = $annee->id;
            $t3->annee_id = $annee->id;

            $t1->save();
            $t2->save();
            $t3->save();
            DB::commit();
        } else {
            return response()->json(["message" => "Cloturez d'abord l'annee en cours"], 500);
        }
    }


    public function fermeture(Request $request)
    {
        DB::begintransaction();
        $annee = Annee::where('ouvert', true)->firstOrFail();
        Periode::query()->update(['ouvert' => false]);
        Annee::query()->update(['ouvert' => false]);
        DB::commit();
    }


    public function currentYear()
    {
        return Annee::where('ouvert', true)->first();
    }


    public function normalize()
    {
    }
}
