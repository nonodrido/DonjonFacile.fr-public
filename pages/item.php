<?php
$titre='liste des éléments du jeu de rôle - accueil';
// str_replace('+','_',urlencode($data['name']))
/*if(isset($mode) AND mb_substr($mode,0,6)!='create' AND mb_substr($mode,0,4)!='edit' AND !isset($_GET['popin']) AND $mode!='delete' AND !in_array($mode,array('comp','sort','prodige','arme','protec','divers')))
{
?>
<!--
<div class="actions button-container" style="text-align:center;" >
	<div class="button-group minor-group">
		<a class="button icon log" href="item_comp.html">Compétences</a>
		<a class="button icon log" href="item_sort.html">Sorts</a>
		<a class="button icon log" href="item_prodige.html">Prodiges</a>
		<a class="button icon log" href="item_arme.html">Armement</a>
		<a class="button icon log" href="item_protec.html">Protections</a>
		<a class="button icon log" href="item_divers.html">Autres objets divers</a>
		<a class="button icon add" href="item_create.html">Mode création</a>
	</div>


	<?php
	if(!empty($mode) OR isset($_GET['id'])){echo '<a class="button pill icon reload" href="item.html">Retour</a>';}
	?>
</div><br/>-->
<?php }*/

/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ///////////////////////          PAGE PRINCIPALE DU MODULE          /////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
if(empty($mode) OR $mode=='officiel')
	{
	if(!isset($_GET['id']))
		{
		?>
		<ul class="nav nav-tabs">
			<?php if(empty($mode)){ ?><li class="active"><a rel="nofollow" id="main_link" href="#main" data-toggle="tab">général</a></li><?php } ?>
			<li><a rel="nofollow" href="#comp" id="comp_link" data-toggle="tab">Compétences/coups spéciaux</a></li>
			<li><a rel="nofollow" href="#arme" id="arme_link" data-toggle="tab">Armement</a></li>
			<li><a rel="nofollow" href="#protec" id="protec_link" data-toggle="tab">Protections</a></li>
			<li<?php if($mode=='officiel'){echo ' class="active"';} ?>><a rel="nofollow" href="#divers" id="divers_link" data-toggle="tab">Autres objets divers</a></li>
			<li><a class="btn" href="/item/create"><i class="icon-plus"></i> Mode création</a></li>
			<li>
			<?php if($mode=='officiel')
			{$offi='off_';
			echo '<a type="button" id="offi_button" class="btn" href="/item#main">Tout le contenu du site</a>';
			$titre='Contenu officiel du DdN';}
			else{$offi='';
			echo '<a type="button" id="offi_button" class="btn" href="/item/officiel#divers">Contenu officiel uniquement</a>';
			$titre='Liste du contenu du site';}
			?></li>
		</ul>
		
		<div class="tab-content">
			<?php if($mode!='officiel'){ ?><div id="main"  class="tab-pane active">
				<?php 
					if(isset($_SESSION['user_id']))
						{
						$req=$bdd->query('SELECT * FROM item WHERE auteur_id='.$_SESSION['user_id']);
						if($req->rowCount()!=0)
							{
							echo file_get_contents("./cache/item_main_".$_SESSION['user_id'].".cache");
							}
						else{echo file_get_contents("./cache/item_main_.cache");}
						}
					else{echo file_get_contents("./cache/item_main_.cache");}
				?>
			</div><?php } ?>
			<div id="comp"  class="tab-pane">
				<?php echo file_get_contents("./cache/item_comp_".$offi.".cache"); ?>
			</div>
			<div id="arme"  class="tab-pane">
				<?php echo file_get_contents("./cache/item_arme_".$offi.".cache"); ?>
			</div>
			<div id="protec"  class="tab-pane">
				<?php echo file_get_contents("./cache/item_protec_".$offi.".cache"); ?>
			</div>
			<div id="divers"  class="tab-pane <?php if($mode=='officiel'){echo ' active';} ?>">
				<?php echo file_get_contents("./cache/item_divers_".$offi.".cache"); ?>
			</div>
		</div>
		<script>
		  $(function () {
			$(window.location.hash+'_link').tab('show');
			<?php if(!isuser()){echo'$(".add_btn").remove();';} ?>
		  })
		</script>
		<?php
		
		}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ///////////////////          AFFICHAGE D'UN ITEM EN PARTICULIER          ////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
	else
		{
		/* $array_comp=array("ALL"=>"Aucune en particulier","COU"=>"Courage","INT"=>"Intelligence","CHA"=>"Charisme","AD"=>"Adresse","FO"=>"Force","PI"=>"Points d'Impact","PR"=>"Points de résistance");		
		$array_arme=array("recup"=>"Récupération","lames_courtes"=>"Lame courte","lames_1_main"=>"Lame 1 main","lames_2_mains"=>"Lame 2 mains","haches_1_main"=>"Hache 1 main","haches_2_mains"=>"Hache 2 mains","haches_de_jet"=>"Hache de jet","marteaux_et_masses_1_main"=>"Marteau et masse 1 main","marteaux_2_mains"=>"Marteau 2 mains","lances_et_piques"=>"Lance et pique (2 mains; arme d'hast)","javelots"=>"Javelot (jet)","arc"=>"Arc (jet)","fleches"=>"Flèches pour arc (jet)","arbaletes"=>"Arbalète (jet)","carreaux"=>"Carreaux arbalète (jet)","bizarres"=>"Arme bizarre");
		$array_protec=array("matelassée"=>"Vestes et cottes matelassées","Plastrons_cuir"=>"Plastrons cuir","Plastrons_métal"=>"Plastrons métal","Cottes_de_maille"=>"Cottes de maille","Casques_et_heaumes"=>"Casques et heaumes","Gantelets/Bracelets"=>"Gantelets/Bracelets","Bottes"=>"Bottes","Boucliers"=>"Boucliers","bizarres"=>"Protection bizarre");
		asort($array_comp);asort($array_prodige);asort($array_sort);asort($array_arme);asort($array_protec); */
		$array_prodige=array("Pr_Slanoush"=>"Prêtre de Slanoush","Pr_Dlul"=>"Prêtre de Dlul","PR_Adathie"=>"Prêtre d'Adathie","PR_Youclidh"=>"Prêtre de Youclidh","Pa_Khornettoh"=>"Guerrier de Khornettoh","Pa_Slanoush"=>"Paladin de Slanoush","Pa_Dlul"=>"Paladin de Dlul");
		$array_sort=array("Magie générale","Magie de l'air","Magie de l'Eau/Glace","Magie du Feu","Magie thermodynamique","Magie Métamorphique","Nécromancie","Sorcellerie Noire de Tzinntch","Magie de la terre","Illusion","entropiques");

		$array=array(
				'comp' =>$array_comp,
				'prodige' =>$array_prodige,
				'sort' =>$array_sort,
				'arme' =>$array_arme,
				'protec'=>$array_protec,
				'divers'=>array()
				);
		
		$text_carac='test';
		$item=new item($_GET['id']);
		if($item->verif())
			{
			if(!isset($_GET['popin']))
				{
				verif_uri('/item/'.$item->id.'/'.to_url($item->name));
				if(isset($_POST['offi']) AND $item->droits())
					{
					$bdd->exec('UPDATE item SET etat="officiel" WHERE id='.$_GET['id']);
					$item->etat='officiel';
					$_SESSION['success'].='Objet proposé';
					}
				}
			// var_dump($item);
			if(isset($_GET['inplace_value']) OR isset($_POST['inplace_value'])){
			echo'<div class="alert alert-error">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Erreur!</strong> Le site a détecté une tentative de modification de ce contenu mais ne peut pas traiter la demande !<br>
			  Pour modifier une information, il faut saisir la valeur souhaitée puis cliquer <u>hors du cadre de modification</u>.<br>
			  Appuyer sur la touche entrée ne permet pas de valider mais se contente de recharger la page.
			</div>';
			}
			if(isset($_GET['popin']) AND $_GET['popin']=='popin')
			{echo '<meta name="robots" content="noindex,follow" />	<p>'.$item->tableau(1).'</p>
						<div class="btn-group">'.
						fav_button($item->id,'item').add_list($item->id).'
						</div>';
						echo rate_button($item->id);}
			else{$item->tableau(0);
			echo '<div class="btn-group">';
			fav_button($item->id,'item');//add_list($item->id);//echo '<a class="button icon add" onclick="add_item('.$item->id.','.$_SESSION['current_perso'].');">Ajouter</a>';
			echo '<a class="btn add_btn" rel="nofollow" href="/ajax/ajax_item_add_window.php?item_id='.$item->id.'"  onclick="href_modal(this);return false;"><i class="icon-plus-sign"></i> Ajouter</a></div>';
			echo rate_button($item->id);} // AFFICHAGE ITEM
			if(isset($_GET['popin']) AND $_GET['popin']=='popin')
				{
				echo ' <a href="/item/'.$item->id.'/'.to_url($item->name).'" class="btn btn-primary">Voir/Modifier</a>';
				exit;
				}
			if($item->droits())
				{
				$sublist_array='array_'.$item->type;
				$header='
				<script type="text/javascript" >
				var item_id='.$_GET['id'].';
				var $text_carac="'.$text_carac.'";
				var $subtype_list="Autre,'.implode(',',${$sublist_array}).'";
				var $emplacement_list="Autre,'.implode(',',$array_emplacements).'";
				</script>
				<script type="text/javascript" src="/ressources/js/'.@filemtime('ressources/js/item_edit.js').'-item_edit.js"></script>
				';
				echo '
				<br><br>Type personnalisé : <div class="input-append">
					<input type="text" id="custom_input"/>
					<a class="btn" id="custom_submit">Valider</a>
				</div>';
				if($item->etat!='officiel')
					{
					echo '<form method="post" action="">	
								<input type="hidden" name="offi" value="offi"/>
								 <input type="submit" class="btn btn-primary" value="Proposer comme objet officiel" onclick="return(confirm(\'Êtes-vous certain que cet objet fait partie du contenu officiel ?\'));"/>
							</form>';
					}
				echo'
				<script>
					function delete_time()
						{
						$(\'.update_item_val\').remove();
						}
					$(document).ready(function(){
						setInterval(\'delete_time()\',1000);
					$(\'#custom_submit\').click(function() {$.ajax
					({
					type : "POST", // envoi des données en GET ou POST
					url : \'/ajax/ajax_item_edit.php\' , // url du fichier de traitement
					data: \'element_id=subtype&update_value=\'+$(\'#custom_input\').val()+\'&item_id='.$_GET['id'].'&original_html=\',
					success : function(data)
						{
						console.log(data);
						$.pnotify
							({
							text: \'Modification effectuée\',
							type:\'success\'
							});
						$(\'#subtype\').html($(\'#custom_input\').val());
						},
					error : function(jqXHR, textStatus, errorThrown)
						{
						console.log(textStatus+\' : \'+errorThrown);
						$.pnotify
							({
							text:"Le serveur est indisponible.",
							title:"Erreur de connexion",
							type:"error"
							});
						}
					});
					});
					});
				</script>
						<div class="info-box">Vous pouvez modifier cet item en cliquant sur les cases du tableau ou le 
				<a class="btn btn-danger" style="color:white;" href="/item/delete/'.$item->id.'/'.to_url($item->name).'"><i class="icon-remove icon-white"></i> supprimer</a>.</div>';
				}
			$titre=secure::html($item->name).' - '.$item->type;
			$fil['items']='item';
			}
		else{echo 'Cet item n\'existe pas ou plus !';include('pages/404.php');$error=1;}
		
		
		/* echo str_replace('<br/>','<br/>',aff_item($_GET['id'],1)); // AFFICHAGE ITEM
		$val1=get_item($_GET['id']);
		$val=type_item($_GET['id']);
		$titre=secure::html($val1['name']).' - '.$val;
		$fil['items']='item';
		if($val!=false){$fil[$list_name[$val]]='item_'.$list_table[$val];}else{echo 'Cet item n\'existe pas ou plus !';}
		if(isset($_GET['popin']) AND $_GET['popin']=='popin'){exit;} */
		}
	}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* /////////////////////             PAGE GENERALE DU MODULE             ///////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
elseif($mode=='main')
	{
	?>
	<div class="info-box">
		Cette section vous permet de gérer les objets, sorts, compétences que vous avez créé.<br/>
		Vous trouverez une liste ordonnée de tous les objets disponible sur le site triés par type.
	</div>
	<?php
	// mes items
	if(isset($_SESSION['user']))
		{
		echo '<h3>Mes Créations :</h3>';
		$ligne_i=0;
		$rep=$bdd->query('SELECT * FROM item WHERE auteur_id='.$_SESSION['user_id'].' AND `etat`!="delete" ORDER BY date DESC LIMIT 500');
		if($rep->rowCount() !=0)
			{
			$list_name=array('divers' => 'Divers','comp' => 'compétences','sort' => 'sorts','prodige' => 'prodiges','arme' => 'armement','protec' => 'protections');
			echo '<table class="jtable_main table table-bordered table-striped table-hover">
													<thead><tr>
														<th>Nom</th>
														<th>Type</th>
														<th>Actions</th>
													</tr></thead><tbody>';
			while($result=$rep->fetch())
				{
				if(($ligne_i++ % 2 == 1)){$class=' class="impaire"';}else{$class='';}
				echo'<tr'.$class.'>
						<td>
							<a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$result['id'].'/'.to_url($result['name']).'?popin=popin" rel="nofollow">
							'.$result['name'].'
							</a>
						</td>
						<td style="color:black;" >'.$list_name[$result['type']].'</td>
						<td class="center">
						<span class="btn-group">
							<a class="btn" href="/item/'.$result['id'].'/'.to_url($result['name']).'"><i class="icon-search"></i> Voir</a>'; 
				// add_list($result['id'],1,$result['name']);
				echo 	'<a class="btn add_btn" rel="nofollow" href="/ajax/ajax_item_add_window.php?item_id='.$result['id'].'"  onclick="href_modal(this);return false;"><i class="icon-plus-sign"></i> Ajouter</a>
				<a class="btn btn-danger" 
				href="/item/delete/'.$result['id'].'/'.to_url($result['name']).'" title="Supprimer"><i class="icon-trash"></i></a>
						</span>
						</td>
					 </tr>';
				}
			echo '</tbody></table>
			<script>
	$(document).ready(function(){$(\'.jtable_main\').dataTable({
		// "bStateSave": true,
		// "bJQueryUI": true,
		"iDisplayLength":50,
		"sDom": "<\'row\'<\'span6\'l><\'span6\'f>r>t<\'row\'<\'span6\'i><\'span6\'p>>",
		"sWrapper": "<span class=\"searchword\">dataTables</span>_wrapper form-inline",
        "sPaginationType": "full_numbers",
		"oLanguage": {
					"sProcessing":     "Traitement en cours...",
					"sLengthMenu":     "Afficher _MENU_ éléments",
					"sZeroRecords":    "Aucun élément à afficher",
					"sInfo":           "Affichage de l\'élement _START_ à _END_ sur _TOTAL_ éléments",
					"sInfoEmpty":      "Affichage de l\'élement 0 à 0 sur 0 éléments",
					"sInfoFiltered":   "(filtré de _MAX_ éléments au total)",
					"sInfoPostFix":    "",
					"sSearch":         "Rechercher :",
					"sLoadingRecords": "Téléchargement...",
					"sUrl":            "",
					"oPaginate": {
						"sFirst":    "Premier",
						"sPrevious": "Précédent",
						"sNext":     "Suivant",
						"sLast":     "Dernier"
								}
					}
    });});
	</script>';
			}
		else{echo 'Vous n\'avez rien créé pour l\'instant.<br/>';}
		}
		// echo "Page générée en ". round((microtime(true) - $debut),2) ." seconde(s).";
	}

/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* //////////////////          SUPRESSION D'UN ITEM EN PARTICULIER          ////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
elseif($mode=='delete')
	{
	$titre='suppression d\'un item';
	if(isset($_SESSION['user_id']))
		{
		if(isset($_GET['id']))
			{
			$item=new item($_GET['id']);
			if($item->verif() AND $item->droits())
				{
				if(isset($_POST['delete']))
					{
					$item->delete();
					$bdd->exec('UPDATE fav SET etat="delete" WHERE type="item" AND fav_id='.$item->id);
					$_SESSION['success'].=secure::html($item->name).' supprimé !';
					header('location:/item');
					cache::create_item('./pages/item.php','./cache/item_main_'.$_SESSION['user_id'].'.cache','main');
					cache::create_item('./pages/item.php','./cache/item_'.$item->type.'_.cache',$item->type);
					exit;
					}
				else{
					$titre='suppression de '.secure::html($item->name,1);
					echo'<div class="code">
					Êtes vous sûr de vouloir supprimer cet item ('.secure::html($item->name,1).') ?<br/>
					<form action="'.$_SERVER['REQUEST_URI'].'" method="post" class="button-group" name="delete_item">
					<input type="hidden" name="delete" value="1">
					<span class="btn-group">
					<a class="btn btn-danger" onclick="document.forms.delete_item.submit()"><i class="icon-ok icon-white"></i> Oui</a>
					<a href="javascript:history.back()" class="btn"><i class="icon-remove"></i> Non</a>
					</span>
					</form></div><br/>
					'.$item->tableau();
					}
				}
			else{echo ACCES_REFUSE;$header='<meta name="robots" content="noindex,follow" />';}
			}
		else{echo ACCES_REFUSE;$header='<meta name="robots" content="noindex,follow" />';}
		}
	else{echo ACCES_REFUSE_INVITE;$header='<meta name="robots" content="noindex,follow" />';}
	}
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////          LISTE ITEMS PARTICULIERS          /////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
elseif(in_array($mode,array('comp','sort','prodige','arme','protec','divers','comp_off','sort_off','prodige_off','arme_off','protec_off','divers_off')))
	{
	if(in_array($mode,array('comp_off','sort_off','prodige_off','arme_off','protec_off','divers_off')))
		{
		$offi=1;
		$off_req=' AND item.auteur_id=1 ';
		}
	else{
		$off_req='';
		}
	$url_end='';
	$fil['items']='item';
	if(empty($_GET['class'])){$_GET['class']='name';}else{$url_end.='?class='.$_GET['class'];}
	if(empty($_GET['type'])){$_GET['type']='';}
	else
		{
		$_GET['type']='desc';
		if(empty($url_end)){$url_end.='?type='.$_GET['type'];}
		else{$url_end.='&type='.$_GET['type'];}
		}
	$ecart=15;
	$list_table=array('comp' => 'comp','sorts' => 'sorts','prodiges' => 'prodiges','arme' => 'arme','protec' => 'protec');
	$list_name=array('comp' => 'compétences','sort' => 'sorts','prodige' => 'prodiges','arme' => 'armes','protec' => 'protections','divers' => 'Divers',
	'comp_off' => 'compétences','sort_off' => 'sorts','prodige_off' => 'prodiges','arme_off' => 'armes','protec_off' => 'protections','divers_off' => 'Divers');
	// $table=$list_table[$mode];
	$titre='Liste des '.$list_name[$mode];
	if(isset($_GET['id'])){$id=(($_GET['id']*$ecart)-$ecart);$page=$_GET['id'];}else{$id=0;$page=1;}
	$array=array(
		'comp' =>array('Nom'=>'name','Type'=>'subtype','Effets'=>'effets','Description'=>'descr','Auteur'=>'pseudo','Action'=>'action'),
		'prodige' =>array('Nom'=>'name','Métier'=>'subtype','Niveau'=>'carac','Description'=>'descr','Auteur'=>'pseudo','Action'=>'action'),
		'sort' =>array('Nom'=>'name','Type de magie'=>'subtype','Niveau'=>'carac','Description'=>'descr','Auteur'=>'pseudo','Action'=>'action'),
		'arme' =>array('Nom'=>'name','Type'=>'subtype','Prix'=>'prix','PI'=>'carac','Effets'=>'effets','Rupture'=>'rupture','Auteur'=>'pseudo','Action'=>'action'),
		'protec'=>array('Nom'=>'name','Type'=>'subtype','PR'=>'carac','Prix'=>'prix','Effets'=>'effets','Rupture'=>'rupture','Auteur'=>'pseudo','Action'=>'action'),
		'divers'=>array('Nom'=>'name','Type'=>'subtype','Prix'=>'prix','Effets'=>'effets','Description'=>'descr','Auteur'=>'pseudo','Action'=>'action'),
		'comp_off' =>array('Nom'=>'name','Type'=>'subtype','Effets'=>'effets','Description'=>'descr','Action'=>'action'),
		'prodige_off' =>array('Nom'=>'name','Métier'=>'subtype','Niveau'=>'carac','Description'=>'descr','Action'=>'action'),
		'sort_off' =>array('Nom'=>'name','Type de magie'=>'subtype','Niveau'=>'carac','Description'=>'descr','Action'=>'action'),
		'arme_off' =>array('Nom'=>'name','Type'=>'subtype','Prix'=>'prix','PI'=>'carac','Effets'=>'effets','Rupture'=>'rupture','Action'=>'action'),
		'protec_off'=>array('Nom'=>'name','Type'=>'subtype','PR'=>'carac','Prix'=>'prix','Effets'=>'effets','Rupture'=>'rupture','Action'=>'action'),
		'divers_off'=>array('Nom'=>'name','Type'=>'subtype','Prix'=>'prix','Effets'=>'effets','Description'=>'descr','Action'=>'action')
				);
	if(!in_array($_GET['class'],$array[$mode])){$_GET['class']='name';}
	else{if($_GET['class']=='action'){$_GET['class']='name';}}
	$rep=$bdd->query(/* 'SELECT '.$table.'.*, id_list.id as item_id
						FROM '.$table.' 
						INNER JOIN id_list 
						ON ('.$table.'.id = id_list.id_table AND id_list.table ="'.$table.'")
						WHERE '.$table.'.etat!="delete"
						ORDER BY '.$_GET['class'].' '.$_GET['type'] ); */
						// .'LIMIT '.$id.', '.$ecart );
						'SELECT item.*,users.pseudo 
						FROM item 
						INNER JOIN users 
						ON item.auteur_id = users.id 
						WHERE item.etat!="delete" AND item.type="'.str_replace('_off','',$mode).'" '.$off_req.'
						ORDER BY item.name');
	// DEBUT TABLEAU
	echo '<table class="jtable_'.$mode.' table table-bordered table-striped table-hover"><thead><tr>';
	foreach($array[$mode] as $cle=>$val)
		{
		if($_GET['class']==$val AND empty($_GET['type'])){echo '<th><!--<a href="?class='.$val.'&type=desc">-->'.$cle.'<!--</a>--></th>';}
		else{echo '<th><!--<a href="?class='.$val.'">-->'.$cle.'<!--</a>--></th>';}
		}
	echo '</tr></thead><tbody>';
	$ligne_i=0;
	while($result = $rep->fetch())
		{
		if(($ligne_i++ % 2 == 1)){$class=' class="impaire"';}else{$class='';}
		echo '<tr'.$class.'>';
		foreach($array[$mode] as $cle=>$val)
			{
			if($val=='action')
				{
				echo '<td class="center">
						<span class="btn-group">
							<a class="btn" title="Voir/Modifier" 
							href="/item/'.$result['id'].'/'.to_url($result['name']).'"><i class="icon-search"></i></a>
							<a class="btn add_btn" rel="nofollow" href="/ajax/ajax_item_add_window.php?item_id='.$result['id'].'"  onclick="href_modal(this);return false;">
							<i class="icon-plus-sign"></i> Ajouter</a>
						</span>
					  </td>';
				}
			elseif($val=='name')
				{
				echo '	<td class="'.$cle.'"><a target="_blank"  onclick="href_modal(this);return false;" href="/item/'.$result['id'].'/'.to_url($result[$val]).'?popin=popin" rel="nofollow">
						'.trunc(str_replace('_',' ',secure::html($result[$val],1)),100).'
						</a></td>';
				}
			else{
				echo '<td class="'.$cle.'">'.trunc(str_replace('_',' ',secure::html($result[$val],1)),100).'</td>';
				}
			}
		echo '</tr>';
		}
	echo '</tbody></table>
	<script>
	$(document).ready(function(){$(\'.jtable_'.$mode.'\').dataTable({
		// "bStateSave": true,
		// "bJQueryUI": true,
		"iDisplayLength":50,
		"sDom": "<\'row\'<\'span6\'l><\'span6\'f>r>t<\'row\'<\'span6\'i><\'span6\'p>>",
		"sWrapper": "<span class=\"searchword\">dataTables</span>_wrapper form-inline",
        "sPaginationType": "full_numbers",
		"aLengthMenu":[ 5, 10, 25, 50, 100 ],
		"oLanguage": {
					"sProcessing":     "Traitement en cours...",
					"sLengthMenu":     "Afficher _MENU_ éléments",
					"sZeroRecords":    "Aucun élément à afficher",
					"sInfo":           "Affichage de l\'élement _START_ à _END_ sur _TOTAL_ éléments",
					"sInfoEmpty":      "Affichage de l\'élement 0 à 0 sur 0 éléments",
					"sInfoFiltered":   "(filtré de _MAX_ éléments au total)",
					"sInfoPostFix":    "",
					"sSearch":         "Rechercher :",
					"sLoadingRecords": "Téléchargement...",
					"sUrl":            "",
					"oPaginate": {
						"sFirst":    "Premier",
						"sPrevious": "Précédent",
						"sNext":     "Suivant",
						"sLast":     "Dernier"
								}
					}
    });});
	</script>';
	}// FIN TABLEAU

/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* /////////////////////////////////      MODE CREATION      ///////////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */

elseif(mb_substr($mode,0,6)=='create')
	{
		$titre='Création d\'objets';
		if(isset($_POST['type'])){$get='?'.encode_array($_POST);}else{$get='';}
		?>
		<ul class="nav nav-tabs">
			<li class="active"><a rel="nofollow" id="create_main_link" href="#create_main" data-toggle="tab">général</a></li>
			<li><a rel="nofollow" href="#create_comp" id="create_comp_link" data-toggle="tab">Compétences/coups spéciaux</a></li>
			<li><a rel="nofollow" href="#create_arme" id="create_arme_link" data-toggle="tab">Armement</a></li>
			<li><a rel="nofollow" href="#create_protec" id="create_protec_link" data-toggle="tab">Protections</a></li>
			<li><a rel="nofollow" href="#create_divers" id="create_divers_link" data-toggle="tab">Autres objets divers</a></li>
			<li><a class="btn" href="/item"><i class="icon-list"></i> Liste des items créés</a></li>
		</ul>
		
		<div class="tab-content">
			<div id="create_main"  class="tab-pane active">
				<?php 
					if(isset($_SESSION['user_id']))
						{
						$req=$bdd->query('SELECT * FROM item WHERE auteur_id='.$_SESSION['user_id']);
						if($req->rowCount()!=0)
							{
							echo file_get_contents("./cache/item_main_".$_SESSION['user_id'].".cache");
							}
						else{echo file_get_contents("./cache/item_main_.cache");}
						}
					else{echo file_get_contents("./cache/item_main_.cache");}
				?>
			</div>
		<!--<a href="item.html" class="button icon arrowleft">Quitter le mode création</a>
			<div id="tabs"><ul>
			<li><a rel="noindex" href="/cache/item_main_<?php if(isset($_SESSION['user_id'])){echo $_SESSION['user_id'];} ?>.cache">Vos créations</a></li>
			<li><a rel="noindex" href="item_create_comp.html">Compétences</a></li>
			<!--<li><a rel="noindex" href="item_create_sort.html">Sorts</a></li>
			<li><a rel="noindex" href="item_create_prodige.html">Prodiges</a></li>--><!--
			<li><a rel="noindex" href="item_create_arme.html">Armes</a></li>
			<li><a rel="noindex" href="item_create_protec.html">Protections</a></li>
			<li><a rel="noindex" href="item_create_divers.html">Divers</a></li>
			
			</ul></div>-->
			<?php
			/* foreach(array('main'=>'item_main.html','comp'=>"item_create_comp.html",'sort'=>"item_create_sort.html",'prodige'=>"item_create_prodige.html",'arme'=>"item_create_arme.html",'protec'=>"item_create_protec.html",'divers'=>"item_create_divers.html") as $cle=>$val)
				{
				echo '<div id="'.$cle.'">';
				echo file_get_contents('../'.$val);
				echo '</div>';
				} */
		$rupture=array("0"=>"jamais","1"=>"1","2"=>"1 à 2","3"=>"1 à 3","4"=>"1 à 4","5"=>"1 à 5");
			$list=array('arme'=>array(
									'carac'=>array(
												'name'=>'Points d\'Impacts (PI)',
												'placeholder'=>'PI'
												),
									'subtype'=>array(
												'name'=>'Type d\'arme',
												'list'=>$array_arme
												),
									'descr'=>array('opt'=>'(optionnel)'),
									'title'=>'Création d\'une arme'
									),
						'protec'=>array(
									'carac'=>array(
												'name'=>'Points de Resistance (PR)',
												'placeholder'=>'PR'
												),
									'subtype'=>array(
												'name'=>'Type de protection',
												'list'=>$array_protec
												),
									'descr'=>array('opt'=>'(optionnel)'),
									'title'=>'Création d\'armure'
									),
						'sort'=>array(
									'carac'=>array(
												'name'=>'Niveau du sort',
												'placeholder'=>'niveau'
												),
									'subtype'=>array(
												'name'=>'Type de sort',
												'list'=>array("Magie générale","Magie de l'air","Magie de l'Eau/Glace","Magie du Feu","Magie thermodynamique","Magie Métamorphique","Nécromancie","Sorcellerie Noire de Tzinntch","Magie de la terre","Illusion","entropiques")
												),
									'descr'=>array('opt'=>''),
									'title'=>''
									),
						'prodige'=>array(
									'carac'=>array(
												'name'=>'Niveau du prodige',
												'placeholder'=>'niveau'
												),
									'subtype'=>array(
												'name'=>'Type de prodige',
												'list'=>array("Pr_Slanoush"=>"Prêtre de Slanoush","Pr_Dlul"=>"Prêtre de Dlul","Pr_Adathie"=>"Prêtre d'Adathie","Pr_Youclidh"=>"Prêtre de Youclidh","Pa_Khornettoh"=>"Guerrier de Khornettoh","Pa_Slanoush"=>"Paladin de Slanoush","Pa_Dlul"=>"Paladin de Dlul")
												),
									'descr'=>array('opt'=>''),
									'title'=>''
									),
						'comp'=>array(
									'subtype'=>array(
												'name'=>'Type de compétence',
												'list'=>array("Coups spéciaux"=>"Coups spéciaux","COU"=>"Courage (COU)","INT"=>"Intelligence (INT)","CHA"=>"Charisme (CHA)","AD"=>"Adresse (AD)","FO"=>"Force (FO)","PI"=>"Points d'Impact (PI)","PR"=>"Points de résistance (PR)")
												),
									'descr'=>array('opt'=>''),
									'title'=>'Création de compétence et coups spéciaux'
									),
						'divers'=>array(
									'subtype'=>array(
												'name'=>'Type d\'objet',
												'list'=>$array_divers
												),
									'descr'=>array('opt'=>''),
									'title'=>'Création d\'objets divers',
									'emplacement'=>true
									),
						);
		foreach(array('comp','protec','arme','divers') as $type)
			{
			if(!in_array($type,array('comp','divers'))){asort($list[$type]['subtype']['list']);}
			// php part
			if(!empty($_POST) AND !empty($_POST[$type.'_name']) AND !is_numeric($_POST[$type.'_name']) 
			AND ((!empty($_POST[$type.'_subtype']) AND !is_numeric($_POST[$type.'_subtype'])) OR 
			(!empty($_POST[$type.'_subtype']) AND $_POST[$type.'_subtype']=='Autre' AND !empty($_POST[$type.'_subtype_autre']) AND !is_numeric($_POST[$type.'_subtype_autre']))))
				{
				$item= new item('new');
				if($item->check_name($_POST[$type.'_name']))
					{
					$array['type']=$type;
					$array['auteur_id']=$_SESSION['user_id'];
					if($_POST[$type.'_subtype']=='Autre'){$_POST[$type.'_subtype']=$_POST[$type.'_subtype_autre'];}
					foreach($_POST as $cle=>$val)
						{
						$array[str_replace($type.'_','',$cle)]=$val;
						}
					$item->hydrate($array);
					if(isset($_POST['officiel'])){$item->etat='officiel';}else{$item->etat='default';}
					if($id=$item->update())
						{
						$_SESSION['success'].='Élément créé avec succès !';
						header('Location: /item/'.$id.'/'.to_url($_POST[$type.'_name']).'');
						echo '<script>$(location).attr(\'href\',"/item/'.$id.'/'.to_url($_POST[$type.'_name']).'");</script>
						<a href="/item/'.$id.'/'.to_url($_POST[$type.'_name']).'">Lien vers l\'item</a>';
						cache::create_item('./pages/item.php','./cache/item_main_'.$_SESSION['user_id'].'.cache','main');
						cache::create_item('./pages/item.php','./cache/item_'.$type.'_.cache',$type);
						exit;
						}
					else{$_SESSION['err'].='Erreur lors de la création de l\'élément. Un des champs doit ne pas être correctement rempli.';}
					/*if(!isset($error)){echo '<script>$(location).attr(\'href\',"/item-'.$id.'-'.to_url($_POST[$type.'_name']).'.html");</script>
						<a href="/item-'.$id.'-'.to_url($_POST[$type.'_name']).'.html"></a>';exit;}*/
					// if(!isset($error)){header('Location: /item-'.$id.'-'.to_url($_POST[$type.'_name']).'.html');exit;}
					}
				else{
					$_SESSION['err'].='Ce nom est déjà utilisé sur le site soit par vous, soit par un objet officiel. Veuillez en choisir un autre !';
					$nom_err=1;
					}
				}
			//html part
			// $titre='Création de '.$type;
			$tab=array('comp'=>2,'sort'=>3,'prodige'=>4,'arme'=>5,'protec'=>6,'divers'=>7);
			echo '<div id="create_'.$type.'"  class="tab-pane">';
			?>
			<form id="item_create_<?php echo $type; ?>" name="item_create_<?php echo $type; ?>" class="form-horizontal" method="post" action="#create_<?php echo $type ?>">
				<fieldset><legend><?php echo $list[$type]['title']; ?></legend>
					<div class="control-group<?php if(isset($nom_err)){echo ' error ';} ?>"><label for="<?php echo $type.'_'; ?>name" class="control-label">Nom </label>
						<div class="controls"><input required type="text" id="<?php echo $type.'_'; ?>name" name="<?php echo $type.'_'; ?>name"<?php if(!empty($_POST[$type.'_name'])){echo 'value="'.$_POST[$type.'_name'].'"';} ?> size="50" placeholder="nom">
						<?php if(isset($nom_err)){echo '<span class="help-inline">Nom déjà utilisé sur le site !</span>';} ?></div>
					</div>
					<?php if(!in_array($type,array('sort','prodige','comp'))){ //affichage prix ?>
					<div class="control-group"><label class="control-label" for="<?php echo $type.'_'; ?>prix">Prix </label>
						<div class="controls"><input required type="number" min="0" max="100000" id="<?php echo $type.'_'; ?>prix" name="<?php echo $type.'_'; ?>prix" <?php if(!empty($_POST[$type.'_prix'])){echo 'value="'.$_POST[$type.'_prix'].'"';} ?> placeholder="prix" /> PO</div>
					</div>
					<?php }?>
					<?php if(!in_array($type,array('comp','divers'))){ //affichage carac ?>
					<div class="control-group"><label class="control-label" for="<?php echo $type.'_'; ?>carac"><?php echo $list[$type]['carac']['name']; ?> </label>
						<div class="controls"><input type="text" id="<?php echo $type.'_'; ?>carac" name="<?php echo $type.'_'; ?>carac"<?php if(!empty($_POST[$type.'_carac'])){echo 'value="'.$_POST[$type.'_carac'].'"';} ?> size="25" placeholder="<?php echo $list[$type]['carac']['placeholder']; ?>"></div>
					</div>
					<?php } ?>
					<div class="control-group"><label class="control-label" for="<?php echo $type.'_'; ?>subtype"><?php echo $list[$type]['subtype']['name']; ?> </label>
					<div class="controls"><select size=1 name="<?php echo $type.'_'; ?>subtype" id="<?php echo $type.'_'; ?>subtype" required
					onchange="if($(this).val()=='Autre'){$(<?php echo $type.'_'; ?>autre).show();}else{$(<?php echo $type.'_'; ?>autre).hide();}">
					<option value="Autre">Autre</option>
					<?php foreach($list[$type]['subtype']['list'] as $b)
						{
						if(!empty($_POST[$type.'_subtype']) AND $_POST[$type.'_subtype']==$b){echo'<option selected value="'.$b.'">'.$b.'</option>';}
						else{echo'<option value="'.$b.'">'.ucfirst($b).'</option>';}
						}
					echo '</select></div></div>'; ?>
					<!-- type personalisé -->
					<div class="control-group" id="<?php echo $type.'_'; ?>autre"><label class="control-label" for="<?php echo $type.'_'; ?>subtype_autre"><?php echo $list[$type]['subtype']['name']; ?> (personnalisé)</label>
						<div class="controls"><input required type="text" id="<?php echo $type.'_'; ?>subtype_autre" name="<?php echo $type.'_'; ?>subtype_autre"<?php if(!empty($_POST[$type.'_subtype_autre'])){echo 'value="'.$_POST[$type.'_subtype_autre'].'"';} ?> size="50" placeholder="Type (personnalisé)"></div>
					</div>
					<?php if(isset($list[$type]['emplacement'])){ ?>
					<div class="control-group" id="<?php echo $type.'_'; ?>emplacement"><label class="control-label" for="<?php echo $type.'_'; ?>emplacement">Emplacement</label>
						<div class="controls">
							<select required id="<?php echo $type.'_'; ?>emplacement" name="<?php echo $type.'_'; ?>emplacement" placeholder="Emplacement">
								<option value="default">Défaut</option>
								<?php foreach($array_emplacements as $b)
									{
									if(!empty($_POST[$type.'_emplacement']) AND $_POST[$type.'_emplacement']==$b){echo'<option selected value="'.$b.'">'.$b.'</option>';}
									else{echo'<option value="'.$b.'">'.ucfirst($b).'</option>';}
									}
								?>
							</select>
							<?php help_button('Emplacement de l\'objet',
							'Cette option sert à définir l\'emplacement de l\'objet dans les fiches officielles du DdN. Si vous ne savez pas à quoi cela correspond, laissez l\'option sur "default"'); ?>
						</div>
					</div>
					<?php } ?>
					<div class="control-group"><label class="control-label" for="<?php echo $type.'_'; ?>effets">Effets </label>
						<div class="controls">
							<input required type="text" id="<?php echo $type.'_'; ?>effets" name="<?php echo $type.'_'; ?>effets"<?php if(!empty($_POST[$type.'_effets'])){echo 'value="'.$_POST[$type.'_effets'].'"';} ?> size="50" placeholder="effets">
							<span class="help-inline">Sous la forme AD+1 ou AT/PRD-2 (pour que le site puisse prendre en compte ces valeurs)</span>
						</div>
					</div>
					<?php if(in_array($type,array('arme','protec'))){ //affichage rupture ?>
					<div class="control-group"><label class="control-label" for="<?php echo $type.'_'; ?>rupture">Rupture </label>
						<div class="controls"><select size=1 id="<?php echo $type.'_'; ?>rupture" name="<?php echo $type.'_'; ?>rupture">
							<?php foreach($rupture as $a=>$b)
									{
									if(!empty($_POST[$type.'_rupture']) AND $_POST[$type.'_rupture']==$b){echo'<option selected value="'.$a.'">'.$b.'</option>';}
									else{echo'<option value="'.$a.'">'.ucfirst($b).'</option>';}
									}?>
						</select></div>
					</div>
					<?php }?>
					<div class="control-group"><label class="control-label" for="<?php echo $type.'_'; ?>descr">Description <?php echo $list[$type]['descr']['opt']; ?> </label>
						<div class="controls"><textarea id="<?php echo $type.'_'; ?>descr" name="<?php echo $type.'_'; ?>descr" cols="75" rows="10"><?php if(!empty($_POST[$type.'_descr'])){echo $_POST[$type.'_descr'];} ?></textarea></div>
					</div>
					<div class="control-group">
					  <div class="controls">
					    <label class="checkbox">
						  <input type="checkbox" id="<?php echo $type.'_'; ?>officiel" name="officiel"> Proposer comme contenu officiel
					    </label>
						<span class="help-inline"><?php help_button('Contenu officiel','En cochant cette case, vous créerez cet objet comme étant un objet officiel. Il sera 
						utilisable immédiatement mais ne sera réellement déclaré officiel qu\'après verification par un administrateur. Merci de participer à 
						l\'enrichissement du contenu du site !'); ?></span>
				  	  </div>
				    </div>
					<div class="control-group">
						<div class="controls">
							<a class="btn" onclick="
								if(!document.item_create_<?php echo $type; ?>.<?php echo $type.'_'; ?>officiel.checked==false)
									{
									if(confirm('Êtes-vous certain que cet objet fait partie du contenu officiel ?'))
										{
										$('#item_create_<?php echo $type; ?>').submit();
										}
									else{return false;}
									}
								else{$('#item_create_<?php echo $type; ?>').submit();}
							"><i class="icon-ok"></i> Valider</a>
						</div>
					</div>
				</fieldset>
			</form>
			</div>
			<?php
			}
		echo '</div>
		<script>
		  $(function () {
			$(window.location.hash+\'_link\').tab(\'show\');
		  })
		</script>';//if($('#echo $type.'_';officiel').is(':checked')){confirm('Etes-vous sûrs que ce contenu est officiel ? \nChaque proposition est validée par un administrateur et les abus seront punis !\n\nToutefois, si ce contenu est bien officiel, l\'équipe du site vous remercie pour votre coopération !');}
		}
else{include('pages/404.php');$error=1;}