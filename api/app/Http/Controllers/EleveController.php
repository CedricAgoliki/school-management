<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Annee;
use App\Models\Paiement;
use App\Models\Niveau;
use App\Models\Classe;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEleveRequest;
use App\Http\Requests\UpdateEleveRequest;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class EleveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Eleve::with('classe')->whereNull('dateDepart')->get();
    }

    public function elevesEtPaiements()
    {
        return Eleve::with('classe')->with('paiements')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEleveRequest $request)
    {
        $data = $request->validated();
        //return $data;
        Eleve::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eleve  $eleve
     * @return \Illuminate\Http\Response
     */
    public function show(Eleve $eleve)
    {
        return $eleve;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Eleve  $eleve
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEleveRequest $request, Eleve $eleve)
    {
        $data = $request->validated();
        $eleve->fill($data);
        $eleve->save();
    }

    public function sortie(Request $request, Eleve $eleve)
    {
        $data = $request->all();
        $eleve->fill($data);
        $eleve->save();
    }

    public function saveall(Request $request)
    {
        $data = $request->data;
        DB::begintransaction();
        foreach ($data as $e) {
            $eleve = Eleve::find($e['id']);
            $eleve->nom = $e['nom'];
            $eleve->prenom = $e['prenom'];
            $eleve->naissance = $e['naissance'];
            $eleve->sexe = $e['sexe'];
            $classe = Classe::find($e['classe_id']);
            $eleve->classe_id = $classe->id;
            $eleve->niveau_id = $classe->niveau_id;
            $eleve->save();
        }
        DB::commit();
    }

    public function annulerSortie(Eleve $eleve)
    {
        $eleve->dateDepart = null;
        $eleve->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eleve  $eleve
     * @return \Illuminate\Http\Response
     */
    public function destroy(Eleve $eleve)
    {
        $eleve->delete();
    }

    public function print(Request $request)
    {
        $eleves = $this->filtre($request);
        $filtre = $request->all();

        if ($filtre['niveau']) {
            $filtre['niveau'] = Niveau::find($filtre['niveau']);
        }
        if ($filtre['classe']) {
            $filtre['classe'] = Classe::find($filtre['classe']);
        }
        //return $request->all();

        //	return view('rapports.test', compact('eleves'));
        if ($filtre['type'] == 'pdf') {
            $pdf = Pdf::loadView('rapports.listeEleve', compact('eleves', 'filtre'));

            return $pdf->stream('rapport.pdf', ['Attachment' => false]);
        }
        //return 'hey';
        $response = new StreamedResponse();
        $response->setCallback(function () use ($filtre, $eleves) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Nom');
            $sheet->setCellValue('B1', 'Prenom');
            $sheet->setCellValue('C1', 'Sexe');
            $sheet->setCellValue('D1', 'Naissance');
            $sheet->setCellValue('E1', 'Age');
            $sheet->setCellValue('F1', 'Classe');
            $i = 2;
            // $montantTotal = 0;
            foreach ($eleves as $eleve) {
                $sheet->setCellValue("A$i", $eleve->nom);
                $sheet->setCellValue("B$i", $eleve->prenom);
                $sheet->setCellValue("C$i", $eleve->sexe);

                $ddate = Carbon::parse($eleve->naissance);
                $day = $ddate->format('d');
                $month = $ddate->format('m');
                $year = $ddate->format('Y');

                $sheet->setCellValue("D$i", '=DATE('.$year.','.$month.','. $day.')');
                //$sheet->setCellValue("D$i", 'hey');
                $sheet->setCellValue("E$i", "=INT(TODAY()-D$i)/365");
                $sheet->setCellValue("F$i", $eleve->classe->libelle);
                $i++;
            }
            $sheet->setCellValue("A$i", 'Total');
            $sheet->setCellValue("B$i", $eleves->count());
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="rapport.xlsx"');
        return $response->send();
    }

    public function printPaiement(Request $request)
    {
        $eleves = $this->filtreSurPaiements($request);
        $filtre = $request->all();
        if ($filtre['niveau']) {
            $filtre['niveau'] = Niveau::find($filtre['niveau']);
        }
        if ($filtre['classe']) {
            $filtre['classe'] = Classe::find($filtre['classe']);
        }
        $annee = Annee::where('ouvert', true)->firstOrFail();
        //return $request->all();

        //	return view('rapports.test', compact('eleves'));
        if ($filtre['type'] == 'pdf') {
            if ($filtre['format'] == 1) {
                $pdf = Pdf::loadView('rapports.listePaiement', compact('eleves', 'filtre', 'annee'));
            } else {
                $pdf = Pdf::loadView('rapports.listePaiement2', compact('eleves', 'filtre', 'annee'));
                $pdf->setPaper('A4', 'landscape');
            }


            return $pdf->stream('rapport.pdf', ['Attachment' => false]);
        }

        $response = new StreamedResponse();
        $response->setCallback(function () use ($filtre, $eleves, $annee) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Nom');
            $sheet->setCellValue('B1', 'Prenom');
            $sheet->setCellValue('C1', 'Classe');
            $sheet->setCellValue('D1', 'Montant');
            if ($filtre['format'] == 2) {
                $sheet->setCellValue('E1', '1 tranc');
                $sheet->setCellValue('F1', '2 tranc');
                $sheet->setCellValue('G1', '3 tranc');
                $sheet->setCellValue('H1', 'Fonc');
                $sheet->setCellValue('I1', 'APE');
                $sheet->setCellValue('J1', 'Insc');
            }
            $i = 2;
            // $montantTotal = 0;
            foreach ($eleves as $eleve) {
                $sheet->setCellValue("A$i", $eleve->nom);
                $sheet->setCellValue("B$i", $eleve->prenom);
                $sheet->setCellValue("C$i", $eleve->classe->libelle);

                $montant = $eleve->paiements()->where('annee_id', $annee->id)->get()->sum('montant');
                // $montantTotal += $montant;
                $sheet->setCellValue("D$i", $montant);
                $i++;
            }
            $sheet->setCellValue("A$i", 'Total');
            $sheet->setCellValue("B$i", $eleves->count());
            $sheet->setCellValue("D$i", '=SUM(D2:D'.($i - 1).')');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="rapport.xlsx"');
        return $response->send();
    }


    public function printResultats(Request $request)
    {
        $eleves = $this->resultats($request, 1);
        $filtre = $request->all();
        if ($filtre['classe']) {
            $filtre['classe'] = Classe::find($filtre['classe']);
        }

        if ($filtre['type'] == 'pdf') {
            $pdf = Pdf::loadView('rapports.resultatsExamen', compact('eleves', 'filtre'));

            return $pdf->stream('rapport.pdf', ['Attachment' => false]);
        }
        $response = new StreamedResponse();
        $response->setCallback(function () use ($filtre, $eleves) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', '#');
            $sheet->setCellValue('B1', 'Nom');
            $sheet->setCellValue('C1', 'Prenom');
            $sheet->setCellValue('D1', 'Moyenne');
            $sheet->setCellValue('E1', 'Sexe');
            $sheet->setCellValue('F1', 'Age');
            $i = 2;
            // $montantTotal = 0;
            $rang =  1;
            $oldmoy = null;
            foreach ($eleves as $eleve) {
                if ($oldmoy == null) {
                    $oldmoy = $eleve->moyenne;
                } elseif ($oldmoy < $eleve->moyenne) {
                    $rang++;
                    $oldmoy = $eleve->moyenne;
                }

                $sheet->setCellValue("A$i", $rang);
                $sheet->setCellValue("B$i", $eleve->nom);
                $sheet->setCellValue("C$i", $eleve->prenom);
                $sheet->setCellValue("D$i", $eleve->moyenne);
                $sheet->setCellValue("E$i", $eleve->sexe);
                $sheet->setCellValue("F$i", $eleve->age);
                $i++;
            }
            $sheet->setCellValue("A$i", 'Total');
            $sheet->setCellValue("C$i", $eleves->count());
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="rapport.xlsx"');
        return $response->send();
    }


    public function filtre(Request $request)
    {
        $nom = strtoupper(trim($request->nom));
        $niveau = $request->niveau;
        $classe = $request->classe;
        $sexe = $request->sexe;
        $ageMin = $request->ageMin;
        $ageMax = $request->ageMax;
        $parti = filter_var($request->parti, FILTER_VALIDATE_BOOLEAN);

        $eleves = Eleve::with('classe');

        if ($parti == true) {
            $eleves = $eleves->whereNotNull('dateDepart');
        } else {
            $eleves = $eleves->whereNull('dateDepart');
        }

        if (Str::of($nom)->trim()->length() > 0) {
            $eleves = $eleves->where('nom', Str::upper($nom));
        }
        if ($niveau !== null) {
            $eleves = $eleves->where('niveau_id', $niveau);
        }
        if ($classe !== null) {
            $eleves = $eleves->where('classe_id', $classe);
        }
        if ($sexe !== null) {
            $eleves = $eleves->where('sexe', $sexe);
        }

        $eleves = $eleves->whereRaw('TIMESTAMPDIFF(YEAR, naissance, CURDATE()) between ? and ?', [$ageMin, $ageMax]);

        return $eleves->orderBy('nom')->get();
    }


    public function paiements(Eleve $eleve)
    {
        $annee = Annee::where('ouvert', true)->firstOrFail();
        return Paiement::where('eleve_id', $eleve->id)->where('annee_id', $annee->id)->get();
    }


    public function filtreSurPaiements(Request $request)
    {
        $nom = $request->nom;
        $classe = $request->classe;
        $niveau = $request->niveau;
        $montantMin = $request->montantMin;
        $montantMax = $request->montantMax;

        $eleves = Eleve::with(['classe' => function ($q) {
            $q->orderBy('libelle');
        }]);

        if (strlen(trim($nom)) > 0) {
            $eleves = $eleves->where('nom', $nom);
        }
        if ($niveau !== null) {
            $eleves = $eleves->where('niveau_id', $niveau);
        }
        if ($classe !== null) {
            $eleves = $eleves->where('classe_id', $classe);
        }


        if ($montantMin == 0 || $montantMax == 0) {
            $eleves = $eleves->where(function ($query) use ($montantMin, $montantMax) {
                $query->doesntHave('paiements')->orWhereHas('paiements', function ($query) use ($montantMin, $montantMax) {
                    $query->havingRaw('SUM(montant) between ? and ?', [$montantMin, $montantMax]);
                });
            });
        } else {
            $eleves = $eleves->whereHas('paiements', function ($query) use ($montantMin, $montantMax) {
                $query->havingRaw('SUM(montant) between ? and ?', [$montantMin, $montantMax]);
            });
        }

        return $eleves->orderBy('nom')->get();
    }


    public function resultats(Request $request, int $in = 0)
    {
        $classe = $request->classe;
        $sexe = $request->sexe;
        $eleves =  Eleve::select('id', 'nom', 'prenom', 'sexe', 'classe_id')->with('classe')
        ->where('classe_id', $classe)
        ->whereNull('dateDepart');

        if ($sexe !== null) {
            $eleves = $eleves->where('sexe', $sexe);
        }

        if ($in == 0) {
            return array_values($eleves->get()->append('moyenne')->sortByDesc('moyenne')->toArray());
        }

        return $eleves->get()->append('moyenne')->sortByDesc('moyenne');
    }
}
