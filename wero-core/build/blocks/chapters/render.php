<?php
/**
 * Blocco wero/chapters: contenitore dei capitoli.
 *
 * @var string $content Capitoli (blocchi interni).
 *
 * @package WeroCore
 */

?>
<ol <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-chapters' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</ol>
