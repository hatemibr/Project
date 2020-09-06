<?php
/**
 * Template Name: Box Page
 *
 * @package WordPress
 * @subpackage fastshop
 * @since fastshop 1.0
 */
get_header();
?>
	<div class="box-template">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
			?>
			<?php the_content( );?>
			<?php
			// End the loop.
		endwhile;
		?>
	</div>
<?php
get_footer();