<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
* Model Class
* Handles generic plugin functionality.
* @package WooCommerce - Customize Shop
* @since 1.0.0
*/
class RepUPress_Customize_Woocommerce_Model {
	
	public function __construct() {
	
	}
	
	/**
	* Escape Tags & Slashes
	* Handles escapping the slashes and tags
	* @package  WooCommerce - Customize Shop
	* @since 1.0.0
	*/
	public function repupress_customize_woocommerce_product_escape_attr($data){
		return esc_attr(stripslashes($data));
	}
	

	/**
	* Strip Slashes From Array
	* @package WooCommerce - Customize Shop
	* @since 1.0.0
	*/
	public function repupress_customize_woocommerce_product_escape_slashes_deep($data = array(),$flag=false){
			
		if($flag != true) {
			$data = $this->repupress_customize_woocommerce_product_nohtml_kses($data);
		}
		$data = stripslashes_deep($data);
		return $data;
	}

	
	/**
	* Strip Html Tags 
	* It will sanitize text input (strip html tags, and escape characters)
	* @package WooCommerce - Customize Shop
	* @since 1.0.0
	*/
	public function repupress_customize_woocommerce_product_nohtml_kses($data = array()) {
		
		if ( is_array($data) ) {
			
			$data = array_map(array($this,'repupress_customize_woocommerce_product_nohtml_kses'), $data);
			
		} elseif ( is_string( $data ) ) {
			
			$data = wp_filter_nohtml_kses($data);
		}
		
		return $data;
	}	
	
	/**	
	* Get default data settings for Excell or csv  
	* @package WooCommerce - Customize Shop
	* @since 1.0.0
	*/
	public function repupress_customize_woocommerce_product_save_option_default()
	{
		update_option('repupress_customize_woocommerce_product_shop_product_image',0);
		update_option('repupress_customize_woocommerce_product_shop_product_title',0);		
		update_option('repupress_customize_woocommerce_product_shop_product_prices',0);
		update_option('repupress_customize_woocommerce_product_shop_product_add_to_cart',0);
		update_option('repupress_customize_woocommerce_product_shop_product_logged_in',0);

		update_option('repupress_customize_woocommerce_product_single_product_list','all');
		update_option('repupress_customize_woocommerce_product_single_product_image',0);
		update_option('repupress_customize_woocommerce_product_single_product_title',0);
		update_option('repupress_customize_woocommerce_product_single_product_tab',0);
		update_option('repupress_customize_woocommerce_product_single_product_related',0);
		update_option('repupress_customize_woocommerce_product_single_product_prices',0);
		update_option('repupress_customize_woocommerce_product_single_product_add_to_cart',0);
		update_option('repupress_customize_woocommerce_product_single_product_logged_in',0);
		update_option('repupress_customize_woocommerce_product_single_product_category',0);
		
		// Shop page options
		update_option('repupress_customize_woocommerce_product_shop_set_unset_product_image',0);
		update_option('repupress_customize_woocommerce_product_shop_set_default_image','');
		update_option('repupress_customize_woocommerce_product_shop_title_font_color',"#000000");
		update_option('repupress_customize_woocommerce_product_shop_title_font_size','');
		update_option('repupress_customize_woocommerce_product_shop_title_font_weight','');
		update_option('repupress_customize_woocommerce_product_shop_price_font_color',"#000000");
		update_option('repupress_customize_woocommerce_product_shop_price_font_size',"");
		update_option('repupress_customize_woocommerce_product_shop_price_font_weight',"");
		update_option('repupress_customize_woocommerce_product_shop_button_bg_color',"");
		update_option('repupress_customize_woocommerce_product_shop_btn_font_color',"#000000");
		update_option('repupress_customize_woocommerce_product_shop_image_border_style',"");
		update_option('repupress_customize_woocommerce_product_shop_image_border_width',"");
		update_option('repupress_customize_woocommerce_product_shop_image_border_color',"#000000");
		
		// Product detail page options
		update_option('repupress_customize_woocommerce_product_detail_set_unset_product_image',0);
		update_option('repupress_customize_woocommerce_product_detail_set_default_image','');
		update_option('repupress_customize_woocommerce_product_detail_title_font_color',"#000000");
		update_option('repupress_customize_woocommerce_product_detail_title_font_size','');
		update_option('repupress_customize_woocommerce_product_detail_title_font_weight','');
		update_option('repupress_customize_woocommerce_product_detail_price_font_color',"#000000");
		update_option('repupress_customize_woocommerce_product_detail_price_font_size',"");
		update_option('repupress_customize_woocommerce_product_detail_price_font_weight',"");
		update_option('repupress_customize_woocommerce_product_detail_button_bg_color',"");
		update_option('repupress_customize_woocommerce_product_detail_btn_font_color',"#000000");
		update_option('repupress_customize_woocommerce_product_detail_image_border_style',"");
		update_option('repupress_customize_woocommerce_product_detail_image_border_width',"");
		update_option('repupress_customize_woocommerce_product_detail_image_border_color',"#000000");
		update_option('repupress_customize_woocommerce_product_detail_description_font_color',"#000000");
		update_option('repupress_customize_woocommerce_product_detail_description_font_size','');
		update_option('repupress_customize_woocommerce_product_detail_description_font_weight','');
		update_option('repupress_customize_woocommerce_product_detail_category_font_color',"#000000");
		update_option('repupress_customize_woocommerce_product_detail_category_font_size','');
		update_option('repupress_customize_woocommerce_product_detail_category_font_weight','');
	}		
}
?>