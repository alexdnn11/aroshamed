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
	
    <h2><?php _e("Customize Shop Settings", 'repupresscustomwoocommerceproduct');?></h2>

	<div class="wpd-ws-reset-setting">
				<form method="post" action="">
					<input type="submit" class="button-primary" name="repupresscustomwoocommerceproduct_reset_settings" value="<?php _e( 'Reset To Deafault', 'repupresscustomwoocommerceproduct' );?>" />
				</form>
			</div>
            <?php
				if(isset($_POST['repupresscustomwoocommerceproduct_reset_settings']) && !empty($_POST['repupresscustomwoocommerceproduct_reset_settings']) && $_POST['repupresscustomwoocommerceproduct_reset_settings'] == __( 'Reset To Deafault', 'repupresscustomwoocommerceproduct' )) { //check click of reset button
				
					$repupress_customize_woocommerce_product_model->repupress_customize_woocommerce_product_save_option_default();
					$html = '</br><div class="updated" id="message">
								<p><strong>'.__("All Settings Reset Successfully.",'repupresscustomwoocommerceproduct').'</strong></p>
							</div>';
					echo $html;
				}
			?>

		<div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div><h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
			<?php
			    $current_tab     = empty( $_GET['tab'] ) ? 'product_page' : sanitize_title( $_GET['tab'] );
			    $tabs =array(
						'product_page'=> 'Product Shop Page',
						'product_page_appearance'=> 'Shop Page Appearance',			    		
                        'single_page'=>'Product Details Page',
						'single_page_appearance'=>'Details Page Appearance',
						'cart_page'=>'Cart Page',
						'checkout_page'=>'Checkout Page',											
  						
			    	);
				foreach ( $tabs as $name => $label )
					echo '<a href="' . admin_url( 'admin.php?page=woo-product-customizerort&tab=' . $name ) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';

				
			?>
		</h2>
		<?php
			switch ($current_tab) {
				
				case 'product_page':
						require_once('settings/woo-product-customizer-shop-page.php');
					break;
					
				case 'product_page_appearance':
						require_once('settings/woo-product-customizer-shop-page-appearance-settings.php');
					break;	
					
				case 'single_page':
						require_once('settings/woo-product-customizer-single-page.php');
					break;
					
				case 'single_page_appearance':
						require_once('settings/woo-product-customizer-details-page-appearance-settings.php');
					break;

				case 'cart_page':
						require_once('settings/woo-product-customizer-cart-page.php');
					break;

				case 'checkout_page':
						require_once('settings/woo-product-customizer-checkout-dashboard.php');
					break;
					
				default:
					
					break;
			}
		?>
        
</div>