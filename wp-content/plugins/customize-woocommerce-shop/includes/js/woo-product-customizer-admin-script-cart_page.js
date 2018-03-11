jQuery(document).ready(function($) {
	
    var btn_bg_color = $("#repupress_customize_woocommerce_product_shop_button_bg_color").val();
    
    if(btn_bg_color == ""){
        $("#repupress_customize_woocommerce_product_shop_btn_result").hide();
    }
    $("#repupress_customize_woocommerce_product_shop_btn_css_gradients div").click(function() {
        if(btn_bg_color == ""){
            $("#repupress_customize_woocommerce_product_shop_btn_result").show();
        }
       var myClass = $(this).attr("class");
       var myId = $(this).attr("id");
       $("#repupress_customize_woocommerce_product_shop_btn_result a").attr('class', '');
       $("#repupress_customize_woocommerce_product_shop_btn_result a").addClass('button_example');
       $("#repupress_customize_woocommerce_product_shop_btn_result a").addClass(myClass);
       $("#repupress_customize_woocommerce_product_shop_button_bg_color").val(myId);
       
    });
    
	$('#carttitlecolorpicker').hide();
    $('#carttitlecolorpicker').farbtastic('#carttitlecolor');

    $('#carttitlecolor').click(function() {
        $('#carttitlecolorpicker').fadeIn();
    });

    $(document).mousedown(function() {
    
		$('#carttitlecolorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
	
    });
	
});