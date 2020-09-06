<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage FastShop
 * @since 1.0
 * @version 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php get_template_part( 'template-parts/header', 'sticky' ); ?>
<div class="body-overlay"></div>
<div id="box-mobile-menu" class="box-mobile-menu full-height">
    <a href="javascript:void(0);" id="back-menu" class="back-menu"><i class="pe-7s-angle-left"></i></a>
    <span class="box-title"><?php echo esc_html__( 'Menu', 'fastshop' ); ?></span>
    <a href="javascript:void(0);" class="close-menu"><i class="pe-7s-close"></i></a>
    <div class="box-inner"></div>
</div>
<?php fastshop_get_header(); ?>