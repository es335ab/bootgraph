<?php
/**
 * The Sidebar Right containing the widget areas.
 *
 * @package Tatami
 * @since Tatami 1.0
 */
?>

	<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
		<section class="sidebar-right" role="complementary">
			<?php dynamic_sidebar( 'sidebar-2' ); ?>
		</section><!-- .sidebar-right -->
	<?php endif; ?>