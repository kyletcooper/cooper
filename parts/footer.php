<?php
/**
 * The footer partial.
 *
 * @package cooper
 */

?>

<footer>
	<?php

	// translators: %s is the current year.
	printf( esc_html__( 'Copyright %s', 'cooper' ), esc_html( gmdate( 'Y' ) ) );

	?>
</footer>
