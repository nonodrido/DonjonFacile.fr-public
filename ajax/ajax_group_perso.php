<?php
$ajax=1;
session_start();
include('../includes/admin/f.php');
// print_r($_POST);
// exit;
$bdd=connect();
if(isset($_SESSION['user_id']) AND !empty($_POST['perso_id']) AND isset($_POST['group_id']) AND filter_var($_POST['group_id'], FILTER_VALIDATE_INT) 
AND filter_var($_POST['perso_id'], FILTER_VALIDATE_INT) AND get_droits_perso($_POST['perso_id'],$_SESSION['user_id'],'wiew'))
	{
	$req=$bdd->query('SELECT * FROM `group` WHERE user_id='.$_SESSION['user_id'].' AND id='.$_POST['group_id']);
	if($req->rowcount() != 0)
		{
		try{
			$bdd->exec('INSERT INTO group_perso (group_id,perso_id) VALUES ('.$_POST['group_id'].','.$_POST['perso_id'].')');
			echo ajax::send('Ce personnage a rejoint cette compagnie !');
			}
		catch (Exception $e)
			{
			echo ajax::send('Ce personnage appartient déjà à cette compagnie','warning');
			}
		}
	else{echo ajax::send('Aucun personnage trouvé !','error');}
	}
else{echo ajax::send('Erreur lors de l\'opération !','error');}