<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ============================================================
   Theme Setup
   ============================================================ */

function confidup_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'html5', [ 'script', 'style', 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
	add_theme_support( 'responsive-embeds' );
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'confidup_setup' );

/* ============================================================
   Enqueue Assets
   ============================================================ */

function confidup_enqueue_assets() {
	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'confidup-style',
		get_stylesheet_uri(),
		[],
		$version
	);

	wp_enqueue_style(
		'confidup-main',
		get_template_directory_uri() . '/assets/css/main.css',
		[],
		$version
	);

	wp_enqueue_script(
		'confidup-performance',
		get_template_directory_uri() . '/assets/js/performance.js',
		[],
		$version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'confidup_enqueue_assets' );

/* ============================================================
   Resource Hints — preconnect for faster asset loading
   ============================================================ */

function confidup_resource_hints( $hints, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$hints[] = [ 'href' => 'https://fonts.googleapis.com' ];
		$hints[] = [ 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' ];
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'confidup_resource_hints', 10, 2 );

/* ============================================================
   Remove Emoji Scripts & Styles
   ============================================================ */

function confidup_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'confidup_disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'confidup_disable_emojis_dns_prefetch', 10, 2 );
}
add_action( 'init', 'confidup_disable_emojis' );

function confidup_disable_emojis_tinymce( $plugins ) {
	return is_array( $plugins ) ? array_diff( $plugins, [ 'wpemoji' ] ) : [];
}

function confidup_disable_emojis_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' !== $relation_type ) {
		return $urls;
	}
	$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
	return array_diff( $urls, [ $emoji_svg_url ] );
}

/* ============================================================
   Remove Unnecessary Head Tags
   ============================================================ */

function confidup_clean_head() {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
}
add_action( 'init', 'confidup_clean_head' );

/* ============================================================
   Remove WordPress Version from RSS Feed
   ============================================================ */

add_filter( 'the_generator', '__return_empty_string' );

/* ============================================================
   Dequeue jQuery Migrate (not needed in FSE themes)
   ============================================================ */

function confidup_dequeue_jquery_migrate( $scripts ) {
	if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
		$scripts->registered['jquery']->deps = array_diff(
			$scripts->registered['jquery']->deps,
			[ 'jquery-migrate' ]
		);
	}
}
add_filter( 'wp_default_scripts', 'confidup_dequeue_jquery_migrate' );

/* ============================================================
   Disable XML-RPC
   ============================================================ */

add_filter( 'xmlrpc_enabled', '__return_false' );

/* ============================================================
   Disable Heartbeat on Front End
   ============================================================ */

function confidup_disable_heartbeat() {
	if ( ! is_admin() ) {
		wp_deregister_script( 'heartbeat' );
	}
}
add_action( 'init', 'confidup_disable_heartbeat', 1 );

/* ============================================================
   Limit Post Revisions (also set in wp-config, belt & braces)
   ============================================================ */

if ( ! defined( 'WP_POST_REVISIONS' ) ) {
	define( 'WP_POST_REVISIONS', 5 );
}

/* ============================================================
   Add async/defer to non-critical scripts
   ============================================================ */

function confidup_script_loader_tag( $tag, $handle, $src ) {
	$defer_handles = [ 'confidup-performance' ];

	if ( in_array( $handle, $defer_handles, true ) ) {
		return str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'confidup_script_loader_tag', 10, 3 );

/* ============================================================
   Optimise Image Output
   ============================================================ */

function confidup_image_attributes( $attr, $attachment, $size ) {
	// Ensure decoding="async" on all images for non-blocking rendering.
	if ( empty( $attr['decoding'] ) ) {
		$attr['decoding'] = 'async';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'confidup_image_attributes', 10, 3 );

/* ============================================================
   Preload Featured Image on Single Posts/Pages
   ============================================================ */

function confidup_preload_featured_image() {
	if ( ! ( is_single() || is_page() ) ) {
		return;
	}
	if ( ! has_post_thumbnail() ) {
		return;
	}

	$thumbnail_id  = get_post_thumbnail_id();
	$thumbnail_url = wp_get_attachment_image_src( $thumbnail_id, 'full' );

	if ( $thumbnail_url ) {
		echo '<link rel="preload" as="image" href="' . esc_url( $thumbnail_url[0] ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'confidup_preload_featured_image', 1 );
