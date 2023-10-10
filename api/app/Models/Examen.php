<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    public $fillable = ['id', 'libelle', 'code', 'periode_id', 'matiere_id'];

    public static $storeRules = ['libelle' => 'required'];
    public static $updateRules = ['libelle' => 'required'];
}
