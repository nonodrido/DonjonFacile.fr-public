<?php
$ajax=1;
session_start();
include('../includes/admin/f.php');
if(isset($_POST['item_id']) AND isset($_POST['rate']) AND ($_POST['rate']==1 OR $_POST['rate']==-1) AND filter_var($_POST['item_id'],FILTER_VALIDATE_INT) AND isuser())
	{
	$bdd=connect();
	$bdd->exec('INSERT INTO item_rate VALUES(NOW(),'.$_POST['item_id'].','.$_SESSION['user_id'].','.$_POST['rate'].') 
		ON DUPLICATE KEY UPDATE rate='.$_POST['rate']);
	echo ajax::send('Votre vote a été pris en compte !');
	}
else{echo ajax::send('Vous n\'êtes pas connecté !','error');}