<?php
$ajax=1;
session_start();
include('../includes/admin/f.php');
if(isset($_SESSION['user_id']) AND isset($_POST['item_id']) AND filter_var($_POST['item_id'], FILTER_VALIDATE_INT) AND isset($_POST['perso_id']) AND filter_var($_POST['perso_id'], FILTER_VALIDATE_INT))
	{
	if(get_droits_perso($_POST['perso_id'],$_SESSION['user_id'],'group'))
		{
		$mode='qte';
		if(isset($_POST['mode']) AND in_array($_POST['mode'],array('qte','equip','waste'))){$mode=$_POST['mode'];}
		delete_item($_POST['item_id'],$_POST['perso_id'],$mode);
		echo ajax::send('Element supprimé au personnage');
		}
	else{echo ajax::send('Vous n\'avez pas ou plus le droit de modifier ce personnage !','error');}
	}
else{
	echo ajax::send('Ce personnage n\'existe pas !','error');
	}