<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Periode;

class Annee extends Model
{
    use HasFactory;

    public function periodes()
    {
	return $this->hasMany(Periode::class);
    }
}
