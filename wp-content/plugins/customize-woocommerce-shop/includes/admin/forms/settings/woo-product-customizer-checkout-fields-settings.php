<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 	global $repupress_customize_woocommerce_product_model;
	
	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script( 'farbtastic' );
	wp_enqueue_script( 'woo-product-customizer-detail', REPUPRESS_CUSTOMIZE_WOOCOMMERCE .'/includes/js/woo-product-customizer-detail.js', array( 'farbtastic', 'jquery' ) );
	$model = $repupress_customize_woocommerce_product_model;
	
	$html = '';
	if(isset($_POST['repupress_customize_woocommerce_product_save_settings']) && !empty($_POST['repupress_customize_woocommerce_product_save_settings']) && $_POST['repupress_customize_woocommerce_product_save_settings'] == __( 'Save Settings', 'repupresscustomwoocommerceproduct' )) { //check click of save button
				
		if (isset($_POST['repupress_customize_woocommerce_product_fields_show_order_details'])) update_option('repupress_customize_woocommerce_product_fields_show_order_details', filter_var($_POST['repupress_customize_woocommerce_product_fields_show_order_details'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_fields_show_order_details',0);
			
		if (isset($_POST['repupress_customize_woocommerce_product_fields_show_order_email'])) update_option('repupress_customize_woocommerce_product_fields_show_order_email',filter_var($_POST['repupress_customize_woocommerce_product_fields_show_order_email'], FILTER_FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_fields_show_order_email',0);
		
		if (isset($_POST['repupress_customize_woocommerce_product_fields_show_order_edit'])) update_option('repupress_customize_woocommerce_product_fields_show_order_edit',filter_var($_POST['repupress_customize_woocommerce_product_fields_show_order_edit'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_fields_show_order_edit',0);
		

		$html = '</br><div class="updated" id="message">
					<p><strong>'.__("Changes Saved Successfully.",'repupresscustomwoocommerceproduct').'</strong></p>
				</div>';
	
	}
		
	
	$html .= '<div class="wrap">'.screen_icon('options-general');
	
	$html .= '<h2>'.__("Custom Fields Settings", 'repupresscustomwoocommerceproduct').'</h2>';
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
				
										<span style="vertical-align: top;">'. __( 'Custom Fields Display Settings', 'repupresscustomwoocommerceproduct' ).'</span>
				
									</h3>
				
									<div class="inside">';
	
							$html .= '	<table class="form-table wpd-ws-settings-box"> 
											<tbody>';							
										
										
										
										$order_check = (get_option('repupress_customize_woocommerce_product_fields_show_order_details') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Order Details Page ', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$order_check.' name="repupress_customize_woocommerce_product_fields_show_order_details" >
														<span class="description">'.__( 'If you select than Custom Fields display on Order Details and Thank You Page(After placed Order).', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';

										$email_check = (get_option('repupress_customize_woocommerce_product_fields_show_order_email') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Order E-Mail', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$email_check.' name="repupress_customize_woocommerce_product_fields_show_order_email" >
														<span class="description">'.__( 'If you select than Custom Fields display on Order E-Mail.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';

										$order_edit_check = (get_option('repupress_customize_woocommerce_product_fields_show_order_edit') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Order Edit Page(Admin Dashboard) ', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$order_edit_check.' name="repupress_customize_woocommerce_product_fields_show_order_edit" >
														<span class="description">'.__( 'If you select than Custom Fields display on Order Edit page on Admin Dashboard(WooCommerce => Orders => View).', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';		

										$html .= '<tr>
													<td colspan="2">
														<input type="submit" class="button-primary wpd-ws-settings-save" name="repupress_customize_woocommerce_product_save_settings" class="" value="'.__( 'Save Settings', 'repupresscustomwoocommerceproduct' ).'" />
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