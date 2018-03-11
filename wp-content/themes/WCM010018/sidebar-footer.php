<?php
/**
 * The Footer Sidebar
 *
 * @package WordPress
 * @subpackage TemplateMela
 * @since TemplateMela 1.0
 */
if ( !is_active_sidebar( 'first-footer-widget-area'  )
	&& ! is_active_sidebar( 'second-footer-widget-area' )
	&& ! is_active_sidebar( 'third-footer-widget-area'  )
)
{
	return;
}
?>

<div id="footer-widget-area" class="show-on-mobile">
  <?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
  <center><div id="first" class="first-widget footer-widget" style=" margin: auto;" >
    <?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
  </div>
  </center>
  <!-- #first .widget-area -->
  <?php endif; ?>
  <?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>
  <div id="second" class="second-widget footer-widget" style="margin: auto;">
    <?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
  </div>
  <!-- #second .widget-area -->
  <?php endif; ?>
  <?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>
  <div id="third" class="third-widget footer-widget" style="margin: auto;">
    <?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
  </div>
  <!-- #third .widget-area -->
  <?php endif; ?>
  <?php if ( is_active_sidebar( 'footer-widget' ) ) : ?>
  <div class="footer-widget footer-widget">
    <?php dynamic_sidebar( 'footer-widget' ); ?>
  </div>
  <!-- #fourth .widget-area -->
  <?php endif; ?>
</div>
<div id="footer-widget-area" class="hide-on-mobile">
    <?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
        <center><div id="first" class="first-widget footer-widget" style="width: 30%; margin: auto;" >
                <?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
            </div>
        </center>
        <!-- #first .widget-area -->
    <?php endif; ?>
    <?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>
        <div id="second" class="second-widget footer-widget" style="width: 30%; margin: auto;">
            <?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
        </div>
        <!-- #second .widget-area -->
    <?php endif; ?>
    <?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>
        <div id="third" class="third-widget footer-widget" style="width: 30%; margin: auto;">
            <?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
        </div>
        <!-- #third .widget-area -->
    <?php endif; ?>
    <?php if ( is_active_sidebar( 'footer-widget' ) ) : ?>
        <div class="footer-widget footer-widget">
            <?php dynamic_sidebar( 'footer-widget' ); ?>
        </div>
        <!-- #fourth .widget-area -->
    <?php endif; ?>
</div>
