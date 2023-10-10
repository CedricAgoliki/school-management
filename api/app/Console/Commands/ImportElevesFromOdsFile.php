<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Ods;
use App\Models\Classe;
use App\Models\Niveau;
use App\Models\Eleve;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ImportElevesFromOdsFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eleves:import {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'importer les eleves depuis un fichier .ods';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reader = new Ods();
        $spreadsheet = $reader->load($this->argument('file'));
        $sheets = $spreadsheet->getAllSheets();
        foreach ($sheets as $key => $sheet) {
            $classe = Str::of($sheet->getTitle())->trim();
            $niveau = Str::substr($classe, 0, Str::length($classe) - 2);
            $niveau = Niveau::where('libelle', $niveau)->first();
            if ($niveau == null) {
                // echo "$classe \n";
                $niveau = new Niveau();
                $niveau->libelle = Str::substr($classe, 0, Str::length($classe) - 2);
                $niveau->save();
            }
            $classe = Classe::where('libelle', $classe)->first();
            if ($classe == null) {
                $classe = new Classe();
                $classe->libelle = Str::of($sheet->getTitle())->trim();
                $classe->niveau_id = $niveau->id;
                $classe->save();
            }
            $data = $sheet->toArray();
            foreach ($data as $line) {
                $mat = $line[1];
                $nom = $line[2] . ' ' . $line[3] . ' ' . $line[4];
                if (Str::of($line[2])->trim()->lower() == "nom") {
                    continue;
                }
                $eleve = new Eleve();
                $eleve->matricule = $mat;
                $eleve->nom = Str::of($line[2])->trim();
                $eleve->prenom = Str::of($line[3]. ' '. $line[4])->trim();
                $eleve->naissance = Carbon::now();
                $eleve->niveau_id = $niveau->id;
                $eleve->classe_id = $classe->id;
                $eleve->save();
            }
        }
        echo "importation terminee\n";
        return 0;
    }
}
