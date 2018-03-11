<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, TemplateMela
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage TemplateMela
 * @since TemplateMela 1.0
 */
get_header(); ?>
<header>
	<div class="page-title">
		<div class="page-title-inner">
			<h1 class="entry-title-main">
				<?php if ( is_day() ) :
						printf( esc_html__( 'Daily Archives: %s', 'harvest' ), get_the_date() );
					elseif ( is_month() ) :
						printf( esc_html__( 'Monthly Archives: %s', 'harvest' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'harvest' ) ) );
					elseif ( is_year() ) :
						printf( esc_html__( 'Yearly Archives: %s', 'harvest' ), get_the_date( _x( 'Y', 'yearly archives date format', 'harvest' ) ) );
					else :
						esc_html_e( 'Archives', 'harvest' );
					endif;
			?> </h1>
			<?php templatemela_breadcrumbs(); ?>
		</div>
	</div>
</header>
<div id="main-content" class="main-content blog-page blog-list <?php echo esc_attr(tm_sidebar_position()); ?>">
<section id="primary" class="content-area">
  <div id="content" class="site-content" role="main">
    <?php if ( have_posts() ) : ?>
        <!-- .page-header -->
    <?php
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
</div>
<!-- #primary -->
<?php
get_sidebar( 'content' );
get_sidebar();
get_footer(); ?>