<?php
$titre='Documentation';
$list_type=array('Scénario','Divers','Fiche','Grimoire','Aide de jeu','Équipement','Bestiaire','Carte','Règles');sort($list_type);
if(empty($_GET['id']))
	{
	verif_uri('/doc');
	$array_result=$bdd->query('SELECT * FROM fichiers WHERE etat!="delete" ORDER BY name,url')->fetchAll();
	echo '<h1>Ressources officielle du jeu</h1>';
	if(isadmin()){echo '<a href="/admin?mode=fichiers" class="btn btn-primary">Mettre à jour</a><br>';}
	foreach($array_result as $val)
		{
		$type[]=$val['type'];
		}
	$type=array_unique($type);sort($type);
	echo '<ul class="nav nav-tabs" id="myTab">';
	foreach($type as $mode)
		{
		echo '<li><a href="#tab'.str_replace(' ','',$mode).'" data-toggle="tab">'.ucfirst($mode).'</a></li>';
		}
	echo '</ul><div class="tab-content">';
	foreach($type as $mode)
		{
		echo '<div class="tab-pane fade" id="tab'.str_replace(' ','',$mode).'">
				<table class="table table-condensed table-hover table-striped table-bordered">
				<thead><tr><th>Nom</th><th>Description</th><!--<th>Type</th>--><th>Date</th><th>Nature</th><th>Action</th></tr></thead><tbody>';
		foreach($array_result as $val)
			{
			if($val['type']==$mode)
				{
				if(empty($val['name']) OR empty($val['type'])){$nom=$val['url'].' <span class="badge badge-success">nouveau</span>';}
				else{$nom=secure::html($val['name'],1);}
				echo '<tr>
						<td><b><i>'.ucfirst($nom).'</i></b></td>
						<td>'.secure::html($val['descr']).'</td>
						<!--<td>'.secure::html($val['type'],1).'</td>-->
						<td><time class="date" data-date="'.$val['date_ftp'].'" title="'.date_bdd($val['date_ftp']).'">
						'.date_bdd($val['date_ftp'],'d/m/Y').'</time></td>
						<td>
							<span class="badge badge-info">'.strtoupper(str_replace('.','',substr($val['url'],-4))).'</span>
							<span class="label label-inverse">'.secure::html($val['orig'],1).'</span>
						</td>
						<td>
							<a href="http://www.naheulbeuk.com/jdr-docs/'.$val['url'].'" class="btn btn-info" download="'.$val['url'].'">
								<i class="icon-download-alt"></i> Télécharger
							</a>';
				if(isadmin())
					{
					echo '<a class="btn" href="/doc/'.$val['id'].'">Modifier</a><br>';
					echo '<form id="modif_doc_'.$val['id'].'" action="'.$_SERVER['REQUEST_URI'].'" method="post">
							<input type="hidden" name="id" value="'.$val['id'].'" />
							<select id="orig" name="orig" onchange="ajax_API(\'/ajax/ajax_doc.php\',$(\'#modif_doc_'.$val['id'].'\').serialize());">
								<option></option>';
							$list_orig=array('officiel','contribution');
					foreach($list_orig as $a)
						{
						if($a==$val['orig']){echo '<option value="'.$a.'" selected>'.$a.'</option>';}
						else{echo '<option value="'.$a.'">'.$a.'</option>';}
						}
					echo '</select>
							<select id="type" name="type" onchange="ajax_API(\'/ajax/ajax_doc.php\',$(\'#modif_doc_'.$val['id'].'\').serialize());">
								<option></option>';
					foreach($list_type as $a)
						{
						if($a==$val['type']){echo '<option value="'.$a.'" selected>'.$a.'</option>';}
						else{echo '<option value="'.$a.'">'.$a.'</option>';}
						}
					echo '</select></form>';
					}
				echo '	</td>
					  </tr>';
				}
			}
		echo '</tbody></table></div>';
		}
	echo '</div>
			<script>
		  $(function () {
			$(\'#myTab a:first\').tab(\'show\');
		  })
		</script>';
	}
else{
	if(isset($_POST['name']) AND isset($_POST['type']) AND isset($_POST['descr']))
		{
		$bdd->exec('UPDATE fichiers SET name="'.$_POST['name'].'",type="'.$_POST['type'].'",descr="'.$_POST['descr'].'" WHERE id='.$_GET['id']);
		$_SESSION['success'].='Ressource modifiée !';
		}
	$val=$bdd->query('SELECT * FROM fichiers WHERE etat!="delete" AND id='.$_GET['id'])->fetch();
	echo '	<a href="/doc" class="btn"><i class="icon-share-alt"></i> Retour</a>
			<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
				<fieldset><legend>Modifier une ressource ('.$val['url'].')</legend>
					<div class="control-group">
					  <label class="control-label">Nom</label>
					  <div class="controls">
						<input id="name" name="name" type="text" placeholder="" value="'.secure::html($val['name'],1).'" class="input-xlarge">
					  </div>
					</div>
					<div class="control-group">
					  <label class="control-label">Type</label>
					  <div class="controls">
						<select id="type" name="type" type="text">
							<option></option>';
						$list=array('Scénario','Divers');sort($list);
						foreach($list_type as $a)
							{
							if($a==$val['type']){echo '<option value="'.$a.'" selected>'.$a.'</option>';}
							else{echo '<option value="'.$a.'">'.$a.'</option>';}
							}
	echo '				</select>
					  </div>
					</div>
					<div class="control-group">
					  <label class="control-label">Description</label>
					  <div class="controls">                     
						<textarea id="descr" name="descr" class="span4">'.$val['descr'].'</textarea>
					  </div>
					</div>
					<div class="control-group">
					  <div class="controls">
						<input type="submit" class="btn btn-primary" value="Modifier"/>
					  </div>
					</div>
				</fieldset>
			</form>';
	}