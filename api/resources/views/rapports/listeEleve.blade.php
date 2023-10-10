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
		<td><b>Sexe</b></td>
		<td><b>Age</b></td>
	    </tr>
	    <tr>
		<td>{{ $filtre['niveau'] ? $filtre['niveau']->libelle : 'Tous' }}</td>
		<td>{{ $filtre['classe'] ? $filtre['classe']->libelle : 'Tous' }}</td>
		<td>{{ $filtre['sexe'] ? $filtre['sexe'] : 'Tous' }}</td>
		<td>de {{ $filtre['ageMin'] }} a {{ $filtre['ageMax'] }} ans</td>
	    </tr>
	</table>
    </div>

    <table>
	<tr class="header">
	    <td>Nom</td>
	    <td>Prenoms</td>
	    <td>Sexe</td>
	    <td>Age</td>
	    <td>Classe</td>
	</tr>
	@foreach($eleves as $eleve)
	<tr>
	    <td>{{ $eleve->nom }}</td>
	    <td>{{ $eleve->prenom }}</td>
	    <td>{{ $eleve->sexe }}</td>
	    <td>{{ $eleve->age }}</td>
	    <td>{{ $eleve->classe->libelle }}</td>
	</tr>
	@endforeach
	<tr>
	    <td><b>TOTAL</b></td>
	    <td colspan="4" style="text-align: center;"><b>{{ $eleves->count() }}</b></td>
	</tr>
    </table>

</body>
</html>
