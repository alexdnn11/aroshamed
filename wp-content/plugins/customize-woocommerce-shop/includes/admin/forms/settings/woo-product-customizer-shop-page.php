<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

 	global $repupress_customize_woocommerce_product_model;
	
	$model = $repupress_customize_woocommerce_product_model;
	
	$html = '';
	
	if(isset($_POST['repupress_customize_woocommerce_product_settings_save']) && !empty($_POST['repupress_customize_woocommerce_product_settings_save']) && $_POST['repupress_customize_woocommerce_product_settings_save'] == __( 'Save All Settings', 'repupresscustomwoocommerceproduct' )) { //check click of save button
	
		if (isset($_POST['repupress_customize_woocommerce_product_shop_product_list']))
		{	
			if (!empty($_POST['repupress_customize_woocommerce_product_shop_product_list']) && count($_POST['repupress_customize_woocommerce_product_shop_product_list']) >= 1 ) {
				
				$product_list = implode( ',', $_POST['repupress_customize_woocommerce_product_shop_product_list'] );
				$product_list = filter_var($product_list, FILTER_SANITIZE_STRING);
				$product_list = str_replace("all,","", $product_list);
			}else{
				$product_list = $_POST['repupress_customize_woocommerce_product_shop_product_list'];
			}
			
			update_option('repupress_customize_woocommerce_product_shop_product_list',$product_list);
		
		}else update_option('repupress_customize_woocommerce_product_shop_product_list',"all");
		
		
		if (isset($_POST['repupress_customize_woocommerce_product_shop_product_image'])) update_option('repupress_customize_woocommerce_product_shop_product_image', filter_var($_POST['repupress_customize_woocommerce_product_shop_product_image'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_shop_product_image',0);			
		
		if (isset($_POST['repupress_customize_woocommerce_product_shop_product_title'])) update_option('repupress_customize_woocommerce_product_shop_product_title', filter_var($_POST['repupress_customize_woocommerce_product_shop_product_title'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_shop_product_title',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_shop_product_prices'])) update_option('repupress_customize_woocommerce_product_shop_product_prices', filter_var($_POST['repupress_customize_woocommerce_product_shop_product_prices'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_shop_product_prices',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_shop_product_add_to_cart'])) update_option('repupress_customize_woocommerce_product_shop_product_add_to_cart', filter_var($_POST['repupress_customize_woocommerce_product_shop_product_add_to_cart'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_shop_product_add_to_cart',0);			
		if (isset($_POST['repupress_customize_woocommerce_product_shop_product_logged_in'])) update_option('repupress_customize_woocommerce_product_shop_product_logged_in', filter_var($_POST['repupress_customize_woocommerce_product_shop_product_logged_in'], FILTER_SANITIZE_STRING)); else update_option('repupress_customize_woocommerce_product_shop_product_logged_in',0);			
		
		$html = '</br><div class="updated" id="message">
					<p><strong>'.__("Changes Saved Successfully.",'repupresscustomwoocommerceproduct').'</strong></p>
				</div>';
	}
	
	$html .= '<div class="wrap">'.screen_icon('options-general');
	
	$html .= '<h2>'.__("Product Shop Page Customizer", 'repupresscustomwoocommerceproduct').'</h2>';
	
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
				
										<span style="vertical-align: top;">'. __( 'Shop Page', 'repupresscustomwoocommerceproduct' ).'</span>
				
									</h3>
				
									<div class="inside">';
	
							$html .= '	<table class="form-table wpd-ws-settings-box"> 
											<tbody>';
									
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Selected Products :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><p>You can choose to customize particular products or All products.</p><select  class="chosen-select" id="woo-order-products" name="repupress_customize_woocommerce_product_shop_product_list[]" multiple data-placeholder="Choose a Product...">';
													
												$product_list_check =explode(',', get_option('repupress_customize_woocommerce_product_shop_product_list') );
												
												$all_check = "";
												if (count($product_list_check) == 1 &&  in_array("all", $product_list_check)) {													
													$all_check = 'selected="selected"';
												}
												$html .='<option value="all" '.$all_check.' >All</option>';
													$args = array(															        														        
												        'post_type'       => 'product',
												        'post_status'     => 'publish',															       
												        'suppress_filters' => false,  
												        'posts_per_page' => -1    
													);
													$posts=get_posts($args);													
													foreach ($posts as $key => $value) {
														if (in_array($value->ID, $product_list_check)) {
															$list_check = 'selected="selected"';
														}else{
															$list_check ='';
														}
														$html .="<option value='".$value->ID."' ".$list_check.">".$value->post_title."</option>";
													}
										$html .='</select></td>
													<td>';
									
										$pi_check = (get_option('repupress_customize_woocommerce_product_shop_product_image') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Product Images :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$pi_check.' name="repupress_customize_woocommerce_product_shop_product_image" >
														<span class="description">'.__( 'Check this option to Hide Product Image.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';
										$ps_title = (get_option('repupress_customize_woocommerce_product_shop_product_title') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Product Title :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$ps_title.' name="repupress_customize_woocommerce_product_shop_product_title" >
														<span class="description">'.__( 'Check this option to Hide Product Titles.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';
																			
										$ps_luo = (get_option('repupress_customize_woocommerce_product_shop_product_logged_in') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Logged In User Only :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$ps_luo.' name="repupress_customize_woocommerce_product_shop_product_logged_in" >
														<span class="description">'.__( 'Check this option to Only Allow Logged In users to see the Add To Cart Button.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';
										$ps_pri = (get_option('repupress_customize_woocommerce_product_shop_product_prices') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Products Prices :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$ps_pri.' name="repupress_customize_woocommerce_product_shop_product_prices" >
														<span class="description">'.__( 'Check this option to Hide Products Prices.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';
										$ps_atc = (get_option('repupress_customize_woocommerce_product_shop_product_add_to_cart') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Add To Cart Button :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$ps_atc.' name="repupress_customize_woocommerce_product_shop_product_add_to_cart" >
														<span class="description">'.__( 'Check this option to Hide the Add To Cart Button.', 'repupresscustomwoocommerceproduct' ).'</span>
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