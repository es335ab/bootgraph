<?php
/**
 * The template for displaying Comments.
 *
 * @package Tatami 
 * @since Tatami 1.0
 */
?>

	<div id="comments" class="comments-area">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'tatami' ); ?></p>
	</div><!-- #comments .comments-area -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title">
			<?php
				printf( _n( 'Comment (1)', 'Comments (%1$s)', get_comments_number(), 'tatami' ),
					number_format_i18n( get_comments_number() ) );
			?>
			<?php if ( comments_open() ) : ?>
			<span><a href="#reply-title"><?php _e( 'Write a comment', 'tatami' ); ?></a></span>
			<?php endif; // comments_open() ?>
		</h3>

		<ol class="commentlist">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use tatami_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define tatami_comment() and that will be used instead.
				 * See tatami_comment() in functions.php for more.
				 */
				wp_list_comments( array( 'callback' => 'tatami_comment' ) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav">
			<div class="nav-previous"><?php previous_comments_link( __( ' &larr;  Older Comments', 'tatami' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments  &rarr; ', 'tatami' ) ); ?></div>
		</nav><!-- end #comment-nav -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are no comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'tatami' ); ?></p>
	<?php endif; ?>

	<?php comment_form (
		array(
			'comment_notes_before' =>__( '<p class="comment-note">Required fields are marked <span class="required">*</span>.</p>', 'tatami'),
			'comment_notes_after' =>(''),
			'comment_field'  => '<p class="comment-form-comment"><label for="comment">' . _x( 'Message <span class="required">*</span>', 'noun', 'tatami' ) . 			'</label><br/><textarea id="comment" name="comment" rows="8"></textarea></p>',
			'label_submit'	=> __( 'Send Comment', 'tatami' ))
		); 
	?>

</div><!-- #comments .comments-area -->
