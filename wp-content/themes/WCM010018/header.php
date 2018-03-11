<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Templatemela
 */
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113220207-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-113220207-1');
</script>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width,user-scalable=no">
<meta name="yandex-verification" content="8a73a82a1a23addf" />
<title>
<?php wp_title( '|', true, 'right'); ?>
</title>

<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
<?php templatemela_header(); ?>
 
	
<!--Display favivon -->
<?php tm_favicon(); ?>
	 
<style>
<?php templatemela_custom_css(); ?>
</style>	
<?php
wp_head();
?> 
</head>
<body <?php body_class(); ?>>
<div class="home-slider-inner slider-loading"></div>
<?php if ( get_option('tmoption_control_panel') == 'yes' ) do_action('tm_show_panel'); ?>
<div id="page" class="hfeed site">
<?php if ( get_header_image() ) : ?>
<div id="site-header"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"> <img src="<?php header_image(); ?>" width="<?php echo esc_attr(get_custom_header()->width); ?>" height="<?php echo esc_attr(get_custom_header()->height); ?>" alt=""> </a> </div>
<?php endif; ?>
<!-- Header -->
<?php templatemela_header_before(); ?>
<header id="masthead" class="site-header<?php echo " header".esc_attr(get_option('tmoption_header_layout')); ?> <?php echo esc_attr(tm_sidebar_position()); ?>" role="banner">
  <?php if (get_option('tmoption_show_topbar') == 'yes') : ?>
  <div class="topbar-outer">
    <div class="topbar-main">

								<?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) : ?>
										<div class="header_login"><!-- Start header cart -->
											<div class="header_logout">					
												<?php
												$account_text = get_option("tmoption_myaccount_text");
												$login_text = get_option("tmoption_register_text");
												$logout_text = get_option("tmoption_logout_text");
												$logout_url = '';
												
												if ( is_user_logged_in() ) {
													$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' ); 
													if ( $myaccount_page_id ) { 
													$logout_url = wp_logout_url( get_permalink( $myaccount_page_id ) ); 
													if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' )
													$logout_url = str_replace( 'http:', 'https:', $logout_url );
												} ?>
												<a href="<?php echo esc_url(get_permalink( get_option('woocommerce_myaccount_page_id')) ); ?>" class="account">
													<i class="fa fa-user"></i><?php echo _e('Myaccount','templatemela'); ?></a>
												<a href="<?php echo esc_url($logout_url); ?>" class="logout" ><i class="fa fa-power-off"></i><?php echo _e('Logout','templatemela');; ?></a>
												<?php }
												else { ?>
												<a href="<?php echo esc_url(get_permalink( get_option('woocommerce_myaccount_page_id')) ); ?>" class="login show-login-link" id="show-login-link" ><i class="fa fa-lock"></i> <?php echo _e('Login/Register','templatemela'); ?></a>
												<?php } ?>  
											</div>
										</div>
									
						<div class="header_cart">
							
								<?php 
									// Woo commerce Header Cart
									if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) : ?>
									
										<div class="cart togg">
									<?php global $woocommerce;
									ob_start();?>						
									<div id="shopping_cart" class="shopping_cart tog" <?php /*?>href="<?php echo $woocommerce->cart->get_cart_url(); ?>"<?php */?> title="<?php _e('View your shopping cart', 'woothemes'); ?>">
									
									<a class="cart-contents" href="<?php echo esc_url($woocommerce->cart->get_cart_url()); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>"><?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?><?php //echo esc_attr($woocommerce->cart->get_cart_total()); ?></a>
									</div>	
									<?php global $woocommerce; ?>
									<?php templatemela_get_widget('header-widget'); ?>		
								</div>	
															
									<?php endif; ?>	

						</div>	
						
						<?php if (is_active_sidebar('header-search')) : ?>
							<div class="header-search">
								<?php templatemela_get_widget('header-search');  ?>	
							</div>
						<?php endif; ?>				
										
									<?php endif; ?>
      <?php if (get_option('tmoption_show_topbar_social') == 'yes') : ?>
      <div class="topbar-right">
        <?php templatemela_get_topbar_social(); ?>
      </div>
      <?php endif; ?> 
    </div>
  </div>
  <?php endif; ?>
  <div class="site-header-main">
    <div class="header-main">
      <div class="hide-on-mobile">
      <div class="header_left">
        <?php if (get_option('tmoption_logo_image') != '') : ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
        <?php tm_get_logo(); ?>
        </a>
        <?php else: ?>
        <h1 class="site-title"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
          <?php bloginfo( 'name' ); ?>
          </a> </h1>
        <?php endif; ?>
        <?php if(get_option('tmoption_showsite_description') == 'yes') : ?>
        <h2 class="site-description">
          <?php bloginfo( 'description' ); ?>
        </h2>
        <?php endif; // End tmoption_showsite_description ?>
      </div>
      </div>
      <?php templatemela_header_inside(); ?>
	  <?php /*?><div class="header_middle">
	  		<?php templatemela_get_widget('header-cms');?>
	  </div><?php */?>
	  
      <div class="header_right">
						<!-- Start header-bottom -->		
					<div id="navbar" class="header-bottom navbar default">
						<nav id="site-navigation" class="navigation main-navigation" role="navigation">
								<?php /*?><div class="menu-category">
									<div class="cate-inner">
										<p class="Category-toggle">
											<span class="category_title"> <?php echo get_option('tmoption_category_text'); ?> </span>
											<span class="cat-bullet"> </span> 
										</p> 
									</div>	
									<?php if (is_active_sidebar('menu-category')) : ?>
										<?php templatemela_get_widget('menu-category'); ?>	
									<?php endif;?>
								</div><?php */?>
							<h3 class="menu-toggle"><?php _e( 'Меню', 'templatemela' ); ?></h3>
		  				    <a class="screen-reader-text skip-link" href="#content" title="<?php esc_attr_e( 'Skip to content', 'templatemela' ); ?>"><?php _e( 'Skip to content', 'templatemela' ); ?></a>	
							<div class="mega-menu">
								<?php wp_nav_menu( array( 'theme_location' => 'primary','menu_class' => 'mega' ) ); ?>
							</div>	
													
						</nav><!-- #site-navigation -->
					</div><!-- End header-bottom #navbar -->	
						
	  </div>
	
    </div>
	<div class="site-top">
				<div class="top_main">
				<?php if (get_option('tmoption_custom_banner') == 'yes') : ?>
				  <div class="topbar-banner">
					<?php templatemela_get_topbar_banner(); ?>
				  </div>
				<?php endif; ?>		
				</div>	
			</div>
	
    <!-- End header-main -->
  </div>
  <!-- End site-main -->
</header>
<!-- #masthead -->
<?php templatemela_header_after(); ?>
<?php templatemela_main_before(); ?>
<!-- Center -->
<?php /* if ( in_array( 'revslider/revslider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) : ?>  
	<div class="revolution_slider">
		<?php echo do_shortcode('[rev_slider megnordefult]'); ?>
		</div>
	<?php endif; */ ?>
<div id="main" class="site-main <?php if (get_option('tmoption_show_topbar') == 'yes') echo "extra"; ?>">
<div class="main_inner">
	<!-- Start main slider -->
	<?php if ( is_page_template('page-templates/home.php') ) : ?>
		<?php get_template_part('slider'); ?>
	<?php endif; ?>
	<!-- End main slider -->
<?php 
	$tm_page_layout = tm_page_layout(); 
	if( isset( $tm_page_layout) && !empty( $tm_page_layout ) ):
	$tm_page_layout = $tm_page_layout; 
	else:
	$tm_page_layout = '';
	endif;
	$shop = '0';
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) :
	if(is_shop())
		$tm_page_layout = 'wide-page';
		$shop = '1';
	endif;
	if ( is_page_template('page-templates/home.php') || $tm_page_layout == 'wide-page' ) : ?>
<div class="main-content-inner-full">
<?php else: ?>
<div class="main-content-inner">
<?php endif; ?>
<?php templatemela_content_before(); ?>
<?php if ( !is_page_template('page-templates/home.php')) : ?>
<div class="page-title header">
  <div class="page-title-inner">
    <h1 class="entry-title-main">
<?php	    
	  	    
	   if($shop == '1') {
	       		if(is_shop()) :
		    		echo '';
				elseif(is_blog()):  ?>
					 <?php  echo get_the_title( get_option('page_for_posts', true));
				elseif(is_search()) : ?>
					<?php printf( __( 'Search Results for: "%s"', 'templatemela' ), get_search_query() ); 
				else :
				    the_title();
	        	endif; 	
	   }else {
			 if(is_blog()){  ?>
				 <?php  echo get_the_title( get_option('page_for_posts', true));
			}else if(is_search()) { ?>
				<?php printf( __( 'Search Results for: "%s"', 'templatemela' ), get_search_query() ); 
			}else {
				    the_title();
			}
		}  
	  ?>
    </h1>
    <?php templatemela_breadcrumbs(); ?>
  </div>
</div>
<?php endif; ?>