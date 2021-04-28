<?php
$ajax=1;
@include('../includes/admin/f.php');
if(!empty($_POST['term']) OR !empty($_GET['term']))
	{
	if(!empty($_POST['term'])){$term=$_POST['term'];}else{$term=$_GET['term'];}
	$bdd=connect();
	$rep = $bdd->prepare('SELECT pseudo FROM users WHERE pseudo LIKE ? AND etat!="delete" LIMIT 10');
	$rep->execute(array('%'.$term.'%'));
	$result=$rep->fetchAll(PDO::FETCH_COLUMN,0);
	echo '["';
	foreach($result as $val){echo $val.'","';}
	echo '"]';
	}
?>