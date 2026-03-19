<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function confidup_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'confidup_setup' );

function confidup_enqueue_assets() {
	wp_enqueue_style(
		'confidup-style',
		get_stylesheet_uri(),
		[],
		wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_style(
		'confidup-main',
		get_template_directory_uri() . '/assets/css/main.css',
		[],
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'confidup_enqueue_assets' );
