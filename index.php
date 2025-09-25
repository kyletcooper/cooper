<?php
/**
 * The Template for displaying single Pages.
 *
 * @package cooper
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		if ( has_blocks( get_the_content() ) ) {
			the_content();
		} else {
			echo '<section class="container">';
			the_content();
			echo '</section>';
		}
	endwhile;
endif;

get_footer();
