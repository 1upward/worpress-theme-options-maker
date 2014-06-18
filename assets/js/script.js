jQuery(document).ready(function($) {

	sizeContent();
	$(window).resize(sizeContent);

	function sizeContent() {
		var windowSize = $('#wpbody-content').width();
		var main = windowSize - 370;
		// alert(windowSize);
		$('.metabox-main').width(main+'px');
	}
	
	$('.container-body').each(function() {
		if (jQuery.trim ($(this).text()) == "") {
			$(this).append('iseh kosong');
		}
	});

	// Loads the color pickers
	$('.tom-color').wpColorPicker();

	// Image Options
	$('.tom-radio-img-img').click(function(){
		$(this).parent().parent().find('.tom-radio-img-img').removeClass('tom-radio-img-selected');
		$(this).addClass('tom-radio-img-selected');
	});

	$('.tom-radio-img-label').hide();
	$('.tom-radio-img-img').show();
	$('.tom-radio-img-radio').hide();

	// Loads tabbed sections if they exist
	if ( $('.nav-tab-wrapper').length > 0 ) {
		tom_tabs();
	}

	function tom_tabs() {

		var $group = $('.group'),
			$navtabs = $('.nav-tab-wrapper a'),
			active_tab = '';

		// Hides all the .group sections to start
		$group.hide();

		// Find if a selected tab is saved in localStorage
		if ( typeof(localStorage) != 'undefined' ) {
			active_tab = localStorage.getItem('active_tab');
		}

		// If active tab is saved and exists, load it's .group
		if ( active_tab != '' && $(active_tab).length ) {
			$(active_tab).fadeIn();
			$(active_tab + '-tab').addClass('nav-tab-active');
		} else {
			$('.group:first').fadeIn();
			$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
		}

		// Bind tabs clicks
		$navtabs.click(function(e) {

			e.preventDefault();

			// Remove active class from all tabs
			$navtabs.removeClass('nav-tab-active');

			$(this).addClass('nav-tab-active').blur();

			// if (typeof(localStorage) != 'undefined' ) {
			// 	localStorage.setItem('active_tab', $(this).attr('href') );
			// }

			var selected = $(this).attr('href');

			$group.hide();
			$(selected).fadeIn();
		});
	}

	/* Prevent drag on action button */ 
	$(".dd").delegate("a", "mousedown", function(event) { // mousedown prevent nestable click
	    event.preventDefault();
	    return false;
	});

	$(".dd").delegate( "a.delete-nestable", "click", function(event) { // click event
	    event.preventDefault();
	    if (confirm("Are you sure to delete option ?")) {
		  // alert("sure banget ya..");
		  $(this).closest( "li" ).fadeOut(500, function() { $(this).remove(); });
		 }

	    return false;
	});

	$(".dd").delegate( "a.edit-nestable", "click", function(event) { // click event
	    event.preventDefault();
	    var id = $(this).closest( "li" ).attr("data-id");
	   	// alert(id);
	   	$('.nestable-input#'+id).slideToggle('fast');

	    return false;
	});

	/* Trigger cek display options on document ready */
	// $('.tom-type').each(function(index,element){
		// displayOptions(element);/* DIsplay default form */
	  	// showDefault(element);
	// });

	/* Trigger cek display options if select type change */
	$(document).delegate( ".tom-type", "change", function(event) { 
		event.preventDefault();
		displayOptions(this);/* DIsplay default form */
	  	showDefault(this);
	});

	/* function to display or hide repeatable options, and default field */
	function displayOptions(element){

		var containerId = $(element).attr('data-container');
		var arrayName = 'tom_options['+containerId+']';

		var templateRepeatable 	= '<div data-order="1" class="input-options-group">';
		templateRepeatable 		+= 	'<i class="dashicons dashicons-yes"></i>';
		templateRepeatable 		+= 	'<input class="input-opt input-key" name="'+arrayName+'[options][opt-key][]" value="" placeholder="Key">';
		templateRepeatable 		+= 	'<input class="input-opt input-val" name="'+arrayName+'[options][opt-val][]" value="" placeholder="Value">';
		templateRepeatable 		+= 	'<a class="btn-remove dashicons dashicons-dismiss"></a>';
		templateRepeatable 		+= '</div>';

		
		var showOptions = false;
		var val = $(element).val();
		/* switch value of type to display option and default for */
	  	switch (val){
		  	case "select":
		  		showOptions = true;
		  		break;

		  	case "radio":
		  		showOptions = true;
		  		break;

		  	case "multicheck":
		  		showOptions = true;
		  		break;

		  	case "select-image":
		  		showOptions = true;
		  		break;

		  	default:
		  		showOptions = false;
	  	}

	  	if (showOptions == true) {
	  		/* check if repeatable field not exist and append it */
			// var cek = $('#add-opt-'+containerId).find('.input-options-group');
			// if (!cek.length) {
			// 	$('#add-opt-'+containerId).html(templateRepeatable);
			// }
			// /* Show repeatable field */
	  		// 	$('#'+containerId+'-options').fadeIn(500);
	  		$('#add-opt-'+containerId).html(templateRepeatable);
	  		$('#'+containerId+'-options').fadeIn(500);
	  	} else {
	  		/* if false hide it */
	  		$('#'+containerId+'-options').fadeOut(500);
	  	}
	  	
	}

	$(document).delegate( ".input-val", "blur", function(event) { 
		event.preventDefault();
		var idDefaultForm = $(this).closest('.options-container').attr('data-default');
		updateDefaultOption(idDefaultForm);
	});

	function showDefault(element) {
		var containerId = $(element).attr('data-container');
		var arrayName = 'tom_options['+containerId+']';
		var type = $(element).val();
		var valDefault = $('#'+containerId+'-hidden-default').val();
		

		switch (type){
		  	case "select":
		  		inputDefault = 	'<select name="'+arrayName+'[default]" id="tom-default-'+containerId+'">';
		  		inputDefault += '<option value="">Select default option</option>';
		  		inputDefault += '</select>';
		  		updateDefaultOption(containerId);
		  		break;

		  	case "textarea":
		  		inputDefault = '<textarea name="'+arrayName+'[default]" id="tom-default-'+containerId+'"></textarea>';
		  		break;

		  	case "radio":
		  		inputDefault = 	'<select name="'+arrayName+'[default]" id="tom-default-'+containerId+'">';
		  		inputDefault += '<option value="">Select default option</option>';
		  		inputDefault += '</select>';
		  		updateDefaultOption(containerId);
		  		break;

		  	case "checkbox":
		  		inputDefault = 	'<input type="checkbox" name="'+arrayName+'[default]" id="tom-default-'+containerId+'" value="true">';
		  		break;

		  	default:
		  		inputDefault = '<input name="'+arrayName+'[default]" id="tom-default-'+containerId+'" type="text" value="'+valDefault+'">';
	  	}

	  	$('#'+containerId+'-default').html(inputDefault);
	}

	
	function updateDefaultOption(containerId) {
			var optionDefault="";
			var key = [];
			var val = [];
			var input = $('#add-opt-'+containerId+' :input');
			input.each(function(i, field){ 
				// console.log(field);
				if (field.placeholder == 'Key'){
						key.push(field.value);
				}
				if (field.placeholder == 'Value'){
					val.push(field.value);
				}
			});

			var arr3 = {};
				for (var i = 0; i < key.length; i++) {
				    arr3[key[i]] = val[i];
				}
			$.each( arr3, function( key, val ) {
				if (input.val().length) {
			    	optionDefault += '<option value="'+key+'">'+val+"</option>";
			    } else {
			    	optionDefault += '<option value="">Select default option</option>';
			    }
			  });
			$('#tom-default-'+containerId).html(optionDefault);
			
	}


	/* Clone for repeatable options */
	$(document).delegate( "a#new-repeatable", "click", function(event) { // click event
	    event.preventDefault();
	    // alert('ok');
	    
	    /* get parent id to append */
		var idToAppend = $( this ).closest('.options-container').find('.input-options').first().attr('id');
	    /* get element to clone */
	    var elemToClone = $( '#'+idToAppend).find('.input-options-group');
		var oldOrder = parseInt(elemToClone.last().attr('data-order'));
		var newOrder = oldOrder+1;
		// alert(idToAppend);

		

	    // var cloneInput = templateRepeatable;
	    var cloneInput = $( '#'+idToAppend).find('.input-options-group').first().clone();
	    // alert(cloneInput);
	    cloneInput.attr('data-order', newOrder);
	    // cloneInput.find('.label-opt').html( newOrder+' : ');
	    cloneInput.find('input.input-opt').val('');
	    cloneInput.appendTo( '#'+idToAppend );

	    return false;
	});

	/* Delete repeatable options */
	$(document).delegate( "a.btn-remove", "click", function(event) { // click event
	    event.preventDefault();
	    var repeatableInput = $(this).closest('.input-options').find('.input-options-group');
	    var idDefaultForm = $(this).closest('.options-container').attr('data-default');
	    // var x = $(this).closest('.options-container');
	    // var c =$(x).prev();
	    // alert(idDefaultForm);

	    /* if the input remaining one, disable the delete and just emptied */
	    if (repeatableInput.length <= '1' ) {
	    	$(this).closest('.input-options-group').find('.input-opt').val('');
	    	updateDefaultOption(idDefaultForm);
	    	showDefault();
	    	return false;
	    }

 		$(this).closest( "div.input-options-group" ).fadeOut(500, function() { 
 			$(this).remove(); 
	    	updateDefaultOption(idDefaultForm);
 		});
	
	    return false;
	});


	/* Add / Clone option to nestable list*/
	$(document).delegate( "#tom-add-options", "click", function(event) {
		event.preventDefault();
		var id = $("#add-tom-options input[id=tom-id-new-data]").val();
        var id = id.replace(/\s+/g, '').toLowerCase();
        if (!id.length){
    		alert('Option ID is required!');
    		return;
    	}
		var arrayName = 'tom_options['+id+']';

        var name = $("#add-tom-options input[id=tom-name-new-data]").val();
        var desc = $("#add-tom-options textarea#tom-desc-new-data").val();
        var type = $("#add-tom-options select#tom-type-new-data").val();
        var defaultValue = $("#add-tom-options input[id=tom-default-new-data]").val();

		var activeDiv = $('.nav-tab-active').attr('href');

		template ='<li class="dd-item" data-id="'+id+'">';
		template +='  <div class="dd-handle">'+name+'';
		template +='    <span class="tom-action-buttons">';
		template +='      <a class="blue edit-nestable" href="#">';
		template +='        <i class="dashicons dashicons-edit"></i>';
		template +='      </a>';
		template +='      <a class="red delete-nestable" href="#">';
		template +='        <i class="dashicons dashicons-trash"></i>';
		template +='      </a>';
		template +='    </span>';
		template +='  </div>';
		template +='  <div class="nestable-input" id="'+id+'" style="display:none;">';
		template +='    <table class="widefat"><tbody><tr class="inline-edit-row inline-edit-row-page inline-edit-page quick-edit-row quick-edit-row-page inline-edit-page alternate inline-editor"><td colspan="5" class="colspanchange" style="padding-bottom:10px;">';
		template +='        <fieldset class="inline-edit-col-left">';
		template +='          <div class="inline-edit-col">';
		template +='            <h4>Edit Option : '+id+'</h4>';
		template +='            <label>';
		template +='              <span class="title">Name</span>';
		template +='              <span class="input-text-wrap input">';
		template +='                <input type="text" name="'+arrayName+'[name]" value="'+name+'">';
		template +='              </span>';
		template +='            </label>';
		template +='            <label>';
		template +='              <span class="title">Description</span>';
		template +='              <span class="input-text-wrap input">';
		template +='                <textarea name="'+arrayName+'[desc]">'+desc+'</textarea>';
		template +='              </span>';
		template +='            </label>';
		template +='          </div>';
		template +='        </div>';
		template +='      </fieldset>';
		template +='        <fieldset class="inline-edit-col-right">';
		template +='          <div class="inline-edit-col">';
		template +='            <label>';
		template +='              <span class="title">Type</span>';
		template +='              <span id="select_'+id+'" class="input-text-wrap input">';
		/********************************************************************
		*		APPENDED BY FUNCTION 
		********************************************************************/
		template +='              </span>';
		template +='            </label>';
		template +='			<label id="'+id+'-options">';
		template +='			  <span class="title">Options</span>';
		template +='			  <span class="input-text-wrap input">';
		template +='			  <div id="opt-container-'+id+'" class="options-container" data-default="'+id+'">';
		/********************************************************************
		*		APPENDED BY FUNCTION 
		********************************************************************/
		template +='			  <p><a id="new-repeatable" href="#">Add New Field</a></p>';
		template +='			  </div>';
		template +='	          </span>';
		template +='			</label>';
		template +='            <label>';
		template +='              <span class="title">Default</span>';
		template +='              <span class="input-text-wrap input">';
		template +='              	<input type="hidden" id="'+id+'-hidden-default" value="'+defaultValue+'">';
		template +='              	<div id="'+id+'-default">';
		/********************************************************************
		*		APPENDED BY FUNCTION 
		********************************************************************/
		template +='              	</div>';
		template +='              </span>';
		template +='            </label>';
		template +='          </div>';
		template +='        </fieldset>';
		template +='      </tbody>';
		template +='    </table>';
		template +='  </div>';
		template +='</li>';


		$(activeDiv).find('ol.dd-list').append(template);
		displayOptions('#tom-type-'+id);
		showDefault('#tom-type-'+id);
		cloneNewData(id);
		/* Clear input */
		$('#add-tom-options').find('option:first').attr('selected', 'selected');
		$('#new-data-options').hide();
		$('#add-opt-new-data').html('');
		$('#add-tom-options').find('input, textarea').val(''); 
		$('#new-data-default').html('<input name="default" type="text" id="tom-default-new-data" value="">'); 
	});


	/* function to clone option type, repeatable options to nestable */
	function cloneNewData(id) {
		// alert(id);
		var arrayName = 'tom_options['+id+']';

		/* Clone select type */
		var orgType = $('#tom-type-new-data');
		var type = orgType.clone();
		type.each(function(index, item) {
		     //set new select name and value 
		     $(item).attr( 'name', arrayName+'[type]' );
		     $(item).attr( 'id', 'tom-type-'+id );
		     $(item).attr( 'data-container', id );
		     $(item).val( orgType.eq(index).val() );

		});
		type.appendTo('#select_'+id);

		/* Clone repeatable Options*/
		var opt = $('#add-opt-new-data').clone().attr('id', 'add-opt-'+id);
			opt.each(function(index, item) {
		     	//set new option name (with id)
		     	// var orgName = $(item).attr( 'name' );
		     	$(item).find('.input-key').attr( 'name', arrayName+'[options][opt-key][]' );
		     	$(item).find('.input-val').attr( 'name', arrayName+'[options][opt-val][]' );
		     	// var x = $(item);
		     	// alert(x); tom_options[dsdsd][desc]

			});
		opt.prependTo('#opt-container-'+id);

		/* Clone default field */		
		var orgDef = $('#tom-default-new-data');
		var def = orgDef.clone();
		def.each(function(index, item) {
			$(item).attr( 'name', arrayName+'[default]' );
			$(item).attr( 'id', 'tom-default-'+id );
			$(item).val( orgDef.eq(index).val() );
		});
		$('#'+id+'-default').html(def);
	}

	/* Delete group */
	$(document).delegate( "#tom-delete-group", "click", function(event) {
		event.preventDefault();
		if (confirm("Are you sure to delete options group ?")) {
			var activeTab = $('.nav-tab-active');
			var activeDiv = activeTab.attr('href');
			var prev = activeTab.prev();
			// alert(activeDiv);
			activeTab.fadeOut().remove();
			$(activeDiv).fadeOut().remove();

			prev.addClass('nav-tab-active');
			tom_tabs();
		 }

	    return false;
	});

	$('.tom_media_upload').click(function(e) {
	    e.preventDefault();

	    var custom_uploader = wp.media({
	        title: 'Select Option Image',
	        button: {
	            text: 'Add To Option'
	        },
	        multiple: false  // Set this to true to allow multiple files to be selected
	    })
	    .on('select', function() {
	        var attachment = custom_uploader.state().get('selection').first().toJSON();
	        $('.tom_media_image').attr('src', attachment.url);
	        $('.tom_media_url').val(attachment.url);
	        $('.tom_media_id').val(attachment.id);
	    })
	    .open();
	});

});