<?php
/**
 * Blocco wero/hero-video.
 *
 * @var array    $attributes Attributi.
 * @var string   $content    Blocchi interni (titolo, testo, pulsanti).
 * @var WP_Block $block      Blocco.
 *
 * @package WeroCore
 */

use Wero\Core\Media;

$wero_media  = Media::parse( (string) ( $attributes['mediaUrl'] ?? '' ) );
$wero_label  = (string) ( $attributes['videoLabel'] ?? '' );
$wero_poster = '';
if ( ! empty( $attributes['posterId'] ) ) {
	$wero_poster = wp_get_attachment_image(
		(int) $attributes['posterId'],
		'full',
		false,
		array(
			'class'         => 'wero-hero__poster',
			'alt'           => '',
			'loading'       => false,
			'fetchpriority' => 'high',
			'decoding'      => 'async',
			'sizes'         => '100vw',
		)
	);
} elseif ( ! empty( $attributes['poster'] ) ) {
	$wero_poster = sprintf( '<img class="wero-hero__poster" src="%s" alt="" fetchpriority="high" decoding="async">', esc_url( $attributes['poster'] ) );
}
$wero_data = $wero_media ? sprintf( ' data-kind="%s" data-src="%s"', esc_attr( $wero_media['kind'] ), esc_url( $wero_media['src'] ) ) : '';
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-hero' ) ) . $wero_data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="wero-hero__media"><?php echo $wero_poster; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><div class="wero-hero__player"></div></div>
	<div class="wero-hero__scrim" aria-hidden="true"></div>
	<button class="wero-hero__bigplay" type="button" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: titolo del video */ __( 'Guarda il video “%s”', 'wero-core' ), $wero_label ) ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 4.5v15l12-7.5z"/></svg></button>
	<button class="wero-hero__close" type="button" hidden><?php esc_html_e( 'Chiudi il video', 'wero-core' ); ?></button>
	<div class="wero-hero__content"><div class="wero-hero__inner">
		<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- blocchi interni già resi. ?>
		<p class="wero-hero__note" role="status"></p>
	</div></div>
</section>
