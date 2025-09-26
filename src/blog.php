<?php
/**
 * Hooks/functions for managing the blog.
 *
 * @package {{PACKAGE_NAME}}
 */

/**
 * Removes [...] from excerpts.
 *
 * @return string
 */
function return_ellipse(): string {
	return '...';
}
add_filter( 'excerpt_more', 'return_ellipse' );

/**
 * Disables comments.
 *
 * Redirects the admin URL, removes the metabox and removes post type support.
 *
 * @return void
 */
function disable_comments_admin_area(): void {
	global $pagenow;

	if ( 'edit-comments.php' === $pagenow ) {
		wp_safe_redirect( admin_url() );
		exit;
	}

	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );

	foreach ( get_post_types() as $post_type ) {
		if ( post_type_supports( $post_type, 'comments' ) ) {
			remove_post_type_support( $post_type, 'comments' );
			remove_post_type_support( $post_type, 'trackbacks' );
		}
	}
}
add_action( 'admin_init', 'disable_comments_admin_area' );

/**
 * Hide the comments in the admin menu.
 *
 * @return void
 */
function hide_comments_admin_menu(): void {
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'hide_comments_admin_menu' );

/**
 * Hides comments on the admin bar.
 *
 * @return void
 */
function hide_comments_admin_bar(): void {
	if ( is_admin_bar_showing() ) {
		remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
	}
}
add_action( 'init', 'hide_comments_admin_bar' );

// Disable comments & pings.
add_filter( 'comments_open', '__return_false', 20, 2 );
add_filter( 'pings_open', '__return_false', 20, 2 );
add_filter( 'comments_array', '__return_empty_array', 10, 2 );

// Removes 'Archive:' prefix from archive titles.
add_filter( 'get_the_archive_title_prefix', '__return_false' );
