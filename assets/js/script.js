jQuery(document).ready(function($){	
	var oTable;
	var col_num = header.split(',').length;

	gen_header = generate_header();
	gen_data_table = generate_data(data_table);

	oTable = $('#datatables').dataTable( {		
		"aaData": gen_data_table,
		"aoColumns": gen_header,
		"bPaginate": false,
        "bLengthChange": false,
        "bFilter": false,
        "bSort": true,
        "bInfo": true
	}).rowReordering();

	print_last_number();

	
	/** 
	 * Sheep It Form
	 */
	var sheepItForm = jQuery('#sheepItForm').sheepIt({
        separator: '',
        allowRemoveLast: true,
        allowRemoveCurrent: false,
		allowRemoveAll: false,
        allowAdd: true,
        allowAddN: true,
        maxFormsCount: 25,
        minFormsCount: 2,
        iniFormsCount: 2
    });


	$('#insert_table').click(function(){		
		add_sheepit();

		if($(this).html() == "Add")
		{
			insert_table(0);
		}
		else
		{
			edit_table();
		}
	});

	$('#form-datatables').submit(function(){
		var old_data = $("#table_data").val();
		var sData = $('input', oTable.fnGetNodes()).serializeArray();
		var arr_data = serialize_to_array(sData,col_num);
		var stringify = window.JSON.stringify(arr_data);

		stringify = stringify.replace(/\'/g,"&#39;");

		if(old_data != stringify)
		{
			$("#table_data").val(stringify);
		}

		return true;
	});

	$("#datatables tbody tr").live("click",function( e ) {
        if ( $(this).hasClass('row_selected') ) 
        {
            $(this).removeClass('row_selected');
        }
        else 
        {
            oTable.$('tr.row_selected').removeClass('row_selected');
            $(this).addClass('row_selected');
        }

        get_selected();
    });
        
    $('#delete_row').click( function() {
        var anSelected = fnGetSelected( oTable );
        if ( anSelected.length !== 0 ) {
            oTable.fnDeleteRow( anSelected[0] );
        }

        get_selected();
    });

    window.onbeforeunload = function (e) {
    	var old_data = $("#table_data").val();
		var sData = $('input', oTable.fnGetNodes()).serializeArray();
		var arr_data = serialize_to_array(sData,col_num);
		var stringify = window.JSON.stringify(arr_data);

		stringify = stringify.replace(/\'/g,"&#39;");

		if(old_data != stringify)
		{
			e = e || window.event;

			// For IE and Firefox prior to version 4
			if (e) {
			    e.returnValue = 'Your data has not been saved and will be lost if you leave this page.';
			}

			// For Safari
			return 'Your data has not been saved and will be lost if you leave this page.';
		}
	};
    
    /* Functions */
	function generate_header()
	{
		var arr_data = [];

		data = header_title.split(',');

		for (var i = 0, l = col_num; i < l; i++)
		{
			if(i == 3)
			{
				/* kolom select_val (kolom 3) dipindah ke kolom 2, lalu kolom 3 di hide */
				arr_data[i] = {"sTitle": data[i], "bSearchable": false, "bVisible": false};
			}
			else
			{
				arr_data[i] = {"sTitle": data[i]};
			}			
		}

		arr_data.unshift({"sTitle": "Num"});

		return arr_data;
	}

	function generate_data(data)
	{
		if(data.length > 2)
		{
			var arr_data = window.JSON.parse(data);
			var return_arr_data = arr_data;

			for (var i = 0, l = arr_data.length; i < l; i++)
			{
				for (var j = 0, k = col_num; j < k; j++)
				{										
					if(j == 2)
					{
						var this_value = arr_data[i][j];

						return_arr_data[i][j] = "<input type='hidden' style='display:none;' name='col_" + i + "_" + j + "' value='" + arr_data[i][j] + "' />" + arr_data[i][j];					


						/* kolom select_val (kolom 3) dipindah ke kolom 2, lalu kolom 3 di hide */
						var n = j + 1;
						return_arr_data[i][j] = return_arr_data[i][j] + "<input type='hidden' style='display:none;' name='col_" + i + "_" + n + "' value='" + arr_data[i][n] + "' />";

						if(this_value == 'Select') 
						{
							return_arr_data[i][j] = return_arr_data[i][j] + "<br>" + arr_data[i][n];
						}
					}
					else
					{
						return_arr_data[i][j] = "<input type='hidden' style='display:none;' name='col_" + i + "_" + j + "' value='" + arr_data[i][j] + "' />" + arr_data[i][j];					
					}		

				}

				return_arr_data[i].unshift(i + 1);
			}
		}
		else
		{
			return_arr_data = "";
		}

		return return_arr_data;
	}

	function serialize_to_array(data,num_limit)
	{
		var arr_data = [];
		var arr_temp = [];
		var j = 0;
		var k = 0;
		
		for (var i = 0, l = data.length; i < l; i++)
		{
			arr_temp[j++] = data[i].value;

		    if(j >= num_limit)
		    {
		    	arr_data[k++] = arr_temp;

		    	var arr_temp = [];
		    	j = 0;
		    }
		}

		return arr_data;
	}

	function insert_table(selected_num)
	{
		var arr_data = [];

		data = header.split(',');

		for (var i = 0, l = data.length; i < l; i++)
		{
			var this_val = $('#' + data[i] + '_val').val();

			this_val = this_val.replace(/\'/g,"&#39;");

			if(i == 2)
			{
				arr_data[i] = "<input type='hidden' style='display:none;' name='" + data[i] + "' value='" + this_val + "' />" + this_val;

				/* kolom select_val (kolom 3) dipindah ke kolom 2, lalu kolom 3 di hide */
				var j = i + 1;
				var sheepit_val = $('#' + data[j] + '_val').val();
				sheepit_val = sheepit_val.replace(/\'/g,"&#39;");

				arr_data[i] = arr_data[i] + "<input type='hidden' style='display:none;' name='" + data[j] + "' value='" + sheepit_val + "' />";

				if(this_val == "Select")
				{
					arr_data[i] = arr_data[i] + "<br>" + sheepit_val;
				}
			}
			else
			{
				arr_data[i] = "<input type='hidden' style='display:none;' name='" + data[i] + "' value='" + this_val + "' />" + this_val;
			}			
		}

		/* order number */		
		if(selected_num > 0)
		{
			arr_data.unshift(selected_num);
		}
		else
		{
			arr_data.unshift(oTable.fnGetData().length + 1);
		}

		$('#datatables').dataTable().fnAddData(arr_data);

		$(".col_val").val("");

		print_last_number();
	}

	function edit_table()
	{
		var selected_num = get_selected_num();

		var anSelected = fnGetSelected( oTable );
        if ( anSelected.length !== 0 ) {
            oTable.fnDeleteRow( anSelected[0] );
        }

        insert_table(selected_num);
        get_selected();
	}

	function get_selected()
	{
		var anSelected = fnGetSelected( oTable );

        if ( anSelected.length !== 0 ) 
        {
        	var data = header.split(',');
        	var i = 0;

        	$(".row_selected td input").each(function(i){

        		$("input[id=" + data[i] + "_val]").val($(this).val());

        		/* for select */
        		$("select[id=" + data[i] + "_val]").val($(this).val());
        		
        		/* sheepit */
        		if(i == 3)
        		{            		
        			sheepit_load_data($(this).val());
        		}

        		i++;
        	});

        	$("#insert_table").html("Edit");
        	$("#delete_row").removeAttr("disabled");
        	$("#type_val").change();
        }
        else
        {
        	$(".col_val").val("");
        	$("#insert_table").html("Add");
        	$("#delete_row").attr("disabled","disabled");
        	
        	print_last_number();
        }
	}

	function print_last_number()
	{
		/* print last number */
		$('#sort-number_val').val(oTable.fnGetData().length + 1);
	}

	function get_selected_num()
	{
		var selected_num = 0;
	    var aTrs = oTable.fnGetNodes();
	     
	    for ( var i=0 ; i<aTrs.length ; i++ )
	    {
	        if ( $(aTrs[i]).hasClass('row_selected') )
	        {
	            selected_num = i + 1;
	        }
	    }
	    
	    return selected_num;
	}

	function fnGetSelected( oTableLocal )
	{
	    var aReturn = new Array();
	    var aTrs = oTableLocal.fnGetNodes();
	     
	    for ( var i=0 ; i<aTrs.length ; i++ )
	    {
	        if ( $(aTrs[i]).hasClass('row_selected') )
	        {
	            aReturn.push( aTrs[i] );
	        }
	    }

	    return aReturn;
	}


	/**
	 * SheepIt input
	 */

	function add_sheepit()
	{
		var old_data = $("#output_sheepit").val();
		var sData = $('.type_select').serializeArray();
		// var arr_data = serialize_to_array_sheepit(sData);
		var arr_data = serialize_to_array(sData,2);
		var stringify = window.JSON.stringify(arr_data);

		stringify = stringify.replace(/\'/g,"&#39;");

		if(old_data != stringify)
		{
			$("#select_type_val").val(stringify);
		}

		sheepit_reset_form(2);
	}

	function json_to_object(arr_data, optionname, optionval)
	{
		function escapeRegExp(str) {
			return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
		}

		// arr_data = arr_data.replace(new RegExp(escapeRegExp('["'), 'g'), "[{'" + variable + "': '");
		// arr_data = arr_data.replace(new RegExp(escapeRegExp('",'), 'g'), "'},");
		// arr_data = arr_data.replace(new RegExp(escapeRegExp(',"'), 'g'), ",{'" + variable + "': '");
		// arr_data = arr_data.replace(new RegExp(escapeRegExp('"]'), 'g'), "'}]");

		arr_data = arr_data.replace(new RegExp(escapeRegExp('[["'), 'g'), "[{'" + optionname + "': '");
		arr_data = arr_data.replace(new RegExp(escapeRegExp('"],'), 'g'), "'},");
		arr_data = arr_data.replace(new RegExp(escapeRegExp('","'), 'g'), "','" + optionval + "': '");
		arr_data = arr_data.replace(new RegExp(escapeRegExp(',["'), 'g'), ",{'" + optionname + "': '");
		arr_data = arr_data.replace(new RegExp(escapeRegExp('"]]'), 'g'), "'}]");

		return eval("("+arr_data+")");
	}

	function serialize_to_array_sheepit(data)
	{
		var arr_data = [];
		var j = 0;
		
		for (var i = 0, l = data.length; i < l; i++)
		{
			arr_data[j++] = data[i].value;
		}

		return arr_data;
	}

	function sheepit_load_data(inject_data)
	{
		//count displayed data
		var form_count = sheepItForm.getFormsCount();

    	var arr_data = json_to_object(inject_data,"#form#_#index#_optionname","#form#_#index#_optionval");

    	// count arr_data
    	var data_count = 0;
		for (var k in arr_data) {
		    if (arr_data.hasOwnProperty(k)) {
		       ++data_count;
		    }
		}

		//inject data
    	sheepItForm.inject(arr_data);

    	// remove unnecessary last form
    	if(form_count > data_count)
    	{
    		var count_min_form = form_count - data_count;

    		for (var i = 0; i < count_min_form; i++) 
    		{
    			sheepItForm.removeLastForm();
    		}
    	}
	}

	function sheepit_reset_form(first_form_count)
	{
		//count displayed data
		var form_count = sheepItForm.getFormsCount();

		// remove unnecessary last form
    	if(form_count > first_form_count)
    	{
    		var count_min_form = form_count - first_form_count;

    		for (var i = 0; i < count_min_form; i++) 
    		{
    			sheepItForm.removeLastForm();
    		}
    	}

    	$('input.type_select').val("");
    	$('input#sheepItForm_add_n_input').val("");
	}
});