<?php
/**
 * Social share buttons for posts and pages
 *
 * @package Tatami 
 * @since Tatami 1.0
 */
?>

 <button class="share-btn"><?php _e( 'Share', 'tatami' ); ?></button>
<div class="share-links-wrap">
<h3 class="share-this"><?php _e( 'Share this:', 'tatami' ); ?></h3>
	<ul>
		<li class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-lang="<?php bloginfo('language'); ?>"><?php _e('Tweet', 'tatami') ?></a></li>
		<li class="gplus"><g:plusone size="medium" href="<?php the_permalink(); ?>"></g:plusone></li>
		<li class="fb"><iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;layout=button_count&amp;show_faces=false&amp;width=110&amp;action=like&amp;colorscheme=light" scrolling="no" frameborder="0" allowTransparency="true"></iframe></li>
		<li class="pinit"><?php $pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink($post->ID)); ?>&media=<?php echo $pinterestimage[0]; ?>&description=<?php the_title(); ?>" class="pin-it-button" count-layout="horizontal" >Pin It</a></li>
	</ul>
</div><!-- end .share-links-wrap -->