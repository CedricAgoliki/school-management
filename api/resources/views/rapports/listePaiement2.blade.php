<html>
<head>
    <style>
    table { border-collapse: collapse; width: 100%; }
    tr, td { border: 1px solid #000; }
    .header{ font-weight: bold; }

    </style>
</head>
<body>
<header>
    <div>
	<span style="font-size: 11px;">Ecole Primaire Catholique Notre Dame de l'Esperance - Kpogan</small></span>
	<span style="float: right; font-size: 11px;">Annee scolaire 2020 - 2021</span>
    </div>
    <img src="./images/logo.jpg" style="width: 100px;"/>
</header>
    <h3 style="text-align: center">Liste des eleves</h3>
    <div style="margin-bottom: 5px;">
	<table>
	    <tr>
		<td><b>Niveau</b></td>
		<td><b>Classe</b></td>
		<td><b>Montant</b></td>
	    </tr>
	    <tr>
		<td>{{ $filtre['niveau'] ? $filtre['niveau']->libelle : 'Tous' }}</td>
		<td>{{ $filtre['classe'] ? $filtre['classe']->libelle : 'Tous' }}</td>
		<td>de {{ $filtre['montantMin'] }} a {{ $filtre['montantMax'] }} FCFA</td>
	    </tr>
	</table>
    </div>

    <table>
	<tr class="header">
	    <td>Nom</td>
	    <td>Prenoms</td>
	    <td>Classe</td>
	    <td>Montant</td>
	    <td>1 tranc</td>
	    <td>2 tranc</td>
	    <td>3 tranc</td>
	    <td>Fonc</td>
	    <td>APE</td>
	    <td>Insc</td>
	</tr>
	<?php $montantTotal = 0; ?>
	@foreach($eleves as $eleve)
	<tr>
	    <td>{{ $eleve->nom }}</td>
	    <td>{{ $eleve->prenom }}</td>
	    <td>{{ $eleve->classe->libelle }}</td>
	    <?php $montant = $eleve->paiements()->where('annee_id', $annee->id)->get()->sum('montant');
            
     $montantTotal += $montant; ?>
	    <td>{{ $montant }}</td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>

	</tr>
	@endforeach
	<tr>
	    <td><b>TOTAL</b></td>
	    <td style="text-align: center;"><b>{{ $eleves->count() }}</b></td>
	    <td colspan="2" style="text-align: center;"><b>{{ $montantTotal }}</b></td>
	    <td  style="text-align: center;"></td>
	    <td  style="text-align: center;"></td>
	    <td  style="text-align: center;"></td>
	    <td  style="text-align: center;"></td>
	    <td  style="text-align: center;"></td>
	    <td  style="text-align: center;"></td>
	</tr>
    </table>

</body>
</html>
