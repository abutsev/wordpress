<?php
/**
 * Bistro functions and definitions
 *
 * @package Bistro
 */

/*
** Parent stylesheet
*/
function bistro_enqueue_styles() {

    wp_enqueue_style( 'bistro-parent-style', get_template_directory_uri() . '/style.css' );
    
}
add_action( 'wp_enqueue_scripts', 'bistro_enqueue_styles' );

/*
** Bistro setup
*/
function bistro_setup() {

	//Translations
	load_theme_textdomain( 'bistro', get_stylesheet_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'bistro_setup' );	

/*
** Custom header args
*/
function bistro_custom_header() {
	$header_args = array(
		'default-image'          => get_stylesheet_directory_uri() . '/images/header.jpg',
		'default-text-color'     => '000000',
		'width'                  => 1920,
		'height'                 => 600,
		'flex-height'            => true,
		'wp-head-callback'       => 'sydney_header_style',
		'admin-head-callback'    => 'sydney_admin_header_style',
		'admin-preview-callback' => 'sydney_admin_header_image',
	);
	return $header_args;
}
add_filter('sydney_custom_header_args', 'bistro_custom_header');

/*
** Widgets
*/
function bistro_widgets_init() {
	register_widget( 'Bistro_Menu_A' );
	register_widget( 'Bistro_Menu_B' );
}
add_action( 'widgets_init', 'bistro_widgets_init' );

/*
** Filter the title for the restaurant archive
*/
function bistro_menu_archive_title($title) {
	if ( is_post_type_archive('restaurant_item') ) {
        $title = post_type_archive_title( '', false );
    }
 	if ( is_tax('restaurant_tag') ) {
        $title = single_term_title( '', false );
    }   
    return $title;
}
add_filter( 'get_the_archive_title', 'bistro_menu_archive_title' );

/*
** Restaurant archive entry post class
*/
function bistro_restaurant_item_class($classes) {
	if ( is_post_type_archive('restaurant_item') || is_tax('restaurant_tag') ) {
		$classes[] = 'col-md-4 col-sm-4 col-xs-6';
	}
	return $classes;
}
add_filter( 'post_class', 'bistro_restaurant_item_class' );

/*
** Load the widgets
*/
require get_stylesheet_directory() . "/widgets/menus-type-a.php";
require get_stylesheet_directory() . "/widgets/menus-type-b.php";

/*
** Load the slider
*/
require get_stylesheet_directory() . "/inc/slider.php";

/*
** Load the extra page builder options
*/
require get_stylesheet_directory() . "/inc/builder.php";

/*
** Dynamic styles
*/
require get_stylesheet_directory() . "/inc/styles.php";
