<?php
$titre='FAQ';
if(isset($_GET['id']) AND isadmin())
	{
	$faq=$bdd->query('SELECT * FROM faq WHERE etat!="delete" AND id='.$_GET['id'])->fetch();
	if(!empty($faq))
		{
		if(isset($_POST['titre']) AND isset($_POST['contenu']))
			{
			$bdd->exec('UPDATE faq SET titre="'.$_POST['titre'].'",contenu="'.$_POST['contenu'].'" WHERE id='.$_GET['id']);
			$_SESSION['success'].='FAQ mise à jour avec succès';
			header('Location:/faq');
			}
		if(isset($_POST['delete']))
			{
			$bdd->exec('UPDATE faq SET etat="delete" WHERE id='.$_GET['id']);
			$_SESSION['success'].='FAQ mise à jour avec succès';
			header('Location:/faq');
			}
		echo '	<form action="'.$_SERVER['REQUEST_URI'].'" method="post" class="form-horizontal">
						<fieldset><legend>Modifier une question</legend>
						<div class="control-group">
						  <label class="control-label">Question</label>
						  <div class="controls">
							<input id="titre" name="titre" type="text" placeholder="" value="'.$faq['titre'].'" class="input-xlarge" required="">
						  </div>
						</div>
						<div class="control-group">
						  <label class="control-label">Réponse à la question</label>
						  <div class="controls">                     
							<textarea id="contenu" class="span4" name="contenu">'.$faq['contenu'].'</textarea>
						  </div>
						</div>
						<div class="control-group">
						  <div class="controls">
							<input type="submit" class="btn btn-primary" value="Modifier"/>
						  </div>
						</div>
						</fieldset>
					</form>
					<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
						<input type="hidden" name="delete" value="delete"/>
						<input type="submit" class="btn btn-danger" value="Supprimer"/>
					</form>
					';
		}
	else{echo 'Cet élément n\'existe pas ou plus !<br>';include('pages/404.php');$error=1;}
	}
else{
	verif_uri('/faq');
	if(isadmin() AND isset($_POST['titre']) AND isset($_POST['contenu']))
		{
		$bdd->exec('INSERT INTO faq VALUES(NULL,NOW(),"default","'.$_POST['titre'].'","'.$_POST['contenu'].'")');
		$_SESSION['success'].='FAQ mise à jour avec succès';
		}
	$faq=$bdd->query('SELECT * FROM faq WHERE etat!="delete" ORDER BY titre')->fetchAll();
	echo '<div class="accordion" id="accordionfaq">
			  <div class="accordion-group">
				<div class="accordion-heading">
				  <h4><a class="accordion-toggle black" data-toggle="collapse" data-parent="#accordionfaq" href="#collapse0">
					(FAQ) Foire aux questions
				  </a></h4>
				</div>
				<div id="collapse0" class="accordion-body collapse in">
				  <div class="accordion-inner">
					Bienvenue dans la foire au question de ce site !<br>
					Vous trouverez ici la plupart des questions fréquemment posée au sujet du site et de ses fonctinnalitées.<br>
					Si votre question concerne plutôt le jeu de rôle, je vous conseille aussi de lire la <a href="http://www.naheulbeuk.com/foire-aux-questions.htm">
					faq officielle</a> du jeu de rôle écrite par PoC himself.
					Croyez moi, la réponse s\'y trouve certainement !<br><br>
					Si jamais vous avez une question dont vous n\'avez pas la réponse ici, vous pouvez me <a href="/contact">contacter</a> via 
					le formulaire du site ou directement depuis mon mail : <a href="mailto:contact@donjonfacile.fr">contact@donjonfacile.fr</a>
				  </div>
				</div>
			  </div>';
	$i=0;
	foreach($faq as $cle=>$val)
		{
		$i++;
		echo '	<div class="accordion-group">
					<div class="accordion-heading">
					  <h4><a class="accordion-toggle black" data-toggle="collapse" data-parent="#accordionfaq" href="#collapse'.$i.'">
						'.secure::html($val['titre'],1).'
					  </a></h4></div>
					<div id="collapse'.$i.'" class="accordion-body collapse">
					  <div class="accordion-inner">
						'.$val['contenu'];
		if(isadmin()){echo '<div class="pull-right"><a class="btn" style="margin:10px" href="/faq/'.$val['id'].'">Modifier</a></div>';}
		echo '		  </div>
					</div>
				  </div>';
		}
	echo '</div>';
	if(isadmin())
		{
		echo '	<form action="'.$_SERVER['REQUEST_URI'].'" method="post" class="form-horizontal">
					<fieldset><legend>Ajouter une question</legend>
					<div class="control-group">
					  <label class="control-label">Question</label>
					  <div class="controls">
						<input id="titre" name="titre" type="text" placeholder="" class="input-xlarge" required="">
					  </div>
					</div>
					<div class="control-group">
					  <label class="control-label">Réponse à la question</label>
					  <div class="controls">                     
						<textarea id="contenu" name="contenu"></textarea>
					  </div>
					</div>
					<div class="control-group">
					  <div class="controls">
						<input type="submit" class="btn btn-primary" value="Ajouter"/>
					  </div>
					</div>
					</fieldset>
				</form>
				';
		}
	}