<?php
/**
 * The default template for displaying content
 *
 * @package Tatami 
 * @since Tatami 1.0
 */
?>

<?php $options = get_option('tatami_theme_options');
$theme_thumbnail = $options['theme_thumbnail']; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php if ( has_post_thumbnail() && $theme_thumbnail == 'thumbnail-bigtop' && ! is_search() ) : // Show fullwidth thumbnail above the headline. ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="post-thumb">
				<?php the_post_thumbnail(); ?>
			</a>
		<?php endif; ?>

		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<div class="featured-post">
				<?php _e( 'Featured Post', 'tatami' ); ?>
			</div><!--end .featured-post -->
		<?php endif; ?>

			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tatami' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	</header><!--end .entry-header -->

	<div class="entry-content">
	<?php if( $options['show-excerpt'] || is_search () ) : // Show excerpts if the theme option is activated and on search results. ?>
		<?php the_excerpt(); ?>
	<?php else : ?>
		<?php if ( has_post_thumbnail() && $theme_thumbnail == 'thumbnail-big' && ! is_search() ) : // Show big thumbnail below the headline. ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="post-thumb">
				<?php the_post_thumbnail(); ?>
			</a>
		<?php elseif ( has_post_thumbnail() && $theme_thumbnail == 'thumbnail-small' && ! is_search() ) :  // Show small thumbnail below the headline. ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="post-thumb-small">
				<?php the_post_thumbnail('thumbnail'); ?>
			</a>
		<?php endif; ?>
		<?php the_content( __( 'Read more', 'tatami' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'tatami' ), 'after' => '</div>' ) ); ?>
	<?php endif; ?>
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
			<?php if ( has_category() ) : ?>
			<li class="entry-cats"><span><?php _e('Filed under: ', 'tatami') ?></span><?php the_category(', '); ?></li>
			<?php endif; // has_category() ?>
		</ul>
		<?php // Include Share-Btns
		if( $options['share-posts'] && ! is_search() ) : ?>
			<?php get_template_part( 'share'); ?>
		<?php endif; ?>
	</footer><!-- end .entry-meta -->

</article><!-- end post -<?php the_ID(); ?> -->