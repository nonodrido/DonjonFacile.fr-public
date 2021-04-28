<?php
$titre='Récupération de mot de passe';
if(isset($_SESSION['user'])) {header('Location:'.referer());exit();}
if(!empty($_GET['key']))
	{
	$val=$bdd->prepare('SELECT u.id,u.pseudo FROM mdp_oubli o INNER JOIN users u ON u.id=o.user_id WHERE o.key=? AND u.etat!="delete" AND o.etat!="delete" 
						AND (o.date > (NOW() - INTERVAL 2 DAY)) LIMIT 1');
	$val->execute(array($_GET['key']));
	if($val->rowCount() != 0)
		{
		if(!empty($_POST['mdp1']) AND !empty($_POST['mdp2']))
			{
			if($_POST['mdp1']!=$_POST['mdp2']){echo 'Les deux mots de passe sont différents !<br><a href="/oubli_mdp?key='.$_GET['key'].'">Réessayer</a>';}
			else{
				$val=$val->fetch();
				$bdd->exec('UPDATE users SET mdp="'.sha1(md5('salt').md5($_POST['mdp1'])).'" WHERE id='.$val['id']);
				$bdd->exec('UPDATE mdp_oubli SET etat="delete" WHERE user_id='.$val['id']);
				$_SESSION['success'].='Votre nouveau mot de passe a été pris en compte, vous pouvez désormais vous connecter avec celui-ci !';
				header('Location:'.referer());
				echo'Votre nouveau mot de passe a été pris en compte, vous pouvez désormais vous connecter avec celui-ci !';
				header('Location: /');
				exit();
				}
			}
		else{
			echo '	<form action="/oubli_mdp?key='.$_GET['key'].'" class="form-horizontal" method="post">
						<fieldset><legend>Choisissez votre nouveau mot de passe</legend>
							<div class="control-group"><label class="control-label" for="mdp">mot de passe : </label>
								<div class="controls"><input required type="password" id="mdp1" name="mdp1"/></div>
							</div>
							<div class="control-group"><label class="control-label" for="mdp2">confirmer le mot de passe : </label>
								<div class="controls"><input required type="password" id="mdp2" name="mdp2"/></div>
							</div>
							<div class="control-group">
								<div class="controls"><input type="submit" value="Valider" class="btn btn-primary"/></div>
							</div>
						</fieldset>
					</form>';
			}
		}
	else{echo '<div class="warning-box">Le code de déverrouillage est invalide ou trop vieux.<br>
				<!--Bien sûr si vous venez de rentrer votre mot de passe ce message signifie juste que le code que vous avez reçu est désormais
				inactif par mesure de sécurité. <b>Vous pouvez donc dès à présent utiliser votre nouveau mot de passe pour vous connecter.</b>
				--></div>';}
	}
else{
if(!empty($_POST['pseudo']) AND !empty($_POST['mail']))
	{
	$val=$bdd->prepare('SELECT * FROM users WHERE etat!="delete" AND pseudo=? AND mail=?');
	$val->execute(array($_POST['pseudo'],$_POST['mail']));
	if($val->rowCount() != 0)
		{
		$val=$val->fetch();
		$key=sha1(genpwd(25));
		$key=uniqid($key,true);	
		$address=array($val['mail'] => $val['pseudo']);
		$from = "oubli mdp - DonjonFacile.fr";
		$subject = 'DonjonFacile.fr - Récupération du mot de passe du compte "'.$val['pseudo'].'"';
		$body=format_mail(array('Récupération du mot de passe du compte "'.$val['pseudo'].'"'=>
								'Suivez ce lien pour choisir un nouveau mot de passe pour le compte '.$val['pseudo'].' :<br>
								<a href="http://donjonfacile.fr/oubli_mdp?key='.$key.'">J\'ai oublié mon mot de passe</a>',
								
								'Informations'=>'Si ce mail vous a été envoyé alors que vous n\'avez pas suivi la procédure de récupération de mot de passe, merci de contacter
								l\'administrateur du site qui pourra contrôler l\'origine de cette demande.<br/>
								A titre d\'information, cette procédure nécessite uniquement votre pseudo et votre adresse mail, qui est par défaut cachée sur votre profil
								mais qui est facilement accessible.<br/>
								Toutefois tout abus signalé peut conduire à un ban provisoire voire définitif des comptes et/ou de l\'ip des utilisateurs abusant du système 
								en cas de signalement, n\'hésitez donc pas à rapporter ce genre de problème.'
								));
	   if(envoi_mail($subject,$body,$address,$from))
			{
			include("includes/geoloc.php");
			$_SESSION['success'].='Message envoyé à '.$_POST['mail'].' !';
			$bdd->exec('INSERT INTO mdp_oubli SET user_id='.$val['id'].',ip="'.get_ip().'",
						nav="'.$_SERVER["HTTP_USER_AGENT"].'",geoloc="'.$geoloc.'",`key`="'.$key.'"');//serialize(get_browser(null, true))
			}
		else{$_SESSION['err'].='Erreur lors de l\'envoi du mail, veuillez réassayer plus tard.';}
		}
	else{$_SESSION['success'].='Message envoyé à '.$_POST['mail'].' !';}
	}
verif_uri('/oubli_mdp');
?>
<p>Si vous avez oublié votre mot de passe, 
vous pouvez choisir un nouveau mot de passe si vous avez fournis une adresse E-Mail valide.<br/>
Pour cela, remplissez les champs suivants avec votre pseudo et l'adresse mail que vous aviez fournis.<br/>
Si jamais vous avez déjà effectué cette opération mais que le mail ne vous est pas parvenu, verifiez vos courriers indésirables.</p>
<form class="form-horizontal" method="POST">
  <div class="control-group">
    <label class="control-label" for="pseudo">Pseudo : </label>
    <div class="controls">
      <input id="pseudo" name="pseudo" type="text"/>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="mail"> E-Mail : </label>
    <div class="controls">
      <input id="mail" name="mail" type="email"/>
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
	  <input type="submit" class="btn" value="Récupérer le mot de passe"/>
	</div>
  </div>
</form>
<div class="warning-box">Tout abus signalé peut conduire à un ban provisoire voire définitif des comptes et/ou de l'ip des utilisateurs
 abusant du système en cas de signalement.
 </div>
 <?php } ?>