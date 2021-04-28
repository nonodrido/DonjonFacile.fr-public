<?php 
$titre='Module personnage';

$array_metier_select='<select size="1" name="metier" id="metier">';
foreach($array_metiers as $val)
	{
	if(isset($_POST['metier']) AND $_POST['metier']==$val){$array_metier_select.='<option value="'.$val.'" selected>'.$val.'</option>';}
	else{$array_metier_select.='<option value="'.$val.'">'.$val.'</option>';}
	}$array_metier_select.='</select>';
$origine_select='<select size="1" name="origine" id="origine">';
foreach($array_origines as $val)
	{
	if(isset($_POST['origine']) AND $_POST['origine']==$val){$origine_select.='<option value="'.$val.'" selected>'.$val.'</option>';}
	else{$origine_select.='<option value="'.$val.'">'.$val.'</option>';}
	}$origine_select.='</select>';
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* //////////////////////////          CREATION D'UN PERSO          ////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
if($mode=='create')
	{
	if(!isset($_SESSION['user'])){echo ACCES_REFUSE_INVITE;$header='<meta name="robots" content="noindex,follow" />';}
	else
		{
		if(!empty($_POST))
			{
			if(!in_array($_POST['origine'],$array_origines) OR !in_array($_POST['metier'],$array_metiers) OR ($_POST['sexe']!='M' AND $_POST['sexe']!='F')){$_SESSION['err'].='erreur critique dans les choix des listes déroulantes.<br/>';}
			if(!filter_var($_POST['AT'],FILTER_VALIDATE_INT) OR !filter_var($_POST['PRD'],FILTER_VALIDATE_INT) OR !filter_var($_POST['COU'],FILTER_VALIDATE_INT) OR !filter_var($_POST['CHA'],FILTER_VALIDATE_INT) OR !filter_var($_POST['FO'],FILTER_VALIDATE_INT) OR !filter_var($_POST['INT'],FILTER_VALIDATE_INT) OR !filter_var($_POST['AD'],FILTER_VALIDATE_INT) OR $_POST['COU']>20 OR $_POST['COU']<0 OR $_POST['AD']>20 OR $_POST['AD']<0 OR $_POST['CHA']>20 OR $_POST['CHA']<0 OR $_POST['FO']>20 OR $_POST['FO']<0 OR $_POST['INT']>20 OR $_POST['INT']<0)
				{if($_POST['COU']!=0 AND $_POST['CHA']!=0 AND $_POST['AD']!=0 AND $_POST['FO']!=0 AND $_POST['INT']!=0){$_SESSION['err'].='Au moins une des caractéristiques entrées est vide ou n\'est pas un nombre valide.<br/>';}}
			if(!filter_var($_POST['exp'],FILTER_VALIDATE_INT) OR $_POST['exp']<0){if($_POST['exp']!=0){$_SESSION['err'].='Le nombre de points d\'expérience entré n\'est pas un nombre valide.<br/>';}}
			if(!filter_var($_POST['ev'],FILTER_VALIDATE_INT)OR $_POST['ev']<0){if($_POST['ev']!=0){$_SESSION['err'].='La valeur d\'EV maximale n\'est pas un nombre valide.<br/>';}}
			if(!filter_var($_POST['ea'],FILTER_VALIDATE_INT)OR $_POST['ea']<0){if($_POST['ea']!=0){$_SESSION['err'].='La valeur d\'EA maximale n\'est pas un nombre valide.<br/>';}}
			if(!filter_var($_POST['pd'],FILTER_VALIDATE_INT)OR $_POST['pd']<0){if($_POST['pd']!=0){$_SESSION['err'].='La valeur de points de destin n\'est pas un nombre valide.<br/>';}}
			if(!filter_var($_POST['img'],FILTER_VALIDATE_URL) AND !empty($_POST['img'])){$_SESSION['err'].='url de l\'image du personnage non valide.<br/>';}
			if($_POST['name']=='' OR $_POST['exp']=='' OR $_POST['ev']=='' OR $_POST['ea']=='' OR $_POST['pd']==''){$_SESSION['err'].='Certains champs obligatoires ne sont pas remplis.<br/>';}
			if(is_numeric($_POST['name'])){$_SESSION['err'].='Le nom du personnage doit contenir au moins une lettre.<br/>';}
			if(isset($_POST['pnj'])){$pnj=true;}else{$pnj=false;}
			if(empty($_SESSION['err']))
				{
				if(empty($_POST['img'])){$img='http://donjonfacile.fr/ressources/img/avatar/default.png';}else{$img=$_POST['img'];}
				if($_POST['sexe']=='M'){$sex='masculin';}else{$sex='feminin';}
				try 
				{
				$req = $bdd->prepare('INSERT INTO perso(name,create_date, origine, metier, xp, sexe, AT, PRD, COU, INTL , CHA, AD, FO, descr, img, evmax, eamax, PDest, user_id, pnj) 
											 VALUES(:name, NOW(), :orig, :metier, :xp, :sexe, :AT, :PRD, :COU, :INT, :CHA, :AD, :FO, :descr, :img, :ev, :ea, :pd, '.$_SESSION['user_id'].',:pnj)');
				$req->execute(array('name' => $_POST['name'],
									'orig' => $_POST['origine'],
									'metier' => $_POST['metier'],
									'ev' => $_POST['ev'],
									'ea' => $_POST['ea'],
									'xp' => $_POST['exp'],
									'pd' => $_POST['pd'],
									'COU' => $_POST['COU'],
									'INT' => $_POST['INT'],
									'CHA' => $_POST['CHA'],
									'AD' => $_POST['AD'],
									'FO' => $_POST['FO'],
									'AT' => $_POST['AT'],
									'PRD' => $_POST['PRD'],
									'img' => $img,
									'sexe' => $sex,
									'descr' => $_POST['descr'],
									'pnj' => $pnj
									));
				$id=$bdd->lastInsertId();
				$req = $bdd->prepare('INSERT INTO users_persos SET perso_id = :perso_id, user_id=:user_id');
				$req->execute(array('user_id' => $_SESSION['user_id'],
									'perso_id' => $id
									));
				$array_orig_comp=array(	'Humain'=>array(),// rien de particulier
										'Barbare'=>array(71,83,112,114),
										'Nain'=>array(75,96,105,108),
										'Haut Elfe'=>array(712,111,116),
										'Demi-Elfe'=>array(73,711,85,101),
										'Elfe Sylvain'=>array(84,104,107,115,116),
										'Elfe Noir'=>array(72,88,711,115),
										'Orque'=>array(72,74,75,80,95,112,114),
										'Demi-Orque'=>array(72,83,95,112,114),
										'Gobelin'=>array(72,73,74,79,95,96,112,114),
										'Ogre'=>array(77,75,76,95,97,112,114),
										'Semi-homme'=>array(75,87,95,79,110),
										'Gnôme'=>array(71,73,84,85,88,96)
										);
				$array_metier_comp=array('Guerrier'=>array(77,80),
										'Gladiateur'=>array(77,80),
										'Paladin'=>array(84,97,109),
										'Ninja'=>array(115,92,88),
										'Assassin'=>array(115,92,88),
										'Voleur'=>array(73,85,88,711,113),
										'Prêtre'=>array(712,101,109),
										'Mage'=>array(712,109,111), 
										'Sorcier'=>array(712,109,111),
										'Ranger'=>array(88,711,84,103,106),
										'Ménestrel'=>array(79,84,712,93,99,104),
										'Pirate'=>array(73,75,78,85,90,103),
										'Marchand'=>array(73,78,712,93,101),
										'Ingénieur'=>array(81,89,91,110,113),
										'Bourgeois'=>array(73,79,84,712,105),
										'Noble'=>array(73,79,84,712,105),
										'Nécromancien'=>array(712,109,111)
										);
				if(array_key_exists($_POST['origine'],$array_orig_comp))
					{
					foreach($array_orig_comp[$_POST['origine']] as $comp_id)
						{
						$bdd->exec('INSERT INTO perso_items SET qte=1,type="comp",perso_id='.$id.',item_id='.$comp_id);
						}
					}
				if(array_key_exists($_POST['metier'],$array_metier_comp))
					{
					foreach($array_orig_comp[$_POST['metier']] as $comp_id)
						{
						$bdd->exec('INSERT INTO perso_items SET qte=1,type="comp",perso_id='.$id.',item_id='.$comp_id.' ON DUPLICATE KEY UPDATE date=NOW()');
						}
					}
				}catch(Exception $e){die('Erreur : '.$e->getMessage());}
				$_SESSION['success'].='Personnage créé avec succés. Vous pouvez désormais lui ajouter des objets, des compétences etc...<br/>';
				$_SESSION['update_perso_timer']=time()+100000*60;
				header('Location: /perso/'.$id.'/'.to_url($_POST['name']).'');exit();
				}
			}
$titre='Créer un nouveau personnage';$fil['personnages']='perso';
verif_uri('/perso/create');
?>
<h2>Créer un personnage</h2>
<form action="/perso/create" method="post" name="perso_creer" class="form-horizontal">
	<fieldset><legend>Informations générales </legend>
		<div class="control-group"><label class="control-label" for="name">Nom </label>
			<div class="controls"><input type="text" placeholder="nom" id="name" name="name" <?php if(isset($_POST['name'])){echo 'value="'.$_POST['name'].'"';}?>/></div>
		</div>
		<div class="control-group"><label class="control-label" for="origine">Origine </label><div class="controls"><?php echo $origine_select; ?></div></div>
		<div class="control-group"><label class="control-label" for="sex">Sexe </label>
			<div class="controls"><select size="1" name="sexe" id="sexe">
				<option value="M" <?php if(isset($_POST['sexe']) AND $_POST['sexe']=='M'){echo 'selected';} ?>>masculin</option>
				<option value="F" <?php if(isset($_POST['sexe']) AND $_POST['sexe']=='F'){echo 'selected';} ?>>feminin</option>
			</select></div>
		</div>
		<div class="control-group"><label class="control-label" for="metier">Métier </label><div class="controls"><?php echo $array_metier_select; ?>
		<span class="help-inline">Vous pourrez indiquer votre spécialisation directement sur la fiche de votre personnage.</span></div></div>
	</fieldset>
	<fieldset><legend>Caractéristiques </legend>
		<div class="control-group"><div class="controls">
		<a class="btn btn-primary" title="1d6+7" onclick="$('#COU').val(7+Math.floor(Math.random()*7));$('#AD').val(7+Math.floor(Math.random()*7));$('#CHA').val(7+Math.floor(Math.random()*7));$('#FO').val(7+Math.floor(Math.random()*7));$('#INT').val(7+Math.floor(Math.random()*7));"><i class="icon-random icon-white"></i> Tirage aléatoire</a>
		</div></div>
		<div class="control-group"><label class="control-label" for="COU">COU </label>
			<div class="controls"><input type="number" min="0" max="1000" placeholder="COU" id="COU" name="COU" <?php if(isset($_POST['COU'])){echo 'value="'.$_POST['COU'].'"';}else{echo'value="0"';}?>/></div>
		</div>
		<div class="control-group"><label class="control-label" for="INT">INT </label>
			<div class="controls"><input type="number" min="0" max="1000" placeholder="INT" id="INT" name="INT" <?php if(isset($_POST['INT'])){echo 'value="'.$_POST['INT'].'"';}else{echo'value="0"';}?>/></div>
		</div>
		<div class="control-group"><label class="control-label" for="CHA">CHA </label>
			<div class="controls"><input type="number" min="0" max="1000" placeholder="CHA" id="CHA" name="CHA" <?php if(isset($_POST['CHA'])){echo 'value="'.$_POST['CHA'].'"';}else{echo'value="0"';}?>/></div>
		</div>
		<div class="control-group"><label class="control-label" for="AD">AD </label>
			<div class="controls"><input type="number" min="0" max="1000" placeholder="AD" id="AD" name="AD" <?php if(isset($_POST['AD'])){echo 'value="'.$_POST['AD'].'"';}else{echo'value="0"';}?>/></div>
		</div>
		<div class="control-group"><label class="control-label" for="FO">FO </label>
			<div class="controls"><input type="number" min="0" max="1000" placeholder="FO" id="FO" name="FO" <?php if(isset($_POST['FO'])){echo 'value="'.$_POST['FO'].'"';}else{echo'value="0"';}?>/></div>
		</div>
	</fieldset>
	<fieldset><legend>Autres caractéristiques </legend>
		<div class="control-group"><label class="control-label" for="AT">Attaque </label>
			<div class="controls"><input type="number" min="0" placeholder="Attaque" id="AT" name="AT" <?php if(isset($_POST['AT'])){echo 'value="'.$_POST['AT'].'"';}else{echo'value="8"';}?>/></div>
		</div>
		<div class="control-group"><label class="control-label" for="PRD">Parade </label>
			<div class="controls"><input type="number" min="0" placeholder="Parade" id="PRD" name="PRD" <?php if(isset($_POST['PRD'])){echo 'value="'.$_POST['PRD'].'"';}else{echo'value="10"';}?>/></div>
		</div>
		<div class="control-group"><label class="control-label" for="exp">Expérience </label>
			<div class="controls"><input type="number" min="0" placeholder="exp" id="exp" name="exp" <?php if(isset($_POST['exp'])){echo 'value="'.$_POST['exp'].'"';}else{echo'value="0"';}?>/></div>
		</div>
		<div class="control-group"><label class="control-label" for="pd">Points de destin </label>
			<div class="controls"><input type="number" min="0" placeholder="point de destin" id="pd" name="pd" <?php if(isset($_POST['pd'])){echo 'value="'.$_POST['pd'].'"';}else{echo'value="0"';}?>/></div>
		</div>
		<div class="control-group"><label class="control-label" for="ev">EV max </label>
			<div class="controls"><input type="number" min="0" placeholder="EV max" id="ev" name="ev" <?php if(isset($_POST['ev'])){echo 'value="'.$_POST['ev'].'"';}else{echo'value="0"';}?>/></div>
		</div>
		<div class="control-group"><label class="control-label" for="ea">EA max </label>
			<div class="controls"><input type="number" min="0" placeholder="EA max" id="ea" name="ea" <?php if(isset($_POST['ea'])){echo 'value="'.$_POST['ea'].'"';}else{echo'value="0"';}?>/></div>
		</div>
		<div class="control-group"><label class="control-label" for="img">avatar du perso (462 x 472 px) </label>
			<div class="controls"><input size="50" type="text" placeholder="image (lien direct)" id="img" name="img" 
			<?php if(isset($_POST['img'])){echo 'value="'.$_POST['img'].'"';}?> onchange="$('#img_preview').attr('src',$(this).val());" />
			<span class="help-inline">
				<a href="/ajax/ajax_avatar.php" onclick="href_modal(this);return false;" class="btn btn-primary" rel="nofollow">Bibliothèque d'avatar</a>
				<a class="btn" target="_blank" href="/membre/library"><i class="icon-film"></i> Bibliothèque personnelle</a>
			</span>
			<span class="help-block"><img id="img_preview" style="margin:10px;margin-bottom:0;" src="" width="150" height="150"/></span></div>
			
		</div>
		<div class="control-group"><label class="control-label" for="descr">Description </label>
			<div class="controls"><textarea cols="100" rows="6" name="descr" id="descr" placeholder="description de votre personnage"><?php if(isset($_POST['descr'])){echo $_POST['descr'];}?></textarea></div>
		</div>
		<div class="control-group"><div class="controls">
			<label class="checkbox"><input type="checkbox" name="pnj" id="pnj"<?php if(isset($_POST['pnj'])){echo ' checked';}?>/>
			 Ce personnage est un pnj
		</div></div>
		<div class="control-group">
			<div class="controls">
				<span class="help-block">Votre personnage ne contiendra que les informations déjà indiquées, il faudra ensuite lui ajouter des
				compétences et de léquipement depuis le module objet.</span>
				<a class="btn"  onclick="document.forms.perso_creer.submit()">Créer ce personnage </a>
			</div>
		</div>
	</fieldset>
</form>
<?php
		}
	}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* //////////////////////////        PAGE DE DROITS GÉNÉRALE        ////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
elseif($mode=='droits')
	{
	if(empty($_GET['id']))
		{
		if(isuser())
			{
			$titre='Gestion des droits';
			$rep=$bdd->query('	SELECT p.name,u.pseudo,up.*
								FROM users_persos up
								INNER JOIN perso p 
								ON p.id = up.perso_id
								INNER JOIN users u 
								ON u.id = up.user_id
								WHERE p.user_id='.$_SESSION['user_id'].' AND up.user_id!='.$_SESSION['user_id'].' AND up.etat!="delete" AND p.etat!="delete" AND u.etat!="delete"
								ORDER BY p.name,u.pseudo');
			$list=$rep->fetchAll();
			$array_perso=array();
		echo'<div class="actions btn-toolbar center">
				<div class="btn-group">
					<a class="btn" href="/perso/create"><i class="icon-play-circle"></i> Créer un nouveau personnage</a>
					<a class="btn" href="/perso"><i class="icon-list""></i> Liste de vos personnages</a>
				</div>
			</div><br>';
			foreach($list as $val)
				{
				$array_perso[$val['perso_id']][]=$val;
				}
			$array_droits=array('full'=>'Complets','group'=>'Restreints','wiew'=>'Compagnie seulement');
			echo '<table class="table table-hover table-striped"><thead><tr><th>Personnage</th><th>Utilisateurs</th><th>Type de droits</th><th>Action</th></thead><tbody>';
			foreach($array_perso as $val)
				{
				$nb=count($val);
				echo '	<tr>
							<td rowspan="'.$nb.'"><a href="/perso/'.$val[0]['perso_id'].'/'.to_url($val[0]['name']).'">'.secure::html($val[0]['name'],1).'</a></td>
							<td><a href="/membre/'.$val[0]['user_id'].'/'.to_url($val[0]['pseudo']).'">'.secure::html($val[0]['pseudo'],1).'</a></td>
							<td>'.$array_droits[$val[0]['type']].'</td>
							<td rowspan="'.$nb.'"><a class="btn btn-info" href="/perso/droits/'.$val[0]['perso_id'].'/'.to_url($val[0]['name']).'"><i class="icon-lock icon-white"></i> Modifier</a></td>
						</tr>';
				foreach($val as $cle=>$val2)
					{
					if($cle!=0){
					echo '	<tr>
								<td><a href="/membre/'.$val2['user_id'].'/'.to_url($val2['pseudo']).'">'.secure::html($val2['pseudo'],1).'</a></td>
								<td>'.$array_droits[$val2['type']].'</td>
							</tr>';}
					}
				}
			echo '</tbody></table>';
			}
		else{echo ACCES_REFUSE_INVITE;$header='<meta name="robots" content="noindex,follow" />';}
		}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ///////////////////////////        DROITS D'UN PERSONNAGE        ////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
	else
		{
		if(!isset($_SESSION['user']) OR  !get_droits_perso($_GET['id'],$_SESSION['user_id'],'wiew')){echo ACCES_REFUSE;$header='<meta name="robots" content="noindex,follow" />';}
		else
			{
			$user_id=$_SESSION['user_id'];
			$rep = $bdd->prepare('SELECT perso.*, users.pseudo as user_name
								  FROM perso 
								  INNER JOIN users 
								  ON perso.user_id = users.ID 
								  WHERE perso.id= ?');
			$rep->execute(array($_GET['id']));
			$data = $rep->fetch();
			if($rep->rowCount() == 0){echo 'Ce personnage n\'existe pas !<br/>';}
			else
				{
				verif_uri('/perso/droits/'.$_GET['id'].'/'.to_url($data['name']));
				echo '<h2>Droits de '.secure::html($data['name'],1).'</h2>';
				if(!empty($_POST['delete']))
					{
					$bdd->exec('UPDATE users_persos SET etat="delete" WHERE user_id='.$_SESSION['user_id'].' AND perso_id='.$_GET['id']);
					$_SESSION['success'].='Vous n\'avez plus les droits sur '.$data['name'];
					header('Location: /perso/'.$_GET['id'].'/'.to_url($data['name']).'');exit();
					}
				$titre='Gestion des droits sur '.$data['name'];
				if($data['user_id']!=$_SESSION['user_id'] AND !isadmin())
					{echo '<div class="btn-toolbar center">
					<a class="btn" href="/membre/persos/'.$data['user_id'].'"><i class="icon-th-list"></i> autres personnages du membre</a>
						<div class="btn-group">
						<a class="btn" href="/perso/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-folder-open"></i> Personnage</a>
						<a class="btn" href="/perso/fiche/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-file"></i> Fiche</a>
						</div>
					</div>
					Seul le créateur de ce personnage pour modifier les droits de ce personnage pour une autre personne.<br/>
					Vous pouvez toutefois supprimer ce personnage de ceux dont vous avez le contrôle, mais cette action sera irréversible 
					et en cas d\'erreur il vous faudra demander à nouveau l\'autorisation au créateur du personnage. Faites donc en votre âme et 
					conscience.<br/><br/>
					<form action="'.$_SERVER['REQUEST_URI'].'" method="post" name="delete_perso_droit">
						<input type=hidden name="delete" value="delete"/>
						<div class="center"><a class="btn btn-danger" onclick="document.forms.delete_perso_droit.submit()"><i class="icon-ban-circle"></i> Perdre les droits sur ce personnage</a></div>
					</form>
					';
					}
				else
					{
					if(!empty($_POST['pseudo']) AND !empty($_POST['option_droits']))
						{
						if(in_array($_POST['option_droits'],array('full','group','wiew')))
							{
							$rep=$bdd->prepare('SELECT * FROM users WHERE pseudo=:pseudo AND etat!="delete"');
							$rep->execute(array('pseudo'=>$_POST['pseudo']));
							if($rep->rowCount() == 0){$_SESSION['err'].='Utilisateur inconnu !';}
							else{
								$result=$rep->fetch();
								$bdd->exec('INSERT INTO users_persos VALUES(NOW(),"",'.$result['id'].','.$_GET['id'].',"'.$_POST['option_droits'].'")
												ON DUPLICATE KEY UPDATE date=NOW(), etat="default", type="'.$_POST['option_droits'].'"');
								$_SESSION['success'].='Modification des droits pour '.$_POST['pseudo'].' sur '.$data['name'].' effectuée.';
								}
							}
						}
					if(!empty($_POST['give_pseudo']) AND !empty($_POST['give_mdp']))
						{
						if($_SESSION['user_data']['mdp']===sha1(md5('salt').md5($_POST['give_mdp'])) OR isadmin()){
						$rep=$bdd->prepare('SELECT * FROM users WHERE pseudo=:pseudo AND etat!="delete"');
						$rep->execute(array('pseudo'=>$_POST['give_pseudo']));
						if($rep->rowCount() == 0){$_SESSION['err'].='Utilisateur inconnu !';}
						else{
							$result=$rep->fetch();
							if($_SESSION['user_id']!=$result['id'])
								{
								$bdd->exec('UPDATE perso SET user_id='.$result['id'].' WHERE id='.$_GET['id']);
								$_SESSION['success'].='Ce personnage a été donné à '.$_POST['give_pseudo'].' avec succès.';
								$req = $bdd->prepare('INSERT INTO message(auteur_id,destinataire_id, sujet, contenu) VALUES('.$_SESSION['user_id'].',:id, :sujet, :contenu)');
								$sujet='Transfert de personnage vers votre compte';
								$contenu='Le personnage suivant vous a été transféré par '.$_SESSION['pseudo'].' 
								[url=/perso/'.$_GET['id'].']Cliquez ici pour accéder à ce personnage[/url]';
								$req->execute(array(
									'sujet' => $sujet,
									'contenu' => $contenu,
									'id' => $result['id']
									));
								header('Location: /perso/'.$_GET['id'].'/'.to_url($data['name']));exit;
								}
							}
						}else{$_SESSION['err'].='Mot de passe incorrect';}
						}
					if(!empty($_POST['choix']) AND filter_var($_POST['choix'], FILTER_VALIDATE_INT))
						{
						$bdd->exec('UPDATE users_persos SET etat="delete" WHERE user_id='.$_POST['choix'].' AND perso_id='.$_GET['id']);
						$_SESSION['success'].='Modification des droits sur '.$data['name'].' effectuée.';
						}
					try{
					echo '<div class="center btn-toolbar">
					<a class="btn" href="/perso"><i class="icon-th-list"></i> retour à la liste de mes personnages</a>
						<div class="btn-group">
						<a class="btn" href="/perso/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-folder-open"></i> Personnage</a>
						<a class="btn" href="/perso/fiche/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-file"></i> Fiche</a>
						</div>
					<a class="btn" href="/perso/droits"><i class="icon-lock"></i> Page générale des droits</a>
					</div>
					
					<div class="well"><div class="row-fluid">
					<div class="span6">
					<fieldset><legend>Ajouter un utilisateur</legend>
						<br>
						<form action="'.$_SERVER['REQUEST_URI'].'" method="post" name="new_perso_droit">
							<div class="input-append">
								<input class="ajax-typeahead-pseudo" type="text" autocomplete="off" name="pseudo" id="inputString"/>
								<a class="btn" onclick="document.forms.new_perso_droit.submit()"><i class="icon-plus"></i> Ajouter cet utilisateur</a>
							</div>
							<label class="radio">
							  <input type="radio" name="option_droits" value="full" checked>
							  Donner les droits complets à l\'utilisateur
							</label>
							<label class="radio">
							  <input type="radio" name="option_droits" value="group">
							  Permettre la modification des éléments secondaires uniquement (Tout sauf le nom, métier, origine, sexe et avatar)
							</label>
							<label class="radio">
							  <input type="radio" name="option_droits" value="wiew">
							  Seul l\'ajout dans une compagnie est autorisé (aucune modification du personnage n\'est possible).
							</label>
							<span class="help-block">
								En aucun cas un utilisateur autre que vous ne pourra supprimer votre personnage. <br>
								Toutes les modifications effectuées par
								les utilisateurs autorisés sont sous votre responsabilité et ne seront pas réparées (sauf cas particulier).
							</span>
						</form>
					</fieldset>
					</div>
					<div class="span6">
					<fieldset><legend>Transférer votre personnage</legend>
						<form action="'.$_SERVER['REQUEST_URI'].'" class="form-horizontal" method="post" name="perso_give"><br>
							  <div class="control-group">
								<label class="control-label" for="give_pseudo">Pseudo du nouveau propriétaire</label>
								<div class="controls">
								  <input type="text" class="ajax-typeahead-pseudo" id="give_pseudo" name="give_pseudo" placeholder="Pseudo">
								</div>
							  </div>
							  <div class="control-group">
								<label class="control-label" for="give_mdp">Entrez votre mot de passe</label>
								<div class="controls">
								  <input type="password" id="give_mdp" name="give_mdp" placeholder="Mot de passe">
								</div>
							  </div>
							  <div class="control-group">
								<div class="controls">
								  <span class="help-block">
									Cette action est définitive ! Ne faites donc pas n\'importe quoi avec !
								  </span>
								  <input type="submit" value="Transférer votre personnage" onclick="return(confirm(\'Êtes-vous certain de vouloir donner ce personnage à ce joueur ?\'));" class="btn btn-primary">
								</div>
							  </div>
						</form>
					</fieldset>
					</div>
					</div></div>
					<fieldset><legend>Liste des utilisateurs ayant accés à votre personnage</legend>
						<br>';
						$req=$bdd->prepare('SELECT users_persos.*,users.pseudo as user_name FROM users_persos INNER JOIN users ON users_persos.user_id = users.ID  WHERE perso_id=:perso_id AND user_id!=:user_id  AND users_persos.etat!="delete" ORDER BY users.pseudo');
						$req->execute(array('user_id'=>$data['user_id'],'perso_id'=>$_GET['id']));
						if($etat=$req->rowCount() == 0)
							{
							echo '<h4>Aucun utilisateur autre que vous ne peut modifier ce personnage.</h4>';
							}
						else{
							echo '<form action="'.$_SERVER['REQUEST_URI'].'" method="post" class="pull-right" name="delete_perso_droit">
									<div class="input-append"><select name="choix">';
							$val='';
							$array_droit=array('full'=>'complet','group'=>'restreint','wiew'=>'ajout compagnie');
							while($result=$req->fetch())
								{
								echo '<option value="'.$result['user_id'].'">'.secure::html($result['user_name'],1).'</option>';
								$val.='<li><b>'.secure::html($result['user_name'],1).'</b> ('.$array_droit[$result['type']].')</li>';
								}
							echo '</select><a class="btn btn-danger" onclick="document.forms.delete_perso_droit.submit()"><i class="icon-remove icon-white"></i> Supprimer les droits de cet utilisateur</a>';
							echo'</div></form>';
							echo '	<ul>
									'.$val.'
									</ul>';
							}
					echo '</fieldset>';
					}catch(Exception $e){die('Erreur : '.$e->getMessage());}
					}
				}
			}
		}
	}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////          FICHE D'UN PERSO          /////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
elseif($mode=='fiche')
	{
	if(isset($_GET['id']))
		{
				//récupération des données du perso
		if(!isset($_SESSION['user_id'])){$user_id='0';}
		else{$user_id=$_SESSION['user_id'];}
		$rep = $bdd->prepare('SELECT perso.*, users.pseudo as user_name
							  FROM perso 
							  INNER JOIN users 
							  ON perso.user_id = users.ID 
							  WHERE perso.id= ?');
		$rep->execute(array($_GET['id']));
		$donnees = $rep->fetch();
		if(!empty($donnees['name'])){
		verif_uri('/perso/fiche/'.$_GET['id'].'/'.to_url($donnees['name']));
		$rep->closeCursor();
		if(get_droits_perso($donnees['id'],$user_id,'wiew')){$droits=1;}else{$droits=0;}
		$titre='Fiche de '.$donnees['name'];
		if(!isset($_SESION['user_id']) OR $donnees['user_id']!=$_SESION['user_id']){$fil['personnages de '.secure::html($donnees['user_name'])]='membre_persos-'.$donnees['user_id'];}
		else{$fil['personnages']='perso';}
		?>
		<div class="center btn-toolbar">
			<div class="btn-toolbar">
				
			<?php if(isset($_SESSION['user_id']) AND $_SESSION['user_id']==$donnees['user_id'])
					{echo '<a class="btn" href="/perso"><i class="icon-th-list"></i> Retour à la liste de mes personnages</a>';}
			else{echo '<a class="btn" href="/membre/persos/'.$donnees['user_id'].'"><i class="icon-th-list"></i> Autres personnages du membre</a>';}
			?>
			<div class="btn-group">
			<a class="btn" href="/perso/<?php echo $donnees['id'].'/'.to_url($donnees['name']);?>"><i class="icon-folder-open"></i> Voir</a>
			<?php if($droits==1){ ?>
				
				<a class="btn" href="/perso/droits/<?php echo $donnees['id'].'/'.to_url($donnees['name']);?>"><i class="icon-lock"></i> Droits</a>
			<?php } ?>
			</div></div></div>
			<div class="center">
				Code forum : <div class="input-append">
				<input type="text" value="[img]http://donjonfacile.fr/fiche-<?php echo $donnees['id'].'-'.to_url($donnees['name']);?>.jpg[/img][img]http://donjonfacile.fr/fiche-advanced-<?php echo $donnees['id'].'-'.to_url($donnees['name']);?>.jpg[/img]"/>
				<a class="btn" id="copy_bbcode" title="Copier dans le presse-papier"><i class="icon-inbox"></i> Copier</a>
				</div>
			</div><br>
			<?php
		echo '<div class="center row-fluid">
				<!--  FICHE PRINCIPALE  -->
				<div class="span6">
				<p><a href="/fiche-'.$_GET['id'].'.jpg" class="btn" download="fiche de '.secure::html($donnees['name'],1).'">
				<i class="icon-hdd"></i> Télécharger la fiche</a>
				Lien dynamique de l\'image pour les forums : </p>
				<div class="input-append">
				<input type="text" value="http://donjonfacile.fr/fiche-'.$_GET['id'].'-'.to_url($donnees['name']).'.jpg"/>
				<a class="btn" id="copy_main" title="Copier dans le presse-papier"><i class="icon-inbox"></i> Copier</a>
				</div><br>
				<img alt="fiche de '.secure::html($donnees['name'],1).'" style="max-width:100%;" src="/fiche-'.$_GET['id'].'-'.to_url($donnees['name']).'.jpg"/>
				</div>
				
				<!--  FICHE SECONDAIRE  -->
				<div class="span6">
				<p><a href="/fiche-advanced-'.$_GET['id'].'.jpg" class="btn" download="fiche secondaire de '.secure::html($donnees['name'],1).'">
				<i class="icon-hdd"></i> Télécharger la fiche secondaire</a>
				Lien dynamique de l\'image pour les forums : </p>
				<div class="input-append">
				<input type="text" value="http://donjonfacile.fr/fiche-advanced-'.$_GET['id'].'-'.to_url($donnees['name']).'.jpg"/>
				<a class="btn" id="copy_advanced" title="Copier dans le presse-papier"><i class="icon-inbox"></i> Copier</a>
				</div><br>
				<img alt="fiche secondaire de '.secure::html($donnees['name'],1).'" style="max-width:100%;" src="/fiche-advanced-'.$_GET['id'].'-'.to_url($donnees['name']).'.jpg"/>
				</div>
				<script>
					$(document).ready(function(){
					$("#copy_main").zclip({
							copy:\'http://donjonfacile.fr/fiche-'.$_GET['id'].'-'.to_url($donnees['name']).'.jpg\',
							afterCopy:function(){$.pnotify({text:\'Le lien a été copié dans votre presse-papier\',title:\'Texte copié\',type:\'success\'});;}
						});
					$("#copy_advanced").zclip({
							copy:\'http://donjonfacile.fr/fiche-advanced-'.$_GET['id'].'-'.to_url($donnees['name']).'.jpg\',
							afterCopy:function(){$.pnotify({text:\'Le lien a été copié dans votre presse-papier\',title:\'Texte copié\',type:\'success\'});;}
						});
					$("#copy_bbcode").zclip({
							copy:\'[img]http://donjonfacile.fr/fiche-'.$_GET['id'].'-'.to_url($donnees['name']).'.jpg[/img][img]http://donjonfacile.fr/fiche-advanced-'.$_GET['id'].'-'.to_url($donnees['name']).'.jpg[/img]\',
							afterCopy:function(){$.pnotify({text:\'Le lien a été copié dans votre presse-papier\',title:\'Texte copié\',type:\'success\'});;}
						});
					});
				</script>
			</div><br>
			';
		}else{echo 'personnage inexistant ou supprimé';}
		}
	}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////      PAGE GENERALE DES PERSOS     //////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
elseif(empty($mode))
	{
	
	if(!isset($_SESSION['user']) AND empty($_GET['id']))
	{
	verif_uri('/perso');
	//liste des persos du site
	$req=$bdd->query('SELECT p.*,u.pseudo FROM perso p INNER JOIN users u ON p.user_id = u.id WHERE p.etat!="delete" AND u.etat!="delete" ORDER BY p.name');
	echo '	<fieldset><legend>Liste des personnages</legend>
			<table class="table jtable table-bordered table-striped table-hover">
				<thead><tr><th>Nom</th><th>Créateur</th><th>Origine</th><th>Métier</th></tr></thead><tbody>';
	while($val=$req->fetch())
		{
		echo '<tr>
				<td><a href="/perso/'.$val['id'].'/'.to_url($val['name']).'">'.secure::html($val['name'],1).'</a></td>
				<td><a href="/membre/'.$val['user_id'].'/'.to_url($val['pseudo']).'">'.secure::html($val['pseudo'],1).'</a></td>
				<td>'.secure::html($val['origine']).'</td>
				<td>'.secure::html($val['metier']).'</td>
			  </tr>';
		}
	echo '</tbody></table></fieldset>';
	}
	else
		{
		if(empty($_GET['id'])) // si l'utilisateur demande sa page perso
			{
			verif_uri('/perso');			?>
			<div class="actions btn-toolbar center">
				<div class="btn-group">
					<a class="btn" href="/perso/create"><i class="icon-play-circle"></i> Créer un nouveau personnage</a>
					<a class="btn" href="/perso/droits"><i class="icon-lock""></i> Gestion des droits</a>
				</div>
			</div><br>
			<?php
			$user_id=$_SESSION['user_id'];
			$titre='Liste de vos personnages';$fil['profil']='membre';
			$req2=$bdd->prepare('SELECT * FROM perso WHERE user_id = :id AND etat != "delete" ORDER BY name');
			$req2->execute(array('id' => $user_id));
			echo '<h2>Personnages créés :</h2><div class="row-fluid">';
			$i=0;
			while($data=$req2->fetch())
			{
			if(($i%3)==0){$margin=' style="margin-left:0;"';}else{$margin='';}$i++;
			echo '<div class="span4"'.$margin.'> 
					<fieldset><legend>'.$data['name'].'</legend>
					<table class="table">
						<thead><tr><th>COU</th><th>INT</th><th>CHA</th><th>AD</th><th>FO</th></tr></thead>
						<tbody><tr><td>'.$data['COU'].'</td><td>'.$data['INTL'].'</td><td>'.$data['CHA'].'</td><td>'.$data['AD'].'</td><td>'.$data['FO'].'</td></tr></tbody>
					</table>
					'.$data['origine'].'<span class="pull-right">'.$data['metier'].'</span><br/>
					Niveau <b>'.get_niv($data['xp']).'</b> 
					<span class="pull-right">
					'.get_money(array('PO'=>$data['PO'],'PA'=>$data['PA'],'PC'=>$data['PC'],'LT'=>$data['LT'], 'LB'=>$data['LB']))
					.' PO </span><br/>
					<div class="btn-group span12 center">
						<div class="btn-group">
						  <a class="btn" href="/perso/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-folder-open"></i> Voir</a>
						  <button class="btn dropdown-toggle" data-toggle="dropdown">
							<span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu" style="text-align:left;">
							<li><a href="/perso/fiche/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-file"></i> Fiche</a></li>
							<li class="divider"></li>
							<li><a href="/perso/droits/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-lock"></i> Droits</a></li>
							<li><a href="/perso/delete/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-remove"></i> supprimer</a></li>
						  </ul>
						</div>
					</div>
				</fieldset>
				</div>';
				/* <!--<div class="papers"> 
						<table style="margin:3px;text-align:center;">
							<tr><td><b>'.$data['name'].'</b> ('.$data['origine'].') </td> <td >'.$data['metier'].' </td>
							<td rowspan="3"><div class="button-group minor-group" style="padding-left:50px;">
												<a class="button primary icon search" href="/perso-'.$data['id'].'-'.to_url($data['name']).'.html">Voir</a>
												<a class="button icon edit" href="/perso_edit-'.$data['id'].'-'.to_url($data['name']).'.html">Modifier</a>
												<a class="button icon lock" href="/perso_droits-'.$data['id'].'-'.to_url($data['name']).'.html">Droits</a>
												<a class="button icon log" href="/perso_fiche-'.$data['id'].'-'.to_url($data['name']).'.html">Fiche</a>
												<a class="button danger icon remove" href="perso_delete-'.$data['id'].'-'.to_url($data['name']).'.html">supprimer</a>
											</div></td></tr>
							<tr><td rowspan="2"><table class="table_carac">
										<tr><th>COU</th><th>INT</th><th>CHA</th><th>AD</th><th>FO</th></tr>
										<tr><td>'.$data['COU'].'</td><td>'.$data['INTL'].'</td><td>'.$data['CHA'].'</td><td>'.$data['AD'].'</td><td>'.$data['FO'].'</td></tr>
									</table></td>
							 <td>Niveau <b>'.get_niv($data['xp']).'</b> ('.$data['xp'].' exp)</td></tr>
							<tr><td>'.get_money(array('PO'=>$data['PO'],'PA'=>$data['PA'],'PC'=>$data['PC'],'LT'=>$data['LT'], 'LB'=>$data['LB'])).' PO </td></tr>
						</table>
					</div>-->'; */
			}
			if($req2->rowCount() == 0){echo '<br/>Vous n\'avez créé aucun personnage pour l\'instant.';}
			$req2->closeCursor();
			echo '</div><h2>Autres personnages :</h2><div class="row-fluid">';
				$r='SELECT p.* 
					FROM perso p
					INNER JOIN users_persos up
					ON p.id = up.perso_id 
					WHERE p.etat!="delete" AND up.etat!="delete" AND up.user_id ='.$_SESSION['user_id'].' AND p.user_id !='.$_SESSION['user_id'];//echo $r;
				$req=$bdd->query($r);
				if($req->rowCount() == 0){echo '<br/>Aucun personnage trouvé.';}
				else{
				$i=0;
			while($data=$req->fetch())
				{
				if(($i%3)==0){$margin=' style="margin-left:0;"';}else{$margin='';}$i++;
				echo '<div class="span4"'.$margin.'> 
						<fieldset><legend>'.$data['name'].'</legend>
						<table class="table">
							<thead><tr><th>COU</th><th>INT</th><th>CHA</th><th>AD</th><th>FO</th></tr></thead>
							<tbody><tr><td>'.$data['COU'].'</td><td>'.$data['INTL'].'</td><td>'.$data['CHA'].'</td><td>'.$data['AD'].'</td><td>'.$data['FO'].'</td></tr></tbody>
						</table>
						'.$data['origine'].'<span class="pull-right">'.$data['metier'].'</span><br/>
						Niveau <b>'.get_niv($data['xp']).'</b> 
						<span class="pull-right">
						'.get_money(array('PO'=>$data['PO'],'PA'=>$data['PA'],'PC'=>$data['PC'],'LT'=>$data['LT'], 'LB'=>$data['LB']))
						.' PO </span><br/>
						<div class="btn-group center span12">
							<div class="btn-group">
							  <a class="btn" href="/perso/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-folder-open"></i> Voir</a>
							  <button class="btn dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
							  </button>
							  <ul class="dropdown-menu" style="text-align:left;">
								<li><a href="/perso/fiche/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-file"></i> Fiche</a></li>
								<li class="divider"></li>
								<li><a href="/perso/droits/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-lock"></i> Droits</a></li>
								<li><a href="/perso/delete/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-remove"></i> supprimer</a></li>
							  </ul>
							</div>
						</div>
					</fieldset>
					</div>';
				}
			}
		echo '</div><hr>';
			//liste des persos du site
		$req=$bdd->query('SELECT p.*,u.pseudo FROM perso p INNER JOIN users u ON p.user_id = u.id WHERE p.etat!="delete" AND u.etat!="delete" ORDER BY p.name');
		echo '	<fieldset><legend>Liste des personnages</legend>
				<table class="jtable table table-bordered table-striped table-hover">
					<thead><tr><th>Nom</th><th>Créateur</th><th>Origine</th><th>Métier</th></tr></thead><tbody>';
		while($val=$req->fetch())
			{
			echo '<tr>
					<td><a href="/perso/'.$val['id'].'/'.to_url($val['name']).'">'.secure::html($val['name'],1).'</a></td>
					<td><a href="/membre/'.$val['user_id'].'/'.to_url($val['pseudo']).'">'.secure::html($val['pseudo'],1).'</a></td>
					<td>'.secure::html($val['origine']).'</td>
					<td>'.secure::html($val['metier']).'</td>
				  </tr>';
			}
		echo '</tbody></table></fieldset>';
			echo '</div>';
			}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////           PAGE D'UN PERSO         //////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
		else // si un personnage particulier veut être visionné
			{
$perso_id=$_GET['id'];
$rep = $bdd->prepare('	SELECT perso.*, users.pseudo as user_name
									FROM perso 
									INNER JOIN users 
									ON perso.user_id = users.ID 
									WHERE perso.id= ?
									AND perso.etat!="delete"
									LIMIT 1');								
$rep->execute(array($perso_id));

if($rep->rowcount() ==0)
	{
	$titre='Fiche personnage';
	$header='<meta name="robots" content="noindex,follow" />';
	echo '<br/>Ce personnage n\'existe pas ou plus !<br/>';
	include('pages/404.php');
	$error=1;
	}
else{
$perso=$rep->fetch();
verif_uri('/perso/'.$perso_id.'/'.to_url($perso['name']));
$droits=false;
if(isuser() AND get_droits_perso($perso['id'],$_SESSION['user_id'],'group')){$droits=true;}

$titre=ucfirst(secure::html($perso['name'],1));

$rep = $bdd->prepare('	SELECT p.*, t.*
						FROM perso_items AS p
						INNER JOIN item AS t
						ON t.id = p.item_id
						WHERE p.perso_id=? AND p.etat!="delete" AND t.etat!="delete" 
						AND (p.qte>0 OR p.waste>0 OR (p.equip>0 AND (p.type="arme" OR p.type="protec")))
						ORDER BY t.name
						');
$rep->execute(array($perso_id));
$items=$rep->fetchAll();
$protec_list = $bdd->query('SELECT p.*, t.*
							FROM perso_items AS p
							INNER JOIN item AS t
							ON t.id = p.item_id
							WHERE p.perso_id='.$_GET['id'].' AND p.type="protec" AND p.equip>0 
							ORDER BY t.name');
$total_protec=0;
if($protec_list->rowCount() != 0){
while($protec = $protec_list->fetch())
	{
	$item=new item($protec['item_id']);
	$total_protec+=$item->carac;
	}
}
$comp_protec_add=$bdd->query('SELECT p.*
							FROM perso_items AS p
							WHERE p.perso_id='.$_GET['id'].' AND p.item_id=117 AND p.etat!="delete" AND p.qte>0
							LIMIT 1');// comp "truc de mauviette"
if($comp_protec_add->rowCount() != 0){
	$total_protec++;
	}
if($droits)
	{
	$header='	<script type="text/javascript" >var perso_id='.$_GET['id'].';var img_old="'.$perso['img'].'";</script>
				<script type="text/javascript" src="/ressources/js/'.@filemtime('ressources/js/perso_edit.js').'-perso_edit.js" ></script>';
	}
$rupture=array('jamais','1','1 à 2','1 à 3','1 à 4','1 à 5');
/* <a class="btn dropdown-toggle btn-mini" data-toggle="dropdown"><span class="caret"></span></a>
<div class="dropdown-menu">
	<div class="input-append">
		<input id="item_qte_input'.$item['id'].'" type="number" value="'.$item['qte'].'"/>
		<button class="btn" type="button" onclick="qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'item_qte_input'.$item['id'].'\').val()));"><i class="icon-ok-sign"></i></button>
	</div>
</div> */
?>
<div style="margin-right:161px;">
	<div class="pull-left">
	<div class="btn-group">
	  <a class="btn dropdown-toggle btn-inverse" data-toggle="dropdown" >
		<i class="icon-white icon-user"></i> Créateur 
		<span class="caret"></span>
	  </a>
	  <div class="dropdown-menu user_div_<?php echo secure::html($perso['user_id'],1);?>" style="padding:5px;">
		<script>
			$(document).ready(function() {
				$(".user_div_<?php echo secure::html($perso['user_id'],1);?>").load("/ajax/ajax_user.php?user_id=<?php echo secure::html($perso['user_id'],1);?>");
			});
		</script>
	  </div>
	</div>
	<?php 
	fav_button($perso['id'],'perso');
	group_button($perso['id']);
	if($droits){help_button('Page du personnage','Ceci est la page principal de votre personnage. Elle ne permet que peu de modifications
					mais présente votre personnage en totalité à la manière de la fiche papier officiel. Pour modifier ce personnage, cliquez sur 
					\'Modifier le personnage\'. Une fiche conforme au format papier est disponible dans l\'onglet \'Fiche du personnage\'. 
					Vous pouvez autoriser d\'autres personnes à modifier votre personnage en cliquant sur \'Droits\'.','bottom');}
	?>
	</div>
	<div class="center btn-toolbar">
	<?php if(isset($_SESSION['user_id']) AND $_SESSION['user_id']==$perso['user_id'])
			{echo '<a class="btn" href="/perso"><i class="icon-th-list"></i> Retour à la liste de mes personnages</a>';}
	else{echo '<a class="btn" href="/membre/persos/'.$perso['user_id'].'/'.to_url($perso['user_name']).'"><i class="icon-th-list"></i> Autres personnages du membre</a>';}
	?>
		<div class="btn-group">
		<a class="btn" href="/perso/<?php echo $perso['id'].'/'.to_url($perso['name']);?>" 
		<?php if($droits){ ?>onclick="<?php echo'reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');'; ?>return false;"<?php } ?>>
		<i class="icon-refresh"></i> Actualiser</a>
	<?php if($droits){ ?>
		<a class="btn" href="/perso/droits/<?php echo $perso['id'].'/'.to_url($perso['name']);?>"><i class="icon-lock"></i> Droits</a>
	<?php } ?>
		<a class="btn" href="/perso/fiche/<?php echo $perso['id'].'/'.to_url($perso['name']);?>"><i class="icon-file"></i> Fiche</a>
	<?php if($droits){ ?>
		<a class="btn" href="/perso/delete/<?php echo $perso['id'].'/'.to_url($perso['name']);?>"><i class="icon-remove"></i> Suppression</a>
	<?php } ?>
		</div>
	</div>
</div>
<?php if($droits){ ?>
<a id="modif_toggle" onclick="perso_toggle();" class="btn btn-inverse" style="position:fixed;top:109px;right:20px;z-index:100000000000000000000;">
	<span class="modif"><i class="icon-pencil icon-white"></i> Modification</span>
	<span class="modif hidden"><i class="icon-list icon-white"></i> Vue standard</span>
</a>
<?php } ?>
<?php if(isset($_GET['inplace_value']) OR isset($_POST['inplace_value'])){ ?>
<div class="alert alert-error">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>Erreur!</strong> Le site a détecté une tentative de modification de votre personnage mais ne peut pas traiter la demande !<br>
  Pour modifier une information, il faut saisir la valeur souhaitée puis cliquer <u>hors du cadre de modification</u>.<br>
  Appuyer sur la touche entrée ne permet pas de valider mais se contente de recharger la page.
</div>
<?php } ?>
<div class="info-box" style="margin-top:10px;">
	Vous pouvez modifier ce personnage en cliquant simplement sur les valeurs que vous souhaitez changer.<br/>
	La couleur de fond des valeurs modifiables devient bleue au passage de la souris, cliquez et modifiez la valeur. <br/>
	Pour valider, cliquez hors de la zone, la sauvegarde de votre modification est immédiate (appuyer sur entrée ne fonctionne pas).
</div>
<div class="row-fluid">
	<div class="span3">
		<fieldset><legend><?php echo secure::html(ucfirst($perso['name']),1); ?></legend>
			<img src="<?php echo secure::html($perso['img'],1); ?>" alt="avatar de <?php echo secure::html($perso['name'],1); ?>"  class="img-polaroid"  width="150" height="150"/><br><br>
			<?php if($perso['pnj']){echo '<span style="font-size:xx-large;">PNJ</span><br><br>';$titre='PNJ - '.$titre;} ?>
			<span style="font-size:large;">Niveau <b><span id="niv" style="font-style:italic;font-weight:bold;font-size:xx-large;"><?php echo get_niv($perso['xp']); ?></span></b></span><br>
			<span id="xp"><?php echo secure::html($perso['xp'],1); ?></span> points d'expérience<br>
			<span style="">Points de destin : <span id="PDest"  style="font-weight:bold;font-size:large;"><?php echo secure::html($perso['PDest'],1); ?></span></span><br>
			<span style="font-size:large;">Protection totale : <span style="font-weight:bold;font-size:xx-large;"><?php echo secure::html($total_protec,1); ?></span></span><br>
		</fieldset>
		<fieldset><legend>Richesses</legend>
			<span style="font-weight:bold;">Total : <span id="total_money" style="font-style:italic;font-weight:bold;font-size:large;">
			<?php echo get_money(array('PO'=>$perso['PO'],'PA'=>$perso['PA'],'PC'=>$perso['PC'],'LT'=>$perso['LT'], 'LB'=>$perso['LB']));?>
			</span> PO</span><br>
			<span id="PO"><?php echo secure::html($perso['PO'],1); ?></span> PO<br>
			<span id="PA"><?php echo secure::html($perso['PA'],1); ?></span> PA<br>
			<span id="PC"><?php echo secure::html($perso['PC'],1); ?></span> PC<br>
			<span id="LT"><?php echo secure::html($perso['LT'],1); ?></span> lingot(s) de Thritil<br>
			<span id="LB"><?php echo secure::html($perso['LB'],1); ?></span> lingot(s) de Berylium<br>
		</fieldset>
		<br>
		<b>Mis à jour <time class="date" data-date="<?php echo $perso['date']; ?>" title="Le <?php echo date_bdd($perso['date']); ?>"> Le <?php echo date_bdd($perso['date']); ?></time></b>
	</div>
	<div class="span9">
		<fieldset><legend>Caractéristiques</legend>
			<div class="row-fluid">
				<div class="span6">
					<table>
						<tr><td>Nom : </td>
						<td> <b><span id="name" style="font-size:x-large;font-style:italic;"><?php echo secure::html(ucfirst($perso['name']),1); ?></span></b></td></tr>
						<tr><td>Origine : </td>
						<td> <span id="origine" style="font-weight:bold;font-size:large;"><?php echo secure::html($perso['origine'],1); ?></span></td></tr>
						<tr><td>Energie vitale : </td>
						<td> &nbsp;&nbsp;<span id="evmax" style="font-weight:bold;"><?php echo secure::html($perso['evmax'],1); ?></span> points</td></tr>
					</table>
				</div>
				<div class="span6">
					<table>
						<tr><td>Sexe : </td>
						<td> <span id="sexe"><?php echo secure::html($perso['sexe'],1); ?></span></td></tr>
						<tr><td>Métier : </td>
						<td> <span id="metier" style="font-weight:bold;font-size:large;"><?php echo secure::html($perso['metier'],1); ?></span></td></tr>
						<tr><td>Energie astrale : </td>
						<td> &nbsp;&nbsp;<span id="eamax" style="font-weight:bold;"><?php echo secure::html($perso['eamax'],1); ?></span> points</td></tr>
					</table>
				</div>
			</div><div class="row-fluid">
				<div class="span6 center">
				<span style="font-weight:bold;font-size:large;">Attaque (AT) : <span id="AT" style="font-size:x-large;font-style:italic;"><?php echo secure::html($perso['AT'],1); ?></span></span>
				</div>
				<div class="span6 center">
				<span style="font-weight:bold;font-size:large;">Parade (PRD) : <span id="PRD" style="font-size:x-large;font-style:italic;"><?php echo secure::html($perso['PRD'],1); ?></span></span>
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span6">
					<table class="table table-bordered">
					<thead><tr>
						<th title="Courage" style="text-align:center;">COU</th>
						<th title="Intelligence" style="text-align:center;">INT</th>
						<th title="Charisme" style="text-align:center;">CHA</th>
						<th title="Adresse" style="text-align:center;">AD</th>
						<th title="Force" style="text-align:center;">FO</th>
					</tr></thead>
					<tbody><tr>
						<td id="COU" style="font-weight:bold;font-size:x-large;text-align:center;"><?php echo secure::html($perso['COU'],1); ?></td>
						<td id="INTL" style="font-weight:bold;font-size:x-large;text-align:center;"><?php echo secure::html($perso['INTL'],1); ?></td>
						<td id="CHA" style="font-weight:bold;font-size:x-large;text-align:center;"><?php echo secure::html($perso['CHA'],1); ?></td>
						<td id="AD" style="font-weight:bold;font-size:x-large;text-align:center;"><?php echo secure::html($perso['AD'],1); ?></td>
						<td id="FO" style="font-weight:bold;font-size:x-large;text-align:center;"><?php echo secure::html($perso['FO'],1); ?></td>
					</tr></tbody>
					</table>
				</div>
				<div class="span6">
					<?php if(in_array($perso['metier'],array('Prêtre','Paladin','Mage','Sorcier'))){ 
						$spec_array=array(	'Prêtre'=>array('name'=>'Dieu tutélaire','choix'=>'Adathie,Braav\',Caddyro,Chakhom,Crôm,Dlul,Fuhala,Khornettoh,Lafoune,Malgar,Mankdebol,Niourgl,Oboulos,Petipani,Picrate,Slanoush,Tzinntch,Vaarb,Youclidh,Yrfoul'),
											'Paladin'=>array('name'=>'Dieu tutélaire','choix'=>'Adathie,Braav\',Caddyro,Chakhom,Crôm,Dlul,Fuhala,Khornettoh,Lafoune,Malgar,Mankdebol,Niourgl,Oboulos,Petipani,Picrate,Slanoush,Tzinntch,Vaarb,Youclidh,Yrfoul'),
											'Mage'=>array('name'=>'Spécialisation','choix'=>'Mage Généraliste,Mage de Combat,Mage du Feu,Mage Invocateur,Mage de l\'Air,Mage de l\'Eau/Glace,Mage Thermodynamique,Mage Métamorphe,Sorcier Noir de Tzinntch,Mage de la Terre,Mage illusionniste,Nécromancien'),
											'Sorcier'=>array('name'=>'Spécialisation','choix'=>'Mage Généraliste,Mage de Combat,Mage du Feu,Mage Invocateur,Mage de l\'Air,Mage de l\'Eau/Glace,Mage Thermodynamique,Mage Métamorphe,Sorcier Noir de Tzinntch,Mage de la Terre,Mage illusionniste,Nécromancien')
											);
					?>
					<?php echo secure::html($spec_array[$perso['metier']]['name'],1); ?> : 
					<span id="specialisation" style="font-weight:bold;font-size:large;"><?php echo secure::html($perso['specialisation'],1); ?></span><br>
					<?php } ?>
					Résistance magique : <span style="font-weight:bold;"><?php echo round(($perso['INTL']+$perso['COU']+$perso['FO'])/3);?></span><br>
					<?php if($perso["eamax"]>0){ ?>
					Magie PSY. : <span style="font-weight:bold;"><?php echo round(($perso['INTL']+$perso['CHA'])/2);?></span><br>
					Magie PHYS. : <span style="font-weight:bold;"><?php echo round(($perso['INTL']+$perso['AD'])/2);?></span>
					<?php } ?>
				</div>

			</div>
		</fieldset>
		<fieldset class="modif hidden"><legend>Modification détaillée</legend>
			url de l'avatar (462 x 472 px): <span id="img" class="img-polaroid"><?php echo $perso['img']; ?></span> 
			<a class="btn" target="_blank" href="/membre/library"><i class="icon-film"></i> Bibliothèque</a><br><br>
			Choix de la fiche avancée :  <span id="adv_opt" class="img-polaroid"><?php echo $perso['adv_opt']; ?></span><br><br>
			Valeurs personnalisées : <div class="input-prepend input-append">
				<select id="custom_select">
					<option value="origine">Origine</option>
					<option value="metier">Métier</option>
					<option value="specialisation">Spécialisation</option>
				</select>
				<input type="text" id="custom_input"/>
				<a class="btn" id="custom_submit">Valider</a>
			</div><br>
			Ce personnage est un PNJ : <select id="pnj_select" class="input-small">
				<option value="0"<?php if(!$perso['pnj']){echo ' selected';} ?>>Faux</option>
				<option value="1"<?php if($perso['pnj']){echo ' selected';} ?>>Vrai</option>
			</select>
		</fieldset>
		<fieldset><legend>Compétences et coups spéciaux</legend>
			<table id="table_item_comp" class="table table-hover table-striped table-condensed table_item">
				<thead><tr><th style="max-width:100px;">Nom</th><th>Effets (ou description)</th><th class="modif hidden">Action</th></tr></thead>
				<tbody>
		<?php
			foreach($items as $key=>$item)
				{
				if($item['type']=="comp")
					{
					echo '
					<tr id="item_'.$item['id'].'">
					<td>
						<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$item['id'].'/'.to_url($item['name']).'?popin=popin" rel="nofollow">
							'.secure::html($item['name'],1).'
						</a> ';
				if($item['subtype']=="Coups spéciaux")
					{
					echo ' (coup spécial) ';
					}
				if($item['auteur_id']==1){echo '<span class="label label-inverse" title="Officiel"><i class="icon-book icon-white"></i></span>';}
				if(empty($item['effets'])){$item['effets']=$item['descr'];}
				echo'</td>
					<td><small>'.secure::html($item['effets'],1).'</small></td>
					<td class="modif hidden center">
						<a class="btn btn-mini btn-info" onclick="delete_item('.$item['id'].',perso_id);$(\'#item_'.$item['id'].'\').remove();"><i class="icon-remove icon-white"></i> Oublier</a>
					</td>
					</tr>
					';
					}
				}
		?>
				</tbody>
			</table>
		</fieldset>
		<?php if($droits){ ?>
		<div class="center">
			<form class="form-search form-inline" action="" method="post">
			  <div class="input-append">
				<input type="text" placeholder="Ajouter un objet" id="add_item_from_name" class="ajax-typeahead-search search-query">
				<a onclick="additemfromname(<?php echo $perso_id;?>);<?php echo'reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');'; ?>" class="btn btn-primary">Ajouter</a>
			  </div>
			</form>
		</div>
		<?php } ?>
	</div>
</div>
<div class="row-fluid">
	<div class="span6">
		<fieldset><legend>Armement</legend>
			<table id="table_item_armement" class="table table-hover table-striped table-condensed">
				<thead><tr><th>Nom</th><th>Type</th><th>PI</th><th>Rupture</th><th>Prix</th><th class="modif hidden">Action</th></tr></thead>
				<tbody>
		<?php
			$span=6;
			foreach($items as $key=>$item)
				{
				if($item['type']=="arme" AND $item['equip']>0 AND !($item['subtype']=="Flèches pour arc (jet)" OR $item['subtype']=="flèches" 
					OR $item['subtype']=="Carreaux arbalète (jet)"))
					{
					echo '
					<tr id="item_equip_'.$item['id'].'">
					<td>
						<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$item['id'].'/'.to_url($item['name']).'?popin=popin" rel="nofollow">
							'.secure::html($item['name'],1).'
						</a> ';
					if($item['auteur_id']==1){echo '<span class="label label-inverse" title="Officiel"><i class="icon-book icon-white"></i></span>';}
					echo'<br>
						<small>'.secure::html($item['effets'],1).'</small>
					</td>
					<td>'.secure::html($item['subtype'],1).'</td>
					<td>'.secure::html($item['carac'],1).'</td>
					<td>'.$rupture[$item['rupture']].'</td>
					<td>'.secure::html($item['prix'],1).' PO</td>
					<td class="modif hidden center">
						<a class="btn btn-mini" onclick="item_equip('.$perso_id.','.$item['id'].',\'desequip\');reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');">Déséquiper</a>
					</td>
					</tr>
					';
					}
				}
		?>
				</tbody>
			</table>
		</fieldset>
	</div>
	<div class="span6">
		<fieldset><legend>Protection</legend>
			<table id="table_item_protec" class="table table-hover table-striped table-condensed table_item">
				<thead><tr><th>Nom</th><th>Type</th><th>PR</th><th>Rupture</th><th>Prix</th><th class="modif hidden">Action</th></tr></thead>
				<tbody>
			<?php
				$span=6;
				foreach($items as $key=>$item)
				{
				if($item['type']=="protec" AND $item['equip']>0)
					{
					echo '
					<tr id="item_equip_'.$item['id'].'">
					<td>
						<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$item['id'].'/'.to_url($item['name']).'?popin=popin" rel="nofollow">
							'.secure::html($item['name'],1).'
						</a> ';
					if($item['auteur_id']==1){echo '<span class="label label-inverse" title="Officiel"><i class="icon-book icon-white"></i></span>';}
					echo'<br>
						<small>'.secure::html($item['effets'],1).'</small>
					</td>
					<td>'.secure::html($item['subtype'],1).'</td>
					<td>'.secure::html($item['carac'],1).'</td>
					<td>'.$rupture[$item['rupture']].'</td>
					<td>'.secure::html($item['prix'],1).' PO</td>
					<td class="modif hidden center">
						<a class="btn btn-mini" onclick="item_equip('.$perso_id.','.$item['id'].',\'desequip\');reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');">Déséquiper</a>
					</td>
					</tr>
					';
					}
				}
			?>
				</tbody>
			</table>
		</fieldset>
	</div>
</div>
<div class="row-fluid">
	<div class="span4">
		<fieldset><legend>Description</legend>
			<div id="descr_div">
				<span id="descr"><?php $descr_as=trim(preg_replace('#\n|\t|\r#','',$perso['descr']));if(!empty($descr_as)){echo secure::html($perso['descr']);} ?></span>
			</div>
		</fieldset>
	</div>
		<?php $count_span=4;$style='';//Objets spéciaux
			$span=4;$txt='';
			foreach($items as $key=>$item)
				{
				if(($item['type']=="divers" AND $item['emplacement']=="objets spéciaux")
						OR
						(in_array($item['subtype'],array("objets spéciaux","Objets exclusifs","Accessoires – Augmentation des carac.",
						"Matériel à usage magique",
						"Relique - Dlul","Relique - Adathie",
						"Relique - Kornettoh","Relique - Slanoush","Relique - Youclidh"))
						AND $item['emplacement']!="page principale"))
					{
					$txt.= '
					<tr id="item_'.$item['id'].'">
					<td id="item_qte_'.$item['id'].'">'.$item['qte'].'</td>
					<td class="modif hidden center">
						<span class="btn-group">
							<a class="btn btn-mini" onclick="if(parseInt($(\'#item_qte_'.$item['id'].'\').html())>1){qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);}"><i class="icon-minus"></i></a>
							<a class="btn btn-mini" onclick="qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);"><i class="icon-plus"></i></a>
							<a class="btn btn-mini btn-danger" onclick="delete_item('.$item['id'].',perso_id);$(\'#item_'.$item['id'].'\').remove();"><i class="icon-remove icon-white"></i></a>
						</span>
						<a class="btn btn-mini" onclick="item_equip('.$perso_id.','.$item['id'].',\'waste\');reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');">À revendre</a>
					</td>
					<td>
						<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$item['id'].'/'.to_url($item['name']).'?popin=popin" rel="nofollow">
							'.secure::html($item['name'],1).'
						</a> ';
				if($item['auteur_id']==1)
				{$txt.=  '<span class="label label-inverse" title="Officiel"><i class="icon-book icon-white"></i></span>';}
				if(empty($item['effets'])){$item['effets']=$item['descr'];}
				$txt.= '</td>
					<td><small>'.secure::html($item['effets'],1).'</small></td>
					</tr>
					';
					}
				}
if(!empty($txt)){
$count_span+=$span;
if($count_span>12){$count_span=$span;$style=' style="margin-left:0;" ';}else{$style='';}
echo'<div class="span4"'.$style.'>
		<fieldset><legend>Objets spéciaux</legend>
			<table id="table_item_speciaux" class="table table-hover table-striped table-condensed table_item">
				<thead><tr><th>Qté</th><th class="modif hidden">Action</th><th>Nom</th><th>Effets</th></tr></thead>
				<tbody>
					'.$txt.'
				</tbody>
			</table>
		</fieldset>
	</div>';}
		$span=4;$txt='';//BUTIN
			foreach($items as $key=>$item)
				{
				if($item['type']=="divers" AND ($item['emplacement']=="butin" OR ($item['subtype']=='pierre/gemme' AND $item['emplacement']!="page principale")))
					{
					$txt.= '
					<tr id="item_'.$item['id'].'">
					<td id="item_qte_'.$item['id'].'">'.$item['qte'].'</td>
					<td class="modif hidden center">
						<span class="btn-group">
							<a class="btn btn-mini" onclick="if(parseInt($(\'#item_qte_'.$item['id'].'\').html())>1){qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);}"><i class="icon-minus"></i></a>
							<a class="btn btn-mini" onclick="qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);"><i class="icon-plus"></i></a>
							<a class="btn btn-mini btn-danger" onclick="delete_item('.$item['id'].',perso_id);$(\'#item_'.$item['id'].'\').remove();"><i class="icon-remove icon-white"></i></a>
						</span>
						<a class="btn btn-mini" onclick="item_equip('.$perso_id.','.$item['id'].',\'waste\');reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');">À revendre</a>
					</td>
					<td>
						<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$item['id'].'/'.to_url($item['name']).'?popin=popin" rel="nofollow">
							'.secure::html($item['name'],1).'
						</a> ';
				if($item['auteur_id']==1){$txt.= '<span class="label label-inverse" title="Officiel"><i class="icon-book icon-white"></i></span>';}
				if(empty($item['effets'])){$item['effets']=$item['descr'];}
				$txt.='</td>
					<td>'.secure::html($item['prix'],1).' PO</td>
					</tr>
					';
					}
				}
if(!empty($txt)){
$count_span+=$span;
if($count_span>12){$count_span=$span;$style=' style="margin-left:0;" ';}else{$style='';}
echo'<div class="span4"'.$style.'>
		<fieldset><legend>Butin</legend>
			<table id="table_item_butin" class="table table-hover table-striped table-condensed table_item">
				<thead><tr><th>Qté</th><th class="modif hidden">Action</th><th>Nom</th><th>Prix</th></tr></thead>
				<tbody>
					'.$txt.'
				</tbody>
			</table>
		</fieldset>
	</div>';}
		$span=4;$txt='';// POTIONS
			foreach($items as $key=>$item)
				{
				if(($item['type']=="divers" AND  in_array($item['emplacement'],array("potions"))) 
						OR (in_array($item['subtype'],array("potions","Antidotes","potions","remèdes et produits de medecine","Potions – Augmentation des carac."))
						AND $item['emplacement']!="page principale" AND $item['emplacement']!="poisons"))
					{
					$txt.= '
					<tr id="item_'.$item['id'].'">
					<td id="item_qte_'.$item['id'].'">'.$item['qte'].'</td>
					<td class="modif hidden center">
						<span class="btn-group">
							<a class="btn btn-mini" onclick="if(parseInt($(\'#item_qte_'.$item['id'].'\').html())>1){qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);}"><i class="icon-minus"></i></a>
							<a class="btn btn-mini" onclick="qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);"><i class="icon-plus"></i></a>
							<a class="btn btn-mini btn-danger" onclick="delete_item('.$item['id'].',perso_id);$(\'#item_'.$item['id'].'\').remove();"><i class="icon-remove icon-white"></i></a>
						</span>
						<a class="btn btn-mini" onclick="item_equip('.$perso_id.','.$item['id'].',\'waste\');reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');">À revendre</a>
					</td>
					<td>
						<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$item['id'].'/'.to_url($item['name']).'?popin=popin" rel="nofollow">
							'.secure::html($item['name'],1).'
						</a> ';
				if($item['auteur_id']==1){$txt.= '<span class="label label-inverse" title="Officiel"><i class="icon-book icon-white"></i></span>';}
				if(empty($item['effets'])){$item['effets']=$item['descr'];}
				$txt.='</td>
					<td>'.secure::html($item['effets'],1).'</td>
					</tr>
					';
					}
				}
if(!empty($txt)){
$count_span+=$span;
if($count_span>12){$count_span=$span;$style=' style="margin-left:0;" ';}else{$style='';}
echo'<div class="span4"'.$style.'>
		<fieldset><legend>Potions</legend>
			<table id="table_item_potions" class="table table-hover table-striped table-condensed table_item">
				<thead><tr><th>Qté</th><th class="modif hidden">Action</th><th>Nom</th><th>Effets</th></tr></thead>
				<tbody>
					'.$txt.'
				</tbody>
			</table>
		</fieldset>
	</div>';}
		$span=4;$txt='';// POISONS
			foreach($items as $key=>$item)
				{
				if(($item['type']=="divers" AND  in_array($item['emplacement'],array("poisons"))) 
									OR ( in_array($item['subtype'],array("poisons")) AND $item['emplacement']!="page principale" AND $item['emplacement']!="potions"))
					{
					$txt.= '
					<tr id="item_'.$item['id'].'">
					<td id="item_qte_'.$item['id'].'">'.$item['qte'].'</td>
					<td class="modif hidden center">
						<span class="btn-group">
							<a class="btn btn-mini" onclick="if(parseInt($(\'#item_qte_'.$item['id'].'\').html())>1){qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);}"><i class="icon-minus"></i></a>
							<a class="btn btn-mini" onclick="qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);"><i class="icon-plus"></i></a>
							<a class="btn btn-mini btn-danger" onclick="delete_item('.$item['id'].',perso_id);$(\'#item_'.$item['id'].'\').remove();"><i class="icon-remove icon-white"></i></a>
						</span>
						<a class="btn btn-mini" onclick="item_equip('.$perso_id.','.$item['id'].',\'waste\');reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');">À revendre</a>
					</td>
					<td>
						<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$item['id'].'/'.to_url($item['name']).'?popin=popin" rel="nofollow">
							'.secure::html($item['name'],1).'
						</a> ';
				if($item['auteur_id']==1){$txt.= '<span class="label label-inverse" title="Officiel"><i class="icon-book icon-white"></i></span>';}
				if(empty($item['effets'])){$item['effets']=$item['descr'];}
				$txt.='</td>
					<td>'.secure::html($item['effets'],1).'</td>
					</tr>
					';
					}
				}
if(!empty($txt)){
$count_span+=$span;
if($count_span>12){$count_span=$span;$style=' style="margin-left:0;" ';}else{$style='';}
echo'<div class="span4"'.$style.'>
		<fieldset><legend>Poisons</legend>
			<table id="table_item_poisons" class="table table-hover table-striped table-condensed table_item">
				<thead><tr><th>Qté</th><th class="modif hidden">Action</th><th>Nom</th><th>Effets</th></tr></thead>
				<tbody>
					'.$txt.'
				</tbody>
			</table>
		</fieldset>
	</div>';}
		$span=4;$txt='';// NOURRITURE
			foreach($items as $key=>$item)
				{
				if(($item['type']=="divers" AND $item['emplacement']=="bouffe et boisson")
						OR ($item['subtype']=="bouffe et boisson" AND $item['emplacement']!="page principale"))
					{
					$txt.= '
					<tr id="item_'.$item['id'].'">
					<td id="item_qte_'.$item['id'].'">'.$item['qte'].'</td>
					<td class="modif hidden center">
						<span class="btn-group">
							<a class="btn btn-mini" onclick="if(parseInt($(\'#item_qte_'.$item['id'].'\').html())>1){qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);}"><i class="icon-minus"></i></a>
							<a class="btn btn-mini" onclick="qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);"><i class="icon-plus"></i></a>
							<a class="btn btn-mini btn-danger" onclick="delete_item('.$item['id'].',perso_id);$(\'#item_'.$item['id'].'\').remove();"><i class="icon-remove icon-white"></i></a>
						</span>
						<a class="btn btn-mini" onclick="item_equip('.$perso_id.','.$item['id'].',\'waste\');reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');">À revendre</a>
					</td>
					<td>
						<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$item['id'].'/'.to_url($item['name']).'?popin=popin" rel="nofollow">
							'.secure::html($item['name'],1).'
						</a> ';
				if($item['auteur_id']==1){$txt.= '<span class="label label-inverse" title="Officiel"><i class="icon-book icon-white"></i></span>';}
				$txt.='</td>
					</tr>
					';
					}
				}
if(!empty($txt)){
$count_span+=$span;
if($count_span>12){$count_span=$span;$style=' style="margin-left:0;" ';}else{$style='';}
echo'<div class="span4"'.$style.'>
		<fieldset><legend>Bouffe et boisson</legend>
			<table id="table_item_bouffe" class="table table-hover table-striped table-condensed table_item">
				<thead><tr><th>Qté</th><th class="modif hidden">Action</th><th>Nom</th></tr></thead>
				<tbody>
					'.$txt.'
				</tbody>
			</table>
		</fieldset>
	</div>';}
		$span=8;$txt='';// INGREDIENTS
			foreach($items as $key=>$item)
				{
				if(($item['type']=="divers" AND $item['emplacement']=="ingrédients magiques")
					OR(in_array($item['type'],array("Matériel à usage magique","ingrédients magiques")) AND $item['emplacement']!="page principale"))
					{
					$txt.= '
					<tr id="item_'.$item['id'].'">
					<td id="item_qte_'.$item['id'].'">'.$item['qte'].'</td>
					<td class="modif hidden center">
						<span class="btn-group">
							<a class="btn btn-mini" onclick="if(parseInt($(\'#item_qte_'.$item['id'].'\').html())>1){qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);}"><i class="icon-minus"></i></a>
							<a class="btn btn-mini" onclick="qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);"><i class="icon-plus"></i></a>
							<a class="btn btn-mini btn-danger" onclick="delete_item('.$item['id'].',perso_id);$(\'#item_'.$item['id'].'\').remove();"><i class="icon-remove icon-white"></i></a>
						</span>
						<a class="btn btn-mini" onclick="item_equip('.$perso_id.','.$item['id'].',\'waste\');reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');">À revendre</a>
					</td>
					<td>
						<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$item['id'].'/'.to_url($item['name']).'?popin=popin" rel="nofollow">
							'.secure::html($item['name'],1).'
						</a> ';
				if($item['auteur_id']==1){$txt.= '<span class="label label-inverse" title="Officiel"><i class="icon-book icon-white"></i></span>';}
				if(empty($item['effets'])){$item['effets']=$item['descr'];}
				$txt.='</td>
					<td>'.secure::html($item['prix'],1).' PO</td>
					</tr>
					';
					}
				}
if(!empty($txt)){
$count_span+=$span;
if($count_span>12){$count_span=$span;$style=' style="margin-left:0;" ';}else{$style='';}
echo'<div class="span8"'.$style.'>
		<fieldset><legend>Ingrédients magiques</legend>
			<table id="table_item_ingrédients" class="table table-hover table-striped table-condensed table_item">
				<thead><tr><th>Qté</th><th class="modif hidden">Action</th><th>Nom</th><th>Effets</th></tr></thead>
				<tbody>
					'.$txt.'
				</tbody>
			</table>
		</fieldset>
	</div>';}
		$span=4;$txt='';// BOUQUINS
			foreach($items as $key=>$item)
				{
				if(($item['type']=="divers" AND $item['emplacement']=="bouquins")
						OR
						(in_array($item['subtype'],array("bouquins","Livres pour mages","Livres pour prêtres/paladins","Livres généraux",
						"Livres généraux – compétences")) AND $item['emplacement']!="page principale"))
					{
					$txt.= '
					<tr id="item_'.$item['id'].'">
					<td id="item_qte_'.$item['id'].'">'.$item['qte'].'</td>
					<td class="modif hidden center">
						<span class="btn-group">
							<a class="btn btn-mini" onclick="if(parseInt($(\'#item_qte_'.$item['id'].'\').html())>1){qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);}"><i class="icon-minus"></i></a>
							<a class="btn btn-mini" onclick="qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);"><i class="icon-plus"></i></a>
							<a class="btn btn-mini btn-danger" onclick="delete_item('.$item['id'].',perso_id);$(\'#item_'.$item['id'].'\').remove();"><i class="icon-remove icon-white"></i></a>
						</span>
						<a class="btn btn-mini" onclick="item_equip('.$perso_id.','.$item['id'].',\'waste\');reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');">À revendre</a>
					</td>
					<td>
						<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$item['id'].'/'.to_url($item['name']).'?popin=popin" rel="nofollow">
							'.secure::html($item['name'],1).'
						</a> ';
				if($item['auteur_id']==1){$txt.= '<span class="label label-inverse" title="Officiel"><i class="icon-book icon-white"></i></span>';}
				if(empty($item['effets'])){$item['effets']=$item['descr'];}
				$txt.='</td>
					<td>'.secure::html($item['prix'],1).' PO</td>
					</tr>
					';
					}
				}
if(!empty($txt)){
$count_span+=$span;
if($count_span>12){$count_span=$span;$style=' style="margin-left:0;" ';}else{$style='';}
echo'<div class="span4"'.$style.'>
		<fieldset><legend>Bouquins</legend>
			<table id="table_item_bouquins" class="table table-hover table-striped table-condensed table_item">
				<thead><tr><th>Qté</th><th class="modif hidden">Action</th><th>Nom</th><th>Prix</th></tr></thead>
				<tbody>
					'.$txt.'
				</tbody>
			</table>
		</fieldset>
	</div>';}
		$span=6;$txt='';// FLECHES
			foreach($items as $key=>$item)
				{
				if($item['type']=="arme" AND $item['qte']>0 AND((
					($item['subtype']=="Flèches pour arc (jet)" OR $item['subtype']=="flèches" OR $item['subtype']=="Carreaux arbalète (jet)") 
					AND $item['emplacement']!="page principale") OR $item['emplacement']=="flèches"))
					{
					$txt.= '
					<tr id="item_'.$item['id'].'">
					<td id="item_qte_'.$item['id'].'">'.$item['qte'].'</td>
					<td class="modif hidden center">
						<span class="btn-group">
							<a class="btn btn-mini" onclick="if(parseInt($(\'#item_qte_'.$item['id'].'\').html())>1){qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);}"><i class="icon-minus"></i></a>
							<a class="btn btn-mini" onclick="qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);"><i class="icon-plus"></i></a>
							<a class="btn btn-mini btn-danger" onclick="delete_item('.$item['id'].',perso_id);$(\'#item_'.$item['id'].'\').remove();"><i class="icon-remove icon-white"></i></a>
						</span>
						<a class="btn btn-mini" onclick="item_equip('.$perso_id.','.$item['id'].',\'waste\');reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');">À revendre</a>
					</td>
					<td>
						<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$item['id'].'/'.to_url($item['name']).'?popin=popin" rel="nofollow">
							'.secure::html($item['name'],1).'
						</a> ';
				if($item['auteur_id']==1){$txt.= '<span class="label label-inverse" title="Officiel"><i class="icon-book icon-white"></i></span>';}
				if(empty($item['effets'])){$item['effets']=$item['descr'];}
				$txt.='</td>
					<td>'.secure::html($item['effets']).'</td>
					</tr>
					';
					}
				}
if(!empty($txt)){
$count_span+=$span;
if($count_span>12){$count_span=$span;$style=' style="margin-left:0;" ';}else{$style='';}
echo'<div class="span6"'.$style.'>
		<fieldset><legend>Flèches</legend>
			<table id="table_item_flèches" class="table table-hover table-striped table-condensed table_item">
				<thead><tr><th>Qté</th><th class="modif hidden">Action</th><th>Nom</th><th>Effets</th></tr></thead>
				<tbody>
					'.$txt.'
				</tbody>
			</table>
		</fieldset>
	</div>';}
		$span=6;$txt='';// BAGUES
			foreach($items as $key=>$item)
				{
				if(($item['type']=="divers" AND $item['emplacement']=="bagues" AND $item['qte']>0) 
					OR
					(in_array($item['subtype'],array("Bagues de puissance (prêtre)","bagues",
					"Médaillons d'économie (prêtre)","Bagues de Sûreté (prêtre)",
					"Bagues de puissance (sorcier)","Bagues d'économie (sorcier)",
					"Bagues de Sûreté (sorcier)")) AND $item['emplacement']!="page principale" AND $item['qte']>0)
					)
					{
					$txt.= '
					<tr id="item_'.$item['id'].'">
					<td id="item_qte_'.$item['id'].'">'.$item['qte'].'</td>
					<td class="modif hidden center">
						<span class="btn-group">
							<a class="btn btn-mini" onclick="if(parseInt($(\'#item_qte_'.$item['id'].'\').html())>1){qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);}"><i class="icon-minus"></i></a>
							<a class="btn btn-mini" onclick="qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);"><i class="icon-plus"></i></a>
							<a class="btn btn-mini btn-danger" onclick="delete_item('.$item['id'].',perso_id);$(\'#item_'.$item['id'].'\').remove();"><i class="icon-remove icon-white"></i></a>
						</span>
						<a class="btn btn-mini" onclick="item_equip('.$perso_id.','.$item['id'].',\'waste\');reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');">À revendre</a>
					</td>
					<td>
						<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$item['id'].'/'.to_url($item['name']).'?popin=popin" rel="nofollow">
							'.secure::html($item['name'],1).'
						</a> ';
				if($item['auteur_id']==1){$txt.= '<span class="label label-inverse" title="Officiel"><i class="icon-book icon-white"></i></span>';}
				if(empty($item['effets'])){$item['effets']=$item['descr'];}
				$txt.='</td>
					<td>'.secure::html($item['prix'],1).' PO</td>
					</tr>
					';
					}
				}
if(!empty($txt)){
$count_span+=$span;
if($count_span>12){$count_span=$span;$style=' style="margin-left:0;" ';}else{$style='';}
echo'<div class="span6"'.$style.'>
		<fieldset><legend>Bagues</legend>
			<table id="table_item_bagues" class="table table-hover table-striped table-condensed table_item">
				<thead><tr><th>Qté</th><th class="modif hidden">Action</th><th>Nom</th><th>Effets</th></tr></thead>
				<tbody>
					'.$txt.'
				</tbody>
			</table>
		</fieldset>
	</div>';}
	?>
</div>
<div class="row-fluid">
	<div class="span12">
		<fieldset><legend>Equipements et objets divers</legend>
			<table id="table_item_main" class="table jtable table-hover table-striped table-condensed table_item">
				<thead><tr><th>Nom</th><th>Qté</th><th class="modif hidden">Action</th><th>Type</th><th>Prix</th></tr></thead>
				<tbody>
		<?php
			$array_type=array('comp'=>'Compétence','arme'=>'Armement','protec'=>'Protection','divers'=>'Divers','sort'=>'Sorts','prodige'=>'Prodiges');
			foreach($items as $key=>$item)
				{
				if(($item['type']!="comp" AND $item['type']!="sorts" AND $item['etat']!="delete") AND $item['qte']>0
				AND (in_array($item['emplacement'],array('page principale','default'))
				AND !($item['subtype']=="Flèches pour arc (jet)" OR $item['subtype']=="flèches" OR $item['subtype']=="Carreaux arbalète (jet)")
				AND $item['subtype']!="bouffe et boisson" 
				AND !in_array($item['subtype'],array("potions","Antidotes","potions","remèdes et produits de medecine","Potions – Augmentation des carac."))
				AND !in_array($item['subtype'],array("poisons"))
				AND !in_array($item['subtype'],array("objets spéciaux","Objets exclusifs","Accessoires – Augmentation des carac.","Matériel à usage magique",
									"Relique - Dlul","Relique - Adathie",
									"Relique - Kornettoh","Relique - Slanoush","Relique - Youclidh","bouffe et boisson"))
				AND !in_array($item['subtype'],array("bouquins","Livres pour mages","Livres pour prêtres/paladins","Livres généraux",
										"Livres généraux – compétences"))
				AND !in_array($item['subtype'],array("Bagues de puissance (prêtre)","bagues",
									"Médaillons d\'économie (prêtre)",
									"Bagues de Sûreté (prêtre)",
									"Bagues de puissance (sorcier)",
									"Bagues d\'économie (sorcier)",
									"Bagues de Sûreté (sorcier)"))
				AND !in_array($item['subtype'],array("objets spéciaux","Objets exclusifs","Accessoires – Augmentation des carac.","Matériel à usage magique",
									"Relique - Dlul","Relique - Adathie",
									"Relique - Kornettoh","Relique - Slanoush","Relique - Youclidh","bouffe et boisson"))
				))
					{
					// if($item['equip']=='equip' AND ($item['type']=='arme' OR $item['type']=='protec')){$qte=$item['qte']-1;}else{$qte=$item['qte'];}
					echo '
					<tr id="item_'.$item['id'].'">
					<td>
						<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$item['id'].'/'.to_url($item['name']).'?popin=popin" rel="nofollow">
							'.secure::html($item['name'],1).'
						</a> ';
				if($item['auteur_id']==1){echo '<span class="label label-inverse" title="Officiel"><i class="icon-book icon-white"></i></span>';}
				if(empty($item['effets'])){$item['effets']=$item['descr'];}
				echo'</td>
					<td id="item_qte_'.$item['id'].'">'.$item['qte'].'</td>
					<td class="modif hidden center">
						<span class="btn-group">
							<a class="btn btn-mini" onclick="if(parseInt($(\'#item_qte_'.$item['id'].'\').html())>1){qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())-1);}"><i class="icon-minus"></i></a>
							<a class="btn btn-mini" onclick="qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);$(\'#item_qte_'.$item['id'].'\').html(parseInt($(\'#item_qte_'.$item['id'].'\').html())+1);"><i class="icon-plus"></i></a>
							<a class="btn btn-mini btn-danger" onclick="delete_item('.$item['id'].',perso_id);$(\'#item_'.$item['id'].'\').remove();reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');"><i class="icon-remove icon-white"></i></a>
						</span><span class="btn-group">';
				if(($item['type']=='arme' OR $item['type']=='protec') AND $item['equip']==0){echo '<a class="btn btn-mini btn-info" onclick="item_equip('.$perso_id.','.$item['id'].',\'equip\');reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');">Équiper</a>';}
				
				echo'<a class="btn btn-mini" onclick="item_equip('.$perso_id.','.$item['id'].',\'waste\');reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');">À revendre</a>
					</span>
					</td>
					<td>'.secure::html($item['subtype'].' ('.$array_type[$item['type']].')',1).'</td>
					<td>'.secure::html($item['prix'],1).' PO</td>
					</tr>
					';
					}
				}
		?>
				</tbody>
			</table>
		</fieldset>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<fieldset><legend>Éléments diversement inutiles à vendre dans la plus proche boutique</legend>
			<table id="table_item_main" class="table table-hover table-striped table-condensed table_item">
				<thead><tr><th>Nom</th><th>Qté</th><th class="modif hidden">Action</th><th>Type</th><th>Prix</th></tr></thead>
				<tbody>
		<?php
			$array_type=array('comp'=>'Compétence','arme'=>'Armement','protec'=>'Protection','divers'=>'Divers','sort'=>'Sorts','prodige'=>'Prodiges');
			foreach($items as $key=>$item)
				{
				if($item['etat']!="delete" AND $item['waste']>0)
					{
					echo '
					<tr id="item_waste_'.$item['id'].'">
					<td>
						<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$item['id'].'/'.to_url($item['name']).'?popin=popin" rel="nofollow">
							'.secure::html($item['name'],1).'
						</a> ';
				if($item['auteur_id']==1){echo '<span class="label label-inverse" title="Officiel"><i class="icon-book icon-white"></i></span>';}
				if(empty($item['effets'])){$item['effets']=$item['descr'];}
				echo'</td>
					<td id="item_waste_qte_'.$item['id'].'">'.$item['waste'].'</td>
					<td class="modif hidden center">
						<span class="btn-group">
							<a class="btn btn-mini" onclick="if(parseInt($(\'#item_waste_qte_'.$item['id'].'\').html())>1){qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_waste_qte_'.$item['id'].'\').html())-1,\'waste\');$(\'#item_waste_qte_'.$item['id'].'\').html(parseInt($(\'#item_waste_qte_'.$item['id'].'\').html())-1);}"><i class="icon-minus"></i></a>
							<a class="btn btn-mini" onclick="qte_item('.$item['id'].','.$_GET['id'].',parseInt($(\'#item_waste_qte_'.$item['id'].'\').html())+1,\'waste\');$(\'#item_waste_qte_'.$item['id'].'\').html(parseInt($(\'#item_waste_qte_'.$item['id'].'\').html())+1);"><i class="icon-plus"></i></a>
							<a class="btn btn-mini btn-danger" onclick="delete_item('.$item['id'].','.$_GET['id'].',\'waste\');$(\'#item_waste_'.$item['id'].'\').remove();"><i class="icon-remove icon-white"></i></a>
						</span>
					<a class="btn btn-mini" onclick="item_equip('.$perso_id.','.$item['id'].',\'qte\');reload_content(\'/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true\');">Sauver</a>
					</td>
					<td>'.secure::html($item['subtype'].' ('.$array_type[$item['type']].')',1).'</td>
					<td>'.secure::html($item['prix'],1).' PO</td>
					</tr>
					';
					}
				}
		?>
				</tbody>
			</table>
		</fieldset>
	</div>
</div>
<div class="center">
	Code forum : <div class="input-append">
		<input type="text" value="[img]http://donjonfacile.fr/fiche-<?php echo $perso['id'].'-'.to_url($perso['name']);?>.jpg[/img][img]http://donjonfacile.fr/fiche-advanced-<?php echo $perso['id'].'-'.to_url($perso['name']);?>.jpg[/img]"/>
		<a class="btn" id="copy_bbcode" title="Copier dans le presse-papier"><i class="icon-inbox"></i> Copier</a>
	</div>
	<script>
<?php echo '$(document).ready(function(){
			$("#copy_bbcode").zclip({
			copy:\'[img]http://donjonfacile.fr/fiche-'.$_GET['id'].'-'.to_url($perso['name']).'.jpg[/img][img]http://donjonfacile.fr/fiche-advanced-'.$_GET['id'].'-'.to_url($perso['name']).'.jpg[/img]\',
			afterCopy:function(){$.pnotify({text:\'Le lien a été copié dans votre presse-papier\',title:\'Texte copié\',type:\'success\'});;}
		});
	});';?>
	</script>
</div>
<div class="hidden" id="time_div"><?php echo strtotime($perso['date']); ?></div>
<script>
	var $time=<?php echo strtotime($perso['date']); ?>;
	function check_perso()
		{
		// $time=$('#time_div').html();
		$.ajax
			({
			type : "POST", // envoi des données en GET ou POST
			url : '/ajax/ajax_perso_update.php' , // url du fichier de traitement
			data: "perso_id=<?php echo $perso_id; ?>",
			dataType:'json',
			success : function(data)
				{
				if($time<data && $('#perso_update_ad').length ==0)
					{
					$time=data;$('#time_div').html(data);
					$('#contenu_global').prepend('<div style="position:fixed;top:150px;right:20px;z-index:10000000000000000;" class="well center">Votre personnage a été mis à jour.<br><div class="center"><a class="btn btn-primary" onclick="reload_content(\'<?php echo '/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true';?>\');">Actualiser</a><a class="btn" onclick="$(this).parent().parent().remove();">Fermer</a></div></div>');
					console.log('màj perso !');
					}
				else{console.log('perso à jour');}
				},
			error : function(jqXHR, textStatus, errorThrown)
				{
				console.log(textStatus+' : '+errorThrown);
				}
			});
		}
	function delete_time()
		{
		$('.update_perso_val').remove();
		}
	$(document).ready(function(){
		setInterval('check_perso()',60000);
		setInterval('delete_time()',1000);
		$("#specialisation").editInPlace({
			// callback: function(unused, enteredText) { return enteredText; },
			url: "/ajax/ajax_perso_edit.php",
			field_type: "select",
			value_required: true,
			select_text:"nouveau choix",
			select_options: "<?php if(isset($spec_array)){echo $spec_array[$perso['metier']]['choix'];} ?>",
			params: "perso_id=<?php echo $perso_id; ?>",
			success:function(){$.pnotify({text: 'Spécialisation mise à jour !',type:'success'});}
		});
		/* $('table.table_item > tbody').each(function()
			{
			if($(this).children().length ==0)
				{
				$(this).parent().parent().parent().remove();//.addClass('hidden');//remonte jusqu'au span
				}
			});
		if($('#table_item_comp > tbody').children().length ==0)
				{
				$('#table_item_comp').parent().remove();// remonte jusqu'au fieldset seulement (le div contient le reste des datas)
				} */
		$('#custom_submit').click(function() {$.ajax
		({
		type : "POST", // envoi des données en GET ou POST
		url : '/ajax/ajax_perso_edit.php' , // url du fichier de traitement
		data: 'element_id='+$('#custom_select').val()+'&update_value='+$('#custom_input').val()+'&perso_id=<?php echo $perso_id; ?>&original_html=',
		success : function(data)
			{
			console.log(data);
			$.pnotify
				({
				text: 'Modification effectuée',
				type:'success'
				});
			reload_content('<?php echo '/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true';?>');
			},
		error : function(jqXHR, textStatus, errorThrown)
			{
			console.log(textStatus+' : '+errorThrown);
			$.pnotify
				({
				text:"Le serveur est indisponible.",
				title:"Erreur de connexion",
				type:"error"
				});
			}
		});
		});
		$('#pnj_select').change(function() {$.ajax
		({
		type : "POST", // envoi des données en GET ou POST
		url : '/ajax/ajax_perso_edit.php' , // url du fichier de traitement
		data: 'element_id=pnj&update_value='+$('#pnj_select').val()+'&perso_id=<?php echo $perso_id; ?>&original_html=',
		success : function(data)
			{
			console.log(data);
			$.pnotify
				({
				text: 'Modification effectuée',
				type:'success'
				});
			reload_content('<?php echo '/perso/'.$perso_id.'/'.to_url($perso['name']).'?ajax=true';?>');
			},
		error : function(jqXHR, textStatus, errorThrown)
			{
			console.log(textStatus+' : '+errorThrown);
			$.pnotify
				({
				text:"Le serveur est indisponible.",
				title:"Erreur de connexion",
				type:"error"
				});
			}
		});
		});
	});
</script>
<?php
if(isset($_GET['ajax']))
	{
	echo '	<script type="text/javascript" src="/ressources/js/'.@filemtime('ressources/js/main.js').'-main.js" >
			</script><script type="text/javascript" >var perso_id='.$_GET['id'].';var img_old="'.$perso['img'].'";</script>
			<script type="text/javascript" src="/ressources/js/'.@filemtime('ressources/js/perso_edit.js').'-perso_edit.js" ></script>
			<script>perso_toggle();</script>';
	exit;
	}
 }

			}
		}
}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////        SUPPRESSION D'UN PERSO     //////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
elseif($mode=='delete')
	{
	$titre='suppression d\'un personnage';
	if(isset($_SESSION['user_id']))
		{
		if(isset($_GET['id']))
			{
			$val=$bdd->query('SELECT * FROM perso WHERE id='.$_GET['id'])->fetch();
			if($_SESSION['user_id']==$val['user_id'] OR isadmin())
				{
				if(isset($_POST['delete']))
					{
					$bdd->exec('UPDATE perso SET etat="delete" WHERE id='.$_GET['id']);
					$bdd->exec('UPDATE fav SET etat="delete" WHERE type="perso" AND fav_id='.$_GET['id']);
					$_SESSION['success'].=secure::html($val['name']).' supprimé !';
					$_SESSION['update_perso_timer']=time()+100000*60;
					header('location:/perso');
					}
				else{
					$titre='suppression de '.secure::html($val['name'],1);
					echo'<div class="code">
					Êtes-vous certain(e) de vouloir supprimer ce personnage ('.secure::html($val['name'],1).') ?<br/>
					<form action="'.$_SERVER['REQUEST_URI'].'" method="post" class="button-group" name="delete_perso">
					<input type="hidden" name="delete" value="1">
					<span class="btn-group">
					<a class="btn btn-danger" onclick="document.forms.delete_perso.submit()"><i class="icon-ok"></i> Oui</a>
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
else{include('pages/404.php');$error=1;}