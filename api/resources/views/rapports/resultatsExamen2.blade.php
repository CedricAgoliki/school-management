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
		<td><b>Classe</b></td>
		<td><b>sexe</b></td>
	    </tr>
	    <tr>
		<td>{{ $filtre['classe']->libelle }}</td>
		<td>{{ $filtre['sexe'] ? $filtre['sexe'] : 'Tous' }}</td>
	    </tr>
	</table>
    </div>

    <table>
	<tr class="header">
	    <td>#</td>
	    <td>Nom</td>
	    <td>Prenoms</td>
	    <td>Moyenne</td>
	    <td>Sexe</td>
	    <td>Age</td>
	</tr>
	<?php $rang =  1; $oldmoy = null; ?>
	@foreach($eleves as $index => $eleve)
<?php
        if ($oldmoy == null) {
            $oldmoy = $eleve->moyenne;
        } elseif ($oldmoy < $eleve->moyenne) {
            $rang++;
            $oldmoy = $eleve->moyenne;
        }

        ?>
	<tr>
	    <td>{{ $rang }}</td>
	    <td>{{ $eleve->nom }}</td>
	    <td>{{ $eleve->prenom }}</td>
	    <td>{{ $eleve->moyenne }}</td>
	    <td>{{ $eleve->sexe }}</td>
	    <td>{{ $eleve->age }}</td>
	</tr>
	@endforeach
	<tr>
	    <td><b>TOTAL</b></td>
	    <td colspan="5" style="text-align: center;"><b>{{ $eleves->count() }}</b></td>
	</tr>
    </table>

</body>
</html>
