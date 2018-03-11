<?php
/**
 * The template for displaying posts in the Link post format
 *
 * @package WordPress
 * @subpackage TemplateMela
 * @since TemplateMela 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="entry-main-content">
 <?php if ( is_search() || !is_single()) : ?>
  			<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>  
				  <div class="entry-thumbnail">
					<?php 
					the_post_thumbnail('blog-posts-list'); 
					$postImage = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );
					?>				
<?php 
	if(!empty($postImage)): ?>
	<div class="block_hover">
		<div class="links">
			  <a href="<?php echo esc_url( $postImage); ?>" title="Click to view Full Image" data-lightbox="example-set" class="icon mustang-gallery"><i class="fa fa-search"></i></a> <a href="<?php echo esc_url( get_permalink()); ?>" title="Click to view Read More" class="icon readmore"><i class="fa fa-share"></i></a> 
		</div>
	</div>
	<?php	endif; ?>	
				  </div>
			 <?php endif; ?>
  			<?php else: ?>
 			 <?php templatemela_post_thumbnail(); ?>
  		<?php endif; ?>
    <div class="entry-content-other">
      <div class="entry-main-header">
	 <header class="entry-header">
        <?php 
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
			endif;
		?>
      </header>
      <!-- .entry-header -->
	  </div>
	  
      <div class="entry-content">
        <?php
					the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'templatemela' ) );
					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'templatemela' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
					) );
				?>
      </div>
      <!-- .entry-content -->
	  <div class="entry-meta-inner"> 
					<div class="entry-content-date">
      					<?php tm_post_entry_date(); ?>
    				</div>
					<div class="entry-meta"> <span class="post-format"> <a class="entry-format" href="<?php echo esc_url( get_post_format_link( 'link' ) ); ?>"><?php echo esc_attr(get_post_format_string( 'link' )); ?></a> </span>
          <?php templatemela_categories_links(); ?>
          <?php templatemela_tags_links(); ?>
          <?php templatemela_author_link(); ?>
          <?php templatemela_comments_link(); ?>
          <?php edit_post_link( __( 'Edit', 'templatemela' ), '<span class="edit-link"><i class="fa fa-pencil"></i>', '</span>' ); ?>
        </div>
		 <!-- .entry-meta -->
	</div>
    </div>
    <!-- .entry-content-other -->
  </div>
  <!-- .entry-main-content -->
</article>
<!-- #post-## -->