<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage FastShop
 * @since 1.0
 * @version 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<?php
$comment_field = '<p class="comment-reply-content"><label>' . esc_html__( 'Your Comment:', 'fastshop' ) . ' <span class="required">*</span></label><textarea class="input-form" id="comment" name="comment" cols="45" rows="6" aria-required="true">' .
	'</textarea></p>';
$fields        = array(
	'name'  => '<p class="comment-reply-content"><label>' . esc_html__( 'Your Name:', 'fastshop' ) . ' <span class="required">*</span></label><input type="text" name="author" id="name" class="input-form name" /></p>',
	'email' => '<p class="comment-reply-content"><label>' . esc_html__( 'Your Email:', 'fastshop' ) . ' <span class="required">*</span></label><input type="text" name="email" id="email" class="input-form email" /></p>',
);


$comment_form_args = array(
	'class_submit'  => 'button',
	'comment_field' => $comment_field,
	'fields'        => $fields,
	'label_submit'  => esc_html__( 'submit', 'fastshop' ),
	'title_reply'   => esc_html__( 'Leave your comment', 'fastshop' ),
);
?>
<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
        <h2 class="comments-title">
			<?php
			$comments_number = get_comments_number();
			if ( '1' === $comments_number ) {
				/* translators: %s: post title */
				printf( _x( 'One Reply to &ldquo;%s&rdquo;', '', 'fastshop' ), get_the_title() );
			} else {
				printf(
				/* translators: 1: number of comments, 2: post title */
					_nx(
						'%1$s Reply to &ldquo;%2$s&rdquo;',
						'%1$s Replies to &ldquo;%2$s&rdquo;',
						$comments_number,
						'',
						'fastshop'
					),
					number_format_i18n( $comments_number ),
					get_the_title()
				);
			}
			?>
        </h2>

        <ol class="comment-list">
			<?php
			wp_list_comments( array(
					'avatar_size' => 60,
					'style'       => 'ol',
					'short_ping'  => true,
					'reply_text'  => esc_html__( 'Comment Reply', 'fastshop' ),
				)
			);
			?>
        </ol>

		<?php the_comments_pagination( array(
			'screen_reader_text' => '',
			'prev_text'          => '<span class="screen-reader-text">' . esc_html__( 'Previous', 'fastshop' ) . '</span>',
			'next_text'          => '<span class="screen-reader-text">' . esc_html__( 'Next', 'fastshop' ) . '</span>',
		)
		);

	endif; // Check for have_comments().

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( !comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

        <p class="no-comments"><?php echo esc_html__( 'Comments are closed.', 'fastshop' ); ?></p>
		<?php
	endif;

	comment_form( $comment_form_args );
	?>

</div><!-- #comments -->
