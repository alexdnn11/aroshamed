// JavaScript Document
jQuery(document).ready(function($) {
	// detail page button settngs
	var cust_class = "gradient_"+wpFrontProductCustDetail.product_cust_detail_button_bg_color;
	$(".product .single_add_to_cart_button").addClass(cust_class);					
	$('.product .single_add_to_cart_button').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_button_font_color+ ' !important');
	$(".product .products .add_to_cart_button").addClass(cust_class);					
	$('.product .products .add_to_cart_button').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_button_font_color+ ' !important');
	
	$(".repupress_customize_woocommerce_productom_class button").addClass(cust_class);					
	$('.repupress_customize_woocommerce_productom_class button').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_button_font_color+ ' !important');
	$(".repupress_customize_woocommerce_productom_class .products button").addClass(cust_class);					
	$('.repupress_customize_woocommerce_productom_class .products button').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_button_font_color+ ' !important');
	
	//detail page image settings
	$('.product .wp-post-image').attr('style','border-color: '+wpFrontProductCustDetail.product_cust_detail_image_border_color+ ' !important; border-style: '+wpFrontProductCustDetail.product_cust_detail_image_border_style+ ' !important; border-width: '+wpFrontProductCustDetail.product_cust_detail_image_border_width+ 'px !important;');
	$('.repupress_customize_woocommerce_productom_class .wp-post-image').attr('style','border-color: '+wpFrontProductCustDetail.product_cust_detail_image_border_color+ ' !important; border-style: '+wpFrontProductCustDetail.product_cust_detail_image_border_style+ ' !important; border-width: '+wpFrontProductCustDetail.product_cust_detail_image_border_width+ 'px !important;');
	
	// detail page price settigs
	$('.product .price .amount').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_price_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.product_cust_detail_price_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.product_cust_detail_price_font_weight+ ' !important; ');
	$('.repupress_customize_woocommerce_productom_class .amount').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_price_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.product_cust_detail_price_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.product_cust_detail_price_font_weight+ ' !important; ');
	
	// detail page title settings
	$('.product .product_title').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_title_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.product_cust_detail_title_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.product_cust_detail_title_font_weight+ ' !important;');
	$('.product .products h3').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_title_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.product_cust_detail_title_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.product_cust_detail_title_font_weight+ ' !important;');
	$('.repupress_customize_woocommerce_productom_class .product_title').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_title_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.product_cust_detail_title_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.product_cust_detail_title_font_weight+ ' !important;');
	$('.repupress_customize_woocommerce_productom_class .products h1,.repupress_customize_woocommerce_productom_class .products h2,.repupress_customize_woocommerce_productom_class .products h3,.repupress_customize_woocommerce_productom_class .products h4,.repupress_customize_woocommerce_productom_class .products h5').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_title_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.product_cust_detail_title_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.product_cust_detail_title_font_weight+ ' !important;');
	
	// detail page category settings
	$('.product .product_meta span').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_cat_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.product_cust_detail_cat_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.product_cust_detail_cat_font_weight+ ' !important;');
	$('.product .product_meta .posted_in a').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_cat_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.product_cust_detail_cat_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.product_cust_detail_cat_font_weight+ ' !important;');
	$('.repupress_customize_woocommerce_productom_class .product_meta span').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_cat_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.product_cust_detail_cat_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.product_cust_detail_cat_font_weight+ ' !important;');
	$('.repupress_customize_woocommerce_productom_class .product_meta .posted_in a').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_cat_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.product_cust_detail_cat_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.product_cust_detail_cat_font_weight+ ' !important;');
	
	// detail page description settings
	$('.product #tab-description').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_desc_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.product_cust_detail_desc_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.product_cust_detail_desc_font_weight+ ' !important;');
	$('.product div[itemprop="description"] p').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_desc_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.product_cust_detail_desc_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.product_cust_detail_desc_font_weight+ ' !important;');
	$('.repupress_customize_woocommerce_productom_class #tab-description').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_desc_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.product_cust_detail_desc_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.product_cust_detail_desc_font_weight+ ' !important;');
	$('.repupress_customize_woocommerce_productom_class div[itemprop="description"] p').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_desc_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.product_cust_detail_desc_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.product_cust_detail_desc_font_weight+ ' !important;');
	//$('.product .stars a:hover').attr('style','color: '+wpFrontProductCustDetail.product_cust_detail_review_star_color+ ' !important;');
	

	//Checkout Field Style
	$('.repupress_customize_woocommerce_product-checkout-field').attr('style','color: '+wpFrontProductCustDetail.checkout_field_font_color+ ' !important; font-size: '+wpFrontProductCustDetail.checkout_field_font_size+ 'px !important; font-weight: '+wpFrontProductCustDetail.checkout_field_font_weight+ ' !important;');
});