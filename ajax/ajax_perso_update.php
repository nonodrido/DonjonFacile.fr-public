<?php
$ajax=1;
@session_start();
@include('../includes/admin/f.php');
$bdd=connect();
if(isset($_POST['perso_id']) AND filter_var($_POST['perso_id'], FILTER_VALIDATE_INT))
	{
	$val=$bdd->query('SELECT * FROM perso WHERE etat!="delete" AND id='.$_POST['perso_id'])->fetch();
	if(isset($val['date']))
		{
		echo strtotime($val['date']);
		}
	else{
		echo 'personnage supprimé';
		}
	}
else{
	echo 'erreur';
	}