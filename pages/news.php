<?php 
if(isset($accueil))///// MODULE ACCUEIL /////
	{
	if(isadmin())
		{
		echo "<p><a class='btn' href=/news/create><i class='icon-plus'></i> Ajouter une news</a></p>";
		}
    $donnees = $bdd->query('SELECT * FROM news WHERE `etat` != "delete" ORDER BY date DESC LIMIT 1')->fetch();
	$nb_comment=$bdd->query('SELECT COUNT(*) as nbre FROM news_commentaires WHERE etat!="delete" AND news_id='.$donnees['id'])->fetch();
	echo '<h4 style="margin-top:0;"><a style="color:#333;" href="/news/'.$donnees['id'].'/'.to_url($donnees['titre']).'">
			' . secure::html($donnees['titre'],1). '
			</a></h4>
			<p class="pull-right muted" style="font: small italic;">'.$donnees['type'].'</p>
			<time class=" muted" title="Le '.date_bdd($donnees["date"]).'" data-date="'.$donnees["date"].'">Le '.date_bdd($donnees["date"]).'</time>
			<p>' .secure::html(tronquer_texte($donnees['contenu'], 500)). '</p>
			<p><a href="/news/'.$donnees['id'].'/'.to_url($donnees['titre']).'">Lire en entier</a>
			<span class="pull-right"><a href="/news/'.$donnees['id'].'/'.to_url($donnees['titre']).'#commentaires">'.$nb_comment['nbre'].' commentaire(s)</a></span></p>
	';//Le '.date_bdd($donnees["date"]).'</p>/*  tronquer_texte(secure::html($donnees['contenu']),550,0,1) */
	}
elseif(empty($mode) AND !isset($_GET['id']))///// DERNIERES NEWS /////
	{
	$titre='News';
	verif_uri('/news');
	if(isadmin())
		{
		echo "<p><a class='btn' href=/news/create><i class='icon-plus'></i> Ajouter une news</a></p>";
		}
	echo '<h2>Dernières news :</h2>';
    $reponse = $bdd->query('SELECT * FROM news WHERE `etat` != "delete" ORDER BY date DESC LIMIT 150');
    while ($donnees = $reponse->fetch())
		{
		$nb_comment=$bdd->query('SELECT COUNT(*) as nbre FROM news_commentaires WHERE etat!="delete" AND news_id='.$donnees['id'])->fetch();
		echo '<article class="papers news">
				<a href="/news/'.$donnees['id'].'/'.to_url($donnees['titre']).'"><h2 class="news">' . secure::html($donnees['titre']) . '</h2></a> ' . secure::html($donnees['contenu']) . '<br/><br/>
				<p>
				<time class=date data-date="'.$donnees["date"].'" title="Le '.date_bdd($donnees["date"]).'"> 
				Ecrit le '.calendar($donnees["date"]).' (le '.date_bdd($donnees["date"]).')</time>
				<span class="pull-right">
					<a href="/news/'.$donnees['id'].'/'.to_url($donnees['titre']).'#commentaires">'.$nb_comment['nbre'].' commentaire(s)</a>
				</span>';
		if(isadmin())
			{
			echo '<p class="btn-group">
					<a onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer cette news ?\'));" 
						href= "/news/delete/'.$donnees['id'].'" class="btn btn-danger">
						<i class="icon-trash"></i> supprimer cette news</a>
					<a href= "/news/edit/'.$donnees["id"].'" class="btn"><i class="icon-edit"></i> éditer la news</a>
				  </p>';
			}
		echo '</p></article>';
		}
	}
elseif(empty($mode) AND isset($_GET['id']))///// NEWS PARTICULIERE /////
	{
	//////////////   MODULE COMMENTAIRE    //////////////////
	if(isuser() AND !empty($_POST['comment']))
		{
		if(!empty($_POST['comment_id'])){$comment_id=(int) $_POST['comment_id'];}else{$comment_id=0;}
		$req = $bdd->prepare('INSERT INTO news_commentaires(news_id, user_id, comment_id, txt) VALUES(:news_id, :user_id, :comment_id, :txt)');
		$req->execute(array(
			'news_id' => $_GET['id'],
			'user_id' => $_SESSION['user_id'],
			'txt' => $_POST['comment'],
			'comment_id' => $comment_id
			));
		$_SESSION['success'].='Commentaire ajouté !';
		}
	if(isadmin() AND !empty($_POST['delete_comment_id']))
		{
		$bdd->exec('UPDATE news_commentaires SET etat="delete" WHERE id='.$_POST['delete_comment_id']);
		$_SESSION['success'].='Commentaire modéré !';
		}
	//////////// AFFICHAGE NEWS ///////////////
    $reponse = $bdd->query('SELECT id, titre, contenu, date FROM news WHERE `etat` != "delete" AND `id` = '.$_GET['id'].'');
	if($reponse->rowCount()==0)
		{
		echo 'Cette news n\'existe pas ou as été supprimée.<br/>';
		$header='<meta name="robots" content="noindex,follow" />';
		}
	else{
		$donnees = $reponse->fetch();
		$titre=$donnees['titre'].' - News';
		verif_uri('/news/'.$donnees['id'].'/'.to_url($donnees['titre']));
		$nb_comment=$bdd->query('SELECT COUNT(*) as nbre FROM news_commentaires WHERE etat!="delete" AND news_id='.$donnees['id'])->fetch();
		echo '<article class="papers news">
			<a href="/news/'.$donnees['id'].'/'.to_url($donnees['titre']).'"><h2 class="news">' . secure::html($donnees['titre']) . '</h2></a> ' . secure::html($donnees['contenu']) . '<br/><br/>
			<p>
			<time class=date data-date="'.$donnees["date"].'" title="Le '.date_bdd($donnees["date"]).'"> 
			Ecrit le '.calendar($donnees["date"]).' (le '.date_bdd($donnees["date"]).')</time>
			<span class="pull-right"><a href="/news/'.$donnees['id'].'/'.to_url($donnees['titre']).'#commentaires">'.$nb_comment['nbre'].' commentaire(s)</a></span>';
		if(isadmin())
			{
			echo '<p class="btn-group"><a onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer cette news ?\'));" 
			href= "/news/delete/'.$donnees['id'].'" class="btn btn-danger"><i class="icon-trash"></i> supprimer cette news</a>
			<a href= "/news/edit/'.$donnees["id"].'" class="btn"><i class="icon-edit"></i> éditer la news</a></p>';
			}
		echo '</p></article><br>';
		// Affichage commentaires
		$req=$bdd->query('SELECT c.*, u.avatar, u.pseudo
							FROM news_commentaires AS c
							INNER JOIN users AS u
							ON u.id = c.user_id
							WHERE c.news_id='.$_GET['id'].' AND c.etat!="delete" AND c.comment_id=0
							ORDER BY c.date');//$data['']
		echo '<fieldset id="commentaires">
				<legend>Commentaires :<a class="btn pull-right" href="/news"><i class="icon-share-alt"></i> Retour à la liste des news</a></legend><br>';
		while($data = $req->fetch())
			{
			echo '	<div id="comment_'.$data['id'].'"><div class="well well-small">
					  <a class="pull-left thumbanil" style="margin:5px;margin-top:0;" href="/membre/'.$data['user_id'].'/'.to_url($data['pseudo']).'" alt="voir le profil de cet utilisateur">
						<img class="media-object" style="width: 64px; height: 64px;" src="'.secure::html($data['avatar'],1).'" alt="voir le profil de cet utilisateur" />
					  </a>
					  <div class="media-body" style="margin-left:74px;">
						<h4 class="media-heading">'.secure::html($data['pseudo'],1).'</h4>
						'.secure::html($data['txt']);
			echo '<span class="pull-right"><small><time class=" muted" title="Le '.date_bdd($data["date"]).'" data-date="'.$data["date"].'">Le '.date_bdd($data["date"]).'</time></small> ';
				if(isset($_SESSION['user']))
					{
					if($_SESSION['user']=='admin'){echo '<form class="form-inline" style="display:inline;" action="'.$_SERVER['REQUEST_URI'].'" method="post">
														<input value="'.$data['id'].'" type="hidden" name="delete_comment_id"/>
														<button type="submit" class="btn btn-link"><small>Modérer</small></button> 
														';}
					echo '<a href="#comment_'.$data['id'].'" onclick="$(\'#comment_form_'.$data['id'].'\').removeClass(\'hidden\');"><small>Répondre</small></a></form>';
					} 
			echo '</span></div>
					</div>';
			$rep=$bdd->query('SELECT c.*, u.avatar, u.pseudo
							FROM news_commentaires AS c
							INNER JOIN users AS u
							ON u.id = c.user_id
							WHERE c.news_id='.$_GET['id'].' AND c.etat!="delete" AND c.comment_id='.$data['id'].'
							ORDER BY c.date');
			while($val = $rep->fetch())
				{
				echo '	<div class="well well-small" style="margin-left:74px;">
					  <a class="pull-left thumbanil" style="margin:5px;margin-top:0;" href="/membre/'.$val['user_id'].'/'.to_url($val['pseudo']).'" alt="voir le profil de cet utilisateur">
						<img class="media-object" style="width: 64px; height: 64px;" src="'.secure::html($val['avatar'],1).'" alt="voir le profil de cet utilisateur" />
					  </a>
					  <div class="media-body" style="margin-left:64px;">
						<h4 class="media-heading">'.secure::html($val['pseudo'],1).'</h4>
						'.secure::html($val['txt']);
					echo '<span class="pull-right"><small><time class=" muted" title="Le '.date_bdd($val["date"]).'" data-date="'.$val["date"].'">Le '.date_bdd($val["date"]).'</time></small> ';
				if(isset($_SESSION['user']))
					{
					if($_SESSION['user']=='admin'){echo '<form class="form-inline" style="display:inline;" action="'.$_SERVER['REQUEST_URI'].'" method="post">
														<input value="'.$val['id'].'" type="hidden" name="delete_comment_id"/>
														<button type="submit" class="btn btn-link"><small>Modérer</small></button> 
														';}
					echo '<a href="#comment_'.$data['id'].'" onclick="$(\'#comment_form_'.$data['id'].'\').removeClass(\'hidden\');"><small>Répondre</small></a></form>';
					} 
				echo '</span></div>
					</div>';
				}
			if(isset($_SESSION['user']))
				{
				echo '	<form class="form-horizontal hidden" style="margin-left:74px;" id="comment_form_'.$data['id'].'" action="'.$_SERVER['REQUEST_URI'].'" method="post">
				  <div class="control-group">
					<label class="control-label" for="comment">Répondre à ce commentaire</label>
					<div class="controls">
					  <textarea name="comment" class="span4" rows="8" placeholder=""></textarea>
					</div>
				  </div>
				  <div class="control-group">
					<div class="controls">
					  <input type="hidden" value="'.$data['id'].'" name="comment_id"/>
					  <button type="submit" class="btn">Répondre</button>
					</div>
				  </div>
				</form>';
				}
			echo '</div>';
			}
		echo '</fieldset>';
		if(isuser())
			{
			echo '	<fieldset><legend>Commenter cette news :</legend><form class="form-horizontal" action="'.$_SERVER['REQUEST_URI'].'" method="post">
					  <div class="control-group">
						<label class="control-label" for="comment">Ajouter un commentaire</label>
						<div class="controls">
						  <textarea name="comment" class="span4" rows="8" placeholder=""></textarea>
						</div>
					  </div>
					  <div class="control-group">
						<div class="controls">
						  <button type="submit" class="btn">Commenter</button>
						</div>
					  </div>
					</form></fieldset>';
			}
		}
	}
elseif($mode=='create' AND isadmin())///// NOUVELLE NEWS /////
	{
	$titre='Création de news';
	if(!empty($_POST['titre']) AND !empty($_POST['message']))
		{
		$req = $bdd->prepare('INSERT INTO news (titre, contenu, date) VALUES(?, ?, NOW())');
		$req->execute(array($_POST['titre'],$_POST["message"]));
		$_SESSION['success'].='News créée.';
		header("Location: /news");
		}
	?>
	<form class="form-horizontal" id="news" method="post" action="/news/create">
	  <div class="control-group">
		<label class="control-label" for="titre">Titre</label>
		<div class="controls">
		  <input type="text" id="titre" name="titre" placeholder="titre" tabindex="1" />
		</div>
	  </div>
	  <div class="control-group">
		<label class="control-label" for="message">Contenu</label>
		<div class="controls">
		  <textarea id="message" name="message" value="message" tabindex="2" rows="20" style="width:90%"></textarea>
		</div>
	  </div>
	  <div class="control-group">
		<div class="controls">
		  <button type="submit" class="btn">Créer la news</button>
		</div>
	  </div>
	</form>
	<?php
	}
elseif($mode=='edit' AND isset($_GET['id']) AND isadmin())///// MODIFIER NEWS /////
	{
	if(!empty($_POST['titre']) AND !empty($_POST['message']))
		{
		$req = $bdd->prepare('UPDATE news SET titre = :nvtitre, contenu = :nvcontenu WHERE id = :id');
		$req->bindValue(':id', $_GET["id"], PDO::PARAM_INT);
		$req->bindValue(':nvtitre', $_POST["titre"], PDO::PARAM_STR);
		$req->bindValue(':nvcontenu', $_POST["message"], PDO::PARAM_STR);
		$req->execute();
		$_SESSION['success'].='News modifiée.';
		header("Location: /news");
		}
	$reponse = $bdd->prepare('SELECT id, titre, contenu FROM news WHERE id= ?');
	$reponse->execute(array($_GET['id']));
	$donnees = $reponse->fetch();
	$titre='Modification de "'.$donnees['titre'].'"';
	?>
	<form class="form-horizontal" id="news" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	  <div class="control-group">
		<label class="control-label" for="titre">Titre</label>
		<div class="controls">
		  <input type="text" id="titre" name="titre" value="<?php echo $donnees['titre']; ?>" placeholder="titre" tabindex="1" />
		</div>
	  </div>
	  <div class="control-group">
		<label class="control-label" for="message">Contenu</label>
		<div class="controls">
		  <textarea id="message" name="message" value="message" tabindex="2" rows="20" style="width:90%"><?php echo $donnees['contenu']; ?></textarea>
		</div>
	  </div>
	  <div class="control-group">
		<div class="controls">
		  <button type="submit" class="btn">Modifier la news</button>
		</div>
	  </div>
	</form>
	<?php
	}
elseif($mode=='delete' AND isset($_GET['id']) AND isadmin())///// SUPPRIMER NEWS /////
	{
	$bdd = connect();
	$req = $bdd->prepare('UPDATE news SET `etat` = "delete" WHERE id = :id');
	$req->bindValue(':id', $_GET["id"], PDO::PARAM_INT);
	$req->execute();
	$_SESSION['success'].='News supprimée.';
	header("Location: /news");
	}
else{header("HTTP/1.1 404 Not Found");include('pages/404.php');$error=1;}
/* 
if(!isset($accueil)){$titre= 'News';}
$h1='';
if(!isset($_SESSION['user']) OR !$_SESSION['user'] == 'admin' OR !isset($_GET['action'])) 
	{
	//////////////   MODULE COMMENTAIRE    //////////////////
	if(isset($_SESSION['user']) AND !empty($_POST['comment']) AND !empty($_GET['id']))
		{
		if(!empty($_POST['comment_id'])){$comment_id=(int) $_POST['comment_id'];}else{$comment_id=0;}
		$req = $bdd->prepare('INSERT INTO news_commentaires(news_id, user_id, comment_id, txt) VALUES(:news_id, :user_id, :comment_id, :txt)');
		$req->execute(array(
			'news_id' => $_GET['id'],
			'user_id' => $_SESSION['user_id'],
			'txt' => $_POST['comment'],
			'comment_id' => $comment_id
			));
		$_SESSION['success'].='Commentaire ajouté !';
		}
	if(isset($_SESSION['user']) AND $_SESSION['user']=='admin' AND !empty($_POST['delete_comment_id']))
		{
		$bdd->exec('UPDATE news_commentaires SET etat="delete" WHERE id='.$_POST['delete_comment_id']);
		$_SESSION['success'].='Commentaire modéré !';
		}
	
	
	if(empty($_GET['id']) AND !isset($accueil)){echo "<br/><h1>Dernières News</h1>";}
	if(isset($_SESSION['user']) AND empty($_GET['id']) AND $_SESSION['user'] == 'admin')
		{
		echo "<p><a class='btn' href=/news?action=5><i class='icon-plus'></i> Ajouter une news</a></p>";
		}
// Connexion à la base de données
try{
	if(!isset($accueil)){$lim= '100';}
	else{$lim= '1';}
    if(!empty($_GET['id']) AND filter_var($_GET['id'], FILTER_VALIDATE_INT) != false)
		{
		$req_txt='SELECT id, titre, contenu, date FROM news WHERE `etat` != "delete" AND `id` = "'.$_GET['id'].'" ORDER BY date DESC LIMIT 0, 10';
		}
	else{
		$req_txt='SELECT * FROM news WHERE `etat` != "delete" ORDER BY date DESC LIMIT '.$lim;
		}
    // Récupération des 10 derniers messages
    $reponse = $bdd->query($req_txt);
	
    $i=0;
    // Affichage de chaque message
    while ($donnees = $reponse->fetch()){
		$i++;
		$nb_comment=$bdd->query('SELECT COUNT(*) as nbre FROM news_commentaires WHERE etat!="delete" AND news_id='.$donnees['id'])->fetch();
		if(!isset($accueil))// page news.html
			{
			echo '<article class="papers news">';
			if(!empty($_GET['id'])){$titre.=' - '.$donnees['titre'];}
			echo '<a href="/news/'.$donnees['id'].'/'.to_url($donnees['titre']).'"><h2 class="news">' . secure::html($donnees['titre']) . '</h2></a> ' . secure::html($donnees['contenu']) . '<br/><br/><p>
			<time class=date data-date="'.$donnees["date"].'" title="Le '.date_bdd($donnees["date"]).'"> 
			Ecrit le '.calendar($donnees["date"]).' (le '.date_bdd($donnees["date"]).')</time>';
			echo '<span class="pull-right"><a href="/news/'.$donnees['id'].'/'.to_url($donnees['titre']).'#commentaires">'.$nb_comment['nbre'].' commentaire(s)</a></span>';
			if(isset($_SESSION['user']) AND $_SESSION['user'] == 'admin')
				{
				echo '<p class="btn-group"><a onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer cette news ?\'));" href= "/news?id='.$donnees['id'].'&amp;action=1" class="btn btn-danger"><i class="icon-trash"></i> supprimer cette news</a>
				<a href= "/news?id='.$donnees["id"].'&amp;action=4" class="btn"><i class="icon-edit"></i> éditer la news</a></p>';
				}
			echo '</p>';
			if(empty($_GET['id'])){echo "</article>";}
			else{
				echo '</article><br>';
				// Affichage commentaires
				$req=$bdd->query('SELECT c.*, u.avatar, u.pseudo
									FROM news_commentaires AS c
									INNER JOIN users AS u
									ON u.id = c.user_id
									WHERE c.news_id='.$_GET['id'].' AND c.etat!="delete" AND c.comment_id=0
									ORDER BY c.date');//$data['']
				echo '<fieldset id="commentaires"><legend>Commentaires :<a class="btn pull-right" href="/news"><i class="icon-share-alt"></i> Retour à la liste des news</a></legend><br>';
				while($data = $req->fetch())
					{
					echo '	<div id="comment_'.$data['id'].'"><div class="well well-small">
							  <a class="pull-left thumbanil" style="margin:5px;margin-top:0;" href="/membre/'.$data['user_id'].'/'.to_url($data['pseudo']).'" alt="voir le profil de cet utilisateur">
								<img class="media-object" style="width: 64px; height: 64px;" src="'.secure::html($data['avatar'],1).'" alt="voir le profil de cet utilisateur" />
							  </a>
							  <div class="media-body" style="margin-left:74px;">
								<h4 class="media-heading">'.secure::html($data['pseudo'],1).'</h4>
								'.secure::html($data['txt']);
					echo '<span class="pull-right"><small><time class=" muted" title="Le '.date_bdd($data["date"]).'" data-date="'.$data["date"].'">Le '.date_bdd($data["date"]).'</time></small> ';
						if(isset($_SESSION['user']))
							{
							if($_SESSION['user']=='admin'){echo '<form class="form-inline" style="display:inline;" action="'.$_SERVER['REQUEST_URI'].'" method="post">
																<input value="'.$data['id'].'" type="hidden" name="delete_comment_id"/>
																<button type="submit" class="btn btn-link"><small>Modérer</small></button> 
																';}
							echo '<a href="#comment_'.$data['id'].'" onclick="$(\'#comment_form_'.$data['id'].'\').removeClass(\'hidden\');"><small>Répondre</small></a></form>';
							} 
					echo '</span></div>
							</div>';
					$rep=$bdd->query('SELECT c.*, u.avatar, u.pseudo
									FROM news_commentaires AS c
									INNER JOIN users AS u
									ON u.id = c.user_id
									WHERE c.news_id='.$_GET['id'].' AND c.etat!="delete" AND c.comment_id='.$data['id'].'
									ORDER BY c.date');
					while($val = $rep->fetch())
						{
						echo '	<div class="well well-small" style="margin-left:74px;">
							  <a class="pull-left thumbanil" style="margin:5px;margin-top:0;" href="/membre/'.$val['user_id'].'/'.to_url($val['pseudo']).'" alt="voir le profil de cet utilisateur">
								<img class="media-object" style="width: 64px; height: 64px;" src="'.secure::html($val['avatar'],1).'" alt="voir le profil de cet utilisateur" />
							  </a>
							  <div class="media-body" style="margin-left:64px;">
								<h4 class="media-heading">'.secure::html($val['pseudo'],1).'</h4>
								'.secure::html($val['txt']);
							echo '<span class="pull-right"><small><time class=" muted" title="Le '.date_bdd($val["date"]).'" data-date="'.$val["date"].'">Le '.date_bdd($val["date"]).'</time></small> ';
						if(isset($_SESSION['user']))
							{
							if($_SESSION['user']=='admin'){echo '<form class="form-inline" style="display:inline;" action="'.$_SERVER['REQUEST_URI'].'" method="post">
																<input value="'.$val['id'].'" type="hidden" name="delete_comment_id"/>
																<button type="submit" class="btn btn-link"><small>Modérer</small></button> 
																';}
							echo '<a href="#comment_'.$data['id'].'" onclick="$(\'#comment_form_'.$data['id'].'\').removeClass(\'hidden\');"><small>Répondre</small></a></form>';
							} 
						echo '</span></div>
							</div>';
						}
					if(isset($_SESSION['user']))
						{
						echo '	<form class="form-horizontal hidden" style="margin-left:74px;" id="comment_form_'.$data['id'].'" action="'.$_SERVER['REQUEST_URI'].'" method="post">
						  <div class="control-group">
							<label class="control-label" for="comment">Répondre à ce commentaire</label>
							<div class="controls">
							  <textarea name="comment" class="span4" rows="8" placeholder=""></textarea>
							</div>
						  </div>
						  <div class="control-group">
							<div class="controls">
							  <input type="hidden" value="'.$data['id'].'" name="comment_id"/>
							  <button type="submit" class="btn">Répondre</button>
							</div>
						  </div>
						</form>';
						}
					echo '</div>';
					}
				echo '</fieldset>';
				if(isset($_SESSION['user']))
					{
					echo '	<fieldset><legend>Commenter cette news :</legend><form class="form-horizontal" action="'.$_SERVER['REQUEST_URI'].'" method="post">
							  <div class="control-group">
								<label class="control-label" for="comment">Ajouter un commentaire</label>
								<div class="controls">
								  <textarea name="comment" class="span4" rows="8" placeholder=""></textarea>
								</div>
							  </div>
							  <div class="control-group">
								<div class="controls">
								  <button type="submit" class="btn">Commenter</button>
								</div>
							  </div>
							</form></fieldset>';
					}
				}
			}
		else{// page d'accueil
			echo '	
						<h4 style="margin-top:0;"><a style="color:#333;" href="/news/'.$donnees['id'].'/'.to_url($donnees['titre']).'">
							' . secure::html($donnees['titre'],1). '
						</a></h4>
						<p class="pull-right muted" style="font: small italic;">'.$donnees['type'].'</p>
						<time class=" muted" title="Le '.date_bdd($donnees["date"]).'" data-date="'.$donnees["date"].'">Le '.date_bdd($donnees["date"]).'</time>
						<p>' .secure::html(tronquer_texte($donnees['contenu'], 500)). '</p>
						<p><a href="/news/'.$donnees['id'].'/'.to_url($donnees['titre']).'">Lire en entier</a>
						<span class="pull-right"><a href="/news/'.$donnees['id'].'/'.to_url($donnees['titre']).'#commentaires">'.$nb_comment['nbre'].' commentaire(s)</a></span></p>
					';//Le '.date_bdd($donnees["date"]).'</p>  tronquer_texte(secure::html($donnees['contenu']),550,0,1) 
			}
		}
	if($i==0){echo '<br/>Cette news n\'existe pas ou as été supprimée. En cas de problème contactez l\'administrateur du site via le formulaire de contact<br/>';}
    $reponse->closeCursor();
	}
catch(Exception $e){die('Erreur : '.$e->getMessage());}
echo '<br/>';
	}
else{
	if($_GET['action']== 1){ // SQL/supprimer la news
		try{
			$bdd = connect();
			$req = $bdd->prepare('UPDATE news SET `etat` = "delete" WHERE id = :id');
			$req->bindValue(':id', $_GET["id"], PDO::PARAM_INT);
			$req->execute();
		}
		catch(Exception $e){die('Erreur : '.$e->getMessage());}?> 
		
		<script language="javascript" type="text/javascript">
			<!--
			window.location.replace("/news");
			-->
		</script> 
	<?php
		}
		
	elseif($_GET['action']== 2){ // SQL/éditer la news
		try
		{
		$bdd = connect();
			$req = $bdd->prepare('UPDATE news SET titre = :nvtitre, contenu = :nvcontenu WHERE id = :id');
			$req->bindValue(':id', $_GET["id"], PDO::PARAM_INT);
			$req->bindValue(':nvtitre', $_POST["titre"], PDO::PARAM_STR);
			$req->bindValue(':nvcontenu', str_replace('\"','"',str_replace("\'","'",$_POST["message"])), PDO::PARAM_STR);
			$req->execute();
		}
		catch(Exception $e){die('Erreur : '.$e->getMessage());}?> 
		
		<script language="javascript" type="text/javascript">
			<!--
			window.location.replace("/news");
			-->
		</script> 
	<?php
		}
		
	elseif($_GET['action']== 3){ // SQL/créer une news
	try{
		$bdd = connect();
		$req = $bdd->prepare('INSERT INTO news (titre, contenu, date) VALUES(?, ?, NOW())');
		$req->execute(array($_POST['titre'],str_replace("\'","'",$_POST["message"])));
		
	}
	catch(Exception $e){die('Erreur : '.$e->getMessage());}?> 
	
	<script language="javascript" type="text/javascript">
		<!--
		window.location.replace("/news");
		-->
	</script> 
	
	<?php
	}
	elseif($_GET['action']== 4){ // éditer une news
	try{
		$bdd = connect();
		$reponse = $bdd->prepare('SELECT id, titre, contenu FROM news WHERE id= ?');
		$reponse->execute(array($_GET['id']));
		$donnees = $reponse->fetch();
		$reponse->closeCursor();
		}
	catch(Exception $e){die('Erreur : '.$e->getMessage());}
	?>

	<form id="news" method="post" action="/news?id=<?php echo "".$_GET['id'];?>&amp;action=2">
		<p><label for="titre">Titre :</label><input type="text" id="titre" name="titre" value="<?php echo ''.$donnees['titre']; ?>" tabindex="1" style="width:100%"/></p>
		<p><br/><label for="message">News :</label><textarea id="message" name="message" value="message" rows="25" tabindex="2" style="width:100%"><?php echo $donnees['contenu']; ?></textarea></p>
		<div class="center"><input class="btn" type="submit" value="Modifier la news !" /></div>
	</form>

	<?php }
	elseif($_GET['action']== 5){ // créer une news
	?>

		<form id="news" method="post" action="/news?action=3">
			<p><label for="titre">Titre :</label><input type="text" id="titre" name="titre" value="titre" tabindex="1" /></p>
			<p><test><br/><label for="message">News :</label><textarea id="message" name="message" value="message" tabindex="2" rows="25" style="width:100%"></textarea></p>
			</test><div class="center"><input class="btn" type="submit" value="Créer la news !" /></div>
		</form>
	<?php
		}
} */
?>