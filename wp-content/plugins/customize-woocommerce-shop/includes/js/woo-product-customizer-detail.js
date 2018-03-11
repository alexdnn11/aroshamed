jQuery(document).ready(function($) {
	var current_border_color = $("#detailimagecolor").val();
	var current_border_style = $("#repupress_customize_woocommerce_product_detail_image_border_style").val();
	var current_border_width = $("#repupress_customize_woocommerce_product_detail_image_border_width").val();
	
	$("#previewborder").css("border-color",current_border_color);
	$("#previewborder").css("border-style",current_border_style);
	$("#previewborder").css("border-width",current_border_width);
								
    $('#detailtitlecolorpicker').hide();
    $('#detailtitlecolorpicker').farbtastic('#detailtitlecolor');

    $('#detailtitlecolor').click(function() { 
        $('#detailtitlecolorpicker').fadeIn();
    });
	$('#detaildescriptioncolorpicker').hide();
    $('#detaildescriptioncolorpicker').farbtastic('#detaildescriptioncolor');

    $('#detaildescriptioncolor').click(function() {
        $('#detaildescriptioncolorpicker').fadeIn();
    });
	
	$('#detailcategorycolorpicker').hide();
    $('#detailcategorycolorpicker').farbtastic('#detailcategorycolor');

    $('#detailcategorycolor').click(function() {
        $('#detailcategorycolorpicker').fadeIn();
    });
	
	/*$('#detailreviewstarcolorpicker').hide();
    $('#detailreviewstarcolorpicker').farbtastic('#detailreviewstarcolor');

    $('#detailreviewstarcolor').click(function() {
        $('#detailreviewstarcolorpicker').fadeIn();
    });*/
	
	$('#detailpricecolorpicker').hide();
    $('#detailpricecolorpicker').farbtastic('#detailpricecolor');

    $('#detailpricecolor').click(function() {
        $('#detailpricecolorpicker').fadeIn();
    });
	$('#detailbtncolorpicker').hide();
    $('#detailbtncolorpicker').farbtastic('#detailbtncolor');

    $('#detailbtncolor').click(function() {
        $('#detailbtncolorpicker').fadeIn();
    });
	$('#detailimagecolorpicker').hide();
    $('#detailimagecolorpicker').farbtastic('#detailimagecolor');
	
    $('#detailimagecolor').click(function() {
        $('#detailimagecolorpicker').fadeIn();
		var current_val = $(this).val();
		$("#previewborder").css("border-color",current_val);
    });
	
	$("#repupress_customize_woocommerce_product_detail_image_border_style").change(function(){
		var current_val = $(this).val();
		$("#previewborder").css("border-style",current_val);
	});
	
	$("#repupress_customize_woocommerce_product_detail_image_border_width").change(function(){
		var current_val = $(this).val();
		$("#previewborder").css("border-width",current_val);
	});
    $(document).mousedown(function() {
        $('#detailtitlecolorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
		 $('#detaildescriptioncolorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
		 /*$('#detailreviewstarcolorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });*/
		 $('#detailcategorycolorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
		 $('#detailpricecolorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
		 $('#detailbtncolorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
		  $('#detailimagecolorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
			var current_val = $("#detailimagecolor").val();
			$("#previewborder").css("border-color",current_val);
        });
    });
	var btn_bg_color = $("#repupress_customize_woocommerce_product_detail_button_bg_color").val();
	if(btn_bg_color == ""){
		$("#repupress_customize_woocommerce_product_detail_btn_result").hide();
	}
	$("#repupress_customize_woocommerce_product_detail_btn_css_gradients div").click(function() {
		if(btn_bg_color == ""){
			$("#repupress_customize_woocommerce_product_detail_btn_result").show();
		}
	   var myClass = $(this).attr("class");
	   var myId = $(this).attr("id");
	   $("#repupress_customize_woocommerce_product_detail_btn_result a").attr('class', '');
	   $("#repupress_customize_woocommerce_product_detail_btn_result a").addClass('button_example');
	   $("#repupress_customize_woocommerce_product_detail_btn_result a").addClass(myClass);
	   $("#repupress_customize_woocommerce_product_detail_button_bg_color").val(myId);
	   
	});
});	