<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

 	global $repupress_customize_woocommerce_product_model;
	
	$model = $repupress_customize_woocommerce_product_model;
	
	$html = '';
	$order_date_start = $order_date_end  = "";
	
	if(isset($_POST['repupress_customize_woocommerce_product_settings_save']) && !empty($_POST['repupress_customize_woocommerce_product_settings_save'])){
		$post_reupress = filter_var($_POST['repupress_customize_woocommerce_product_settings_save'], FILTER_SANITIZE_STRING);
	}
	
	if(isset($post_reupress) && $_POST['repupress_customize_woocommerce_product_settings_save'] == __( 'Save All Settings', 'repupresscustomwoocommerceproduct' )) { //check click of save button
	
		$cart_empty_button = isset($_POST['repupress_customize_woocommerce_product_cart_empty_button']) ? filter_var($_POST['repupress_customize_woocommerce_product_cart_empty_button'], FILTER_SANITIZE_STRING):"";
		if (!empty($cart_empty_button)) update_option('repupress_customize_woocommerce_product_cart_empty_button',$_POST['repupress_customize_woocommerce_product_cart_empty_button']); else update_option('repupress_customize_woocommerce_product_cart_empty_button',0);

		$cart_button_font_color = isset($_POST['repupress_customize_woocommerce_product_cart_button_font_color']) ? filter_var($_POST['repupress_customize_woocommerce_product_cart_button_font_color'], FILTER_SANITIZE_STRING):"";
		if (!empty($cart_button_font_color)) update_option('repupress_customize_woocommerce_product_cart_button_font_color', $cart_button_font_color); else update_option('repupress_customize_woocommerce_product_cart_button_font_color',"#000000");
		
		$cart_button_font_size = isset($_POST['repupress_customize_woocommerce_product_cart_button_font_size']) ? filter_var($_POST['repupress_customize_woocommerce_product_cart_button_font_size'], FILTER_SANITIZE_STRING):"";
		if (!empty($cart_button_font_size)) update_option('repupress_customize_woocommerce_product_cart_button_font_size',$cart_button_font_size); else update_option('repupress_customize_woocommerce_product_cart_button_font_size',"");
		
		$cart_button_font_weight = isset($_POST['repupress_customize_woocommerce_product_cart_button_font_weight']) ? filter_var($_POST['repupress_customize_woocommerce_product_cart_button_font_weight'], FILTER_SANITIZE_STRING):"";
		if (!empty($cart_button_font_weight)) update_option('repupress_customize_woocommerce_product_cart_button_font_weight',$cart_button_font_weight); else update_option('repupress_customize_woocommerce_product_cart_button_font_weight',"");
		
		$cart_button_bg_color = isset($_POST['repupress_customize_woocommerce_product_cart_button_bg_color']) ? filter_var($_POST['repupress_customize_woocommerce_product_cart_button_bg_color'], FILTER_SANITIZE_STRING):"";
		if (!empty($cart_button_bg_color)) update_option('repupress_customize_woocommerce_product_cart_button_bg_color',$cart_button_bg_color); else update_option('repupress_customize_woocommerce_product_cart_button_bg_color',"");
		
									
		$html = '</br><div class="updated" id="message">
					<p><strong>'.__("Changes Saved Successfully.",'repupresscustomwoocommerceproduct').'</strong></p>
				</div>';
		}
	
	$html .= '<div class="wrap">'.screen_icon('options-general');
	
	$html .= '<h2>'.__("Cart Page Settings", 'repupresscustomwoocommerceproduct').'</h2>';
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
				
										<span style="vertical-align: top;">'. __( 'Cart Appearance Settings', 'repupresscustomwoocommerceproduct' ).'</span>
				
									</h3>
				
									<div class="inside">';
	
							$html .= '	<table class="form-table wpd-ws-settings-box"> 
											<tbody>';							
										
					
										$pi_check = (get_option('repupress_customize_woocommerce_product_cart_empty_button') == 1 ) ? 'checked="checked"' : '' ;
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Cart Empty Button :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><input type="checkbox" value="1" '.$pi_check.' name="repupress_customize_woocommerce_product_cart_empty_button" >
														<span class="description">'.__( 'Add "Cart Empty" button to cart page.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';
										
										$ps_tfc = get_option('repupress_customize_woocommerce_product_cart_button_font_color');
										$ps_tfc = !empty($ps_tfc) ? $ps_tfc : "#000000";
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Button Font Color :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td>
														<div class="color-picker" style="position: relative;">
															<input type="text" name="repupress_customize_woocommerce_product_cart_button_font_color" id="carttitlecolor"  value="'.$ps_tfc.'" />
															<div id="carttitlecolorpicker"></div>
														</div>
														
														<span class="description">'.__( 'Select font color for Empty Cart button.', 'repupresscustomwoocommerceproduct' ).'</span>
													</td>
													<td>';													
										$html .='</td>
												 </tr>';
												 
						
										$ps_fs = get_option('repupress_customize_woocommerce_product_cart_button_font_size');
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Button Font Size :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><select name="repupress_customize_woocommerce_product_cart_button_font_size">
															<option value="">Select Font Size</option>';
															$fontsize = 8;
															for($i=$fontsize;$i<100;$i++){
																if($ps_fs == $i)
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
										
										$ps_fw = get_option('repupress_customize_woocommerce_product_cart_button_font_weight');
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Button Font Weight :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td><select name="repupress_customize_woocommerce_product_cart_button_font_weight">
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
																if($key_opt == $ps_fw)
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
										
										
									
										$ps_bfc = get_option('repupress_customize_woocommerce_product_cart_button_bg_color');
										if(isset($ps_bfc) && $ps_bfc != ""){
											$grad_class = "gradient_".$ps_bfc;
										}else{
											$grad_class = "";
										}
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Button Color :', 'repupresscustomwoocommerceproduct' ).'</strong></label>
													</th>
													<td>
														<input type="hidden" id="repupress_customize_woocommerce_product_shop_button_bg_color" name="repupress_customize_woocommerce_product_cart_button_bg_color" value="'.$ps_bfc.'">
														<div id="repupress_customize_woocommerce_product_shop_btn_result">
														<style type="text/css">
														
														.button_example{
														-webkit-border-radius: 3px; -moz-border-radius: 3px;border-radius: 3px;font-size:12px;font-family:arial, helvetica, sans-serif; padding: 10px 10px 10px 10px; text-decoration:none; display:inline-block;text-shadow: -1px -1px 0 rgba(0,0,0,0.3);font-weight:bold; color: #FFFFFF;
														 }
														 </style>
														<a href="#" class="button_example '.$grad_class.'">Empty CART PREVIEW BUTTON</a>
														</div>
														<div id="repupress_customize_woocommerce_product_shop_btn_css_gradients">
															
															<div id="1" title="ffd65e" class="gradient_1"></div>
															<div id="2" title="606c88" class="gradient_2"></div>
															<div id="3" title="d5cea6" class="gradient_3"></div>
															<div id="4" title="a90329" class="gradient_4"></div>
															<div id="5" title="4ba614" class="gradient_5"></div>
															<div id="6" title="ff5db1" class="gradient_6"></div>
															<div id="7" title="7d7e7d" class="gradient_7"></div>
															<div id="8" title="cef8ff" class="gradient_8"></div>
															<div id="9" title="f2f9fe" class="gradient_9"></div>
															<div id="10" title="fb83fa" class="gradient_10"></div>		
															<div id="11" title="3093c7" class="gradient_11"></div>
															<div id="12" title="a9db80" class="gradient_12"></div>
															<div id="13" title="b29af8" class="gradient_13"></div>
															<div id="14" title="f2f5f6" class="gradient_14"></div>
															<div id="15" title="ffc579" class="gradient_15"></div>
															<div id="16" title="d3d3d3" class="gradient_16"></div>
															<div id="17" title="fcfac0" class="gradient_17"></div>
															<div id="18" title="f4f5f5" class="gradient_18"></div>
															<div id="19" title="f7e3e3" class="gradient_19"></div>
															<div id="20" title="ff9a9a" class="gradient_20"></div>
															<div id="21" title="a9a588" class="gradient_21"></div>
															<div id="22" title="f62b2b" class="gradient_22"></div>
															<div id="23" title="a67939" class="gradient_23"></div>
															<div id="24" title="d2d2f9" class="gradient_24"></div>
															<div id="25" title="49c0f0" class="gradient_25"></div>
															<div id="26" title="CEDCE7" class="gradient_26"></div>
															<div id="27" title="b6e026" class="gradient_27"></div>
															<div id="28" title="eab92d" class="gradient_28"></div>
															<div id="29" title="45484d" class="gradient_29"></div>
															<div id="30" title="92cfde" class="gradient_30"></div>
															<div id="31" title="a7cfdf" class="gradient_31"></div>
															<div id="32" title="E6E6E6" class="gradient_32"></div>
															<div id="33" title="a5b8da" class="gradient_33"></div>
															<div style="clear:both;height:1px;"></div>
		
														</div>
			
														<span class="description">'.__( 'Select button color for Empty Cart Button on cart page.', 'repupresscustomwoocommerceproduct' ).'</span>
													';													
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