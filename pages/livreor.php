<?php
$titre='Livre d\'or';
//script pour les feedbacks
if(!empty($_POST['feedback_txt']))
	{
	if(isset($_SESSION['user']))
		{
		$bdd->prepare('INSERT INTO livreor (txt, uri, user, type, ip,user_id) VALUES(:txt, :uri, :user, "user", :ip, :user_id)')
		->execute(array('txt'=>$_POST['feedback_txt'],'uri'=>$_POST['feedback_uri'],'user'=>$_SESSION['pseudo'],'ip'=>get_ip(),'user_id'=>$_SESSION['user_id']));
		$_SESSION['success'].='Votre message a bien été enregistré.';
		@envoi_mail('Nouveau message de '.secure::html($_SESSION['pseudo'],1),'Nouveau message dans le livre d\'or :<br/>'.secure::html($_POST['feedback_txt']),array('contact@donjonfacile.fr'=>'contact@donjonfacile.fr'));
		unset($_POST);
		}
	else{
		if(!empty($_POST['feedback_pseudo']) AND !empty($_POST['feedback_mail']) AND filter_var($_POST['feedback_mail'], FILTER_VALIDATE_EMAIL))
			{
			if(isset($_POST['feedback_verif']) AND isset($_POST['feedback_test']) AND $_POST['feedback_verif']==$_POST['feedback_test'])
				{
				$bdd->prepare('INSERT INTO livreor (txt, uri, user, mail, type, ip) VALUES(:txt, :uri, :user, :mail, "invite", :ip)')
				->execute(array('txt'=>$_POST['feedback_txt'],'uri'=>$_POST['feedback_uri'],'user'=>$_POST['feedback_pseudo'],'ip'=>get_ip(),'mail'=>$_POST['feedback_mail']));
				$_SESSION['success'].='Votre message a bien été enregistré.';
				envoi_mail('Nouveau message de '.secure::html($_POST['feedback_pseudo'],1),'Nouveau message dans le livre d\'or :<br/>'.secure::html($_POST['feedback_txt']),array('contact@donjonfacile.fr'=>'contact@donjonfacile.fr'));
				unset($_POST); 
				}
			else{$_SESSION['err'].= 'La réponse à la question de sécurité est fausse !';}
			}
		else{$_SESSION['err'].= 'Pseudo et/ou mail vide ou invalide.';}
		}
	}
elseif(!empty($_POST['delete_id']) AND isadmin())
	{
	$bdd->exec('UPDATE livreor SET etat="delete" WHERE id='.$_POST['delete_id']);
	$_SESSION['success'].='Message supprimé.';
	}
elseif(!empty($_POST['message_id']) AND !empty($_POST['message_txt']) AND isadmin())
	{
	$bdd->prepare('INSERT INTO livreor (txt, ref_id, uri, user, mail, type, ip) VALUES(:txt, :ref_id, :uri, :user, :mail, "user", :ip)')
				->execute(array('txt'=>$_POST['message_txt'],'ref_id'=>$_POST['message_id'],'uri'=>'/livreor','user'=>$_SESSION['pseudo'],'ip'=>get_ip(),'mail'=>'REP ADMIN'));
	$reponse=$bdd->query('SELECT * FROM livreor where id='.$_POST['message_id'].' ORDER BY date DESC LIMIT 1')->fetch();
	if(!empty($reponse['type']) AND $reponse['type']=='user')
		{
		$req = $bdd->prepare('INSERT INTO message(auteur_id,destinataire_id, sujet, contenu) VALUES('.$_SESSION['user_id'].','.$reponse['user_id'].', :sujet, :contenu)');
			$req->execute(array(
				'sujet' => 'Un administrateur vous a répondu sur le livre d\'or !',
				'contenu' => '	[url=/livreor#livreor_'.$reponse['id'].']Cliquez içi pour accéder à votre message.[/url]'
				));
		}
	elseif(!empty($reponse['type']) AND $reponse['type']=='invite')
		{
		@envoi_mail('Un administrateur de DonjonFacile.fr vous a répondu sur le livre d\'or !','<a href="http://donjonfacile.fr/livreor#livreor_'.$reponse['id'].'">Cliquez içi pour accéder à votre message.</a>',array($reponse['mail']=>$reponse['user']));
		}
	$_SESSION['success'].='Réponse enregistrée.';
	}
?>
<form id="feedback_main" method="post" class="pull-right" style="margin-left:10px;">
	<fieldset><legend>Donnez votre avis</legend>
	<input type="hidden" value="<?php if(!empty($_POST['feedback_uri'])){echo secure::html($_POST['feedback_uri'],1);}else{echo $_SERVER['REQUEST_URI'];} ?>" name="feedback_uri">
	<?php if(!isset($_SESSION['user'])){ ?>
	<input size="25" type="text" name="feedback_pseudo" placeholder="pseudo" <?php if(!empty($_POST['feedback_pseudo'])){echo 'value="'.secure::html($_POST['feedback_pseudo'],1).'" ';} ?>required/><br/>
	<input size="25" type="email" name="feedback_mail" placeholder="email" <?php if(!empty($_POST['feedback_mail'])){echo 'value="'.secure::html($_POST['feedback_mail'],1).'" ';} ?>required/><br/>
	<?php } ?>
	<textarea id="feedback_txt" class="input-xlarge" name="feedback_txt" placeholder="Vous êtes invités à donner votre avis pour permettre d'améliorer ce site et de le rendre plus proche de vos besoins. Seul les avis constructifs seront acceptés. Le bbcode est autorisé."
	rows="10" cols="50" required><?php if(!empty($_POST['feedback_txt'])){echo secure::html($_POST['feedback_txt'],1);} ?></textarea><br/>
	<?php if(!isset($_SESSION['user'])){ ?>
	<input type="hidden" value="<?php $verif1=rand(1,9);$verif2=rand(1,9);echo ($verif1+$verif2); ?>" name="feedback_verif">
	<?php echo $verif1.' + '.$verif2.' ? '; ?><input type="number" name="feedback_test" size="5" placeholder="résultat" required/><br/>
	<?php } ?>
	<div style="text-align:center;"><a class="btn" title="L'adresse de la page où vous vous trouvez sera enregistrée." onclick="$('#feedback_main').submit();"><i class="icon-edit"></i> Envoyer</a></div>
	<?php if(isuser()){echo '<br><div style="text-align:center;"><a class="btn" href="/messagerie?username=nonodrido"><i class="icon-envelope"></i> Mp l\'administrateur</a></div>';}?>
	</fieldset>
</form>
<?php
verif_uri('/livreor');
$val=$bdd->query('SELECT * FROM livreor WHERE etat!="delete" AND ref_id=0 ORDER BY date DESC,id DESC LIMIT 150');
while($q=$val->fetch())
	{
	if($q['type']=="user")
		{
		echo '<blockquote id="livreor_'.$q['id'].'">
			  <p style="font-size: 1.1em;">'.secure::html($q['txt']).'</p>
			  <small>
				<a href="/membre/'.$q['user_id'].'/'.to_url($q['user']).'"><cite title="Source Title"><b>'.secure::html($q['user'],1).'</b></cite></a>
				<time data-date="'.$q['date'].'" title="'.date_bdd($q['date']).'">Le '.date_bdd($q['date']).'</time>
				<a rel="nofollow" href="'.secure::html($q['uri'],1).'">#'.secure::html($q['uri'],1).'</a>
			';
		}
	elseif($q['type']=="invite")
		{
		echo '<blockquote id="livreor_'.$q['id'].'">
			  <p style="font-size: 1.1em;">'.secure::html($q['txt']).'</p>
			  <small>
				<cite title="Source Title"><b>'.secure::html($q['user'],1).' (invité(e)) </b></cite>
				<time data-date="'.$q['date'].'" title="'.date_bdd($q['date']).'">Le '.date_bdd($q['date']).'</time>
				<a rel="nofollow" href="'.secure::html($q['uri'],1).'">#'.secure::html($q['uri'],1).'</a>
			';
		}
	if(isadmin())
		{
		echo ' ['.$q['ip'].']
		        <form action="'.$_SERVER['REQUEST_URI'].'" style="display:inline-block;" method="post">
				<input type="hidden" name="delete_id" value="'.$q['id'].'"/>
				<input type="submit" title="modérer" value="X" class="btn btn-mini btn-danger"/>
				<a  class="btn btn-mini btn-info" title="Répondre" onclick="$(\'#livreor_'.$q['id'].'_rep\').toggleClass(\'hidden\');return false;"><i class="icon-share-alt icon-white"></i></a>
				</form>
				<form class="hidden" action="#livreor_'.$q['id'].'" method="post" id="livreor_'.$q['id'].'_rep">
					<textarea name="message_txt"></textarea>
					<input type="hidden" name="message_id" value="'.$q['id'].'"/><br>
					<input type="submit" class="btn" value="Répondre" />
				</form>
				';
		}
	echo '</small>';
	$reponse=$bdd->query('SELECT * FROM livreor where ref_id='.$q['id'].' AND etat!="delete" ORDER BY date DESC LIMIT 1')->fetch();
	if(!empty($reponse['txt']))
		{
		echo '	<p style="font-size: 1em;margin-left:50px;">
					<i>Réponse de l\'administrateur ('.secure::html($reponse['user'],1).') :</i><br>
					<span style="padding-left:0;">'.secure::html($reponse['txt']).'</span>
					<small><time data-date="'.$reponse['date'].'" title="'.date_bdd($reponse['date']).'">Le '.date_bdd($reponse['date']).'</time></small>
				</p>';
		}
	echo '</blockquote>';
	}