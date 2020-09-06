<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage FastShop
 * @since 1.0
 * @version 1.0
 */

?>
<?php fastshop_get_footer(); ?>
<a href="javascript:void(0);" class="backtotop">
    <i class="fa fa-angle-up"></i>
</a>
<?php get_template_part( 'template-parts/popup', 'content' ); ?>
<?php wp_footer(); ?>
</body>
</html>
