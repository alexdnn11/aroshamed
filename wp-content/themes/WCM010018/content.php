<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage TemplateMela
 * @since TemplateMela 1.0
 */
?>
<?php
$event=false;
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(stripos($actual_link,"/events/")){
	$event=true;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="entry-main-content">
	<?php if ( is_search() || !is_single()) : // Only display Excerpts for Search and not single pages ?>
	  <?php if ( has_post_thumbnail() && ! post_password_required() ) :
			$event_start_date = get_post_meta( get_the_ID(), 'event-start-date', true );
			$event_end_date = get_post_meta( get_the_ID(), 'event-end-date', true );
			$event_venue = get_post_meta( get_the_ID(), 'event-venue', true );
			if($event){
			echo '<div class="c-event events-page">
			<div class="sd-event i-event"><h5><strong>Дата начала:</strong><br>'.date_i18n( get_option( 'date_format' ), $event_start_date ).'</h5></div>
	<div class="ed-event i-event"><h5><strong>Дата окончания:</strong><br>'.date_i18n( get_option( 'date_format' ), $event_end_date ).'</h5></div>
	<div class="p-event i-event"><h5><strong>Место проведения:</strong><br>'. $event_venue.'</h5></div>
	</div>';
			}
			?>
			<a href="<?php echo esc_url(get_permalink()); ?>">
            <div class="entry-thumbnail">
			<?php 
			the_post_thumbnail('blog-posts-list');
			$postImage = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );
			?>
			<?php 
	if(!empty($postImage)): ?>
	<div class="block_hover">
		<div class="links">
			  <a href="<?php echo esc_url($postImage); ?>" title="Click to view Full Image" data-lightbox="example-set" class="icon mustang-gallery"><i class="fa fa-search"></i></a> <a href="<?php echo esc_url(get_permalink()); ?>" title="Click to view Read More" class="icon readmore"><i class="fa fa-share"></i></a>
		</div>
	</div>
	<?php	endif; ?>
		  </div>		</a>
		  <?php else : ?>
			  <?php if ($postImage = templatemela_get_first_post_images(get_the_ID())):?>
				  <?php if( $postImage!="0" ) : ?>
				  <div class="entry-thumbnail">
					<div class="entry-image-loop-con main">
					  <?php templatemela_print_images_thumb($postImage, get_the_title(get_the_ID()) , 400, 300,'center'); ?>
					  <article class="da-animate da-slideFromRight" style="display: block;">
						<div class="blog-icon-container"> <span class="zoom"><a data-lightbox="example-set" id="blog-zoom" href="<?php echo esc_url($postImage); ?>" title="Standard Post"></a></span> <span class="single_link"><a href="<?php echo esc_url(get_permalink()); ?>" title="View Full Image" class="standard"></a></span> </div>
					  </article>
					</div>
				  </div>
			  <?php endif; ?>
			<?php endif; ?>
  		<?php endif; ?>
  	<?php endif; ?>
    <div class="entry-content-other">
	<div class="entry-content-inner"> 
	<div class="entry-main-header">
		<?php 
				if( $post->post_title == '' ) : 
					$entry_meta_class = "empty-entry-header";
				else :
					$entry_meta_class = "";
				endif; ?>
		  <header class="entry-header <?php echo esc_attr($entry_meta_class); ?>">
			<?php if ( is_single() ) : ?>
			<h1 class="entry-title">
			  <?php the_title(); ?>
			</h1>
			<?php else : ?>
			<h1 class="entry-title"> <a href="<?php esc_url(the_permalink()); ?>" rel="bookmark">
			  <?php the_title(); ?>
			  </a><?php templatemela_sticky_post(); ?> </h1>
			<?php endif; ?>
		 </header>
	</div>
	
		  </div>
      <!-- .entry-header -->
      <?php if ( is_search() || !is_single()) : // Only display Excerpts for Search and not single pages ?>
      <div class="entry-summary">
        <div class="excerpt"> <?php echo tm_posts_short_description(); ?> </div>
      </div>
      <!-- .entry-summary -->
      <?php else : ?>
      <div class="entry-content">
        <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'templatemela' ) ); ?>
        <?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'templatemela' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
      </div>
      <!-- .entry-content -->
      <?php endif; ?>
	  <div class="entry-meta-inner">    
     <div class="entry-content-date">
		  <?php tm_post_entry_date(); ?>
		</div>
		<div class="entry-meta">
          <?php templatemela_categories_links(); ?>
          <?php templatemela_tags_links(); ?>
          <?php templatemela_author_link(); ?>
          <?php templatemela_comments_link(); ?>
          <?php edit_post_link( __( 'Edit', 'templatemela' ), '<span class="edit-link"><i class="fa fa-pencil"></i>', '</span>' ); ?>
        </div>	
        <!-- .entry-meta -->
		</div>
    </div>
    <!-- entry-content-other -->
  </div>
</article>
<!-- #post -->