<?php
/**
 * The footer partial.
 *
 * @package cooper
 */

?>

<footer id="footer" class="container">
	<div class="border-t border-primary-200 py-6">
		<span class="text-surface-600">
			<?php

			// translators: %s is the current year.
			printf( esc_html__( 'Copyright %s', 'cooper' ), esc_html( gmdate( 'Y' ) ) );

			?>
		</span>
	</div>
</footer>
