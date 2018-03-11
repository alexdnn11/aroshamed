<?php
/**
* Plugin Name: Customize Woocommerce Shop
* Plugin URI: https://www.wpcloudapp.com/shop/wordpress-plugins/customize-woocommerce-wordpress-plugin/
* Description: Customize every aspect of your Woocommerce Shop with this incredibly powerful Plugin.
* Version: 6.0
* Author: RepUPress.com
* Author URI: https://www.WPCloudApp.com
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Basic plugin definitions
* @package WooCommerce - Customize Shop
* @since 1.0.0
*/

global $wpdb;

if( !defined( 'REPUPRESS_CUSTOMIZE_WOOCOMMERCE' ) ) {
	define( 'REPUPRESS_CUSTOMIZE_WOOCOMMERCE', plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'WOO_PRODUCT_CUST_DIR' ) ) {
	define( 'WOO_PRODUCT_CUST_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'WOO_PRODUCT_CUST_ADMIN' ) ) {
	define( 'WOO_PRODUCT_CUST_ADMIN', WOO_PRODUCT_CUST_DIR . '/includes/admin' ); // plugin admin dir
}

/**
* Load Text Domain
* This gets the plugin ready for translation.
* @package WooCommerce - Customize Shop
* @since 1.0.0
*/
function repupress_customize_woocommerce_product_load_textdomain() {

  load_plugin_textdomain( 'repupresscustomwoocommerceproduct', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

}
add_action( 'init', 'repupress_customize_woocommerce_product_load_textdomain' );



/**
* Activation Hook
* Register plugin activation hook.
* @package WooCommerce - Customize Shop
* @since 1.0.0
*/	
register_activation_hook( __FILE__, 'repupress_customize_woocommerce_product_install' );

	
/**
* Deactivation Hook
* Register plugin deactivation hook.
* @package WooCommerce - Customize Shop
* @since 1.0.0
*/	
register_deactivation_hook( __FILE__, 'repupress_customize_woocommerce_product_uninstall');
	
	
/**
* Plugin Setup (On Activation)
* Does the initial setup,
* stest default values for the plugin options.
* @package WooCommerce - Customize Shop
* @since 1.0.0
*/	
function repupress_customize_woocommerce_product_install() {		
	global $wpdb,$repupress_customize_woocommerce_product_model;		
	$repupress_customize_woocommerce_product_model -> repupress_customize_woocommerce_product_save_option_default();
}
	
/**
* Plugin Setup (On Deactivation)
* Delete  plugin options.
* @package WooCommerce - Customize Shop
* @since 1.0.0
*/	
function repupress_customize_woocommerce_product_uninstall() {

		delete_option('repupress_customize_woocommerce_product_shop_product_image');
		delete_option('repupress_customize_woocommerce_product_shop_product_title');		
		delete_option('repupress_customize_woocommerce_product_shop_product_prices');
		delete_option('repupress_customize_woocommerce_product_shop_product_add_to_cart');
		delete_option('repupress_customize_woocommerce_product_shop_product_logged_in');

		delete_option('repupress_customize_woocommerce_product_single_product_list');
		delete_option('repupress_customize_woocommerce_product_single_product_image');
		delete_option('repupress_customize_woocommerce_product_single_product_title');
		delete_option('repupress_customize_woocommerce_product_single_product_tab');
		delete_option('repupress_customize_woocommerce_product_single_product_related');
		delete_option('repupress_customize_woocommerce_product_single_product_prices');
		delete_option('repupress_customize_woocommerce_product_single_product_add_to_cart');
		delete_option('repupress_customize_woocommerce_product_single_product_logged_in');
		delete_option('repupress_customize_woocommerce_product_single_product_category');
		
		// Shop page options
		delete_option('repupress_customize_woocommerce_product_shop_set_unset_product_image');
		delete_option('repupress_customize_woocommerce_product_shop_set_default_image');
		delete_option('repupress_customize_woocommerce_product_shop_title_font_color');
		delete_option('repupress_customize_woocommerce_product_shop_title_font_size');
		delete_option('repupress_customize_woocommerce_product_shop_title_font_weight');
		delete_option('repupress_customize_woocommerce_product_shop_price_font_color');
		delete_option('repupress_customize_woocommerce_product_shop_price_font_size');
		delete_option('repupress_customize_woocommerce_product_shop_price_font_weight');
		delete_option('repupress_customize_woocommerce_product_shop_button_bg_color');
		delete_option('repupress_customize_woocommerce_product_shop_btn_font_color');
		delete_option('repupress_customize_woocommerce_product_shop_image_border_style');
		delete_option('repupress_customize_woocommerce_product_shop_image_border_width');
		delete_option('repupress_customize_woocommerce_product_shop_image_border_color');
		
		// Product detail page options
		delete_option('repupress_customize_woocommerce_product_detail_set_unset_product_image');
		delete_option('repupress_customize_woocommerce_product_detail_set_default_image');
		delete_option('repupress_customize_woocommerce_product_detail_title_font_color');
		delete_option('repupress_customize_woocommerce_product_detail_title_font_size');
		delete_option('repupress_customize_woocommerce_product_detail_title_font_weight');
		delete_option('repupress_customize_woocommerce_product_detail_price_font_color');
		delete_option('repupress_customize_woocommerce_product_detail_price_font_size');
		delete_option('repupress_customize_woocommerce_product_detail_price_font_weight');
		delete_option('repupress_customize_woocommerce_product_detail_button_bg_color');
		delete_option('repupress_customize_woocommerce_product_detail_btn_font_color');
		delete_option('repupress_customize_woocommerce_product_detail_image_border_style');
		delete_option('repupress_customize_woocommerce_product_detail_image_border_width');
		delete_option('repupress_customize_woocommerce_product_detail_image_border_color');
		delete_option('repupress_customize_woocommerce_product_detail_description_font_color');
		delete_option('repupress_customize_woocommerce_product_detail_description_font_size');
		delete_option('repupress_customize_woocommerce_product_detail_description_font_weight');
		delete_option('repupress_customize_woocommerce_product_detail_category_font_color');
		delete_option('repupress_customize_woocommerce_product_detail_category_font_size');
		delete_option('repupress_customize_woocommerce_product_detail_category_font_weight');

	}
		
/**
* Start Session
* @package WooCommerce - Customize Shop
* @since 1.0.0
*/	
function repupress_customize_woocommerce_product_start_session() {
	
	if( !session_id() ) {
		session_start();
	}
}

/**
* Includes Files
* Includes some required files for plugin
* @package WooCommerce - Customize Shop
* @since 1.0.0
*/

global $repupress_customize_woocommerce_product_model, $repupress_customize_woocommerce_product_scripts, $repupress_customize_woocommerce_product_admin;	


//Model Class for generic functions
require_once( WOO_PRODUCT_CUST_DIR . '/includes/class-woo-product-customizer-model.php' );
$repupress_customize_woocommerce_product_model = new RepUPress_Customize_Woocommerce_Model();

//Scripts Class for scripts / styles
require_once( WOO_PRODUCT_CUST_DIR . '/includes/class-woo-product-customizer-scripts.php' );
$repupress_customize_woocommerce_product_scripts = new RepUPress_Customize_Woocommerce_Scripts();
$repupress_customize_woocommerce_product_scripts->add_hooks();

//Admin Pages Class for admin site
require_once( WOO_PRODUCT_CUST_ADMIN . '/class-woo-product-customizer-admin.php' );
$repupress_customize_woocommerce_product_admin = new RepUPress_Customize_Woocommerce_Admin();
$repupress_customize_woocommerce_product_admin->add_hooks();	

?>