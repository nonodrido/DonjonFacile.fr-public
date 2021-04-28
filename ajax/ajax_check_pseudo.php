<?php
$ajax=1;
include('../includes/admin/f.php');
$bdd=connect();


if(isset($_POST['username']))
	{
	$username = $_POST['username'];
	$req=$bdd->prepare('SELECT * FROM users WHERE etat!="delete" AND pseudo = :pseudo');
	$req->execute(array('pseudo' => $username));
	if($req->rowCount() != 0)
		{
		echo '<span class="alert alert-error"><b>'.$username.'</b> est indisponible.</span>';
		}
	else
		{
		echo 'OK';
		}
	}

?>