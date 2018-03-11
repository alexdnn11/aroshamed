<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;
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
    <h2><?php _e("Customize Shop Options", 'repupresscustomwoocommerceproduct'); ?></h2>
    <?php
    if (isset($notices)):
        foreach ($notices as $notice):
            ?>
            <div class='<?= $notice['class']; ?>'>
                <p><?= $notice['message']; ?></p>
            </div>
            <?php
        endforeach;
    endif;
    ?>
	<p styles="font-size:16px"><strong>Please Note:</strong> You need a <strong>Free</strong> API Key to unlock the functionality of this Plugin. <strong>After Activating your API Key click on the Customize Shop menu link</strong>.</p>
    <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="fp_poster_options_nonce" id="fp_poster_options_nonce" value="<?php echo wp_create_nonce('fp_poster_options_nonce'); ?>" />
        <table class="form-table">
            <tr>
                <th scope="row">
                </th>
                <td>
                    Get an API key: <a target="_blank" href="http://www.wpcloudapp.com/shop/wordpress-plugins/customize-woocommerce-wordpress-plugin">Click Here</a>
                </td> 
            </tr>
            <tr>
                <th scope="row">
                    <label for="repupress_customize_woocommerce_api_key">API License Key</label>
                </th>
                <td>
                    <?php if ($status_check == 'active'): ?>
                        <?php echo get_option('repupress_customize_woocommerce_api_key'); ?>
                        <input id="repupress_customize_woocommerce_api_key" type="hidden" name="repupress_customize_woocommerce_api_key" value="<?php echo get_option('repupress_customize_woocommerce_api_key'); ?>"/>
                    <?php else: ?>
                        <input id="repupress_customize_woocommerce_api_key" type="text" name="repupress_customize_woocommerce_api_key" value="<?php echo get_option('repupress_customize_woocommerce_api_key'); ?>"/>
                    <?php endif; ?> 
                </td> 
            </tr>
            <tr>
                <th scope="row">
                    <label for="repupress_customize_woocommerce_api_email">API License Email</label>
                </th>
                <td> 
                    <?php if ($status_check == 'active'): ?>
                        <?php echo get_option('repupress_customize_woocommerce_api_email'); ?>
                        <input id="repupress_customize_woocommerce_api_email" type="hidden" name="repupress_customize_woocommerce_api_email" value="<?php echo get_option('repupress_customize_woocommerce_api_email'); ?>"/>
                    <?php else: ?>
                        <input id="repupress_customize_woocommerce_api_email" type="text" name="repupress_customize_woocommerce_api_email" value="<?php echo get_option('repupress_customize_woocommerce_api_email'); ?>"/>
                    <?php endif; ?>
                </td> 
            </tr> 
            <tr>
                <th scope="row">
                    <label for="repupress_customize_woocommerce_api_key">Activation status</label>
                </th>
                <td>
                    <?= $status_check; ?>
                </td> 
            </tr>
        </table>
        <p class="submit">
            <?php if ($status_check == 'active'): ?>
                <input type="submit" class="button-primary" value="<?php _e('Deactivate plugin') ?>" />
                <input type="hidden" name="status_check" value="inactive" />
            <?php else: ?>
                <input type="submit" class="button-primary" value="<?php _e('Activate plugin') ?>" />
                <input type="hidden" name="status_check" value="active" />
            <?php endif; ?>
        </p>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="repupress_customize_woocommerce_api_key" />
    </form>	
</div>