<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Note;
use App\Models\Niveau;

class Matiere extends Model
{
    use HasFactory;
    public $fillable = ['id', 'code', 'nom', 'coef', 'niveau_id'];

    public static $storeRules = ['code' => '', 'nom' => 'required', 'coef' => 'required', 'niveau_id' => 'required'];
    public static $updateRules = ['code' => '', 'nom' => 'required', 'coef' => 'required', 'niveau_id' => 'required'];


    public function notes()
    {
	return $this->hasMany(Note::class);
    }

    public function niveau()
    {
	return $this->belongsTo(Niveau::class);
    }
}
