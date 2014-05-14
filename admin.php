<div class="wrap">

<?php
	$tonjoo_tom = get_option('tonjoo_tom');
	$show_key = 0;

	if($tonjoo_tom && count(unserialize($tonjoo_tom)) > 0)
	{
		$tonjoo_tom = unserialize($tonjoo_tom);

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
		foreach ($_POST as $key => $value) {
			$value = stripslashes($value);

			$value = str_replace("'", "&#39;", $value);
			$value = str_replace('"', "&#34;", $value);

			$data_insert[$key] = $value;
		}
		
		update_option("tonjoo_tom_data_$show_key",serialize($data_insert));

		wp_redirect(admin_url("admin.php?page=tonjoo-tom/admin.php") . '&data=' . $show_key . '&updated=true');
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
	$tonjoo_tom = get_option("tonjoo_tom");
	$tonjoo_tom = unserialize($tonjoo_tom);
	$option_data = $tonjoo_tom[$show_key]['data'];

	/* get value data */
	$value = get_option("tonjoo_tom_data_$show_key");	
	$value = $value ? unserialize($value) : false;

	$arr_data = json_decode(stripslashes($option_data), true);

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
	</style>
	
	<form method="post" id="post" action="<?php echo '?page=tonjoo-tom/admin.php&data='.$show_key.'&noheader=true' ?>">	
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