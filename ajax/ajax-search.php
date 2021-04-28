<?php
$start = microtime(true);
$ajax=1;
@session_start();
@include('../includes/admin/f.php');
// print_r($_POST);
if(empty($_POST['q']) AND empty($_POST['main_q']))
{echo '<h3 class="center">Pas de r&eacute;sultats pour cette recherche</h3>';echo 'test';}
else{
$id_result=array();
$add=' AND `id`!= 1 AND `type` != "encyclo" AND `type` != "sort" AND `type` != "prodige"';
if(isset($_POST['choix_nbre']) AND filter_var($_POST['choix_nbre'], FILTER_VALIDATE_INT) 
		AND $_POST['choix_nbre']>0 AND $_POST['choix_nbre']<50)//maximum de retour de la recherche
{$max=$_POST['choix_nbre'];}
else{$max=10; }
// $_POST['choix_mode']
$choix_table=' ';
if(isset($_POST['choix_table']))//choix du champ de recherche
	{
	foreach($_POST['choix_table'] AS $val)
		{
		if(in_array($val,array('comp','comp','protec','arme','divers')))
			{
			$choix_table.=' OR `type`="'.$val.'" ';
			}
		}
	}
$choix_mode=' ';
// if(isset($_SESSION['user_id'])){$choix_mode=' AND (`auteur_id`='.$_SESSION['user_id'].' OR `auteur_id`=1) ';}else{$choix_mode=' AND `auteur_id`=1 ';}
if(isset($_POST['choix_mode']))//choix du champ de recherche
	{
	if(isset($_SESSION['user_id']) AND $_POST['choix_mode']=='me')
		{
		$choix_mode=' AND `auteur_id`='.$_SESSION['user_id'];
		}
	elseif(isset($_SESSION['user_id']) AND $_POST['choix_mode']=='normal')
		{
		$choix_mode=' AND (`auteur_id`='.$_SESSION['user_id'].' OR `auteur_id`=1) ';
		}
		elseif(!isset($_SESSION['user_id']) AND $_POST['choix_mode']=='normal')
		{
		$choix_mode=' AND `auteur_id`=1 ';
		}
		elseif($_POST['choix_mode']=='officiel')
		{
		$choix_mode=' AND `auteur_id`=1 ';
		}
		elseif($_POST['choix_mode']=='all')
		{
		$choix_mode=' ';
		}
	}
$choix_subtype='';
if(!empty($_POST['choix_subtype']))
	{
	$choix_subtype=' AND `subtype`="'.trim(secure::bdd($_POST['choix_subtype'])).'" ';
	}
if($choix_table==' '){$choix=$choix_mode.$choix_subtype.$add;}
else{$choix='AND ( '.mb_substr($choix_table,4).' ) '.$choix_mode.$choix_subtype.$add;}
$bdd=connect();
/* if(empty($_POST['q'])){$q=trim(secure::bdd($_POST['main_q']));}
else{$q=trim(secure::bdd($_POST['q']));} */

if(empty($_POST['q'])){$q=trim(str_replace('  ',' ',$_POST['main_q']));}
else{$q=trim(str_replace('  ',' ',$_POST['q']));}

/////////  sépration des mots  //////
$mot_q = explode(' ',$q);$mot_s='';$mot_t='';$array_mot=array();
foreach($mot_q as $mot)
	{
	$mot_s.='AND (name LIKE ?
		  OR descr LIKE ?) ';
	$mot_t.='OR (name LIKE ?
		  OR descr LIKE ?) ';
	$array_mot[]='%'.$mot.'%';
	$array_mot[]='%'.$mot.'%';
	}
$mot_s='('.mb_substr($mot_s,4).')';
$mot_t='('.mb_substr($mot_t,3).')';

/////////////  REQUETE DE RECHERCHE  ////////////////
	$req_txt='		(
				SELECT 1 as sort_col,item.id,item.name
				FROM item
				WHERE `etat` != "suppr" AND `etat` != "delete" AND 
				name LIKE ?
				'.$choix.'
				)
			UNION
				(
				SELECT 2 as sort_col,search.id,search.name
				FROM search 
				WHERE (`etat` != "suppr" AND `etat` != "delete" '.$choix.') AND 
				MATCH (name,descr) AGAINST (?)
				)
			UNION
				(
				SELECT 3 as sort_col,item.id,item.name
				FROM item
				WHERE `etat` != "suppr" AND `etat` != "delete"
				AND (name LIKE ?
				OR descr LIKE ?)
				'.$choix.'
				)
			UNION
				(
				SELECT 4 as sort_col,item.id,item.name
				FROM item
				WHERE  `etat` != "suppr" AND `etat` != "delete"
				AND '.$mot_s.' 
				'.$choix.'
				)
			UNION
				(
				SELECT 5 as sort_col,item.id,item.name
				FROM item
				WHERE  `etat` != "suppr" AND `etat` != "delete"
				AND '.$mot_t.' 
				'.$choix.'
				)
			ORDER BY sort_col,name
			LIMIT 0,'.$max;

// echo '<pre>'.$req_txt.'</pre>';
$req=$bdd->prepare($req_txt);
$req->execute(array_merge(array('%'.$q.'%',$q,'%'.$q.'%','%'.$q.'%'),$array_mot,$array_mot));
$result=$req->rowCount();
//////////////////  AFFICHAGE RESULTATS   //////////
if( $result == 0)
	{
	// echo '<h3 class="center"></h3>';
	echo '	<script>
				$(\'#nbresult\').html(\'<h4>Pas de r&eacute;sultats pour cette recherche</h4>\');
			</script>';
	}
else{
	$id_list=array();
	$i=0;
	while($val=$req->fetch())
		{
		if(!in_array($val['id'],$id_list))
			{
			$item=new item($val['id']);
			echo '<div class="result_algo_'.$val['sort_col'].'">'.$item->tableau(1,true).'</div>';
			$id_list[]=$val['id'];
			$i++;
			}
		}
	if($max==$i)
		{
		echo '	<script>
					$(\'#nbresult\').html(\'<h4>'.$i.' r&eacute;sultats (ou plus) pour cette recherche</h4>\');
				</script>';
		}
	else{
		echo '	<script>
					$(\'#nbresult\').html(\'<h4>'.$i.' r&eacute;sultat(s) pour cette recherche</h4>\');
				</script>';
		}
	
	}
echo '<div class="center"><small>'.round((microtime(true) - $start),2).' sec</small></div>';
/* if(!isset($direct))
	{
	echo '<script>
		// Gestion auto des modal avec url
			$(\'a[data-toggle="modal"][href]\').click(function(e) {cursor_switch();
				e.preventDefault();
				var url = $(this).attr(\'href\');
				var txt = $(this).html();
				if (url.indexOf(\'#\') == 0) {
					$(url).modal(\'open\');
				} else {
					$.get(url, function(data) {
						$(\'<div class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\'
						+\'<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>\'
						+\'<h3 id="modalLabel">\'+txt+\'</h3></div><div class="modal-body">\'
						+ data 
						+ \'</div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button></div></div>\')
						.modal();
					}).success(function() { $(\'input:text:visible:first\').focus();cursor_switch();});
				}
			});
		</script>';
	} */
// echo '<pre>'.$req_txt.'</pre>';
}
/*








/////////////  forme de l'array(id,table,id,id_algorithme) /////////////
$result=0;
$bdd=connect();
if(empty($_POST['q'])){$q=trim(secure::bdd($_POST['main_q']));}
else{$q=trim(secure::bdd($_POST['q']));}
// $q=trim(secure::bdd($_POST['q']));
// recherche LIKE simple uniquement sur le champ 'name'
	$req='SELECT id
		  FROM item
		  WHERE `etat` != "suppr" AND `etat` != "delete" AND 
		  name LIKE "%'.$q.'%"
		   '.$choix.' 
		  ORDER BY RAND()
		  LIMIT 0,'.$max; 
	// echo $req;
	$result0 =   $bdd->query($req); 
	
	if($result0->rowCount() != 0){
	while( $post = $result0->fetch(PDO::FETCH_OBJ))
    {$list0[]=$post->id;
	$id_result[]=array($post->id,1);
    }
$result+=$result0->rowCount();
	}
//recherche FULLTEXT
if($result < $max )
	{
$exclusion0='';
if(isset($list0)){
$exclusion0=' AND id NOT IN (';
$exclusion0.=implode(' , ',$list0).' ) ';
	}
	$nombre=$max-$result;
$req='SELECT id FROM search WHERE (`etat` != "suppr" AND `etat` != "delete" '.$exclusion0.$choix.') AND 
	MATCH (name,descr) AGAINST ('.$bdd->quote($q).') LIMIT 0,'.$nombre;
$result1 =   $bdd->query( $req);
if($result1->rowCount() != 0)
{while( $post = $result1->fetch(PDO::FETCH_OBJ))
    {
	$list1[]=$post->id;
	$id_result[]=array($post->id,2);
	}
$result+=$result1->rowCount();
}
}
// recherche LIKE simple
if($result < $max )
	{
$exclusion1='';
if(isset($list1)){
if(empty($exclusion0)){$exclusion1=' AND ';}else{$exclusion1=' AND ';}
$exclusion1.='id NOT IN (';
$exclusion1.=implode(' , ',$list1).' ) ';
	}
	$nombre=$max-$result;
	$req='SELECT id
		  FROM item
		  WHERE `etat` != "suppr" AND `etat` != "delete"
		  '.$exclusion0.$exclusion1.'
		  AND (name LIKE "%' .$q. '%"
		  OR descr LIKE "%' .$q. '%")
		  '.$choix.'
		  ORDER BY RAND()
		  LIMIT 0,'.$nombre; 
	$result2 = $bdd->query($req); 
	if($result2->rowCount() != 0){
	while( $post = $result2->fetch(PDO::FETCH_OBJ))
    {$list2[]=$post->id;
	$id_result[]=array($post->id,3);
    }
	$result+=$result2->rowCount();
	}
} 

// recherche LIKE avec explode()
if($result < $max )
	{
$exclusion2=' AND ';$mot_s='';
if(isset($list2)){foreach($list2 as $id)
	{
	$exclusion2.= 'AND id !='.$id.' ';
	}}
if($exclusion2!=''){$exclusion2.= 'AND ';}
$mot_q = explode(' ',$q);
foreach($mot_q as $mot)
	{
	$mot_s.='AND (name LIKE "% ' .$mot. ' %"
		  OR descr LIKE "% ' .$mot. ' %") ';
	}
$mot_s='('.mb_substr($mot_s,4).')';
$nombre=$max-$result;
	$req='SELECT id
		  FROM item
		  WHERE  `etat` != "suppr" AND `etat` != "delete"
		  '.$exclusion0.$exclusion1.'
		   '.mb_substr($exclusion2,4). '
		  '.$mot_s.' 
		  '.$choix.'
		  ORDER BY RAND()
		  LIMIT 0,'.$nombre; 
	$result3 =   $bdd->query($req); 

if(isset($result3)){
while( $post = $result3->fetch(PDO::FETCH_OBJ))
    {
	$id_result[]=array($post->id,4);
	}
$result+=$result3->rowCount();
}
}



// affichage des resultats
if( $result == 0){echo '<h3 class="center">Pas de r&eacute;sultats pour cette recherche</h3>';}
else
	{
	echo '<h5 class="center">'.$result.' r&eacute;sultat(s) pour cette recherche</h5>';
	foreach($id_result as $i)
		{
		$item=new item($i[0]);
		echo '<div>'.$item->tableau(1,true).'</div>';
		}
	/*echo '<!--<script type="text/javascript">
	$(".add_item").click //ajout d\'item en ajax
		(
		function()
			{
			$.ajax
				({
				type : "POST", // envoi des données en GET ou POST
				url : "/ajax/ajax_items.php" , // url du fichier de traitement
				data : "id="+this.id.slice(9),  // données à envoyer en  GET ou POST
				success : function(data){
										if(data==\'Element ajouté au personnage actuel\'){var result=\'success-box\';}
										else{var result=\'error-box\';}
										$.easyNotification({
															id:\'notif_add_item\',
															text: data,
															parent: \'#notif_body\',
															classe:result,
															autoClose:\'true\',
															duration:10000,
															closeText: \'\'
															});
										}
				})
			}
		);
</script>-->';
	}*/
	/* /////////  sépration des mots  //////
$mot_q = explode(' ',$q);$mot_s='';
foreach($mot_q as $mot)
	{
	$mot_s.='AND (name LIKE "% ' .$mot. ' %"
		  OR descr LIKE "% ' .$mot. ' %") ';
	}
$mot_s='('.mb_substr($mot_s,4).')';
$mot_t='';
foreach($mot_q as $mot)
	{
	$mot_t.='OR (name LIKE "% ' .$mot. ' %"
		  OR descr LIKE "% ' .$mot. ' %") ';
	}
$mot_t='('.mb_substr($mot_t,3).')';
// '%'.trim($item->name).'%'

/////////////  REQUETE DE RECHERCHE  ////////////////
	$req_txt='		(
				SELECT 1 as sort_col,item.id,item.name
				FROM item
				WHERE `etat` != "suppr" AND `etat` != "delete" AND 
				name LIKE "%'.$q.'%"
				'.$choix.'
				)
			UNION
				(
				SELECT 2 as sort_col,search.id,search.name
				FROM search 
				WHERE (`etat` != "suppr" AND `etat` != "delete" '.$choix.') AND 
				MATCH (name,descr) AGAINST ('.$bdd->quote($q).')
				)
			UNION
				(
				SELECT 3 as sort_col,item.id,item.name
				FROM item
				WHERE `etat` != "suppr" AND `etat` != "delete"
				AND (name LIKE "%'.$q.'%"
				OR descr LIKE "%'.$q.'%")
				'.$choix.'
				)
			UNION
				(
				SELECT 4 as sort_col,item.id,item.name
				FROM item
				WHERE  `etat` != "suppr" AND `etat` != "delete"
				AND '.$mot_s.' 
				'.$choix.'
				)
			UNION
				(
				SELECT 5 as sort_col,item.id,item.name
				FROM item
				WHERE  `etat` != "suppr" AND `etat` != "delete"
				AND '.$mot_t.' 
				'.$choix.'
				)
			ORDER BY sort_col,name
			LIMIT 0,'.$max;

// echo '<pre>'.$req_txt.'</pre>';
$req=$bdd->query($req_txt);
$result=$req->rowCount(); */