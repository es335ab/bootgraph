<?php
/**
 * The template for displaying search results.
 *
 * @package Tatami 
 * @since Tatami 1.0
 */

get_header(); ?>

	<div id="content">

		<?php if ( have_posts() ) : ?>
		<header class="page-header">
			<h2 class="page-title">
				<?php echo $wp_query->found_posts; ?> <?php printf( __( 'Search Results for <strong>&lsquo;%s&rsquo;</strong>', 'tatami' ), '<span>' . get_search_query() . '</span>' ); ?>
			</h2>
		</header><!--end .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php	get_template_part( 'content', get_post_format() ); ?>

			<?php endwhile; // end of the loop. ?>

			</div><!-- end .content -->
			
			<?php /* Display navigation to next/previous pages when applicable, also check if WP pagenavi plugin is activated */ ?>
				<?php if(function_exists('wp_pagenavi')) : wp_pagenavi(); else: ?>
				<?php tatami_content_nav( 'nav-below' ); ?>
			<?php endif; ?>

			<?php else : ?>

			<article id="post-0" class="page no-results not-found">		
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'Nothing Found', 'tatami' ); ?></h1>
				</header><!--end .entry-header -->
				<div class="entry-content">
					<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'tatami' ); ?></p>
				</div><!-- end .entry-content -->				
			</article>

		<?php endif; ?>

		<footer class="site-footer" role="contentinfo">
			<?php get_sidebar( 'footer' ); ?>
			<div class="site-info">
				<?php if (has_nav_menu( 'optional' ) ) {
					wp_nav_menu( array('theme_location' => 'optional', 'container' => 'nav' , 'container_class' => 'footer-nav', 'depth' => 1 ));} 
				?>
				<ul class="credit">
					<li>&copy; <?php echo date('Y'); ?> <?php bloginfo(); ?></li>
					<li><?php _e('Powered by', 'tatami') ?> <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'tatami' ) ); ?>" ><?php _e('WordPress', 'tatami') ?></a></li>
					<li><?php printf( __( 'Theme: %1$s by %2$s', 'tatami' ), 'Tatami', '<a href="http://www.elmastudio.de/en/themes/">Elmastudio</a>' ); ?></li>
				</ul><!-- end .credit -->
				<a href="#site-header" class="top clearfix"><?php _e('Top', 'tatami') ?></a>
			</div><!-- .site-info -->
		</footer><!-- end .site-footer -->
	</section><!-- end .content-wrap -->

<?php get_sidebar('right'); ?>
<?php get_footer(); ?>