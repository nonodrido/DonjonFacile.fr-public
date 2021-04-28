<?php
$ajax=1;
session_start();
include('../includes/admin/f.php');
if(isset($_SESSION['user_id']) AND isset($_POST['fav_id']) AND filter_var($_POST['fav_id'], FILTER_VALIDATE_INT) AND !empty($_POST['type']))
	{
	$id=$_POST['fav_id'];
	$type=$_POST['type'];
	$bdd=connect();
	$req=$bdd->query('SELECT * FROM fav WHERE etat!="delete" AND user_id='.$_SESSION['user_id'].' AND fav_id='.$id.' AND type="'.$type.'"');
	if($req->rowCount()==0)
		{
		$bdd->exec('INSERT INTO fav (user_id,fav_id,type)VALUES('.$_SESSION['user_id'].','.$id.',"'.$type.'")');
		echo ajax::send('Ajouté à vos favoris');
		}
	else{
		$bdd->exec('DELETE FROM fav WHERE user_id='.$_SESSION['user_id'].' AND fav_id='.$id.' AND type="'.$type.'"');
		echo ajax::send('Supprimé de vos favoris');
		}
	$_SESSION['cache_time']=time();
	}
else{echo ajax::send('Erreur lors du traitement !','error');}