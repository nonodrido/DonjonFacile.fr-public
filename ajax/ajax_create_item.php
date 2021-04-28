<?php

$ajax=1;
session_start();
include('../includes/admin/f.php');
if(isuser() AND !empty($_POST) AND !empty($_GET['type']) AND !empty($_GET['perso_id']) 
		AND is_int($_GET['perso_id']) AND get_droits_perso($_GET['perso_id'],$_SESSION['user_id']))
	{
	$type=$_GET['type'];
	if(!empty($_POST) AND !empty($_POST[$type.'_name']) AND !is_numeric($_POST[$type.'_name']) 
			AND ((!empty($_POST[$type.'_subtype']) AND !is_numeric($_POST[$type.'_subtype'])) OR 
			(!empty($_POST[$type.'_subtype']) AND $_POST[$type.'_subtype']=='Autre' AND !empty($_POST[$type.'_subtype_autre']) 
				AND !is_numeric($_POST[$type.'_subtype_autre']))))
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
					if(isset($_POST['officiel'])){$item->etat='officiel';}
					if($id=$item->update())
						{//SUCCES
						$action='<tr id="block_'.$item->id.'">
								<td><a target="_blank" data-toggle="modal" data-target="#modal_'.$item->id.'" href="/item/'.$item->id.'/'.to_url($item->name).'?popin=popin">
								'.secure::html($item->name,1).'</a></td>
								<td>
									<span id="block_'.$item->id.'_qte">1</span>
								</td><td><span class="btn-group">
										<a class="btn btn-mini" onclick="if(parseInt($(\'#block_'.$item->id.'_qte\').html())>1){qte_item('.$item->id.','.$_POST['perso_id'].',parseInt($(\'#block_'.$item->id.'_qte\').html())-1);$(\'#block_'.$item->id.'_qte\').html(parseInt($(\'#block_'.$item->id.'_qte\').html())-1);}"><i class="icon-minus"></i></a>
										<a class="btn btn-mini" onclick="qte_item('.$item->id.','.$_POST['perso_id'].',parseInt($(\'#block_'.$item->id.'_qte\').html())+1);$(\'#block_'.$item->id.'_qte\').html(parseInt($(\'#block_'.$item->id.'_qte\').html())+1);"><i class="icon-plus"></i></a>
										<a class="btn btn-mini btn-danger" data-dismiss="alert" onclick="delete_item('.$item->id.','.$_POST['perso_id'].');"><i class="icon-remove icon-white"></i></a>
									</span></td>	<td>'.$array_type[$item->type].'</td>
								<td>'.$item->prix.'</td>
								</tr>';
						add_item($item->id,$_GET['perso_id']);
						echo json_encode(array('msg'=>'Ajout de l\'objet réussi','type'=>'success','action'=>"$('#table_item_main tbody').prepend(data.code);",
													'code'=>$action));
						}
					else{//ERREUR
						echo json_encode(array('msg'=>'Erreur lors de la création de l\'item','type'=>'error'));
						}
					}
				else{
					echo json_encode(array('msg'=>'Ce nom est déjà utilisé sur le site, veuillez en choisir un autre !','type'=>'error'));
					$nom_err=1;
					}
				}
	else{echo json_encode(array('msg'=>'Il manque des éléments !','type'=>'error'));}
	}
else{
	$uniqid=uniqid();
	echo '<div class="modal hide fade" tabindex="-1" role="dialog" id="modal'.$uniqid.'" aria-labelledby="myModalLabel'.$uniqid.'" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-left:10px;">×</button>
					<ul class="nav nav-pills pull-right">
						<li class="active"><a href="#create_comp'.$uniqid.'" data-toggle="tab">Compétence</a></li>
						<li><a href="#create_arme'.$uniqid.'" data-toggle="tab">Arme</a></li>
						<li><a href="#create_protec'.$uniqid.'" data-toggle="tab">Protection</a></li>
						<li><a href="#create_divers'.$uniqid.'" data-toggle="tab">Divers</a></li>
					</ul>
				<h3 id="modalLabel">Ajout d\'un nouvel objet</h3>
			</div>
			<div class="modal-body">
			<div class="row-fluid">
				<div class="tab-content">';
	foreach(array('comp','protec','arme','divers') as $type)
		{
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
											'list'=>array("COU"=>"Courage (COU)","INT"=>"Intelligence (INT)","CHA"=>"Charisme (CHA)","AD"=>"Adresse (AD)","FO"=>"Force (FO)","PI"=>"Points d'Impact (PI)","PR"=>"Points de résistance (PR)")
											),
								'descr'=>array('opt'=>''),
								'title'=>'Création de compétence'
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
		if(!in_array($type,array('comp','divers'))){asort($list[$type]['subtype']['list']);}
		//html part
		// $titre='Création de '.$type;
		$tab=array('comp'=>2,'sort'=>3,'prodige'=>4,'arme'=>5,'protec'=>6,'divers'=>7);
		if($type=='comp'){echo '<div id="create_'.$type.'"  class="tab-pane active">';}
		else{echo '<div id="create_'.$type.$uniqid.'"  class="tab-pane">';}
		?>
		<form id="item_create_<?php echo $type; ?>" class="form-horizontal" method="post" action="#create_<?php echo $type ?>">
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
					</div>
				</div>
				<?php } ?>
				<div class="control-group"><label class="control-label" for="<?php echo $type.'_'; ?>effets">Effets </label>
					<div class="controls"><input required type="text" id="<?php echo $type.'_'; ?>effets" name="<?php echo $type.'_'; ?>effets"<?php if(!empty($_POST[$type.'_effets'])){echo 'value="'.$_POST[$type.'_effets'].'"';} ?> size="50" placeholder="effets"></div>
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
						<a class="btn" onclick="ajax_API('/ajax/ajax_create_item.php?type=<?php echo $type; ?>&perso_id='+$perso_id,$('#item_create_<?php echo $type; ?>').serialize());$('#modal<?php echo $uniqid; ?>').modal('hide');"><i class="icon-ok"></i> Valider</a>
					</div>
				</div>
			</fieldset>
		</form>
		<!--<script>
			$('#item_create_<?php echo $type; ?>').submit(function(){
				cursor_switch();
				$.ajax({
					type:"POST", 
					data: $(this).serialize(),
					url:"ajax_create_item.php?type=<?php echo $type; ?>", 
					success: function(data)
						{
						
						$('.modal').modal('hide');
						$.pnotify(text:data.msg,type:data.type,title:'Ajout de nouveau contenu');
						eval(data.action);
						cursor_switch();
						}
				});
				return false;
			}););
		</script>-->
		</div>
		<?php
		}
	echo '</div></div></div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
			</div>
		</div>';
	}