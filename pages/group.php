<?php
$titre='module de gestions des compagnies';
if(empty($mode))
	{
	
	if(!isset($_GET['id']))
		{
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////        PAGE GENERALE GROUPES      //////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
		verif_uri('/group');
		if(isset($_SESSION['user_id']))
		{
		$titre='Gestion des compagnies';
		?>
		<div class="row-fluid">
			<div class="span3">
				<fieldset><legend>Informations</legend>
					Une compagnie permet de regrouper des personnages de manière simple pour y accéder plus rapidement. 
					Cela permet aussi de faire des actions groupées sur plusieurs personnages comme la gestion des droits ou encore 
					l'ajout d'or ou d'xp groupé simplifié.<br/><br/>
					<div class="center"><a class="btn" href="/group/create">Créer une compagie</a></div>
				</fieldset>
			</div>
		<div class="span9">
		<?php
		$req=$bdd->query('SELECT * FROM `group` WHERE etat!="delete" AND user_id='.$_SESSION['user_id'].' ORDER BY name');
		echo '<fieldset><legend>Mes compagnies</legend>';
		if($req->rowcount() != 0)
			{
			echo '<table class="table table-striped"><thead><tr><th>Nom</th><th>Description</th><th>Action</th></tr></thead><tbody>';
			$i=0;
			while($val=$req->fetch())
				{
				echo '	<tr>
							<td>'.secure::html($val['name'],1).'</td>
							<td>'.secure::html($val['descr']).'</td>
							<td><div class="btn-group">
								<a class="btn" href="/group/'.$val['id'].'/'.to_url($val['name']).'"><i class="icon-search"></i> Voir</a>
								<!--<a class="btn" href="/group/edit/'.$val['id'].'/'.to_url($val['name']).'"><i class="icon-edit"></i> Modifier</a>-->
								<a class="btn btn-danger" href="/group/delete/'.$val['id'].'/'.to_url($val['name']).'"><i class="icon-remove icon-white"></i> Supprimer</a>
							</div></td>
						</tr>';
				}
			echo '</tbody></table>';
			}
		else{echo 'Vous n\'êtes le leader d\'aucune compagnie !';}
		echo '</fieldset></div></div>';
		?>
		<div class="row-fluid"><div class="span6">
		<fieldset><legend>Compagnies de vos personnages</legend>
			<?php
			$req=$bdd->query('	SELECT g.name group_name, g.id group_id, p.name perso_name, p.id perso_id 
								FROM `group` g 
								INNER JOIN group_perso gp
								ON g.id = gp.group_id
								INNER JOIN perso p
								ON p.id = gp.perso_id
								WHERE p.etat!="delete" AND g.etat!="delete" AND p.user_id='.$_SESSION['user_id'].' 
								ORDER BY p.name');
			if($req->rowcount() != 0)
				{
				echo '<table class="table table-striped"><thead><tr><th>Personnage</th><th>Compagnie</th></tr></thead><tbody>';
				$i=0;
				while($val=$req->fetch())
					{
					echo '	<tr>
								<td><a href="/perso/'.$val['perso_id'].'/'.to_url($val['perso_name']).'">'.secure::html($val['perso_name'],1).'</a></td>
								<td><a href="/group/'.$val['group_id'].'/'.to_url($val['group_name']).'">'.secure::html($val['group_name'],1).'</a></td>
							</tr>';
					}
				echo '</tbody></table>';
				}
			else{echo 'Aucun de vos personnages n\'a d\'amis !';}
			?>
		</fieldset></div>
		<div class="span6"><fieldset><legend>Les compagnies dont vous êtes membre</legend>
			<?php
			$req=$bdd->query('	SELECT g.name group_name, g.id group_id, c.id creat_id, c.pseudo creat_name
								FROM `group` g 
								INNER JOIN group_user gu
								ON g.id = gu.group_id
								INNER JOIN users u
								ON u.id = gu.user_id
								INNER JOIN users c
								ON c.id = g.user_id
								WHERE u.etat!="delete" AND g.etat!="delete" AND c.etat!="delete" AND gu.etat!="delete" 
								AND gu.user_id='.$_SESSION['user_id'].' AND g.user_id!='.$_SESSION['user_id'].' 
								ORDER BY g.name');
			if($req->rowcount() != 0)
				{
				echo '<table class="table table-striped"><thead><tr><th>Nom</th><th>Créateur</th></tr></thead><tbody>';
				$i=0;
				while($val=$req->fetch())
					{
					echo '	<tr>
								<td><a href="/group/'.$val['group_id'].'/'.to_url($val['group_name']).'">'.secure::html($val['group_name'],1).'</a></td>
								<td><a href="/membre/'.$val['creat_id'].'/'.to_url($val['creat_name']).'">'.secure::html($val['creat_name'],1).'</a></td>
							</tr>';
					}
				echo '</tbody></table>';
				}
			else{echo 'Ancune compagnie ne veut de vous pour l\'instant.';}
			?>
		</fieldset></div>
		</div>
		<?php
		}
		//liste des compagnies du site
		$req=$bdd->query('SELECT g.*,u.pseudo FROM `group` g INNER JOIN users u ON g.user_id = u.id WHERE g.etat!="delete" AND u.etat!="delete" ORDER BY g.name');
		echo '	<hr><fieldset><legend>Liste des compagnies</legend>
				<table class="jtable table table-bordered table-striped table-hover">
					<thead><tr><th>Nom</th><th>Créateur</th><th>Description</th></tr></thead>';
		while($val=$req->fetch())
			{
			echo '<tr>
					<td><a href="/group/'.$val['id'].'/'.to_url($val['name']).'">'.secure::html($val['name'],1).'</a></td>
					<td><a href="/membre/'.$val['user_id'].'/'.to_url($val['pseudo']).'">'.secure::html($val['pseudo'],1).'</a></td>
					<td>'.secure::html($val['descr']).'</td>
				  </tr>';
			}
		echo '</table></fieldset>';
		}
	else{
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////           PAGE D'UN GROUPE        //////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
		$req=$bdd->query('	SELECT g.* ,u.pseudo user_pseudo
							FROM `group` g
							INNER JOIN users u
							ON u.id = g.user_id
							WHERE g.etat!="delete" AND g.id='.$_GET['id']);
		if($req->rowcount() != 0)
			{
			$val=$req->fetch();
			$user_id=$val['user_id'];
			verif_uri('/group/'.$val['id'].'/'.to_url($val['name']));
			if(!empty($_POST['perso_delete_id']) AND filter_var($_POST['perso_delete_id'], FILTER_VALIDATE_INT) AND isset($_SESSION['user']) AND ($_SESSION['user']=='admin' OR $_SESSION['user_id']==$val['user_id']))
				{ //suppression d'un membre de la compagnie
				$bdd->exec('DELETE FROM group_perso WHERE perso_id='.$_POST['perso_delete_id'].' AND group_id='.$_GET['id']);
				$_SESSION['success'].='Ce personnage a quitté cette compagnie !';
				}
			if(isset($_GET['inplace_value']) OR isset($_POST['inplace_value'])){
				echo '<div class="alert alert-error">
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				  <strong>Erreur!</strong> Le site a détecté une tentative de modification de votre compagnie mais ne peut pas traiter la demande !<br>
				  Pour modifier une information, il faut saisir la valeur souhaitée puis cliquer <u>hors du cadre de modification</u>.<br>
				  Appuyer sur la touche entrée ne permet pas de valider mais se contente de recharger la page.
				</div>';
				}
			$titre='Page de la compagnie : "'.$val['name'].'"';
			echo '	<fieldset><legend>Informations sur la compagnie : "'.secure::html($val['name'],1).'"</legend>
						<ul class="unstyled">
							<li>Nom : <b id="name">'.secure::html($val['name'],1).'</b></li>
							<li>Description : <br><span id="descr">'.secure::html($val['descr']).'</span></li>
							<li>Récits de la compagnie : <br><span id="recit">'.secure::html($val['recit']).'</span></li>
							<li>Créateur : <a href="/membre/'.$val['user_id'].'/'.to_url($val['user_pseudo']).'">'.secure::html($val['user_pseudo']).'</a></li>
							<li>'.fav_button($_GET['id'],'group').'</li>';
			if(isset($_SESSION['user']) AND ($_SESSION['user']=='admin' OR $_SESSION['user_id']==$val['user_id']))
				{
				$droits=true;
				/////////// ACTIONS DE GROUPE //////////
				if(!empty($_POST['exp']) AND filter_var($_POST['exp'], FILTER_VALIDATE_INT))
					{
					$req=$bdd->query('SELECT * FROM group_perso WHERE group_id='.$_GET['id']);
					while($val=$req->fetch())
						{
						if(get_droits_perso($val['perso_id'],$_SESSION['user_id'],'group'))
							{
							$val2=$bdd->query('SELECT * FROM perso WHERE id='.$val['perso_id'])->fetch();
							if($val2['xp']+$_POST['exp']>=0){$result=$val2['xp']+$_POST['exp'];}else{$result=0;}
							$bdd->exec('UPDATE perso SET  xp='.$result.' WHERE id='.$val['perso_id']);
							}
						}
					$_SESSION['success'].='Expérience ajoutée/enlevée avec succès.';
					}
				elseif(!empty($_POST['money']) AND filter_var($_POST['money'], FILTER_VALIDATE_INT))
					{
					$req=$bdd->query('SELECT * FROM group_perso WHERE group_id='.$_GET['id']);
					while($val=$req->fetch())
						{
						if(get_droits_perso($val['perso_id'],$_SESSION['user_id'],'group'))
							{
							$val2=$bdd->query('SELECT * FROM perso WHERE id='.$val['perso_id'])->fetch();
							if($val2['PO']+$_POST['money']>=0){$result=$val2['PO']+$_POST['money'];}else{$result=0;}
							$bdd->exec('UPDATE perso SET  PO='.$result.' WHERE id='.$val['perso_id']);
							}
						}
					$_SESSION['success'].='PO ajoutées/enlevées avec succès.';
					}
				elseif(!empty($_POST['divers']) AND !empty($_POST['type_divers']) AND filter_var($_POST['divers'], FILTER_VALIDATE_INT) AND in_array($_POST['type_divers'],array("PO","PA","PC","LT","LB","xp","COU","INTL","FO","CHA","AD","PDest")))// type_divers VARCHAR / divers INT
					{
					$req=$bdd->query('SELECT * FROM group_perso WHERE group_id='.$_GET['id']);
					while($val=$req->fetch())
						{
						if(get_droits_perso($val['perso_id'],$_SESSION['user_id'],'group'))
							{
							$val2=$bdd->query('SELECT * FROM perso WHERE id='.$val['perso_id'])->fetch();
							if($val2[$_POST['type_divers']]+$_POST['divers']>=0){$result=$val2[$_POST['type_divers']]+$_POST['divers'];}else{$result=0;}
							// if(in_array($_POST['type_divers'],array("COU","INTL","FO","CHA","AD")) AND $result>12){$result=12;}
							$bdd->exec('UPDATE perso SET  '.$_POST['type_divers'].'='.$result.' WHERE id='.$val['perso_id']);
							}
						}
					$_SESSION['success'].='Elements ajoutés/enlevés avec succès.';
					}
				elseif(!empty($_POST['delete_user_id']) AND filter_var($_POST['delete_user_id'], FILTER_VALIDATE_INT))
					{
					$bdd->exec('UPDATE group_user SET  etat="delete" WHERE group_id='.$_GET['id'].' AND user_id='.$_POST['delete_user_id']);
					$_SESSION['success'].='Utilisateur supprimé avec succès.';
					}
				elseif(isset($_POST['add_user_pseudo']))
					{
					$rep=$bdd->prepare('SELECT * FROM users WHERE pseudo=:pseudo AND etat!="delete"');
					$rep->execute(array('pseudo'=>$_POST['add_user_pseudo']));
					if($rep->rowCount() == 0){$_SESSION['err'].='Utilisateur inconnu !';}
					else{
						$result=$rep->fetch();
						$bdd->exec('INSERT INTO group_user SET group_id='.$_GET['id'].', user_id='.$result['id'].' 
						ON DUPLICATE KEY UPDATE etat="default"');
						$_SESSION['success'].='Utilisateur ajouté avec succès.';
						}
					}
				}
			echo '		</ul>
					</fieldset>';
			echo '	<div class="row-fluid"><div class="span8">
					<fieldset><legend>Compagnons</legend>';
			$req=$bdd->query('	SELECT u.pseudo user_pseudo,u.id user_id,p.name perso_name, p.id perso_id, p.*
								FROM `group_perso` gp
								INNER JOIN perso p
								ON p.id = gp.perso_id
								INNER JOIN users u
								ON u.id = p.user_id
								WHERE gp.group_id='.$_GET['id']);
			if($req->rowcount() != 0)
				{
				echo '<table class="table table-striped"><thead><tr><th>Personnage</th><th>Créateur</th><th>Informations</th></thead><tbody>';
				echo '</tr>';
				while($data=$req->fetch())
					{
					if(isuser() AND $data['user_id']==$_SESSION['user_id']){$chat=1;}
					echo '	<tr>
								<td><a href="/perso/'.$data['perso_id'].'/'.to_url($data['perso_name']).'">'.secure::html($data['perso_name'],1).'</a></td>
								<td><a href="/membre/'.$data['user_id'].'/'.to_url($data['user_pseudo']).'">'.secure::html($data['user_pseudo'],1).'</a></td>
								<td class="row-fluid">
									<div class="span12">
										<table class="table">
											<thead><tr><th>COU</th><th>INT</th><th>CHA</th><th>AD</th><th>FO</th></tr></thead>
											<tbody><tr><td>'.$data['COU'].'</td><td>'.$data['INTL'].'</td><td>'.$data['CHA'].'</td><td>'.$data['AD'].'</td><td>'.$data['FO'].'</td></tr></tbody>
										</table>
										<div class="row-fluid">
											<div class="span4">
												'.$data['origine'].' ('.$data['PDest'].' Pt(s) de destin)<br/>
												Niveau <b>'.get_niv($data['xp']).'</b> ('.$data['xp'].' xp) 
											</div>
											<div class="btn-group span4" style="text-align:center;">
												<a class="btn" href="/perso/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-folder-open"></i> Voir</a>
												<a class="btn" href="/perso/fiche/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-file"></i> Fiche</a>';
								if(isset($_SESSION['user']) AND ($_SESSION['user']=='admin' OR $_SESSION['user_id']==$val['user_id']))
									{
									echo '	<a class="btn btn-danger" onclick="$(\'#perso_leave_'.$data['perso_id'].'\').submit();"><i class="icon-remove icon-white"></i> S\'en séparer</a>';
									}
								echo '		</div>
											<div class="span4 pull-right" style="text-align:right;">'.$data['metier'].'<br/>
												'.get_money(array('PO'=>$data['PO'],'PA'=>$data['PA'],'PC'=>$data['PC'],'LT'=>$data['LT'], 'LB'=>$data['LB']))
												.' PO 
											</div>
										</div>
									</div>';
								if(isset($_SESSION['user']) AND ($_SESSION['user']=='admin' OR $_SESSION['user_id']==$val['user_id']))
									{
									echo '	<form style="display:inline;" id="perso_leave_'.$data['perso_id'].'" method="post" action="'.$_SERVER['REQUEST_URI'].'" >
												<input type="hidden"  value="'.$data['perso_id'].'" name="perso_delete_id" />
											</form>';
									}
							echo '</td>
							</tr>';
					}
				echo'</tbody></table>';
				}
			else{echo 'Aucun personnage ne fait partie de cette compagnie pour l\'instant !';}
			echo '	</fieldset></div>
					<div class="span4">
					<fieldset><legend>Utilisateurs</legend>';
				$req=$bdd->query('	SELECT g.name group_name, g.id group_id, u.id user_id, u.pseudo user_name
								FROM `group` g 
								INNER JOIN group_user gu
								ON g.id = gu.group_id
								INNER JOIN users u
								ON u.id = gu.user_id
								WHERE u.etat!="delete" AND g.etat!="delete" AND gu.etat!="delete" 
								AND g.id='.$_GET['id'].' AND gu.user_id!=g.user_id
								ORDER BY g.name');
					echo '<table class="table table-striped table-condensed"><thead><tr><th>Utilisateur</th><th>Action</th></thead><tbody>';
					echo '</tr><tr><td colspan="2">
										<div class="center">
										<form method="post" style="margin-bottom:0;">Ajouter : 
										<div class="input-append">
										<input name="add_user_pseudo" class="ajax-typeahead-pseudo" placeholder="Pseudo du membre" type="text" autocomplete="off" />
										<input class="btn btn-primary" type="submit" value="Ajouter"/>
										</div></form>
										</div>
									</td></tr>';
					while($data=$req->fetch())
						{
						if(isuser() AND $data['user_id']==$_SESSION['user_id']){$chat=1;}
						echo '<tr>
								<td><a href="/membre/'.$data['user_id'].'/'.to_url($data['user_name']).'">'.secure::html($data['user_name'],1).'</a></td>
								<td>
									<form id="form_delete_user_'.$data['user_id'].'" class="hidden" method="post">
										<input type="hidden" name="delete_user_id" value="'.$data['user_id'].'"/>
									</form>
									<div class="btn-group">
									<a class="btn" href="/messagerie?username='.secure::html($data['user_name'],1).'"><i class="icon-envelope"></i> message</a>
									';
						if(isset($droits) AND $droits){echo'<a class="btn btn-danger" onclick="$(\'#form_delete_user_'.$data['user_id'].'\').submit();">Bannir</a>';}
						echo '		</div>
								</td>
							  </tr>';
						}
					echo'</tbody></table>
							<p>
							Ces utilisateurs ont accès au chat de la compagnie sans qu\'un personnage leur appartenant n\'en fasse partie.
							</p>';
			echo '	</fieldset>
					</div>
					</div>
					<div class="warning-box">
						Vous devez avoir les droits sur un personnage pour pouvoir l\'ajouter à votre compagnie !
					</div>';
			if(isadmin() OR (isuser() AND $_SESSION['user_id']==$user_id))
				{
				echo '<a class="btn btn-danger" href="/group/delete/'.$val['id'].'/'.to_url($val['name']).'"><i class="icon-remove icon-white"></i> Supprimer la compagnie</a>';
				?>
				
				<fieldset><legend>Actions de groupe</legend>
					<div class="row-fluid">
						<div class="span4">
							<br/>
							<form class="form-inline" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
								<label class="control-label" for="exp">Expérience à ajouter/retirer : 
								  <div class="input-append input-prepend">
								    <span class="add-on">xp</span>
									<input class="input-small center" type="number" name="exp" id="exp" value="0">
									<button type="submit" class="btn">Ajouter</button>
								  </div>
								</label>
							</form>
						</div>
						<div class="span4">
							<br/>
							<form class="form-inline" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
								<label class="control-label" for="money">Argent à ajouter/retirer : 
								  <div class="input-append input-prepend">
								    <span class="add-on">PO</span>
									<input class="input-small center" type="number" name="money" id="money" value="0">
									<button type="submit" class="btn">Ajouter</button>
								  </div>
								</label>
							</form>
						</div>
						<div class="span4">
							<br/>
							<form class="form-inline" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>"> 
								<label class="control-label">Modifications diverses : 
								  <div class="input-append">
									<input class="input-small center" type="number" name="divers" id="divers" value="0">
									<select name="type_divers" class="input-small">
									  <option value="PO" selected>PO</option>
									  <option value="PA">PA</option>
									  <option value="PC">PC</option>
									  <option value="LT">L. de Thritil</option>
									  <option value="LB">L. de Berylium</option>
									  <option value="xp">exp</option>
									  <option value="COU">COU</option>
									  <option value="INTL">INT</option>
									  <option value="FO">FO</option>
									  <option value="CHA">CHA</option>
									  <option value="AD">AD</option>
									  <option value="PDest">Pt de Destin</option>
									</select>
									<button type="submit" class="btn">Ajouter</button>
								  </div>
								</label>
							</form>
						</div>
					</div>
				</fieldset>
				<div class="info-box">Vous pouvez modifier le nom et la description de la compagnie en cliquant dessus !</div>
				<script type="text/javascript" src="<?php echo DIR_JS; ?>jquery.editinplace.js"></script>
				<script>
					var group_id=<?php echo $_GET['id']; ?>;
					$("#name").editInPlace({
							url: '/ajax/ajax_group_edit.php',
							show_buttons: false,
							value_required: true,
							params: "group_id="+group_id,
							success:function(){$.pnotify({text: 'Nom mis à jour',type:'success'});}
						});
					$("#descr").editInPlace({
							url: '/ajax/ajax_group_edit.php',
							show_buttons: false,
							value_required: false,
							field_type: "textarea",
							params: "group_id="+group_id,
							success:function(){$.pnotify({text: 'Description mise à jour !',type:'success'});}
						});
					$("#recit").editInPlace({
							url: '/ajax/ajax_group_edit.php',
							show_buttons: false,
							value_required: false,
							field_type: "textarea",
							params: "group_id="+group_id,
							success:function(){$.pnotify({text: 'Récit mis à jour !',type:'success'});}
						});
				</script>
			<?php }
			if(isadmin() OR isset($chat) OR (isuser() AND $_SESSION['user_id']==$val['user_id']))
				{
				if($_SESSION['user_id']==$val['user_id']){$admin='&admin='.md5($val['id'].'admin');}else{$admin='';}
				echo '<div id="group_chat"><a class="btn btn-info" onclick="$(\'#group_chat\').html(\'<iframe src=\\\'/chat.php?mode=group&chan_id='.$val['id'].'&chan='.urlencode($val['name']).$admin.'\\\' style=\\\'width:100%;height:800px;border:none;\\\' seamless></iframe>\');">
							<i class="icon-globe icon-white"></i> Accéder au chat de la compagnie
						</a></div>';
				}
			}
		else{echo 'Cette compagnie n\'existe pas ou plus !<br>';include('pages/404.php');$error=1;}
		}
	}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////        CREATION D'UN GROUPE       //////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
elseif($mode=='create')
	{
	$titre='Création d\'une compagnie';
	if(isset($_SESSION['user_id']))
		{
		verif_uri('/group/create');
		if(!empty($_POST['name']) AND isset($_POST['descr']))
			{
			$req=$bdd->prepare('SELECT * FROM `group` WHERE name=?');
			$req->execute(array($_POST['name']));
			if($req->rowcount() == 0 OR 1==1)
				{
				$req=$bdd->prepare('INSERT INTO `group` (name,descr,user_id,create_date) VALUES (?,?,?,NOW())');
				$req->execute(array($_POST['name'],$_POST['descr'],$_SESSION['user_id']));
				$_SESSION['success'].='Compagnie fondée !';
				header('location:/group/'.$bdd->lastInsertId().'/'.to_url($_POST['name']).'');exit;
				}
			else{$_SESSION['err'].='Ce nom de compagnie est déjà utilisé, veuillez en choisir un autre.';}
			}
		?>
		<form method="post" action="" class="form-horizontal">
			<fieldset><legend>Création d'une compagnie</legend>
			  <div class="control-group">
				<label class="control-label" for="name">Nom</label>
				<div class="controls">
				  <input type="text" placeholder="nom" name="name" id="name" required <?php if(!empty($_POST['name'])){echo 'value="'.$_POST['name'].'" ';} ?>/>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="descr">Description</label>
				<div class="controls">
				  <textarea name="descr" rows="7" class="span7" id="descr" placeholder="description"><?php if(!empty($_POST['descr'])){echo $_POST['descr'];} ?></textarea>
				</div>
			  </div>
			  <div class="control-group"><div class="controls">
			    <button type="submit" class="btn">Fonder !</button>
			  </div></div>
			
			</fieldset>
		</form>
		<?php
		}
	else{echo ACCES_REFUSE_INVITE;$header='<meta name="robots" content="noindex,follow" />';}
	}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////      MODIFICATION D'UN GROUPE     //////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/*elseif($mode=='edit')
	{
	$titre='Modification d\'une compagnie	';
	if(isset($_GET['id']))
		{
		$req=$bdd->query('SELECT * FROM `group` WHERE etat!="delete" AND id='.$_GET['id']);
		$val=$req->fetch();
		if($req->rowcount() != 0 AND $val['user_id']==$_SESSION['user_id'])
			{
			
			if(!empty($_POST['name']) AND isset($_POST['descr']))
				{
				$req=$bdd->prepare('SELECT * FROM `group` WHERE name=?');
				$req->execute(array($_POST['name']));
				$a=$req->fetch();
				if($req->rowcount() == 0 OR $a['id']==$_GET['id'])
					{
					$req=$bdd->prepare('UPDATE `group` SET name=?,descr=? WHERE id='.$_GET['id']);
					$req->execute(array($_POST['name'],$_POST['descr']));
					$_SESSION['success'].='Compagnie modifiée !';
					header('location:/group-'.$_GET['id'].'-'.to_url($_POST['name']).'.html');exit;
					}
				else{$_SESSION['err'].='Ce nom de compagnie est déjà utilisé, veuillez en choisir un autre.';}
				}
			?>
			<form method="post" action="" class="form-horizontal"><?php echo secure::html($val['name'],1); ?>
				<fieldset><legend>Modification de la compagnie</legend>
				  <div class="control-group">
					<label class="control-label" for="name">Nom</label>
					<div class="controls">
					  <input type="text" placeholder="nom" name="name" id="name" required <?php echo 'value="'.secure::html($val['name'],1).'" '; ?>/>
					</div>
				  </div>
				  <div class="control-group">
					<label class="control-label" for="descr">Description</label>
					<div class="controls">
					  <textarea name="descr" rows="7" class="span7" id="descr" placeholder="description"><?php echo secure::html($val['descr'],1); ?></textarea>
					</div>
				  </div>
				  <div class="control-group"><div class="controls">
					<button type="submit" class="btn">Modifier !</button>
				  </div></div>
				
				</fieldset>
			</form>
			<?php
			}
		else{echo 'Cette compagnie n\'existe pas ou plus !';include('pages/404.php');$error=1;}
		}
	else{echo ACCES_REFUSE_INVITE;$header='<meta name="robots" content="noindex,follow" />';}
	}*/
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////      SUPPRESSION D'UN GROUPE      //////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
elseif($mode=='delete')
	{
	$titre='suppression d\'une compagnie';
	if(isset($_SESSION['user_id']))
		{
		if(isset($_GET['id']))
			{
			$req=$bdd->query('SELECT * FROM `group` WHERE id='.$_GET['id'].' AND user_id='.$_SESSION['user_id']);
			if($req->rowcount() != 0 OR $_SESSION['user']=='admin')
				{
				$val=$bdd->query('SELECT * FROM `group` WHERE id='.$_GET['id'])->fetch();
				if(isset($_POST['delete']))
					{
					$bdd->exec('UPDATE `group` SET etat="delete" WHERE id='.$_GET['id']);
					$bdd->exec('UPDATE fav SET etat="delete" WHERE type="group" AND fav_id='.$_GET['id']);
					$_SESSION['success'].=secure::html($val['name']).' supprimé !';
					header('location:/group');
					}
				else{
					$titre='suppression de '.secure::html($val['name'],1);
					echo'<div class="code">
					Êtes-vous certain(e) de vouloir supprimer cette compagnie ('.secure::html($val['name'],1).') ?<br/>
					<form action="" method="post" class="button-group" name="delete_group">
					<input type="hidden" name="delete" value="1">
					<span class="btn-group">
					<a class="btn btn-danger" onclick="document.forms.delete_group.submit()"><i class="icon-ok"></i> Oui</a>
					<a href="javascript:history.back()" class="btn"><i class="icon-remove icon-white"></i> Non</a>
					</span>
					</form></div><br/>
					';
					}
				}
			else{echo ACCES_REFUSE;$header='<meta name="robots" content="noindex,follow" />';}
			}
		else{echo ACCES_REFUSE;$header='<meta name="robots" content="noindex,follow" />';}
		}
	else{echo ACCES_REFUSE_INVITE;$header='<meta name="robots" content="noindex,follow" />';}
	}
elseif($mode=='erreur'){include('pages/404.php');$error=1;}
else{include('pages/404.php');$error=1;}