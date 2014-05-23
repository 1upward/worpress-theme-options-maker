<div class="wrap">
<?php
	
	if ( file_exists( get_template_directory() . "/tonjoo_options.php" ) ) {
	    require_once( get_template_directory() . "/tonjoo_options.php" );
	} 

	if ( function_exists( 'tonjoo_tom_options' ) ) {
		$tonjoo_tom = tonjoo_tom_options();

	} else {

		$tonjoo_tom = get_option("tonjoo_tom");
		$tonjoo_tom = unserialize($tonjoo_tom);
	}

	$show_key = 0;

	if($tonjoo_tom && count($tonjoo_tom) > 0)
	{

		if(isset($_GET['data']))
		{
			$show_key = $_GET['data'];
		}
		else
		{
			reset($tonjoo_tom);
			$show_key = key($tonjoo_tom);
		}
		
		echo '<h2 class="nav-tab-wrapper">';

		foreach ($tonjoo_tom as $key => $value) 
		{
			if($key == $show_key) $class_active = "nav-tab-active";
			else $class_active = "";

			echo '<a class="nav-tab '.$class_active.'" href="'.admin_url("admin.php?page=tonjoo-tom/admin.php")."&data=$key".'">'.$tonjoo_tom[$key]["name"].'</a>';
		}

		echo '</h2>';
	}
	else
	{
		echo '<div class="updated" style="margin-top:10px;"><p><strong>No data found!</strong> Please create a new option group <a href="'.admin_url("admin.php?page=tonjoo-tom/settings.php")."&new_group=true".'">here</a></p></div>';
		exit();
	}

	/* Insert Data */
	if($_POST)
	{
		// print_r($_POST); exit();
		foreach ($_POST as $key => $value) {
			$value = stripslashes($value);

			$value = str_replace("'", "&#39;", $value);
			$value = str_replace('"', "&#34;", $value);

			$data_insert[$key] = $value;
		}
		
		update_option("tonjoo_tom_data_$show_key",serialize($data_insert));

		echo '<div class="updated"><p><strong>Updated!</strong> Your changes has been saved!</p></div>';
		// wp_redirect(admin_url("admin.php?page=tonjoo-tom/admin.php") . '&data=' . $show_key . '&updated=true');
		// die();
	}

	/* flash-message */
	if(isset($_GET['updated']) && $_GET['updated'] == 'true')
	{
		echo '<div class="updated"><p><strong>Updated!</strong> Your changes has been saved!</p></div>';
	}

	
	/* BEGIN */
	$config = array('header'=>'caption,option-name,type,description,sort-number',
					'header_title'=>'Caption,Option Name,Type,Description,Sort Number',
					'sort_col'=>'5',
					'sort_type'=>'asc');

	$option_header = $config['header'];
	$option_header_title = $config['header_title'];

	/* get key data */
	// echo get_template_directory() . "/tonjoo_options.php";
	// $optionsfile = get_template_directory() . "/tonjoo_options.php";

	
	$option_data = $tonjoo_tom[$show_key]['data'];

	/* get value data */
	$value = get_option("tonjoo_tom_data_$show_key");	
	$value = $value ? unserialize($value) : false;

	$arr_data = json_decode(stripslashes($option_data), true);

	// echo "<pre>";
	// print_r($arr_data); 
	// echo "</pre>";

	if(is_array($arr_data)):
	?>

	<style type="text/css">
		.mediaUploadImage {
			margin-top:10px;
			max-width:350px;
			max-height:350px;
		}

		th {
			width: 400px !important;
		}

		th p.label {
			font-weight: normal;
			margin-top: 5px;
		}

		th p.label input {
			background-color: #f1f1f1;
			border: 1px solid #f1f1f1;
			width: 400px;
			font-size: 13px;
			padding: 0px;
		}

		td {
			vertical-align: top !important;
		}
		
		.img-select {
			width: 100px;
			height: 70px;
		}
		.cc-selector input{
			display: none;
		    margin:0;padding:0;
		    -webkit-appearance:none;
		       -moz-appearance:none;
		            appearance:none;
		}
		/*.visa{background-image:url(http://i.imgur.com/lXzJ1eB.png);}
		.mastercard{background-image:url(http://i.imgur.com/SJbRQF7.png);}*/
		 
		.cc-selector input:active +.drinkcard-cc{opacity: .9;}
		.cc-selector input:checked +.drinkcard-cc{
		    -webkit-filter: none;
		       -moz-filter: none;
		            filter: none;
		}
		.drinkcard-cc{
			margin-right: 10px;
		    cursor:pointer;
		    background-size:contain;
		    background-repeat:no-repeat;
		    display:inline-block;
		    width:100px;height:70px;
		    -webkit-transition: all 100ms ease-in;
		       -moz-transition: all 100ms ease-in;
		            transition: all 100ms ease-in;
		    -webkit-filter: brightness(1.8) grayscale(1) opacity(.7);
		       -moz-filter: brightness(1.8) grayscale(1) opacity(.7);
		            filter: brightness(1.8) grayscale(1) opacity(.7);
		}
		.drinkcard-cc:hover{
		    -webkit-filter: brightness(1.2) grayscale(.5) opacity(.9);
		       -moz-filter: brightness(1.2) grayscale(.5) opacity(.9);
		            filter: brightness(1.2) grayscale(.5) opacity(.9);
		}
		 
		/* Extras */
		a:visited{color:#888}
		a{color:#444;text-decoration:none;}
		p{margin-bottom:.3em;}
	</style>
	
	<form method="post" id="post" action="<?php echo '?page=tonjoo-tom/admin.php&data='.$show_key.'' ?>">	
	<table class="form-table" style="margin-bottom:20px;">

<?php
	foreach ($arr_data as $n) 
	{
		/* label */
		$label = "";

		if($n['2'] != 'Header') $label.= '<p class="label"><input value=\'[tonjoo_tom group="'.$show_key.'" name="'.$n['1'].'"]\' /></p>';

		// if(! empty($n['3'])) $label.= "<p class='label'>{$n['3']}</p>";

		/* option value */
		$option_value = "";

		if($value && isset($value[$n['1']]))
		{
			$option_value = $value[$n['1']];
		}

		switch ($n['2']) 
		{
		    case 'Header':		        
		        echo "<tr><td colspan=3><h2 style='margin-left:-10px;'>{$n['0']} <p>{$n['4']}</p><hr></h2></td></tr>";
		        break;

		    case 'Input Text':
		        echo "<tr>
		        	  <th scope='row'>{$n['0']} $label</th>
		        	  <td>
		        	  	  <input type='text' name='{$n['1']}' class='col_val' style='width:400px;' value='".$option_value."'>
		        	  	  <br><label>{$n['4']}</label>
		        	  </td>		        	  
		        	  </tr>";
		        break;

		    case 'Text Area':
		        echo "<tr>
		        	  <th scope='row'>{$n['0']} $label</th>
		        	  <td>
		        	  	  <textarea name='{$n['1']}' style='width:400px;' rows='8'>".$option_value."</textarea>
		        	  	  <br><label>{$n['4']}</label>
		        	  </td>		        	  
		        	  </tr>";
		        break;

		    case 'Select':		    	
		    	$arr_select = json_decode($n['3'], true);
		    	
		    	if(is_array($arr_select))
		    	{
		    		echo "<tr>
		        	  	  <th scope='row'>{$n['0']} $label</th>
		        	  	  <td>
		    			  <select name='{$n['1']}' >";

		    		foreach ($arr_select as $key => $value_select)
					{
						if($option_value == $value_select[1])
						{
							$selected = "selected";
						}
						else
						{
							$selected = "";
						}

						echo "<option value='{$value_select[1]}' $selected >{$value_select[0]}</option>";
					}

		    		echo "</select>
		    			  <br><label>{$n['4']}</label>
		        	  	  </td>		        	  
		        	  	  </tr>";
		    	}
		        break;

		    case 'Image Select':		    	
		    	$arr_img_select = json_decode($n['3'], true);
		    	
		    	if(is_array($arr_img_select))
		    	{
		    		echo "<tr>
		        	  	  <th scope='row'>{$n['0']} $label</th>
		        	  	  <td>";

		        	echo "<div class='cc-selector'>";
		    		foreach ($arr_img_select as $key => $value_select)
					{
						if($option_value == $value_select[0])
						{
							$selected = "checked";
						}
						else
						{
							$selected = "";
						}

						echo "<input id='{$value_select[0]}' type='radio' name='{$n['1']}' value='{$value_select[0]}' $selected ><label class='drinkcard-cc' for='{$value_select[0]}'><img class='img-select' src='{$value_select[1]}'></label>";
						
					}

		    		echo "</div>
		    			  </td>		        	  
		        	  	  </tr>";
		    	}
		        break;

		    case 'Image':
		        echo "<tr>
		        	  <th scope='row'>{$n['0']} $label</th>
		        	  <td mediauploader >
					      <input mediaUploadText type='hidden' style='display:none;' name='{$n['1']}' value='".$option_value."'>
					      <input type='button' class='button' mediaUploadButton value='Set image'>";

				if($option_value != ""):
					echo "<div><img mediaUploadImage class='mediaUploadImage' src='".$option_value."'></img></div>
						  <label>{$n['4']}</label>
					      </td>
					      </tr>";
				else:
					echo "<div><img mediaUploadImage class='mediaUploadImage' src=''></img></div>
				 		  <label>{$n['4']}</label>
					      </td>
					      </tr>";
				endif;

		        break;
		}
	}

?>
	
	</table>
	
	<hr>

	<?php submit_button(); ?>	
	</form>

<?php
	else:
		echo '<div class="updated" style="margin-top:10px;"><p><strong>No data found!</strong> Please check you settings</p></div>';
	endif;
?>

</div> <!-- end wrap -->