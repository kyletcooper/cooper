<?php
/**
 * Header template.
 *
 * @package cooper
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<a href="#main" class="sr-only"><?php esc_html_e( 'Skip to main content', 'cooper' ); ?></a>

	<?php get_template_part( '/parts/navigation' ); ?>

	<main id="main">
