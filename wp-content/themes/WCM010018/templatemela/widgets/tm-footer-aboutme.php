<?php
/**
 * TemplateMela
 * @copyright  Copyright (c) 2010 TemplateMela. (http://www.templatemela.com)
 * @license    http://www.templatemela.com/license/
 */
?><?php  // Reference:  http://codex.wordpress.org/Widgets_API
class FooterAboutWidget extends WP_Widget
{
    function FooterAboutWidget(){
		$widget_settings = array('description' => 'Footer About Me Widget', 'classname' => 'widgets-about');
		parent::__construct(false,$name='TM - Footer About Me Widget',$widget_settings);
    }
    function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? 'About Me' : $instance['title']);
		$is_template_path = isset($instance['is_template_path']) ? $instance['is_template_path'] : false;
        $window_target = isset($instance['window_target']) ? $instance['window_target'] : false;
		$imageSrc = empty($instance['imageSrc']) ? '' : $instance['imageSrc'];
		$description = empty($instance['description']) ? '' : $instance['description']; 
		$link_text = empty($instance['link_text']) ? '' : $instance['link_text']; 
		$linkURL = empty($instance['linkURL']) ? '' : $instance['linkURL'];
		if($is_template_path == 1):
			$imageSrc= get_template_directory_uri() . '/images/' . $imageSrc; 
		endif;		
		echo $before_widget;
		 ?>
		 <div class="home-about-me hb-animate-element left-to-right"> 		 
				<div class="tm-about-text">
					 <?php 
					/*echo $before_title ;			
						if($title)
						echo $title;
					echo $after_title;*/
				 ?>
				 	 <a href="<?php if($linkURL == ""): echo esc_url(home_url( '#' )); else:?><?php echo esc_url($linkURL); endif;?>" 
						<?php if($window_target == true) echo 'target="_blank"'; ?>> 
						<img src="<?php echo esc_url($imageSrc); ?>" alt="" class="vv" />
					 </a> 
					<div class="tm-about-description">					
					<?php echo wpautop($description); ?>
					</div>
				</div>
		</div>
		<?php
		echo $after_widget;					
	}
    function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['window_target'] = false;
		$instance['is_template_path'] = false;
		if (isset($new_instance['window_target'])) $instance['window_target'] = true;
		if (isset($new_instance['is_template_path'])) $instance['is_template_path'] = true;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['imageSrc'] = strip_tags($new_instance['imageSrc']);
		$instance['description'] = strip_tags($new_instance['description']);
		$instance['link_text'] = strip_tags($new_instance['link_text']);
		$instance['linkURL'] = strip_tags($new_instance['linkURL']);
		return $instance;
	}
    function form($instance){
		$instance = wp_parse_args( (array) $instance, array(
		'title'=>'About Us', 
		'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
		'imageSrc'=>'logo.png', 
		'link_text'=>'',
		'is_template_path'=>1,
		'linkURL'=>'#'
		) );
		$title = esc_attr($instance['title']);
		$imageSrc = esc_attr($instance['imageSrc']);
		$description = esc_attr($instance['description']);
		$link_text = esc_attr($instance['link_text']);
		$linkURL = esc_attr($instance['linkURL']);
		?>
		<?php /*?><p><label for="<?php echo $this->get_field_id('title');?>">Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo $title;?>" />
		</p><?php */?>
		<p><label for="<?php echo esc_attr($this->get_field_id('imageSrc'));?>">Image URL1:<br /></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('imageSrc'));?>" name="<?php echo esc_attr($this->get_field_name('imageSrc'));?>" type="text" value="<?php echo esc_attr($imageSrc);?>" /><br />
			<input class="checkbox" type="checkbox" <?php checked($instance['is_template_path'], true) ?> id="<?php echo esc_attr($this->get_field_id('is_template_path')); ?>" name="<?php echo esc_attr($this->get_field_name('is_template_path')); ?>" /><label for="<?php echo esc_attr($this->get_field_id('is_template_path')); ?>">Use Template Path for Image</label>
		</p>
		<p>
  <label for="<?php echo esc_attr($this->get_field_id('linkURL'));?>">Link URL:<br />
  </label>
  <input class="widefat" id="<?php echo esc_attr($this->get_field_id('linkURL'));?>" name="<?php echo esc_attr($this->get_field_name('linkURL'));?>" type="text" value="<?php echo esc_attr($linkURL);?>" />
  <label>(e.g. http://www.yoursite.com/...)</label>
  <br />
  <input class="checkbox" type="checkbox" <?php checked($instance['window_target'], true) ?> id="<?php echo esc_attr($this->get_field_id('window_target')); ?>" name="<?php echo esc_attr($this->get_field_name('window_target')); ?>" />
  <label for="<?php echo esc_attr($this->get_field_id('window_target')); ?>">Open Link In New Window</label>
</p>
		<p><label for="<?php echo esc_attr($this->get_field_id('description'));?>">Description:</label>
			<textarea cols="18" rows="3" class="widefat" id="<?php echo esc_attr($this->get_field_id('description'));?>" name="<?php echo esc_attr($this->get_field_name('description'));?>" ><?php echo esc_attr($description);?></textarea>
		</p>
		<?php
	}
}
add_action('widgets_init', create_function('', 'return register_widget("FooterAboutWidget");'));
// end AboutWidget
?>