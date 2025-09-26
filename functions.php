<?php
/**
 * Entrypoint for theme.
 *
 * @package {{PACKAGE_NAME}}
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
	require_once get_template_directory() . '/src/assets.php';
	require_once get_template_directory() . '/src/blog.php';
	require_once get_template_directory() . '/src/optimise.php';
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

	remove_theme_support( 'core-block-patterns' );
	remove_theme_support( 'block-templates' );

	register_block_category( 'header', __( 'Header', 'cooper' ) );
	register_block_category( 'cta', __( 'Call to Action', 'cooper' ) );

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
