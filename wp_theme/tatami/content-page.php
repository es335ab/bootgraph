<?php
/**
 * The template used for displaying page content.
 *
 * @package Tatami 
 * @since Tatami 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php if ( has_post_thumbnail()  ) : // Show fullwidth thumbnail above the headline. ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="post-thumb">
				<?php the_post_thumbnail(); ?>
			</a>
		<?php endif; ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- end .entry-header -->

	<div class="entry-content clearfix">
		<?php the_content(); ?>
	</div><!-- end .entry-content -->

	<?php // Include Share-Btns
		$options = get_option('tatami_theme_options');
		if( $options['share-pages'] ) : ?>
		<footer class="entry-meta">
			<?php get_template_part( 'share'); ?>
		</footer><!-- end .entry-meta -->
	<?php endif; ?>

</article><!-- end post-<?php the_ID(); ?> -->