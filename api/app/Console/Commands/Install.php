<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Examen;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'installer le logiciel';

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
	Examen::firstOrCreate(['code' => 'ev1', 'libelle' => 'Evaluation 1']);
	Examen::firstOrCreate(['code' => 'ev2', 'libelle' => 'Evaluation 2']);
	Examen::firstOrCreate(['code' => 'ev3', 'libelle' => 'Evaluation 3']);
	Examen::firstOrCreate(['code' => 'comp', 'libelle' => 'Composition']);

	// new user
	$user = User::where('nom', 'admin')->first();
	if ($user == null) {
	    $user = new User;
	    $user->nom = "admin";
	    $user->username = "admin";
	    $user->role = "admin";
	    $user->password = Hash::make("admin");
	    $user->deletable = false;
	    $user->save();	
	}

	echo "Installation terminee\n";

        return 0;
    }
}
