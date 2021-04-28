<?php 
$titre='Contact';
if(!empty($_POST['message']) AND !empty($_POST['sujet']) AND !empty($_POST['motif'])
	AND (isuser() OR (!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))))
	{
	$nom = (isuser()) ? $_SESSION['pseudo'] : $_POST['pseudo'].' (visiteur)';
	$mail = (isuser()) ? $_SESSION['mail'] : $_POST['mail'];
	if(envoi_mail(
		stripslashes($_POST['motif'].' - '.$_POST['sujet'].' - formulaire de contact'),
		stripslashes(secure::html($_POST['message'])),
		array('contact@donjonfacile.fr'=>'nonodrido'),
		$nom,
		$mail
		))
	{$_SESSION['success'].='Message envoyé !';}
	else{
		$req = $bdd->prepare('INSERT INTO message(auteur_id,destinataire_id, sujet, contenu) VALUES(1,27, :sujet, :contenu)');
		$req->execute(array(
			'sujet' => 'Erreur mailing !',
			'contenu' => 'Erreur lors de l\'envoi d\'un message !
							'.secure::html($_POST['motif'].' - '.$_POST['sujet'].' - '.$_POST['message'])
			));
		}
	}
?>
<h1>Contacter l'administrateur</h1>

<?php if(isuser()){echo '<p><a href="/messagerie?username=nonodrido" class="btn"><i class="icon-envelope"></i> Mp l\'administrateur</a></p>';} ?>

<!--<div class="info-box">
	L'envoi de mail ne fonctionne plus depuis le site : pour me contacter utilisez votre client mail en cliquant sur ce lien :
	<a href="mailto:contact@donjonfacile.fr">contact@donjonfacile.fr</a>
</div>-->
<form class="form-horizontal" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
  <?php if(!isuser()){ ?>
  <fieldset><legend>Vos coordonnées :</legend>
  <div class="control-group">
    <label class="control-label" for="pseudo">Pseudo</label>
    <div class="controls">
      <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo" required>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="mail">Email</label>
    <div class="controls">
      <input type="email" id="mail" name="mail" placeholder="Email" required>
    </div>
  </div>
  </fieldset>
  <?php }?>
  <fieldset><legend>Votre message :</legend>
  <div class="control-group">
    <label class="control-label" for="motif">Motif</label>
    <div class="controls">
      <select id="motif" name="motif" required>
	    <option>Suggestion</option>
		<option>Rapport de Bug</option>
		<option>Réclamation</option>
		<option>Divers</option>
	  </select>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="sujet">Sujet</label>
    <div class="controls">
      <input type="text" class="input-xlarge" id="sujet" name="sujet" placeholder="Précisez le sujet de votre message" required>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="message">Message</label>
    <div class="controls">
      <textarea class="span4" rows="5" placeholder="Votre message" id="message" name="message" required></textarea>
    </div>
  </div>
  </fieldset>
  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn btn-primary"><i class="icon-envelope icon-white"></i> Envoyer</button>
    </div>
  </div>
</form>
<div class="info-box">
	Si l'envoi de mail ne fonctionne plus depuis le site, pour me contacter malgrès tout utilisez votre client mail en cliquant sur ce lien :
	<a href="mailto:contact@donjonfacile.fr">contact@donjonfacile.fr</a>
</div>