jQuery(document).ready(function($) {
			
	var current_border_color = $("#imagecolor").val();
	var current_border_style = $("#repupress_customize_woocommerce_product_shop_image_border_style").val();
	var current_border_width = $("#repupress_customize_woocommerce_product_shop_image_border_width").val();
	
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
	
	$("#previewborder").css("border-color",current_border_color);
	$("#previewborder").css("border-style",current_border_style);
	$("#previewborder").css("border-width",current_border_width);
								
    $('#titlecolorpicker').hide();
    $('#titlecolorpicker').farbtastic('#titlecolor');

    $('#titlecolor').click(function() {
        $('#titlecolorpicker').fadeIn();
    });
	
	$('#pricecolorpicker').hide();
    $('#pricecolorpicker').farbtastic('#pricecolor');

    $('#pricecolor').click(function() {
        $('#pricecolorpicker').fadeIn();
    });
	$('#btncolorpicker').hide();
    $('#btncolorpicker').farbtastic('#btncolor');

    $('#btncolor').click(function() {
        $('#btncolorpicker').fadeIn();
    });
	
	$('#imagecolorpicker').hide();
    $('#imagecolorpicker').farbtastic('#imagecolor');
	
    $('#imagecolor').click(function() {
        $('#imagecolorpicker').fadeIn();
		var current_val = $(this).val();
		$("#previewborder").css("border-color",current_val);
    });
	
	$("#repupress_customize_woocommerce_product_shop_image_border_style").change(function(){
		var current_val = $(this).val();
		$("#previewborder").css("border-style",current_val);
	});
	
	$("#repupress_customize_woocommerce_product_shop_image_border_width").change(function(){
		var current_val = $(this).val();
		$("#previewborder").css("border-width",current_val);
	});
    $(document).mousedown(function() {
        $('#titlecolorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
		
		 $('#pricecolorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
		 $('#btncolorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
		  $('#imagecolorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
			var current_val = $("#imagecolor").val();
			$("#previewborder").css("border-color",current_val);
        });
    });
	
});