<?php 
$bdd=connect();
if(isuser()) {$user=true;}else{$user=false;}
if (isset($design) AND $design == 1)
{

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="nonodrido">

    <!-- Le styles -->
	<?php
	if(mt_rand(1,1000)==500 and $user){$css_main='bootstrap.fun.css';}else{$css_main='bootstrap.min.css';}
	$array_bootstrap=array(
		$css_main,
		'bootstrap-responsive.min.css'
		);
	foreach($array_bootstrap as $val)
		{
		echo '<link rel="stylesheet" type="text/css" href="/bootstrap/css/'.@filemtime('bootstrap/css/'.$val).'-'.$val.'" />';
		}
	$array_css=array(
		//'jquery.dataTables.css',
		//'jquery.pnotify.default.css',
		/*'jquery-ui.min.css'*/
		'compact.min.css'
		);
	foreach($array_css as $val)
		{
		echo '<link rel="stylesheet" type="text/css" href="/ressources/css/'.@filemtime('ressources/css/'.$val).'-'.$val.'" />';
		}
	//'/ressources/css/jquery.pnotify.default.icons.css',
	//'/ressources/css/jquery.dataTables_themeroller.css',
	//'/ressources/css/iPicture.css',
	?>	
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	  <link rel="stylesheet" media="screen" type="text/css" href="/ressources/css/jquery.ui.1.8.16.ie.css" />
    <![endif]-->
	
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="<?php echo '/favicon.png?d='.@filemtime('favicon.png');?>">
	<!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" /><![endif]-->
	<title>{{ $titre 88fbb3ba455fadcca770fe01cf1386a7 }}</title> <!-- titre dynamique de la page -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta charset="utf-8" />
	<meta http-equiv="content-language" content="fr">
	<meta name="viewport" content="width=device-width"/>
	<link rel="stylesheet" media="screen" type="text/css" title="main design" href="/ressources/css/style.css" />
	<!-- métas dynamiques -->
	<meta name="description" content="{{ $descr 88fbb3ba455fadcca770fe01cf1386a7 }} Application pour le jeu de rôle 'Donjon de Naheulbeuk' de PenofCahos. 
	Génération dynamique de fiches personnages , gestion de compagnies et de groupes, création de contenu dédié au support du jeu
	de rôle à distance par forum, site ou par logiciel comme rolistik" />
	<meta name="keywords" content="donjon, facile, donjonfacile, jeu de rôle, jeu, rôle, gestion, naheulbeuk, fiche, fiches, génération, personnages, 
	parties{{ $keywords 88fbb3ba455fadcca770fe01cf1386a7 }}" />
	<link rel="alternate" type="application/rss+xml" href="/rss" title="News" />
	<!-- <meta name="robots" content="index, follow" /> -->
	<script type="text/javascript" src="/ressources/js/jquery.min.js"></script>
	<!-- ajouts spécifiques à une page -->
		{{ $header 88fbb3ba455fadcca770fe01cf1386a7 }}
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
		
	  <div class="navbar-inner">
        <div class="container-fluid">
		  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
		  </a>
		  <a class="brand" href="/">DonjonFacile.fr</a>
		  <ul class="nav pull-right hidden-phone" style="margin-right:-20px;">
		  <li class="dropdown visible-desktop">
			  <a href="/search?advanced" title="Recherche avancée"><i class="icon-cog "></i></a>
		  </li>
			<!--<li class="dropdown">
			  <a class="dropdown-toggle" data-toggle="dropdown" ><i class="icon-cog "></i></a>
			   <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
				<li class="nav-header">Recheche avancée</li>
				<li class="center">
					<div class="btn-group" data-toggle="buttons-radio">
					  <button type="button" class="btn<?php if(!isset($_COOKIE['option_search_main'])){echo ' active';} ?>" onclick="$('#search_input_main').data('typeahead').source=list_item_name;delCookie('option_search_main');">Tout</button>
					  <button type="button" class="btn<?php if(isset($_COOKIE['option_search_main'])){echo ' active';} ?>" onclick="$('#search_input_main').data('typeahead').source=list_item_offi_name;setCookie('option_search_main','offi',15);">Officiel</button>
					</div>
					<?php if(isset($_COOKIE['option_search_main']))
						{
						echo '	<script>
									$(document).ready(function(){$(\'#search_input_main\').data(\'typeahead\').source=list_item_offi_name;});
								</script>';
						} ?>
				</li>
			  </ul> 
			</li>-->
		  </ul>
		  <div class="nav-collapse collapse">
			<form class="navbar-search  pull-right" id="search_form_main" action="/search" method="post">
				<input id="search_input_main" class="search-query input-medium ajax-typeahead-search" name="main_q" type="text" placeholder="Recherche" autocomplete="off">
			</form>
			</div>
			<?php if($user){ ?>
			  <ul class="nav pull-right">
				<li class="dropdown">
				  <a  id="drop3" role="button" style="cursor:pointer;" class="dropdown-toggle" data-toggle="dropdown">Bienvenue, <?php echo $_SESSION['pseudo']; ?> <b class="caret"></b></a>
				  <ul class="dropdown-menu" role="menu" aria-labelledby="drop3">
					<li><a tabindex="-1" href="/membre"><i class="icon-user"></i> Profil</a></li>
					<li><a tabindex="-1" href="/membre/library"><i class="icon-film"></i> Bibliothèque</a></li>
					<li><a tabindex="-1" href="/messagerie"><i class="icon-envelope"></i> Messagerie</a></li>
					<li><a tabindex="-1" href="/membre/edit_profil"><i class="icon-edit"></i> Modifier mon profil</a></li>
					<li><a tabindex="-1" href="/membre/param"><i class="icon-cog"></i> Paramètres</a></li>
					<li class="divider"></li>
					<li><a tabindex="-1" href="/logout"><i class="icon-off"></i> Se déconnecter</a></li>
				  </ul>
				</li>
				<li class="divider-vertical"></li>
			  </ul>
			 {{ $new_mess 88fbb3ba455fadcca770fe01cf1386a7 }}
			<div class="nav-collapse collapse">	
			<?php }  else{ //SI LE MEMBRE EST VISITEUR ?>
				<ul class="nav pull-right">
					<li class="dropdown">
						<a class="dropdown-toggle" style="cursor:pointer;"  data-toggle="dropdown">connexion / inscription <strong class="caret"></strong></a>
						<div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
							<form method="post" action="/login" name="Mlogin" id="Mlogin" accept-charset="UTF-8">
								<input style="margin-bottom: 15px;" type="text" placeholder="pseudo" id="Mpseudo" name="Mpseudo">
								<input style="margin-bottom: 15px;" type="password" placeholder="Mot de passe" id="Mmdp" name="Mmdp">
								<input style="float: left; margin-right: 10px;" type="checkbox" name="autoconnect" id="autoconnect" value="on">
								<label class="string optional" for="autoconnect"> Se souvenir de moi</label>
								<input class="btn btn-primary btn-block" type="submit" id="connection" value="Se connecter">
								<label style="text-align:center;margin-top:5px">ou</label>
                                <a class="btn btn-primary btn-block" href="/inscription">S'inscrire</a>
								<a class="btn btn-primary btn-block" href="/oubli_mdp">mot de passe oublié</a>
							</form>
						</div>
					</li>
					<li class="divider-vertical"></li>
                 </ul>
			<?php } ?>
			<ul class="nav">
				<li class="divider-vertical"></li>
			  
			  <?php 
				if(isset($_SESSION['user_id']))//////// PERSONNAGES /////////
					{
					$req=$bdd->query('SELECT p.* FROM fav 
										INNER JOIN perso p
										ON p.id = fav.fav_id
										WHERE p.etat!="delete" AND fav.etat !="delete" AND fav.user_id='.$_SESSION['user_id'].' AND fav.type="perso"
										ORDER BY p.name LIMIT 25');
					if($req->rowCount()==0)
						{
						echo '<li><a href="/perso">Personnages</a></li>';
						}
					else{
						echo '	<li class="dropdown">
								  <a href="/perso" id="drop1" role="button" class="dropdown-toggle" data-toggle="dropdown">Personnages <b class="caret"></b></a>
								  <ul class="dropdown-menu" role="menu" aria-labelledby="drop1">
									<li><a href="/perso"><i class="icon-list-alt"></i> Liste</a></li>
									<li><a href="/perso/droits"><i class="icon-lock"></i> Droits</a></li>
									<li><a href="/perso/create"><i class="icon-wrench"></i> Création</a></li>
									<li class="divider"></li>
									<li class="dropdown-submenu visible-desktop">
									<a tabindex="-1"><i class="icon-heart"></i> Favoris</a>
									<ul class="dropdown-menu">';
									while($data=$req->fetch())
										{
										echo '<li><a tabindex="-1" href="/perso/'.$data['id'].'/'.to_url($data['name']).'">'.secure::html($data['name'],1).'</a></li>';
										}
						echo '    </ul>
								  </li>
								 </ul>
								</li>';
						}
					}
				else{echo '<li><a href="/perso">Personnages</a></li>';}
				if(isset($_SESSION['user_id']))///////////// ITEMS /////////////
					{
					$req=$bdd->query('SELECT p.* FROM fav 
										INNER JOIN item p
										ON p.id = fav.fav_id
										WHERE p.etat!="delete" AND fav.etat !="delete" AND fav.user_id='.$_SESSION['user_id'].' AND fav.type="item"
										ORDER BY p.name LIMIT 25');
					if($req->rowCount()==0)
						{
						echo '	<li class="dropdown">
									<a href="/item" id="drop2" role="button" class="dropdown-toggle" data-toggle="dropdown">Objets <b class="caret"></b></a>
									<ul class="dropdown-menu" role="menu" aria-labelledby="drop2">
										<li><a href="/item"><i class="icon-list-alt"></i> Liste Générale</a></li>
										<li><a href="/item/create"><i class="icon-wrench"></i> Création</a></li>
										<li class="divider"></li>
										<li><a href="/item/officiel"><i class="icon-book"></i> Liste officielle</a></li>
										<li><a href="/classement"><i class="icon-tags"></i> Classement</a></li>
									</ul>
								</li>';
						}
					else{
						echo '	<li class="dropdown">
								  <a href="/item" id="drop2" role="button" class="dropdown-toggle" data-toggle="dropdown">Objets <b class="caret"></b></a>
								  <ul class="dropdown-menu" role="menu" aria-labelledby="drop2">
									<li><a href="/item"><i class="icon-list-alt"></i> Liste Générale</a></li>
									<li><a href="/item/officiel"><i class="icon-book"></i> Liste officielle</a></li>
									<li><a href="/item/create"><i class="icon-wrench"></i> Création</a></li>
									<li><a href="/classement"><i class="icon-tags"></i> Classement</a></li>
									<li class="divider"></li>
									<li class="dropdown-submenu visible-desktop">
									<a tabindex="-1"><i class="icon-heart"></i> Favoris</a>
									<ul class="dropdown-menu">';
									while($data=$req->fetch())
										{
										echo '<li><a tabindex="-1" href="/item/'.$data['id'].'/'.to_url($data['name']).'">'.secure::html($data['name'],1).'</a></li>';
										}
						echo '    </ul>
								  </li>
								  </ul>
								</li>';
						}
					}
				else{echo '	<li class="dropdown">
									<a href="/item" id="drop2" role="button" class="dropdown-toggle" data-toggle="dropdown">Objets <b class="caret"></b></a>
									<ul class="dropdown-menu" role="menu" aria-labelledby="drop2">
										<li><a href="/item"><i class="icon-list-alt"></i> Liste Générale</a></li>
										<li><a href="/item/officiel"><i class="icon-book"></i> Liste officielle</a></li>
										<li><a href="/classement"><i class="icon-tags"></i> Classement</a></li>
									</ul>
								</li>';}
				if(isset($_SESSION['user_id']))
					{
					$req=$bdd->query('SELECT p.* FROM fav 
										INNER JOIN `group` p
										ON p.id = fav.fav_id
										WHERE p.etat!="delete" AND fav.etat != "delete" AND fav.user_id='.$_SESSION['user_id'].' AND fav.type="group"
										ORDER BY p.name LIMIT 25');
					if($req->rowCount()==0)
						{
						echo '<li><a href="/group">Compagnies</a></li>';
						}
					else{
						echo '	<li class="dropdown">
								  <a href="/group" id="drop3" role="button" class="dropdown-toggle" data-toggle="dropdown">Compagnies <b class="caret"></b></a>
								  <ul class="dropdown-menu" role="menu" aria-labelledby="drop3">
									<li><a href="/group"><i class="icon-list-alt"></i> Page de gestion</a></li>
									<li><a href="/group/create"><i class="icon-wrench"></i> Création</a></li>
									<li class="divider"></li>
									<li class="dropdown-submenu visible-desktop">
									<a tabindex="-1"><i class="icon-heart"></i> Favoris</a>
									<ul class="dropdown-menu">';
									while($data=$req->fetch())
										{
										echo '<li><a tabindex="-1" href="/group/'.$data['id'].'/'.to_url($data['name']).'">'.secure::html($data['name'],1).'</a></li>';
										}
						echo '    </ul>
								  </li>
								 </ul>
								</li>';
						}
					}
				else{echo '<li><a href="/group">Compagnies</a></li>';}
				//echo '<li class="divider-vertical"></li>';
				
			  ?>
						<li class="dropdown hidden-desktop">
						  <a  id="drop4" role="button" class="dropdown-toggle" data-toggle="dropdown">Site <b class="caret"></b></a>
						  <ul class="dropdown-menu" role="menu" aria-labelledby="drop4">
							  <li><a href="/">Accueil</a></li>
							  <li><a href="/news">News</a></li>
							  <li><a href="/membre/list">Membres</a></li>
							  <?php if($user){?>
							  <li><a href="/messagerie">Messagerie</a></li>
							  <li><a href="/chat/">Chat</a></li>
							  <?php } ?>
							  <li><a href="/contact">Contact</a></li>
							  <li><a href="/livreor">Livre d'or</a></li>
							  
							  <li><a href="/faq">FAQ</a></li>
							  <li><a href="/dev">Développement</a></li>
							  <li><a href="/doc">Ressources</a></li>
						</ul>
						</li>
						<?php if(isadmin()){?>
						<li><a href="/admin">Administration</a></li>
						<li><a href="/test">Test</a></li>
						<?php } ?>
						<?php if(isadmin('validateur')){
						$nb=$bdd->query('SELECT COUNT( * ) AS  `nbre` FROM  `item` WHERE etat="officiel"')->fetch();
						echo '<li><a href="/admin/item_officiel">Validation ('.$nb['nbre'].')</a></li>';
						} ?>
			</ul>
		  </div><!--/.nav-collapse -->
		</div>
      </div>
    </div>
	<div id="scrolable_body">
	<!--<ul class="breadcrumb visible-desktop">
		{{ $fil 88fbb3ba455fadcca770fe01cf1386a7 }}
		<li class="pull-right active">site en bêta - version : <b>b18</b></li>
	</ul>-->
	<noscript>
		<b>De nombreuses fonctionalitées de ce site sont desactivées lorsque javascript est desactivé sur votre navigateur. 
		Nous vous conseillons donc de le réactiver ou d'utiliser un navigateur compatible.</b>
	</noscript>
    <div class="container-fluid">
      <div class="row-fluid" id="main_page">
        <div class="span2 hidden-phone" id="side_menu">
		<!--<a class="pull-right btn btn-mini" id="hide_bar_button" onclick="toggle_menu();"><<</a>-->
		  <?php
		  if(!isset($_SESSION['user']))
		  {echo '<div class="center"><a class="btn btn-large btn-block" href="/inscription"><i class="icon-user"></i> S\'inscrire</a></div>';}
		  ?>
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Personnages</li>
				  <li><a href="/perso">Liste</a></li>
				  <?php if($user){?>
				  <li><a href="/perso/droits">Droits</a></li>
				  <li><a href="/perso/create">Création</a></li>
				  <?php } ?>
				  <li><a href="/group">Compagnies</a></li>
              <li class="nav-header">Objets</li>
				  <li><a href="/item/officiel">Contenu officiel</a></li>
				  <li><a href="/item" >Liste générale</a></li>
				  <!-- #mainonclick="$('#main_link').tab('show');"<ul class="nav nav-list">
					  <li><a href="/item#arme" onclick="$('#arme_link').tab('show');">Armement</a></li>
					  <li><a href="/item#protec" onclick="$('#protec_link').tab('show');">Protections</a></li>
					  <li><a href="/item#comp" onclick="$('#comp_link').tab('show');">Compétences</a></li>
					  <li><a href="/item#divers" onclick="$('#divers_link').tab('show');">Divers/autre</a></li>
				  </ul> -->
				  <li><a href="/item/create">Création</a></li>
				  <li><a href="/search">Recherche</a></li>
              <li class="nav-header">Le site</li>
				  <!-- <li><a href="/">Accueil</a></li> -->
				  <li><a href="/news">News</a></li>
				  <li><a href="/membre/list">Membres</a></li>
				  <?php if($user){?>
				  <li><a href="/messagerie">Messagerie</a></li>
				  <li><a href="/membre/library">Bibliothèque</a></li>
				  <li><a href="/chat/">Chat <span id="nb_chat_user">{{ $chat_number 88fbb3ba455fadcca770fe01cf1386a7 }}</span></a></li>
				  <?php } ?>
				  <li><a href="/classement">Classement</a></li>
				  <li><a href="/doc">Ressources</a></li>
			  <li class="divider"></li>
				  <li><a href="/livreor">Livre d'or</a></li>
				  <li><a href="/faq">FAQ</a></li>
				  <li><a href="/contact">Contact</a></li>
				  <li><a href="/dev">Développement</a></li>
            </ul>
          </div><!--/.well -->
		  <div class="well sidebar-nav">
            <ul class="nav nav-list">
				<li class="nav-header">Liens utiles</li>
				<li>
					<a rel="nofollow" target="_blank" href="http://www.naheulbeuk.com/" title="site officiel">
						<img src="<?php base64_encode_image('./ressources/img/img_html/favicon_old.ico','ico');?>" style="width:16px;height:16px;"/> 
						Site officiel du jeu de rôle
					</a>
				</li>
				<li>
					<a rel="nofollow" target="_blank" href="http://www.naheulbeuk-online.org/" title="site de jeu en ligne">
						<img src="<?php base64_encode_image('./ressources/img/img_html/naheulbeuk_online.jpg','jpg');?>" style="width:16px;height:16px;"/> 
						Naheulbeuk Online (JdR en ligne)
					</a>
				</li>
			</ul>
		  </div>
		  <div class="well">
			<span class="nav-header">Dernier commentaire</span>
			<?php $q=$bdd->query('SELECT * FROM livreor WHERE etat!="delete" AND ref_id=0 ORDER BY date DESC,id DESC LIMIT 1')->fetch();
				$q2=$bdd->query('SELECT * FROM livreor WHERE etat!="delete" AND ref_id='.$q['id'].' ORDER BY date DESC,id DESC LIMIT 1')->fetch();
				echo '<small><p>'.secure::html(tronquer_texte($q['txt'],400)).'</p>
					  
						<cite title="Source Title"><b>'.secure::html($q['user'],1).'</b></cite>
						<i style="font-size:x-small"><time data-date="'.$q['date'].'" title="'.date_bdd($q['date']).'">Le '.date_bdd($q['date']).'</time></i>
						</small>'; 
						if(isset($q2['id'])){echo '<br><small><i>Un administrateur a répondu à ce message.</i></small>';}
						?>
			<br><a href="/livreor">Voir</a>
		  </div>
		  <div class="well"><!-- livre d'or -->
			<span class="nav-header">Commentaires</span>
			<form id="feedback" class="form" method="post" action="/livreor">
				<input type="hidden" value="{{ $REQUEST_URI 88fbb3ba455fadcca770fe01cf1386a7 }}" name="feedback_uri">
				<?php if(!isset($_SESSION['user'])){ ?>
				<input type="text" name="feedback_pseudo" placeholder="pseudo" required/>
				<input type="email" name="feedback_mail" placeholder="email" required/>
				<?php } ?>
				<textarea id="feedback_txt" name="feedback_txt" placeholder="Vous êtes invités à donner votre avis pour permettre d'améliorer ce site et de le rendre plus proche de vos besoins. Seul les avis constructifs seront acceptés. Le bbcode est autorisé."
				rows="10" required></textarea>
				<?php if(!isset($_SESSION['user'])){ ?>
				<input type="hidden" value="<?php $verif1=rand(1,9);$verif2=rand(1,9);echo ($verif1+$verif2); ?>" name="feedback_verif">
				<?php echo $verif1.' + '.$verif2.' ? '; ?><input type="text" name="feedback_test" size="5" placeholder="résultat" required/><br/>
				<?php } ?>
				<a class="btn" title="L'adresse de la page où vous vous trouvez sera enregistrée." onclick="$('#feedback').submit();"><i class="icon-map-marker"></i> Envoyer</a>
			</form><!-- fin livre d'or -->
		  </div>
		   <div class="well">
			<ul class="unstyled">
				<li class="nav-header">Remerciements</li>
				<li><a rel="nofollow" target="_blank" href="http://www.mtxserv.fr/" title="location de serveurs css, minecraft, ...">
					<img alt="hébergeur mtxserv" src="<?php base64_encode_image('./ressources/img/img_html/mtxserv.jpg','jpg');?>"/>
				</a></li>
				<li><a rel="nofollow" target="_blank" href="http://www.penofchaos.com/warham/donjon.htm" title="site de PoC">
					<img alt="Le donjon de Naheulbeuk" src="<?php base64_encode_image('./ressources/img/img_html/donjon-miniban.gif','gif');?>"/>
				</a></li>
				<li><a rel="nofollow" target="_blank" href="http://www.cronoo.com" title="Cron gratuit par CronOo">
					<img alt="CronOo.com" src="<?php base64_encode_image('./ressources/img/img_html/cronoo.jpg','jpg');?>"/>
				</a></li>
			</ul>
		  </div>
		  <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/fr/"><img alt="Licence Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/fr/80x15.png" /></a>
        </div><!--/span-->
        <div class="span10" id="corps">
		<div id="notif"></div>
			<div id="contenu_global" class="row-fluid">
			
<?php } elseif(isset($design) AND $design == 2){ ?>


			</div>
		</div><!--/span-->
      </div><!--/row-->

      
	</div>
	<div class="hidden-phone" id="toTop"><b>^</b></div>
	<footer class="hidden-phone">
	   <div class="container narrow row-fluid">
		  <div class="span2 offset2">
			<span class="nav-header">Statistiques</span>
			<table>
				<tr><td>Membres actifs :</td><td> <?php @$fichier =file('cron/compteur_user.cache');echo $fichier['0']; ?></td></tr>
				<tr><td>Personnages :</td><td> <?php @$fichier =file('cron/compteur_perso.cache');echo $fichier['0']; ?></td></tr>
				<tr><td>Objets officiels :</td><td> <?php @$fichier =file('cron/compteur_item_offi.cache');echo $fichier['0']; ?></td></tr>
				<tr><td>Autres objets :</td><td> <?php @$fichier =file('cron/compteur_item.cache');echo $fichier['0']; ?></td></tr>
				<tr><td>Compagnies :</td><td> <?php @$fichier =file('cron/compteur_group.cache');echo $fichier['0']; ?></td></tr>
			</table>
		  </div>
		  <div class="span4">
			<p class="center">
			<?php echo 'Nous sommes le '.calendar();?><br>
			<!--<img src="<?php //base64_encode_image('./ressources/img/img_html/like-glyph.png','png');?>"/> 
			<a href="http://www.facebook.com/DonjonFacile" target="_blank">Facebook</a> | 
			<img src="<?php //base64_encode_image('./ressources/img/img_html/tweet-glyph.png','png');?>"/> 
			<a href="http://www.twitter.com/DonjonFacile" target="_blank">Twitter</a> | -->
			<img src="<?php base64_encode_image('./ressources/img/img_html/forward-glyph.png','png');?>"/> 
			<a href="/contact" target="_blank">Contact</a><!-- mailto:contact@donjonfacile.fr --> | 
			<a href="/cgu">CGU</a>
			<br><br>
			<small class="center">
			Ce site n'est en aucun cas lié officiellement à <a href="http://www.penofchaos.com/warham/donjon.htm">penofchaos.com</a>.
			<br>Certaines données et images restent la propriété de PenOfChaos et de 
			ses <a href='http://www.naheulbeuk.com/jdr-auteurs.htm'>auteurs</a>.
		</small>
			</p>
		  </div>
		  <div class="span2 center">
			<span class="nav-header">Dernière news officielle</span>
			<span id="feed">
				<script>
					$(document).ready(function() { 
						$('#feed').rssfeed('http://www.penofchaos.com/naheulbeukrss.xml', {
							limit: 1,dateformat: '',header:false,errormsg:'Chargement impossible !'
						  });
					});
				</script>
			</span>
		  </div>
	   </div>
	   {{ $dev 88fbb3ba455fadcca770fe01cf1386a7 }}
	</footer>
	</div>
	<!-- Code js des notifications -->
			{{ $notif 88fbb3ba455fadcca770fe01cf1386a7 }}
	
	<!-- Google Analytics -->
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-31806717-1']);
		  _gaq.push(['_trackPageview']);

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
	<?php //if(isset($_SESSION['user']) AND $_SESSION['user'] == 'admin') {echo print_r($_SESSION).print_r($_GET)." Page générée en ". round((microtime(true) - $debut),5) ." seconde(s).";} ?>
  <!-- Le javascript -->
	<?php
	echo '<script type="text/javascript" src="/bootstrap/js/'.@filemtime("bootstrap/js/bootstrap.min.js").'-bootstrap.min.js" ></script>';
	$array_js=array(
		"cache.json.js",		
		"jquery-ui.min.js",
		//'jquery.dataTables.min.js',// tableaux dynamiques
		//'jquery.editinplace.min.js',// édition ajax inline
		//'jquery.pnotify.min.js',// notifications
		//'moment.min.js',// gestion des "il y a ..."
		//'jquery.zrssfeed.min.js',
		'jquery.zclip.min.js',
		'compact.min.js',
		
		'main.js'// script principal du site
		
		);
	foreach($array_js as $val)
		{
		echo '<script type="text/javascript" src="/ressources/js/'.@filemtime('ressources/js/'.$val).'-'.$val.'" ></script>';
		}
	?>	
	<!-- <a href="http://www.freesitemapgenerator.com/" rel="nofollow">Free xml sitemap generator</a> -->
  </body>
</html>

<?php
}
?>