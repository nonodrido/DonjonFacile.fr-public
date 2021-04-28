<?php $titre='recherche';
$ajax='function(){
    $field = $("#q");
    $("#ajax-loader").remove(); // on retire le loader
 
    // on commence à traiter à partir du 2ème caractère saisie
    if( $field.val().length > 1 )
    {
      // on envoie la valeur recherché en GET au fichier de traitement
      $.ajax({
  	type : "POST", // envoi des données en GET ou POST
	url : "../ajax/ajax-search.php" , // url du fichier de traitement
	data : $("#search_page").serialize() , // données à envoyer en  GET ou POST
	beforeSend : function() { // traitements JS à faire AVANT envoi
		$("#q").css("background","url('.base64_encode_image('./ressources/img/img_html/ajax-loader.gif','gif',2).') right no-repeat");// ajout du loader pour signifier l\'action
	},
	success : function(data){ // traitements JS à faire APRES le retour d\'ajax-search.php
		$("#q").css("background",""); // on enleve le loader
		$("#results").html(data); // affichage des résultats dans le bloc
		/* $(".url").jTruncate({  
        length: 150,  
        minTrail: 0,  
        moreText: "plus",  
        lessText: "moins",  
        ellipsisText: "[...]",  
        moreAni: "fast",  
        lessAni: "fast"  
    }); */
	}
      });
    }		
  }';
$header='
<!--<script type="text/javascript" src="'.DIR_JS.'jquery.jtruncate.pack.js"></script>-->
'.f_js('$("#q").focus();').'
<style>
.ajax p{
	margin:20px 0;
	text-align:center;
}
.ajax label{
	font-size:16px;
	font-weight:bold;
	padding:3px;
}
.ajax label span{
	font-size:12px;
	color:#777;
}
.ajax input{
	width:500px;
	padding:3px;
	border:1px solid #aaa;
	height:22px;
	line-height:22px;
}
#ajax-loader{
	margin:15px auto 0 auto;
	display:block;
}
//div.article-result{
	//border-bottom:1px solid #ccc;
}
div.article-result p.url{
	color:#777;
}
</style>

<script type="text/javascript">
$(document).ready( function() {
  // détection de la saisie dans le champ de recherche
  $("#q").keyup('.$ajax.');
  $("#choix_subtype").keyup('.$ajax.');
  $("input").change('.$ajax.');
  $("select").change('.$ajax.');
  /*$(".item_descr").jTruncate({  
        length: 200,  
        minTrail: 0,  
        moreText: "plus",  
        lessText: "moins",  
        ellipsisText: "",  
        moreAni: "fast",  
        lessAni: "fast"  
    });*/
});
</script>';
verif_uri('/search');
?> 


<!--debut du formulaire-->
<!--<script>
$(function() {
    $("fieldset").collapse({head:'.hide',show: function(){this.animate({opacity: 'toggle', height: 'toggle'}, 200);},hide : function() {this.animate({opacity: 'toggle', height: 'toggle'}, 200);}});
});
</script>-->
<form  action="search.html" method="post" id="search_page">
		<div class="center">
			<div class="ajax input-append center">
				<!--<label id="test" for="q" style="padding-top:15px;">Rechercher :</label>-->
				<input type="search" name="q" id="q" autocomplete="off" 
				<?php if(isset($_POST['main_q'])){echo 'value="'.$_POST['main_q'].'"';}else{if(isset($_POST['q']))
				{echo 'value="'.$_POST['q'].'"';}} ?> 
				autofocus required placeholder="Rechercher"
				style="padding-right:15px;" />
				<button id="advanced_button" type="button" class="btn" data-toggle="collapse" data-target="#advanced">
				  Recherche avancée <i class="caret"></i>
				</button>
				
			</div><div id="nbresult"></div>
		</div>
		<div id="advanced" class="<?php if(!isset($_GET['advanced'])){echo'collapse';}?>"><div class="well">
		<fieldset><legend>Recherche avancée :</legend><?php //if(empty($_POST['choix_table']) OR (isset($_POST['choix_table']) AND sizeof($_POST['choix_table'])==5 AND isset($_POST['choix_mode']) AND $_POST['choix_mode']=='normal' AND isset($_POST['choix_nbre']) AND $_POST['choix_nbre']==10)){echo'class="hide"';} ?>
		<ul class="unstyled">
			<li><i>Choix de la ou des sections de recherche :</i></li>
			<li>
			<div class="row-fluid">
				<div class="span6">
					<label class="checkbox"><input type="checkbox" name="choix_table[]" id="arme" value="arme" <?php if(!isset($_POST['choix_table']) OR in_array('arme',$_POST['choix_table'])){echo 'checked';} ?>>Armement</label>
					<label class="checkbox"><input type="checkbox" name="choix_table[]" id="comp" value="comp" <?php if(!isset($_POST['choix_table']) OR in_array('comp',$_POST['choix_table'])){echo 'checked';} ?>>Compétences</label>
					<label class="checkbox"><input type="checkbox" name="choix_table[]" id="protec" value="protec" <?php if(!isset($_POST['choix_table']) OR in_array('protec',$_POST['choix_table'])){echo 'checked';} ?>>Protection</label>
					<label class="checkbox"><input type="checkbox" name="choix_table[]" id="divers" value="divers" <?php if(!isset($_POST['choix_table']) OR in_array('divers',$_POST['choix_table'])){echo 'checked';} ?>>Divers</label>
				</div>
				<div class="span6">
					Choix d'un type d'objet :
					<input id="choix_subtype" name="choix_subtype" type="text" />
				</div>
			</div>
			</li>
			<li><i>Choix du mode de recherche :</i></li>
			<li class="form-inline">Afficher les items
			<label class="radio"><input type="radio" name="choix_mode" id="normal" value="normal" <?php if(isset($_POST['choix_mode']) AND $_POST['choix_mode']=='normal'){echo 'checked';} ?>>officiels et créés par moi</label>
			<label class="radio"><input type="radio" name="choix_mode" id="officiel" value="officiel" <?php if(isset($_POST['choix_mode']) AND $_POST['choix_mode']=='officiel'){echo 'checked';} ?>>officiels uniquement</label>
			<label class="radio"><input type="radio" name="choix_mode" id="me" value="me" <?php if(isset($_POST['choix_mode']) AND $_POST['choix_mode']=='me'){echo 'checked';} ?>>créés par moi uniquement</label>
			<label class="radio"><input type="radio" name="choix_mode" id="all" value="all" <?php if(!isset($_POST['choix_mode']) OR $_POST['choix_mode']=='all'){echo 'checked';} ?>>tous</label>
			</li>
			<li>
			<label>Nombre de resultats affichés : 
				<select id="choix_nbre" class="span1" name="choix_nbre">
				<option value="5" <?php if(isset($_POST['choix_nbre']) AND $_POST['choix_nbre']==5){echo 'selected';} ?>>5</option>
				<option value="10" <?php if(!isset($_POST['choix_nbre']) OR $_POST['choix_nbre']==10){echo 'selected';} ?>>10</option>
				<option value="15" <?php if(isset($_POST['choix_nbre']) AND $_POST['choix_nbre']==15){echo 'selected';} ?>>15</option>
				<option value="20" <?php if(isset($_POST['choix_nbre']) AND $_POST['choix_nbre']==20){echo 'selected';} ?>>20</option>
				<option value="25" <?php if(isset($_POST['choix_nbre']) AND $_POST['choix_nbre']==25){echo 'selected';} ?>>25</option>
				<option value="30" <?php if(isset($_POST['choix_nbre']) AND $_POST['choix_nbre']==30){echo 'selected';} ?>>30</option>
				</select>
			</label>
			</li>
		</ul>
		</fieldset>
		</div></div>
</form>
<?php
$array=array_merge($array_divers,$array_arme,$array_protec,$array_comp);
sort($array);
?>
<script>
$(document).ready(function(){
	var subtype_list=<?php echo json_encode($array);?>;
	$('#choix_subtype').typeahead({
    source: subtype_list
    });
});
</script>
<!--fin du formulaire-->
 
<!--preparation de l'affichage des resultats-->
<div id="results">
<?php $direct=true;if(isset($_POST['main_q'])){include('ajax/ajax-search.php');} ?>
</div>