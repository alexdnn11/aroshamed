<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

 	global $repupress_customize_woocommerce_product_model;
	
	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script( 'farbtastic' );
	wp_enqueue_script( 'woo-product-customizer-detail', REPUPRESS_CUSTOMIZE_WOOCOMMERCE .'/includes/js/woo-product-customizer-detail.js', array( 'farbtastic', 'jquery' ) );
	$model = $repupress_customize_woocommerce_product_model;
	
	$html = '';
	if(isset($_POST['repupress_customize_woocommerce_product_settings_save']) && !empty($_POST['repupress_customize_woocommerce_product_settings_save']) && $_POST['repupress_customize_woocommerce_product_settings_save'] == __( 'Save All Settings', 'repupresscustomwoocommerceproduct' )) { //check click of save button
	
		//disable
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_billing_country'])) update_option('repupress_customize_woocommerce_product_checkout_billing_country', filter_var($_POST['repupress_customize_woocommerce_product_checkout_billing_country'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_billing_country',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_billing_first_name'])) update_option('repupress_customize_woocommerce_product_checkout_billing_first_name',filter_var($_POST['repupress_customize_woocommerce_product_checkout_billing_first_name'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_billing_first_name',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_billing_last_name'])) update_option('repupress_customize_woocommerce_product_checkout_billing_last_name', filter_var($_POST['repupress_customize_woocommerce_product_checkout_billing_last_name'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_billing_last_name',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_billing_company'])) update_option('repupress_customize_woocommerce_product_checkout_billing_company', filter_var($_POST['repupress_customize_woocommerce_product_checkout_billing_company'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_billing_company',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_billing_address_1'])) update_option('repupress_customize_woocommerce_product_checkout_billing_address_1', filter_var($_POST['repupress_customize_woocommerce_product_checkout_billing_address_1'],FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_billing_address_1',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_billing_address_2'])) update_option('repupress_customize_woocommerce_product_checkout_billing_address_2', filter_var($_POST['repupress_customize_woocommerce_product_checkout_billing_address_2'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_billing_address_2',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_billing_city'])) update_option('repupress_customize_woocommerce_product_checkout_billing_city', filter_var($_POST['repupress_customize_woocommerce_product_checkout_billing_city'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_billing_city',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_billing_state'])) update_option('repupress_customize_woocommerce_product_checkout_billing_state', filter_var($_POST['repupress_customize_woocommerce_product_checkout_billing_state'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_billing_state',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_billing_postcode'])) update_option('repupress_customize_woocommerce_product_checkout_billing_postcode', filter_var($_POST['repupress_customize_woocommerce_product_checkout_billing_postcode'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_billing_postcode',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_billing_email'])) update_option('repupress_customize_woocommerce_product_checkout_billing_email',filter_var($_POST['repupress_customize_woocommerce_product_checkout_billing_email'], FILTER_VALIDATE_EMAIL)); else update_option('repupress_customize_woocommerce_product_checkout_billing_email',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_billing_phone'])) update_option('repupress_customize_woocommerce_product_checkout_billing_phone', filter_var($_POST['repupress_customize_woocommerce_product_checkout_billing_phone'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_billing_phone',0);			
		
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_shipping_country'])) update_option('repupress_customize_woocommerce_product_checkout_shipping_country', filter_var($_POST['repupress_customize_woocommerce_product_checkout_shipping_country'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_shipping_country',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_shipping_first_name'])) update_option('repupress_customize_woocommerce_product_checkout_shipping_first_name',filter_var($_POST['repupress_customize_woocommerce_product_checkout_shipping_first_name'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_shipping_first_name',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_shipping_last_name'])) update_option('repupress_customize_woocommerce_product_checkout_shipping_last_name', filter_var($_POST['repupress_customize_woocommerce_product_checkout_shipping_last_name'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_shipping_last_name',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_shipping_company'])) update_option('repupress_customize_woocommerce_product_checkout_shipping_company', filter_var($_POST['repupress_customize_woocommerce_product_checkout_shipping_company'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_shipping_company',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_shipping_address_1'])) update_option('repupress_customize_woocommerce_product_checkout_shipping_address_1', filter_var($_POST['repupress_customize_woocommerce_product_checkout_shipping_address_1'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_shipping_address_1',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_shipping_address_2'])) update_option('repupress_customize_woocommerce_product_checkout_shipping_address_2', filter_var($_POST['repupress_customize_woocommerce_product_checkout_shipping_address_2'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_shipping_address_2',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_shipping_city'])) update_option('repupress_customize_woocommerce_product_checkout_shipping_city', filter_var($_POST['repupress_customize_woocommerce_product_checkout_shipping_city'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_shipping_city',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_shipping_state'])) update_option('repupress_customize_woocommerce_product_checkout_shipping_state', filter_var($_POST['repupress_customize_woocommerce_product_checkout_shipping_state'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_shipping_state',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_shipping_postcode'])) update_option('repupress_customize_woocommerce_product_checkout_shipping_postcode', filter_var($_POST['repupress_customize_woocommerce_product_checkout_shipping_postcode'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_shipping_postcode',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_account_password'])) update_option('repupress_customize_woocommerce_product_checkout_account_password', filter_var($_POST['repupress_customize_woocommerce_product_checkout_account_password'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_account_password',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_order_comments'])) update_option('repupress_customize_woocommerce_product_checkout_order_comments', filter_var($_POST['repupress_customize_woocommerce_product_checkout_order_comments'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_order_comments',0);			
		

		//Optional
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_country'])) update_option('repupress_customize_woocommerce_product_checkout_opt_billing_country', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_country'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_billing_country',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_first_name'])) update_option('repupress_customize_woocommerce_product_checkout_opt_billing_first_name', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_first_name'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_billing_first_name',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_last_name'])) update_option('repupress_customize_woocommerce_product_checkout_opt_billing_last_name', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_last_name'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_billing_last_name',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_company'])) update_option('repupress_customize_woocommerce_product_checkout_opt_billing_company', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_company'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_billing_company',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_address_1'])) update_option('repupress_customize_woocommerce_product_checkout_opt_billing_address_1', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_address_1'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_billing_address_1',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_address_2'])) update_option('repupress_customize_woocommerce_product_checkout_opt_billing_address_2', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_address_2'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_billing_address_2',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_city'])) update_option('repupress_customize_woocommerce_product_checkout_opt_billing_city', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_city'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_billing_city',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_state'])) update_option('repupress_customize_woocommerce_product_checkout_opt_billing_state', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_state'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_billing_state',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_postcode'])) update_option('repupress_customize_woocommerce_product_checkout_opt_billing_postcode', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_postcode'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_billing_postcode',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_email'])) update_option('repupress_customize_woocommerce_product_checkout_opt_billing_email', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_email'], FILTER_VALIDATE_EMAIL)); else update_option('repupress_customize_woocommerce_product_checkout_opt_billing_email',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_phone'])) update_option('repupress_customize_woocommerce_product_checkout_opt_billing_phone', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_billing_phone'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_billing_phone',0);			
		
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_country'])) update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_country', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_country'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_country',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_first_name'])) update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_first_name', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_first_name'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_first_name',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_last_name'])) update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_last_name', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_last_name'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_last_name',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_company'])) update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_company', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_company'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_company',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_address_1'])) update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_address_1', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_address_1'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_address_1',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_address_2'])) update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_address_2', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_address_2'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_address_2',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_city'])) update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_city', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_city'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_city',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_state'])) update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_state', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_state'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_state',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_postcode'])) update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_postcode', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_shipping_postcode'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_shipping_postcode',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_account_password'])) update_option('repupress_customize_woocommerce_product_checkout_opt_account_password', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_account_password'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_account_password',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_opt_order_comments'])) update_option('repupress_customize_woocommerce_product_checkout_opt_order_comments', filter_var($_POST['repupress_customize_woocommerce_product_checkout_opt_order_comments'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_opt_order_comments',0);			
		

		//Appearance
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_title_font_color'])) update_option('repupress_customize_woocommerce_product_checkout_title_font_color', filter_var($_POST['repupress_customize_woocommerce_product_checkout_title_font_color'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_title_font_color',"#000000");
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_title_font_size'])) update_option('repupress_customize_woocommerce_product_checkout_title_font_size', filter_var($_POST['repupress_customize_woocommerce_product_checkout_title_font_size'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_title_font_size',"");
		if (isset($_POST['repupress_customize_woocommerce_product_checkout_title_font_weight'])) update_option('repupress_customize_woocommerce_product_checkout_title_font_weight',filter_var($_POST['repupress_customize_woocommerce_product_checkout_title_font_weight'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_checkout_title_font_weight',"");
		


		$html = '</br><div class="updated" id="message">
					<p><strong>'.__("Changes Saved Successfully.",'repupresscustomwoocommerceproduct').'</strong></p>
				</div>';
		}
	
	$html .= '<div class="wrap">'.screen_icon('options-general');
	
	$html .= '<h2>'.__("Checkout Page Settings", 'repupresscustomwoocommerceproduct').'</h2>';
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
				
										<span style="vertical-align: top;">'. __( 'Checkout Appearance Settings', 'repupresscustomwoocommerceproduct' ).'</span>
				
									</h3>
				
									<div class="inside">';
	
							$html .= '	<table class="form-table wpd-ws-settings-box"> 
											<tbody>';							
										
										$html .='<tr><td colspan="3"><h2>Billing Settings</h2></td></tr>';
										$bc_check = (get_option('repupress_customize_woocommerce_product_checkout_billing_country') == 1 ) ? 'checked="checked"' : '' ;
										$bco_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_billing_country') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Billing Country :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$bc_check.' name="repupress_customize_woocommerce_product_checkout_billing_country" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$bco_check.' name="repupress_customize_woocommerce_product_checkout_opt_billing_country" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';

										$bfn_check = (get_option('repupress_customize_woocommerce_product_checkout_billing_first_name') == 1 ) ? 'checked="checked"' : '' ;
										$bfno_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_billing_first_name') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Billing First Name :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$bfn_check.' name="repupress_customize_woocommerce_product_checkout_billing_first_name" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$bfno_check.' name="repupress_customize_woocommerce_product_checkout_opt_billing_first_name" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';
												 
										$bln_check = (get_option('repupress_customize_woocommerce_product_checkout_billing_last_name') == 1 ) ? 'checked="checked"' : '' ;
										$blno_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_billing_last_name') == 1 ) ? 'checked="checked"' : '' ;

										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Billing Last Name :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$bln_check.' name="repupress_customize_woocommerce_product_checkout_billing_last_name" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$blno_check.' name="repupress_customize_woocommerce_product_checkout_opt_billing_last_name" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';


										$bcmp_check = (get_option('repupress_customize_woocommerce_product_checkout_billing_company') == 1 ) ? 'checked="checked"' : '' ;
										$bcmpo_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_billing_company') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Billing Company :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$bcmp_check.' name="repupress_customize_woocommerce_product_checkout_billing_company" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$bcmpo_check.' name="repupress_customize_woocommerce_product_checkout_opt_billing_company" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';


										$badd1_check = (get_option('repupress_customize_woocommerce_product_checkout_billing_address_1') == 1 ) ? 'checked="checked"' : '' ;
										$badd1o_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_billing_address_1') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Billing Address1 :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$badd1_check.' name="repupress_customize_woocommerce_product_checkout_billing_address_1" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$badd1o_check.' name="repupress_customize_woocommerce_product_checkout_opt_billing_address_1" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';

										$badd2_check = (get_option('repupress_customize_woocommerce_product_checkout_billing_address_2') == 1 ) ? 'checked="checked"' : '' ;
										$badd2o_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_billing_address_2') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Billing Address2 :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$badd1_check.' name="repupress_customize_woocommerce_product_checkout_billing_address_2" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$badd2o_check.' name="repupress_customize_woocommerce_product_checkout_opt_billing_address_2" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';


										$bc_check = (get_option('repupress_customize_woocommerce_product_checkout_billing_city') == 1 ) ? 'checked="checked"' : '' ;
										$bco_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_billing_city') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Billing City :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$bc_check.' name="repupress_customize_woocommerce_product_checkout_billing_city" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$bco_check.' name="repupress_customize_woocommerce_product_checkout_opt_billing_city" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';


										$bs_check = (get_option('repupress_customize_woocommerce_product_checkout_billing_state') == 1 ) ? 'checked="checked"' : '' ;
										$bso_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_billing_state') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Billing State :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$bs_check.' name="repupress_customize_woocommerce_product_checkout_billing_state" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$bso_check.' name="repupress_customize_woocommerce_product_checkout_opt_billing_state" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';

										$bpost_check = (get_option('repupress_customize_woocommerce_product_checkout_billing_postcode') == 1 ) ? 'checked="checked"' : '' ;
										$bposto_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_billing_postcode') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Billing Post Code :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$bpost_check.' name="repupress_customize_woocommerce_product_checkout_billing_postcode" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$bposto_check.' name="repupress_customize_woocommerce_product_checkout_opt_billing_postcode" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';

										$be_check = (get_option('repupress_customize_woocommerce_product_checkout_billing_email') == 1 ) ? 'checked="checked"' : '' ;
										$beo_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_billing_email') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Billing Email :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$be_check.' name="repupress_customize_woocommerce_product_checkout_billing_email" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$beo_check.' name="repupress_customize_woocommerce_product_checkout_opt_billing_email" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';

										$bphon_check = (get_option('repupress_customize_woocommerce_product_checkout_billing_phone') == 1 ) ? 'checked="checked"' : '' ;
										$bphono_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_billing_phone') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Billing Phone :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$bphon_check.' name="repupress_customize_woocommerce_product_checkout_billing_phone" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$bphono_check.' name="repupress_customize_woocommerce_product_checkout_opt_billing_phone" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';

										
										

										$html .='<tr><td colspan="3"><h2>Shipping Settings</h2></td></tr>';
										$sc_check = (get_option('repupress_customize_woocommerce_product_checkout_shipping_country') == 1 ) ? 'checked="checked"' : '' ;
										$sco_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_shipping_country') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Shipping Country :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$sc_check.' name="repupress_customize_woocommerce_product_checkout_shipping_country" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$sco_check.' name="repupress_customize_woocommerce_product_checkout_opt_shipping_country" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';

										$sfn_check = (get_option('repupress_customize_woocommerce_product_checkout_shipping_first_name') == 1 ) ? 'checked="checked"' : '' ;
										$sfno_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_shipping_first_name') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Shipping First Name :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$sfn_check.' name="repupress_customize_woocommerce_product_checkout_shipping_first_name" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$sfno_check.' name="repupress_customize_woocommerce_product_checkout_opt_shipping_first_name" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';
												 
										$sln_check = (get_option('repupress_customize_woocommerce_product_checkout_shipping_last_name') == 1 ) ? 'checked="checked"' : '' ;
										$slno_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_shipping_last_name') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Shipping Last Name :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$sln_check.' name="repupress_customize_woocommerce_product_checkout_shipping_last_name" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$slno_check.' name="repupress_customize_woocommerce_product_checkout_opt_shipping_last_name" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';


										$scmp_check = (get_option('repupress_customize_woocommerce_product_checkout_shipping_company') == 1 ) ? 'checked="checked"' : '' ;
										$scmpo_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_shipping_company') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Shipping Company :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$scmp_check.' name="repupress_customize_woocommerce_product_checkout_shipping_company" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$scmpo_check.' name="repupress_customize_woocommerce_product_checkout_opt_shipping_company" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';


										$sadd1_check = (get_option('repupress_customize_woocommerce_product_checkout_shipping_address_1') == 1 ) ? 'checked="checked"' : '' ;
										$sadd1o_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_shipping_address_1') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Shipping Address1 :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$sadd1_check.' name="repupress_customize_woocommerce_product_checkout_shipping_address_1" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$sadd1o_check.' name="repupress_customize_woocommerce_product_checkout_opt_shipping_address_1" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';

										$sadd2_check = (get_option('repupress_customize_woocommerce_product_checkout_shipping_address_2') == 1 ) ? 'checked="checked"' : '' ;
										$sadd2o_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_shipping_address_2') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Shipping Address2 :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$sadd1_check.' name="repupress_customize_woocommerce_product_checkout_shipping_address_2" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$sadd2o_check.' name="repupress_customize_woocommerce_product_checkout_opt_shipping_address_2" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';


										$sc_check = (get_option('repupress_customize_woocommerce_product_checkout_shipping_city') == 1 ) ? 'checked="checked"' : '' ;
										$sco_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_shipping_city') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Shipping City :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$sc_check.' name="repupress_customize_woocommerce_product_checkout_shipping_city" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$sco_check.' name="repupress_customize_woocommerce_product_checkout_opt_shipping_city" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';


										$ss_check = (get_option('repupress_customize_woocommerce_product_checkout_shipping_state') == 1 ) ? 'checked="checked"' : '' ;
										$sso_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_shipping_state') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Shipping State :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$ss_check.' name="repupress_customize_woocommerce_product_checkout_shipping_state" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$sso_check.' name="repupress_customize_woocommerce_product_checkout_opt_shipping_state" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';

										$spost_check = (get_option('repupress_customize_woocommerce_product_checkout_shipping_postcode') == 1 ) ? 'checked="checked"' : '' ;
										$sposto_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_shipping_postcode') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Shipping Post Code :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$spost_check.' name="repupress_customize_woocommerce_product_checkout_shipping_postcode" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$sposto_check.' name="repupress_customize_woocommerce_product_checkout_opt_shipping_postcode" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';

										$sap_check = (get_option('repupress_customize_woocommerce_product_checkout_account_password') == 1 ) ? 'checked="checked"' : '' ;
										$sapo_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_account_password') == 1 ) ? 'checked="checked"' : '' ;										
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Account Password :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$sap_check.' name="repupress_customize_woocommerce_product_checkout_account_password" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$sapo_check.' name="repupress_customize_woocommerce_product_checkout_opt_account_password" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';

										$soc_check = (get_option('repupress_customize_woocommerce_product_checkout_order_comments') == 1 ) ? 'checked="checked"' : '' ;
										$soco_check = (get_option('repupress_customize_woocommerce_product_checkout_opt_order_comments') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Order Comment :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$soc_check.' name="repupress_customize_woocommerce_product_checkout_order_comments" >
														<span class="description">'.__( 'Disable.', 'repupresscustomwoocommerceproduct' ).'</span>
														</br><input type="checkbox" value="1" '.$soco_check.' name="repupress_customize_woocommerce_product_checkout_opt_order_comments" >
														<span class="description">'.__( 'Optional.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';

										$html .='<tr><td colspan="3"><h2>Appearance Settings</h2></td></tr>';
										
										$cft_fc = get_option('repupress_customize_woocommerce_product_checkout_title_font_color');
										$cft_fc = !empty($cft_fc) ? $cft_fc : "#000000";
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Field Title Font Color :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td>
														<div class="color-picker" style="position: relative;">
															<input type="text" name="repupress_customize_woocommerce_product_checkout_title_font_color" id="checkouttitlecolor"  value="'.$cft_fc.'" />
															<div id="checkouttitlecolorpicker"></div>
														</div>
														
														<span class="description">'.__( 'Select font color for Empty Cart button.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';
												 
						
										$cft_fs = get_option('repupress_customize_woocommerce_product_checkout_title_font_size');
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Field Title Font Size :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><select name="repupress_customize_woocommerce_product_checkout_title_font_size">
															<option value="">Select Font Size</option>';
															$fontsize = 8;
															for($i=$fontsize;$i<100;$i++){
																if($cft_fs == $i)
																	$selected_font = "selected='selected'";
																else
																	$selected_font = ""; 
																$html .= '<option value="'.$i.'" '.$selected_font.'>'.$i.' px</option>';
															}
													
													
													$html .='</select><br><span class="description">'.__( 'Select font size for Empty Cart button.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';									
										
										$cft_fw = get_option('repupress_customize_woocommerce_product_checkout_title_font_weight');
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Field Title Font Weight :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><select name="repupress_customize_woocommerce_product_checkout_title_font_weight">
															<option value="">Select Font Weight</option>';
															$options = array(
																				"normal" => "Normal",
																				"bold" => "Bold",
																				"bolder" => "Bolder",
																				"lighter" => "Lighter",
																				"100" => "100",
																				"200" => "200",
																				"300" => "300",
																				"400" => "400",
																				"500" => "500",
																				"600" => "600",
																				"700" => "700",
																				"800" => "800",
																				"900" => "900",
																				"initial" => "Initial",
																				"inherit" => "Inherit"
																			);
															foreach($options as $key_opt => $key_val){
																if($key_opt == $cft_fw)
																	$selected_font = "selected='selected'";
																else
																	$selected_font = ""; 
																$html .= '<option value="'.$key_opt.'" '.$selected_font.'>'.$key_val.'</option>';
															}
													
													
													$html .='</select><br><span class="description">'.__( 'Select font weight for Empty Cart button.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';									
										

										$html .= '<tr>
													<td colspan="2">
														<input type="submit" class="button-primary wpd-ws-settings-save" name="repupress_customize_woocommerce_product_settings_save" class="" value="'.__( 'Save All Settings', 'repupresscustomwoocommerceproduct' ).'" />
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