<?php
/**
 * The footer content with footer widgets and the closing of the .content-wrap
 *
 * @package Tatami 
 * @since Tatami 1.0
 */
?>

	<footer class="site-footer" role="contentinfo">

		<?php get_sidebar( 'footer' ); ?>

		<div class="site-info">
			<?php if (has_nav_menu( 'optional' ) ) {
				wp_nav_menu( array('theme_location' => 'optional', 'container' => 'nav' , 'container_class' => 'footer-nav', 'depth' => 1 ));} 
			?>

			<?php
				$options = get_option('tatami_theme_options');
				if($options['custom_footertext'] != '' ){
					echo ('<ul class="credit"><li>');
					echo stripslashes($options['custom_footertext']);
					echo ('</li></ul>');
			} else { ?>
			<ul class="credit">
				<li>&copy; <?php echo date('Y'); ?> <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></li>
				<li><?php _e('Powered by', 'tatami') ?> <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'tatami' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'tatami' ); ?>"><?php _e('WordPress', 'tatami') ?></a></li>
				<li><?php printf( __( 'Theme: %1$s by %2$s', 'tatami' ), 'Tatami', '<a href="http://www.elmastudio.de/en/themes/" title="Elmastudio WordPress Themes">Elmastudio</a>' ); ?></li>
			</ul><!-- end .credit -->
			<?php } ?>

			<a href="#site-header" class="top clearfix" title="<?php _e('Top', 'tatami') ?>"><?php _e('Top', 'tatami') ?></a>
		</div><!-- .site-info -->
	</footer><!-- end .site-footer -->
</section><!-- end .content-wrap -->