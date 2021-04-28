<?php
$titre='Page d\'administration';
if(isadmin('validateur'))
	{
	if(isset($_GET['mode']) AND $_GET['mode']=='item_officiel')
		{///////////////////////////  GESTION DES OBJETS SUGGERES  ///////////////////////////
		if(isset($_POST['item_id']) AND isset($_POST['action']) AND isset($_POST['auteur_id']))
			{
			if($_POST['action']=='accepter')
				{
				$bdd->exec('UPDATE item SET auteur_id=1,date=NOW(), etat="default" WHERE id='.$_POST['item_id']);
				$sujet='Objet officiel accepté !';
				$contenu='	L\'objet que vous aviez proposé a été ajouté à la liste des objets officiels. Par conséquent il ne vous appartient 
							plus et il vous sera désormais impossible de le modifier.
							Je vous remercie pour votre participation au développement du site.
							
							Lien vers l\'objet concerné : [url=/item/'.$_POST['item_id'].']cliquez içi[/url]
							';
				$val=$bdd->query('SELECT name FROM item WHERE id='.(int)$_POST['item_id'])->fetch();
				$q=$bdd->prepare('SELECT * FROM item WHERE etat!="delete" AND name=:name AND id!='.(int)$_POST['item_id']);
				$q->bindValue(':name', $val['name']);
				$q->execute();$q=$q->fetchAll();
				foreach($q as $cle=>$val2)
					{
					$q2=$bdd->prepare('UPDATE item SET name=:name WHERE id='.$val2['id']);
					$q2->bindValue(':name', $val['name'].' (nom à modifier car non-officiel)');
					$q2->execute();
					$req = $bdd->prepare('INSERT INTO message(auteur_id,destinataire_id, sujet, contenu) VALUES('.$_SESSION['user_id'].',:id, :sujet, :contenu)');
								$sujet='Transfert de personnage vers votre compte';
								$contenu='L\'objet suivant a le même nom qu\'un objet qui est devenu officiel, son nom a donc été modifié.
								[url=/item/'.$val2['id'].']Cliquez ici pour accéder à cet objet[/url]';
								$req->execute(array(
									'sujet' => $sujet,
									'contenu' => $contenu,
									'id' => $val2['user_id']
									));
					}
				}
			elseif($_POST['action']=='refuser')
				{
				$bdd->exec('UPDATE item SET etat="default" WHERE id='.$_POST['item_id']);
				$sujet='Objet officiel refusé';
				$contenu='	L\'objet que vous aviez proposé a été refusé dans la liste des objets officiels. Par conséquent il vous appartient 
							toujours et vous serez désormais libre de le modifier à votre guise.
							Je vous remercie toutefois pour votre participation au développement du site.
							';
				if(!empty($_POST['motif'])){$contenu.='Motif du refus : '.$_POST['motif'];}
				$contenu.='
							Lien vers l\'objet concerné : [url=/item/'.$_POST['item_id'].']cliquez içi[/url]
							';
				}
			$req = $bdd->prepare('INSERT INTO message(auteur_id,destinataire_id, sujet, contenu) VALUES('.$_SESSION['user_id'].',:id, :sujet, :contenu)');
			$req->execute(array(
				'sujet' => $sujet,
				'contenu' => $contenu,
				'id' => $_POST['auteur_id']
				));
			}
		$titre='Gestion des items officiels suggérés';
		$val=$bdd->query('SELECT COUNT( * ) AS  `nbre` FROM  `item` WHERE etat="officiel"')->fetch();
		if($val['nbre']==0){$_SESSION['warning'].='Aucun contenu à valider !';header('Location: /admin');exit;}
		echo '<h1>Gestion du contenu officiel suggéré ('.$val['nbre'].')</h1>';
		$req=$bdd->query('SELECT id FROM  `item` WHERE etat="officiel"');
		while($val=$req->fetch())
			{
			echo '<div class="well">';
			$item=new item($val['id']);
			$item->tableau();
			$val=$bdd->prepare('SELECT id FROM  `item` WHERE auteur_id=1 AND etat!="delete" AND name LIKE ?');
			$val->execute(array('%'.trim($item->name).'%'));
			if($val->rowCount()!=0){echo '<div class="alert">Cet objet existe probablement déjà dans la liste officielle.</div>';}
			echo '	<form method="post" action="'.$_SERVER['REQUEST_URI'].'" class="form-inline">
						<input type="hidden" value="'.$item->id.'" name="item_id"/>
						<input type="hidden" id="auteur_id" name="auteur_id" value="'.$item->auteur_id.'"/>
						<label class="radio">
						  <input type="radio" name="action" value="accepter">
						  Accepter
						</label>
						<label class="radio">
						  <input type="radio" name="action" value="refuser" checked>
						  Refuser
						</label> 
						<label>
						   Motif : 
						   <input type="text" name="motif" placeholder="motif du refus (optionnel)">
						</label>
						<button type="submit" class="btn">Valider</button>
						<span class="pull-right">
							<a class="btn btn-primary" href="/item/'.$item->id.'">Voir l\'objet</a>
							<a class="btn btn-danger" href="/item/delete/'.$item->id.'">Supprimer l\'objet</a>
							<a class="btn btn-danger" href="/membre/ban/'.$item->auteur_id.'">Bannir l\'auteur</a>
						</span>
					</form>
			
			</div>';
			}
		}
	elseif(isset($_GET['mode']) AND $_GET['mode']=='fichiers' AND isadmin())
		{
		$titre='Gestion des fichiers officiels';
		$pre_url='http://www.naheulbeuk.com/jdr-docs/';
		$data= file_get_contents($pre_url);
		preg_match_all("|<a href=[\"'](.*?)[\"']>(.*)</a>[ ]*([0-9]{2}-[A-Za-z]{3}-[0-9]{4} [0-9]{2}:[0-9]{2})|",//  ([0-9]*[OKMG])
			$data,$result,PREG_SET_ORDER);
		$i=0;$now=date(MYSQL_DATETIME_FORMAT);
		foreach($result as $val)
			{
			$i++;
			$date_mysql=date_bdd($val[3],MYSQL_DATETIME_FORMAT);
			$req='INSERT INTO fichiers VALUES(NULL,"'.$now.'","default","'.$val[1].'","'.$date_mysql.'","","","","") ON DUPLICATE KEY UPDATE etat="default",date="'.$now.'",date_ftp="'.$date_mysql.'"';
			// echo $req;
			$bdd->exec($req);
			}
		$bdd->exec('UPDATE fichiers SET etat="delete" WHERE date!="'.$now.'"');
		echo $i.' éléments détectés';
		}
		elseif(isset($_GET['mode']) AND $_GET['mode']=='newsletter' AND isadmin())
		{
		// MODULE NEWSLETTER
		
		echo '<a href="/admin" class="btn">Retour à l\'administration</a>';
		
		if(!empty($_POST['titre1']) AND !empty($_POST['newsletter_title']) AND !empty($_POST['contenu1']))
			{
			$rep=$bdd->query('SELECT pseudo,mail FROM users WHERE etat!="delete" AND type!="ban"')->fetchall();
			foreach($rep as $val)
				{
				$mail_list[$val['mail']]=$val['pseudo'];
				}
			$i=1;
			$array=array();
			while(!empty($_POST['titre'.$i]) AND !empty($_POST['contenu'.$i]))
				{
				$array[$_POST['titre'.$i]]=$_POST['contenu'.$i];
				$i++;
				}
			$txt=format_mail($array);
			envoi_mail($_POST['newsletter_title'],$txt,$mail_list);
			$_SESSION['success'].='Newsletter envoyée';
			}
		?>
		<form class="form-horizontal" id="newsletter_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<fieldset><legend>Newsletter</legend>
			  <div class="control-group">
				<label class="control-label" for="newsletter_title">Titre de la news</label>
				<div class="controls">
				  <input type="text" id="newsletter_title" name="newsletter_title" placeholder="Titre de la news"/>
				</div>
			  </div>
			  <div id="news_form_input">
				  <div class="control-group">
					<label class="control-label" for="titre1">Titre1</label>
					<div class="controls">
					  <input class="input-xlarge" type="text" id="titre1" name="titre1" placeholder="Titre1">
					</div>
				  </div>
				  <div class="control-group">
					<label class="control-label" for="contenu1">Contenu1</label>
					<div class="controls">
					  <textarea class="span5" rows="5" name="contenu1" id="contenu1" placeholder="contenu1"></textarea>
					</div>
				  </div>
			  </div>
			  <div class="control-group">
				<div class="controls">
				  <button type="submit" class="btn btn-primary" 
				  onclick="return(confirm('Etes-vous sûrs de vouloir envoyer cette newsletter ?'));">Envoyer la newsletter</button>
				</div>
			  </div>			
			</fieldset>
		</form>
		<script>
			var news_counter=1;
		</script>
		<?php
		$var=htmlspecialchars(preg_replace('#\n|\t|\r#','','\'<div class="control-group">
					<label class="control-label" for="titre\'+news_counter+\'">Titre\'+news_counter+\'</label>
					<div class="controls">
					  <input class="input-xlarge" type="text" id="titre\'+news_counter+\'" name="titre\'+news_counter+\'" placeholder="Titre\'+news_counter+\'">
					</div>
				  </div>
				  <div class="control-group">
					<label class="control-label" for="contenu\'+news_counter+\'">Contenu\'+news_counter+\'</label>
					<div class="controls">
					  <textarea class="span5" rows="5" name="contenu\'+news_counter+\'" id="contenu\'+news_counter+\'" placeholder="contenu\'+news_counter+\'"></textarea>
					</div>
				  </div>\''));
		?>
		<button class="btn" onclick="news_counter=news_counter+1;$('#news_form_input').append(<?php echo $var; ?>);">
		Ajouter une section</button>
		<a href="#newsletter_apercu" class="btn btn-primary" onclick="$('#newsletter_apercu').attr('src','/ajax/ajax_newsletter.php?'+$('#newsletter_form').serialize());">
		Aperçu</a>
		<fieldset><legend>Aperçu</legend>
		<iframe id="newsletter_apercu" src="/ajax/ajax_newsletter.php" style="width:100%;height:1000px;border:none;" seamless></iframe>
		</fieldset>
		<?php
		}
	elseif(isadmin()){
		echo '<h1>Page d\'administration</h1>';
		// MODULE LUTTE CONTRE LES ANTISLASHS
		/* echo 'Supprimer les antislashs du site : ';
		$count=0;
		$anti_array=array(	'item'=>array('name','descr','effets','carac','subtype'),
							'group'=>array('name','descr'),
							'perso'=>array('name','descr'),
							'search'=>array('name','descr'),
							'users'=>array('pseudo','descr','localisation'),
							'news_commentaires'=>array('txt')
							);
		foreach($anti_array as $table=>$champ_list)
			{
			foreach($champ_list as $champ)
				{
				// echo "<br>UPDATE `".$table."` SET ".$champ." = REPLACE(".$champ.", '\\\\', '')";
				$count+= $bdd->exec("UPDATE `".$table."` SET ".$champ." = REPLACE(".$champ.", '\\\\', '')");
				}
			}
		echo $count.' champ(s) nettoyés !';*/
		
		echo '<p><a href="/?verrou=aa36dc6e81e2ac7ad03e12fedcb6a2c0" class="btn btn-danger" onclick="return(confirm(\'Valider le verrouillage\'));">!!! Verrou site !!!</a>
				<a href="/admin/newsletter" class="btn btn-primary">Écrire une newsletter</a></p>';
		
		//  MODULE SUGGESTION
		$val=$bdd->query('SELECT COUNT( * ) AS  `nbre` FROM  `item` WHERE etat="officiel"')->fetch();
		if($val['nbre']!=0){echo '<fieldset><legend>Proposition d\'objets officiels ('.$val['nbre'].')</legend>
						<a class="btn btn-primary" href="/admin/item_officiel">Gérer les propositions</a>
						</fieldset>';} 
		
		// MODULE REFERER FICHES
		$req=$bdd->query('SELECT * FROM fiches_stat ORDER BY date');
		if($req->rowCount() != 0)
			{
			echo '<fieldset><legend>Gestion des sites référents</legend>
					<table class="table table-striped table-hover table-bordered">
						<thead><tr><th>Referer</th><th>Date</th><th>Nombre de vues</th></tr></thead><tbody>';
			
			while($val=$req->fetch())
				{
				echo '<tr><td>'.secure::html($val['referer'],1).'</td><td>'.secure::html($val['date'],1).'</td><td>'.secure::html($val['nbre'],1).'</td></tr>';
				}
			echo '</tbody></table></fieldset>';
			}
		
		$rep=$bdd->query('	SELECT COUNT(*) AS `Lignes`, `name` 
							FROM `item` 
							WHERE `auteur_id`=1 AND etat!="delete" 
							GROUP BY `name` 
							HAVING count(*) > 1
							ORDER BY `name`  DESC');
		$i=0;$txt='';
		while($val=$rep->fetch())
			{
			$i++;
			$txt.= '<tr><td>'.$val['Lignes'].'</td><td>'.secure::html($val['name'],1).'</td></tr>';
			}
		if($i>0)
			{
			echo '<table class="table table-striped table-bordered table-hover">
					<caption>Objets dédoublés (<b>'.$i.'</b>)</caption>
					<thead><tr><th>Nbre</th><th>Nom</th></tr></thead>
					<tbody>
					'.$txt.'
					</tbody>
				  </table>';
			}
		// MODULE ITEMS SUBTYPE
		/* echo '<div class="row-fluid">';
		echo '<div class="span6">';
		$rep=$bdd->query('SELECT COUNT( * ) AS  `Lignes` ,COUNT(DISTINCT subtype) AS  `total` ,  `subtype` 
					FROM  `item` 
					GROUP BY  `subtype` 
					HAVING COUNT( * ) >=5
					ORDER BY  `Lignes` DESC
					');
		$i=0;$txt='';
		while($val=$rep->fetch())
			{
			$i++;
			$txt.= '<tr><td>'.$val['Lignes'].'</td><td>'.secure::html($val['subtype'],1).'</td></tr>';
			}
		echo '<table class="table table-striped table-bordered table-hover">
				<caption>Liste des types d\'objets (<b>'.$i.'</b>)</caption>
				<thead><tr><th>Nbre</th><th>Subtype</th></tr></thead>
				<tbody>
				'.$txt.'
				</tbody>
			  </table>';
		echo '</div>';
		echo '<div class="span6">';
		$rep=$bdd->query('SELECT COUNT( * ) AS  `Lignes` ,COUNT(DISTINCT subtype) AS  `total` ,  `subtype` 
					FROM  `item` 
					GROUP BY  `subtype` 
					HAVING COUNT( * ) <5
					ORDER BY  `Lignes`
					');
		$i=0;$txt='';
		while($val=$rep->fetch())
			{
			$i++;
			$txt.= '<tr><td>'.$val['Lignes'].'</td><td>'.secure::html($val['subtype'],1).'</td></tr>';
			}
		echo '<table class="table table-striped table-bordered table-hover">
				<caption>Types d\'objets peu utilisés (<b>'.$i.'</b>)</caption>
				<thead><tr><th>Nbre</th><th>Subtype</th></tr></thead>
				<tbody>
				'.$txt.'
				</tbody>
			  </table>';
		echo '</div>';
		echo '</div>'; */
		}
	else{header("HTTP/1.1 404 Not Found");echo ACCES_REFUSE_ADMIN;$header='<meta name="robots" content="noindex,follow" />';}
	}
else{header("HTTP/1.1 404 Not Found");echo ACCES_REFUSE_ADMIN;$header='<meta name="robots" content="noindex,follow" />';}
?>