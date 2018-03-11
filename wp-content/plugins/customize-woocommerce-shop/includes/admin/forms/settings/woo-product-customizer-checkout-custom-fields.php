<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

 	global $repupress_customize_woocommerce_product_model;
	
	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script( 'farbtastic' );
	wp_enqueue_script( 'woo-product-customizer-detail', REPUPRESS_CUSTOMIZE_WOOCOMMERCE .'/includes/js/woo-product-customizer-detail.js', array( 'farbtastic', 'jquery' ) );
	$model = $repupress_customize_woocommerce_product_model;
	
	$html = '';
	
	if(isset($_POST['repupress_customize_woocommerce_product_add_fields'])){
		$commerce_product_add_fields = filter_var($_POST['repupress_customize_woocommerce_product_add_fields'], FILTER_SANITIZE_STRING);
	}
	
	if(!empty($commerce_product_add_fields) && $commerce_product_add_fields == __( 'Add Field', 'repupresscustomwoocommerceproduct' )) { //check click of save button
		$error_msg = 0;
		
		if(isset($_POST['repupress_customize_woocommerce_product_checkout_field_label'])){
			$product_checkout_field_label = filter_var($_POST['repupress_customize_woocommerce_product_checkout_field_label'], FILTER_SANITIZE_STRING);
		}
		
		if (empty($product_checkout_field_label) ){
			$error_msg = 1;
			$err_msg = __("Enter Label for Field.",'repupresscustomwoocommerceproduct');
		}

		if (empty($product_checkout_field_label) ){
			$error_msg = 1;
			$err_msg .= '</br>'.__("Enter Field Name.",'repupresscustomwoocommerceproduct');
		}
		
		if ($error_msg == 0) {

			$section = filter_var($_POST['repupress_customize_woocommerce_product_checkout_field_section'], FILTER_SANITIZE_STRING);
			$label = filter_var($_POST['repupress_customize_woocommerce_product_checkout_field_label'], FILTER_SANITIZE_STRING);		
			$field_name = filter_var($_POST['repupress_customize_woocommerce_product_checkout_field_name'], FILTER_SANITIZE_STRING);
			$input_type = filter_var($_POST['repupress_customize_woocommerce_product_checkout_field_input_type'], FILTER_SANITIZE_STRING);
			$place_holder = (!empty($_POST['repupress_customize_woocommerce_product_checkout_field_place_holder'])) ? filter_var($_POST['repupress_customize_woocommerce_product_checkout_field_place_holder'], FILTER_SANITIZE_STRING) : '' ;
			$required = (!empty($_POST['repupress_customize_woocommerce_product_checkout_field_required'])) ? filter_var($_POST['repupress_customize_woocommerce_product_checkout_field_required'], FILTER_SANITIZE_STRING) : 0 ;

			

			$update = array(
					'section' => $section,
					'name' => $field_name,
					'label' => $label,
					'required' => $required,
					'placeholder' => $place_holder,
					'type' => $input_type,
						
				);
			
			
			
			$upd = get_option('repupress_customize_woocommerce_product_checkout_fields');
			if (!empty($upd)) {
				array_push($upd, $update);
			}else{
				$upd[] = $update;
			}
			update_option('repupress_customize_woocommerce_product_checkout_fields',$upd);
			

			$html = '</br><div class="updated" id="message">
					<p><strong>'.__("Custom Field Add Successfully.",'repupresscustomwoocommerceproduct').'</strong></p>
				</div>';
		}else{
			$html = '</br><div class="error" id="message">
					<p><strong>'.$err_msg.'</strong></p>
				</div>';
		}
		
	}
	
	if(isset($_POST['repupress_customize_woocommerce_product_add_fields']) && !empty($_POST['repupress_customize_woocommerce_product_add_fields']) && $_POST['repupress_customize_woocommerce_product_add_fields'] == __( 'Update Field', 'repupresscustomwoocommerceproduct' )) { //check click of save button
		$error_msg = 0;
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_field_label']) && empty($_POST['repupress_customize_woocommerce_product_checkout_field_label']) ){
			$error_msg = 1;
			$err_msg = __("Enter Label for Field.",'repupresscustomwoocommerceproduct');
		}

		if (isset($_POST['repupress_customize_woocommerce_product_checkout_field_label']) && empty($_POST['repupress_customize_woocommerce_product_checkout_field_label']) ){
			$error_msg = 1;
			$err_msg .= '</br>'.__("Enter Field Name.",'repupresscustomwoocommerceproduct');
		}
		
		if ($error_msg == 0) {
			$edit_key = ($_GET['edit_key'] == 'first') ? 0 : filter_var($_GET['edit_key'], FILTER_SANITIZE_STRING);
			$section = filter_var($_POST['repupress_customize_woocommerce_product_checkout_field_section'], FILTER_SANITIZE_STRING);
			$label = filter_var($_POST['repupress_customize_woocommerce_product_checkout_field_label'], FILTER_SANITIZE_STRING);		
			$field_name = filter_var($_POST['repupress_customize_woocommerce_product_checkout_field_name'], FILTER_SANITIZE_STRING);
			$input_type = filter_var($_POST['repupress_customize_woocommerce_product_checkout_field_input_type'], FILTER_SANITIZE_STRING);
			$place_holder = (!empty($_POST['repupress_customize_woocommerce_product_checkout_field_place_holder'])) ? filter_var($_POST['repupress_customize_woocommerce_product_checkout_field_place_holder'], FILTER_SANITIZE_STRING) : '' ;
			$required = (!empty($_POST['repupress_customize_woocommerce_product_checkout_field_required'])) ? filter_var($_POST['repupress_customize_woocommerce_product_checkout_field_required'], FILTER_SANITIZE_STRING_INT) : 0 ;

			$upd = get_option('repupress_customize_woocommerce_product_checkout_fields');
			$upd[$edit_key] = array(
					'section' => $section,
					'name' => $field_name,
					'label' => $label,
					'required' => $required,
					'placeholder' => $place_holder,
					'type' => $input_type,
						
				);
			update_option('repupress_customize_woocommerce_product_checkout_fields',$upd);

			$url = admin_url( 'admin.php?page=woo-product-customizerort&tab=checkout_page&report=custom_checkout_fields&update=1');
			echo "<script>window.location='".$url."'</script>";
			
		}else{
			$html = '</br><div class="error" id="message">
					<p><strong>'.$err_msg.'</strong></p>
				</div>';
		}
		
	}
	
	if (isset($_GET['field_key']) && !empty($_GET['field_key'])) {
		$upd = get_option('repupress_customize_woocommerce_product_checkout_fields');
		$field_key = ($_GET['field_key'] == 'first') ? 0 : $_GET['edit_key'];

		unset($upd[$field_key]);
		update_option('repupress_customize_woocommerce_product_checkout_fields',$upd);
		$html = '</br><div class="updated" id="message">
					<p><strong>'.__("Custom Field Remove Successfully.",'repupresscustomwoocommerceproduct').'</strong></p>
				</div>';
	}

	if (isset($_GET['edit_key']) && !empty($_GET['edit_key']) ){
		$button_text = __("Update Field", 'repupresscustomwoocommerceproduct');
		$header_text =__("Edit Custom Field on Checkout Page", 'repupresscustomwoocommerceproduct');
	}else{
		$button_text = __("Add Field", 'repupresscustomwoocommerceproduct');
		$header_text =__("Add Custom Fields on Checkout Page ", 'repupresscustomwoocommerceproduct');
	}		


	if(isset($_GET['update']) && !empty($_GET['update']) &&  $_GET['update'] == 1){
		$html = '</br><div class="updated" id="message">
					<p><strong>'.__("Custom Field Updated Successfully.",'repupresscustomwoocommerceproduct').'</strong></p>
				</div>';
	}
	$html .= '<div class="wrap">'.screen_icon('options-general');
	
	$html .= '<h2>'.__($header_text, 'repupresscustomwoocommerceproduct').'</h2>';
	// beginning of the plugin options form
	$html .= '<form  method="post" action="" enctype="multipart/form-data">';
	echo $html;
	$html = '<!-- beginning of the settings meta box -->
				<div id="wpd-ws-settings" class="post-box-container">
				
					<div class="metabox-holder">	
				
						<div class="meta-box-sortables ui-sortable">
				
							<div id="settings" class="postbox">	
				
									<!-- settings box title -->
				
									<h3 class="hndle">
				
										<span style="vertical-align: top;">'. __( 'Custom Fields', 'repupresscustomwoocommerceproduct' ).'</span>
				
									</h3>
				
									<div class="inside">';
	
							$html .= '	<table class="form-table wpd-ws-settings-box"> 
											<tbody>';							
										
							if (isset($_GET['edit_key']) && !empty($_GET['edit_key']) ){

								$data =  get_option('repupress_customize_woocommerce_product_checkout_fields');
								$data = (gettype($data) == "array" && count($data) >= 1) ? $data:array();
								$edit_key = ($_GET['edit_key'] == 'first') ? 0 : $_GET['edit_key'];

								$html .='<tr>													
											<td>
												<label><strong>'.__( 'Select Field Section', 'repupresscustomwoocommerceproduct' ).'</strong></label>
												</br>
												<select name="repupress_customize_woocommerce_product_checkout_field_section">
													<option value="billing" '.(($data[$edit_key]['section'] == 'billing') ? 'selected="selected"' : "").'>Billing Section</option>
													<option value="shipping" '.(($data[$edit_key]['section'] == 'shipping') ? 'selected="selected"' : "").'>Shipping Section</option>
													<option value="account" '.(($data[$edit_key]['section'] == 'account') ? 'selected="selected"' : "").'>Account Section</option>
													<option value="order" '.(($data[$edit_key]['section'] == 'order') ? 'selected="selected"' : "").'>Additional Section</option>
												</select>
												</br><span class="description">'.__( 'Select Section for add custom fileds.', 'repupresscustomwoocommerceproduct' ).'</span>
											</td>							
											
											<td>
												<label><strong>'.__( 'Label', 'repupresscustomwoocommerceproduct' ).'</strong></label></br>
												<input type="text" name="repupress_customize_woocommerce_product_checkout_field_label" value="'.$data[$edit_key]['label'].'" >
												</br><span class="description">'.__( 'Enter Label to display on Checkout page for Input type.', 'repupresscustomwoocommerceproduct' ).'</span>
											</td>
											<td>
												<label><strong>'.__( 'Field Name', 'repupresscustomwoocommerceproduct' ).'</strong></label></br>
												<input type="text" name="repupress_customize_woocommerce_product_checkout_field_name" value="'.$data[$edit_key]['name'].'" >
												</br><span class="description">'.__( 'Enter field name without any spaces.', 'repupresscustomwoocommerceproduct' ).'</span>
											</td>';													
								$html .='</tr>';

								$html .='<tr>
											<td>
												<label><strong>'.__( 'Input Type', 'repupresscustomwoocommerceproduct' ).'</strong></label>
												</br>
												<select name="repupress_customize_woocommerce_product_checkout_field_input_type">
													<option value="text" '.(($data[$edit_key]['type'] == 'text') ? 'selected="selected"' : "").'>Text Box</option>
													<option value="textarea" '.(($data[$edit_key]['type'] == 'textarea') ? 'selected="selected"' : "").'>Text Area</option>															
												</select>
												</br><span class="description">'.__( 'Select Input type which you want to Add.', 'repupresscustomwoocommerceproduct' ).'</span>
											</td>														
											<td>
												<label><strong>'.__( 'Place Holder', 'repupresscustomwoocommerceproduct' ).'</strong></label></br>
												<input type="text" name="repupress_customize_woocommerce_product_checkout_field_place_holder"  value="'.$data[$edit_key]['placeholder'].'">
												</br><span class="description">'.__( 'This is for text and text area.', 'repupresscustomwoocommerceproduct' ).'</span>
											</td>
											<td>
												<label><strong>'.__( 'Required', 'repupresscustomwoocommerceproduct' ).'</strong></label></br>
												<input type="checkbox" name="repupress_customize_woocommerce_product_checkout_field_required" value="1" '.(($data[$edit_key]['required'] == 1) ? 'checked="checked"' : "").' >
												<span class="description">'.__( 'Select if you want to add with Required fields.', 'repupresscustomwoocommerceproduct' ).'</span>
											</td>';													
								$html .='</tr>';

							}else{
							$html .='<tr>													
											<td>
												<label><strong>'.__( 'Select Field Section', 'repupresscustomwoocommerceproduct' ).'</strong></label>
												</br>
												<select name="repupress_customize_woocommerce_product_checkout_field_section">
													<option value="billing">Billing Section</option>
													<option value="shipping">Shipping Section</option>
													<option value="account">Account Section</option>
													<option value="order">Additional Section</option>
												</select>
												</br><span class="description">'.__( 'Select Section for add custom fileds.', 'repupresscustomwoocommerceproduct' ).'</span>
											</td>							
											
											<td>
												<label><strong>'.__( 'Label', 'repupresscustomwoocommerceproduct' ).'</strong></label></br>
												<input type="text" name="repupress_customize_woocommerce_product_checkout_field_label" >
												</br><span class="description">'.__( 'Enter Label to display on Checkout page for Input type.', 'repupresscustomwoocommerceproduct' ).'</span>
											</td>
											<td>
												<label><strong>'.__( 'Field Name', 'repupresscustomwoocommerceproduct' ).'</strong></label></br>
												<input type="text" name="repupress_customize_woocommerce_product_checkout_field_name" >
												</br><span class="description">'.__( 'Enter field name without any spaces.', 'repupresscustomwoocommerceproduct' ).'</span>
											</td>';													
								$html .='</tr>';

								$html .='<tr>
											<td>
												<label><strong>'.__( 'Input Type', 'repupresscustomwoocommerceproduct' ).'</strong></label>
												</br>
												<select name="repupress_customize_woocommerce_product_checkout_field_input_type">
													<option value="text">Text Box</option>
													<option value="textarea">Text Area</option>															
												</select>
												</br><span class="description">'.__( 'Select Input type which you want to Add.', 'repupresscustomwoocommerceproduct' ).'</span>
											</td>														
											<td>
												<label><strong>'.__( 'Place Holder', 'repupresscustomwoocommerceproduct' ).'</strong></label></br>
												<input type="text" name="repupress_customize_woocommerce_product_checkout_field_place_holder" >
												</br><span class="description">'.__( 'This is for text and text area.', 'repupresscustomwoocommerceproduct' ).'</span>
											</td>
											<td>
												<label><strong>'.__( 'Required', 'repupresscustomwoocommerceproduct' ).'</strong></label></br>
												<input type="checkbox" name="repupress_customize_woocommerce_product_checkout_field_required" value="1" >
												<span class="description">'.__( 'Select if you want to add with Required fields.', 'repupresscustomwoocommerceproduct' ).'</span>
											</td>';													
								$html .='</tr>';			

							}			

							
							$html .= '<tr>
										<td colspan="2">
											<input type="submit" class="button-primary wpd-ws-settings-save" name="repupress_customize_woocommerce_product_add_fields" class="" value="'.$button_text.'" />
										</td>
									</tr>';
													
										
							$html .= '		</tbody>
										</table>';	
							
	$html .= '					</div><!-- .inside -->
					
							</div><!-- #settings -->
				
						</div><!-- .meta-box-sortables ui-sortable -->
				
					</div><!-- .metabox-holder -->
				
				</div><!-- #wps-settings-general -->
				
				<!-- end of the settings meta box -->';
	
	$html .= '</form>';
	
	$html .= '</div><!-- .wrap -->';
	
	echo $html;	
?>
<?php
	$data = get_option('repupress_customize_woocommerce_product_checkout_fields');
    $i = 1;
    if (isset($data) && count($data) > 0) {
?>

<div class="report-list">
	<table class="woo-product-cust-table" >
	    <thead>
	        <tr>
	            <th>#</th>        
	            <th><?php echo __('Section', 'repupresscustomwoocommerceproduct'); ?></th>
	            <th><?php echo __('Field Name', 'repupresscustomwoocommerceproduct'); ?></th>
	            <th><?php echo __('Label', 'repupresscustomwoocommerceproduct'); ?></th>
	            <th><?php echo __('Input Type', 'repupresscustomwoocommerceproduct'); ?></th>
	            <th><?php echo __('Place Holder', 'repupresscustomwoocommerceproduct'); ?></th>
	            <th><?php echo __('Required', 'repupresscustomwoocommerceproduct'); ?></th>
	            <th><?php echo __('Option', 'repupresscustomwoocommerceproduct'); ?></th>
	            
	        </tr>
	    </thead>

	    <tbody>
	        <?php           	
	                $total =0;
	                foreach ($data as $key => $value) {
	                	if($value['section'] == 'billing') $section = __('Billing Section', 'repupresscustomwoocommerceproduct');
	                	else if($value['section'] == 'shipping') $section = __('Shipping Section', 'repupresscustomwoocommerceproduct');
	                	else if($value['section'] == 'account') $section = __('Account Section', 'repupresscustomwoocommerceproduct');
	                	else if($value['section'] == 'order') $section = __('Additional Section', 'repupresscustomwoocommerceproduct');

	                	if($value['type'] == 'text') $type = __('Text Box', 'repupresscustomwoocommerceproduct');
	                	else if($value['type'] == 'textarea') $type = __('Text Area', 'repupresscustomwoocommerceproduct');

	                	if($value['required'] == 1) $required = __('Compulsory', 'repupresscustomwoocommerceproduct');
	                	else if($value['required'] == 0) $required = __('Optional', 'repupresscustomwoocommerceproduct');

	                	$key = ($key == 0) ? 'first' : $key;
	        ?>
	                <tr>
	                    <td><?php echo $i; ?></td>
	                    <td><?php echo $section; ?></td>
	                    <td><?php echo $value['name']; ?></td>
	                    <td><?php echo $value['label']; ?></td>
	                    <td><?php echo $type; ?></td>
	                    <td><?php echo $value['placeholder']; ?></td>
	                    <td><?php echo $required; ?></td>  
	                    <td>
	                    	<a href="<?php echo admin_url( 'admin.php?page=woo-product-customizerort&tab=checkout_page&report=custom_checkout_fields&field_key=').$key; ?>" title="Delete Custom Field"><img src="<?php echo REPUPRESS_CUSTOMIZE_WOOCOMMERCE .'/includes/images/delete.png'; ?>" width="15"></a>
	                    	<a href="<?php echo admin_url( 'admin.php?page=woo-product-customizerort&tab=checkout_page&report=custom_checkout_fields&edit_key=').$key; ?>" title="Edit Custom Field"><img src="<?php echo REPUPRESS_CUSTOMIZE_WOOCOMMERCE .'/includes/images/edit.png'; ?>" width="15"></a>
	                   	</td>   
	                </tr>               

	        <?php   $i++; } ?>               
	          
	    </tbody>
	</table>

	</div>
<?php } ?>