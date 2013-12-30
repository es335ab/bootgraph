<?php
/**
 * Tatami functions and definitions
 *
 * @package Tatami 
 * @since Tatami 1.0
 */
 
/*-----------------------------------------------------------------------------------*/
/* Set the content width based on the theme's design and stylesheet.
/*-----------------------------------------------------------------------------------*/

if ( ! isset( $content_width ) )
	$content_width = 840; /* pixels */

/*-----------------------------------------------------------------------------------*/
/* Call JavaScript Scripts for Tatami (Fitvids for elasic videos, Custom and Placeholder)
/*-----------------------------------------------------------------------------------*/

add_action('wp_enqueue_scripts','tatami_scripts_function');
	function tatami_scripts_function() {
		wp_enqueue_script( 'fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', false, '1.0');
		wp_enqueue_script( 'custom', get_template_directory_uri() . '/js/custom.js', false, '1.0');
}

/*-----------------------------------------------------------------------------------*/
/* Include Google Webfonts
/*-----------------------------------------------------------------------------------*/

function load_fonts() {
          wp_register_style('googleFonts', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,700,600');
          wp_enqueue_style( 'googleFonts');
       }

add_action('wp_print_styles', 'load_fonts');

/*-----------------------------------------------------------------------------------*/
/* Sets up theme defaults and registers support for various WordPress features.
/*-----------------------------------------------------------------------------------*/
/**
 * Tell WordPress to run tatami_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'tatami_setup' );

if ( ! function_exists( 'tatami_setup' ) ):
/**
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override tatami_setup() in a child theme, add your own tatami_setup to your child theme's
 * functions.php file.
 */
function tatami_setup() {

	// Make Tatami available for translation. Translations can be filed in the /languages/ directory.
	load_theme_textdomain( 'tatami', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();
	
	// Load up the Tatami theme options page and related code.
	require( get_template_directory() . '/inc/theme-options.php' );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu().
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'tatami' ),
		'optional' => __( 'Footer Navigation (no sub menus supported)', 'tatami' )
	) );
	
	// Add support for Post Formats
	add_theme_support( 'post-formats', array( 'aside', 'status', 'link', 'quote', 'image', 'gallery', 'video', 'audio','chat' ) );

	// This theme support for Jetpack Infinite Scroll
	add_theme_support( 'infinite-scroll', array(
		'container'  => 'content',
		'footer_widgets' => array( 'sidebar-3', 'sidebar-4', 'sidebar-5' ),

	) );

	// Allows users to set a custom background color or image
	add_theme_support( 'custom-background' );

}
endif; // tatami_setup

/*-----------------------------------------------------------------------------------*/
/* A more formatted title element for the head tag
/*-----------------------------------------------------------------------------------*/
function tatami_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'tatami' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'tatami_wp_title', 10, 2 );

/*-----------------------------------------------------------------------------------*/
/* Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
/*-----------------------------------------------------------------------------------*/
function tatami_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'tatami_page_menu_args' );

/*-----------------------------------------------------------------------------------*/
/* Number of tags in the tagcoud widget
/*-----------------------------------------------------------------------------------*/
add_filter( 'widget_tag_cloud_args', 'tatami_widget_tag_cloud_args' );
function tatami_widget_tag_cloud_args( $args ) {
	$args['number'] = 20;
	return $args;
}

/*-----------------------------------------------------------------------------------*/
/* Sets the post excerpt length to 40 characters.
/*-----------------------------------------------------------------------------------*/
function tatami_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'tatami_excerpt_length' );

/*-----------------------------------------------------------------------------------*/
/* Returns a "Continue Reading" link for excerpts
/*-----------------------------------------------------------------------------------*/
function tatami_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Read more', 'tatami' ) . '</a>';
}

/*-----------------------------------------------------------------------------------*/
/* Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and tatami_continue_reading_link().
/*
/* To override this in a child theme, remove the filter and add your own
/* function tied to the excerpt_more filter hook.
/*-----------------------------------------------------------------------------------*/
function tatami_auto_excerpt_more( $more ) {
	return ' (&hellip;)' . tatami_continue_reading_link();
}
add_filter( 'excerpt_more', 'tatami_auto_excerpt_more' );

/*-----------------------------------------------------------------------------------*/
/* Adds a pretty "Continue Reading" link to custom post excerpts.
/*
/* To override this link in a child theme, remove the filter and add your own
/* function tied to the get_the_excerpt filter hook.
/*-----------------------------------------------------------------------------------*/
function tatami_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= tatami_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'tatami_custom_excerpt_more' );

/*-----------------------------------------------------------------------------------*/
/* Remove inline styles printed when the gallery shortcode is used.
/*-----------------------------------------------------------------------------------*/
function tatami_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'tatami_remove_gallery_css' );


if ( ! function_exists( 'tatami_comment' ) ) :
/*-----------------------------------------------------------------------------------*/
/* Comments template tatami_comment
/*-----------------------------------------------------------------------------------*/
function tatami_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>

	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">

			<div class="comment-avatar">
				<?php echo get_avatar( $comment, 40 ); ?>
			</div>

<div class="comment-content">
				<ul class="comment-meta">
					<li class="comment-author"><?php printf( __( ' %s ', 'tatami' ), sprintf( ' %s ', get_comment_author_link() ) ); ?></li>
					<li class="comment-time"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s @ %2$s', 'tatami' ),
						get_comment_date('d.m.y'),
						get_comment_time() );
					?></a></li>
					<li class="comment-edit"><?php edit_comment_link( __( 'Edit', 'tatami' ), ' &#183; ' );?></li>
				</ul>
					<div class="comment-text">
						<?php comment_text(); ?>
						<?php if ( $comment->comment_approved == '0' ) : ?>
							<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'tatami' ); ?></p>
						<?php endif; ?>
						<p class="comment-reply"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'tatami' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></p>
					</div><!-- end .comment-text -->
					
			</div><!-- end .comment-content -->
		
		</article><!-- end .comment -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="pingback">
		<p><?php _e( '<span>Pingback:</span>', 'tatami' ); ?> <?php comment_author_link(); ?></p>
		<p><?php edit_comment_link( __('Edit', 'tatami'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/*-----------------------------------------------------------------------------------*/
/* Register widgetized areas
/*-----------------------------------------------------------------------------------*/
function tatami_widgets_init() {

	register_sidebar( array (
		'name' => __( 'Sidebar Left', 'tatami' ),
		'id' => 'sidebar-1',
		'description' => __( 'Widgets will appear in the left sidebar below the main navigation on posts and pages.', 'tatami' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Sidebar Right', 'tatami' ),
		'id' => 'sidebar-2',
		'description' => __( 'Widgets will appear in the right sidebar on posts and pages.', 'tatami' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Single-Column', 'tatami' ),
		'id' => 'sidebar-3',
		'description' => __( 'Widgets will appear in a single column widget area in the footer.', 'tatami' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Two-Column Left', 'tatami' ),
		'id' => 'sidebar-4',
		'description' => __( 'Widgets will appear in the left column of the two-column footer widget area.', 'tatami' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Two-Column Right', 'tatami' ),
		'id' => 'sidebar-5',
		'description' => __( 'Widgets will appear in the right column of the two-column footer widget area.', 'tatami' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

}
add_action( 'init', 'tatami_widgets_init' );


if ( ! function_exists( 'tatami_content_nav' ) ) :

/*-----------------------------------------------------------------------------------*/
/* Display navigation to next/previous pages when applicable
/*-----------------------------------------------------------------------------------*/
function tatami_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>" class="clearfix">
				<div class="nav-previous"><?php next_posts_link( __( ' &larr; Older entries', 'tatami'  ) ); ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer entries &rarr; ', 'tatami' ) ); ?></div>
			</nav><!-- end #nav-below -->
	<?php endif;
}

endif; // tatami_content_nav

/*-----------------------------------------------------------------------------------*/
/* Removes the default CSS style from the WP image gallery
/*-----------------------------------------------------------------------------------*/
add_filter('gallery_style', create_function('$a', 'return "
<div class=\'gallery\'>";'));


/*-----------------------------------------------------------------------------------*/
/* Extends the default WordPress body classes
/*-----------------------------------------------------------------------------------*/
function tatami_body_class( $classes ) {

	if ( is_page_template( 'page-templates/left-sidebar.php' ) || is_page_template( 'page-templates/left-sidebar-small.php' ) ) {
		$classes[] = 'template-left-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'tatami_body_class' );

function tatamismallcontent_body_class( $classes ) {

	if ( is_page_template( 'page-templates/left-sidebar-small.php' ) ) {
		$classes[] = 'small-content';
	}

	return $classes;
}
add_filter( 'body_class', 'tatamismallcontent_body_class' );

/*-----------------------------------------------------------------------------------*/
/* Tatami Shortcodes
/*-----------------------------------------------------------------------------------*/
// Enable shortcodes in widget areas
add_filter( 'widget_text', 'do_shortcode' );

// Replace WP autop formatting
if (!function_exists( "tatami_remove_wpautop")) {
	function tatami_remove_wpautop($content) { 
		$content = do_shortcode( shortcode_unautop( $content ) ); 
		$content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content);
		return $content;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Multi Columns Shortcodes
/* Don't forget to add "_last" behind the shortcode if it is the last column.
/*-----------------------------------------------------------------------------------*/

// Two Columns
function tatami_shortcode_two_columns_one( $atts, $content = null ) {
   return '<div class="two-columns-one">' . tatami_remove_wpautop($content) . '</div>';
}
add_shortcode( 'two_columns_one', 'tatami_shortcode_two_columns_one' );

function tatami_shortcode_two_columns_one_last( $atts, $content = null ) {
   return '<div class="two-columns-one last">' . tatami_remove_wpautop($content) . '</div>';
}
add_shortcode( 'two_columns_one_last', 'tatami_shortcode_two_columns_one_last' );

// Three Columns
function tatami_shortcode_three_columns_one($atts, $content = null) {
   return '<div class="three-columns-one">' . tatami_remove_wpautop($content) . '</div>';
}
add_shortcode( 'three_columns_one', 'tatami_shortcode_three_columns_one' );

function tatami_shortcode_three_columns_one_last($atts, $content = null) {
   return '<div class="three-columns-one last">' . tatami_remove_wpautop($content) . '</div>';
}
add_shortcode( 'three_columns_one_last', 'tatami_shortcode_three_columns_one_last' );

function tatami_shortcode_three_columns_two($atts, $content = null) {
   return '<div class="three-columns-two">' . tatami_remove_wpautop($content) . '</div>';
}
add_shortcode( 'three_columns_two', 'tatami_shortcode_three_columns' );

function tatami_shortcode_three_columns_two_last($atts, $content = null) {
   return '<div class="three-columns-two last">' . tatami_remove_wpautop($content) . '</div>';
}
add_shortcode( 'three_columns_two_last', 'tatami_shortcode_three_columns_two_last' );

// Four Columns
function tatami_shortcode_four_columns_one($atts, $content = null) {
   return '<div class="four-columns-one">' . tatami_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_one', 'tatami_shortcode_four_columns_one' );

function tatami_shortcode_four_columns_one_last($atts, $content = null) {
   return '<div class="four-columns-one last">' . tatami_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_one_last', 'tatami_shortcode_four_columns_one_last' );

function tatami_shortcode_four_columns_two($atts, $content = null) {
   return '<div class="four-columns-two">' . tatami_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_two', 'tatami_shortcode_four_columns_two' );

function tatami_shortcode_four_columns_two_last($atts, $content = null) {
   return '<div class="four-columns-two last">' . tatami_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_two_last', 'tatami_shortcode_four_columns_two_last' );

function tatami_shortcode_four_columns_three($atts, $content = null) {
   return '<div class="four-columns-three">' . tatami_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_three', 'tatami_shortcode_four_columns_three' );

function tatami_shortcode_four_columns_three_last($atts, $content = null) {
   return '<div class="four-columns-three last">' . tatami_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_three_last', 'tatami_shortcode_four_columns_three_last' );

// Divide Text Shortcode
function tatami_shortcode_divider($atts, $content = null) {
   return '<div class="divider"></div>';
}
add_shortcode( 'divider', 'tatami_shortcode_divider' );

/*-----------------------------------------------------------------------------------*/
/* Text Highlight and Info Boxes Shortcodes
/*-----------------------------------------------------------------------------------*/

function tatami_shortcode_white_box($atts, $content = null) {
   return '<div class="white-box">' . do_shortcode( tatami_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'white_box', 'tatami_shortcode_white_box' );

function tatami_shortcode_yellow_box($atts, $content = null) {
   return '<div class="yellow-box">' . do_shortcode( tatami_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'yellow_box', 'tatami_shortcode_yellow_box' );

function tatami_shortcode_red_box($atts, $content = null) {
   return '<div class="red-box">' . do_shortcode( tatami_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'red_box', 'tatami_shortcode_red_box' );

function tatami_shortcode_blue_box($atts, $content = null) {
   return '<div class="blue-box">' . do_shortcode( tatami_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'blue_box', 'tatami_shortcode_blue_box' );

function tatami_shortcode_green_box($atts, $content = null) {
   return '<div class="green-box">' . do_shortcode( tatami_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'green_box', 'tatami_shortcode_green_box' );

function tatami_shortcode_lightgrey_box($atts, $content = null) {
   return '<div class="lightgrey-box">' . do_shortcode( tatami_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'lightgrey_box', 'tatami_shortcode_lightgrey_box' );

function tatami_shortcode_grey_box($atts, $content = null) {
   return '<div class="grey-box">' . do_shortcode( tatami_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'grey_box', 'tatami_shortcode_grey_box' );

function tatami_shortcode_dark_box($atts, $content = null) {
   return '<div class="dark-box">' . do_shortcode( tatami_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'dark_box', 'tatami_shortcode_dark_box' );

/*-----------------------------------------------------------------------------------*/
/* Buttons Shortcodes
/*-----------------------------------------------------------------------------------*/
function tatami_button( $atts, $content = null ) {
    extract(shortcode_atts(array(
    'link'	=> '#',
    'target' => '',
    'color'	=> '',
    'size'	=> '',
	 'form'	=> '',
	 'font'	=> '',
    ), $atts));

	$color = ($color) ? ' '.$color. '-btn' : '';
	$size = ($size) ? ' '.$size. '-btn' : '';
	$form = ($form) ? ' '.$form. '-btn' : '';
	$font = ($font) ? ' '.$font. '-btn' : '';
	$target = ($target == 'blank') ? ' target="_blank"' : '';

	$out = '<a' .$target. ' class="standard-btn' .$color.$size.$form.$font. '" href="' .$link. '"><span>' .do_shortcode($content). '</span></a>';

    return $out;
}
add_shortcode('button', 'tatami_button');

/*-----------------------------------------------------------------------------------*/
/* Include Tatami Flickr Widget
/*-----------------------------------------------------------------------------------*/
class tatami_flickr extends WP_Widget {

	function tatami_flickr() {
		$widget_ops = array('description' => 'Show your Flickr preview images' , 'tatami');

		parent::WP_Widget(false, __('Tatami Flickr', 'tatami'),$widget_ops);
	}

	function widget($args, $instance) {  
		extract( $args );
		$title = $instance['title'];
		$id = $instance['id'];
		$linktext = $instance['linktext'];
		$linkurl = $instance['linkurl'];
		$number = $instance['number'];
		$type = $instance['type'];
		$sorting = $instance['sorting'];
		
		echo $before_widget; ?>
		<?php if($title != '')
			echo '<h3 class="widget-title">'.$title.'</h3>'; ?>
            
        <div class="flickr_badge_wrapper"><script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=<?php echo $sorting; ?>&amp;&amp;source=<?php echo $type; ?>&amp;<?php echo $type; ?>=<?php echo $id; ?>&amp;size=m"></script>
		  <div class="clear"></div>
		  <?php if($linktext == ''){echo '';} else {echo '<div class="flickr-bottom"><a href="'.$linkurl.'" class="flickr-home" target="_blank">'.$linktext.'</a></div>';}?>
		</div><!-- end .flickr_badge_wrapper -->
	
	   <?php			
	   echo $after_widget;
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {
		$title = esc_attr($instance['title']);
		$id = esc_attr($instance['id']);
		$linktext = esc_attr($instance['linktext']);
		$linkurl = esc_attr($instance['linkurl']);
		$number = esc_attr($instance['number']);
		$type = esc_attr($instance['type']);
		$sorting = esc_attr($instance['sorting']);
		?>
		
		 <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Flickr ID (<a href="http://www.idgettr.com" target="_blank">idGettr</a>):','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('id'); ?>" value="<?php echo $id; ?>" class="widefat" id="<?php echo $this->get_field_id('id'); ?>" />
        </p>
		  
		  <p>
            <label for="<?php echo $this->get_field_id('linktext'); ?>"><?php _e('Flickr Profile Link Text:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('linktext'); ?>" value="<?php echo $linktext; ?>" class="widefat" id="<?php echo $this->get_field_id('linktext'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('linkurl'); ?>"><?php _e('Flickr Profile URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('linkurl'); ?>" value="<?php echo $linkurl; ?>" class="widefat" id="<?php echo $this->get_field_id('linkurl'); ?>" />
        </p>

       	<p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of photos:','tatami'); ?></label>
            <select name="<?php echo $this->get_field_name('number'); ?>" class="widefat" id="<?php echo $this->get_field_id('number'); ?>">
                <?php for ( $i = 1; $i <= 10; $i += 1) { ?>
                <option value="<?php echo $i; ?>" <?php if($number == $i){ echo "selected='selected'";} ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Choose user or group:','tatami'); ?></label>
            <select name="<?php echo $this->get_field_name('type'); ?>" class="widefat" id="<?php echo $this->get_field_id('type'); ?>">
                <option value="user" <?php if($type == "user"){ echo "selected='selected'";} ?>><?php _e('User', 'tatami'); ?></option>
                <option value="group" <?php if($type == "group"){ echo "selected='selected'";} ?>><?php _e('Group', 'tatami'); ?></option>            
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('sorting'); ?>"><?php _e('Show latest or random pictures:','tatami'); ?></label>
            <select name="<?php echo $this->get_field_name('sorting'); ?>" class="widefat" id="<?php echo $this->get_field_id('sorting'); ?>">
                <option value="latest" <?php if($sorting == "latest"){ echo "selected='selected'";} ?>><?php _e('Latest', 'tatami'); ?></option>
                <option value="random" <?php if($sorting == "random"){ echo "selected='selected'";} ?>><?php _e('Random', 'tatami'); ?></option>            
            </select>
        </p>
		<?php
	}
} 

register_widget('tatami_flickr');


/*-----------------------------------------------------------------------------------*/
/* Include Tatami About Widget
/*-----------------------------------------------------------------------------------*/

class tatami_about extends WP_Widget {

	function tatami_about() {
		$widget_ops = array('description' => 'About widget with picture and intro text' , 'tatami');

		parent::WP_Widget(false, __('Tatami About', 'tatami'),$widget_ops);
	}

	function widget($args, $instance) {  
		extract( $args );
		$title = $instance['title'];
		$imageurl = $instance['imageurl'];
		$imagewidth = $instance['imagewidth'];
		$imageheight = $instance['imageheight'];
		$abouttext = $instance['abouttext'];

		echo $before_widget; ?>
		<?php if($title != '')
			echo '<h3 class="widget-title">'.$title.'</h3>'; ?>

				<img src="<?php echo $imageurl; ?>" width="<?php echo $imagewidth; ?>" height="<?php echo $imageheight; ?>" class="about-image">
			<div class="about-text-wrap">
			<p class="about-text"><?php echo $abouttext; ?></p>
			</div><!-- end .about-text-wrap -->
	   <?php			
	   echo $after_widget;
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {
		$title = esc_attr($instance['title']);
		$imageurl = esc_attr($instance['imageurl']);
		$imagewidth = esc_attr($instance['imagewidth']);
		$imageheight = esc_attr($instance['imageheight']);
		$abouttext = esc_attr($instance['abouttext']);
		?>
		
		 <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
		  
		  <p>
            <label for="<?php echo $this->get_field_id('imageurl'); ?>"><?php _e('Image URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('imageurl'); ?>" value="<?php echo $imageurl; ?>" class="widefat" id="<?php echo $this->get_field_id('imageurl'); ?>" />
        </p>
		  
		  <p>
            <label for="<?php echo $this->get_field_id('imagewidth'); ?>"><?php _e('Image Width (only value, no px):','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('imagewidth'); ?>" value="<?php echo $imagewidth; ?>" class="widefat" id="<?php echo $this->get_field_id('imagewidth'); ?>" />
        </p>
		  
		   <p>
            <label for="<?php echo $this->get_field_id('imageheight'); ?>"><?php _e('Image Height (only value, no px):','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('imageheight'); ?>" value="<?php echo $imageheight; ?>" class="widefat" id="<?php echo $this->get_field_id('imageheight'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('abouttext'); ?>"><?php _e('About Text:','tatami'); ?></label>
           <textarea name="<?php echo $this->get_field_name('abouttext'); ?>" class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('abouttext'); ?>"><?php echo( $abouttext ); ?></textarea>
        </p>

		<?php
	}
} 

register_widget('tatami_about');

/*-----------------------------------------------------------------------------------*/
/* Include Tatami Video Widget
/*-----------------------------------------------------------------------------------*/

class tatami_video extends WP_Widget {

	function tatami_video() {
		$widget_ops = array('description' => 'Show a featured video' , 'tatami');

		parent::WP_Widget(false, __('Tatami Featured Video', 'tatami'),$widget_ops);
	}

	function widget($args, $instance) {  
		extract( $args );
		$title = $instance['title'];
		$embedcode = $instance['embedcode'];
		
		echo $before_widget; ?>
		<?php if($title != '')
			echo '<h3 class="widget-title">'.$title.'</h3>'; ?>
            
        <div class="video_widget">
		  <div class="featured-video"><?php echo $embedcode; ?></div>
		  </div><!-- end .video_widget -->
	
	   <?php			
	   echo $after_widget;
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {
		$title = esc_attr($instance['title']);
		$embedcode = esc_attr($instance['embedcode']);
		?>
		
		 <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Video embed code:','tatami'); ?></label>
				<textarea name="<?php echo $this->get_field_name('embedcode'); ?>" class="widefat" rows="6" id="<?php echo $this->get_field_id('embedcode'); ?>"><?php echo( $embedcode ); ?></textarea>
        </p>

		<?php
	}
} 

register_widget('tatami_video');


/*-----------------------------------------------------------------------------------*/
/* Include Tatami Recent Posts Widget
/*-----------------------------------------------------------------------------------*/

class tatami_recentposts extends WP_Widget {

	function tatami_recentposts() {
		$widget_ops = array('description' => 'Show a number of recent posts with publishing date and thumbnails' , 'tatami');

		parent::WP_Widget(false, __('Tatami Recent Posts', 'tatami'),$widget_ops);
	}

	function widget($args, $instance) {  
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
      $cat = apply_filters('widget_title', $instance['cat']);
		$number = apply_filters('widget_title', $instance['number']);
		$thumbnail = $instance['thumbnail'];
		
		echo $before_widget; ?>
		<?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
							<ul>
							<?php
								global $post;
								$tatami_post = $post;
								
								// get the category IDs and place them in an array
								if($cat) {
									$args = 'posts_per_page=' . $number . '&cat=' . $cat;
								} else {
									$args = 'posts_per_page=' . $number;
								}
								$myposts = get_posts( $args );
								foreach( $myposts as $post ) : setup_postdata($post); ?>
									<li>
										<?php if ( has_post_thumbnail() and $thumbnail == true) : ?>
										<a href="<?php the_permalink(); ?>" class="recentposts-thumb"><?php the_post_thumbnail( 'thumbnail');?></a>
										<?php endif; ?>
										<h3 class="recentposts-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
										<a href="<?php the_permalink(); ?>" class="recentposts-date"><?php echo get_the_date(); ?></a>
									</li>
								<?php endforeach; ?>
								<?php $post = $tatami_post; ?>
							</ul>
	   <?php			
	   echo $after_widget;
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {
		$title = esc_attr($instance['title']);
		$cat = esc_attr($instance['cat']);
		$number = esc_attr($instance['number']);
		$thumbnail = esc_attr($instance['thumbnail']);
		?>
		
		 <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
		  
		  <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Numbers of posts','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $number; ?>" class="widefat" id="<?php echo $this->get_field_id('number'); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('cat'); ?>"><?php _e('Category ID numbers (to choose which categories to include, separate by comma ):','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('cat'); ?>" value="<?php echo $cat; ?>" class="widefat" id="<?php echo $this->get_field_id('cat'); ?>" />
        </p>
		  
		  <p>
          <input id="<?php echo $this->get_field_id('thumbnail'); ?>" name="<?php echo $this->get_field_name('thumbnail'); ?>" type="checkbox" value="1" <?php checked( '1', $thumbnail ); ?>/>
          <label for="<?php echo $this->get_field_id('thumbnail'); ?>"><?php _e('Display post with thumbnails?','tatami'); ?></label> 
        </p>


       
		<?php
	}
} 

register_widget('tatami_recentposts');

/*-----------------------------------------------------------------------------------*/
/* Including Tatami Social Links Widget
/*-----------------------------------------------------------------------------------*/

 class tatami_sociallinks extends WP_Widget {

	function tatami_sociallinks() {
		$widget_ops = array('description' => 'Link to your social profile sites' , 'tatami');

		parent::WP_Widget(false, __('Tatami Social Links', 'tatami'),$widget_ops);
	}

	function widget($args, $instance) {  
		extract( $args );
		$title = $instance['title'];
		$twitter = $instance['twitter'];
		$facebook = $instance['facebook'];
		$googleplus = $instance['googleplus'];
		$appnet = $instance['appnet'];
		$flickr = $instance['flickr'];
		$instagram = $instance['instagram'];
		$picasa = $instance['picasa'];
		$fivehundredpx = $instance['fivehundredpx'];
		$youtube = $instance['youtube'];
		$vimeo = $instance['vimeo'];
		$dribbble = $instance['dribbble'];
		$ffffound = $instance['ffffound'];
		$pinterest = $instance['pinterest'];
		$behance = $instance['behance'];
		$deviantart = $instance['deviantart'];
		$squidoo = $instance['squidoo'];
		$slideshare = $instance['slideshare'];
		$lastfm = $instance['lastfm'];
		$grooveshark = $instance['grooveshark'];
		$soundcloud = $instance['soundcloud'];
		$foursquare = $instance['foursquare'];
		$github = $instance['github'];
		$linkedin = $instance['linkedin'];
		$xing = $instance['xing'];
		$wordpress = $instance['wordpress'];
		$tumblr = $instance['tumblr'];
		$rss = $instance['rss'];
		$rsscomments = $instance['rsscomments'];
		
		
		echo $before_widget; ?>
		<?php if($title != '')
			echo '<h3 class="widget-title">'.$title.'</h3>'; ?>

        <ul class="sociallinks">
			<?php 
			if($twitter != '') {
				echo '<li><a href="'.$twitter.'" class="twitter" title="Twitter">Twitter</a></li>';
			}
			?>

			<?php 
			if($facebook != '') {
				echo '<li><a href="'.$facebook.'" class="facebook" title="Facebook">Facebook</a></li>';
			}
			?>

			<?php 
			if($googleplus != '') {
				echo '<li><a href="'.$googleplus.'" class="googleplus" title="Google+">Google+</a></li>';
			}
			?>
			
			<?php 
			if($appnet != '') {
				echo '<li><a href="'.$appnet.'" class="appnet" title="App.net">App.net</a></li>';
			}
			?>

			<?php if($flickr != '') {
				echo '<li><a href="'.$flickr.'" class="flickr" title="Flickr">Flickr</a></li>';
			}
			?>

			<?php if($instagram != '') {
				echo '<li><a href="'.$instagram.'" class="instagram" title="Instagram">Instagram</a></li>';
			}
			?>

			<?php if($picasa != '') {
				echo '<li><a href="'.$picasa.'" class="picasa" title="Picasa">Picasa</a></li>';
			}
			?>

			<?php if($fivehundredpx != '') {
				echo '<li><a href="'.$fivehundredpx.'" class="fivehundredpx" title="500px">500px</a></li>';
			}
			?>	

			<?php if($youtube != '') {
				echo '<li><a href="'.$youtube.'" class="youtube" title="YouTube">YouTube</a></li>';
			}
			?>

			<?php if($vimeo != '') {
				echo '<li><a href="'.$vimeo.'" class="vimeo" title="Vimeo">Vimeo</a></li>';
			}
			?>

			<?php if($dribbble != '') {
				echo '<li><a href="'.$dribbble.'" class="dribbble" title="Dribbble">Dribbble</a></li>';
			}
			?>

			<?php if($ffffound != '') {
				echo '<li><a href="'.$ffffound.'" class="ffffound" title="Ffffound">Ffffound</a></li>';
			}
			?>

			<?php if($pinterest != '') {
				echo '<li><a href="'.$pinterest.'" class="pinterest" title="Pinterest">Pinterest</a></li>';
			}
			?>

			<?php if($behance != '') {
				echo '<li><a href="'.$behance.'" class="behance" title="Behance Network">Behance Network</a></li>';
			}
			?>

			<?php if($deviantart != '') {
				echo '<li><a href="'.$deviantart.'" class="deviantart" title="deviantART">deviantART</a></li>';
			}
			?>

			<?php if($squidoo != '') {
				echo '<li><a href="'.$squidoo.'" class="squidoo" title="Squidoo">Squidoo</a></li>';
			}
			?>

			<?php if($slideshare != '') {
				echo '<li><a href="'.$slideshare.'" class="slideshare" title="Slideshare">Slideshare</a></li>';
			}
			?>

			<?php if($lastfm != '') {
				echo '<li><a href="'.$lastfm.'" class="lastfm" title="Lastfm">Lastfm</a></li>';
			}
			?>

			<?php if($grooveshark != '') {
				echo '<li><a href="'.$grooveshark.'" class="grooveshark" title="Grooveshark">Grooveshark</a></li>';
			}
			?>

			<?php if($soundcloud != '') {
				echo '<li><a href="'.$soundcloud.'" class="soundcloud" title="Soundcloud">Soundcloud</a></li>';
			}
			?>

			<?php if($foursquare != '') {
				echo '<li><a href="'.$foursquare.'" class="foursquare" title="Foursquare">Foursquare</a></li>';
			}
			?>

			<?php if($github != '') {
				echo '<li><a href="'.$github.'" class="github" title="GitHub">GitHub</a></li>';
			}
			?>

			<?php if($linkedin != '') {
				echo '<li><a href="'.$linkedin.'" class="linkedin" title="LinkedIn">LinkedIn</a></li>';
			}
			?>

			<?php if($xing != '') {
				echo '<li><a href="'.$xing.'" class="xing" title="Xing">Xing</a></li>';
			}
			?>

			<?php if($wordpress != '') {
				echo '<li><a href="'.$wordpress.'" class="wordpress" title="WordPress">WordPress</a></li>';
			}
			?>

			<?php if($tumblr != '') {
				echo '<li><a href="'.$tumblr.'" class="tumblr" title="Tumblr">Tumblr</a></li>';
			}
			?>

			<?php if($rss != '') {
				echo '<li><a href="'.$rss.'" class="rss" title="RSS Feed">RSS Feed</a></li>';
			}
			?>

			<?php if($rsscomments != '') {
				echo '<li><a href="'.$rsscomments.'" class="rsscomments" title="RSS Comments">RSS Comments</a></li>';
			}
			?>

		</ul><!-- end .sociallinks -->

	   <?php			
	   echo $after_widget;
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) { 
		$title = esc_attr($instance['title']);
		$twitter = esc_attr($instance['twitter']);
		$facebook = esc_attr($instance['facebook']);
		$googleplus = esc_attr($instance['googleplus']);
		$appnet = esc_attr($instance['appnet']);
		$flickr = esc_attr($instance['flickr']);
		$instagram = esc_attr($instance['instagram']);
		$picasa = esc_attr($instance['picasa']);
		$fivehundredpx = esc_attr($instance['fivehundredpx']);
		$youtube = esc_attr($instance['youtube']);
		$vimeo = esc_attr($instance['vimeo']);
		$dribbble = esc_attr($instance['dribbble']);
		$ffffound = esc_attr($instance['ffffound']);
		$pinterest = esc_attr($instance['pinterest']);
		$behance = esc_attr($instance['behance']);
		$deviantart = esc_attr($instance['deviantart']);
		$squidoo = esc_attr($instance['squidoo']);
		$slideshare = esc_attr($instance['slideshare']);
		$lastfm = esc_attr($instance['lastfm']);
		$grooveshark = esc_attr($instance['grooveshark']);
		$soundcloud = esc_attr($instance['soundcloud']);
		$foursquare = esc_attr($instance['foursquare']);
		$github = esc_attr($instance['github']);
		$linkedin = esc_attr($instance['linkedin']);
		$xing = esc_attr($instance['xing']);
		$wordpress = esc_attr($instance['wordpress']);
		$tumblr = esc_attr($instance['tumblr']);
		$rss = esc_attr($instance['rss']);
		$rsscomments = esc_attr($instance['rsscomments']);
		
		?>

		 <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('twitter'); ?>" value="<?php echo $twitter; ?>" class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('facebook'); ?>" value="<?php echo $facebook; ?>" class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('googleplus'); ?>"><?php _e('Google+ URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('googleplus'); ?>" value="<?php echo $googleplus; ?>" class="widefat" id="<?php echo $this->get_field_id('googleplus'); ?>" />
        </p>
		  
		  <p>
            <label for="<?php echo $this->get_field_id('appnet'); ?>"><?php _e('App.net URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('appnet'); ?>" value="<?php echo $appnet; ?>" class="widefat" id="<?php echo $this->get_field_id('appnet'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('flickr'); ?>"><?php _e('Flickr URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('flickr'); ?>" value="<?php echo $flickr; ?>" class="widefat" id="<?php echo $this->get_field_id('flickr'); ?>" />
        </p>
		  
		 <p>
            <label for="<?php echo $this->get_field_id('instagram'); ?>"><?php _e('Instagram URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('instagram'); ?>" value="<?php echo $instagram; ?>" class="widefat" id="<?php echo $this->get_field_id('instagram'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('picasa'); ?>"><?php _e('Picasa URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('picasa'); ?>" value="<?php echo $picasa; ?>" class="widefat" id="<?php echo $this->get_field_id('picasa'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('fivehundredpx'); ?>"><?php _e('500px URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('fivehundredpx'); ?>" value="<?php echo $fivehundredpx; ?>" class="widefat" id="<?php echo $this->get_field_id('fivehundredpx'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('youtube'); ?>"><?php _e('YouTube URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('youtube'); ?>" value="<?php echo $youtube; ?>" class="widefat" id="<?php echo $this->get_field_id('youtube'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('vimeo'); ?>"><?php _e('Vimeo URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('vimeo'); ?>" value="<?php echo $vimeo; ?>" class="widefat" id="<?php echo $this->get_field_id('vimeo'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('dribbble'); ?>"><?php _e('Dribbble URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('dribbble'); ?>" value="<?php echo $dribbble; ?>" class="widefat" id="<?php echo $this->get_field_id('dribbble'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('ffffound'); ?>"><?php _e('Ffffound URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('ffffound'); ?>" value="<?php echo $ffffound; ?>" class="widefat" id="<?php echo $this->get_field_id('ffffound'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('pinterest'); ?>"><?php _e('Pinterest URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('pinterest'); ?>" value="<?php echo $pinterest; ?>" class="widefat" id="<?php echo $this->get_field_id('pinterest'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('behance'); ?>"><?php _e('Behance Network URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('behance'); ?>" value="<?php echo $behance; ?>" class="widefat" id="<?php echo $this->get_field_id('behance'); ?>" />
        </p>
		  
		 <p>
            <label for="<?php echo $this->get_field_id('deviantart'); ?>"><?php _e('deviantART URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('deviantart'); ?>" value="<?php echo $deviantart; ?>" class="widefat" id="<?php echo $this->get_field_id('deviantart'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('squidoo'); ?>"><?php _e('Squidoo URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('squidoo'); ?>" value="<?php echo $squidoo; ?>" class="widefat" id="<?php echo $this->get_field_id('squidoo'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('slideshare'); ?>"><?php _e('Slideshare URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('slideshare'); ?>" value="<?php echo $slideshare; ?>" class="widefat" id="<?php echo $this->get_field_id('slideshare'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('lastfm'); ?>"><?php _e('Last.fm URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('lastfm'); ?>" value="<?php echo $lastfm; ?>" class="widefat" id="<?php echo $this->get_field_id('lastfm'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('grooveshark'); ?>"><?php _e('Grooveshark URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('grooveshark'); ?>" value="<?php echo $grooveshark; ?>" class="widefat" id="<?php echo $this->get_field_id('grooveshark'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('soundcloud'); ?>"><?php _e('Soundcloud URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('soundcloud'); ?>" value="<?php echo $soundcloud; ?>" class="widefat" id="<?php echo $this->get_field_id('soundcloud'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('foursquare'); ?>"><?php _e('Foursquare URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('foursquare'); ?>" value="<?php echo $foursquare; ?>" class="widefat" id="<?php echo $this->get_field_id('foursquare'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('github'); ?>"><?php _e('GitHub URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('github'); ?>" value="<?php echo $github; ?>" class="widefat" id="<?php echo $this->get_field_id('github'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('Linkedin URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('linkedin'); ?>" value="<?php echo $linkedin; ?>" class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('xing'); ?>"><?php _e('Xing URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('xing'); ?>" value="<?php echo $xing; ?>" class="widefat" id="<?php echo $this->get_field_id('xing'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('wordpress'); ?>"><?php _e('WordPress URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('wordpress'); ?>" value="<?php echo $wordpress; ?>" class="widefat" id="<?php echo $this->get_field_id('wordpress'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('tumblr'); ?>"><?php _e('Tumblr URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('tumblr'); ?>" value="<?php echo $tumblr; ?>" class="widefat" id="<?php echo $this->get_field_id('tumblr'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e('RSS-Feed URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('rss'); ?>" value="<?php echo $rss; ?>" class="widefat" id="<?php echo $this->get_field_id('rss'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('rsscomments'); ?>"><?php _e('RSS for Comments URL:','tatami'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('rsscomments'); ?>" value="<?php echo $rsscomments; ?>" class="widefat" id="<?php echo $this->get_field_id('rsscomments'); ?>" />
        </p>
       
		<?php
	}
} 

register_widget('tatami_sociallinks');