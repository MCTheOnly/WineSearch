<?php
/**
 * Enqueue script and styles for child theme
 */
function woodmart_child_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'woodmart-style' ), woodmart_get_theme_info( 'Version' ) );
    wp_enqueue_script( 'child-script', get_stylesheet_directory_uri() . '/scripts.js', array( 'woodmart-theme' ), null, true );
}
add_action( 'wp_enqueue_scripts', 'woodmart_child_enqueue_styles', 10010 );

add_filter('wpcf7_autop_or_not', '__return_false');

/**
 * modify the apperance of mobile menu heading
 * 
 */

add_action( 'wp_enqueue_scripts', 'dashicons_front_end' );

function dashicons_front_end() {
   wp_enqueue_style( 'dashicons' );
}
add_action( 'init', 'dashicons_front_end' );

function cdon_custom_mobile_menu() {
    remove_action( 'woodmart_before_wp_footer', 'woodmart_mobile_menu', 130 );
    ob_start();
    woodmart_mobile_menu();
    $menu = ob_get_clean();
    $wpml_ls = do_shortcode( '[wpml_language_selector_widget]' );
    $homeurl = esc_url( apply_filters( 'wpml_home_url', get_option( 'home' ) ) );
    $uploads = wp_upload_dir('2022/05');
    $logosrc = esc_url( $uploads['url'] . '/Group-935.png' );
    $logo = '<a class="logo" href="' . $homeurl . '"><img alt="' . get_bloginfo('name' ). '" src="' . $logosrc . '"></a>';
    $heading_opening = '<div class="wd-heading widget-heading">';
    echo str_replace( $heading_opening, $heading_opening . $logo .  $wpml_ls, $menu ); 
}
add_action( 'woodmart_before_wp_footer', 'cdon_custom_mobile_menu', 135 );

