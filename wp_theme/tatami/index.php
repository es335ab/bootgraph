<?php
/**
 * The main template file.
 *
 * @package Tatami 
 * @since Tatami 1.0
 */

get_header(); ?>

	<div id="content">

		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
		
			<?php get_template_part( 'content', get_post_format() ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!-- end #content -->

		<?php /* Display navigation to next/previous pages when applicable, also check if WP pagenavi plugin is activated */ ?>
		<?php if(function_exists('wp_pagenavi')) : wp_pagenavi(); else: ?>
			<?php tatami_content_nav( 'nav-below' ); ?>
		<?php endif; ?>

	<?php get_template_part( 'content-footer'); ?>

<?php get_sidebar('right'); ?>
<?php get_footer(); ?>