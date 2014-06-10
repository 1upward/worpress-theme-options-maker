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
	/* Change to .delegate @http://stackoverflow.com/questions/4442694/jquery-click-on-appended-elements */
	$(".dd").delegate("a", "mousedown", function(event) { // mousedown prevent nestable click
	    event.preventDefault();
	    return false;
	});

	$(".dd").delegate( "a.delete-nestable", "click", function(event) { // click event
	    event.preventDefault();
	    if (confirm("Are you sure to delete option?")) {
		  alert("sure banget ya..");
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

	$("#tom-add-options").on("click", function(event) {
		var id = "xxx";
		var template = '<li class="dd-item" data-id="'+id+'"><div class="dd-handle">Input Text<span class="tom-action-buttons"><a class="blue edit-nestable" href="#"><i class="dashicons dashicons-edit"></i></a><a class="red delete-nestable" href="#"><i class="dashicons dashicons-trash"></i></a></span></div><div class="nestable-input" id="'+id+'" style="display:none;"><p><label class="tomLabel" for=""><span>Name</span><br><input name="tom_options['+id+'][name]" type="text" class="" value="Input Text"><input name="tom_options['+id+'][type]" type="text" class="" value="text"></label></p></div></li>';
		var activeDiv = $('.nav-tab-active').attr('href');
		$(activeDiv).find('ol.dd-list').append(template);
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