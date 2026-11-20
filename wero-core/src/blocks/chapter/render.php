<?php
/**
 * Blocco wero/chapter.
 *
 * @var array $attributes Attributi.
 *
 * @package WeroCore
 */

$wero_label = (string) ( $attributes['label'] ?? '' );
if ( '' === $wero_label ) {
	return;
}
?>
<li <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-chapter' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<button type="button" data-wero-seek="<?php echo esc_attr( (string) (int) ( $attributes['seconds'] ?? 0 ) ); ?>">
		<time><?php echo esc_html( (string) ( $attributes['time'] ?? '' ) ); ?></time><span><?php echo wp_kses( $wero_label, array( 'em' => array(), 'strong' => array() ) ); ?></span>
	</button>
</li>
