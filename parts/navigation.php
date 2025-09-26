<?php
/**
 * Navigation partial.
 *
 * @package cooper
 */

use wrd\wp_navigate\MenuItem;

?>

<nav id="nav" class="bg-primary-950 text-surface-0">
	<div class="container">
		<ul class="flex items-center gap-4 sm:gap-6 md:gap-12 border-b border-primary-900 py-6 text-lg">
			<li class="mr-auto">
				<a class="font-display font-bold" href="<?php echo esc_url( home_url() ); ?>">
					<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
				</a>
			</li>

			<?php foreach ( MenuItem::get_location_items( 'primary_navigation' ) as $item ) : ?>

				<?php $item->render(); ?>

			<?php endforeach; ?>
		</ul>

		<?php if ( is_singular() && ! is_page() ) : ?>

			<div class="border-b border-primary-900 py-4">
				<a class="flex items-center gap-2 text-primary-300 hover:text-primary-200 transition group" href="<?php echo esc_url( get_post_type_archive_link( get_post_type() ) ); ?>">
					<svg class="fill-current size-6 group-hover:-translate-1 transition" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M737.85-180 280-637.85V-360h-60v-380h380v60H322.15L780-222.15 737.85-180Z"/></svg>

					<?php echo esc_html( get_post_type_archive_label( get_post_type() ) ); ?>
				</a>
			</div>

		<?php endif; ?>
	</div>
</nav>
