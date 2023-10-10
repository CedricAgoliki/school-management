<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    public $fillable = ['montant', 'eleve_id', 'annee_id', 'infos', 'date'];

    public static $storeRules = ['montant' => 'required', 'eleve_id' => 'required', 'infos'=> '', 'date' => 'required'];
    public static $updateRules = ['montant' => 'required', 'eleve_id' => 'required', 'infos' => '', 'date' => 'required'];

}
