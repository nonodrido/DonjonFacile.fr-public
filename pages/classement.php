<?php
$titre='Classement du contenu';
verif_uri('/classement');
$req=$bdd->query('SELECT r.*,i.name,i.type,u.pseudo,SUM(r.rate) total,COUNT(r.rate) nbre_vote
						FROM item_rate r
						INNER JOIN item i ON i.id = r.item_id
						INNER JOIN users u ON u.id = i.auteur_id
						WHERE i.etat!="delete" AND i.auteur_id!=1
						GROUP BY r.item_id
						ORDER BY total DESC
						LIMIT 100');
echo '<h2>Classement du contenu du site :</h2>
		<table class="jtable table table-striped table-hover table-bordered">
			<thead><tr><th>Nombre de vote</th><th>Note (en % favorable)</th><th>Nom</th><th>Type</th><th>Créateur</th><th>Pour</th><th>Contre</th></tr></thead><tbody>';
$array_type=array('comp'=>'Compétence','arme'=>'Arme','protec'=>'Protection','divers'=>'Divers');
while($val=$req->fetch())
	{
	$pour=($val['nbre_vote']+$val['total'])/2;
	$contre=($val['nbre_vote']-$val['total'])/2;
	$note=round(($pour/$val['nbre_vote'])*100);
	echo '	<tr>
				<td>'.$val['nbre_vote'].'</td>
				<td>'.$note.' %</td>
				<td><a href="/item/'.$val['item_id'].'/'.to_url($val['name']).'">'.secure::html($val['name'],1).'</a></td>
				<td>'.$array_type[$val['type']].'</td>
				<td>'.secure::html($val['pseudo'],1).'</td>
				<td>'.$pour.'</td>
				<td>'.$contre.'</td>
			</tr>';
	}
echo '</tbody></table>';