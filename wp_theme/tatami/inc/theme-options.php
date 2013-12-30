<?php
/**
 * Tatami Theme Options
 *
 * @subpackage Tatami
 * @since Tatami 1.0
 */

/*-----------------------------------------------------------------------------------*/
/* Properly enqueue styles and scripts for our theme options page.
/*
/* This function is attached to the admin_enqueue_scripts action hook.
/*
/* @param string $hook_suffix The action passes the current page to the function.
/* We don't do anything if we're not on our theme options page.
/*-----------------------------------------------------------------------------------*/

function tatami_admin_enqueue_scripts( $hook_suffix ) {
	if ( $hook_suffix != 'appearance_page_theme_options' )
		return;

	wp_enqueue_style( 'tatami-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2012-07-22' );
	wp_enqueue_script( 'tatami-theme-options', get_template_directory_uri() . '/inc/theme-options.js', array( 'farbtastic' ), '2012-07-22' );
	wp_enqueue_style( 'farbtastic' );
}
add_action( 'admin_enqueue_scripts', 'tatami_admin_enqueue_scripts' );

/*-----------------------------------------------------------------------------------*/
/* Register the form setting for our tatami_options array.
/*
/* This function is attached to the admin_init action hook.
/*
/* This call to register_setting() registers a validation callback, tatami_theme_options_validate(),
/* which is used when the option is saved, to ensure that our option values are complete, properly
/* formatted, and safe.
/*
/* We also use this function to add our theme option if it doesn't already exist.
/*-----------------------------------------------------------------------------------*/

function tatami_theme_options_init() {

	// If we have no options in the database, let's add them now.
	if ( false === tatami_get_theme_options() )
		add_option( 'tatami_theme_options', tatami_get_default_theme_options() );

	register_setting(
		'tatami_options',       // Options group, see settings_fields() call in theme_options_render_page()
		'tatami_theme_options', // Database option, see tatami_get_theme_options()
		'tatami_theme_options_validate' // The sanitization callback, see tatami_theme_options_validate()
	);
}
add_action( 'admin_init', 'tatami_theme_options_init' );

/*-----------------------------------------------------------------------------------*/
/* Add our theme options page to the admin menu.
/* 
/* This function is attached to the admin_menu action hook.
/*-----------------------------------------------------------------------------------*/

function tatami_theme_options_add_page() {
	add_theme_page(
		__( 'Theme Options', 'tatami' ), // Name of page
		__( 'Theme Options', 'tatami' ), // Label in menu
		'edit_theme_options',                  // Capability required
		'theme_options',                       // Menu slug, used to uniquely identify the page
		'theme_options_render_page'            // Function that renders the options page
	);
}
add_action( 'admin_menu', 'tatami_theme_options_add_page' );


/*-----------------------------------------------------------------------------------*/
/* Returns an array of layout options registered for Tatami
/*-----------------------------------------------------------------------------------*/
function tatami_thumbnails() {
	$thumbnail_options = array(
		'thumbnail-bigtop' => array(
			'value' => 'thumbnail-bigtop',
			'label' => __( 'Fullwidth Thumbnail above Headline', 'tatami' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/thumbnail-bigtop.png',
		),
		'thumbnail-big' => array(
			'value' => 'thumbnail-big',
			'label' => __( 'Big Thumbnail below Headline', 'tatami' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/thumbnail-big.png',
		),
		'thumbnail-small' => array(
			'value' => 'thumbnail-small',
			'label' => __( 'Small Thumbnail', 'tatami' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/thumbnail-small.png',
		),
	);

	return apply_filters( 'tatami_thumbnails', $thumbnail_options );
}

/*-----------------------------------------------------------------------------------*/
/* Returns the default options for Tatami
/*-----------------------------------------------------------------------------------*/

function tatami_get_default_theme_options() {
	$default_theme_options = array(
		'link_color'   => '#69A6CC',
		'linkhover_color'   => '#397CA7',
		'footerbg_color'   => '#151515',
		'mobileheader_color'   => '',
		'dark-mobileheader' => '',
		'theme_thumbnail' => 'thumbnail-bigtop',
		'custom_logo' => '',
		'custom_introtext' => '',
		'custom_footertext' => '',
		'custom_authorlinks' => '',
		'custom_favicon' => '',
		'custom_apple_icon' => '',
		'show-excerpt' => '',
		'share-posts' => '',
		'share-singleposts' => '',
		'share-pages' => '',
		'custom-css' => '',
	);

	return apply_filters( 'tatami_default_theme_options', $default_theme_options );
}

/*-----------------------------------------------------------------------------------*/
/* Returns the options array for Tatami
/*-----------------------------------------------------------------------------------*/

function tatami_get_theme_options() {
	return get_option( 'tatami_theme_options' );
}

/*-----------------------------------------------------------------------------------*/
/* Returns the options array for Tatami
/*-----------------------------------------------------------------------------------*/

function theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __( '%s Theme Options', 'tatami' ), wp_get_theme() ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'tatami_options' );
				$options = tatami_get_theme_options();
				$default_options = tatami_get_default_theme_options();
			?>

			<table class="form-table">
			<h3 style="margin-top:30px;"><?php _e( 'Custom Colors', 'tatami' ); ?></h3>
				<tr valign="top"><th scope="row"><?php _e( 'Custom Link Color', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Link Color', 'tatami' ); ?></span></legend>
							 <input type="text" name="tatami_theme_options[link_color]" value="<?php echo esc_attr( $options['link_color'] ); ?>" id="link-color" />
							<div style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;" id="colorpicker1"></div>
							<br />
							<small class="description"><?php printf( __( 'Choose your custom link color, the default color is: %s. Do not forget to include the # before the color value.', 'tatami' ), $default_options['link_color'] ); ?></small>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Custom Link Hover Color', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Link Hover Color', 'tatami' ); ?></span></legend>
							 <input type="text" name="tatami_theme_options[linkhover_color]" value="<?php echo esc_attr( $options['linkhover_color'] ); ?>" id="linkhover-color" />
							<div style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;" id="colorpicker3"></div>
							<br />
							<small class="description"><?php printf( __( 'Choose your custom link hover color, the default color is: %s.', 'tatami' ), $default_options['linkhover_color'] ); ?></small>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top"><th scope="row"><?php _e( 'Custom Footer Background Color', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Footer Background Color', 'tatami' ); ?></span></legend>
							 <input type="text" name="tatami_theme_options[footerbg_color]" value="<?php echo esc_attr( $options['footerbg_color'] ); ?>" id="footerbg-color" />
							<div style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;" id="colorpicker2"></div>
							<br />
							<small class="description"><?php printf( __( 'Choose your custom footer background color, the default color is: %s.', 'tatami' ), $default_options['footerbg_color'] ); ?></small>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top"><th scope="row"><?php _e( 'Custom Mobile Header Background Color', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Mobile Header Background Color', 'tatami' ); ?></span></legend>
							 <input type="text" name="tatami_theme_options[mobileheader_color]" value="<?php echo esc_attr( $options['mobileheader_color'] ); ?>" id="mobileheader-color" />
							<div style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;" id="colorpicker4"></div>
							<br />
							<small class="description"><?php printf( __( 'Choose your custom mobile header background color, the default color is: %s.', 'tatami' ), $default_options['mobileheader_color'] ); ?></small>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top"><th scope="row"><?php _e( 'Dark Mobile Header Title + Icons', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Dark Mobile Header Title + Icons', 'tatami' ); ?></span></legend>
							<input id="tatami_theme_options[dark-mobileheader]" name="tatami_theme_options[dark-mobileheader]" type="checkbox" value="1" <?php checked( '1', $options['dark-mobileheader'] ); ?> />
							<label class="description" for="tatami_theme_options[dark-mobileheader]"><?php _e( 'Check this box to show a dark color for the mobile header title and menu icons.', 'tatami' ); ?></label>
						</fieldset>
					</td>
				</tr>
				</table>
				
			<table class="form-table">
			<h3 style="margin-top:30px;"><?php _e( 'Post Thumbnail Option', 'tatami' ); ?></h3>
				<tr valign="top" class="image-radio-option"><th scope="row"><?php _e( 'Thumbnail Placement', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Thumbnail Placement', 'tatami' ); ?></span></legend>
						<?php
							foreach ( tatami_thumbnails() as $thumbnail ) {
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="tatami_theme_options[theme_thumbnail]" value="<?php echo esc_attr( $thumbnail['value'] ); ?>" <?php checked( $options['theme_thumbnail'], $thumbnail['value'] ); ?> />
									<span>
										<img src="<?php echo esc_url( $thumbnail['thumbnail'] ); ?>"/>
										<?php echo $thumbnail['label']; ?>
									</span>
								</label>
								</div>
								<?php
							}
						?>
						</fieldset>
					</td>
				</tr>
			</table>

				<table class="form-table">
				<h3 style="margin-top:30px;"><?php _e( 'Logo, Post Excerpts & Custom Text', 'tatami' ); ?></h3>
				<tr valign="top"><th scope="row"><?php _e( 'Custom Logo', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Logo image', 'tatami' ); ?></span></legend>
							<input class="regular-text" type="text" name="tatami_theme_options[custom_logo]" value="<?php echo esc_attr( $options['custom_logo'] ); ?>" />
						<br/><label class="description" for="tatami_theme_options[custom_logo]"><?php _e('Upload your own logo image using the ', 'tatami'); ?><a href="<?php echo home_url(); ?>/wp-admin/media-new.php" target="_blank"><?php _e('WordPress Media Uploader', 'tatami'); ?></a><?php _e('. Then copy your logo image file URL and insert the URL here.', 'tatami'); ?></label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Post Excerpts', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Post Excerpts', 'tatami' ); ?></span></legend>
							<input id="tatami_theme_options[show-excerpt]" name="tatami_theme_options[show-excerpt]" type="checkbox" value="1" <?php checked( '1', $options['show-excerpt'] ); ?> />
							<label class="description" for="tatami_theme_options[show-excerpt]"><?php _e( 'Check this box to show automatic post excerpts. With this option you will not need to add the more tag in posts.', 'tatami' ); ?></label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Custom Header Intro Text', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Header Intro Text', 'tatami' ); ?></span></legend>
							<textarea id="tatami_theme_options[custom_introtext]" class="small-text" cols="120" rows="3" name="tatami_theme_options[custom_introtext]"><?php echo esc_textarea( $options['custom_introtext'] ); ?></textarea>
						<br/><label class="description" for="tatami_theme_options[custom_introtext]"><?php _e( 'Customize the header intro text (Standard HTML is allowed).', 'tatami' ); ?></label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Custom Footer Text', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Footer text', 'tatami' ); ?></span></legend>
							<textarea id="tatami_theme_options[custom_footertext]" class="small-text" cols="120" rows="3" name="tatami_theme_options[custom_footertext]"><?php echo esc_textarea( $options['custom_footertext'] ); ?></textarea>
						<br/><label class="description" for="tatami_theme_options[custom_footertext]"><?php _e( 'Customize the footer credit text (Standard HTML is allowed).', 'tatami' ); ?></label>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top"><th scope="row"><?php _e( 'Custom Author Links', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Author Links', 'tatami' ); ?></span></legend>
							<textarea id="tatami_theme_options[custom_authorlinks]" class="small-text" cols="120" rows="3" name="tatami_theme_options[custom_authorlinks]"><?php echo esc_textarea( $options['custom_authorlinks'] ); ?></textarea>
						<br/><label class="description" for="tatami_theme_options[custom_authorlinks]"><?php _e( 'Add custom "Find me on" links to your author bio on single posts (Standard HTML is allowed).', 'tatami' ); ?></label>
						</fieldset>
					</td>
				</tr>

				</table>
				
				<table class="form-table">

				<h3 style="margin-top:30px;"><?php _e( 'Favicon and Apple Touch Icon', 'tatami' ); ?></h3>

				<tr valign="top"><th scope="row"><?php _e( 'Custom Favicon', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Favicon', 'tatami' ); ?></span></legend>
							<input class="regular-text" type="text" name="tatami_theme_options[custom_favicon]" value="<?php echo esc_attr( $options['custom_favicon'] ); ?>" />
						<br/><label class="description" for="tatami_theme_options[custom_favicon]"><?php _e( 'Create a <strong>16x16px</strong> image and generate a .ico favicon using a favicon online generator. Now upload your favicon to your themes folder (via FTP) and enter your Favicon URL here (the URL path should be similar to: yourdomain.com/wp-content/themes/tatami/favicon.ico).', 'tatami' ); ?></label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Custom Apple Touch Icon', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Apple Touch Icon', 'tatami' ); ?></span></legend>
							<input class="regular-text" type="text" name="tatami_theme_options[custom_apple_icon]" value="<?php echo esc_attr( $options['custom_apple_icon'] ); ?>" />
						<br/><label class="description" for="tatami_theme_options[custom_apple_icon]"><?php _e('Create a <strong>128x128px png</strong> image for your webclip icon. Upload your image using the ', 'tatami'); ?><a href="<?php echo home_url(); ?>/wp-admin/media-new.php" target="_blank"><?php _e('WordPress Media Uploader', 'tatami'); ?></a><?php _e('. Now copy the image file URL and insert the URL here.', 'tatami'); ?></label>
						</fieldset>
					</td>
				</tr>

				</table>

				<table class="form-table">

				<h3 style="margin-top:30px;"><?php _e( 'Share Buttons', 'tatami' ); ?></h3>

				<tr valign="top"><th scope="row"><?php _e( 'Share option for posts', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Share option for posts', 'tatami' ); ?></span></legend>
							<input id="tatami_theme_options[share-posts]" name="tatami_theme_options[share-posts]" type="checkbox" value="1" <?php checked( '1', $options['share-posts'] ); ?> />
							<label class="description" for="tatami_theme_options[share-posts]"><?php _e( 'Check this box to include share buttons (for Twitter, Facebook, Google+) on your blogs front page and on single post pages.', 'tatami' ); ?></label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Share option on single posts only', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Share option on single posts only', 'tatami' ); ?></span></legend>
							<input id="tatami_theme_options[share-singleposts]" name="tatami_theme_options[share-singleposts]" type="checkbox" value="1" <?php checked( '1', $options['share-singleposts'] ); ?> />
							<label class="description" for="tatami_theme_options[share-singleposts]"><?php _e( 'Check this box to include the share post buttons <strong>only</strong> on single posts (below the post content).', 'tatami' ); ?></label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Share option for pages', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Share option for pages', 'tatami' ); ?></span></legend>
							<input id="tatami_theme_options[share-pages]" name="tatami_theme_options[share-pages]" type="checkbox" value="1" <?php checked( '1', $options['share-pages'] ); ?> />
							<label class="description" for="tatami_theme_options[share-pages]"><?php _e( 'Check this box to also include the share buttons on pages.', 'tatami' ); ?></label>
						</fieldset>
					</td>
				</tr>

				</table>
				
				<table class="form-table">

				<h3 style="margin-top:30px;"><?php _e( 'Custom CSS', 'tatami' ); ?></h3>
				
				<tr valign="top"><th scope="row"><?php _e( 'Include Custom CSS', 'tatami' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Include Custom CSS', 'tatami' ); ?></span></legend>
							<textarea id="tatami_theme_options[custom-css]" class="small-text" style="font-family: monospace;" cols="120" rows="10" name="tatami_theme_options[custom-css]"><?php echo esc_textarea( $options['custom-css'] ); ?></textarea>
						<br/><label class="description" for="tatami_theme_options[custom-css]"><?php _e( 'Include custom CSS styles, use !important to overwrite existing styles.', 'tatami' ); ?></label>
						</fieldset>
					</td>
				</tr>

				</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/*-----------------------------------------------------------------------------------*/
/* Sanitize and validate form input. Accepts an array, return a sanitized array.
/*-----------------------------------------------------------------------------------*/

function tatami_theme_options_validate( $input ) {
	global $layout_options, $font_options;

	// Link color must be 3 or 6 hexadecimal characters
	if ( isset( $input['link_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['link_color'] ) )
			$output['link_color'] = '#' . strtolower( ltrim( $input['link_color'], '#' ) );

	// Link hover color must be 3 or 6 hexadecimal characters
	if ( isset( $input['linkhover_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['linkhover_color'] ) )
			$output['linkhover_color'] = '#' . strtolower( ltrim( $input['linkhover_color'], '#' ) );

	// Footer background color must be 3 or 6 hexadecimal characters
	if ( isset( $input['footerbg_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['footerbg_color'] ) )
			$output['footerbg_color'] = '#' . strtolower( ltrim( $input['footerbg_color'], '#' ) );
			
	// Mobile header background color must be 3 or 6 hexadecimal characters
	if ( isset( $input['mobileheader_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['mobileheader_color'] ) )
			$output['mobileheader_color'] = '#' . strtolower( ltrim( $input['mobileheader_color'], '#' ) );

	// Theme thumbnail must be in our array of theme thumbnail options
	if ( isset( $input['theme_thumbnail'] ) && array_key_exists( $input['theme_thumbnail'], tatami_thumbnails() ) )
		$output['theme_thumbnail'] = $input['theme_thumbnail'];

	// Text options must be safe text with no HTML tags
	$input['custom_logo'] = wp_filter_nohtml_kses( $input['custom_logo'] );
	$input['custom_favicon'] = wp_filter_nohtml_kses( $input['custom_favicon'] );
	$input['custom_apple_icon'] = wp_filter_nohtml_kses( $input['custom_apple_icon'] );

	// checkbox values are either 0 or 1
	if ( ! isset( $input['share-posts'] ) )
		$input['share-posts'] = null;
	$input['share-posts'] = ( $input['share-posts'] == 1 ? 1 : 0 );

	if ( ! isset( $input['share-singleposts'] ) )
		$input['share-singleposts'] = null;
	$input['share-singleposts'] = ( $input['share-singleposts'] == 1 ? 1 : 0 );

	if ( ! isset( $input['share-pages'] ) )
		$input['share-pages'] = null;
	$input['share-pages'] = ( $input['share-pages'] == 1 ? 1 : 0 );

	if ( ! isset( $input['show-excerpt'] ) )
		$input['show-excerpt'] = null;
	$input['show-excerpt'] = ( $input['show-excerpt'] == 1 ? 1 : 0 );
	
	if ( ! isset( $input['dark-mobileheader'] ) )
		$input['dark-mobileheader'] = null;
	$input['dark-mobileheader'] = ( $input['dark-mobileheader'] == 1 ? 1 : 0 );

	return $input;
}

/*-----------------------------------------------------------------------------------*/
/* Add a style block to the theme for the current link color.
/* 
/* This function is attached to the wp_head action hook.
/*-----------------------------------------------------------------------------------*/

function tatami_print_link_color_style() {
	$options = tatami_get_theme_options();
	$link_color = $options['link_color'];

	$default_options = tatami_get_default_theme_options();

	// Don't do anything if the current link color is the default.
	if ( $default_options['link_color'] == $link_color )
		return;
?>
<style type="text/css">
/* Custom Link Color */
a, .site-title h2.description a, .widget_tatami_recentposts ul li h3.recentposts-title a, .bwp-rc-widget li.sidebar-comment a, .entry-header h2.entry-title a:hover, .main-nav .menu-item a:hover, .main-nav ul li a:hover, .widget_twitter ul.tweets li a, ul.latest-posts-list li a:hover, ul.monthly-archive-list li a:hover, .entry-content a.more-link:hover {color:<?php echo $link_color; ?>;}
.off-canvas-nav, .js .off-canvas-nav, input#submit, input.wpcf7-submit, .flickr_badge_wrapper .flickr-bottom a, .jetpack_subscription_widget form input[type="submit"] {background:<?php echo $link_color; ?>;}
.entry-content blockquote, #comments blockquote {border-left:6px solid <?php echo $link_color; ?>;}
.format-link .entry-content a.link {background:<?php echo $link_color; ?> url(<?php echo get_template_directory_uri(); ?>/images/link-arrow-small.png) right 50% no-repeat;}
@media screen and (min-width: 768px) {
.format-link .entry-content a.link {background:<?php echo $link_color; ?>  url(<?php echo get_template_directory_uri(); ?>/images/link-arrow-big.png) 101% 50% no-repeat;}
}
@media screen and (min-width: 1024px) {
.template-left-sidebar [role="banner"] {background: <?php echo $link_color; ?>;}
}
@media screen and (min-width: 1260px) {
.format-link .entry-content a.link {background:<?php echo $link_color; ?>  url(<?php echo get_template_directory_uri(); ?>/images/link-arrow-big.png) right 50% no-repeat;}
}
</style>
<?php
}
add_action( 'wp_head', 'tatami_print_link_color_style' );

/*-----------------------------------------------------------------------------------*/
/* Add a style block to the theme for the currentlink hover color.
/* 
/* This function is attached to the wp_head action hook.
/*-----------------------------------------------------------------------------------*/

function tatami_print_linkhover_color_style() {
	$options = tatami_get_theme_options();
	$linkhover_color = $options['linkhover_color'];

	$default_options = tatami_get_default_theme_options();

	// Don't do anything if the current link hover color is the default.
	if ( $default_options['linkhover_color'] == $linkhover_color )
		return;
?>
<style type="text/css">
/* Custom Link Hover Color */
a:hover, .site-title h2.description a:hover, .widget_tatami_recentposts ul li h3.recentposts-title a:hover, .bwp-rc-widget li.sidebar-comment a:hover, .widget_twitter ul.tweets li a:hover, #comments .comment-content ul li.comment-author a:hover {color:<?php echo $linkhover_color; ?>;}
input#submit:hover, input.wpcf7-submit:hover, .flickr_badge_wrapper .flickr-bottom a:hover,
.jetpack_subscription_widget form input[type="submit"]:hover {background:<?php echo $linkhover_color; ?>;}
.format-link .entry-content a.link:hover {background:<?php echo $linkhover_color; ?> url(<?php echo get_template_directory_uri(); ?>/images/link-arrow-small.png) right 50% no-repeat;}
@media screen and (min-width: 768px) {
.format-link .entry-content a.link:hover {background:<?php echo $linkhover_color; ?> url(<?php echo get_template_directory_uri(); ?>/images/link-arrow-big.png) 101% 50% no-repeat;}
}
@media screen and (min-width: 1260px) {
.format-link .entry-content a.link:hover {background:<?php echo $linkhover_color; ?> url(<?php echo get_template_directory_uri(); ?>/images/link-arrow-big.png) right 50% no-repeat;}
}
</style>
<?php
}
add_action( 'wp_head', 'tatami_print_linkhover_color_style' );

/*-----------------------------------------------------------------------------------*/
/* Add a style block to the theme for the current footer background color.
/* 
/* This function is attached to the wp_head action hook.
/*-----------------------------------------------------------------------------------*/

function tatami_print_footerbg_color_style() {
	$options = tatami_get_theme_options();
	$footerbg_color = $options['footerbg_color'];

	$default_options = tatami_get_default_theme_options();

	// Don't do anything if the current footer background color is the default.
	if ( $default_options['footerbg_color'] == $footerbg_color )
		return;
?>
<style type="text/css">
/* Custom Footer Bg Color */
.site-footer {background:<?php echo $footerbg_color; ?>;}
</style>
<?php
}
add_action( 'wp_head', 'tatami_print_footerbg_color_style' );

/*-----------------------------------------------------------------------------------*/
/* Add a style block to the theme for the current mobile header background color.
/* 
/* This function is attached to the wp_head action hook.
/*-----------------------------------------------------------------------------------*/

function tatami_print_mobileheader_color_style() {
	$options = tatami_get_theme_options();
	$mobileheader_color = $options['mobileheader_color'];

	$default_options = tatami_get_default_theme_options();

	// Don't do anything if the current mobile header background color is the default.
	if ( $default_options['mobileheader_color'] == $mobileheader_color )
		return;
?>
<style type="text/css">
/* Custom Mobile Header Bg Color */
.off-canvas-nav, .js .off-canvas-nav {background:<?php echo $mobileheader_color; ?>;}
.js .menu-button {background:<?php echo $mobileheader_color; ?> url(<?php echo get_template_directory_uri(); ?>/images/menu-btn.png) 50% 0 no-repeat;}
.js .sidebar-button {background:<?php echo $mobileheader_color; ?> url(<?php echo get_template_directory_uri(); ?>/images/sidebar-btn.png) 50% 0 no-repeat;}
@media only screen and (-moz-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (-webkit-min-device-pixel-ratio: 1.5), only screen and (min-device-pixel-ratio: 1.5) {
.js .menu-button {background:<?php echo $mobileheader_color; ?> url(<?php echo get_template_directory_uri(); ?>/images/x2/menu-btn.png) 50% 0 no-repeat; background-size: 60px 60px;}
.js .sidebar-button {background:<?php echo $mobileheader_color; ?> url(<?php echo get_template_directory_uri(); ?>/images/x2/sidebar-btn.png) 50% 0 no-repeat; background-size: 60px 60px;}
}
</style>
<?php
}
add_action( 'wp_head', 'tatami_print_mobileheader_color_style' );


/*-----------------------------------------------------------------------------------*/
/* Add a style block to the theme for customm css.
/* 
/* This function is attached to the wp_head action hook.
/*-----------------------------------------------------------------------------------*/

function tatami_print_customcss_style() {
	$options = tatami_get_theme_options();
	$customcss = $options['custom-css'];

	$default_options = tatami_get_default_theme_options();

	// Don't do anything if the custom css box is empty.
	if ( $default_options['custom-css'] == $customcss )
		return;
?>
<style type="text/css">
/* Custom CSS */
<?php echo $customcss; ?>
</style>
<?php
}
add_action( 'wp_head', 'tatami_print_customcss_style' );


/*-----------------------------------------------------------------------------------*/
/* Add a style block to the theme for a dark mobile header title and icons.
/* 
/* This function is attached to the wp_head action hook.
/*-----------------------------------------------------------------------------------*/

function tatami_print_darkmobileheader_style() {
	$options = tatami_get_theme_options();
	$darkmobileheader = $options['dark-mobileheader'];

	$default_options = tatami_get_default_theme_options();

	// Don't do anything if the custom css box is empty.
	if ( $default_options['dark-mobileheader'] == $darkmobileheader )
		return;
?>
<style type="text/css">
/* Dark Mobile Header Title and Icons */
.site-title h1.title a {color: #151515;}
.js .menu-button {border-right: 1px solid #151515;}
.js .sidebar-button {border-left: 1px solid #151515;}
.js .menu-button {background: url(<?php echo get_template_directory_uri(); ?>/images/menu-btn-dark.png) 50% 0 no-repeat;}
.js .sidebar-button {background: url(<?php echo get_template_directory_uri(); ?>/images/sidebar-btn-dark.png) 50% 0 no-repeat;}
@media only screen and (-moz-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (-webkit-min-device-pixel-ratio: 1.5), only screen and (min-device-pixel-ratio: 1.5) {
.js .menu-button {background: url(<?php echo get_template_directory_uri(); ?>/images/x2/menu-btn-dark.png) 50% 0 no-repeat; background-size: 60px 60px;}
.js .sidebar-button {background: url(<?php echo get_template_directory_uri(); ?>/images/x2/sidebar-btn-dark.png) 50% 0 no-repeat; background-size: 60px 60px;}
}
</style>
<?php
}
add_action( 'wp_head', 'tatami_print_darkmobileheader_style' );

