<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professeur extends Model
{
    use HasFactory;

    public $fillable = [
	'id',
	'nom',
	'prenom',
	'contact',
	'sexe',
	'grade',
	'fonction',
	'diplome',
	'priseService',
	'dre',
	'iepp',
	'afffectationPoste',
	'retraite',
    ];

    public static $storeRules = [
	'nom' => 'required',
	'prenom' => 'required',
	'contact'=>'',
	'sexe'=>'',
	'grade'=>'',
	'fonction'=>'',
	'diplome'=>'',
	'priseService'=>'',
	'dre'=>'',
	'iepp'=>'',
	'afffectationPoste'=>'',
	'retraite'=>'',
    ];
    public static $updateRules = [
	'nom' => 'required',
	'prenom' => 'required',
	'contact' =>'',
	'sexe'=>'',
	'grade'=>'',
	'fonction'=>'',
	'diplome'=>'',
	'priseService'=>'',
	'dre'=>'',
	'iepp'=>'',
	'afffectationPoste'=>'',
	'retraite'=>'',
    ];
}
