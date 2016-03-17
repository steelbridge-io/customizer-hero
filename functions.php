<?php
/**
 * Theme setup
 */
function customizer_hero_setup()
{
  add_image_size('hero-image', 1080, '');
}

add_action( 'after_setup_theme', 'customizer_hero_setup' );

/**
 * Enqueue scripts and styles.
 */
function customizer_hero_scripts()
{
  $query_args = array(
    'family' => 'Raleway:900,800,700|Merriweather'
  );
  wp_register_style( 'fonts', add_query_arg( $query_args, 'https://fonts.googleapis.com/css' ), array('customizer-hero-style'), null );
  wp_enqueue_style( 'customizer-hero-style', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'customizer_hero_scripts' );

/**
 * Include theme customizer
 */
require get_template_directory() . '/inc/customizer.php';