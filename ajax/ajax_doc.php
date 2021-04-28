<?php
$ajax=1;
session_start();
include('../includes/admin/f.php');
if(isadmin() AND isset($_POST['id']) AND isset($_POST['orig']) AND isset($_POST['type']))
	{
	$bdd=connect();
	$bdd->exec('UPDATE fichiers SET orig="'.$_POST['orig'].'",type="'.$_POST['type'].'" WHERE id='.$_POST['id']);
	echo ajax::send('Modification effectuée !','success');
	}
else{echo ajax::send('Vous n\'êtes pas administrateur !','error');}