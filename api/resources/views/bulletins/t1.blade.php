<html>
<head>
    <style>
    table { border-collapse: collapse; width: 100%; }
    tr, td { border: 1px solid #000; }
    .header{ font-weight: bold; }

    </style>
</head>
<body>
<?php $eleves = $classe->eleves->append('moyenne')->sortByDesc('moyenne');
$rang = 1;
$oldmoy = null;
 ?>

@foreach($eleves as $eleve)

<div style="border: 1px solid #000; padding: 10px; margin: 10px; height: 640px; position: relatiive;">
    <div>
	<span style="font-size: 11px;"><small>Ecole Primaire Catholique Notre Dame de l'Esperance - Kpogan</small></span>
	<span style="float: right; font-size: 11px;">Annee scolaire 2020 - 2021</span>
	<div><img src="./images/logo.jpg" style="width: 100px;"/></div>
	<div style="text-align: center;">
		<div style="font-weight: bold; font-size: 20px">Bulletin d'evaluation</div>
	</div>
	    <?php 
		if ($oldmoy != null && $eleve->moyenne < $oldmoy) {
		    $rang++;
		}
	    ?>
	<table style="margin-bottom: 10px; font-weight: bold">
	    <tr>
		<td>{{ $eleve->nom . ' ' . $eleve->prenom }}</td>
		<td>{{ $classe->libelle }}</td>
		<td>{{ $periode->libelle }}</td>
		<td>Rang: {{ $rang . ($rang == 1? ' er(e)' : ' eme') }}</td>
		<?php $oldmoy = $eleve->moyenne ?>
	    </tr>
	</table>
    </div>
    <div>
	<table>
	    <tr class="header">
		<td>Matiere</td>
		<td colspan="3">Notes de classe</td>
		<td>Composition</td>
		<td>Coef.</td>
		<td>Note definitive</td>
	    </tr>
	    {{ $totalcoef = 0}}
	    @foreach($matieres as $matiere)
		{{ $totalcoef += (int) $matiere->coef }}
	    @endforeach
	    {{ $totalcomp = 0 }}
	    @foreach($matieres as $matiere)
		{{ $noteev1 = $matiere->notes()->where('periode_id', $periode->id)->where('examen_id', $ev1->id)->first() }}
		{{ $noteev2 = $matiere->notes()->where('periode_id', $periode->id)->where('examen_id', $ev2->id)->first() }}
		{{ $noteev3 = $matiere->notes()->where('periode_id', $periode->id)->where('examen_id', $ev3->id)->first() }}
		{{ $notecomp = $matiere->notes()->where('periode_id', $periode->id)->where('examen_id', $comp->id)->first() }}
		{{ $totalcomp += ($notecomp == null ? 0 : (int) $notecomp->valeur)  * (int) $matiere->coef }}
	    <tr>
		<td>{{ $matiere->nom  }}</td>
		<td>{{ $noteev1 == null ? '' : $noteev1->valeur }}</td>
		<td>{{ $noteev2 == null ? '' : $noteev2->valeur }}</td>
		<td>{{ $noteev3 == null ? '' : $noteev3->valeur }}</td>
		<td>{{ $notecomp == null? '' : $notecomp->valeur }}</td>
		<td>{{ $matiere->coef }}</td>
		<td>{{ ($notecomp == null ? 0 : (int)$notecomp->valeur) * (int) $matiere->coef }}</td>
	    </tr>
	    @endforeach
	    <tr>
		<td><b>Total</b></td>
		<td colspan="4"></td>
		<td>{{ $totalcoef }}</td>
		<td>{{ $totalcomp }}</td>
	    </tr>
	    <tr>
		<td><b>Moyenne</b></td>
		<td colspan="4"></td>
		<?php $moyenne = round((float)$totalcomp / $totalcoef, 2) ?>
		<td colspan="2" style="text-align: center"><b>{{ $moyenne }}</b></td>
	    </tr>

	</table>
    </div>
    <div style="margin-top: 20px; position: absolute; top: 503px; left: 50px">
	<table style="margin-bottom: 5px;">
	    <tr>
		<td colspan="5"><b>APPRECIATION</b></td>
	    </tr>
	    <tr>
		<td>Insuffisant</td>
		<td>Moyen</td>
		<td>Assez bon travail</td>
		<td>Bon travail</td>
		<td>Excellent</td>
	    </tr>
	</table>

	<table>
	    <tr>
		<td colspan="5"><b>CONDUITE</b></td>
	    </tr>
	    <tr>
		<td>Avertissement</td>
		<td>A ameliorer</td>
		<td>Assez bonne</td>
		<td>Bonne</td>
		<td>Tres bonne</td>
	    </tr>
	</table>
    </div>
    <div style="margin-top: 20px; position: absolute; top: 580px; left: 50px">
	<div style="display: inline-block; vertical-align:top; width: 45%; margin-right: 5%;">
	    <table style="text-align: center;">
		<tr>
		    <td colspan="4"><b>Recapitulatif</b></td>
		</tr>
		<tr>
		    <td>Trimestre 1</td>
		    <td>Trimestre 2</td>
		    <td>Trimestre 3</td>
		    <td><b>Moy. annuelle</b></td>
		</tr>
		<tr>
		    <td>{{ $moyenne }}</td>
		    <td></td>
		    <td></td>
		    <td><b></b></td>
		</tr>
	    </table>
	</div>
	<div style="display: inline-block; vertical-align:top; width: 45%;">
	    <table style="text-align: center;">
		<tr>
		    <td>PASSE</td>
		    <td>REDOUBLE</td>
		</tr>
	    </table>
	</div>
    </div>
</div>
@endforeach
</body>
</html>
