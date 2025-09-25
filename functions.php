<?php
/**
 * Entrypoint for theme.
 *
 * @package cooper
 */

use function wrd\wp_blocks\core\get_all_theme_block_names;
use function wrd\wp_blocks\core\register_all_theme_blocks;
use function wrd\wp_blocks\core\register_block_category;
use function wrd\wp_blocks\core\set_allowed_block_types;
use function wrd\wp_blocks\core\set_include_theme_block_styles_before_render;
use function wrd\wp_blocks\core\set_use_core_styles;
use function wrd\wp_blocks\richtext\set_richtext_disallow_formats;
use function wrd\wp_blocks\templating\get_prose_blocks;

/**
 * Includes all theme files.
 *
 * @return void
 */
function theme_includes(): void {
	// Composer.
	require_once __DIR__ . '/vendor/autoload.php';

	// Source files.
	require_once get_template_directory() . '/src/blog.php';
	require_once get_template_directory() . '/src/templating.php';

	// Plugins.
	define( 'MY_ACF_PATH', get_template_directory() . '/plugins/acf/' );
	define( 'MY_ACF_URL', get_template_directory_uri() . '/plugins/acf/' );

	include_once get_template_directory() . '/plugins/advanced-custom-fields-pro/acf.php';

	add_filter(
		'acf/settings/url',
		function () {
			return get_template_directory_uri() . '/plugins/advanced-custom-fields-pro/';
		}
	);
}
theme_includes();

/**
 * Sets up the theme, it's menus and supports.
 *
 * @return void
 */
function theme_setup(): void {
	set_allowed_block_types( array( ...get_prose_blocks(), ...get_all_theme_block_names() ) );
	register_all_theme_blocks();
	set_include_theme_block_styles_before_render( true );
	set_use_core_styles( false );

	set_richtext_disallow_formats(
		array(
			'core/footnote',
			'core/image',
			'core/strikethrough',
			'core/text-color',
			'core/subscript',
			'core/superscript',
			'core/keyboard',
			'core/unknown',
			'core/language',
		)
	);

	register_block_category( 'header', __( 'Header', 'cooper' ) );
	register_block_category( 'cta', __( 'Call to Action', 'cooper' ) );

	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-color-palette' );
	add_theme_support( 'disable-custom-colors' );
	add_theme_support( 'editor-gradient-presets' );
	add_theme_support( 'disable-custom-gradients' );
	add_theme_support( 'disable-custom-font-sizes' );
	add_theme_support( 'disable-layout-styles' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
			'navigation-widgets',
		)
	);

	remove_theme_support( 'core-block-patterns' );

	register_block_style(
		'core/button',
		array(
			'name'  => 'orange',
			'label' => 'Orange',
		)
	);

	add_image_size( 'xlarge', 1300 );
	add_image_size( 'fullscreen', 2000 );

	register_nav_menus(
		array(
			'primary_navigation' => 'Primary Navigation',
			'footer_navigation'  => 'Footer Navigation',
		)
	);
}
add_action( 'after_setup_theme', 'theme_setup' );

/**
 * Checks if the current WordPress instance is a production site.
 */
function is_production_environment() {
	return 'production' === wp_get_environment_type();
}

/**
 * Gets the version string of the theme.
 *
 * @return string The version.
 */
function get_theme_version(): string {
	$theme  = wp_get_theme();
	$parent = $theme->parent();

	if ( $parent ) {
		$theme = $parent;
	}

	return $theme->version;
}

/**
 * Adds data to the window.Theme object.
 *
 * @param string $handle The script handle to inline before.
 *
 * @param string $scope The scope of the data.
 *
 * @param mixed  $data The data to add.
 *
 * @return void
 */
function add_script_object( $handle, $scope, $data ): void {
	wp_add_inline_script(
		$handle,
		'window.Theme = window.Theme || {}; window.Theme["' . $scope . '"] = ' . wp_json_encode( $data ),
		'before'
	);
}


/**
 * Registers all scripts & styles that might be enqueued in the front or back-end.
 *
 * @return void
 */
function register_theme_assets(): void {
	$theme_version = get_theme_version();

	/**
	 * Main front-end bundle for CSS.
	 */
	wp_register_style( 'theme-bundle', get_template_directory_uri() . '/assets/styles/dist/bundle.css', array(), $theme_version, 'all' );

	/**
	 * Main front-end bundle for JS
	 */
	wp_register_script( 'theme-bundle', get_template_directory_uri() . '/assets/scripts/dist/bundle.js', array(), $theme_version, array( 'strategy' => 'defer' ) );
}
add_action( 'wp_enqueue_scripts', 'register_theme_assets', 9 );
add_action( 'admin_enqueue_scripts', 'register_theme_assets', 9 );

/**
 * Enqueues the assets for the front-end.
 *
 * @return void
 */
function enqueue_theme_public_assets(): void {
	wp_enqueue_style( 'theme-bundle' );
	wp_enqueue_script( 'theme-bundle' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_theme_public_assets' );

/**
 * Enqueues assets used in the block editor.
 *
 * We don't use add_editor_style because these aren't relevant to the TinyMCE editor.
 *
 * @return void
 */
function enqueue_theme_block_editor_assets(): void {
	wp_enqueue_style( 'theme-bundle' );
}
add_action( 'enqueue_block_editor_assets', 'enqueue_theme_block_editor_assets' );

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
