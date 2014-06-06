/**
 * Custom scripts needed for the colorpicker, image button selectors,
 * and navigation tabs.
 */

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

			if (typeof(localStorage) != 'undefined' ) {
				localStorage.setItem('active_tab', $(this).attr('href') );
			}

			var selected = $(this).attr('href');

			$group.hide();
			$(selected).fadeIn();

		});
	}


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
    $('#nestable-1').nestable({'maxDepth':'1'});
    $('#nestable-2').nestable({'maxDepth':'1'});
    $('#nestable-3').nestable({'maxDepth':'1'});
    $('#nestable-4').nestable({'maxDepth':'1'});
    

    // output initial serialised data
    // updateOutput($('#nestable-1').data('output', $('#nestable-1-output')));
    // updateOutput($('#nestable-2').data('output', $('#nestable-2-output')));
    // updateOutput($('#nestable-3').data('output', $('#nestable-3-output')));
    // updateOutput($('#nestable-4').data('output', $('#nestable-4-output')));


});