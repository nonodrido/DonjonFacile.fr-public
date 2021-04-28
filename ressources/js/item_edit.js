$(document).ready(function(){
$("#name").editInPlace({
		url: '/ajax/ajax_item_edit.php',
		show_buttons: false,
		textarea_rows: "1",
		textarea_cols: "100%",
		value_required: true,
		params: "item_id="+item_id,
		success:function(){$.pnotify({text: 'Item mis à jour !',type:'success'});}
	});
$("#prix").editInPlace({
		url: '/ajax/ajax_item_edit.php',
		show_buttons: false,
		value_required: true,
		params: "item_id="+item_id,
		success:function(){$.pnotify({text: 'Item mis à jour !',type:'success'});}
	});
$("#carac").editInPlace({
		url: '/ajax/ajax_item_edit.php',
		show_buttons: false,
		value_required: true,
		textarea_rows: "1",
		textarea_cols: "100%",
		default_text:$text_carac,
		params: "item_id="+item_id,
		success:function(){$.pnotify({text: 'Item mis à jour !',type:'success'});}
	});
$("#subtype").editInPlace({
		url: '/ajax/ajax_item_edit.php',
		show_buttons: false,
		value_required: true,
		field_type: "select",
		select_text:"nouveau choix",
		select_options: $subtype_list,
		params: "item_id="+item_id,
		success:function(){$.pnotify({text: 'Item mis à jour !',type:'success'});}
	});
$("#emplacement").editInPlace({
		url: '/ajax/ajax_item_edit.php',
		show_buttons: false,
		value_required: true,
		field_type: "select",
		select_text:"nouveau choix",
		select_options: $emplacement_list,
		params: "item_id="+item_id,
		success:function(){$.pnotify({text: 'Item mis à jour !',type:'success'});}
	});
$("#effets").editInPlace({
		url: '/ajax/ajax_item_edit.php',
		show_buttons: false,
		value_required: false,
		textarea_rows: "2",
		textarea_cols: "100%",
		default_text:'Cliquez pour ajouter un effet',
		params: "item_id="+item_id,
		success:function(){$.pnotify({text: 'Item mis à jour !',type:'success'});}
	});
$("#rupture").editInPlace({
		url: '/ajax/ajax_item_edit.php',
		show_buttons: false,
		value_required: true,
		field_type: "select",
		select_text:"nouveau choix",
		select_options: "jamais,1,1 à 2,1 à 3,1 à 4,1 à 5",
		params: "item_id="+item_id,
		success:function(){$.pnotify({text: 'Item mis à jour !',type:'success'});}
	});
$("#descr").editInPlace({
		url: '/ajax/ajax_item_edit.php',
		show_buttons: false,
		field_type: "textarea",
		textarea_rows: "5",
		default_text:'Cliquez pour ajouter une description',
		textarea_cols: "100%",
		value_required: false,
		params: "item_id="+item_id,
		success:function(){$.pnotify({text: 'Item mis à jour !',type:'success'});}
	});





});