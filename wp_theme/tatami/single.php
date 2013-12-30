<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Tatami 
 * @since Tatami 1.0
 */

get_header(); ?>

	<div id="content">
		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', 'single' ); ?>
			<?php comments_template( '', true ); ?>
		<?php endwhile; // end of the loop. ?>
	</div><!-- end .content -->

	<nav id="nav-single" class="clearfix">
		<div class="nav-previous"><?php next_post_link( '%link', __( 'Next Post &rarr; ', 'tatami' ) ); ?></div>
		<div class="nav-next"><?php previous_post_link( '%link', __( ' &larr; Previous Post', 'tatami' ) ); ?></div>
	</nav><!-- #nav-below -->

	<?php get_template_part( 'content-footer'); ?>

<?php get_sidebar('right'); ?>
<?php get_footer(); ?>
