<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Pages Class
 *
 * Handles generic Admin functionailties
 *
 * @package WooCommerce - Customize Shop
 * @since 1.0.0
 */
global $repupress_customize_woocommerce_product_model;
	
$model = $repupress_customize_woocommerce_product_model;

?>

<div class="wrap woocommerce">
	
		<div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div><h3 >
			<?php
			    $current_tab     = empty( $_GET['report'] ) ? 'checkout_option' : sanitize_title( $_GET['report'] );
			    $tabs =array(
			    		'checkout_option'=> 'Checkout Option',
			    		'custom_checkout_fields'=> 'Custom Fields',
			    		'checkout_fields_settings'=> 'Fields Settings',
			    		 						
			    	);
				foreach ( $tabs as $name => $label )
					echo '<a href="' . admin_url( 'admin.php?page=woo-product-customizerort&tab=checkout_page&report=' . $name ) . '" class="checkout-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';

				
			?>
		</h3>
		<?php
			switch ($current_tab) {

				case 'checkout_option':
					require_once('woo-product-customizer-checkout-page.php');
					break;

				case 'custom_checkout_fields':
						require_once('woo-product-customizer-checkout-custom-fields.php');
					break;

				case 'checkout_fields_settings':
						require_once('woo-product-customizer-checkout-fields-settings.php');
					break;

				default:
					
					break;
			}
		?>
        
</div>