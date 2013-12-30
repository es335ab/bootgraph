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
			<?php if ( has_post_thumbnail() && $theme_thumbnail == 'thumbnail-bigtop' ) : // Show fullwidth thumbnail above the headline. ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="post-thumb">
				<?php the_post_thumbnail(); ?>
			</a>
		<?php endif; ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!--end .entry-header -->

		<div class="entry-content">
		<?php if( is_search () ) : // Show excerpts on search results. ?>
			<?php the_excerpt(); ?>
		<?php else : ?>
			<?php if ( has_post_thumbnail() && $theme_thumbnail == 'thumbnail-big' ) : // Show big thumbnail below the headline. ?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="post-thumb">
					<?php the_post_thumbnail(); ?>
				</a>
			<?php elseif ( has_post_thumbnail() && $theme_thumbnail == 'thumbnail-small') :  // Show small thumbnail below the headline. ?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="post-thumb-small">
					<?php the_post_thumbnail('thumbnail'); ?>
				</a>
			<?php endif; ?>
			<?php the_content( __( 'Read more', 'tatami' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'tatami' ), 'after' => '</div>' ) ); ?>
		<?php endif; ?>
		</div><!-- end .entry-content -->

		<?php if ( get_post_format() ) : // Show author bio only for standard post format posts ?>
		<?php else: ?>
		<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their author bio, show it on standard posts. ?>
		<div class="author-info">
			<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'tatami_author_bio_avatar_size', 40 ) ); ?>
				<div class="author-details">
					<h3><?php the_author(); ?></h3>
					<?php if( $options['custom_authorlinks'] ) : // if author social links are filled out in the theme optons. ?>
						<p class="author-links"><span><?php _e('Find me on: ', 'tatami') ?></span>
							<?php echo stripslashes($options['custom_authorlinks']); ?>
						</p>
					<?php endif; ?>
				</div><!-- end .author-details -->
					<p class="author-description"><?php the_author_meta( 'description' ); ?></p>	
		</div><!-- end .author-info -->
		<?php endif; ?>
		<?php endif; ?>

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
			<?php $tags_list = get_the_tag_list( '', ', ' ); 
			if ( $tags_list ): ?>
			<li class="entry-tags"><span><?php _e('Tagged:', 'tatami') ?></span> <?php the_tags( '', ', ', '' ); ?></li>
			<?php endif; // get_the_tag_list() ?>
		</ul>
		<?php // // Include Share Buttons on single posts
			$options = get_option('tatami_theme_options');
			if($options['share-singleposts'] or $options['share-posts']) : ?>
			<?php get_template_part( 'share'); ?>
		<?php endif; ?>
	</footer><!-- end .entry-meta -->

</article><!-- end post -<?php the_ID(); ?> -->