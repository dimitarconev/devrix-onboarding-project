<?php
require "includes/hooks.php";

function load_parent_styles() {
	wp_enqueue_style( 'child-theme-style', get_stylesheet_uri(),
	array( 'twenty-twenty-one-style' ), wp_get_theme()->get('Version') );
}

function load_theme_language(){
    load_theme_textdomain( 'twentytwentyone-child', get_template_directory() . '/languages' );
}

add_action( 'wp_enqueue_scripts', 'load_parent_styles');
add_action('after_setup_theme', 'load_theme_language');
