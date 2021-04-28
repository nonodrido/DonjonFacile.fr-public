<?php
include('../includes/admin/f.php');
$bdd=connect();
$tables=array('sorts', 'comp','prodiges','encyclo','armement'); //liste des tables à indexer
$bdd->exec('DELETE FROM search');
$bdd->exec('ALTER TABLE search AUTO_INCREMENT=0');
$bdd->exec('ALTER TABLE search DROP INDEX name');
$i=0;
foreach($tables as $table)
	{
	$reponse = $bdd->query('SELECT * FROM '.$table.' WHERE auteur_id=1');
	while ($donnees = $reponse->fetch())
		{
		$i++;
		$req='INSERT INTO search VALUES("",:table,:id_table,:name,:descr)';
		try
		{
		$req=$bdd->prepare($req);
		$req->execute(array('table' => $table,
						'id_table' => $donnees['id'],
						'name' => htmlspecialchars_decode($donnees['name']),
						'descr' => htmlspecialchars_decode($donnees['descr']),
						));
		}
		catch(Exception $e){die('Erreur : '.$e->getMessage());}
		}
	}
$bdd->exec('ALTER table search ADD fulltext(name,descr)');
echo $i.' entrées ajoutées ou mise à jour';