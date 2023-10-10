<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Eleve;
use App\Models\Examen;
use App\Models\Matiere;
use App\Models\Periode;

class Note extends Model
{
    use HasFactory;

    public function eleve()
    {
	return $this->belongsTo(Eleve::class);
    }

    public function examen()
    {
	return $this->belongsTo(Examen::class);
    }

    public function matiere()
    {
	return $this->belongsTo(Matiere::class);
    }

    public function periode()
    {
	return $this->belongsTo(Periode::class);
    }
}
