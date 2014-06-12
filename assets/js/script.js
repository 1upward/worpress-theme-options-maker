jQuery(document).ready(function($) {

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

	
	/* if select type change */
	$('.tom-type').change(function(){
		displayOptions();
	});

	function displayOptions() {
		/* loop for check all option type to show option div */
		$('.tom-type').each(function(){
			var container = $(this).attr('data-container');
			// alert(container);
			// return;
			var val = $(this).val();
			
		    // alert(val);
		    if (val == 'select') {
		    	$('#'+container).fadeIn(500);
		    } else {
				$('#'+container).fadeOut(500);
		    }
		});
		
	}

	/* Clone for repeatable options */
	$(".options-container").delegate( "a#new-repeatable", "click", function(event) { // click event
	    event.preventDefault();
	    /* get parent id to append */
		var idToAppend = $( this ).closest('.options-container').find('.input-options').first().attr('id');
	    /* get element to clone */
	    var elemToClone = $( '#'+idToAppend).find('.input-options-group');
		var oldOrder = parseInt(elemToClone.last().attr('data-order'));
		var newOrder = oldOrder+1;
		// alert(idToAppend);

	    var cloneInput = $( '#'+idToAppend).find('.input-options-group').first().clone();
	    // alert(cloneInput);
	    cloneInput.attr('data-order', newOrder);
	    cloneInput.find('.label-opt').html('Option '+newOrder+' : ');
	    cloneInput.find('input.input-opt').attr('name', newOrder).val('');
	    cloneInput.appendTo( '#'+idToAppend );

	    return false;
	});

	/* Delete repeatable options */
	$(".input-options").delegate( "a.btn-remove", "click", function(event) { // click event
	    event.preventDefault();
	 		$(this).closest( "div.input-options-group" ).fadeOut(500, function() { $(this).remove(); });
	    	
	    return false;
	});

	/* TO do */
	// function reorder(id) {
	// 	var index = $( "#"+id ).index( this );
 //  		$( "span" ).text( "That was div index #" + index );
	// }

	/* function to clone selected type to nestable */
	function kelon(id) {
		var arrayName = 'tom_options['+id+']';
		var $orginal = $('#tom-type');
		var $cloned = $orginal.clone();

		//get original selects into a jq object
		var $originalSelects = $orginal;
		$cloned.each(function(index, item) {
		     //set new select name and value 
		     $(item).attr( 'name', arrayName+'[type]' );
		     $(item).attr( 'data-container', 'container-opt-'+id );
		     $(item).val( $originalSelects.eq(index).val() );

		});
		$cloned.appendTo('#select_'+id);
		// alert($cloned);

		/* Clone repeatable Options*/
		var opt = $('#add-opt-new').clone();
			opt.find('.input-opt').each(function(index, item) {
		     	//set new option name (with id)
		     	var orgName = $(item).attr( 'name' );
		     	$(item).attr( 'name', arrayName+'[options]['+orgName+']' );
		     	// var x = $(item);
		     	// alert(x); tom_options[dsdsd][desc]

			});
		opt.appendTo('#opt-container-'+id);
	}

	$("#tom-add-options").on("click", function(event) {
        var id = $("#add-tom-options input[id=tom-id]").val();
        var id = id.replace(/\s+/g, '').toLowerCase();
        if (!id.length){
    		alert('kosong');
    		return;
    	}
        var name = $("#add-tom-options input[id=tom-name]").val();
        var desc = $("#add-tom-options textarea#tom-desc").val();
        var type = $("#add-tom-options select#tom-type").val();
        var defaultValue = $("#add-tom-options input[id=tom-default]").val();
		var arrayName = 'tom_options['+id+']';

		var activeDiv = $('.nav-tab-active').attr('href');
    	
    	// alert('ok');

	    // For testing
	    event.preventDefault();
	    // var template = $("#tom-type").clone();
	    // template.attr("id","newid");
	    // var template = '<li class="dd-item" data-id="'+id+'"><div class="dd-handle">'+name+'<span class="tom-action-buttons"><a class="blue edit-nestable" href="#"><i class="dashicons dashicons-edit"></i></a><a class="red delete-nestable" href="#"><i class="dashicons dashicons-trash"></i></a></span></div><div class="nestable-input" id="'+id+'" style="display:none;">';
	    // $.each(values, function() {
	        // output.children("[data-key='" + this.name + "']").text(this.value);
	    	// template += '<p><label class="tomLabel" for=""><span>'+this.name+'</span><br><input name="tom_options['+id+']['+this.name+']" type="text" class="" value="'+this.value+'">';
	    	// alert(this.value)
	    // });

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
		template +='              <span class="input-text-wrap">';
		template +='                <input type="text" name="'+arrayName+'[name]" value="'+name+'">';
		template +='              </span>';
		template +='            </label>';
		template +='            <label>';
		template +='              <span class="title">Description</span>';
		template +='              <span class="input-text-wrap">';
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
		template +='              <span id="select_'+id+'" class="input-text-wrap" name="xxx">';
		// template += select;
		// template +='                <select name="'+arrayName+'[type]">';
		// template +='                  <option value="0">Main Page (no parent)</option>';
		// template +='                  <option class="level-0" value="2">Sample Page</option>';
		// template +='                </select>';
		template +='              </span>';
		template +='            </label>';
		template +='			<label id="container-opt-'+id+'">';
		template +='			  <span class="title">Options</span>';
		template +='			  <span class="input-text-wrap">';
		template +='			  <div id="opt-container-'+id+'" class="options-container">';
		// template +='							<div id="add-opt-new" class="input-options">';
		// template +='						        <div data-order="1" class="input-options-group">';
		// template +='						        	<span class="label-opt">Option 1 : </span>';
		// template +='						        	<input class="input-opt" name="opt[1]" value="">';
		// template +='						        	<a class="btn-remove dashicons dashicons-dismiss"></a>';
		// template +='					        	</div>';
		// template +='					        	<div data-order="2" class="input-options-group">';
		// template +='						        	<span class="label-opt">Option 2 : </span>';
		// template +='						        	<input class="input-opt" name="opt[1]" value="">';
		// template +='						        	<a class="btn-remove dashicons dashicons-dismiss"></a>';
		// template +='					        	</div>';
		// template +='					        </div>';
		template +='			  </div>';
		template +='	          </span>';
		template +='			</label>';
		template +='            <label>';
		template +='              <span class="title">Default</span>';
		template +='              <span class="input-text-wrap">';
		template +='                <input type="text" name="'+arrayName+'[default]" value="'+defaultValue+'">';
		template +='              </span>';
		template +='            </label>';
		template +='          </div>';
		template +='        </fieldset>';
		template +='      </tbody>';
		template +='    </table>';
		template +='  </div>';
		template +='</li>';



	    // template += '</div></li>';

		// var id = $("#add-tom-options input[name=id]").val();
		// var id = "xxx";
		// var template = '<p><label class="tomLabel" for=""><span>Name</span><br><input name="tom_options['+id+'][name]" type="text" class="" value="Input Text"><input name="tom_options['+id+'][type]" type="text" class="" value="text"></label></p>';
		$(activeDiv).find('ol.dd-list').append(template);
		kelon(id);
		displayOptions();
		/* Clear input */
		$('#add-tom-options').find('option:first').attr('selected', 'selected'); 
		$('#add-tom-options').find('input, textarea').val(''); 
	});
	






	// var updateOutput = function(e)
 //    {
 //        var list   = e.length ? e : $(e.target),
 //            output = list.data('output');
 //        if (window.JSON) {
 //            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
 //        } else {
 //            output.val('JSON browser support required for this demo.');
 //        }
 //    };

    // activate Nestable for list 1
    // $('#nestable-1').nestable({'maxDepth':'1'});
    // $('#nestable-2').nestable({'maxDepth':'1'});
    // $('#nestable-3').nestable({'maxDepth':'1'});
    // $('#nestable-4').nestable({'maxDepth':'1'});
    

    // output initial serialised data
    // updateOutput($('#nestable-1').data('output', $('#nestable-1-output')));
    // updateOutput($('#nestable-2').data('output', $('#nestable-2-output')));
    // updateOutput($('#nestable-3').data('output', $('#nestable-3-output')));
    // updateOutput($('#nestable-4').data('output', $('#nestable-4-output')));


});