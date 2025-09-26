<?php
/**
 * Template tags.
 *
 * @package cooper
 */

/**
 * Parse HTML element attributes from an array to a string.
 *
 * @param array|string $attrs The attributes array to convert.
 *
 * @return string
 */
function get_the_attrs( array|string $attrs ): string {
	if ( is_string( $attrs ) ) {
		return $attrs;
	}

	$output = '';

	foreach ( $attrs as $attr => $value ) {
		$output .= esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
	}

	return $output;
}

/**
 * Parse and output an array of HTML element attributes to a string.
 *
 * @param array|string $attrs The attributes array to convert.
 *
 * @eturn void
 */
function the_attrs( array|string $attrs ): void {
	echo get_the_attrs( $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped.
}

/**
 * Merge two sets of HTML element attributes together.
 *
 * Classes & inline styles are appended together, all other attributes are overwritten.
 *
 * @param array $attrs The provided attributes.
 *
 * @param array $default_attrs The fallback values for each attrbiute.
 *
 * @return array
 */
function merge_attrs( array $attrs, array $default_attrs ): array {
	$merged = wp_parse_args( $attrs, $default_attrs );

	if ( isset( $attrs['class'] ) && isset( $default_attrs['class'] ) ) {
		$merged['class'] = array( trim( $default_attrs['class'] ) . ' ' . trim( $attrs['class'] ) );
	}

	if ( isset( $attrs['style'] ) && isset( $default_attrs['style'] ) ) {
		$merged['style'] = trim( $default_attrs['style'] );

		if ( ! str_ends_with( $merged['style'], ';' ) ) {
			$merged['style'] .= '; ';
		}

		$merged['style'] .= $attrs['style'];
	}

	return $merged;
}

/**
 * Output the statically rendered content for a React Island.
 *
 * @param string $name The island's name.
 *
 * @param ?array $props Props to pass to the component. Optional.
 *
 * @param array  $attrs An array of other HTML attributes to be passed to the wrapper element. Optional.
 *
 * @return void
 */
function the_ssg( string $name, ?array $props = null, array $attrs = array() ): void {
	$attrs = get_the_attrs(
		array_merge(
			array(
				'data-hydrate' => $name,
				'data-props'   => wp_json_encode( $props ),
				'style'        => 'all: inherit; display: contents !important;',
			),
			$attrs
		)
	);

	printf( '<div %s>', $attrs ); // phpcs:ignore -- get_the_attrs escapes the data.

	$file = trailingslashit( get_template_directory() ) . 'assets/scripts/dist/' . $name . '/ssg.html';
	include $file;

	echo '</div>';

	// Enqueue client-side script.
	wp_enqueue_script( "islands-$name", get_template_directory_uri() . "/assets/scripts/dist/$name/client.js", array( 'islands' ), get_theme_version(), array( 'strategy' => 'defer' ) );
}

/**
 * Get the archive label for a post type.
 *
 * @param string $post_type The post type.
 *
 * @return string
 */
function get_post_type_archive_label( string $post_type ): string {
	$obj = get_post_type_object( $post_type );

	if ( ! $obj ) {
		return __( 'Back', 'cooper' );
	}

	$labels = $obj->labels;

	if ( property_exists( $labels, 'name' ) ) {
		return $labels->name;
	}

	return $obj->label;
}
