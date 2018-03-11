<?php
/**
 * The template for displaying Author archive pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage TemplateMela
 * @since TemplateMela 1.0
 */
get_header(); ?>
<header class="archive-header">
     <div class="page-title">
			<div class="page-title-inner">
				<h1 class="entry-title-main">
					<?php
						/*
						 * Queue the first post, that way we know what author
						 * we're dealing with (if that is the case).
						 *
						 * We reset this later so we can run the loop properly
						 * with a call to rewind_posts().
						 */
						the_post();
						printf( esc_html__( 'All posts by %s', 'harvest' ), get_the_author() );
					?>
				</h1>
				<?php templatemela_breadcrumbs(); ?>
			</div>
		</div>
    </header>
<section id="primary" class="content-area">
  <div id="content" class="site-content" role="main">
    <?php if ( have_posts() ) : ?>
        <!-- .archive-header -->
    <?php
					/*
					 * Since we called the_post() above, we need to rewind
					 * the loop back to the beginning that way we can run
					 * the loop properly, in full.
					 */
					rewind_posts();
					// Start the Loop.
					while ( have_posts() ) : the_post();
						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );
					endwhile;
					// Previous/next page navigation.
					templatemela_paging_nav();
				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );
				endif;
			?>
  </div>
  <!-- #content -->
</section>
<!-- #primary -->
<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();