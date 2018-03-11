<?php
if(get_option('tmoption_slider_navigation') == 'yes')
	$tmoption_slider_navigation = 'true';
else 
	$tmoption_slider_navigation = 'false';
if(get_option('tmoption_slider_pagination') == 'yes')
	$tmoption_slider_pagination = 'true';
else 
	$tmoption_slider_pagination = 'false';
if(get_option('tmoption_slider_autoplay') == 'yes')
	$tmoption_slider_autoplay = 'true';
else 
	$tmoption_slider_autoplay = 'false'; 
$tmoption_slide_animation_type = get_option('tmoption_slide_animation_type'); 
$tmoption_slideshow_speed = get_option('tmoption_slideshow_speed');
$tmoption_animation_duration = get_option('tmoption_animation_duration'); ?>
<script>
jQuery(window).load(function(){
   "use strict";
  jQuery('.flexslider').flexslider({
	animation: "<?php echo esc_attr($tmoption_slide_animation_type); ?>",
	slideshow: <?php echo esc_attr($tmoption_slider_autoplay); ?>,
	slideshowSpeed: <?php echo esc_attr($tmoption_slideshow_speed); ?>,
	animationDuration: <?php echo esc_attr($tmoption_animation_duration); ?>,
	directionNav: <?php echo esc_attr($tmoption_slider_navigation); ?>,
	controlNav: <?php echo esc_attr($tmoption_slider_pagination); ?>,
	keyboardNav: true,
	mousewheel: false
  });
});
</script>
<?php wp_reset_query(); // Reset
$paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$slider_args = array(
		'posts_per_page' 	=> 10, 
		'paged' 			=> $paged,
		'post_type'			=> 'slider',
		'status'			=> 'publish',
		'order'				=> 'ASC'
	);
	$wp_query = new WP_Query();
	$wp_query->query( $slider_args );
	if ( $wp_query->have_posts() ): ?>
<div class="home-slider">

	<div class="flexslider">
		<ul class="slides">	
			<?php 
			$i = 1;
			while( $wp_query->have_posts() ): $wp_query->the_post();
			get_post_meta($post->ID, 'slider_background_image', TRUE) ? $slider_background_image = get_post_meta($post->ID, 'slider_background_image', TRUE) : $slider_background_image = '';	
			get_post_meta($post->ID, 'slider_title', TRUE) ? $slider_title = get_post_meta($post->ID, 'slider_title', TRUE) : $slider_title = '';
			get_post_meta($post->ID, 'slider_title2', TRUE) ? $slider_title2 = get_post_meta($post->ID, 'slider_title2', TRUE) : $slider_title2 = '';
			get_post_meta($post->ID, 'slider_title3', TRUE) ? $slider_title3 = get_post_meta($post->ID, 'slider_title3', TRUE) : $slider_title3 = '';
			get_post_meta($post->ID, 'slider_description', TRUE) ? $slider_description = get_post_meta($post->ID, 'slider_description', TRUE) : $slider_description = '';
			get_post_meta($post->ID, 'slider_link_text', TRUE) ? $slider_link_text = get_post_meta($post->ID, 'slider_link_text', TRUE) : $slider_link_text = '';
			get_post_meta($post->ID, 'slider_link_url', TRUE) ? $slider_link_url = get_post_meta($post->ID, 'slider_link_url', TRUE) : $slider_link_url = '';
			get_post_meta($post->ID, 'slider_link', TRUE) ? $slider_link = get_post_meta($post->ID, 'slider_link', TRUE) : $slider_link = '#';
			?>	
			<li>	
				<?php if ( $slider_background_image != '' ) : ?>
				<div class="main_background_image">
				<?php /* <div style='background:url("<?php echo $slider_background_image; ?>") no-repeat scroll center top transparent;width:100%;' class="main_background_image">*/ ?>
					<?php if ( $slider_link != '' ) : ?>
					<a href="<?php echo esc_url($slider_link); ?>" title="<?php echo esc_attr($slider_link); ?> " target="_blank"><img src="<?php echo esc_url($slider_background_image); ?>" alt=""></a>
					<?php else: ?>
						<img src="<?php echo esc_url($slider_background_image); ?>" alt="">
					<?php endif; ?>
				<?php else: ?>
				<div class="main_background_image">
				<?php endif; ?>
					<div class="slider_area_inner">
						<div class="slider_area_inner_container">
						<a href="<?php echo esc_url($slider_link); ?>" title="<?php echo esc_attr($slider_link); ?> " target="_blank">
							<?php if ( $slider_title != '' ) : ?>
								<h2 class="slider-title"><?php echo esc_attr($slider_title); ?></h2>
							<?php endif; ?>
							<?php if ( $slider_title2 != '' ) : ?>
								<h2 class="slider-title2"><?php echo esc_attr($slider_title2); ?></h2>
							<?php endif; ?>
							<?php if ( $slider_title3 != '' ) : ?>
								<h2 class="slider-title3"><?php echo esc_attr($slider_title3); ?></h2>
							<?php endif; ?>
							<?php if ( $slider_description != '' ) : ?>
								<h3 class="slider-description"><?php echo esc_attr($slider_description); ?></h3>
							<?php endif; ?>
							</a>
							<div class="slider-button-container">
							<?php if ( $slider_link_url != '' || $slider_link_url != '' ) : ?>
								<a href="<?php echo esc_url($slider_link_url); ?>" title="<?php echo esc_attr($slider_link_text); ?>" class="button"><?php echo esc_attr($slider_link_text); ?></a>
							<?php endif; ?>	
							</div>	
						</div>			
					</div>					
				</div>
			</li>
			<?php $i++; endwhile; ?>
		</ul>	
	</div>
</div>
<?php endif; ?>
<?php wp_reset_query(); ?>