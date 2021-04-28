<?php
$titre='membre';
?>
<div class="actions btn-toolbar center">
	<?php if(isset($_SESSION['user_id']) AND (empty($_GET['id']) OR $_SESSION['user_id']==$_GET['id']) AND isset($_SESSION['user'])){echo '
		<div class="btn-group">
			<a class="btn" href="/membre/param"><i class="icon-cog"></i> paramètres</a>
			<a class="btn" href="/membre/edit_profil"><i class="icon-pencil"></i> modifier mon profil</a>
			<a class="btn" href="/membre/library"><i class="icon-film"></i> Bibliothèque</a>
		</div>';
		} 
		if(!empty($_GET['id'])){$pseudo=get_pseudo($_GET['id']);}
		 ?> 
	<div class="btn-group">
		<a class="btn" href="/membre<?php if(!empty($_GET['id'])){echo '/'.$_GET['id'].'/'.to_url($pseudo);} ?>"><i class="icon-user"></i> profil</a>
		<a class="btn" href="/membre/persos<?php if(!empty($_GET['id'])){echo '/'.$_GET['id'].'/'.to_url($pseudo);} ?>"><i class="icon-th-list"></i> personnages</a>
		<a class="btn" href="/membre/list"><i class="icon-list"></i> liste des membres</a>
	</div>
	<?php if(isset($_SESSION['user_id']) AND !empty($_GET['id']) AND $_SESSION['user_id']!=$_GET['id'])
				{echo '	<a class="btn" href="/messagerie?username='.$pseudo.'"><i class="icon-envelope"></i> Envoyer un message</a>
						<a class="btn" href="/membre"><i class="icon-share-alt"></i> retour à mon profil</a>
						';}
	?>
</div><br/>
<?php 
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* /////////////////////////          PARAMETRES D'UN MEMBRE          //////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
if($mode=='param')
	{
	if(!isset($_SESSION['user'])){echo ACCES_REFUSE_INVITE;$header='<meta name="robots" content="noindex,follow" />';}
	else{
	$titre='paramètres du compte de '.$_SESSION['pseudo'];$fil['profil']='membre';
	if(!empty($_POST['mdpmain']) AND check_mdp($_SESSION['pseudo'],$_POST['mdpmain'],$_SESSION['user_id']))
	{
	if(isset($_POST['compte_delete']))
		{
		$req = $bdd->prepare('UPDATE users SET etat = :opt WHERE ID = :id');
			$req->execute(array('id' => $_SESSION['user_id'],
								'opt' => 'delete'
								));
		$_SESSION['success'].='Compte supprimé<br/>';
		logout();
		header('Location:/');
		}
	if(!empty($_POST['mail']))
		{
		if($_POST['mail']==$_POST['mail_old']){}
		else if(filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
			{
			$req=$bdd->prepare('SELECT mail,avatar,option_gravatar FROM users WHERE id = :id');
			$req->execute(array('id' => $_SESSION['user_id']));
			$data=$req->fetch();
			if($data['option_gravatar']==1){$avatar=gravatar($_POST['mail']);}else{$avatar=$data['avatar'];}
			$mail=$_POST['mail'];
			$req = $bdd->prepare('UPDATE users SET mail = :mdp,avatar = :avatar WHERE ID = :id');
			$req->execute(array('id' => $_SESSION['user_id'],'mdp' => $_POST['mail'],'avatar'=>$avatar));
			$_SESSION['success'].='L\' email a été mis à jour.<br/>';
			}
		else{$_SESSION['err'].='Le mail entré n\'est pas valide.<br/>';}
		}
	if(isset($_POST['option_mail_old']) AND ((isset($_POST['option_mail']) AND $_POST['option_mail_old']==0) OR (!isset($_POST['option_mail'])AND $_POST['option_mail_old']==1)))
		{
		
		if(isset($_POST['option_mail'])){$opt=1;}else{$opt=0;}
		$req = $bdd->prepare('UPDATE users SET option_mail = :opt WHERE ID = :id');
			$req->execute(array('id' => $_SESSION['user_id'],
								'opt' => $opt
								));
		$_SESSION['success'].='option de l\'affichage du mail modifiée.<br/>';
		}
	if(isset($_POST['pseudo_old']) AND !empty($_POST['pseudo']) AND $_POST['pseudo_old']!=$_POST['pseudo'])
		{
		$req=$bdd->prepare('SELECT * FROM users WHERE etat!="delete" AND pseudo = :pseudo');
		$req->execute(array('pseudo' => $_POST['pseudo']));
		if($req->rowCount() == 0)
			{
			$req = $bdd->prepare('UPDATE users SET pseudo = :pseudo,mdp=:mdp WHERE ID = :id');
			$req->execute(array('id' => $_SESSION['user_id'],
								'mdp' => sha1(md5('salt').md5($_POST['mdpmain'])),
								'pseudo' => $_POST['pseudo']
								));
			$_SESSION['success'].='Vous vous appelez désormais "'.$_POST['pseudo'].'".<br/>';
			$_SESSION['cache_time']=time();
			$_SESSION['pseudo']=$_POST['pseudo'];
			}
		else{$_SESSION['err'].='Ce pseudo est déjà utilisé !';}
		}
	if(isset($_POST['option_newsletter_old']) AND ((isset($_POST['option_newsletter']) AND $_POST['option_newsletter_old']==0) OR (!isset($_POST['option_newsletter'])AND $_POST['option_newsletter_old']==1)))
		{
		if(isset($_POST['option_newsletter'])){$opt=1;}else{$opt=0;}
		$req = $bdd->prepare('UPDATE users SET option_newsletter = :opt WHERE ID = :id');
			$req->execute(array('id' => $_SESSION['user_id'],
								'opt' => $opt
								));
		$_SESSION['success'].='option d\' abonnement aux newsletter modifiée.<br/>';
		}
	if(isset($_POST['option_online_old']) AND ((isset($_POST['option_online']) AND $_POST['option_online_old']==0) OR (!isset($_POST['option_online'])AND $_POST['option_online_old']==1)))
		{
		if(isset($_POST['option_online'])){$opt=1;}else{$opt=0;}
		$req = $bdd->prepare('UPDATE users SET option_online = :opt WHERE ID = :id');
			$req->execute(array('id' => $_SESSION['user_id'],
								'opt' => $opt
								));
		$_SESSION['success'].='Option de l\'affichage de votre présence en ligne modifiée.<br/>';
		}
	if(!empty($_POST['mdp']) AND !empty($_POST['mdp2']))
		{
		
		$mdp= secure::bdd($_POST['mdp']);
		$mdp2= secure::bdd($_POST['mdp2']);
		if($mdp!=$mdp2) {$_SESSION['err'].="Vous avez entré deux mots de passe différents !<br/>";}
		if($_SESSION['err']=='')
			{
			$req = $bdd->prepare('UPDATE users SET mdp = :mdp WHERE ID = :id');
			$req->execute(array('id' => $_SESSION['user_id'],'mdp' => sha1(md5('salt').md5($mdp))));
			$_SESSION['success'].='mot de passe modifié.<br/>';
			if(isset($_COOKIE['pseudo']) AND isset($_COOKIE['mdp']))
			{setcookie('mdp', sha1(md5('salt').md5($mdp)), time() + 365*24*3600);}
			}
		}
	}else{if(isset($_POST['mdpmain'])){$_SESSION['err']='Mot de passe actuel invalide !';}}
$req=$bdd->prepare('SELECT * FROM users WHERE id = :id');
		$req->execute(array('id' => $_SESSION['user_id']));
		$data=$req->fetch();$req->closeCursor();
?>
	<br/>
	<form action="/membre/param" method="post" name="param" class="form-horizontal">
	<fieldset><legend>Paramètres du profil </legend>
		<input type="hidden" name="mail_old" value="<?php echo $data['mail'];?>"/>
		<input type="hidden" name="pseudo_old" value="<?php echo $data['pseudo'];?>"/>
		<input type="hidden" name="option_mail_old" value="<?php if($data['option_mail']==1){echo '1';}else{echo '0';}?>"/>
		<input type="hidden" name="option_online_old" value="<?php if($data['option_online']==1){echo '1';}else{echo '0';}?>"/>
		<input type="hidden" name="option_newsletter_old" value="<?php if($data['option_newsletter']==1){echo '1';}else{echo '0';}?>"/>
		<br><div class="control-group"><label class="control-label" for="mail">E-mail </label>
			<div class="controls"><input type="email" placeholder="email" id="mail" name="mail" <?php echo 'value="'.$data['mail'].'"';?>/></div>
		</div>
		<div class="control-group">
			<div class="controls"><label class="checkbox" for="option_mail">
				<input type="checkbox" name="option_mail" id="option_mail" <?php if($data['option_mail']==1){echo 'checked';}?>/>
				Cacher votre E-mail au public ? </label>
			</div>
		</div>
		<div class="control-group">
			<div class="controls"><label class="checkbox" for="option_online">
				<input type="checkbox" id="option_online" name="option_online" <?php if($data['option_online']==1){echo 'checked';}?>/>
			Autoriser l'affichage de votre présence en ligne ?</label>
			</div>
		</div>
		<div class="control-group">
			<div class="controls"><label class="checkbox" for="option_newsletter">
				<input type="checkbox" id="option_newsletter" name="option_newsletter" <?php if($data['option_newsletter']==1){echo 'checked';}?>/>
			Abonnement aux newsletter ?</label>
			</div>
		</div>
	</fieldset>
	<fieldset><legend>Changer de pseudo </legend>
		<div class="control-group"><label class="control-label" for="mdp">Nouveau pseudo </label>
			<div class="controls"><input type="text" placeholder="entrez pseudo" value="<?php echo $data['pseudo'];?>" id="pseudo" name="pseudo"/>
			<span class="help-inline" id="status"></span></div>
		</div>
	</fieldset>
	<fieldset><legend>Changer de mot de passe </legend>
		<div class="control-group"><label class="control-label" for="mdp">Nouveau de mot de passe </label>
			<div class="controls"><input type="password" placeholder="entrez le mdp" id="mdp" name="mdp"/></div>
		</div>
		<div class="control-group"><label class="control-label" for="mdp2">confirmer le mot de passe </label>
			<div class="controls"><input type="password" placeholder="réentrez le mdp" id="mdp2" name="mdp2"/></div>
		</div>
	</fieldset>
	<fieldset><legend>Supprimer mon compte </legend>
		<div class="control-group">
			<div class="controls"><label class="checkbox" for="compte_delete">
				<input type="checkbox" id="compte_delete" name="compte_delete" />
			Je souhaite supprimer mon compte</label>
			</div>
		</div>
	</fieldset>
	<fieldset><legend>Validation </legend>
		<div class="control-group"><label class="control-label" for="mdpmain">Entrez votre mot de passe actuel </label>
			<div class="controls"><input type="password" placeholder="mdp actuel" id="mdpmain" name="mdpmain"/>
			<span class="help-inline"><a class="btn"  onclick="
					if($('#mdpmain').val()!='')
						{
						if(!document.param.compte_delete.checked==false)
							{
							if(confirm('Êtes-vous sûr de vouloir supprimer votre compte ? \n \n Si vous continuez, toutes les données vous concernant directement sur ce site seront définitivement inaccessible. \n Il vous sera donc impossible de les récupérer. \n Les données créées par vous au titre du jeu de rôle naheulbeuk seront conservée et resteront disponible afin d\'assurer le bon fonctionnement du site. \n Toutefois si un problème spécifique survient, n\'hésitez pas à contacter l\'administrateur du site.'))
								{
								document.forms.param.submit();
								}
							else{return false;}
							}
						else{document.forms.param.submit();}
						}
					else{alert('/!\\ Vous n\'avez pas entré votre mot de passe actuel, cela sert à assurer la sécurité de votre compte !');return false;}
					">Valider</a></span>
			</div>
		</div>
	</fieldset>
	</form>
	<script type="text/javascript">
				$(document).ready(function(){
					$("#pseudo").keyup(function(){
						var username = $("#pseudo").val();
						var msgbox = $("#status");
						if(username.length > 3)
							{
							msgbox.html('<img alt="loader ajax" src="/ressources/img/img_html/loader.gif" align="absmiddle">&nbsp;Vérification de la disponibilité...');
							$.ajax({  
								type: "POST",  
								url: "/ajax/ajax_check_pseudo.php",  
								data: "username="+ username,  
								success: function(msg){
									if(msg == "OK")
									{ 
										$("#pseudo").removeClass("red");
										$("#pseudo").addClass("green");
										msgbox.html('<span class="alert alert-success"><img alt="OK" src="/ressources/img/img_html/yes.png" align="absmiddle"> Disponible</span>');
									}  
									else  
									{  
										 $("#pseudo").removeClass("green");
										 $("#pseudo").addClass("red");
										msgbox.html(msg);
									} 
							   } 
							  }); 
							}
						else{
							$("#pseudo").addClass("red");
							msgbox.html('<font color="#cc0000">Entrez un pseudo valide (plus de 3 caractères).</font>');
							}
						return false;
					});
				});
			</script>
	<?php }
	}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* /////////////////////////          EDIT PROFIL D'UN MEMBRE          /////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
else if($mode=='edit_profil') //modif du profil
	{
	if(!isuser()){echo ACCES_REFUSE_INVITE;$header='<meta name="robots" content="noindex,follow" />';}
	else{
	if(!empty($_POST))
		{
		if(!empty($_POST['jour']) AND !empty($_POST['mois']) AND !empty($_POST['annee']) AND ($_POST['jour']!=$_POST['jour_old'] OR $_POST['mois']!=$_POST['mois_old'] OR $_POST['annee']!=$_POST['annee_old']))
			{
			if(filter_var($_POST['jour'], FILTER_VALIDATE_INT) AND filter_var($_POST['mois'], FILTER_VALIDATE_INT) AND filter_var($_POST['annee'], FILTER_VALIDATE_INT))
				{
				if(checkdate($_POST['mois'],$_POST['jour'],$_POST['annee']))
					{
					$date=mktime(0,0,0,$_POST['mois'],$_POST['jour'],$_POST['annee']);
					$req = $bdd->prepare('UPDATE users SET age = :mdp WHERE ID = :id');
					$req->execute(array('id' => $_SESSION['user_id'],'mdp' => $date));
					$_SESSION['success'].='Date de naissance mise à jour.<br/>';
					}
					else{$_SESSION['err'].='La date de naissance entrée n\'est pas valide.<br/>';}
				}
			else{$_SESSION['err'].='La date de naissance entrée n\'est pas valide.<br/>';}
			}
		if((!empty($_POST['avatar']) AND filter_var($_POST['avatar'],FILTER_VALIDATE_URL)==true AND $_POST['avatar'] != $_SESSION['user_avatar'] AND !isset($_POST['option_gravatar'])) 
			OR (isset($_POST['option_gravatar']) AND isset($_POST['option_gravatar_old']) AND $_POST['option_gravatar']!=$_POST['option_gravatar_old']))
		{
		if(isset($_POST['option_gravatar']))
			{
			$req=$bdd->prepare('SELECT mail FROM users WHERE id = :id');
			$req->execute(array('id' => $_SESSION['user_id']));
			$data=$req->fetch();$req->closeCursor();
			$_POST['avatar']=gravatar($data['mail']);$gravatar=1;
			}
		else{$gravatar=0;}
		$req = $bdd->prepare('UPDATE users SET avatar = :avatar,option_gravatar = '.$gravatar.' WHERE ID = :id');
		$req->execute(array('id' => $_SESSION['user_id'],'avatar' => $_POST['avatar']));
		$_SESSION['success'].='Avatar mis à jour.<br/>';
		$_SESSION['user_avatar']=$_POST['avatar'];
		}else if(isset($_POST['avatar']) AND filter_var($_POST['avatar'],FILTER_VALIDATE_URL)==false){$_SESSION['err'].='url de l\'avatar non-valide.<br/>';}
		
		/* else{if(empty($_POST['jour']) AND empty($_POST['mois']) AND empty($_POST['annee']) AND ($_POST['jour']!=$_POST['jour_old'] OR $_POST['mois']!=$_POST['mois_old'] OR $_POST['annee']!=$_POST['annee_old'])){$date='non renseigné';$req = $bdd->prepare('UPDATE users SET age = :mdp WHERE ID = :id');$req->execute(array('id' => $_SESSION['user_id'],'mdp' => $date));$_SESSION['success'].='Date de naissance mise à jour.<br/>';}} */
		if($_POST['sexe']=='H'){$sexe='homme';}else if($_POST['sexe']=='F'){$sexe='femme';}else{$sexe='non renseigné';}
		if($sexe != $_POST['sexe_old'] OR $_POST['localisation'] != $_POST['localisation_old'] OR $_POST['descr'] != $_POST['descr_old'])
			{
			$req = $bdd->prepare('UPDATE users SET descr= :descr,localisation = :localisation,sexe = :sexe WHERE ID = :id');
			if(empty($_POST['localisation'])){$localisation='non renseignée';}else{$localisation=$_POST['localisation'];}
			$req->execute(array('id' => $_SESSION['user_id'],'sexe' => $sexe,'localisation'=>$localisation,'descr'=>$_POST['descr']));
			$_SESSION['success'].='Sexe, localisation et description mis à jour.<br/>';
			}
		
		}
		$req=$bdd->prepare('SELECT * FROM users WHERE id = :id');
		$req->execute(array('id' => $_SESSION['user_id']));
		$data=$req->fetch();$req->closeCursor();
		$titre='Modification du profil de '.$_SESSION['pseudo'];$fil['profil']='membre';
		if($data['age']!='non renseigné'){$var_age='<input type="number" placeholder="jour" id="jour" min="1" max="31" name="jour" maxlength="2" size="2" value="'.date("d",$data["age"]).'"/>/<input type="number"  placeholder="mois" id="mois" name="mois" min="1" max="12" maxlength="2" size="2" value="'.date("m",$data["age"]).'"/>/<input min="1950" max="2010" type="number"  placeholder="année" id="annee" name="annee" maxlength="4" size="4" value="'.date("Y",$data["age"]).'"/>';}
		else{$var_age='<input type="number" placeholder="jour" id="jour" name="jour" maxlength="2" min="1" max="31" size="5"/>/<input type="number"  placeholder="mois" max="12" min="1" id="mois" name="mois" maxlength="2" size="5"/>/<input type="number"  placeholder="année" id="annee" name="annee" min="1950" max="2010" maxlength="4" size="5" />';}
		?>
		<div class="info-box">
		Vous pouvez changer vos infos personnelles sur cette page. <br/>Ces informations seront visible à travers "DonjonFacile.fr". 
		Si vous ne désirez pas présenter certaines infos, ne remplissez pas le champ - rien n'est obligatoire ici. 
		</div>
		<form action="/membre/edit_profil" method="post" name="param" class="form-horizontal">
			<input type="hidden" name="sexe_old" value="<?php echo secure::html($data['sexe'],1);?>"/>
			<input type="hidden" name="option_gravatar_old" value="<?php echo secure::html($data['option_gravatar'],1);?>"/>
			<input type="hidden" name="descr_old" value="<?php echo secure::html($data['descr'],1);?>"/>
			<input type="hidden" name="localisation_old" value="<?php if($data['localisation']!='non renseignée'){echo secure::html($data['localisation'],1);}?>"/>
			<input type="hidden" name="jour_old" value="<?php if($data['age']!='non renseigné'){echo date("d",$data["age"]);}?>"/>
			<input type="hidden" name="mois_old" value="<?php if($data['age']!='non renseigné'){echo date("m",$data["age"]);}?>"/>
			<input type="hidden" name="annee_old" value="<?php if($data['age']!='non renseigné'){echo date("Y",$data["age"]);}?>"/>
			<fieldset><legend>Informations de profil</legend>
				<div class="control-group"><div class="controls">
					<label for="option_gravatar" class="checkbox">
						<input type="checkbox" name="option_gravatar" id="option_gravatar"<?php if($data['option_gravatar']!=0){echo ' checked ';}?>
						onchange="$('#avatar_block').toggleClass('hidden');" /> 
						Utiliser <a href="http://www.gravatar.com/" target="_blank">gravatar</a> pour mon avatar.
					</label>
				</div></div>
				<div id="avatar_block" class="control-group<?php if($data['option_gravatar']==1){echo ' hidden ';} ?>"><label class="control-label" for="avatar">Votre avatar </label>
					<div class="controls"><input type="text" name="avatar" size="50" id="avatar" <?php echo 'value="'.secure::html($data['avatar'],1).'"';?>/>
				<span class="help-inline">
				<?php help_button('Avatar','Le lien de votre avatar doit pointer sur l\'image elle-même (lien direct). 
				La taille idéale de votre avatar doit être de 75x75 pixels.'); ?>
				<a href="/ajax/ajax_avatar.php" class="btn btn-primary"  onclick="href_modal(this);return false;" rel="nofollow">Bibliothèque d'avatars</a>
				<a class="btn" target="_blank" href="/membre/library"><i class="icon-film"></i> Bibliothèque personnelle</a>
				</span>
				<span class="help-block"><img id="img_preview" style="margin:10px;margin-bottom:0;" src="<?php echo secure::html($data['avatar'],1);?>" width="75" height="75"/></span>
				</div></div>
				<div class="control-group"><label class="control-label" for="sexe">Sexe </label>
					<div class="controls"><select size="1" name="sexe" id="sexe">
						<option value="0" <?php if($data['sexe']=='non renseigné'){echo ' selected ';} ?>>caché</option>
						<option value="H" <?php if($data['sexe']=='homme'){echo ' selected ';} ?>>homme</option>
						<option value="F"<?php if($data['sexe']=='femme'){echo ' selected ';} ?>>femme</option>
					</select></div>
				</div>
				<!--<label for="age">Date de naissance </label></td><td><?php echo $var_age; ?>-->
				<div class="control-group"><label class="control-label" for="localisation">Localisation </label>
					<div class="controls"><input size="35" type="text" placeholder="localisation" id="localisation" name="localisation" <?php if($data['localisation']!='non renseignée'){echo 'value="'.secure::html($data['localisation'],1).'"';}?>/></div>
				</div>
			</fieldset>
			<fieldset><legend>Description</legend>
				<textarea rows="5" class="input-xxlarge" name="descr" id="descr" placeholder="votre description"><?php echo secure::html($data['descr'],1);?></textarea>
			</fieldset><br/>
			<div class="control-group"><div class="controls"><a class="btn"  onclick="document.forms.param.submit()">Valider</a></div></div>
		</form>
		<?php 
		}
	}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////          AFFICHAGE PROFIL MEMBRE          //////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
else if($mode=='' OR $mode=='profil')
	{
	if(!isset($_SESSION['user']) AND empty($_GET['id'])){echo ACCES_REFUSE_INVITE;$header='<meta name="robots" content="noindex,follow" />';}
	else
		{
		if(empty($_GET['id']) OR !$mode='profil'){$user_id=$_SESSION['user_id'];}else{$user_id=$_GET['id'];}
		$req = $bdd->prepare('SELECT users.*,
						  (SELECT COUNT(*) as nb_perso FROM perso WHERE user_id = :id AND etat != "delete") as nb_perso,
						  (SELECT COUNT(*) as nb_item FROM item WHERE auteur_id = :id AND etat != "delete") as nb_item,
						  (SELECT COUNT(*) as nb_group FROM `group` WHERE user_id = :id AND etat != "delete") as nb_group
						  FROM users 
						  WHERE users.id= :id
						  AND users.etat!="delete"
						  ');
		$req->execute(array('id' => $user_id));
		if($req->rowCount() == 0){echo '<br/>Ce membre n\'existe pas ou plus.<br/>';include('pages/404.php');$error=1;}
		else{
		$data=$req->fetch();$req->closeCursor();
		if(isuser() AND $user_id==$_SESSION['user_id'])
			{
			verif_uri('/membre');
			}
		else{
			verif_uri('/membre/'.$data['id'].'/'.to_url($data['pseudo']));
			}
		if($data['nb_perso'] == 0){$nb_perso= 'Aucun personnage.';}
		else{$nb_perso=$data['nb_perso'];}	
		if($data['nb_item'] == 0){$nb_item= 'Aucun objet.';}
		else{$nb_item=$data['nb_item'];}	
		if($data['nb_group'] == 0){$nb_group= 'Aucune compagnie.';}
		else{$nb_group=$data['nb_group'];}	
		$titre='Profil de '.secure::html($data['pseudo']);
		if($data['type']=='ban'){echo '<b>Cet utilisateur a été banni de ce site soit de manière définitive, soit de manière provisoire !</b>';}
		?>
		<!--<fieldset><legend>profil de  <?php echo secure::html($data['pseudo']);?></legend>-->
		<fieldset><legend>site</legend>
			<span style="float:left;margin-right:3px;"><img class="img-polaroid" width="75" height="75" style="width:75px;height:75px;" src="<?php echo $data['avatar'];?>"/></span>
			Pseudo : <b><?php echo secure::html($data['pseudo']);?></b><br/>
			Status: <?php echo secure::html($data['status']);?><br/>
			Personnages : <?php echo secure::html($nb_perso);?><br/>
			Objets : <?php echo secure::html($nb_item);?><br/>
			Compagnies : <?php echo secure::html($nb_group);?><br/>
		</fieldset>
		<fieldset><legend>activité</legend>
			Inscription : <?php echo '<time data-date="'.$data['date'].'" title="'.date_bdd($data['date']).'">Le '.secure::html(date_bdd($data['date']));?></time><br/>
			Dernière activitée : <?php 
								if(isadmin() OR $data['option_online']==1){echo '<time data-date="'.$data['last_connect'].'" title="'.date_bdd($data['last_connect']).'">Le '.secure::html(date_bdd($data['last_connect'])).'</time>';}
								else{echo 'inconnue';}
								?>
			<br/>
			E-mail : <?php 
			if(isadmin()){echo secure::html($data['mail'],1);}
			elseif($data['option_mail']!=1 AND isuser())
				{
				if(!empty($data['mail']))
					{
					echo '<img alt="image du mail antispam" style="vertical-align:center;" src="/ajax/img_mail.php?string='.strrev(secure::html($data['mail'],1)).'" />';
					}
			else{echo 'non renseigné';}
				}
		else{echo 'caché';}?><br/>
		</fieldset>
		<fieldset><legend>informations personnelles</legend>
			Sexe : <?php echo secure::html($data['sexe']);?><br/>
			<!--Âge : <?php if($data['age'] !='non renseigné'){echo secure::html(age(date("Y",$data['age']),date("m",$data['age']),date("d",$data['age']))).' ans';}else{echo $data['age'];}?> <br/>
			-->Localisation : <?php echo secure::html($data['localisation']);?><br/>
		</fieldset>
		<?php if(!empty($data['descr'])){ ?><fieldset><legend>Description </legend><?php echo secure::html($data['descr']);?></fieldset><br/><?php } ?>
		<!--</fieldset>-->
		<?php
		}
		}
	}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ///////////////////////////           BIBLIOTHEQUE IMG           ////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
else if($mode=='library')
	{
	if(!isuser()){echo ACCES_REFUSE_INVITE;$header='<meta name="robots" content="noindex,follow" />';}
	else{verif_uri('/membre/library');
		if(!empty($_POST['del_name']))
			{
			@unlink('ressources/img/user_img/'.$_SESSION['user_id'].'/'.str_replace('./','',htmlspecialchars($_POST['del_name'])));
			$_SESSION['success'].='Image supprimée';
			}
		if(!empty($_FILES) AND !empty($_POST['mode']))
			{
			$extensions_valides = array('jpg','jpeg','gif','png','bmp');
			$extension_upload = strtolower(  substr(  strrchr($_FILES['img']['name'], '.')  ,1)  );
			if (!in_array($extension_upload,$extensions_valides)){$_SESSION['err'].='Le fichier n\'est pas une image';}
			else{
				if ($_FILES['img']['size'] > 1000000 OR $_FILES['img']['error'] > 0){$_SESSION['err'].='L\'image est trop volumineuse !';}
				else{
					$filename = uniqid('img_').'.'.$extension_upload;
					$nom='ressources/img/user_img/'.$_SESSION['user_id'].'/'.$filename;
					$resultat = move_uploaded_file($_FILES['img']['tmp_name'],$nom);
					if($_POST['mode']=='perso' OR $_POST['mode']=='user')
						{
						@$ext= getimagesize ($nom);@$ext=$ext[2];
						if($ext == IMAGETYPE_GIF){$source = @imagecreatefromgif($nom);}
						elseif($ext == IMAGETYPE_PNG){$source = @imagecreatefrompng($nom);}
						elseif($ext == IMAGETYPE_JPEG){$source = @imagecreatefromjpeg($nom);}
						if(isset($source))
						{
						if($_POST['mode']=='perso'){@$destination = imagecreatetruecolor(465, 474);} // On crée la miniature vide
						else{@$destination = imagecreatetruecolor(75, 75);}
						// Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
						@$largeur_source = imagesx($source);
						@$hauteur_source = imagesy($source);
						@$largeur_destination = imagesx($destination);
						@$hauteur_destination = imagesy($destination);
						// On crée la miniature
						@imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);
						imagepng($destination,'ressources/img/user_img/'.$_SESSION['user_id'].'/'.uniqid('img_').'.png');
						imagedestroy($destination);
						unlink($nom);
						}
						}
					if($resultat){$_SESSION['success'].='L\'image a été ajoutée à votre bibliothèque !';}
					else{$_SESSION['err'].='L\'image n\'a pas pu être enregistrée sur le serveur !';}
					}
				}
			}
		$titre='Votre librairie';
		function ScanDirectory($Directory,$opt=false){
		  $MyDirectory = opendir($Directory) or die('Erreur');
		  $i=0;$uniqid=uniqid();
			while($Entry = @readdir($MyDirectory)) 
				{
				if($Entry!='.' AND $Entry!='..' AND $Entry!='index.html')
					{
					if(($i%4)==0){$margin=' style="margin-left:0;height:300px;"';}else{$margin='style="height:300px;"';}
					echo '<div class="span3 center"'.$margin.'>
					<a href="/'.$Directory.$Entry.'" target="_blank"><img width="150"
					style="cursor:pointer;width:150px;" 
					src="/'.$Directory.$Entry.'"/></a>
					<div class="input-append">
					<input class="span5" type="text" value="http://donjonfacile.fr/'.$Directory.$Entry.'"/>
					<a class="btn" id="copy_'.$uniqid.'_'.$i.'" title="Copier dans le presse-papier"><i class="icon-inbox"></i> Copier</a>
					<script>
					$(document).ready(function(){
						$("#copy_'.$uniqid.'_'.$i.'").zclip({
								copy:\'http://donjonfacile.fr/'.$Directory.$Entry.'\',
								afterCopy:function(){$.pnotify({text:\'Le lien a été copié dans votre presse-papier\',title:\'Texte copié\',type:\'success\'});;}
							});
						});
					</script>
					';//path:'js/ZeroClipboard.swf',
				if($opt)
					{
					echo'<form method="post" action="" style="display:inline-block;"><input type="hidden" name="del_name" value="'.$Entry.'"/>
						<a class="btn btn-danger" title="Supprimer cette image" onclick="if(confirm(\'Etes-vous certains de vouloir supprimer cette image ?\')){$(this).parent().submit();}"><i class="icon-remove icon-white"></i></a>
						</form>';
					}
				echo'</div>
					</div>';
					$i++;
					}
				}
			closedir($MyDirectory);
			return $i;
		}
		if(!is_dir('ressources/img/user_img/'.$_SESSION['user_id'].'/')){mkdir('ressources/img/user_img/'.$_SESSION['user_id'].'/', 0777, true);}
		echo '<fieldset><legend>Ma bibliothèque</legend><div class="row-fluid">';
		$nb_img=ScanDirectory('ressources/img/user_img/'.$_SESSION['user_id'].'/',true);
		if($nb_img==0){echo 'Aucune image enregistrée !';}
		echo '</div></fieldset>';
		if($nb_img<12 OR isadmin())
			{
			echo'	<form method="post" action="" class="well form-inline" enctype="multipart/form-data">
						<fieldset><legend>Ajouter une image (moins de 1Mo)</legend>
						<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
						<input type="file" name="img" accept="image/*"/> 
						 <label class="radio"><input type="radio" name="mode" value="default" checked>Pas de modification</label> 
						 <label class="radio"><input type="radio" name="mode" value="perso">Redimensionner pour un personnage</label> 
						 <label class="radio"><input type="radio" name="mode" value="user">Redimensionner pour un compte utilisateur</label> 
						<input type="submit" class="btn" value="Envoyer"/>
						</fieldset>
					</form>';
			}
		else{echo '<div class="info-box"><b>Maximum d\'images (12) atteint !</b></div>';}
		echo '<fieldset><legend>Bibliothèque du site</legend><div class="row-fluid">';
		ScanDirectory('ressources/img/avatar/');
		echo '</div></fieldset>';
		}
	}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ///////////////////////////          LISTE UTILISATEURS          ////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
else if($mode=='list')
	{
	verif_uri('/membre/list');
	$titre= 'liste des membres';if(isset($_SESSION['user_id'])){$fil['profil']='membre';}
	if(isset($_SESSION['user']) AND $_SESSION['user']=='admin'){$req=$bdd->prepare('SELECT * FROM users WHERE etat != "delete" ORDER BY type,pseudo');}
	else{$req=$bdd->prepare('SELECT * FROM users WHERE etat != "delete" AND type != "ban" ORDER BY type,pseudo');}
	$req->execute();
	echo '<table class="jtable table table-striped table-hover">
		<thead><tr>
			<th>Avatar</th>
			<th>Pseudo</th>
			<th>Action</th>
			<th>Inscription</th>
			<th>Dernière apparition</th>
		</tr></thead><tbody>';
	$date_now =time()-15*60; /* new DateTime();
	$intervalle = new DateInterval('P15M');
	$date_now=$date_now->sub($intervalle); */
	while($data=$req->fetch())
		{
		if($data['type']=='user'){$type='';}else{$type=' ('.$data['type'].') ';}
		$date2 = strtotime($data['last_connect']);
		if($data['option_online']!=1){$online='<img title="état caché" src="'.DIR_IMG_HTML.'hidden.png" /> ';}
		elseif($date_now < $date2){$online='<img title="en ligne" src="'.DIR_IMG_HTML.'online.png" /> ';}
		else{$online='<img title="hors ligne" src="'.DIR_IMG_HTML.'offline.png" /> ';}
		echo '<tr>
			<td>
				<img src="'.secure::html($data['avatar'],1).'" style="width:40px;height:40px;" width="40" height="40" />
			</td>
			<td>
				'.$online.secure::html($data['pseudo'],1).$type.'
			</td>
			<td>
				<div class="btn-group"><a class="btn" href="/membre/'.$data['id'].'/'.to_url($data['pseudo']).'"><i class="icon-user"></i> profil</a>
				<a class="btn" href="/membre/persos/'.$data['id'].'/'.to_url($data['pseudo']).'"><i class="icon-th-list"></i> personnages</a>';
		if(isadmin())
			{
			echo '<a class="btn btn-danger" href="/membre/ban/'.$data['id'].'/'.to_url($data['pseudo']).'" 
			onclick="return(confirm(\'Etes-vous sûr de vouloir bannir '.$data['pseudo'].' ?\'));"><i class="icon-ban-circle"></i> bannir</a>';
			}
		echo '</div>
				<a class="btn" href="/messagerie?username='.$data['pseudo'].'"><i class="icon-envelope"></i> Envoyer un message</a></td>
			<td>
				<time data-date="'.$data['date'].'" title="'.date_bdd($data['date']).'">Le '.secure::html(date_bdd($data['date'])).'</time>
			</td>
			<td>';
		if(isadmin() OR $data['option_online']==1){echo '<time data-date="'.$data['last_connect'].'" title="'.date_bdd($data['last_connect']).'">Le '.secure::html(date_bdd($data['last_connect'])).'</time>';}
		else{echo 'inconnue';}
		echo '</td>
		</tr>';
		}
	$req->closeCursor(); 
	echo '</tbody></table>';
	}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////          LISTE PERSOS UTILISATEURS          ////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
else if($mode=='persos')
	{
	if(!isset($_SESSION['user']) AND empty($_GET['id'])){echo ACCES_REFUSE_INVITE;$header='<meta name="robots" content="noindex,follow" />';}
	else
		{
		if(empty($_GET['id']) OR (isset($_SESSION['user_id']) AND $_SESSION['user_id']==$_GET['id'])){header('Location: /perso');exit();}
		if(empty($_GET['id']) OR !$mode='profil'){$user_id=$_SESSION['user_id'];}else{$user_id=$_GET['id'];}
		$req=$bdd->prepare('SELECT * FROM users WHERE id = :id AND etat != "delete"');
		$req->execute(array('id' => $user_id));
		if($req->rowCount() == 0){echo '<br/>Ce membre n\'existe pas ou plus.<br/>';include('pages/404.php');$error=1;}
		else{
		$data=$req->fetch();$req->closeCursor(); 
		verif_uri('/membre/persos/'.$data['id'].'/'.to_url($data['pseudo']));
		if($data['type']=='ban'){echo 'Cet utilisateur a été banni de ce site soit de manière définitive, soit de manière provisoire !';}
		$titre='Liste des personnages de '.secure::html($data['pseudo']);$fil['profil de '.secure::html($data['pseudo'])]='/membre/'.$data['id'].'/'.to_url($data['pseudo']);
		$req2=$bdd->prepare('SELECT * FROM perso WHERE user_id = :id AND etat != "delete" ORDER BY name');
		$req2->execute(array('id' => $user_id));
		echo '<h2>Personnages créés :</h2><div class="row-fluid">';
		$i=0;
		while($data=$req2->fetch())
			{
			if(isset($_SESSION['user_id']) AND get_droits_perso($data['id'],$_SESSION['user_id'],'wiew')){$droits=1;}else{$droits=0;}
			if(($i%3)==0){$margin=' style="margin-left:0;"';}else{$margin='';}$i++;
			echo '	<div class="span4"'.$margin.'> 
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
										  ';
						if($droits!=1)
							{
							echo'<li><a href="/perso/fiche/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-file"></i> Fiche</a></li>';
							}
						else{
							echo'<li><a href="/perso/fiche/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-file"></i> Fiche</a></li>
								<li class="divider"></li>
								<li><a href="/perso/droits/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-lock"></i> Droits</a></li>
								<li><a href="/perso/delete/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-remove"></i> supprimer</a></li>
								';
							}
					echo'					</ul>
										</div>
						</div>
					</fieldset>
					</div>';
					/* <div class="papers"> 
						<table style="margin:3px;text-align:center;">
							<tr><td><b>'.$data['name'].'</b> ('.$data['origine'].') </td> <td >'.$data['metier'].' </td>
							<td rowspan="3"><div class="button-group minor-group">
								<a class="button primary icon search" href="perso-'.$data['id'].'-'.to_url($data['name']).'.html">Voir</a>';
			if($droits==1){echo '<a class="button icon edit" href="perso_edit-'.$data['id'].'-'.to_url($data['name']).'.html">Modifier</a>';}
						echo '</div></td></tr>
							<tr><td rowspan="2"><table class="table_carac">
										<tr><th>COU</th><th>INT</th><th>CHA</th><th>AD</th><th>FO</th></tr>
										<tr><td>'.$data['COU'].'</td><td>'.$data['INTL'].'</td><td>'.$data['CHA'].'</td><td>'.$data['AD'].'</td><td>'.$data['FO'].'</td></tr>
									</table></td>
							 <td>Niveau <b>'.get_niv($data['xp']).'</b> ('.$data['xp'].' exp)</td></tr>
							<tr><td>'.get_money(array('PO'=>$data['PO'],'PA'=>$data['PA'],'PC'=>$data['PC'],'LT'=>$data['LT'], 'LB'=>$data['LB'])).' PO </td></tr>
						</table>
					</div>'; */
			}
		if($req2->rowCount() == 0){echo 'Aucun personnage créé.<br/>';}
		$req2->closeCursor();
		echo '</div><h2>Autres personnages :</h2><div class="row-fluid">';
			$r='SELECT p.* 
					FROM perso p
					INNER JOIN users_persos up
					ON p.id = up.perso_id 
					WHERE p.etat!="delete" AND up.etat!="delete" AND up.user_id ='.$user_id.' AND p.user_id !='.$user_id;//echo $r;
			$req=$bdd->query($r);
			if($req->rowCount() == 0){echo '<br/>Aucun personnage trouvé.';}
			$i=0;
			while($data=$req->fetch())
			{
			if(isset($_SESSION['user_id']) AND get_droits_perso($data['id'],$_SESSION['user_id'],'wiew')){$droits=1;}else{$droits=0;}
			if(($i%3)==0){$margin=' style="margin-left:0;"';}else{$margin='';}$i++;
			echo '	<div class="span4"'.$margin.'> 
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
										  ';
						if($droits!=1)
							{
							echo'<li><a href="/perso/fiche/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-file"></i> Fiche</a></li>';
							}
						else{
							echo'<li><a href="/perso/fiche/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-file"></i> Fiche</a></li>
								<li class="divider"></li>
								<li><a href="/perso/droits/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-lock"></i> Droits</a></li>
								<li><a href="/perso/delete/'.$data['id'].'/'.to_url($data['name']).'"><i class="icon-remove"></i> supprimer</a></li>
								';
							}
					echo'					</ul>
										</div></div>
					</fieldset>
					</div>';
			/* echo '<div class="papers"> 
						<table style="margin:3px;text-align:center;">
							<tr><td><b>'.$data['name'].'</b> ('.$data['origine'].') </td> <td >'.$data['metier'].' </td>
							<td rowspan="3"><div class="button-group minor-group">
												<a class="button primary icon search" href="perso-'.$data['id'].'-'.to_url($data['name']).'.html">Voir</a>';
						if($droits==1){echo '<a class="button icon edit" href="perso_edit-'.$data['id'].'-'.to_url($data['name']).'.html">Modifier</a>';}
									echo '</div></td></tr>
							<tr><td rowspan="2"><table class="table_carac">
										<tr><th>COU</th><th>INT</th><th>CHA</th><th>AD</th><th>FO</th></tr>
										<tr><td>'.$data['COU'].'</td><td>'.$data['INTL'].'</td><td>'.$data['CHA'].'</td><td>'.$data['AD'].'</td><td>'.$data['FO'].'</td></tr>
									</table></td>
							 <td>Niveau <b>'.get_niv($data['xp']).'</b> ('.$data['xp'].' exp)</td></tr>
							<tr><td>'.get_money(array('PO'=>$data['PO'],'PA'=>$data['PA'],'PC'=>$data['PC'],'LT'=>$data['LT'], 'LB'=>$data['LB'])).' PO </td></tr>
						</table>
					</div>'; */
			}
			echo '</div>';
		}
		}
	}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* //////////////////////////          BAN D'UN UTILISATEUR          ///////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
else if($mode=='ban')
	{
	if(!isset($_GET['id']) OR !isset($_SESSION['user']) OR $_SESSION['user']!='admin'){echo ACCES_REFUSE_ADMIN;$header='<meta name="robots" content="noindex,follow" />';}
	else{
		$req=$bdd->prepare('SELECT * FROM users WHERE id = :id AND etat != "delete"');
		$req->execute(array('id' => $_GET['id']));
		$data=$req->fetch();$req->closeCursor();
		if($data['type']=='admin'){echo 'ce membre est un administrateur, il est impossible de le bannir !<br/>';}
		else{
		$ban=0;
		if(!empty($_POST))
			{
			if(isset($_POST['deban']))
				{
				$req = $bdd->prepare('UPDATE users SET type = :opt WHERE ID = :id');
				$req->execute(array('id' => $_GET['id'],'opt' => 'user'));
				$req = $bdd->prepare('UPDATE ban SET etat = :opt WHERE user_id = :id');
				$req->execute(array('id' => $_GET['id'],'opt' => 'old'));
				$_SESSION['err'].='le membre n\'est désormais plus banni.';$ban=2;
				}
			else{
			if(empty($_POST['motif'])){$_SESSION['err'].='vous devez indiquer une raison de bannissement (simple question d\'equité)<br/>';}
			if(empty($_POST['d_d']) AND empty($_POST['d_h']) AND !isset($_POST['ban_def'])){$_SESSION['err'].='vous devez indiquer une durée de bannissement (ou un bannissement définitif)<br/>';}
			else{
				if(isset($_POST['ban_def'])){$time=0;}
				else{$time=time();
					if(!empty($_POST['d_h'])){if(filter_var($_POST['d_h'], FILTER_VALIDATE_INT)==false){$_SESSION['err'].='vous devez entrer un nombre d\'heures correct<br/>';}else{$time+=($_POST['d_h']*3600);}}
					if(!empty($_POST['d_d'])){if(filter_var($_POST['d_d'], FILTER_VALIDATE_INT)==false){$_SESSION['err'].='vous devez entrer un nombre de jours corrcet<br/>';}else{$time+=($_POST['d_d']*24*3600);}}
					}
				}
			if($_SESSION['err']=='' AND !isset($_POST['deban']))
				{
				$req = $bdd ->prepare('INSERT INTO ban(user_id, admin_id,time, motif) VALUES(:user_id,:admin_id, :time, :motif)');
				$req ->execute(array('user_id' => $_GET['id'],
									'admin_id' => $_SESSION['user_id'],
									'time' => $time,
									'motif' => secure::bdd($_POST['motif'])
									));
				$req = $bdd->prepare('UPDATE users SET type = :opt WHERE ID = :id');
				$req->execute(array('id' => $_GET['id'],
								'opt' => 'ban'
								));
				$_SESSION['success'].='membre banni avec succès';$ban=1;
				}
				}
			}
		$titre='bannir '.$data['pseudo'];$fil['profil de '.secure::html($data['pseudo'])]='/membre/'.$data['id'].'/'.to_url($data['pseudo']);
		if(empty($data)){echo '<br/>ce membre n\'existe pas !<br/><br/><br/><br/>';}
		else if(($data['type']=='ban' OR $ban==1) AND $ban!=2){echo '<br/>ce membre est déjà banni !<br/>
											<form action="/membre/ban/'.$_GET['id'].'" method="post" name="ban">
											<input type="hidden" name="deban" value="deban"/>
											<div class="center"><a class="button danger icon approve primary"  onclick="document.forms.ban.submit()">debannir '.$data['pseudo'].'</a></div>
											</form>
											';}
		else{
		?>
		<form action="/membre/ban<?php echo '/'.$_GET['id']; ?>" method="post" name="ban">
			<table>
				<tr><td><label for="motif">motif du ban </label></td><td><input placeholder="motif" name="motif" id="motif"/></td></tr>
				<tr><td><label>durée </label></td><td><input placeholder="heures" type="number" name="d_h" id="d_h" maxlength="2" size="5"/> h, <input type="number" placeholder="jours" name="d_d" id="d_d" maxlength="3" size="3"/> jours.</td></tr>
				<tr><td><label for="ban_def">ban definitif ? </label></td><td><input type="checkbox" name="ban_def" id="ban_def" /></td></tr>
				<tr><td colspan="2" class="center"><a class="btn btn-danger"  onclick="document.forms.ban.submit()"><i class="icon-ban-circle"></i> bannir <?php echo $data['pseudo']; ?></a></td></tr>
				<!--<tr><td><label></label></td><td></td></tr>-->
			</table>
		</form>
		<?php
			}
		}
		}
	}
else{include('pages/404.php');$error=1;}