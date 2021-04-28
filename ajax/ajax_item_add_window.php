<?php
$ajax=1;
session_start();
include('../includes/admin/f.php');
if(isuser() AND !empty($_GET['item_id']))
	{
	$bdd=connect();
	$id=(int) $_GET['item_id'];
	$item=$bdd->query('SELECT * FROM item WHERE etat!="delete" AND id='.$id)->fetch();
	if(isset($item['type']))
		{
		/* $list_perso=$bdd->query('
				(SELECT p.* ,pi.qte
				FROM perso p 
				INNER JOIN perso_items pi
				ON p.id = pi.perso_id
				WHERE p.user_id = '.$_SESSION['user_id'].' AND p.etat != "delete" AND pi.item_id='.$id.')
					UNION
				(SELECT p.*,pi.qte
				FROM users_persos up
				INNER JOIN perso p
				ON up.perso_id = p.id
				INNER JOIN perso_items pi
				ON up.perso_id = pi.perso_id
				WHERE up.user_id='.$_SESSION['user_id'].' AND p.user_id!='.$_SESSION['user_id'].' AND pi.item_id='.$id.' AND p.etat != "delete" AND up.etat!="delete")
				ORDER BY name,qte'); */
		$list_perso=$bdd->query('(SELECT * FROM perso WHERE user_id = '.$_SESSION['user_id'].' AND etat != "delete")
					UNION
				(SELECT p.*
				FROM users_persos as up
				INNER JOIN perso as p
				ON up.perso_id = p.id
				WHERE up.user_id='.$_SESSION['user_id'].' AND p.etat != "delete" AND up.etat != "delete")
				ORDER BY name')->fetchAll();
		foreach($list_perso as $cle=>$val)
			{
			$test=$bdd->query('SELECT * FROM perso_items WHERE perso_id='.$val['id'].' AND item_id='.$id.' AND etat!="delete"')->fetch();
			if(isset($test['qte']) AND $test['qte']>0){$list_perso[$cle]['qte']=$test['qte'];}
			else{$list_perso[$cle]['qte']=false;}
			if(isset($test['equip']) AND $test['equip']>0){/* $list_perso[$cle]['qte']=false; */$list_perso[$cle]['equip']=true;}
			else{$list_perso[$cle]['equip']=false;}
			}
		// var_dump($list_perso);exit;
		echo'<fieldset><legend>Ajout de '.secure::html($item['name'],1).'</legend>';
		$a='';$b='';
		foreach($list_perso as $val)
			{
			if($val['qte']===false)
				{
				$a.= '<tr id="perso_'.$val['id'].'_item_add">
						<td><a target="_blank" href="/perso/'.$val['id'].'/'.to_url($val['name']).'">'.secure::html($val['name'],1).'</a></td>
						<td><span class="btn-group">
							<a class="btn" onclick="add_item('.$id.','.$val['id'].');$(this).parent().remove();"><i class="icon-plus"></i> Ajouter</a>';
				/* if(($item['type']=='arme' OR $item['type']=='protec') AND $val['equip']==false)
					{
					$a.= '<a class="btn" onclick="add_item('.$id.','.$val['id'].',\'equip\');$(this).parent().remove();"><i class="icon-briefcase"></i> Équiper</a>';
					} */
				$a.='	</span></td>
					  </tr>';
				}
			else{
				$b.= '<tr>
						<td><a target="_blank" href="/perso/'.$val['id'].'/'.to_url($val['name']).'">'.secure::html($val['name'],1).' 
						(<span id="qte_'.$_GET['item_id'].'">'.$val['qte'].'</span>)</a></td>
						<!--<td>
							<div class="btn-group">
								<a class="btn btn-mini" onclick="if(parseInt($(\'#qte_'.$_GET['item_id'].'\').html())>1){qte_item('.$_GET['item_id'].','.$val['id'].',parseInt($(\'#qte_'.$_GET['item_id'].'\').html())-1);$(\'#qte_'.$_GET['item_id'].'\').html(parseInt($(\'#qte_'.$_GET['item_id'].'\').html())-1);}"><i class="icon-minus"></i></a>
								<a class="btn btn-mini" onclick="qte_item('.$_GET['item_id'].','.$val['id'].',parseInt($(\'#qte_'.$_GET['item_id'].'\').html())+1);$(\'#qte_'.$_GET['item_id'].'\').html(parseInt($(\'#te_'.$_GET['item_id'].'\').html())+1);"><i class="icon-plus"></i></a>
							</div>
						</td>-->
					  </tr>';
				}
			}
		if(!empty($a))
			{
			echo '	<table class="table table-condensed table-bordered table-striped">'.$a.'</table>';
			}
		else{echo 'Aucun personnage ne peut accepter cet ajout.';}
		if(!empty($b))
			{
			echo' <fieldset><legend>Personnages possédant déjà cet élément</legend>
					<table class="table-condensed table table-bordered table-striped">'.$b.'</table>
				  </fieldset>';
			
			}
		echo '</fieldset>';
		$item=new item($id);
		echo '<fieldset><legend>Détails du contenu :</legend>';
		$item->tableau();
		echo '</fieldset>';
		}
	}
/* 		echo '	<div class="btn-group dropup" style="display:inline-block;">
			  <a class="btn dropdown-toggle" data-toggle="dropdown"  style="color:black;">
				<i class="icon-plus-sign"></i> Ajouter à
				<span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu">';
		foreach($list_perso as $p)
			{
			echo '<li><a style="color:black;"  onclick="add_item('.$id.','.$p['id'].');">'.secure::html($p['name'],1).'</a></li>';
			}
		echo	 '</ul>';
		echo '	</div>';
		<div class="button-group">
				<button class="rerun button">Personnage actuel</button>
				<button class="button"></button>
			</div>
			<ul style="position:absolute;">';
			foreach($_SESSION['list_perso'] as $key=>$val)
				{
				echo '<li><a onclick="add_item('.$id.''.$val['id'].');return false;">'.secure::html($val['name'],1).'</a></li>';
				}
				echo'<!--<li class="dropdown-submenu">
						<a tabindex="-1" >More options</a>
						<ul class="dropdown-menu">
							<li></li>
						</ul>-->
					</li>
			</ul> */