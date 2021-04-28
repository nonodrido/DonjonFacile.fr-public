<?php 
$titre='accueil';
verif_uri('/');
$accueil=1;//pour le module news
$h1='';//le titre est affiché directement et pas automatiquement
if(isuser() AND mt_rand(1,1000)==500){$fun=true;echo '<embed  src="http://337.eleximg.com/337/freegame/uploads/201206/14/3e5cf7ba5b46692d6e332f37fe4e67ec.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="650" height="540"></embed><BR /><a href="http://www.cat-mario.com" title="cat mario" target="_blank"><strong>Cat Mario</strong></a><div class="hidden">';}
?>
<div class="center">
<img src="<?php echo DIR_IMG_CSS.'baniere_center.png' ?>"/>
</div>

<div class="row-fluid"><div class="span8">
	<h1>Bienvenue sur DonjonFacile.fr</h1>
	<h3>Le site des passionnés du jeu de rôle du donjon de naheulbeuk.</h3>
	<!--<div class="warning-box">
	Ce site est en BÊTA :<br>
	Il n'est donc pas exempt de bug et peut avoir quelques ralentissements. N'hésitez pas à reporter tout problème pour accélérer
	la correction de ces problèmes.
	</div>-->
	
<?php if(!isset($_SESSION['user'])){ ?>
	<div class="alert alert-info">
		<p>Si vous ne savez pas ce qu'est le jeu de rôle papier ou le donjon de naheulbeuk, vous risquez de ne pas comprendre le but de ce site.
		Je vous encourage dans ce cas à lire <a style="color:#bdc3c7;" target="_blank" href="http://www.naheulbeuk.com/jdr-docs/commentjouer-naheulbeuk-jdr.pdf">
		ce document explicatif</a>. Bien sûr le <a style="color:#bdc3c7;" href="http://www.naheulbeuk.com" target="_blank">site officiel</a> reste la référence absolue, 
		toutes les réponses aux questions que vous ne vous posez pas encore y sont.</p>
		<p>Pour jouer en ligne, rejoignez la communauté <a style="color:#bdc3c7;" target="_blank" href="http://www.naheulbeuk-online.org/">naheulbeuk online</a>.</p>
	</div>
	<a target="_blank" href="/ressources/img/img_html/exemple_fiche_big.jpg" >
		<img src="/ressources/img/img_html/exemple_fiche.jpg" class="pull-right" alt="exemple de fiche de personnage"/>
	</a>
	<blockquote><p>
		Ce site va vous permettre de gérer directement en ligne une compagnie d'aventuriers du 
		<a href="http://www.naheulbeuk.com" target="_blank">Jeu de Rôle du Donjon de Naheulbeuk</a>. <br/>
		Vous pouvez ainsi partir à l'aventure tranquille : ce site vous permet de créer, gérer et partager 
		des personnages sois via sa page sur le site,
		 sois via une fiche de personnage graphique générée dynamiquement et avec un lien unique : 
		 fini les problèmes d'upload et de création de fiche pour pouvoir jouer en ligne. Tout est fait automatiquement ! <br/>
		 Vous pouvez créer vos propres objets, compétences, sorts ou bien utiliser ceux de la version officielle ou créés par d'autre joueur.
	 </p></blockquote>
	 <blockquote><p>
		 Équipez votre personnage comme bon vous semble et devenez enfin un grand aventurier ! <br/>
		 <a href="/inscription">Inscrivez-vous</a> ou connectez-vous dans la barre supérieure pour profiter 
		 de toutes les fonctionnalitées du site.
	 </p></blockquote>
	 <p class="muted">Pour tester le site sans s’inscrire, utilisez le compte de test (login : test, mdp : test)</p>
	 <p class="center">
		<a class="btn btn-large btn-primary btn-success" href="/inscription"><i class="icon-user"></i> Inscription !</a>
	 </p>
<?php }else{ ?>
	
Bienvenue <b><?php echo $_SESSION['pseudo']; ?></b>,
	<?php 
			$rep = $bdd->query('SELECT * FROM users_persos WHERE etat!="delete" AND user_id='.$_SESSION['user_id']);
			$list='';
			if($rep->rowCount() != 0)
				{
				while($result=$rep->fetch())
					{
					$list.=' OR id="'.$result['perso_id'].'"';
					}
				$list=mb_substr($list,3);
				$list=' OR ('.$list.')';
				}
				$r='SELECT * FROM perso WHERE etat!="delete" AND (user_id ="'.$_SESSION['user_id'].'" '.$list.') ORDER BY date DESC LIMIT 8';//echo $r;
				$req=$bdd->query($r);
				if($req->rowCount() == 0)
					{
					echo '<blockquote>Vous n\'avez aucun personnage disponible pour le moment. Vous pouvez en 
					<a href="/perso/create">créer un</a> ou bien obtenir le droit de modifier un autre personnage 
					d\'un autre utilisateur du site.<br/>
					Ces personnages et leur état seront alors affichés en page d\'accueil du site.</blockquote>';
					}
				else{		// L'utilisateur a des personnages dispo
				echo '	<br/>
						Voici la liste de l\'ensemble de vos personnages (depuis le plus récemment modifié).
						Vous pouvez aussi en <a href="/perso/create">créer un nouveau</a>.
						
						<blockquote><div class="row-fluid">';
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
						}
					echo '</div></blockquote><br>';
					}
			}
	?>
</div>
<div class="span4">
	<div class="well">
		<?php include('news.php'); ?>
		<div class="center">
			<a href="/news" class="btn"><i class="icon-share-alt"></i> Toutes les news</a>
		</div>
	</div>
	<?php if(isuser()){echo'<p class="center"><a target="_blank" class="btn btn-info" href="/chat/"><i class="icon-comment icon-white"></i> Chat du site</a></p>';} ?>
</div>
</div>
<div class="row-fluid">
<ul class="thumbnails">
<?php if(!isuser()){ //si l'user n'est pas connecté ?>
	<li class="span6">
		<div class="thumbnail">
		  <img src="<?php echo DIR_IMG_HTML.'4perso.png' ?>" alt="Personnages">
		  <div class="caption">
			  <h3>Gestion des personnages</h3>
			  <p>
				Grâce à ce site, vous pourrez créer un personnage en quelques instants. 
				Il ne vous restera plus ensuite qu'à l'équiper et en route pour l'aventure ! 
				Seules les utilisateurs que vous autoriserez auront le droit de le modifier.
			  </p>
			  <p>
			    Tous vos personnages seront sauvegardés et visible depuis n'importe où en terre de Fangh. 
				Une fiche officielle dynamique sera générée pour chacun d'eux, avec une url unique et la police officielle du DdN.
			  </p>
			  <!--<p>
			    <a class="btn btn-primary" href="/inscription">S'inscrire</a>
			  </p>-->
		  </div>
		</div>
	</li>
	<li class="span6">
		<div class="thumbnail">
		  <img src="<?php echo DIR_IMG_HTML.'equip.png' ?>" alt="">
		  <div class="caption">
		  <h3>Création d'éléments / objets</h3>
		  <p>Créez vos propres objet, compétences et sorts, équipez-le sur vos personnages, et faites profiter la communauté de vos idées. </p>
		  <p>Faites librement votre choix parmi l'ensemble du contenu officiel du jeu, mais aussi parmi le contenu de la communauté.</p>
		  <p><a class="btn btn-primary" href="/item">Voir la liste des objets</a></p>
		  </div>
		</div>
	</li>
<?php }else{ //si l'user est connecté ?>
	
	<li class="span6">
		<div class="thumbnail">
		  <img src="<?php echo DIR_IMG_HTML.'4perso.png' ?>" alt="Personnages">
		  <div class="caption">
			  <h3>Gestion des personnages</h3>
			  <p>
				Vous pouvez créer un personnage en quelques instants. 
				Il ne vous restera plus ensuite qu'à l'équiper et en route pour l'aventure !
			  </p>
			  <p>
			    Tous vos personnages sont sauvegardés et visible depuis n'importe où en terre de Fangh. 
				Une fiche officielle est générée pour chacun d'eux.
			  </p>
			  <p>
				<a class="btn btn-primary" href="/perso/create">Créer un personnage</a>
				<a class="btn" href="/perso">Voir ses personnages</a>
			  </p>
		  </div>
		</div>
	</li>
	<li class="span6">
		<div class="thumbnail">
		  <img src="<?php echo DIR_IMG_HTML.'equip.png' ?>" alt="Objets">
		  <div class="caption">
		  <h3>Création d'éléments / objets</h3>
		  <p>Créez un objet au nom unique, équipez-le sur vos personnages, mais aussi sur 
		  n'importe quel autre (objets communautaires à venir).</p>
		  <p>Vos objets créés sont listés, mais vous pouvez aussi utiliser ceux officiels ou créés par la communauté.</p>
		  <p><a class="btn btn-primary" href="/item/create">Créer un objet</a>
		  <a class="btn" href="/item">Voir ses objets</a></p>
		  </div>
		</div>
	</li>
	<!--<li class="span6">
	<iframe src="/chat.php" style="width:100%;height:650px;border:none;" seamless></iframe>
	</li>-->
<?php } ?>
</ul></div>
<div class="row-fluid">
	<div class="span4 well">
		<h3 class="center"><a href="/item" class="black">Derniers objets créés</a></h3>
		<dl class="dl-horizontal">
		<?php
		$req=$bdd->query('SELECT * FROM item WHERE `etat`!="delete" ORDER BY create_date DESC,RAND() LIMIT 0,10');
		while($val=$req->fetch())
			{
			echo '	<dt><time class="date" data-date="'.$val["create_date"].'" title="'.date_bdd($val["create_date"]).'">
							'.date_bdd($val["create_date"],'d/m/Y').'
						</time></dt>
						<dd><a href="/item/'.$val['id'].'/'.to_url($val['name']).'" title="'.secure::html($val['name'],1).'">
							' . tronquer_texte($val['name'],40,0,1) . '
						</a>
						</dd>';
			}
		?>
		</dl>
	</div>
	<div class="span4 well">
		<h3 class="center"><a href="/membre/list" class="black">Derniers connectés</a></h3>
		<dl class="dl-horizontal">
			<?php
			$req=$bdd->query('	SELECT *
								FROM users
									WHERE option_online=1
								ORDER BY last_connect DESC,RAND()
								LIMIT 0,10');
			while($val=$req->fetch())
				{
				echo '<dt><time class="date" data-date="'.$val["last_connect"].'" title="Le '.date_bdd($val["last_connect"]).'"> 
							'.date_bdd($val["last_connect"],'d/m/Y').'
						</time>
					</dt>
					<dd><a href="/membre/'.$val['id'].'/'.to_url($val['pseudo']).'" title="'.secure::html($val['pseudo'],1).'">
							' . tronquer_texte($val['pseudo'],50,0,1) . '
						</a>
						</dd>';
				}
			?>
		</dl>
	</div>
	<div class="span4 well">
		<h3 class="center"><a href="/perso" rel="nofollow" class="black">Derniers persos mis à jour</a></h3>
		<dl class="dl-horizontal">
			<?php
			$req=$bdd->query('SELECT * FROM perso WHERE `etat` != "delete" ORDER BY date DESC,RAND() LIMIT 0,10');
			while($val=$req->fetch())
				{
				echo '<dt> <time class="date" data-date="'.$val["date"].'" title="'.date_bdd($val["date"]).'">
							'.date_bdd($val["date"],'d/m/Y').'
						</time>
					</dt>
					<dd><a href="/perso/'.$val['id'].'/'.to_url($val['name']).'" title="'.secure::html($val['name'],1).'">
							' . tronquer_texte($val['name'],40,0,1) . '
						 </a></dd>';
				}
			?>
		</dl></fieldset>
	</div>
</div>
<?php	if(isset($fun)){echo '</div><!--div du jeu aléatoire -->';}/* $array_choix=array('default','all','offi');
	$sondage_id=1;
	if(!empty($_POST['sondage']))
		{
		if(in_array($_POST['sondage'],$array_choix))
			{
			$bdd->exec('INSERT INTO sondage VALUES('.$sondage_id.',NOW(),'.$_SESSION['user_id'].',"'.$_POST['sondage'].'") 
					ON DUPLICATE KEY UPDATE date=NOW(),vote="'.$_POST['sondage'].'"');
			$_SESSION['success'].='Vote pris en compte';
			}
		}
	$val=$bdd->query('SELECT * FROM sondage WHERE sondage_id='.$sondage_id.' AND user_id='.$_SESSION['user_id'])->fetch();
	if(!empty($val['vote'])){$vote=true;}else{$vote=false;} 
	?>
	Bienvenue <b><?php echo $_SESSION['pseudo']; ?></b>,
	<!--<div class="well">
		<h3>Sondage :</h3>
		Concernant le nom des objets et compétences sur le site, vous preferez :
		<form id="sondage_form" action="/" method="post" class="<?php if($vote){echo'hidden';} ?>">
			<label class="radio"><input type="radio" name="sondage" value="default" checked/> On ne change rien : aucun objet n'est unique</label>
			<label class="radio"><input type="radio" name="sondage" value="all"/> Chaque objet du site est unique, une fois qu'un nom est choisi, personne
			d'autre ne peut l'utiliser</label>
			<label class="radio"><input type="radio" name="sondage" value="offi"/> Seul les objets officiels sont unique et personne ne peut créer d'objet du 
			même nom</label>
			<input type="submit" value="Voter !" class="btn btn-primary"/>
		</form>
		
		<?php/*  if($vote){
		$val=$bdd->query('SELECT COUNT( * ) AS `Lignes` , `vote` FROM  `sondage` WHERE sondage_id='.$sondage_id.' GROUP BY  `vote` ORDER BY `Lignes`')->fetchAll();
		$total_vote=0;$array_result=array('default'=>0,'all'=>0,'offi'=>0);
		foreach($val as $b)
			{
			$array_result[$b['vote']]=$b['Lignes'];
			$total_vote=$total_vote+$b['Lignes'];
			}
		echo'
			<p><!--<div class="progress progress-striped active" style="width:200px;"><div class="bar" style="width: '.(($array_result['default']/$total_vote)*100).'%;"></div></div>-->
			 <b>'.round(($array_result['default']/$total_vote)*100).'%</b> ('.$array_result['default'].' votes) On ne change rien : aucun objet n\'est unique</p>
			<p><!--<div class="progress progress-striped active" style="width:200px;"><div class="bar" style="width: '.(($array_result['all']/$total_vote)*100).'%;"></div></div>-->
			 <b>'.round(($array_result['all']/$total_vote)*100).'%</b> ('.$array_result['all'].' votes) Chaque objet du site est unique, une fois qu\'un nom est choisi, personne d\'autre ne peut l\'utiliser</p>
			<p><!--<div class="progress progress-striped active" style="width:200px;"><div class="bar" style="width: '.(($array_result['offi']/$total_vote)*100).'%;"></div></div>-->
			 <b>'.round(($array_result['offi']/$total_vote)*100).'%</b> ('.$array_result['offi'].' votes) Seul les objets officiels sont unique et personne ne peut créer d\'objet du même nom</p>
			<p>
			Nombre de votes : '.$total_vote.'
			';
		?>
			<a onclick="$('#sondage_form').removeClass('hidden');$(this).remove();" class="btn btn-inverse">Changer mon vote</a></p>
		<?php //} ?>
	</div>-->
*/