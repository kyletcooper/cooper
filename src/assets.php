<?php
/**
 * Hooks/functions for managing the theme's assets.
 *
 * @package cooper
 */

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
	 * Common requirements for Islands.
	 *
	 * Islands are always enqueued by the the_island function and this is their dependency.
	 */
	wp_register_script( 'islands', get_template_directory_uri() . '/assets/scripts/dist/common.js', array(), $theme_version, array( 'strategy' => 'defer' ) );
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
