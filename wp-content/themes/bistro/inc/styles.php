<?php
/**
 * Styles
 *
 * @package Bistro
 */

/* Replaces default primary color */
function bistro_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'primary_color' )->default 		= '#ff9100';
    $wp_customize->get_setting( 'slider_title_1' )->default 	= __( 'Welcome to our bistro', 'bistro' );
    $wp_customize->get_setting( 'slider_title_2' )->default 	= __( 'Ready to see what we offer?', 'bistro' );
    $wp_customize->get_setting( 'slider_subtitle_2' )->default 	= __( 'Click the button below', 'bistro' );
    $wp_customize->get_setting( 'slider_image_1' )->default 	= get_stylesheet_directory_uri() . '/images/1.jpg';
    $wp_customize->get_setting( 'slider_image_2' )->default 	= get_stylesheet_directory_uri() . '/images/2.jpg';
}
add_action( 'customize_register', 'bistro_customize_register', 11 );  

/* Dynamic styles */
function bistro_custom_styles($custom) {
    $custom = '';

    $primary_color = get_theme_mod( 'primary_color', '#ff9100' );
    $custom .= ".menu-price,#mainnav ul li a:hover, .sydney_contact_info_widget span, .roll-team .team-content .name,.roll-team .team-item .team-pop .team-social li:hover a,.roll-infomation li.address:before,.roll-infomation li.phone:before,.roll-infomation li.email:before,.roll-testimonials .name,.roll-button.border,.roll-button:hover,.roll-icon-list .icon i,.roll-icon-list .content h3 a:hover,.roll-icon-box.white .content h3 a,.roll-icon-box .icon i,.roll-icon-box .content h3 a:hover,.switcher-container .switcher-icon a:focus,.go-top:hover,.hentry .meta-post a:hover,#mainnav > ul > li > a.active, #mainnav > ul > li > a:hover, button:hover, input[type=\"button\"]:hover, input[type=\"reset\"]:hover, input[type=\"submit\"]:hover, .text-color, .social-menu-widget a, .social-menu-widget a:hover, .archive .team-social li a, a, h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color:" . esc_attr($primary_color) . "}"."\n";
    $custom .= ".preloader .pre-bounce1, .preloader .pre-bounce2,.roll-team .team-item .team-pop,.roll-progress .progress-animate,.roll-socials li a:hover,.roll-project .project-item .project-pop,.roll-project .project-filter li.active,.roll-project .project-filter li:hover,.roll-button.light:hover,.roll-button.border:hover,.roll-button,.roll-icon-box.white .icon,.owl-theme .owl-controls .owl-page.active span,.owl-theme .owl-controls.clickable .owl-page:hover span,.go-top,.bottom .socials li:hover a,.sidebar .widget:before,.blog-pagination ul li.active,.blog-pagination ul li:hover a,.content-area .hentry:after,.text-slider .maintitle:after,.error-wrap #search-submit:hover,#mainnav .sub-menu li:hover > a,#mainnav ul li ul:after, button, input[type=\"button\"], input[type=\"reset\"], input[type=\"submit\"], .panel-grid-cell .widget-title:after { background-color:" . esc_attr($primary_color) . "}"."\n";
    $custom .= ".roll-socials li a:hover,.roll-socials li a,.roll-button.light:hover,.roll-button.border,.roll-button,.roll-icon-list .icon,.roll-icon-box .icon,.owl-theme .owl-controls .owl-page span,.comment .comment-detail,.widget-tags .tag-list a:hover,.blog-pagination ul li,.hentry blockquote,.error-wrap #search-submit:hover,textarea:focus,input[type=\"text\"]:focus,input[type=\"password\"]:focus,input[type=\"datetime\"]:focus,input[type=\"datetime-local\"]:focus,input[type=\"date\"]:focus,input[type=\"month\"]:focus,input[type=\"time\"]:focus,input[type=\"week\"]:focus,input[type=\"number\"]:focus,input[type=\"email\"]:focus,input[type=\"url\"]:focus,input[type=\"search\"]:focus,input[type=\"tel\"]:focus,input[type=\"color\"]:focus, button, input[type=\"button\"], input[type=\"reset\"], input[type=\"submit\"], .archive .team-social li a { border-color:" . esc_attr($primary_color) . "}"."\n";
    //Output all the styles
    wp_add_inline_style( 'bistro-parent-style', $custom );  
}
add_action( 'wp_enqueue_scripts', 'bistro_custom_styles', 11 ); 