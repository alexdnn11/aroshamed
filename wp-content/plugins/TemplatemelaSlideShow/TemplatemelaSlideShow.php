<?php 
/*
  Plugin Name: Templatemela SlideShow
  Plugin URI: http://www.templatemela.com
  Description: Templatemela Default Homepage Slide Show for templatemela wordpress themes.
  Version: 1.0
  Author: Templatemela
  Author URI: http://www.templatemela.com
 */
function slider_theme_custom_posts(){
	//slider
	$labels = array(
	  'name' => __('Slider', 'Slider','templatemela'),
	  'singular_name' => __('Slider', 'slider','templatemela'),
	  'add_new' => __('Add New', 'Slider','templatemela'),
	  'add_new_item' => __('Add New Slider','templatemela'),
	  'edit_item' => __('Edit Slider','templatemela'),
	  'new_item' => __('New Slider','templatemela'),
	  'view_item' => __('View Slider','templatemela'),
	  'search_items' => __('Search Slider','templatemela'),
	  'not_found' =>  __('No Slider found','templatemela'),
	  'not_found_in_trash' => __('No Slider found in Trash','templatemela'), 
	  'parent_item_colon' => ''
	);
	$args = array(
	  'labels' => $labels,
	  'public' => true,
	  'publicly_queryable' => true,
	  'show_ui' => true, 
	  'query_var' => true, 
	  'capability_type' => 'post', 
	  'menu_position' => null,
	  'menu_icon' => 'dashicons-slides',	 
	  'rewrite' => array('slug'=>'slider','with_front'=>''),
	  'supports' => array('title','editor','author','thumbnail','comments')
	); 
	register_post_type('slider',$args);	
}
add_filter('init', 'slider_theme_custom_posts' );
add_action( 'admin_init', 'remove_slidermetabox_option' );
function remove_slidermetabox_option() {
   	remove_meta_box( 'commentsdiv', 'slider', 'normal' );
	remove_meta_box( 'authordiv', 'slider', 'normal' );
	remove_meta_box( 'commentstatusdiv', 'slider', 'normal' );
}
add_action( 'add_meta_boxes', 'slider_add_custom_fields' );
add_action( 'save_post', 'slider_save_custom_fields' );
function slider_add_custom_fields() {
    add_meta_box( 
        'slider_options',
        'Slider Details',
        'slider_inner_custom_field',
        'slider' 
    );
}
function slider_inner_custom_field( $post ) {
// Use nonce for verification
wp_nonce_field( plugin_basename( __FILE__ ), '_templatemela' );	
get_post_meta($post->ID, 'slider_background_image', TRUE) ? $slider_background_image = get_post_meta($post->ID, 'slider_background_image', TRUE) : $slider_background_image = '';	
get_post_meta($post->ID, 'slider_title', TRUE) ? $slider_title = get_post_meta($post->ID, 'slider_title', TRUE) : $slider_title = '';
get_post_meta($post->ID, 'slider_title2', TRUE) ? $slider_title2 = get_post_meta($post->ID, 'slider_title2', TRUE) : $slider_title2 = '';
get_post_meta($post->ID, 'slider_title3', TRUE) ? $slider_title3 = get_post_meta($post->ID, 'slider_title3', TRUE) : $slider_title3 = '';
get_post_meta($post->ID, 'slider_description', TRUE) ? $slider_description = get_post_meta($post->ID, 'slider_description', TRUE) : $slider_description = '';
get_post_meta($post->ID, 'slider_link_text', TRUE) ? $slider_link_text = get_post_meta($post->ID, 'slider_link_text', TRUE) : $slider_link_text = '';
get_post_meta($post->ID, 'slider_link_url', TRUE) ? $slider_link_url = get_post_meta($post->ID, 'slider_link_url', TRUE) : $slider_link_url = '';
get_post_meta($post->ID, 'slider_link', TRUE) ? $slider_link = get_post_meta($post->ID, 'slider_link', TRUE) : $slider_link = '';   ?>
<table class="form-table">
	</tbody>
		<tr>
			<th>
				<label><?php _e( 'Image', 'templatemela' )?>:</label>
			</th>
			<td><input type="text" id="slider_background_image" name="slider_background_image" value="<?php echo esc_attr($slider_background_image); ?>" size="25" />
				<input id="upload_banner" class="button-primary" type="button" value="Upload Image" />
				<div>Upload slider background image here.</div>
				
				<div class="row20">
					<?php  if($slider_background_image != '')  { ?>
						<img src="<?php echo esc_url($slider_background_image); ?>" alt="" id="slider_imagedisplay" class="sliderpreview">
						<a id="slider_remove_link" href="javascript:removeImage();">
						<img src="<?php echo esc_url(get_template_directory_uri());?>/images/megnor/admin/remove.png"></a><br/></td>
					<?php }  else { ?>
						<img src="<?php echo esc_url($slider_background_image); ?>" alt="" id="slider_imagedisplay" class="sliderpreview">
					<?php } ?>
				</div>
			</td>
		</tr>		
		<tr>
			<th>
				<label><?php _e('Banner Title', 'templatemela'); ?>:</label>
			</th>
			<td>
				<input type="text" id="slider_title" name="slider_title" value="<?php echo esc_attr($slider_title); ?>" class="regular-text"/>
				<div>Write small title text here.</div>
			</td>
		</tr>
		
		<tr>
			<th>
				<label><?php _e('Banner Title2', 'templatemela'); ?>:</label>
			</th>
			<td>
				<input type="text" id="slider_title" name="slider_title2" value="<?php echo esc_attr($slider_title2); ?>" class="regular-text"/>
				<div>Write small title text here.</div>
			</td>
		</tr>
		<tr>
			<th>
				<label><?php _e('Banner Title3', 'templatemela'); ?>:</label>
			</th>
			<td>
				<input type="text" id="slider_title" name="slider_title3" value="<?php echo esc_attr($slider_title3); ?>" class="regular-text"/>
				<div>Write small title text here.</div>
			</td>
		</tr>
		
		<tr>
			<th>
				<label><?php _e('Description', 'templatemela'); ?>:</label>
			</th>
			<td>
				<textarea name="slider_description" id="slider_description" cols="55" rows="3"><?php echo esc_attr($slider_description); ?></textarea>
				<div>Write description here.</div>
			</td>
		</tr>
		
		<tr>
			<th>
				<label><?php _e('Link Text', 'templatemela'); ?>:</label>
			</th>
			<td>
				<input type="text" id="slider_link_text" name="slider_link_text" value="<?php echo esc_attr($slider_link_text); ?>" class="regular-text"/>
				<div>Write link text here.</div>
			</td>
		</tr>
		
		<tr>
			<th>
				<label><?php _e('Link URL', 'templatemela'); ?>:</label>
			</th>
			<td>
				<input type="text" id="slider_link_url" name="slider_link_url" value="<?php echo esc_attr($slider_link_url); ?>" class="regular-text"/>
				<div>Write link URL here.</div>
			</td>
		</tr>
		<tr>
			<th>
				<label><?php _e('Banner URL', 'templatemela'); ?>:</label>
			</th>
			<td>
				<input type="text" id="slider_link" name="slider_link" value="<?php echo esc_attr($slider_link); ?>" class="regular-text"/>
				<div>Write Banner Link URL here.</div>
			</td>
		</tr>
	</tbody>
</table>
<script>
function removeImage()
{
	document.getElementById("slider_background_image").value="";
	document.getElementById("slider_imagedisplay").src="";
	document.getElementById("slider_remove_link").innerHTML="";
}
</script>
<?php }
function slider_save_custom_fields( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;
  if ( !current_user_can('edit_post', $post_id ) )
        return;
  $mydata = array();
  
  foreach($_POST as $key => $data) {
    if($key == '_templatemela')
      continue;
	  
    if(preg_match('/^slider/i', $key)) {
      $mydata[$key] = $data;
	  update_post_meta($post_id, $key, $data);
    }
  }
 
  return $mydata;
} 
?>