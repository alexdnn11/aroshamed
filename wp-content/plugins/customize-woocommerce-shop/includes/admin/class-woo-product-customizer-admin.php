<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    die;
}
/**

 * Admin Class

 * Handles generic Admin functionality and AJAX requests.

 * @package WooCommerce - Customize Shop

 * @since 1.0.0

 */
class RepUPress_Customize_Woocommerce_Admin
{
    CONST woo_domain = 'www.wpcloudapp.com';
    
    CONST plugin_id = 'plugin123123123';
    
    public  $model, $scripts ;
    public function __construct()
    {
        global  $repupress_customize_woocommerce_product_model, $repupress_customize_woocommerce_product_scripts ;
        $this->model = $repupress_customize_woocommerce_product_model;
        $this->scripts = $repupress_customize_woocommerce_product_scripts;
    }
    
    /**

     *  Register All need admin menu page

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_product_admin_menu_pages()
    { 
        $repupress_customize_woocommerce_productomizer = add_submenu_page(
            'woocommerce',
            __( 'Customize Shop', 'repupresscustomwoocommerceproduct' ),
            __( 'Customize Shop', 'repupresscustomwoocommerceproduct' ),
            'manage_woocommerce',
            'woo-product-customizerort',
            array( $this, 'repupress_customize_woocommerce_productomizer' )
        );
        add_submenu_page(
            'woocommerce',  __( 'Customize Shop API', 'repupresscustomwoocommerceproduct' ), __( 'Customize Shop API', 'repupresscustomwoocommerceproduct' ), 'manage_options', 'woo-product-customizerort-options', array( $this, 'repupress_customize_woocommerce_options' ));
    }
    
    /**
     * Send requests to woocommerce API manager
     * @param string $key
     * @param string $email
     * @param string $request
     * @return mixed
     */
    static function repupress_customize_woocommerce_woo_api($key, $email, $request) {
        $email_enc = urlencode($email);
        $instance = md5($key . $email . $_SERVER['SERVER_NAME']);
        $plugin_id = urlencode(self::plugin_id);
        $url = "http://" . self::woo_domain . "/?wc-api=am-software-api&request={$request}&email={$email_enc}&licence_key={$key}&product_id={$plugin_id}&instance={$instance}&platform={$_SERVER['SERVER_NAME']}";
        $resp = file_get_contents($url);
        $resp = json_decode($resp);
        return $resp;
    }
    
    /**

     * Add Woo Customizer on woocommerce menu

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_options()
    {
            //activate/deactivate API key
        if ($_POST) {
            if ($_POST['status_check'] == 'active') {
                $woo_request = 'activation';
            } else {
                $woo_request = 'deactivation';
            }
            $resp = self::repupress_customize_woocommerce_woo_api(trim($_POST['repupress_customize_woocommerce_api_key']), trim($_POST['repupress_customize_woocommerce_api_email']), $woo_request);
            if (isset($resp->error)) {
                $notices[] = array('message' => 'Error: ' . $resp->error . ' Code: ' . $resp->code, 'class' => 'notice notice-error is-dismissible');
            } else {
                if ($_POST['status_check'] == 'active') {
                    $notices[] = array('message' => 'Successfully activated', 'class' => 'notice notice-success is-dismissible');
                } else {
                    $notices[] = array('message' => 'Successfully deactivated', 'class' => 'notice notice-success is-dismissible');
                }
            }
            update_option('repupress_customize_woocommerce_api_key', trim($_POST['repupress_customize_woocommerce_api_key']));
            update_option('repupress_customize_woocommerce_api_email', trim($_POST['repupress_customize_woocommerce_api_email']));
        }

        $resp = self::repupress_customize_woocommerce_woo_api(get_option('repupress_customize_woocommerce_api_key'), get_option('repupress_customize_woocommerce_api_email'), 'status');
        if (isset($resp->status_check)) {
            $status_check = $resp->status_check;
        } else {
            $status_check = '';
        }
        include_once WOO_PRODUCT_CUST_ADMIN . '/forms/woo-product-customizer-options.php';
    }
    
    /**

     * Add Woo Customizer on woocommerce menu

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_productomizer()
    {
        if (false !== strstr($_SERVER['REQUEST_URI'], 'page=woo-product-customizerort')) {
            $resp = self::repupress_customize_woocommerce_woo_api(get_option('repupress_customize_woocommerce_api_key'), get_option('repupress_customize_woocommerce_api_email'), 'status');
            if (!isset($resp->status_check)) {
                $resp->status_check = 'inactive';
            }
            if ($resp->status_check != 'active' && $_SERVER['REQUEST_URI'] != '/wp-admin/admin.php?page=woo-product-customizerort-options') {
                if (wp_redirect(get_site_url() . '/wp-admin/admin.php?page=woo-product-customizerort-options')) {
                    exit;
                }
            }
        }
        include_once WOO_PRODUCT_CUST_ADMIN . '/forms/woo-product-customizer-page.php';
    }
    
    /******* Function for Product Page ********/
    /**

     * Settings For  Product Title

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_productomizer_title_changes( $title, $id = '' )
    {
        
        if ( is_shop() ) {
            $product_list_check = explode( ',', get_option( 'repupress_customize_woocommerce_product_shop_product_list' ) );
            if ( !empty($product_list_check) && count( $product_list_check ) >= 1 ) {
                
                if ( in_array( get_the_id(), $product_list_check ) ) {
                    return '';
                } else {
                    if ( !empty($product_list_check) && count( $product_list_check ) == 1 && !in_array( 'all', $product_list_check ) ) {
                        return $title;
                    }
                }
            
            }
            if ( !empty($product_list_check) && count( $product_list_check ) == 1 && in_array( 'all', $product_list_check ) ) {
                return '';
            }
        }
    
    }
    
    /**

     * Settings For  Product Images

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_productomizer_before_imageless_product()
    {
        
        if ( is_shop() ) {
            $product_list_check = explode( ',', get_option( 'repupress_customize_woocommerce_product_shop_product_list' ) );
            if ( !empty($product_list_check) && count( $product_list_check ) >= 1 ) {
                
                if ( in_array( get_the_id(), $product_list_check ) ) {
                    
                    if ( get_option( 'repupress_customize_woocommerce_product_shop_product_image' ) == 1 ) {
                        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
                        echo  '<div class="no-image">' ;
                    }
                    
                    if ( get_option( 'repupress_customize_woocommerce_product_shop_product_title' ) == 1 ) {
                        add_filter( 'the_title', array( $this, 'repupress_customize_woocommerce_productomizer_title_changes' ) );
                    }
                    
                    if ( get_option( 'repupress_customize_woocommerce_product_shop_product_logged_in' ) == 1 ) {
                        if ( !is_user_logged_in() ) {
                            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
                        }
                    } else {
                        if ( get_option( 'repupress_customize_woocommerce_product_shop_product_prices' ) == 1 ) {
                            remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
                        }
                        if ( get_option( 'repupress_customize_woocommerce_product_shop_product_add_to_cart' ) == 1 ) {
                            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
                        }
                    }
                
                }
            
            }
            
            if ( !empty($product_list_check) && count( $product_list_check ) == 1 && in_array( 'all', $product_list_check ) ) {
                
                if ( get_option( 'repupress_customize_woocommerce_product_shop_product_image' ) == 1 ) {
                    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
                    echo  '<div class="no-image">' ;
                }
                
                if ( get_option( 'repupress_customize_woocommerce_product_shop_product_title' ) == 1 ) {
                    add_filter( 'the_title', array( $this, 'repupress_customize_woocommerce_productomizer_title_changes' ) );
                }
                
                if ( get_option( 'repupress_customize_woocommerce_product_shop_product_logged_in' ) == 1 ) {
                    if ( !is_user_logged_in() ) {
                        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
                    }
                } else {
                    if ( get_option( 'repupress_customize_woocommerce_product_shop_product_prices' ) == 1 ) {
                        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
                    }
                    if ( get_option( 'repupress_customize_woocommerce_product_shop_product_add_to_cart' ) == 1 ) {
                        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
                    }
                }
            
            }
        
        }
        
        if ( get_option( 'repupress_customize_woocommerce_product_shop_set_unset_product_image' ) == 1 ) {
            
            if ( !has_post_thumbnail( get_the_id() ) ) {
                remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
                echo  '<div class="no-image">' ;
            }
        
        }
    }
    
    /**

     * Settings For  Product Images

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_productomizer_after_imageless_product()
    {
        
        if ( is_shop() ) {
            $product_list_check = explode( ',', get_option( 'repupress_customize_woocommerce_product_shop_product_list' ) );
            if ( !empty($product_list_check) && count( $product_list_check ) >= 1 ) {
                
                if ( in_array( get_the_id(), $product_list_check ) ) {
                    
                    if ( get_option( 'repupress_customize_woocommerce_product_shop_product_image' ) == 1 ) {
                        add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
                        echo  '</div>' ;
                    }
                    
                    
                    if ( get_option( 'repupress_customize_woocommerce_product_shop_product_logged_in' ) == 1 ) {
                        if ( !is_user_logged_in() ) {
                            add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
                        }
                    } else {
                        if ( get_option( 'repupress_customize_woocommerce_product_shop_product_prices' ) == 1 ) {
                            add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
                        }
                        if ( get_option( 'repupress_customize_woocommerce_product_shop_product_add_to_cart' ) == 1 ) {
                            add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
                        }
                    }
                
                }
            
            }
            
            if ( !empty($product_list_check) && count( $product_list_check ) == 1 && in_array( 'all', $product_list_check ) ) {
                
                if ( get_option( 'repupress_customize_woocommerce_product_shop_product_image' ) == 1 ) {
                    add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
                    echo  '</div>' ;
                }
                
                
                if ( get_option( 'repupress_customize_woocommerce_product_shop_product_logged_in' ) == 1 ) {
                    if ( !is_user_logged_in() ) {
                        add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
                    }
                } else {
                    if ( get_option( 'repupress_customize_woocommerce_product_shop_product_prices' ) == 1 ) {
                        add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
                    }
                    if ( get_option( 'repupress_customize_woocommerce_product_shop_product_add_to_cart' ) == 1 ) {
                        add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
                    }
                }
            
            }
        
        }
        
        if ( get_option( 'repupress_customize_woocommerce_product_shop_set_unset_product_image' ) == 1 ) {
            
            if ( !has_post_thumbnail( get_the_id() ) ) {
                add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
                echo  '</div>' ;
            }
        
        }
    }
    
    /**

     * Used For emtpy or unset images for shop page

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_productomizer_replace_unset_images( $src )
    {
        $image = get_option( 'repupress_customize_woocommerce_product_shop_set_default_image' );
        if ( !empty($image) ) {
            $src = get_option( 'repupress_customize_woocommerce_product_shop_set_default_image' );
        }
        return $src;
    }
    
    /******* Function for Product Details Page ********/
    /**

     * Used For product Details Page.

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_productomizer_single_page()
    {
        global  $product, $post ;
        
        if ( is_product() ) {
            
            if ( get_option( 'repupress_customize_woocommerce_product_detail_set_unset_product_image' ) == 1 ) {
                
                if ( !has_post_thumbnail( get_the_id() ) ) {
                    add_filter( 'body_class', array( $this, 'repupress_customize_woocommerce_productomizer_add_body_css_class' ) );
                    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
                }
            
            } else {
                add_filter( 'woocommerce_placeholder_img_src', array( $this, 'repupress_customize_woocommerce_productomizer_details_page_replace_image' ) );
            }
            
            add_filter( 'woocommerce_get_price_html', array( $this, 'repupress_customize_woocommerce_productomizer_members_only_price_addcart' ) );
            $product_list_check = explode( ',', get_option( 'repupress_customize_woocommerce_product_single_product_list' ) );
            if ( !empty($product_list_check) && count( $product_list_check ) >= 1 ) {
                
                if ( in_array( $post->ID, $product_list_check ) ) {
                    
                    if ( get_option( 'repupress_customize_woocommerce_product_single_product_image' ) == 1 ) {
                        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
                        add_filter( 'body_class', array( $this, 'repupress_customize_woocommerce_productomizer_add_body_css_class' ) );
                    }
                    
                    if ( get_option( 'repupress_customize_woocommerce_product_single_product_tab' ) == 1 ) {
                        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
                    }
                    if ( get_option( 'repupress_customize_woocommerce_product_single_product_related' ) == 1 ) {
                        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
                    }
                    if ( get_option( 'repupress_customize_woocommerce_product_single_product_title' ) == 1 ) {
                        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
                    }
                    if ( get_option( 'repupress_customize_woocommerce_product_single_product_category' ) == 1 ) {
                        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
                    }
                }
            
            }
            
            if ( !empty($product_list_check) && count( $product_list_check ) == 1 && in_array( 'all', $product_list_check ) ) {
                
                if ( get_option( 'repupress_customize_woocommerce_product_single_product_image' ) == 1 ) {
                    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
                    add_filter( 'body_class', array( $this, 'repupress_customize_woocommerce_productomizer_add_body_css_class' ) );
                }
                
                if ( get_option( 'repupress_customize_woocommerce_product_single_product_tab' ) == 1 ) {
                    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
                }
                if ( get_option( 'repupress_customize_woocommerce_product_single_product_related' ) == 1 ) {
                    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
                }
                if ( get_option( 'repupress_customize_woocommerce_product_single_product_title' ) == 1 ) {
                    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
                }
                if ( get_option( 'repupress_customize_woocommerce_product_single_product_category' ) == 1 ) {
                    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
                }
            }
        
        }
    
    }
    
    /**

     * Add a CSS Class in main body

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_productomizer_add_body_css_class( $classes )
    {
        $classes[] = 'details-full-width';
        $classes[] = 'repupress_customize_woocommerce_product_body_class';
        return $classes;
    }
    
    /**

     * Add a CSS Class in Post / single page

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_productomizer_add_post_css_class( $classes )
    {
        $classes[] = 'repupress_customize_woocommerce_productom_class';
        return $classes;
    }
    
    /**

     * Replace unset or empty image on single page

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_productomizer_details_page_replace_image( $src )
    {
        $image = get_option( 'repupress_customize_woocommerce_product_detail_set_default_image' );
        if ( !empty($image) ) {
            $src = get_option( 'repupress_customize_woocommerce_product_detail_set_default_image' );
        }
        return $src;
    }
    
    /**

     * Price only for users

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_productomizer_members_only_price_addcart( $price )
    {
        global  $product, $post ;
        
        if ( is_product() ) {
            $product_list_check = explode( ',', get_option( 'repupress_customize_woocommerce_product_single_product_list' ) );
            if ( !empty($product_list_check) && count( $product_list_check ) >= 1 ) {
                if ( in_array( $post->ID, $product_list_check ) ) {
                    
                    if ( get_option( 'repupress_customize_woocommerce_product_single_product_logged_in' ) == 1 ) {
                        
                        if ( is_user_logged_in() ) {
                            return $price;
                        } else {
                            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
                            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
                            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
                            remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
                            return 'Only <a href="' . get_permalink( woocommerce_get_page_id( 'myaccount' ) ) . '">Logged In Users</a> are able to view pricing.';
                        }
                    
                    } else {
                        
                        if ( get_option( 'repupress_customize_woocommerce_product_single_product_add_to_cart' ) == 1 ) {
                            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
                            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
                        }
                        
                        if ( get_option( 'repupress_customize_woocommerce_product_single_product_prices' ) == 0 ) {
                            return $price;
                        }
                    }
                
                }
            }
            if ( !empty($product_list_check) && count( $product_list_check ) == 1 && in_array( 'all', $product_list_check ) ) {
                
                if ( get_option( 'repupress_customize_woocommerce_product_single_product_logged_in' ) == 1 ) {
                    
                    if ( is_user_logged_in() ) {
                        return $price;
                    } else {
                        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
                        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
                        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
                        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
                        return 'Only <a href="' . get_permalink( woocommerce_get_page_id( 'myaccount' ) ) . '">Logged In Users</a> are able to view pricing.';
                    }
                
                } else {
                    
                    if ( get_option( 'repupress_customize_woocommerce_product_single_product_add_to_cart' ) == 1 ) {
                        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
                        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
                    }
                    
                    if ( get_option( 'repupress_customize_woocommerce_product_single_product_prices' ) == 0 ) {
                        return $price;
                    }
                }
            
            }
        }
    
    }
    
    /**

     * Adding Emty cart Button

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_productomizer_add_empty_cart_button()
    {
        
        if ( get_option( 'repupress_customize_woocommerce_product_cart_empty_button' ) == 1 ) {
            $font_size = get_option( 'repupress_customize_woocommerce_product_cart_button_font_size' );
            $color = get_option( 'repupress_customize_woocommerce_product_cart_button_font_color' );
            $weight = get_option( 'repupress_customize_woocommerce_product_cart_button_font_weight' );
            $bg = get_option( 'repupress_customize_woocommerce_product_cart_button_bg_color' );
            $style = ( !empty($font_size) ? 'font-size: ' . $font_size . 'px !important;' : '' );
            $style .= ( !empty($color) ? 'color: ' . $color . ' !important;' : '' );
            $style .= ( !empty($weight) ? 'font-weight: ' . $weight . ' !important;' : '' );
            echo  '<div><form action="" method="post"><input type="submit" style="' . $style . '" class="gradient_' . $bg . ' " name="empty_cart" value="Empty Cart"></form></div>' ;
        }
    
    }
    
    /**

     * Checkout Page Filter

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_productomizer_checkout_page( $fields )
    {
        foreach ( $fields as $parent => $value ) {
            foreach ( $value as $child => $data ) {
                $get_opt_disable = 'repupress_customize_woocommerce_product_checkout_' . $child;
                $check_opt_disable = get_option( $get_opt_disable );
                $get_opt = 'repupress_customize_woocommerce_product_checkout_opt_' . $child;
                $check_opt = get_option( $get_opt );
                
                if ( $check_opt_disable == 1 ) {
                    unset( $fields[$parent][$child] );
                } else {
                    
                    if ( $check_opt == 1 ) {
                        $fields[$parent][$child]['required'] = 0;
                        if ( isset( $fields[$parent][$child]['class'] ) ) {
                            array_push( $fields[$parent][$child]['class'], 'repupress_customize_woocommerce_product-checkout-optional' );
                        }
                    }
                
                }
                
                if ( isset( $fields[$parent][$child]['class'] ) ) {
                    array_push( $fields[$parent][$child]['class'], 'repupress_customize_woocommerce_product-checkout-field' );
                }
            }
        }
        $upd = get_option( 'repupress_customize_woocommerce_product_checkout_fields' );
        if ( !empty($upd) ) {
            foreach ( $upd as $key => $value ) {
                $fields[$value['section']][$value['name']]['placeholder'] = $value['placeholder'];
                $fields[$value['section']][$value['name']]['label'] = $value['label'];
                $fields[$value['section']][$value['name']]['required'] = $value['required'];
                $fields[$value['section']][$value['name']]['type'] = $value['type'];
            }
        }
        return $fields;
    }
    
    /**

     * Display Custom Checkout Fields on order details page

     * @package WooCommerce - Customize Shop

     * @since 1.1.1

     */
    public function repupress_customize_woocommerce_productomizer_order_details_page( $order_id )
    {
        $show = 0;
        $html = '<h2>' . __( 'Additional Info', 'repupresscustomwoocommerceproduct' ) . '</h2>

		    		<table class="shop_table shop_table_responsive additional_info">

		        	<tbody>';
        $upd = get_option( 'repupress_customize_woocommerce_product_checkout_fields' );
        if ( !empty($upd) ) {
            foreach ( $upd as $key => $value ) {
                $extra_field = get_post_meta( $order_id, $value['name'], true );
                
                if ( !empty($extra_field) ) {
                    $html .= '<tr>

							                <th>' . $value['label'] . '</th>

							                <td>' . $extra_field . '</td>

							            </tr>';
                    $show = 1;
                }
            
            }
        }
        $html .= '</tbody></table>';
        $check = get_option( 'repupress_customize_woocommerce_product_fields_show_order_details' );
        if ( $show == 1 && $check == 1 ) {
            echo  $html ;
        }
    }
    
    /**

     * Save Custom Checkout Fields

     * @package WooCommerce - Customize Shop

     * @since 1.1.1

     */
    public function repupress_customize_woocommerce_productomizer_save_checkout_fields( $order_id, $posted )
    {
        $upd = get_option( 'repupress_customize_woocommerce_product_checkout_fields' );
        if ( !empty($upd) ) {
            foreach ( $upd as $key => $value ) {
                if ( !empty($posted[$value['name']]) ) {
                    update_post_meta( $order_id, $value['name'], sanitize_text_field( $posted[$value['name']] ) );
                }
            }
        }
    }
    
    /**

     * Display Custom Checkout Fields on Edit order

     * @package WooCommerce - Customize Shop

     * @since 1.1.1

     */
    public function repupress_customize_woocommerce_productomizer_display_fields_admin_order( $order )
    {
        $show = 0;
        $html = '';
        $upd = get_option( 'repupress_customize_woocommerce_product_checkout_fields' );
        if ( !empty($upd) ) {
            foreach ( $upd as $key => $value ) {
                $extra_field = get_post_meta( $order->id, $value['name'], true );
                
                if ( !empty($extra_field) ) {
                    $html .= '<p><strong>' . $value['label'] . ':</strong></br>' . $extra_field . '</p>';
                    $show = 1;
                }
            
            }
        }
        $check = get_option( 'repupress_customize_woocommerce_product_fields_show_order_edit' );
        if ( $show == 1 && $check == 1 ) {
            echo  $html ;
        }
    }
    
    /**

     * Add Custom Checkout Fields on Mail

     * @package WooCommerce - Customize Shop

     * @since 1.1.1

     */
    public function repupress_customize_woocommerce_productomizer_custom_fields_email( $fields, $sent_to_admin, $order )
    {
        $upd = get_option( 'repupress_customize_woocommerce_product_checkout_fields' );
        
        if ( !empty($upd) ) {
            $i = 0;
            foreach ( $upd as $key => $value ) {
                $extra_field = get_post_meta( $order->id, $value['name'], true );
                $check = get_option( 'repupress_customize_woocommerce_product_fields_show_order_email' );
                
                if ( !empty($extra_field) && $check == 1 ) {
                    $main = 'section' . $i;
                    $fields[$main] = array(
                        'label' => $value['label'],
                        'value' => $extra_field,
                    );
                    //	$fields[$value['section']][$value['name']]['label'] = $value['label'];
                    //$fields[$value['section']][$value['name']]['value'] = $extra_field;
                    $i++;
                }
            
            }
        }
        
        return $fields;
    }
    
    /**

     * Call INIT for all settings

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function repupress_customize_woocommerce_productomizer_init()
    {
        global  $product, $post, $woocommerce ;
        //empty Cart
        if ( isset( $_POST['empty_cart'] ) ) {
            $woocommerce->cart->empty_cart();
        }
        add_filter( 'post_class', array( $this, 'repupress_customize_woocommerce_productomizer_add_post_css_class' ) );
        add_filter( 'woocommerce_placeholder_img_src', array( $this, 'repupress_customize_woocommerce_productomizer_replace_unset_images' ) );
        add_filter( 'woocommerce_checkout_fields', array( $this, 'repupress_customize_woocommerce_productomizer_checkout_page' ) );
        add_filter(
            'woocommerce_email_order_meta_fields',
            array( $this, 'repupress_customize_woocommerce_productomizer_custom_fields_email' ),
            10,
            3
        );
    }
    
    /**

     * Adding Hooks

     * @package WooCommerce - Customize Shop

     * @since 1.0.0

     */
    public function add_hooks()
    {
        //add admin menu pages
        add_action( 'admin_menu', array( $this, 'repupress_customize_woocommerce_product_admin_menu_pages' ) );
        add_action( 'init', array( $this, 'repupress_customize_woocommerce_productomizer_init' ) );
        add_action( 'template_redirect', array( $this, 'repupress_customize_woocommerce_productomizer_single_page' ) );
        add_action( 'woocommerce_before_shop_loop_item', array( $this, 'repupress_customize_woocommerce_productomizer_before_imageless_product' ) );
        add_action( 'woocommerce_after_shop_loop_item', array( $this, 'repupress_customize_woocommerce_productomizer_after_imageless_product' ) );
        //Cart Page
        add_action( 'woocommerce_after_cart', array( $this, 'repupress_customize_woocommerce_productomizer_add_empty_cart_button' ) );
        //Save Cusrtom fields
        add_action(
            'woocommerce_checkout_update_order_meta',
            array( $this, 'repupress_customize_woocommerce_productomizer_save_checkout_fields' ),
            10,
            2
        );
        //Thankyou & Order Details page
        add_action( 'woocommerce_thankyou', array( $this, 'repupress_customize_woocommerce_productomizer_order_details_page' ), 20 );
        add_action( 'woocommerce_view_order', array( $this, 'repupress_customize_woocommerce_productomizer_order_details_page' ), 20 );
        add_action(
            'woocommerce_admin_order_data_after_billing_address',
            array( $this, 'repupress_customize_woocommerce_productomizer_display_fields_admin_order' ),
            10,
            1
        );
    }

}