<?php 
if(isset($_SESSION['user'])) {header('Location:'.referer());exit();}
require_once('includes/recaptchalib.php');

if(!empty($_GET['key']) AND !empty($_GET['pseudo']) AND !empty($_GET['pass']))
	{
	$titre='Validation de l\'inscription de '.secure::html($_GET['pseudo'],1);
	$req=$bdd->prepare('SELECT * FROM validation WHERE pseudo=:pseudo AND `key`=:key AND pass=:pass AND date>SUBDATE(NOW(), 4)');
	$req->execute(array(
				'pseudo' => $_GET['pseudo'],
				'pass' => $_GET['pass'],
				'key' => $_GET['key'],
				));
	if($req->rowCount() != 0)
		{
		$id=$req->fetch(PDO::FETCH_OBJ)->user_id;
		$req=$bdd->prepare('DELETE FROM validation WHERE pseudo=:pseudo');
		$req->execute(array(
				'pseudo' => $_GET['pseudo']
				));
		$req=$bdd->prepare('UPDATE users SET etat="default", type="user" WHERE id=:id');
		$req->execute(array(
				'id' => $id,
				));
		$req=$bdd->prepare('DELETE FROM users WHERE pseudo=:pseudo AND id !=:id');
		$req->execute(array(
				'pseudo' => $_GET['pseudo'],
				'id' => $id
				));
		$_SESSION['success'].= 'Compte validé : vous pouvez désormais connecter au site. ';
		header('Location: /');
		// include('cron/compteur.php'); //mise à jour du compteur de visiteur
		exit();
		}
	else{$_SESSION['err'].= 'Erreur : les paramètres donnés ne sont pas ou plus valide. Tentez de vous inscrire à nouveau, vous avez peut-être trop attendu pour valider votre compte (4 jours maximum) ou bien le pseudo que vous aviez choisi a été pris par un autre utilisateur.';
	header('Location: /accueil');exit();}
	}
elseif(isset($_POST['pseudo']) AND isset($_POST['mdp']) AND isset($_POST['mdp2']) AND isset($_POST['mail'])) // gestion des données entrées
	{
	// var_dump($_POST);exit;
	$resp = recaptcha_check_answer (PRIVATEKEY,get_ip(),$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
	$pseudo= secure::bdd($_POST['pseudo']);
	$mdp= secure::bdd($_POST['mdp']);
	$mdp2= secure::bdd($_POST['mdp2']);
	$mail= $_POST['mail'];$mail_2= $_POST['mail'];
	if($pseudo== '' OR $mdp=='' OR $mdp2=="" OR $mail="") {$_SESSION['err'].="Certains champs obligatoires n'ont pas été renseignés !<br/>";}
	try // verif pseudo
		{
		if(mb_strlen($pseudo)>25 OR mb_strlen($pseudo)<3){$_SESSION['err'].="Le pseudo entré est trop court ou trop long, veuillez en choisir un dont la longueur est comprise entre 3 et 25 caractères<br/>";}
		$rep = $bdd->prepare('SELECT pseudo FROM users WHERE etat!="delete" AND pseudo=:pseudo');
		$rep->execute(array('pseudo'=>$pseudo));
		if($rep->rowCount() != 0){$_SESSION['err'].="Ce pseudo déja utilisé !<br/>";}
		}
	catch(Exception $e){die('Erreur : '.$e->getMessage());}// fin verif pseudo
	if (!empty($mail) AND ! preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mail)){$_SESSION['err'].="mail non-valide !<br/>";}// verif mail
	if($mdp!=$mdp2) {$_SESSION['err'].="Vous avez entré deux mots de passe différents !<br/>";} //verif mdp
	if (!$resp->is_valid){$_SESSION['err'].="Captcha non-valide !<br/>";} //verif captcha
	if (!isset($_POST['cgu'])){$_SESSION['err'].="Vous devez accepter les Conditions Générale d'Utilisation du site !<br/>";}
	
	if($_SESSION['err']=='') //si tout est ok, inscription du membre
		{
		
		if (isset($_POST['gravatar'])){$gravatar=1;$avatar=gravatar($mail_2);}else{$gravatar=0;$avatar='/ressources/img/avatar/default.png';}
		
		$pass=sha1(md5('salt').md5($mdp));
		$key=genpwd(255);
		$req = $bdd->prepare('INSERT INTO users(etat,pseudo, mdp, orig_mail, mail, type, option_gravatar, avatar) 
								VALUES("delete",:pseudo, :mdp, :orig_mail, :mail, "ghost", :option_gravatar, :avatar)');
		$req->execute(array(
			'pseudo' => $pseudo,
			'mdp' => $pass,
			'mail' => $mail_2,
			'orig_mail' => $mail_2,
			'option_gravatar'=>$gravatar,
			'avatar'=>$avatar
			));
		$id=$bdd->lastInsertId();
		$req = $bdd->prepare('INSERT INTO validation(user_id,pseudo, pass, `key`) VALUES('.$id.',:pseudo, :pass, :key)');
		$req->execute(array(
			'pseudo' => $pseudo,
			'pass' => $pass,
			'key' => $key
			));
	   $from = "inscription - donjonfacile.fr";
	   $subject = 'Validation du compte "'.$pseudo.'" - DonjonFacile.fr';
		$body = format_mail(array('Validation du compte '.$pseudo=>
			'Voici le lien de validation pour le compte '.$pseudo.'<br>
			<a href="http://donjonfacile.fr/inscription?pseudo='.$pseudo.'&pass='.$pass.'&key='.$key.'">
			Validation du compte "'.secure::html($pseudo,1).'"
			</a>',
			'Rappel de vos identifiants :'=>
			'<i>pseudo</i> : <b>'.$pseudo.'</b><br>
			<i>mot de passe</i> : <b>'.$mdp.'</b> <br/>',
			'Sécurité'=>'Étant donné que par mesure de sécurité et par respect pour vos informations confidentielles, votre mot de passe n\'est pas enregistré 
			sur nos serveurs, il nous sera impossible de vous le fournir à nouveau. Toutefois un système générant un nouveau mot de passe aléatoire
			est disponible sur le site en cas de perte du mot de passe ou de vol de compte (<a href="http://donjonfacile.fr/oubli_mdp">ici</a>).'
			));
	   $address=array($mail_2 => $pseudo);
	   if(envoi_mail($subject,$body,$address,$from) or TRUE)
		   {
		   $req = $bdd->prepare('INSERT INTO message(auteur_id,destinataire_id, sujet, contenu) VALUES(27,'.$id.', :sujet, :contenu)');
			$req->execute(array(
				'sujet' => 'Bienvenue sur DonjonFacile '.secure::html($pseudo,1).' !',
				'contenu' => '	Vous pouvez dès à présent utiliser toutes les fonctionnalités du site :
								- Création de fiche de personnage
								- Création des objets qui lui sont associés (vous pouvez aussi utiliser ceux déjà créés)
								- regroupement dans les compagnies et de multiples autres fonctionnalités qui pourront je l\'espère vous simplifier la vie.
								
								Toute suggestion, avis ou rapport de bug est le bienvenue.
								
								Nonodrido, administrateur du site.
								'
				));
		   $titre='inscription réussie !';
			$contenu= "	<p>pseudo : ".$pseudo."<br/>E-mail : ".$mail_2."</p>".
						'<p>Un mail de confirmation vous a été envoyé, si vous ne le recevez pas, regardez
			vos spams ou changez d\'adresse mail pour l\'inscription.</p>
			<p>Suite à des problèmes de mail, vous pouvez valider votre compte en cliquant sur le lien suivant :<br>
			<a href="http://donjonfacile.fr/inscription?pseudo='.$pseudo.'&pass='.$pass.'&key='.$key.'">
			Validation du compte "'.secure::html($pseudo,1).'"
			</a></p>';// penser à virer le "OR true"
			if(isset($titre)){$descr= ucfirst($titre).' - ';}else{$descr='';}
			$keywords='';if(isset($titre)){$meta=explode(" ",$titre);foreach($meta as $cle=>$valeur){$keywords.= ', '.$valeur;}}
			if(isset($titre)){$title= ucfirst(secure::html($titre,1)).TITRE_SITE;$_SESSION['titre']=$titre;} else {$title='page sans nom'.TITRE_SITE;$_SESSION['titre']='page sans nom';}
			if(!isset($header)){$header='';}
			if(!isset($fil)){$fil=array('Accueil'=>'accueil');}$fil=fil($fil);
			$notif=notif();
			// $dev="<br/><em> Page générée en ". round((microtime(true) - $debut),5) ." seconde(s).</em><!--<span class='pull-right' id='generate_time'>".($debut*10000)."</span>-->";
			cache::design_render($contenu,$header,$title,$fil,$descr,$keywords,$_SERVER['REQUEST_URI'],$notif,$debut);
			exit();
			}
		else{
			echo 'Erreur critique lors de l\'envoi du mail<br>';
			$req = $bdd->prepare('INSERT INTO message(auteur_id,destinataire_id, sujet, contenu) VALUES(1,27, :sujet, :contenu)');
			$req->execute(array(
				'sujet' => 'Erreur mailing !',
				'contenu' => 'Erreur lors de l\'inscription du membre '.$pseudo
				));
			}
		}
	else{
		echo '<div class="error-box">'.$_SESSION['err'].'</div>';$_SESSION['err']='';
		}
	} 

	verif_uri('/inscription');
// affichage du formulaire d'inscription
	$titre='inscription';
	
	$header='<script type="text/javascript">var RecaptchaOptions={lang: "fr",theme: "white"};</script>
			<script type="text/javascript">
				$(document).ready(function(){
					$("#pseudo").keyup(function(){
						var username = $("#pseudo").val();
						var msgbox = $("#status");
						if(username.length > 3)
							{
							$("#status").html(\'<img alt="loader ajax" src="'.DIR_IMG_HTML.'loader.gif" align="absmiddle">&nbsp;Vérification de la disponibilité...\');
							$.ajax({  
								type: "POST",  
								url: "/ajax/ajax_check_pseudo.php",  
								data: "username="+ username,  
								success: function(msg){
									if(msg == "OK")
									{ 
										$("#pseudo").removeClass("red");
										$("#pseudo").addClass("green");
										msgbox.html(\'<span class="alert alert-success"><img alt="OK" src="'.DIR_IMG_HTML.'yes.png" align="absmiddle"> Disponible</span>\');
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
							$("#status").html(\'<font color="#cc0000">Entrez un pseudo valide (plus de 3 caractères).</font>\');
							}
						return false;
					});
				});
			</script>
			<style type="text/css">
				#status
				{
					font-size:11px;
					margin-left:10px;
				}
				.green
				{
					background-color:#CEFFCE;
				}
				.red
				{
					background-color:#FFD9D9;
				}
			</style>
';
 ?>

	<h1>Inscription :</h1>
	<div class="info-box">
		Le mail de confirmation ne peut pas être envoyé suite à des problèmes coté serveur.<br>
		Le service sera rétabli dès que possible. En attendant le lien de confiramtion vous sera donné directement sur le site. 
		Cliquez dessus pour activer votre compte.
	</div>
	<form action='/inscription' method='post' class="form-horizontal" name="inscription">
		<fieldset><legend>Informations d'inscription </legend>
			<div class="control-group"><label class="control-label" for="pseudo">pseudo : </label>
				<div class="controls"><input  required type='text' id='pseudo' name='pseudo' <?php if(isset($_POST['pseudo'])){echo "value='".$_POST['pseudo']."'";}?>/>
				<span class="help-inline" id="status"></span></div>
			</div>
			<div class="control-group"><label class="control-label" for="mdp">mot de passe : </label>
				<div class="controls"><input  required type='password' id='mdp' name='mdp'<?php if(isset($_POST['mdp'])){echo "value='".$_POST['mdp']."'";}?>/></div>
			</div>
			<div class="control-group"><label class="control-label" for="mdp2">confirmer le mot de passe : </label>
				<div class="controls"><input required type='password' id='mdp2' name='mdp2'<?php if(isset($_POST['mdp2'])){echo "value='".$_POST['mdp2']."'";}?>/></div>
			</div>
			<div class="control-group"><label class="control-label" for="mail">Adresse E-mail : </label>
				<div class="controls"><input type="email" required id='mail' name='mail'<?php if(isset($_POST['mail'])){echo "value='".$_POST['mail']."'";}?>/></div>
			</div>
			<div class="control-group"><div class="controls">
				<label for="gravatar" class="checkbox">
					<input type="checkbox" name="gravatar" id="gravatar"/> 
				Utiliser <a href="http://www.gravatar.com/" target="_blank">gravatar</a> pour mon avatar.</label>
			</div></div>
		</fieldset>
		<fieldset><legend>Validation </legend>
			<div class="control-group"><?php echo recaptcha_get_html(PUBLICKEY);?></div>
			<div class="control-group"><div class="controls">
				<label for="cgu" class="checkbox">
					<input type="checkbox" name="cgu" id="cgu" required /> 
				J'accepte les <a target="_blank" href="/cgu">CGU</a> du site.</label>
			</div></div>
			<div class="control-group"><div class="controls"><a class="btn" onclick="document.forms.inscription.submit()">S'inscrire !</a></div></div>
		</fieldset>
		
	</form>