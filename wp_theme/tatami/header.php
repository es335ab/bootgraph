<?php
/**
 * The theme Header.
 *
 * Displays all of the <head> section and everything up till .content-wrap
 *
 * @package Tatami 
 * @since Tatami 1.0
 */
?><!DOCTYPE html>
<!--[if lte IE 8]>
<html class="ie" <?php language_attributes(); ?>>
<![endif]-->
<html  id="doc" class="no-js" <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<?php $options = get_option('tatami_theme_options'); ?>
<?php if( $options['custom_favicon'] != '' ) : ?>
<link rel="shortcut icon" type="image/ico" href="<?php echo $options['custom_favicon']; ?>" />
<?php endif  ?>
<?php if( $options['custom_apple_icon'] != '' ) : ?>
<link rel="apple-touch-icon" href="<?php echo $options['custom_apple_icon']; ?>" />
<?php endif  ?>
<script type="text/javascript">
	var doc = document.getElementById('doc');
	doc.removeAttribute('class', 'no-js');
	doc.setAttribute('class', 'js');
</script>
<!-- IE Fix for HTML5 Tags and addtional CSS styles for older IE browser versions -->
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/ie.css" />
	<![endif]-->
<?php
	wp_enqueue_script('jquery');
	if ( is_singular() && get_option( 'thread_comments' ) )
	wp_enqueue_script( 'comment-reply' );
	wp_head();
?>

</head>

<body <?php body_class(); ?> id="menu">

		<header id="site-header" role="banner">
			<hgroup class="site-title">
				<?php if( $options['custom_logo'] != '' ) : ?>
						<a href="<?php echo home_url( '/' ); ?>" class="logo"><img src="<?php echo $options['custom_logo']; ?>" alt="<?php bloginfo('name'); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" /></a>
				<?php else: ?>
				<h1 class="title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php endif  ?>

				<?php
				if($options['custom_introtext'] != '' ){
					echo ('<h2 class="description">');
					echo stripslashes($options['custom_introtext']);
					echo ('</h2>');
				} else { ?>
					<h2 class="description"><?php bloginfo( 'description' ); ?></h2>
				<?php } ?>
			</hgroup>
		</header><!-- end .branding -->
		
		<div class="container">

			<?php get_sidebar('left'); ?>	

		<nav class="off-canvas-nav">
			<ul>
				<li class="menu-item"><a class="menu-button" href="#menu" title="<?php _e( 'Menu', 'tatami' ); ?>"><?php _e( 'Menu', 'tatami' ); ?></a></li>
				<li class="sidebar-item"><a class="sidebar-button" href="#sidebar" title="<?php _e( 'Sidebar', 'tatami' ); ?>"><?php _e( 'Sidebar', 'tatami' ); ?></a></li>
			</ul>
		</nav><!-- end .off-canvas-navigation -->

		<a class="mask-left" href="#site-header"></a>
		<a class="mask-right" href="#site-header"></a>

		<section role="main" class="content-wrap">
