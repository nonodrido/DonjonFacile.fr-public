<?php
$ajax=1;
session_start();
include('../includes/admin/f.php');
// print_r($_POST);
// exit;
$bdd=connect();
if(isset($_SESSION['user_id']) AND !empty($_POST['perso_id']) AND isset($_POST['item_id']) AND filter_var($_POST['item_id'], FILTER_VALIDATE_INT) AND filter_var($_POST['perso_id'], FILTER_VALIDATE_INT))
	{
	if(get_droits_perso($_POST['perso_id'],$_SESSION['user_id'],'group'))
		{
		$mode='qte';
		if(isset($_POST['mode']) AND in_array($_POST['mode'],array('qte','equip','waste'))){$mode=$_POST['mode'];}
		if(add_item($_POST['item_id'],$_POST['perso_id'],$mode)){echo ajax::send('Element ajouté au personnage selectionné !');}
		else{echo ajax::send('erreur critique du script : l\'objet ajouté n\'existe pas ou plus !','error');}
		}
	else{echo ajax::send('Vous n\'avez pas ou plus le droit de modifier ce personnage !','error');}
	}
else{
	echo ajax::send('Aucun personnage n\'est selectionné, veuillez selectionner un personnage avant de lui ajouter un élément !','error');
	}