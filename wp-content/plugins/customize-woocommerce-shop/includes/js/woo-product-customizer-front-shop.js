jQuery(document).ready(function($) {
	// shop page button settngs
	var cust_class = "gradient_"+wpFrontProductCust.product_cust_shop_button_bg_color;
	$(".products .add_to_cart_button").addClass(cust_class);					
	$('.products .add_to_cart_button').attr('style','color: '+wpFrontProductCust.product_cust_shop_button_font_color+ ' !important');
	$(".products .product_type_simple").addClass(cust_class);						
	$('.products .product_type_simple').attr('style','color: '+wpFrontProductCust.product_cust_shop_button_font_color+ ' !important');
	
	$(".repupress_customize_woocommerce_productom_class .add_to_cart_button").addClass(cust_class);					
	$('.repupress_customize_woocommerce_productom_class .add_to_cart_button').attr('style','color: '+wpFrontProductCust.product_cust_shop_button_font_color+ ' !important');
	$(".repupress_customize_woocommerce_productom_class .product_type_simple").addClass(cust_class);						
	$('.repupress_customize_woocommerce_productom_class .product_type_simple').attr('style','color: '+wpFrontProductCust.product_cust_shop_button_font_color+ ' !important');
	
	//shop page image settings
	$('.products .wp-post-image').attr('style','border-color: '+wpFrontProductCust.product_cust_shop_image_border_color+ ' !important; border-style: '+wpFrontProductCust.product_cust_shop_image_border_style+ ' !important; border-width: '+wpFrontProductCust.product_cust_shop_image_border_width+ 'px !important;');
	$('.repupress_customize_woocommerce_productom_class .wp-post-image').attr('style','border-color: '+wpFrontProductCust.product_cust_shop_image_border_color+ ' !important; border-style: '+wpFrontProductCust.product_cust_shop_image_border_style+ ' !important; border-width: '+wpFrontProductCust.product_cust_shop_image_border_width+ 'px !important;');
	
	// shop page price settigs
	$('.products .price .amount').attr('style','color: '+wpFrontProductCust.product_cust_shop_price_font_color+ ' !important; font-size: '+wpFrontProductCust.product_cust_shop_price_font_size+ 'px !important; font-weight: '+wpFrontProductCust.product_cust_shop_price_font_weight+ ' !important; ');
	$('.repupress_customize_woocommerce_productom_class .amount').attr('style','color: '+wpFrontProductCust.product_cust_shop_price_font_color+ ' !important; font-size: '+wpFrontProductCust.product_cust_shop_price_font_size+ 'px !important; font-weight: '+wpFrontProductCust.product_cust_shop_price_font_weight+ ' !important; ');
	
	// shop page title settings
	$('.products h3').attr('style','color: '+wpFrontProductCust.product_cust_shop_title_font_color+ ' !important; font-size: '+wpFrontProductCust.product_cust_shop_title_font_size+ 'px !important; font-weight: '+wpFrontProductCust.product_cust_shop_title_font_weight+ ' !important;');
	$('.repupress_customize_woocommerce_productom_class .entry-title,.repupress_customize_woocommerce_productom_class h1,.repupress_customize_woocommerce_productom_class h2,.repupress_customize_woocommerce_productom_class h3,.repupress_customize_woocommerce_productom_class h4,.repupress_customize_woocommerce_productom_class h5').attr('style','color: '+wpFrontProductCust.product_cust_shop_title_font_color+ ' !important; font-size: '+wpFrontProductCust.product_cust_shop_title_font_size+ 'px !important; font-weight: '+wpFrontProductCust.product_cust_shop_title_font_weight+ ' !important;');
	$('.repupress_customize_woocommerce_productom_class .entry-title a,.repupress_customize_woocommerce_productom_class h1 a,.repupress_customize_woocommerce_productom_class h2 a,.repupress_customize_woocommerce_productom_class h3 a,.repupress_customize_woocommerce_productom_class h4 a,.repupress_customize_woocommerce_productom_class h5 a').attr('style','color: '+wpFrontProductCust.product_cust_shop_title_font_color+ ' !important; font-size: '+wpFrontProductCust.product_cust_shop_title_font_size+ 'px !important; font-weight: '+wpFrontProductCust.product_cust_shop_title_font_weight+ ' !important;');
});