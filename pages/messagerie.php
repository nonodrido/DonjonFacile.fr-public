<?php
$titre='Messagerie';
if(isuser())
	{
	if(isset($_GET['id']))
		{
		$req=$bdd->query('	SELECT m.*,u_a.pseudo a_pseudo,u_d.pseudo d_pseudo
							FROM message m
							LEFT JOIN users u_a ON u_a.id = m.auteur_id
							LEFT JOIN users u_d ON u_d.id = m.destinataire_id
							WHERE m.etat!="delete" AND m.id='.$_GET['id'].'
						');
		$val=$req->fetch();
		if($req->rowCount()==0 OR $val['prec_id']!=0 OR ($val['auteur_id']!=$_SESSION['user_id'] AND $val['destinataire_id']!=$_SESSION['user_id'] AND !isadmin()))
			{
			if($val['prec_id']!=0)
				{
				header('Location:/messagerie/'.$val['prec_id']);
				}
			echo 'Ce message n\'existe pas ou ne vous concerne pas !<br><br>
					<a class="btn btn-primary" href="/messagerie">Retour à ma messagerie</a>';
			}
		else{
			verif_uri('/messagerie/'.$val['id'].'/'.to_url($val['sujet']));
			if(isset($_GET['delete']))//// suppression fil discussion
				{
				if($_SESSION['user_id']==$val['auteur_id']){$delete_auth='auteur_delete';}else{$delete_auth='destinataire_delete';}
				$req=$bdd->exec('UPDATE message SET '.$delete_auth.'=1 WHERE id='.$_GET['id'].' OR prec_id='.$_GET['id']);
				$_SESSION['success'].='Fil supprimé avec succès.';
				header('Location: /messagerie');
				}
			if(!empty($_POST['contenu']))//// ajout d'un nouveau message
				{
				if($_SESSION['user_id']!=$val['auteur_id']){$destinataire=$val['auteur_id'];}else{$destinataire=$val['destinataire_id'];}
				$req=$bdd->prepare('INSERT INTO message (auteur_id,destinataire_id,prec_id,contenu,sujet) VALUES('.$_SESSION['user_id'].','.$destinataire.','.$val['id'].',?,?)');
				$req->execute(array($_POST['contenu'],$val['sujet']));
				$_SESSION['success'].='Réponse ajoutée avec succès.';
				}
			//marquage comme lu
			$bdd->exec('UPDATE message SET lu=1 WHERE (id='.$_GET['id'].' OR prec_id='.$_GET['id'].') AND destinataire_id='.$_SESSION['user_id']);
			$titre='Messagerie : '.secure::html($val['sujet'],1);
			echo '	<a class="btn btn-primary" href="/messagerie">Retour à ma messagerie</a>
					<!--<a class="btn btn-danger" href="?delete">Supprimer ce fil de discussion</a>--><br><br>
					<div id="mess_'.$val['id'].'">
						<table class="table table-hover table-bordered table-condensed">
							<tr><th style="width:75px;">De :</th><td><a href="/membre/'.$val['auteur_id'].'/'.to_url($val['a_pseudo']).'">'.secure::html($val['a_pseudo'],1).'</a></td>
							<th>À :</th><td><a href="/membre/'.$val['destinataire_id'].'/'.to_url($val['d_pseudo']).'">'.secure::html($val['d_pseudo'],1).'</a></td></tr>
							<tr><th>Posté :</th><td><time class="date" data-date="'.$val["date"].'" title="'.date_bdd($val["date"]).'">'.date_bdd($val["date"],'d/m/Y').'</time></td>
							<th>Sujet :</td><th><b>'.secure::html($val['sujet'],1).'</b></td></tr>
							<tr><td colspan="4">'.secure::html($val['contenu']).'</td></tr>
						</table>
					</div>';
			$req=$bdd->query('	SELECT m.*,u_a.pseudo a_pseudo
							FROM message m
							LEFT JOIN users u_a ON u_a.id = m.auteur_id
							WHERE m.etat!="delete" AND m.prec_id='.$val['id'].'
							ORDER BY date 
							');
			while($val2=$req->fetch())
				{
				echo '	<div id="mess_'.$val2["id"].'" style="margin-left:50px;">
							<table class="table table-hover table-bordered table-condensed">
								<tr>
									<th style="width:75px;">De :</th>
									<td style="width:250px;"><a href="/membre/'.$val2['auteur_id'].'/'.to_url($val2['a_pseudo']).'">'.secure::html($val2['a_pseudo'],1).'</a></td>
									<th  style="width:75px;">Posté :</th>
									<td><time class="date" data-date="'.$val2["date"].'" title="'.date_bdd($val2["date"]).'">'.date_bdd($val2["date"],'d/m/Y').'</time></td>
								</tr>
								<tr><td colspan="4">'.secure::html($val2['contenu']).'</td></tr>
							</table>
						</div>';
				}			
				echo '	<form method="post" action="'.$_SERVER['REQUEST_URI'].'" style="margin-left:60px;">
						<fieldset><legend>Répondre</legend>
							<p style="margin:25px;">
							<textarea name="contenu" class="span4" rows="10"></textarea>
							<br>
							<input type="submit" class="btn btn-primary" value="Répondre"/>
							</p>
						</fieldset>
					</form>';
			}
		
		}
	else{//////////////////////////////  PAGE DE MESSAGERIE GENERALE  ///////////////////////////////////
		verif_uri('/messagerie');
		if(!empty($_POST['destinataire']) AND !empty($_POST['sujet']) AND !empty($_POST['contenu']))
			{
			if(!isadmin() OR !isset($_POST['send_to_all']))
				{
				$req=$bdd->prepare('SELECT id FROM users WHERE pseudo=? AND etat!="delete"');
				$req->execute(array($_POST['destinataire']));
				if($req->rowCount()==0)
					{
					$_SESSION['err'].='L\'utilisateur ciblé n\'existe pas ou plus.';
					}
				else{
					$dest=$req->fetch();
					if($_SESSION['user_id']!=$dest['id'])
						{
						$req=$bdd->prepare('INSERT INTO message (auteur_id,destinataire_id,sujet,contenu) VALUES('.$_SESSION['user_id'].','.$dest['id'].',?,?)');
						$req->execute(array($_POST['sujet'],$_POST['contenu']));
						$_SESSION['success'].='Message envoyé à '.$_POST['destinataire'].' !';
						}
					else{$_SESSION['info'].='Parlez plutôt à haute voix, vous vous entendrez mieux que par internet je vous assure !';}
					}
				}	
			else{
				$req = $bdd->exec('INSERT INTO message(auteur_id,destinataire_id, sujet, contenu) 
										SELECT '.$_SESSION['user_id'].',id as destinataire_id, "'.str_replace('"','\"',$_POST['sujet']).'","'.str_replace('"','\"',$_POST['contenu']).'" 
										FROM users');
				}
			}
		if(!empty($_GET['username'])){$username=' value="'.$_GET['username'].'" ';}else{$username='';}
		echo '	<form action="'.$_SERVER['REQUEST_URI'].'" method="post" class="form-horizontal">
					<fieldset><legend>Nouveau message</legend>
					<div class="control-group">
					<label class="control-label" for="destinataire">Destinataire</label>
					<div class="controls">
					  <input type="text" autocomplete="off" id="destinataire" '.$username.' class="ajax-typeahead-pseudo" name="destinataire" placeholder="pseudo">
					</div>
				  </div>
				  <div class="control-group">
					<label class="control-label" for="sujet">Sujet</label>
					<div class="controls">
					  <input type="text" id="sujet" name="sujet" placeholder="Sujet">
					</div>
				  </div>
				  <div class="control-group">
					<label class="control-label" for="contenu">Message</label>
					<div class="controls">
					  <textarea class="span4" rows=5 id="contenu" name="contenu" placeholder="message"></textarea>
					</div>
				  </div>
				  <div class="control-group">
					<div class="controls">';
		if(isadmin()){echo'<label class="checkbox"><input type="checkbox" name="send_to_all" value="all"/> Envoi global</label>';}
		echo'		  <button type="submit" class="btn">Envoyer</button>
					</div>
				  </div>
				  </fieldset>
				</form>';
		////// SELECTION DES MESSAGES RECUS //////
		$req=$bdd->query('	SELECT m.*,u_d.pseudo d_pseudo
						FROM message m
						LEFT JOIN users u_d ON u_d.id = m.auteur_id
						WHERE m.etat!="delete" AND m.destinataire_delete!=1 AND ((m.prec_id=0 AND m.destinataire_id='.$_SESSION['user_id'].') 
						OR (m.prec_id!=0 AND m.destinataire_id='.$_SESSION['user_id'].'))
						ORDER BY date DESC
						');
		echo '	<fieldset><legend>Boite de réception</legend>
					<table class="jtable table table-hover table-striped table-bordered table-condensed">
						<thead><tr><th>Date</th><th>Sujet</th><th>De</th><th>Action</th></tr></thead><tbody>';
		$array_id=array();
		while($val=$req->fetch())
			{
			if(!in_array($val["id"],$array_id) AND !in_array($val["prec_id"],$array_id))
				{
				$last_date=$bdd->query('SELECT * FROM message WHERE (id='.$val['id'].' OR prec_id='.$val['id'].') AND etat!="delete" ORDER BY date DESC')->fetch();
				$val['date']=$last_date['date'];
				if($last_date['lu']==0 AND $last_date['destinataire_id']==$_SESSION['user_id']){$lu='<i class="icon-envelope" title="message non-lu"></i> ';}else{$lu='';}
				if($val["prec_id"]!=0){$t=$val["prec_id"];}else{$t=$val["id"];}
				echo '<tr>
						<td>'.$lu.'<time class="date" data-date="'.$val["date"].'" title="'.date_bdd($val["date"]).'">'.date_bdd($val["date"],'d/m/Y').'</time></td>
						<td><a href="/messagerie/'.$t.'/'.to_url($val['sujet']).'">'.secure::html($val['sujet'],1).'</a></td>
						<td><a href="/membre/'.$val['destinataire_id'].'/'.to_url($val['d_pseudo']).'">'.secure::html($val['d_pseudo'],1).'</a></td>
						<td><a class="btn btn-info" href="/messagerie/'.$t.'/'.to_url($val['sujet']).'">Lire la conversation</a></td>
					  </tr>';
				if($val["prec_id"]!=0){$t=$val["prec_id"];}else{$t=$val["id"];}
				$array_id[]=$t;
				}
			}
		echo '		</tbody></table>
				</fieldset>';
		//// SELECTION MESSAGES ENVOYES /////
		$req=$bdd->query('	SELECT m.*,u_d.pseudo d_pseudo
						FROM message m
						LEFT JOIN users u_d ON u_d.id = m.destinataire_id
						WHERE m.etat!="delete" AND m.auteur_delete!=1 AND m.prec_id=0 AND m.auteur_id='.$_SESSION['user_id'].'
						ORDER BY date DESC
						');
		echo '	<fieldset><legend>Boite d\'envoi</legend>
					<table class="jtable table table-hover table-striped table-bordered table-condensed">
						<thead><tr><th>Date</th><th>Sujet</th><th>À</th><th>Action</th></tr></thead><tbody>';
		while($val=$req->fetch())
			{
			$last_date=$bdd->query('SELECT * FROM message WHERE (id='.$val['id'].' OR prec_id='.$val['id'].') AND etat!="delete" ORDER BY date DESC')->fetch();
			$val['date']=$last_date['date'];
			echo '<tr>
					<td><time class="date" data-date="'.$val["date"].'" title="'.date_bdd($val["date"]).'">'.date_bdd($val["date"],'d/m/Y').'</time></td>
					<td><a href="/messagerie/'.$val['id'].'/'.to_url($val['sujet']).'">'.secure::html($val['sujet'],1).'</a></td>
					<td><a href="/membre/'.$val['destinataire_id'].'/'.to_url($val['d_pseudo']).'">'.secure::html($val['d_pseudo'],1).'</a></td>
					<td><a class="btn btn-info" href="/messagerie/'.$val['id'].'/'.to_url($val['sujet']).'">Lire la conversation</a></td>
				  </tr>';
			}
		echo '		</tbody></table>
				</fieldset>
				<div class="info-box">Les conversations datant de plus de 4 mois seront automatiquement supprimées.</div>';
		}
	}
else{echo ACCES_REFUSE_INVITE;$header='<meta name="robots" content="noindex,follow" />';}