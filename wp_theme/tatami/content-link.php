<?php
/**
 * The template for displaying posts in the Link Post Format
 *
 * @package Tatami 
 * @since Tatami 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<div class="entry-content">
		<?php the_content( __( 'Read more', 'tatami' ) ); ?>
	</div><!-- end .entry-content -->
	
	<footer class="entry-meta">
		<ul>
		<li class="entry-date"><a href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a></li>
		<?php if ( comments_open() ) : ?>
			<li class="entry-comments">
				<?php comments_popup_link( __( 'Comments 0', 'tatami' ), __( 'Comment 1', 'tatami' ), __( 'Comments %', 'tatami' ) ); ?>
			</li>
		<?php endif; // comments_open() ?>
			<li class="entry-edit"><?php edit_post_link(__( 'Edit', 'tatami' ), ' &#183; ' ); ?></li>
			<li class="entry-cats"><span><?php _e('Filed under:', 'tatami') ?></span><?php the_category(''); ?></li>
		</ul>
		<?php // Include Share-Btns
			$options = get_option('tatami_theme_options');
			if( $options['share-posts'] && ! is_search()) : ?>
			<?php get_template_part( 'share'); ?>
		<?php endif; ?>
	</footer><!-- end .entry-meta -->

</article><!-- end post -<?php the_ID(); ?> -->