jQuery(document).ready(function($) {

	/* Set Conntent width */
	sizeContent();
	$(window).resize(sizeContent);

	function sizeContent() {
		var windowSize = $('#wpbody-content').width();
		var main = windowSize - 380;
		// alert(windowSize);
		$('.metabox-main').width(main+'px');
	}

	/* Handle Tab Active */
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

		checkEmpty(active_tab);

		// Bind tabs clicks
		$navtabs.click(function(e) {

			e.preventDefault();

			// Remove active class from all tabs
			$navtabs.removeClass('nav-tab-active');

			$(this).addClass('nav-tab-active').blur();

			if (typeof(localStorage) != 'undefined' ) {
				localStorage.setItem('active_tab', $(this).attr('href') );
			}

			var selected = $(this).attr('href');

			$group.hide();
			$(selected).fadeIn();
			checkEmpty(selected);
		});
	}
	
	function checkEmpty(activeTab) {
		// alert(activeTab);
		var emptyOptions = '<div class="empty-options">';
			emptyOptions +=		'<h1>There is no option here..</h1>';
			if (tomMode == 'full') {
				emptyOptions +=		'<h4>please create the option <a href="'+tomCreatePage+'#tom-id-new-data">first</a></h4>';
			}
			emptyOptions +=	'</div>';

		if ($(activeTab+" .container-body").find('.tom-item').length == '') {
			
			$('.hide-if-empty').hide();
			$('#tom-delete-group').show();
			$(activeTab+" .container-body").html(emptyOptions);
		} else {
			if(activeTab == '#new-group') {
				$('#tom-delete-group').hide();
			} else {
				$('#tom-delete-group').show();
			}
			$('.hide-if-empty').show();
		}

		// $(activeTab+" .container-body").each(function() {
		//     if ($.trim($(this).text()) == '' ) {
		//         // alert('hajar');
		//         $(this).append(emptyOptions);
		// 		/* Hide submit button */
		// 		$('#tonjoo-tom-submit').hide();
		//     } else {
		//     	/* else show button */
		//     	$('#tonjoo-tom-submit').show();
		//     }
		// });
	}
	// var xxx = $('.nav-tab-active').attr('href');
	// alert(xxx);

	// $('.container-body').each(function() {
	// 	var emptyOptions = '<div class="empty-options">';
	// 		emptyOptions +=		'<h1>There is no option here..</h1>';
	// 		if (tomMode == 'full') {
	// 			emptyOptions +=		'<h4>please create the option <a href="'+tomCreatePage+'#tom-id-new-data">first</a></h4>';
	// 		}
	// 		emptyOptions +=	'</div>';
	// 		// alert(xxx);
	// 	if ($.trim ($(this).text()) == "") {
	// 		/* Append empty message */
	// 		$(this).append(emptyOptions);
	// 		/* Hide submit button */
	// 		// alert(activeDiv);
	// 		$('#tonjoo-tom-submit').hide();
	// 	}
	// });

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

	$(".dd").delegate( "a.save-nestable", "click", function(event) { // click event
	    event.preventDefault();
	    var id = $(this).closest( "li" ).attr("data-id");
	   	// alert(id);
	   	ajaxSubmit('f_create-options','tom_options',id);

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
		// var valDefault = $('#'+containerId+'-hidden-default').val();
		

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
		  		inputDefault = '<input name="'+arrayName+'[default]" id="tom-default-'+containerId+'" type="text" value="">';
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
		// template +='              	<input type="hidden" id="'+id+'-hidden-default" value="'+defaultValue+'">';
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
		$('.empty-options').remove();
		$('#tonjoo-tom-submit').show();
		ajaxSubmit('f_create-options','tom_options','new-data');
	});


	/* function to clone option type, repeatable options to nestable */
	function cloneNewData(id) {
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
		/* Clone attribute display to hide or show */
		var display = $('#new-data-options').css('display');
		$('#'+id+'-options').css('display', display);
		// alert(display);

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

	/* Media upload */
	$('.tom_media_upload').delegate( ".tom_button_upload", "click", function(event) {
	// $('.tom_media_upload').click(function(e) {
	    event.preventDefault();
	    var div = $(this).closest('.tom_media_upload');
	    // alert(div.attr('id'));

	    var custom_uploader = wp.media({
	        title: 'Select Option Image',
	        button: {
	            text: 'Add To Option'
	        },
	        multiple: false  // Set this to true to allow multiple files to be selected
	    })
	    .on('select', function() {
	        var attachment = custom_uploader.state().get('selection').first().toJSON();
	        $(div).find('.tom_media_image').attr('src', attachment.url);
	        $(div).find('.tom_media_image').show();
	        $(div).find('.tom_button_upload').html('Change');
	        $(div).find('.tom_media_url').val(attachment.url);
	        $(div).find('.tom_media_id').val(attachment.id);
        	$(div).find('.tom_remove_image').show();
	    })
	    .open();
	});

	$('.tom_media_upload').delegate( ".tom_remove_image", "click", function(event) {
		var div = $(this).closest('.tom_media_upload');

		$(div).find('.tom_media_image').attr('src', '');
        $(div).find('.tom_media_image').hide();
        $(div).find('.tom_button_upload').html('Choose');
        $(div).find('.tom_media_url').val('');
        $(div).find('.tom_media_id').val('');
        $(div).find('.tom_remove_image').hide();
	});

	/* Copy to clipboard */
	$("a.button-copy-shortcode").on('mouseover', function(event){
		event.preventDefault();

        //turn off this listening event for the element that triggered this
        // $(this).off('mouseover');

         //initialize clipboard
        $(this).clipboard({
            path: pluginDir+'/tonjoo-tom/assets/js/jquery.clipboard.swf',
            copy: function() {
	            var shortcode = $(this).find('.shortcodeValue').text();
	            $(this).find('.tooltip-body').html('Copied to clipboard');
	            // Hide "Copy" and show "Copied, copy again?" message in link
	            // this_sel.find('.code-copy-first').hide();
	            // this_sel.find('.code-copy-done').show();

	            // Return text in closest element (useful when you have multiple boxes that can be copied)
	            return shortcode;
	        }
         });
     });


	/* Tooltip */
	var showTooltip = function(event) {
		// alert('ok');
	  	$('div.tooltip').remove();
	  	var title 	= $(this).find('.shortcodeValue').attr('data-title');
	  	var shortcode 	= $(this).find('.shortcodeValue').text();
	  	var	elementDiv 	=  '<div class="tooltip">';
	  		elementDiv 	+= '	<div class="tooltip-head">'+title+'</div>';
	  		elementDiv 	+= '	<div class="tooltip-body">'+shortcode+'</div>';
	  		elementDiv 	+= '</div>';

	  	$(elementDiv).appendTo($(this));

	  	var position = $(this).position();
	  	$('div.tooltip').css({top: position.top - 10, left: position.left + 38});
	};
 
	var hideTooltip = function() {
	   $('div.tooltip').remove();
	};
 
	$("a.button-copy-shortcode").on({
	   mouseenter : showTooltip,
	   mouseleave: hideTooltip
	});



	/* Submit Form*/
	function ajaxSubmit(formId,optionId,buttonId){
		var formData = $('#'+formId).serialize();

		var data = {
			/* actions must be match with add_action name */
			'action': 'tom_options',
			'options': optionId,
			'form_data': formData
		};
		/* Post data*/
		$.post(ajaxurl, data, function(response) {
	       	$("#loading-"+buttonId).show();	       	
			setTimeout( function() {
				/* Remove notification if exist */
				$('#setting-error-save_options').fadeOut('slow').remove();
				if (response == 'success') {
					$("#loading-"+buttonId).hide();
					// alert('ok');
					$('#tom-notification').html('<div id="setting-error-save_options" class="updated fade settings-error below-h2"><p><strong>Options saved.</strong></p></div>').hide().fadeIn('slow');
				} else {
					$("#loading-"+buttonId).hide();
					// alert(response);
					$('#tom-notification').html('<div id="setting-error-save_options" class="error fade settings-error below-h2"><p><strong>Update failed.</strong></p></div>').hide().fadeIn('slow');
				}
				// console.log(response);
		    },1000);
		});

	return false;
	}
});