<?php
$ajax=1;
@session_start();
@include('../includes/admin/f.php');
$bdd=connect();
if(isuser() AND isset($_POST['item_id']) AND filter_var($_POST['item_id'], FILTER_VALIDATE_INT) AND isset($_POST['qte']) 
AND filter_var($_POST['qte'], FILTER_VALIDATE_INT) AND $_POST['qte']>0 AND isset($_POST['perso_id']) 
AND filter_var($_POST['perso_id'], FILTER_VALIDATE_INT))
	{
	if(get_droits_perso($_POST['perso_id'],$_SESSION['user_id'],'group'))
		{
		$mode='qte';
		if(isset($_POST['mode']) AND in_array($_POST['mode'],array('qte','equip','waste'))){$mode=$_POST['mode'];}
		$bdd->exec('UPDATE perso_items SET `'.$mode.'`='.$_POST['qte'].' WHERE perso_id='.$_POST['perso_id'].' AND item_id='.$_POST['item_id']);
		echo ajax::send('Quantité modifiée avec succès ('.$_POST['qte'].') !');
		update_perso($_POST['perso_id']);
		}
	else{echo ajax::send('Vous n\'avez pas ou plus le droit de modifier ce personnage !','error');}
	}
else{
	echo ajax::send('Erreur critique, peut-être que ce personnage n\'existe pas !','error');
	}