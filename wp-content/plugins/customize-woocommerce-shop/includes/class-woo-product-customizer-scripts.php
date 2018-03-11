<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
* Scripts Class
* Handles adding scripts functionality to the admin pages
* as well as the front pages.
* @package WooCommerce - Customize Shop
* @since 1.0.0
*/
class RepUPress_Customize_Woocommerce_Scripts{
	
	public function __construct() {
		
	}
	
	/**
	* Enqueue Styles for backend on needed page
	* @package WooCommerce - Customize Shop
	* @since 1.0.0
	*/
	public function repupress_customize_woocommerce_product_admin_styles() {	
		
			wp_register_style( 'woo-product-customizer-admin-styles', REPUPRESS_CUSTOMIZE_WOOCOMMERCE . 'includes/css/style-admin.css', array(), null);
			wp_enqueue_style( 'woo-product-customizer-admin-styles' );

			wp_enqueue_style( 'jquery-ui-datepicker' );
			wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	}


	/**
	* Enqueue Scripts for backend on needed page
	* @package WooCommerce - Customize Shop
	* @since 1.0.0
	*/
	public function repupress_customize_woocommerce_product_admin_scripts( $hook_suffix ) {
		
		global $wp_version;
		$newui = $wp_version >= '3.5' ? '1' : '0'; 			
		
		if(in_array($hook_suffix,array('woocommerce_page_woo-product-customizerort'))){
			wp_enqueue_script( 'jquery');
			wp_enqueue_script( 'jquery-ui-core');
			wp_enqueue_script("jquery-ui-datepicker");
			wp_enqueue_style( 'farbtastic' );
			
			if(isset($_GET['tab'])){
				$tab = filter_var($_GET['tab'], FILTER_SANITIZE_STRING);
			
			//wp_enqueue_script( 'farbtastic' );
				if($tab == "product_page_appearance"){
					wp_enqueue_script( 'woo-product-cust-admin-script', REPUPRESS_CUSTOMIZE_WOOCOMMERCE .'/includes/js/woo-product-customizer-script.js', array( 'farbtastic', 'jquery' ) );
				}else if($tab == "single_page_appearance"){
					wp_enqueue_script( 'woo-product-cust-admin-script', REPUPRESS_CUSTOMIZE_WOOCOMMERCE .'/includes/js/woo-product-customizer-admin-script-single.js', array( 'farbtastic', 'jquery' ) );
				}else if($tab == "cart_page"){
					wp_enqueue_script( 'woo-product-cust-admin-script', REPUPRESS_CUSTOMIZE_WOOCOMMERCE .'/includes/js/woo-product-customizer-admin-script-cart_page.js', array( 'farbtastic', 'jquery' ) );
				}else if($tab == "checkout_page"){
					wp_enqueue_script( 'woo-product-cust-admin-script', REPUPRESS_CUSTOMIZE_WOOCOMMERCE .'/includes/js/woo-product-customizer-admin-script-checkout_page.js', array( 'farbtastic', 'jquery' ) );
				}
			}
			
			wp_register_script( 'woo-product-customizer-admin-scripts', REPUPRESS_CUSTOMIZE_WOOCOMMERCE . 'includes/js/woo-product-customizer-admin.js', array('jquery', 'jquery-ui-sortable' ) , null, true );
			wp_enqueue_script( 'woo-product-customizer-admin-scripts' );				
			
			wp_localize_script( 'woo-product-customizer-admin-scripts','WooOrderExp',array( 'new_media_ui'	=>	$newui ) );
		
			wp_enqueue_media();
			wp_enqueue_script( 'media-upload' );
			
			
			wp_register_script( 'woo-order-choosen', REPUPRESS_CUSTOMIZE_WOOCOMMERCE . 'includes/js/woo-product-customizer-chosen.jquery.js', array('jquery') , null, true );
			wp_enqueue_script( 'woo-order-choosen' );	
			wp_register_style( 'woo-order-choosen-css', REPUPRESS_CUSTOMIZE_WOOCOMMERCE . 'includes/css/woo-product-customizer-chosen.css', array(), null);
			wp_enqueue_style( 'woo-order-choosen-css' );	
		}
		
	}
	
	public function repupress_customize_woocommerce_product_public_style(){
		wp_register_style( 'woo-product-customizer-public-style', REPUPRESS_CUSTOMIZE_WOOCOMMERCE . 'includes/css/woo-product-customizer-public-style.css',array(), null);
		wp_enqueue_style( 'woo-product-customizer-public-style' );			
		
	}
	public function repupress_customize_woocommerce_product_public_scripts(){

		
		if (is_shop()) {
		
			$ps_bfc = get_option('repupress_customize_woocommerce_product_shop_button_bg_color');
			$ps_btnfc = get_option('repupress_customize_woocommerce_product_shop_btn_font_color');
			$ps_imgbs = get_option('repupress_customize_woocommerce_product_shop_image_border_style');
			$ps_imgbw = get_option('repupress_customize_woocommerce_product_shop_image_border_width');
			$ps_imgbc = get_option('repupress_customize_woocommerce_product_shop_image_border_color');
			$ps_pricefc = get_option('repupress_customize_woocommerce_product_shop_price_font_color');
			$ps_pricefs = get_option('repupress_customize_woocommerce_product_shop_price_font_size');
			$ps_pricefw = get_option('repupress_customize_woocommerce_product_shop_price_font_weight');
			$ps_titlefc = get_option('repupress_customize_woocommerce_product_shop_title_font_color');
			$ps_titlefs = get_option('repupress_customize_woocommerce_product_shop_title_font_size');
			$ps_titlefw = get_option('repupress_customize_woocommerce_product_shop_title_font_weight');
																	
			wp_register_script( 'woo-product-customizer-front-scripts', REPUPRESS_CUSTOMIZE_WOOCOMMERCE . 'includes/js/woo-product-customizer-front-shop.js', array('jquery', 'jquery-ui-sortable' ) , null, true );
			wp_enqueue_script( 'woo-product-customizer-front-scripts' );				
			wp_localize_script( 'woo-product-customizer-front-scripts','wpFrontProductCust', array(
																										'product_cust_shop_button_bg_color' => $ps_bfc,
																										'product_cust_shop_button_font_color' => $ps_btnfc,
																										'product_cust_shop_image_border_style' => $ps_imgbs,
																										'product_cust_shop_image_border_width' => $ps_imgbw,
																										'product_cust_shop_image_border_color' => $ps_imgbc,
																										'product_cust_shop_price_font_color' => $ps_pricefc,
																										'product_cust_shop_price_font_size' => $ps_pricefs,
																										'product_cust_shop_price_font_weight' => $ps_pricefw,
																										'product_cust_shop_title_font_color' => $ps_titlefc,
																										'product_cust_shop_title_font_size' => $ps_titlefs,
																										'product_cust_shop_title_font_weight' => $ps_titlefw
																									));				
			
		}
		
		if (is_product()) {
		
			$ps_bfc = get_option('repupress_customize_woocommerce_product_detail_button_bg_color');
			$ps_btnfc = get_option('repupress_customize_woocommerce_product_detail_btn_font_color');
			$ps_imgbs = get_option('repupress_customize_woocommerce_product_detail_image_border_style');
			$ps_imgbw = get_option('repupress_customize_woocommerce_product_detail_image_border_width');
			$ps_imgbc = get_option('repupress_customize_woocommerce_product_detail_image_border_color');
			$ps_pricefc = get_option('repupress_customize_woocommerce_product_detail_price_font_color');
			$ps_pricefs = get_option('repupress_customize_woocommerce_product_detail_price_font_size');
			$ps_pricefw = get_option('repupress_customize_woocommerce_product_detail_price_font_weight');
			$ps_titlefc = get_option('repupress_customize_woocommerce_product_detail_title_font_color');
			$ps_titlefs = get_option('repupress_customize_woocommerce_product_detail_title_font_size');
			$ps_titlefw = get_option('repupress_customize_woocommerce_product_detail_title_font_weight');
			$ps_catfc = get_option('repupress_customize_woocommerce_product_detail_category_font_color');
			$ps_catfs = get_option('repupress_customize_woocommerce_product_detail_category_font_size');
			$ps_catfw = get_option('repupress_customize_woocommerce_product_detail_category_font_weight');
			$ps_descfc = get_option('repupress_customize_woocommerce_product_detail_description_font_color');
			$ps_descfs = get_option('repupress_customize_woocommerce_product_detail_description_font_size');
			$ps_descfw = get_option('repupress_customize_woocommerce_product_detail_description_font_weight');
			//$ps_starc = get_option('repupress_customize_woocommerce_product_detail_review_star_color');

																	
			wp_register_script( 'woo-product-detail-customizer-front-scripts', REPUPRESS_CUSTOMIZE_WOOCOMMERCE . 'includes/js/woo-product-customizer-front-detail.js', array('jquery', 'jquery-ui-sortable' ) , null, true );
			wp_enqueue_script( 'woo-product-detail-customizer-front-scripts' );				
			wp_localize_script( 'woo-product-detail-customizer-front-scripts','wpFrontProductCustDetail', array(
																										'product_cust_detail_button_bg_color' => $ps_bfc,
																										'product_cust_detail_button_font_color' => $ps_btnfc,
																										'product_cust_detail_image_border_style' => $ps_imgbs,
																										'product_cust_detail_image_border_width' => $ps_imgbw,
																										'product_cust_detail_image_border_color' => $ps_imgbc,
																										'product_cust_detail_price_font_color' => $ps_pricefc,
																										'product_cust_detail_price_font_size' => $ps_pricefs,
																										'product_cust_detail_price_font_weight' => $ps_pricefw,
																										'product_cust_detail_title_font_color' => $ps_titlefc,
																										'product_cust_detail_title_font_size' => $ps_titlefs,
																										'product_cust_detail_title_font_weight' => $ps_titlefw,
																										'product_cust_detail_cat_font_color' => $ps_catfc,
																										'product_cust_detail_cat_font_size' => $ps_catfs,
																										'product_cust_detail_cat_font_weight' => $ps_catfw,
																										'product_cust_detail_desc_font_color' => $ps_descfc,
																										'product_cust_detail_desc_font_size' => $ps_descfs,
																										'product_cust_detail_desc_font_weight' => $ps_descfw,
																										/*product_cust_detail_review_star_color' => $ps_starc*/
																										
																									));				
			
		}

		if (is_checkout()) {			
		
			$ch_tfc = get_option('repupress_customize_woocommerce_product_checkout_title_font_color');
			$ch_tfs = get_option('repupress_customize_woocommerce_product_checkout_title_font_size');
			$ch_tfw = get_option('repupress_customize_woocommerce_product_checkout_title_font_weight');
			wp_register_script( 'woo-product-detail-customizer-front-scripts', REPUPRESS_CUSTOMIZE_WOOCOMMERCE . 'includes/js/woo-product-customizer-front-checkout.js', array('jquery', 'jquery-ui-sortable' ) , null, true );
			wp_enqueue_script( 'woo-product-detail-customizer-front-scripts' );				
			wp_localize_script( 'woo-product-detail-customizer-front-scripts','wpFrontCheckout', array(																										
																										'checkout_field_font_color' => $ch_tfc,
																										'checkout_field_font_size' => $ch_tfs,
																										'checkout_field_font_weight' => $ch_tfw,
																									));				
		}	
	}

	/**
	* Adding Hooks
	* Adding proper hoocks for the scripts.
	* @package WooCommerce - Customize Shop
	* @since 1.0.0
	*/
	public function add_hooks() {

		//add styles for back end
		add_action( 'admin_enqueue_scripts', array($this, 'repupress_customize_woocommerce_product_admin_styles') );
		
		//add script to back side for order export
		add_action( 'admin_enqueue_scripts', array($this, 'repupress_customize_woocommerce_product_admin_scripts') );
		add_action( 'wp_enqueue_scripts', array($this, 'repupress_customize_woocommerce_product_public_scripts') );
		add_action( 'wp_head', array($this, 'repupress_customize_woocommerce_product_public_style') );
	}
	
}
?>