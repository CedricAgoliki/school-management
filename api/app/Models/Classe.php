<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Eleve;
use App\Models\Niveau;

class Classe extends Model
{
    use HasFactory;

    public $fillable = ['id', 'libelle', 'description', 'niveau_id'];

    public static $storeRules = [
	'libelle' => 'required',
	'description' => '',
	'niveau_id' => 'required'
    ];
    public static $updateRules = [
	'libelle' => 'required',
	'description' => '' ,
	'niveau_id' => 'required'
    ];


    public function eleves()
    {
	return $this->hasMany(Eleve::class);
    }

    public function niveau()
    {
	return $this->belongsTo(Niveau::class);
    }

}
