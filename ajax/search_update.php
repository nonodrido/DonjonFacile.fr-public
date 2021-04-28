<?php
$ajax=1;
include('../includes/admin/f.php');
$bdd=connect();
$tables=array('sorts', 'comp','prodiges','encyclo','armement','protec'); //liste des tables à indexer
$i=0;$list_id=array();$date=date('y-m-j h:i:s');
$bdd->exec('UPDATE search SET date = "'.$date.'" WHERE id = "1"');
foreach($tables as $table)
	{
	$reponse = $bdd->query('SELECT * FROM '.$table);
	while ($donnees = $reponse->fetch())
		{
		$i++;
		$req='INSERT INTO search VALUES("",:date,default,:table,:id_table,:id_user,:name,:descr,:tags)ON DUPLICATE KEY UPDATE id_user=:id_user,date=:date, name=:name, descr=:descr, tags=:tags';
		try
		{
		$req=$bdd->prepare($req);
		$req->execute(array('date' => $date,
						'table' => $table,
						'id_table' => $donnees['id'],
						'name' => htmlspecialchars_decode($donnees['name']),
						'descr' => htmlspecialchars_decode($donnees['descr']),
						'tags' => htmlspecialchars_decode(str_replace('  ',' ',preg_replace("# [[:alnum:A-Zà]]{1,3}[\.,;:]? #",  "", str_replace(' ','  ',$donnees['descr'])))),
						'id_user' => $donnees['auteur_id']
						));
		}
		catch(Exception $e){die('Erreur : '.$e->getMessage());}
		$req='INSERT INTO id_list VALUES(:id,:date,default,:table,:id_table,:id_user,:name)ON DUPLICATE KEY UPDATE id_user=:id_user,date=:date,name=:name';
		try
		{
		$id=$bdd->lastInsertId();
		$req=$bdd->prepare($req);
		$req->execute(array('id' => $id,
						'date' => $date,
						'table' => $table,
						'id_table' => $donnees['id'],
						'id_user' => $donnees['auteur_id'],
						'name' => $donnees['name']
						));
		}
		catch(Exception $e){die('Erreur : '.$e->getMessage());}
		}
	}
try{
$reponse = $bdd->query('SELECT date FROM search WHERE id= 1 ');
$reponse = $reponse->fetch();
if($reponse['date'] != 'NULL'){
$req=$bdd->prepare('UPDATE search SET etat = "suppr" WHERE date != :date');
$req->execute(array('date' => $reponse['date']));
$req=$bdd->prepare('UPDATE id_list SET etat = "suppr" WHERE date != :date');
$req->execute(array('date' => $reponse['date']));
}}
catch(Exception $e){die('Erreur : '.$e->getMessage());}

echo $i.' entrées ajoutées ou mise à jour '.$reponse['date'];