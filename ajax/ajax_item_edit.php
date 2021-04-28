<?php
$ajax=1;
session_start();
include('../includes/admin/f.php');
$bdd=connect();
/*
 *  original_html; the original text in the in-place editor container
 *  update_value; the new value of the text from the in-place editor
 *  element_id; the id attribute of the in-place editor  
 *  item_id; id of the item
 */
 // echo $_POST['update_value'];exit;
if(isset($_SESSION['user_id']))
	{
	$req=$bdd->prepare('SELECT * FROM users WHERE id = :id AND etat != "delete"');
	$req->execute(array('id' => $_SESSION['user_id']));
	$user=$req->fetch();$req->closeCursor();
	if($user['type']=='ban')
		{
		$reponse = $bdd->prepare('SELECT * FROM ban WHERE user_id= ? AND etat != "old"');$reponse->execute(array($_SESSION['user_id']));$rep = $reponse->fetch();$reponse->closeCursor();
		logout();
		session_start();
		if($rep['time']!=0){$_SESSION['warning'].='vous avez été banni de ce site jusque au '.date('d/m/Y à h\hi',$rep['time']).' ('.$rep['motif'].')<br/>';}
		else{$_SESSION['err'].='vous avez été banni de ce site définitivement ! ('.$rep['motif'].')<br/>';}
		exit;
		}
	}
else{echo ACCES_REFUSE_INVITE;exit;}

// id,create_date,date,auteur_id,etat,type,subtype,name,carac,prix,descr,effets,rupture

if(!empty($_POST) AND isset($_POST['item_id']))
	{
	$item_id=(int) $_POST['item_id'];
	$item=new item($item_id);
	if($item->droits())
		{
		
		if(in_array($_POST['element_id'],array('subtype','name','carac','prix','descr','effets','rupture','emplacement')))
			{
			if(in_array($_POST['element_id'],array('subtype','name','carac','emplacement')) AND empty($_POST['update_value']))
				{
				echo $_POST['original_html'].'<script type="text/javascript">alert("Erreur : pas de données reçues !");</script>';
				}
			else
				{
				if($_POST['element_id']=='prix')
					{
					if(!empty($_POST['update_value']))
							{
							if(!filter_var($_POST['update_value'], FILTER_VALIDATE_FLOAT) OR $_POST['update_value']<0)
								{
								echo $_POST['original_html'].'<script class="update_item_val" type="text/javascript">alert("Erreur : la valeur attendue est un nombre !");</script>';
								exit();
								}	
							}
					}
				elseif($_POST['element_id']=='rupture')
					{
					if(in_array($_POST['update_value'],array('jamais','1','1 à 2','1 à 3','1 à 4','1 à 5')))
						{
						$rupture=array('jamais'=>0,'1'=>1,'1 à 2'=>2,'1 à 3'=>3,'1 à 4'=>4,'1 à 5'=>5);
						$rval=$_POST['update_value'];
						$_POST['update_value']=$rupture[$_POST['update_value']];
						}
					else{
						echo $_POST['original_html'].'<script class="update_item_val" type="text/javascript">alert("Erreur critique: valeure de rupture non reconnue");</script>';
						exit();
						}
					}
				elseif($_POST['element_id']=='name')
					{
					$item=new item('new');
					if(!$item->check_name($_POST['update_value']))
						{
						echo $_POST['original_html'].'<script class="update_item_val" type="text/javascript">alert("Erreur : le nom de l\'objet est verrouillé !");</script>';
						exit();
						}
					elseif(is_numeric($_POST['update_value']))
						{
						echo $_POST['original_html'].'<script class="update_item_val" type="text/javascript">alert("Erreur : le nom de l\'objet doit contenir au moins une lettre !");</script>';
						exit();
						}
					}
				$item->$_POST['element_id']=$_POST['update_value'];
				if($_POST['element_id']!='rupture'){echo $item->$_POST['element_id'];}else{echo $rval;}
				$item->update();
				$_SESSION['cache_time']=time();
				}
			}
		else{echo $_POST['original_html'].'<script class="update_item_val" type="text/javascript">alert("Erreur critique, le champ renseigné n\'est pas prévu !");</script>';}
		}
	}