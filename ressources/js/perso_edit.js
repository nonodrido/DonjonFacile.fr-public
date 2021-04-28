function perso_toggle()
	{
	$('.modif').toggleClass('hidden');
	$("#xp").toggleClass('img-polaroid');
	$("#PO").toggleClass('img-polaroid');
	$("#PA").toggleClass('img-polaroid');
	$("#PC").toggleClass('img-polaroid');
	$("#LB").toggleClass('img-polaroid');
	$("#LT").toggleClass('img-polaroid');
	$("#descr_div").toggleClass('img-polaroid');
	$("#name").toggleClass('img-polaroid');
	$("#origine").toggleClass('img-polaroid');
	$("#metier").toggleClass('img-polaroid');
	$("#sexe").toggleClass('img-polaroid');
	$("#eamax").toggleClass('img-polaroid');
	$("#evmax").toggleClass('img-polaroid');
	$("#PDest").toggleClass('img-polaroid');
	$("#specialisation").toggleClass('img-polaroid');
	$("#AT").toggleClass('img-polaroid');
	$("#PRD").toggleClass('img-polaroid');
	}
$(document).ready(function(){
setInterval(function(){
					$('#total_money')
					.html(Math.round(parseFloat(parseInt($('#LB').html())*500+parseInt($('#LT').html())*100+parseInt($('#PO').html())+parseInt($('#PA').html())/10+
					 parseInt($('#PC').html())/100)*100)/100);
					$exp=parseInt($('#xp').html())
					if($exp==0){$('#niv').html('1');}
					else{
						$lvl=0;
						$lvl_xp=0;
						while($exp > $lvl_xp)
							{
							$lvl=$lvl+1;
							$lvl_xp=$lvl_xp+($lvl-1)*100;
							}
						$lvl=$lvl-1;
						$('#niv').html(parseInt($lvl)); 
						}
					$('#res_mag').html(Math.round((parseInt($('#INTL').html())+parseInt($('#COU').html())+parseInt($('#FO').html()))/3));
					$('#mag_psy').html(Math.round((parseInt($('#INTL').html())+parseInt($('#CHA').html()))/2));
					$('#mag_phys').html(Math.round((parseInt($('#INTL').html())+parseInt($('#AD').html()))/2));
					},1000);
$("#xp").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Expérience mise à jour !',type:'success'});}
	});
$("#img").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		default_text: img_old,
		success:function(){$.pnotify({text: 'Avatar mis à jour !',type:'success'});}

	});
$("#PDest").editInPlace({
	// callback: function(unused, enteredText) { return enteredText; },
	url: '/ajax/ajax_perso_edit.php',
	show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Points de destin mis à jour !',type:'success'});}
	});
$("#PO").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'PO mises à jour !',type:'success'});}
	});
$("#AT").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Attaque mise à jour !',type:'success'});}
	});
$("#PRD").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Parade mise à jour !',type:'success'});}
	});
$("#PA").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'PA mises à jour !',type:'success'});}
	});
$("#PC").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'PC mises à jour !',type:'success'});}
	});
$("#LT").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Lingot(s) de Thritil mis à jour !',type:'success'});}
	});
$("#LB").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'lingot(s) de Berylium mis à jour !',type:'success'});}
	});
$("#name").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Nom mis à jour !',type:'success'});}
	});
$("#descr").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: false,
		field_type: "textarea",
		textarea_rows: "15",
		textarea_cols: "125",
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Description mis à jour !',type:'success'});}
	});
$("#evmax").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Energie vitale mis à jour !',type:'success'});}
	});
$("#eamax").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Energie astrale mis à jour !',type:'success'});}
	});
$("#COU").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Courage mis à jour !',type:'success'});}
	});
$("#CHA").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Charisme mis à jour !',type:'success'});}
	});
$("#AD").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Adresse mise à jour !',type:'success'});}
	});
$("#INTL").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Intelligence mise à jour !',type:'success'});}
	});
$("#FO").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Force mise à jour !',type:'success'});}
	});
/* $("#").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: '/ajax/ajax_perso_edit.php',
		show_buttons: false,
		value_required: true,
		params: "perso_id="+perso_id
	}); */
$("#sexe").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: "/ajax/ajax_perso_edit.php",
		field_type: "select",
		select_text:"nouveau choix",
		value_required: true,
		select_options: "masculin, feminin",
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Sexe mis à jour !',type:'success'});}
	});
$("#metier").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: "/ajax/ajax_perso_edit.php",
		field_type: "select",
		value_required: true,
		select_text:"nouveau choix",
		select_options: "Assassin,Bourgeois,Gladiateur,Guerrier,Mage,Marchand,Nécromancien,Noble,Paladin,Pirate,Prêtre,Ranger,Sorcier,Voleur",
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Metier mis à jour !',type:'success'});}
	});
$("#origine").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: "/ajax/ajax_perso_edit.php",
		field_type: "select",
		value_required: true,
		select_text:"nouveau choix",
		select_options: "Barbare,Demi-Orque,Elfe Noir,Elfe Sylvain,Gnôme,Gobelin,Haut Elfe,Hobbit,Humain,Nain,Ogre,Orque",
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Origine mise à jour !',type:'success'});}
	});
$("#adv_opt").editInPlace({
		// callback: function(unused, enteredText) { return enteredText; },
		url: "/ajax/ajax_perso_edit.php",
		field_type: "select",
		value_required: true,
		select_text:"nouveau choix",
		select_options: "Automatique,Guerrier,Archer,Mage",
		params: "perso_id="+perso_id,
		success:function(){$.pnotify({text: 'Fiche avancée mise à jour !',type:'success'});}
	});



});



/*$(document).ready(function(){
	
	// All examples use the commit to function interface for ease of demonstration.
	// If you want to try it against a server, just comment the callback: and 
	// uncomment the url: lines.
	
	// The most basic form of using the inPlaceEditor
	$("#editme1").editInPlace({
		callback: function(unused, enteredText) { return enteredText; },
		// url: './server.php',
		show_buttons: true
	});


	// This example shows how to call the function and display a textarea
	// instead of a regular text box. A few other options are set as well,
	// including an image saving icon, rows and columns for the textarea,
	// and a different rollover color.
	$("#editme2").editInPlace({
		callback: function(unused, enteredText) { return enteredText; },
		// url: "./server.php",
		bg_over: "#cff",
		field_type: "textarea",
		textarea_rows: "15",
		textarea_cols: "35",
		saving_image: "./images/ajax-loader.gif"
	});

	// A select input field so we can limit our options
	$("#editme3").editInPlace({
		callback: function(unused, enteredText) { return enteredText; },
		// url: "./server.php",
		field_type: "select",
		select_options: "Change me to this, No way:no"
	});

	// Using a callback function to update 2 divs
	$("#editme4").editInPlace({
		callback: function(original_element, html, original){
			$("#updateDiv1").html("The original html was: " + original);
			$("#updateDiv2").html("The updated text is: " + html);
			return(html);
		}
	});
	
	$("#editme5").editInPlace({
		saving_animation_color: "#ECF2F8",
		callback: function(idOfEditor, enteredText, orinalHTMLContent, settingsParams, animationCallbacks) {
			animationCallbacks.didStartSaving();
			setTimeout(animationCallbacks.didEndSaving, 2000);
			return enteredText;
		}
	});
	
	// If you need to remove an already bound editor you can call

	// > $(selectorForEditors).unbind('.editInPlace')

	// Which will remove all events that this editor has bound. You need to make sure however that the editor is 'closed' when you call this.
	
});*/