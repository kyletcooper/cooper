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
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Nanum+Myeongjo:wght@700&family=Work+Sans:wght@100..900&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<a href="#main" class="sr-only"><?php esc_html_e( 'Skip to main content', 'cooper' ); ?></a>

	<?php get_template_part( '/parts/navigation' ); ?>

	<main id="main" class="min-h-[200vh]">
