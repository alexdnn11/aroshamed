<?php
/* Template Name: Gallery */ 
?>
<?php get_header(); ?>
<!--Start gallery-page-->
<div id="main-content" class="main-content blog-page blog-filter <?php echo esc_attr(tm_sidebar_position()); ?>">	
  <div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">
      <?php while ( have_posts() ) : the_post(); ?>
      <?php the_content(); ?>
      <?php //comments_template( '', true ); ?>
      <?php endwhile; // end of the loop. ?>
    </div>
    <!-- #content -->
  </div>
  <!-- #primary -->
  <?php get_sidebar(); ?>
</div>
<!-- End blog-filter -->
<?php get_footer(); ?>