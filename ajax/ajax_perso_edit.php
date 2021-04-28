<?php
$ajax=1;
session_start();
include('../includes/admin/f.php');
$bdd=connect();
/*
 *  original_html; the original text in the in-place editor container
 *  update_value; the new value of the text from the in-place editor
 *  element_id; the id attribute of the in-place editor  
 *  perso_id; id of the character
 */
if(isset($_SESSION['user_id']))
	{
	/* $req=$bdd->prepare('SELECT * FROM users WHERE id = :id AND etat != "delete"');
	$req->execute(array('id' => $_SESSION['user_id']));
	$user=$req->fetch();$req->closeCursor();
	if($user['type']=='ban')
		{
		$reponse = $bdd->prepare('SELECT * FROM ban WHERE user_id= ? AND etat != "old"');$reponse->execute(array($_SESSION['user_id']));$rep = $reponse->fetch();$reponse->closeCursor();
		logout();
		session_start();
		if($rep['time']!=0){$_SESSION['warning'].='vous avez été banni de ce site jusque au '.date('d/m/Y à h\hi',$rep['time']).' ('.$rep['motif'].')<br/>';}
		else{$_SESSION['err'].='vous avez été banni de ce site définitivement ! ('.$rep['motif'].')<br/>';}
		} */
	}
else{echo ACCES_REFUSE_INVITE;exit();}
$type='group';
if(in_array($_POST['element_id'],array('sexe','metier','origine','name','img','specialisation')))
			{
			$type='full';
			}
if(get_droits_perso($_POST['perso_id'],$_SESSION['user_id'],$type))
	{
	if(!empty($_POST))
		{
		if(in_array($_POST['element_id'],array('pnj','xp','COU','AD','INTL','FO','CHA','evmax','eamax','adv_opt','PO','PA','PC','LT','LB','descr','sexe','metier','origine','name','PRD','AT','PDest','img','specialisation')))
			{
			if($_POST['update_value']=='' AND $_POST['element_id']!='descr')
				{
				echo $_POST['original_html'].'<script class="update_perso_val" type="text/javascript">$.pnotify({type:"error",text:"Erreur : pas de données reçues !"});</script>';
				}
			else
				{
				if($_POST['element_id']=='img' AND !filter_var($_POST['update_value'], FILTER_VALIDATE_URL))
					{
					echo $_POST['original_html'].'<script class="update_perso_val" type="text/javascript">$.pnotify({type:"error",text:"Erreur : la valeur attendue est une url !"});</script>';
					exit();
					}
				elseif(in_array($_POST['element_id'],array('xp','evmax','eamax','PO','PA','PC','LT','LB','PDest','AT','PRD')))
					{
					if(!empty($_POST['update_value']))
							{
							if(!filter_var($_POST['update_value'], FILTER_VALIDATE_INT) OR $_POST['update_value']<0)
								{
								echo $_POST['original_html'].'<script class="update_perso_val" type="text/javascript">$.pnotify({type:"error",text:"Erreur : la valeur attendue est un nombre positif !"});</script>';
								exit();
								}	
							}
					}
				elseif(in_array($_POST['element_id'],array('COU','AD','INTL','FO','CHA')))
					{
					if(!empty($_POST['update_value']))
							{
							if(!filter_var($_POST['update_value'], FILTER_VALIDATE_INT) OR $_POST['update_value']<0 OR $_POST['update_value']>1000)
								{
								echo $_POST['original_html'].'<script class="update_perso_val" type="text/javascript">$.pnotify({type:"error",text:"Erreur : la valeur attendue est un nombre entre 0 et 1000 !"});</script>';
								exit();
								}	
							}
					}
				elseif($_POST['element_id']=='pnj')
					{
					if(!empty($_POST['update_value']))
							{
							if($_POST['update_value']!=1 AND $_POST['update_value']!=0)
								{
								echo $_POST['original_html'].'<script class="update_perso_val" type="text/javascript">$.pnotify({type:"error",text:"Erreur : la valeur attendue est un booléen"});</script>';
								exit();
								}	
							}
					}
				elseif($_POST['element_id']=='name' AND is_numeric($_POST['update_value']))
					{
					echo $_POST['original_html'].'<script class="update_perso_val" type="text/javascript">$.pnotify({type:"error",text:"Erreur : le nom du personnage doit contenir au moins une lettre !"});</script>';
					exit();
					}
				$rep = $bdd->prepare('SELECT perso.*, users.pseudo as user_name
									  FROM perso 
									  INNER JOIN users 
									  ON perso.user_id = users.ID 
									  WHERE perso.id= ?');
				$rep->execute(array($_POST['perso_id']));
				$donnees = $rep->fetch();$rep->closeCursor();
				if($_POST['element_id']=='descr'){$val=secure::html($_POST['update_value']);}else{$val=$_POST['update_value'];}
				try
					{
					$req=$bdd->prepare('UPDATE perso SET '.$_POST['element_id'].'=:val WHERE id=:id');
					$req->execute(array(
					'val' => $_POST['update_value'],
					'id' => $_POST['perso_id']
					));
					echo '<script class="update_perso_val" type="text/javascript">var $time='.time().';</script>'.$val;update_perso($_POST['perso_id']);$_SESSION['cache_time']=time();$_SESSION['update_perso_timer']=time()+100000*60;
					}
				catch(Exception $e){echo $_POST['original_html'].'<script class="update_perso_val" type="text/javascript">$.pnotify({type:"error",text:"Erreur critique mysql"});</script>';}
				}
			}
		else{echo $_POST['original_html'].'<script class="update_perso_val" type="text/javascript">$.pnotify({type:"error",text:"Erreur critique, le champ renseigné n\'est pas prévu !"});</script>';}
		}
	}
	else{
		echo $_POST['original_html'].'<script class="update_perso_val" type="text/javascript">$.pnotify({type:"error",text:"Vous n\'avez pas le droit de modifier cette valeur !"});</script>';
		}