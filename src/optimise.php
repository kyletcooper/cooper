<?php
/**
 * Hooks/functions for optimising WordPress' output.
 *
 * @package {{PACKAGE_NAME}}
 */

/**
 * Cleans up the core output to the head.
 *
 * @return void
 */
function optimise_theme_wp_head(): void {
	wp_dequeue_script( 'wp-embed' );

	remove_action( 'wp_head', 'wp_resource_hints' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_json' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
	remove_action( 'wp_head', 'wp_print_auto_sizes_contain_css_fix', 1 );
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	add_filter( 'embed_oembed_discover', '__return_false' );
	add_filter( 'emoji_svg_url', '__return_false' );

	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
}
add_action( 'init', 'optimise_theme_wp_head' );
