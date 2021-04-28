<?php
$ajax=1;
session_start();
include('../includes/admin/f.php');
/* print_r($_POST);
exit; */
// if(isset($_GET)){$_POST=$_GET;}
$bdd=connect();
if(isset($_SESSION['user_id']) AND !empty($_POST['perso_id']) AND isset($_POST['item_name'])  AND filter_var($_POST['perso_id'], FILTER_VALIDATE_INT))
	{
	if(get_droits_perso($_POST['perso_id'],$_SESSION['user_id'],'group'))
		{
		$req = $bdd->prepare('SELECT id FROM item WHERE etat!="delete" AND name = ? ORDER BY auteur_id LIMIT 1');
		$req->execute(array($_POST['item_name']));
		if($donnees = $req->fetch())
			{
			if(add_item($donnees['id'],$_POST['perso_id']))
				{
				$item= new item($donnees['id']);
				$array_type=array('comp'=>'Compétence','arme'=>'Arme','protec'=>'Protection','divers'=>'Divers');
				/* $table='<tr id="block_'.$item->id.'" class="info">
						<td><a target="_blank" data-toggle="modal" data-target="#modal_'.$item->id.'" href="/item/'.$item->id.'/'.to_url($item->name).'?popin=popin" rel="nofollow">
						'.secure::html($item->name,1).'</a></td>
						<td>
							<span id="block_'.$item->id.'_qte">1</span>
						</td><td><span class="btn-group">
								<a class="btn btn-mini" onclick="if(parseInt($(\'#block_'.$item->id.'_qte\').html())>1){qte_item('.$item->id.','.$_POST['perso_id'].',parseInt($(\'#block_'.$item->id.'_qte\').html())-1);$(\'#block_'.$item->id.'_qte\').html(parseInt($(\'#block_'.$item->id.'_qte\').html())-1);}"><i class="icon-minus"></i></a>
								<a class="btn btn-mini" onclick="qte_item('.$item->id.','.$_POST['perso_id'].',parseInt($(\'#block_'.$item->id.'_qte\').html())+1);$(\'#block_'.$item->id.'_qte\').html(parseInt($(\'#block_'.$item->id.'_qte\').html())+1);"><i class="icon-plus"></i></a>
								<a class="btn btn-mini btn-danger" data-dismiss="alert" onclick="delete_item('.$item->id.','.$_POST['perso_id'].');"><i class="icon-remove icon-white"></i></a>
							</span></td>	<td>'.$array_type[$item->type].'</td>
						<td>'.$item->prix.'</td>
						</tr>';
				$action='$("#table_item_main tbody").prepend("'.preg_replace('#\n|\t|\r#','',str_replace('"','\"',$table)).'");'; */
				echo ajax::send('Element ajouté au personnage selectionné !','success',false,'');
				}
			else{echo ajax::send('Erreur lors de la tentative d\'ajout : l\'objet demandé n\'existe pas !','error');}
			}
		else{echo ajax::send('Erreur lors de la tentative d\'ajout : l\'objet demandé n\'existe pas !','error');}
		}
	else{echo ajax::send('Erreur lors de la tentative d\'ajout : l\'objet demandé n\'existe pas !','error');}
	}
else{
	echo ajax::send('Erreur lors de la tentative d\'ajout : l\'objet demandé n\'existe pas !','error');
	}