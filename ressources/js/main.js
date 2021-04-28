function setCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}
function delCookie(name) {
	setCookie(name,"",-1);
}
function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}
function ajax_API($url,$data)
	{
	cursor_switch();
	$.ajax
		({
		type : "POST", // envoi des données en GET ou POST
		url : $url , // url du fichier de traitement
		data: $data,
		dataType:'json',
		success : function(data)
			{
			console.log(data);
			$.pnotify
				({
				text: data.msg,
				type:data.type,
				title:data.title
				});
			eval(data.action);
			},
		error : function(jqXHR, textStatus, errorThrown)
			{
			console.log(textStatus+' : '+errorThrown);
			$.pnotify
				({
				text:"Le serveur est indisponible.",
				title:"Erreur de connexion",
				type:"error"
				});
			}
		});
	cursor_switch();
	}
function href_modal(elem) 
	{
	cursor_switch();
	var url = $(elem).attr('href');
	var txt = $(elem).html();
	$.post(url, function(data) {
		$('<div class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'
		+'<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'
		+'<h3 id="modalLabel">'+txt+'</h3></div><div class="modal-body">'
		+ data 
		+ '</div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button></div></div>')
		.modal();
		}).success(function() { $('input:text:visible:first').focus();cursor_switch();});
	return false;
	}
function add_item($item_id,$perso_id,$mode)
		{
		$mode = ($mode ? $mode : "qte");
		ajax_API("/ajax/ajax_item_add.php","item_id="+$item_id+"&perso_id="+$perso_id+"&mode="+$mode);
		};
function add_item_equip($item_id,$perso_id)
		{
		ajax_API("/ajax/ajax_item_add.php","mode=equip&item_id="+$item_id+"&perso_id="+$perso_id);
		};
function additemfromname($perso_id)
		{
		var $item_name=$('#add_item_from_name').val();
		ajax_API("/ajax/ajax_item_add_from_name.php","item_name="+$item_name+"&perso_id="+$perso_id);
		};
function group_perso($perso_id,$group_id)
		{
		ajax_API("/ajax/ajax_group_perso.php","group_id="+$group_id+"&perso_id="+$perso_id);
		};
function delete_item($item_id,$perso_id,$mode)
		{
		$mode = ($mode ? $mode : "qte");
		ajax_API("/ajax/ajax_item_delete.php","item_id="+$item_id+"&perso_id="+$perso_id+"&mode="+$mode);
		};
function qte_item($item_id,$perso_id,$qte,$mode)
		{
		$mode = ($mode ? $mode : "qte");
		ajax_API("/ajax/ajax_item_qte.php","item_id="+$item_id+"&perso_id="+$perso_id+"&qte="+$qte+"&mode="+$mode);
		};
function rate($rate,$item_id)
		{
		ajax_API("/ajax/ajax_item_rate.php","item_id="+$item_id+"&rate="+$rate);
		};
function item_equip($perso_id,$item_id,$mode)
		{
		ajax_API("/ajax/ajax_item_etat.php","item_id="+$item_id+"&perso_id="+$perso_id+"&mode="+$mode);
		}
function fav($id,$type)
		{
		ajax_API("/ajax/ajax_fav.php","fav_id="+$id+"&type="+$type);
		$('#btn_fav_'+$type+'_'+$id).toggleClass('active');
		}
function clignote (){ 
   $(".clignote").fadeOut(700).delay(100).fadeIn(500); 
}
function time_update()
	{
	// gestion différentielle du temps => pb tps serveur !
	$('time').each(function(){
	var val_bdd = $(this).data('date');
	var val_final = moment(val_bdd,"YYYY-MM-DD HH:mm:ss").fromNow();
	$(this).html(val_final);
	});
	}
function reload_content($url,$mode)
	{
	if (!$mode)
		{
		$('#contenu_global').prepend('<div style="width:100%;height:100%;background:black;opacity: .8;filter: alpha(opacity=80);"></div><div style="position:fixed;top:50%;left:50%;z-index:10000000000000000;" class="well center"><img src="/ressources/img/img_html/loader_big.gif"/><br>Mise à jour de la page<br>Veuillez patienter</div></div>');
		}
	setTimeout(function() {
		 $('#contenu_global').load($url);
		}, 1000);
	}
function toggle_menu()
	{
	// .hasClass(
	// $(this).switchClass( "input-xlarge", "input-medium", 1000 );
	// $('#main_page').toggleClass('row-fluid',200);
	if($('#side_menu').hasClass('null_width'))
		{
		$('#side_menu').switchClass( "null_width hidden", "span2", 1000 );
		}
	else{
		$('#side_menu').switchClass( "span2", "null_width hidden", 1000 );
		}
	$('#corps').toggleClass('span10');
	// $('#side_menu').toggleClass('hidden');
	}
moment.lang('fr', {
    months : "Janvier_Février_Mars_Avril_Mai_Juin_Juillet_Août_Septembre_Octobre_Novembre_Décembre".split("_"),
    monthsShort : "Jan_Fev_Mar_Avr_Mai_Juin_Juil_Aou_Sep_Oct_Nov_Dec".split("_"),
    weekdays : "Dimanche_Lundi_Mardi_Mercredi_Jeudi_Vendredi_Samedi".split("_"),
    weekdaysShort : "Dim_Lun_Mar_Mer_Jeu_Ven_Sam".split("_"),
    longDateFormat : { 
        L : "DD/MM/YYYY",
        LL : "D MMMM YYYY",
        LLL : "D MMMM YYYY HH:mm",
        LLLL : "dddd, D MMMM YYYY HH:mm"
    },
    meridiem : {
        AM : 'AM',
        am : 'am',
        PM : 'PM',
        pm : 'pm'
    },
    calendar : {
        sameDay: "[Ajourd'hui à] LT",
        nextDay: '[Demain à] LT',
        nextWeek: 'dddd [à] LT',
        lastDay: '[Hier à] LT',
        lastWeek: 'dddd [dernier à] LT',
        sameElse: 'L'
    },
    relativeTime : {
        future : "Dans %s",
        past : "il y a %s",
        s : "un instant",
        m : "une minute",
        mm : "%d minutes",
        h : "une heure",
        hh : "%d heures",
        d : "un jour",
        dd : "%d jours",
        M : "un mois",
        MM : "%d mois",
        y : "un an",
        yy : "%d ans"
    },
    ordinal : function (number) {
        return (~~ (number % 100 / 10) === 1) ? 'er' : 'ème';
    }
});
function cursor_switch()
	{
	if($('body').css('cursor')=='auto'){$('body').css('cursor','wait');}
	else{$('body').css('cursor','auto')}
	// $('body').delay(50).css('cursor','auto');
	}
function update_user_chat()
	{
	$('#nb_chat_user').load('/ajax/nb_user_chat.php');
	}
$(document).error(function(){
	  alert("Une erreur s'est produite !");
	});
/* ///////////////////////////////////////////////////////////// */
/* /////////////////////////////READY/////////////////////////// */
/* ///////////////////////////////////////////////////////////// */
$(document).ready(function()
	{
	setInterval('clignote()',1200);
	setInterval('update_user_chat()',20000);
	setInterval('time_update()',60000);
	time_update();

	// surlignage lien page active
	var $url= window.location.pathname+window.location.hash;
	$("a[href='"+$url+"']").parent().addClass('active');
	// popover et tooltip => utilité du tooltip ?
	$("a[rel=popover]").popover();
	// $("a[toolrel=tip]").tooltip();
	/////////  Gestion de l'accès aux ancres cachés sous le menu horizontal  ////////
	// $('a[href*="#"]').click(function(){sleep(100);window.scrollBy(0,-50);});
	
	// Gestion auto des modal avec url
	$('a[data-toggle="modal"][href]').click(function(e) {cursor_switch();
		e.preventDefault();
		var url = $(this).attr('href');
		var txt = $(this).html();
		if (url.indexOf('#') == 0) {
			$(url).modal('open');
		} else {
			$.get(url, function(data) {
				$('<div class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'
				+'<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'
				+'<h3 id="modalLabel">'+txt+'</h3></div><div class="modal-body">'
				+ data 
				+ '</div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button></div></div>')
				.modal();
			}).success(function() { $('input:text:visible:first').focus();cursor_switch();});
		}
	});
//////// autocompletion  //////////
	$('.ajax-typeahead-search').typeahead({
    source: list_item_name
    });
	$('.ajax-typeahead-pseudo').typeahead({
    source: list_pseudo_name
    });
	
	// switchclass pour la barre de recherche
	$( "#search_input_main" ).focus(function(){
      $(this).switchClass( "input-medium", "input-xlarge", 1000*2/3 );
      return false;
    });
	$( "#search_input_main" ).blur(function(){
      if($(this).val()=='')
		{$(this).switchClass( "input-xlarge", "input-medium", 1000 );}
      return false;
    });
	
	// DATATABLES (donnée de traduction + lancement)
	$('.jtable').dataTable({
		// "bStateSave": true,
		// "bJQueryUI": true,
        "sPaginationType": "full_numbers",
		"iDisplayLength":50,
		"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
		"sWrapper": "<span class=\"searchword\">dataTables</span>_wrapper form-inline",
		"oLanguage": {
					"sProcessing":     "Traitement en cours...",
					"sLengthMenu":     "Afficher _MENU_ éléments",
					"sZeroRecords":    "Aucun élément à afficher",
					"sInfo":           "Affichage de l'élement _START_ à _END_ sur _TOTAL_ éléments",
					"sInfoEmpty":      "Affichage de l'élement 0 à 0 sur 0 éléments",
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
		});
		
		// BACKTOTOP
		$(window).scroll(function() {
		if($(this).scrollTop() != 0) {
					$('#toTop').fadeIn();	
				} else {
					$('#toTop').fadeOut();
				}
			});
		 
			$('#toTop').click(function() {
				$('body,html').animate({scrollTop:0},800);
			});
	}//END DOC READY
);