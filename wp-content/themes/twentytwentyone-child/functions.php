<?php

function loadParentStyles() {
	wp_enqueue_style( 'child-theme-style', get_stylesheet_uri(),
	array( 'twenty-twenty-one-style' ), wp_get_theme()->get('Version') );
}
add_action( 'wp_enqueue_scripts', 'loadParentStyles');