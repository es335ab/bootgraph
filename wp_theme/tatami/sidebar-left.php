<?php
/**
 * The Sidebar left containing the main navigation, a search form and widget areas.
 *
 * @package Tatami
 * @since Tatami 1.0
 */
?>

	<section class="sidebar-left" role="navigation">
	
		<nav class="main-nav">
			<?php get_search_form(); ?>
			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
		</nav><!-- end .main-nav -->

		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		<?php endif; ?>
	</section><!-- .sidebar-left -->