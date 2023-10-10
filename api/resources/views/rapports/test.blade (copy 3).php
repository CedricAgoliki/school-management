<html>
<head>
    <style>
    table { border-collapse: collapse; width: 100%; }
    tr, td { border: 1px solid #000; }
    .header{ font-weight: bold; }

    </style>
</head>
<body>
<div>
<span>Ecole Primaire Catholique Notre Dame de l'Esperance</span>
<span style="float: right">2020 - 2021</span>
</div>
    <h3 style="text-align: center">Liste des eleves</h3>
    <div style="margin-bottom: 5px;">
	<table>
	    <tr>
		<td>Classe</td>
		<td>Classe</td>
		<td>Classe</td>
		<td>Classe</td>
	    </tr>
	</table>
    </div>

    <table>
	<tr class="header">
	    <td>Nom</td>
	    <td>Prenoms</td>
	</tr>
	@foreach($eleves as $eleve)
	<tr>
	    <td>{{ $eleve->nom }}</td>
	    <td>{{ $eleve->prenom }}</td>
	</tr>
	@endforeach
    </table>

</body>
</html>
