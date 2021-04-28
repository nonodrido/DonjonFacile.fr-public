<?php
$ajax=1;
@session_start();
@include('../includes/admin/f.php');
$bdd=connect();
if(isset($_SESSION['user_id']) AND isset($_POST['item_id']) AND filter_var($_POST['item_id'], FILTER_VALIDATE_INT)AND isset($_POST['mode']) 
AND in_array($_POST['mode'],array('equip','desequip','waste','qte')) AND isset($_POST['perso_id']) 
AND filter_var($_POST['perso_id'], FILTER_VALIDATE_INT))
	{
	if(get_droits_perso($_POST['perso_id'],$_SESSION['user_id'],'group'))
		{
		if($_POST['mode']=='equip')
			{
			$bdd->exec('UPDATE perso_items SET equip=equip+1, qte=qte-1 WHERE perso_id='.$_POST['perso_id'].' AND item_id='.$_POST['item_id']);
			echo ajax::send('Équipement équipé avec succès !');
			update_perso($_POST['perso_id']);
			}
		elseif($_POST['mode']=='desequip')
			{
			$bdd->exec('UPDATE perso_items SET equip=equip-1, qte=qte+1 WHERE perso_id='.$_POST['perso_id'].' AND item_id='.$_POST['item_id']);
			echo ajax::send('Équipement déséquipé avec succès !');
			update_perso($_POST['perso_id']);
			}
		elseif($_POST['mode']=='waste')
			{
			$bdd->exec('UPDATE perso_items SET waste=qte+waste, qte=0 WHERE perso_id='.$_POST['perso_id'].' AND item_id='.$_POST['item_id']);
			echo ajax::send('Élément mis au débarras avec succès !');
			update_perso($_POST['perso_id']);
			}
		elseif($_POST['mode']=='qte')
			{
			$bdd->exec('UPDATE perso_items SET qte=qte+waste, waste=0 WHERE perso_id='.$_POST['perso_id'].' AND item_id='.$_POST['item_id']);
			echo ajax::send('Élément sauvé du débarras avec succès !');
			update_perso($_POST['perso_id']);
			}
		
		}
	else{echo ajax::send('Vous n\'avez pas ou plus le droit de modifier ce personnage !','error');}
	}
else{
	echo ajax::send('Erreur critique, peut-être que ce personnage n\'existe pas !','error');
	}