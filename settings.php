<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<!-- IF add new group ($_GET['new_group'] == 'true')  -->
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

<?php
	if(isset($_GET['new_group']) && $_GET['new_group'] == 'true'):

	if($_POST)
	{
		if(isset($_POST['group_name']) && $_POST['group_name'] != "")
		{
			$tonjoo_tom = get_option('tonjoo_tom');
			
			if($tonjoo_tom && count(unserialize($tonjoo_tom)) > 0)
			{
				$tonjoo_tom = unserialize($tonjoo_tom);
			}
			else
			{
				$tonjoo_tom = array();
			}

			$arr = array('name' => $_POST['group_name'], 'data' => '');

			array_push($tonjoo_tom, $arr);

			update_option('tonjoo_tom', serialize($tonjoo_tom));
		}

		/* REDIRECT */
		$tonjoo_tom = unserialize(get_option('tonjoo_tom'));

		wp_redirect(admin_url("admin.php?page=tonjoo-tom/settings.php")."&data=" . (count($tonjoo_tom) - 1));
	}
?>

<div class="wrap">
<h2>Theme Options Maker (TOM) Settings</h2>
<p>Customize your theme options!, you can add, edit, or delete easily here. Don't forget to save your changes or you will lose it.</p>

<h2 class="nav-tab-wrapper">

	<?php
		$tonjoo_tom = get_option('tonjoo_tom');

		if($tonjoo_tom && count(unserialize($tonjoo_tom)) > 0)
		{
			$tonjoo_tom = unserialize($tonjoo_tom);

			foreach ($tonjoo_tom as $key => $value) 
			{
				echo '<a class="nav-tab" href="'.admin_url("admin.php?page=tonjoo-tom/settings.php")."&data=$key".'">'.$tonjoo_tom[$key]["name"].'</a>';
			}
		}
	?>

	<a class="nav-tab nav-tab-active" href="<?php echo admin_url("admin.php?page=tonjoo-tom/settings.php")."&new_group=true" ?>">+ New</a>
</h2>

<form method="post" action="" id="form-new-group" action="?page=tonjoo-tom/settings.php&noheader=true">
	<table class="form-table" style="margin-bottom:20px;">
	    <tr valign="top">
	    	<th scope="row">Group Name</th>
	    	<td>
	    		<input type="text" name="group_name" class="col_val" style="width:300px;">
	    		<br><br>Type your new theme option group name
	    	</td>
	    </tr>
	</table>

	<?php submit_button(); ?>
</form>





<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<!-- IF show group data ($_GET['data'])  -->
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

<?php
	elseif(isset($_GET['data']) && $_GET['data'] != ""):

	if($_POST)
	{
		if(isset($_POST['tonjoo_tom_data']) && $_POST["tonjoo_tom_data"] != "")
		{
			$tonjoo_tom = get_option('tonjoo_tom');
			
			if($tonjoo_tom && count(unserialize($tonjoo_tom)) > 0)
			{
				$tonjoo_tom = unserialize($tonjoo_tom);

				$tonjoo_tom[$_GET['data']]['data'] = $_POST["tonjoo_tom_data"];

				update_option('tonjoo_tom', serialize($tonjoo_tom));
			}
			else
			{
				wp_redirect(admin_url("admin.php?page=tonjoo-tom/settings.php")."&new_group=true");
			}
		}	

		wp_redirect(admin_url("admin.php?page=tonjoo-tom/settings.php") . '&data=' . $_GET['data'] . '&updated=true');
	}

	/*
	 * Delete option group
	 */

	if(isset($_GET['delete']) && $_GET['delete'] == 'true')
	{
		$tonjoo_tom = get_option('tonjoo_tom');

		if($tonjoo_tom && count(unserialize($tonjoo_tom)) > 0)
		{
			$tonjoo_tom = unserialize($tonjoo_tom);

			unset($tonjoo_tom[$_GET['data']]);

			update_option('tonjoo_tom', serialize($tonjoo_tom));

			/* redirect */
			$tonjoo_tom = unserialize(get_option('tonjoo_tom'));

			foreach ($tonjoo_tom as $key => $value) 
			{
				$first_ttom_group = $key;

				break;
			}

			wp_redirect(admin_url("admin.php?page=tonjoo-tom/settings.php")."&data=$first_ttom_group&updated=true");
		}
		else
		{
			wp_redirect(admin_url("admin.php?page=tonjoo-tom/settings.php")."&new_group=true");
		}
	}
?>

<style type="text/css">
	.close {
		float:right;
		margin-top: 12px;
	}

	.close a {
		font-size: 20px;
		text-decoration: none;
		color:#ccc;	
		font-weight: bold;	
		border: 1px solid #ccc;
		padding: 0px 8px 3px 8px;
		background-color: #fff;		
	}

	.close a:hover {
		color:#fff;
		/*border: 1px solid red;*/
		background-color: #ba3731;
		border: 1px solid #ba3731;		
	}

	a {
	    text-decoration:underline;
	    color:#00F;
	    cursor:pointer;
	}

	/*#sheepItForm {
		margin-top: 25px;
	}*/

	#sheepItForm_controls {
		margin-top: 10px;
	}

	#sheepItForm_controls div, #sheepItForm_controls div input {
	    float:left;    
	    margin-right: 10px;
	}

	#sheepItForm_template img.delete {
		margin-top: 10px;
	}

	p.submit {
		float: right;
	}

	.rm_option_group {
		color: #a00;
		text-decoration: none;
	}

	.rm_option_group:hover {
		color: red;
	}
</style>

<div class="wrap">
<h2>Theme Options Maker (TOM) Settings</h2>
<p>Customize your theme options!, you can add, edit, or delete easily here. Don't forget to save your changes or you will lose it.</p>
<!-- <div class="close"><a href="javascript:;" class="rm_option_group" >x</a></div> -->
<h2 class="nav-tab-wrapper">

	<?php
		$tonjoo_tom = get_option('tonjoo_tom');

		if($tonjoo_tom && count(unserialize($tonjoo_tom)) > 0)
		{
			$tonjoo_tom = unserialize($tonjoo_tom);

			foreach ($tonjoo_tom as $key => $value) 
			{
				if($key == $_GET['data']) $class_active = "nav-tab-active";
				else $class_active = "";

				echo '<a class="nav-tab '.$class_active.'" href="'.admin_url("admin.php?page=tonjoo-tom/settings.php")."&data=$key".'">'.$tonjoo_tom[$key]["name"].'</a>';
			}
		}
	?>

	<a class="nav-tab" href="<?php echo admin_url("admin.php?page=tonjoo-tom/settings.php")."&new_group=true" ?>" style="font-weight:bold;">+ New</a>
</h2>

<?php
	/* flash-message */
	if(isset($_GET['updated']) && $_GET['updated'] == 'true')
	{
		echo '<div class="updated"><p><strong>Updated!</strong> Your changes has been saved!</p></div>';
	}

	/* BEGIN */
	$config = array('header'=>'caption,option-name,type,select_type,description',
					'header_title'=>'Caption,Option Name,Type,Select Value,Description');

	$option_header = $config['header'];
	$option_header_title = $config['header_title'];

	$tonjoo_tom = unserialize(get_option('tonjoo_tom'));

	$option_data = $tonjoo_tom[$_GET['data']]['data'];

?>

<script type="text/javascript">
	var header = "<?php echo $option_header ?>";
	var header_title = "<?php echo $option_header_title ?>";
	var data_table = '<?php echo $option_data; ?>';

	jQuery('.rm_option_group').live('click',function(){
		var rm_link = '<?php echo admin_url("admin.php?page=tonjoo-tom/settings.php")."&data=".$_GET['data']."&delete=true" ?>';

		var prompt = confirm("Are you sure want to delete this option group?");
		if (prompt == true)
		{
			window.location.href = rm_link;
		}
	});

	jQuery("#type_val").live('change',function(){
		if(jQuery(this).val() == 'Select')
		{
			jQuery('#select_form').show('slow');
		}
		else
		{
			jQuery('#select_form').hide('slow');
		}
	});
</script>

<table class="form-table" style="margin-bottom:20px;margin-top: 10px !important;width:650px;float:left;">
	<?php
		$col_name = explode(',', $config['header']);
		$col_title = explode(',', $config['header_title']);

		for ($i=0; $i < count($col_name); $i++):			
	?>

    
    		<?php if($col_name[$i] == 'type'): ?>
    			<tr valign="top">
		    		<th scope="row"><?php echo $col_title[$i] ?></th>
			    	<td>
		    			<select id="<?php echo $col_name[$i] ?>_val" style="width:300px;">
		    				<option value="Header">Header</option>
		    				<option value="Input Text">Input Text</option>
		    				<option value="Select">Select</option>
		    				<option value="Text Area">Text Area</option>
		    				<option value="Image">Image</option>
		    			</select>
		    		</td>
    			</tr>

    		<?php elseif($col_name[$i] == 'select_type'): ?>
    			<tr valign="top" id="select_form" style="display:none;">
		    		<th scope="row"><?php echo $col_title[$i] ?></th>
			    	<td>
		    			<!-- sheepIt Form -->
						<div id="sheepItForm">				 
						<!-- Form template-->
						<div id="sheepItForm_template" style="margin-bottom:10px;">
							<label for="sheepItForm_#index#_option">Option <span id="sheepItForm_label"></span> :</label>
							<br>
							<input class="type_select" id="sheepItForm_#index#_optionname" name="optionname_#index#" type="text" size="20" maxlength="100" placeholder="Option Name" />
							<input class="type_select" id="sheepItForm_#index#_optionval" name="optionval_#index#" type="text" size="20" maxlength="100" placeholder="Option Value" />
							<a id="sheepItForm_remove_current">
							<img class="delete" src="<?php echo plugins_url() ?>/tonjoo-tom/assets/img/cross.png" width="16" height="20" border="0">
							</a>
						</div>
						<!-- /Form template-->

						<!-- No forms template -->
						<div id="sheepItForm_noforms_template">No options</div>
						<!-- /No forms template-->

						<!-- Controls -->
						<div id="sheepItForm_controls">
							<div id="sheepItForm_add"><a class="button"><span>Add option</span></a></div>
							<div id="sheepItForm_remove_last"><a class="button"><span>Remove</span></a></div>
							<div id="sheepItForm_remove_all"><a class="button"><span>Remove all</span></a></div>
							<div id="sheepItForm_add_n">
								<input id="sheepItForm_add_n_input" type="text" size="4" />
								<div id="sheepItForm_add_n_button"><a class="button"><span>Add</span></a></div>
							</div>
						</div>
						<!-- /Controls -->

						</div>
						<!-- /sheepIt Form -->

						<textarea style="display:none;" id="select_type_val"></textarea>
					</td>
    			</tr>
    		<?php else: ?>
    			<tr valign="top">
		    		<th scope="row"><?php echo $col_title[$i] ?></th>
			    	<td>
	    				<input type="text" id="<?php echo $col_name[$i] ?>_val" class="col_val" style="width:300px;">
	    			</td>
    			</tr>
	    	<?php endif ?>
    	

    <?php
    	endfor;
    ?>   

    <form method="post" action="" id="form-datatables" action="?page=tonjoo-tom/settings.php&noheader=true">
	    <textarea readonly style="display:none;" name="tonjoo_tom_data" id="table_data"><?php echo stripcslashes($option_data); ?></textarea>

        <tr valign="top">
        	<th scope="row">&nbsp;</th>
        	<td>
        		<style type="text/css">p.submit{padding:0px;margin-top:0px !important;}</style>
        		<a href="javascript:;" class="button action" style="float:left;margin-right:5px;" id="insert_table">Add</a>
        		<a href="javascript:;" class="button button-secondary" style="float:left;margin-right:5px;color:red;" id="delete_row" disabled="disabled">Delete</a>        		
        	</td>
        </tr>    
</table>

    <div class="postbox-container" style="float: right;margin-top: 20px; min-width: 280px;">
	<div class="metabox-holder" style="padding-top:0px;">	
	<div class="meta-box-sortables ui-sortable">
		<div id="email-signup" class="postbox" style="height:151px;">
			<h3 class="hndle"><span>Save Options</span></h3>
			<div class="inside" style="padding-top:10px;">
				Save your changes to apply the options
				<br>
				<br>

				<div style="padding: 10px;clear: both;border-top: 1px solid #ddd;background: #f5f5f5;margin: 7px -12px -12px -12px;height: 30px;">				
					<div style="float:left;padding-top: 5px;">
						<a href="javascript:;" class="rm_option_group">Remove Group</a>
					</div>

					<?php submit_button(); ?>
				</div>				
				</form>
			</div>
		</div>
	</div>
	</div>
	</div>
      

<div style="margin-right:2px;clear:both;">
	<p>			
		Select a row to <strong>"Edit"</strong> or <strong>"Delete"</strong> an item. <strong>Drag and drop</strong> a row to sort the data.<br>
		Don't forget to <strong>"Save Changes"</strong> after add, edit, delete or sort an item(s), or your changes will not saved.
	</p>
	<table class="wp-list-table widefat fixed" cellspacing="0" id="datatables"></table>
</div>
	    
</div> <!-- end wrap -->



<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<!-- ELSE not $_GET['new_group'] or not $_GET['data']  -->
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

<?php
	else:
		$tonjoo_tom = get_option('tonjoo_tom');

		if($tonjoo_tom && count(unserialize($tonjoo_tom)) > 0)
		{
			$tonjoo_tom = unserialize($tonjoo_tom);

			foreach ($tonjoo_tom as $key => $value) 
			{
				$first_ttom_group = $key;

				break;
			}

			// wp_redirect(admin_url("admin.php?page=tonjoo-tom/settings.php")."&data=$first_ttom_group");
			$location = admin_url("admin.php?page=tonjoo-tom/settings.php")."&data=$first_ttom_group";
			echo "<meta http-equiv='refresh' content='0;url=$location' />";
		}
		else
		{
			// wp_redirect(admin_url("admin.php?page=tonjoo-tom/settings.php")."&new_group=true");
			$location = admin_url("admin.php?page=tonjoo-tom/settings.php")."&new_group=true";
			echo "<meta http-equiv='refresh' content='0;url=$location' />";
		}

	/***** 
	 * END IF add new group or show option data 
	 */

	endif;
?>