<?php
/**
 * Template Name: Left Sidebar only, Small Content
 * Description: A site template without the right sidebar and a smaller content area
 *
 * @package Tatami 
 * @since Tatami 1.0.1
 */

get_header(); ?>

	<div id="content">

		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

			<?php comments_template( '', true ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!-- end #content -->

	<?php get_template_part( 'content-footer'); ?>

<?php get_footer(); ?>
