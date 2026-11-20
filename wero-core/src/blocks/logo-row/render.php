<?php
/**
 * Blocco wero/logo-row: riga curva dei loghi dei collaboratori.
 * Le "tessere" (logo bianco + etichetta) le disegna il browser dal logo caricato.
 *
 * @var array $attributes Attributi.
 *
 * @package WeroCore
 */

use Wero\Core\Fields;

$wero_posts = get_posts(
	array(
		'post_type'      => 'partner',
		'posts_per_page' => 100,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		),
		'no_found_rows'  => true,
	)
);
$wero_tiles = array();
foreach ( $wero_posts as $wero_p ) {
	if ( Fields::post( $wero_p->ID, 'founder', false ) ) {
		continue;
	}
	$wero_src = Fields::image_url( Fields::post( $wero_p->ID, 'logo', 0 ), 'medium_large' );
	if ( ! $wero_src ) {
		continue;
	}
	$wero_tiles[] = array(
		'src'  => $wero_src,
		'alt'  => get_the_title( $wero_p ),
		'type' => 'ricerca' === Fields::post( $wero_p->ID, 'tipo', 'business' ) ? 'ricerca' : 'business',
	);
}
if ( ! $wero_tiles ) {
	return;
}
$wero_opts = array(
	'speed'       => (float) ( $attributes['speed'] ?? 3.2 ),
	'curve'       => (int) ( $attributes['curve'] ?? 72 ),
	'tileHeight'  => (int) ( $attributes['tileHeight'] ?? 34 ),
	'aspectRatio' => 1.6,
	'gap'         => 8,
	'axis'        => 52,
	'fade'        => 10,
	'labels'      => array(
		'business' => __( 'Business', 'wero-core' ),
		'ricerca'  => __( 'Ricerca', 'wero-core' ),
	),
	'tiles'       => $wero_tiles,
);
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-logo-row' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( ! empty( $attributes['showLegend'] ) ) : ?>
		<ul class="wero-legend"><li><i class="wero-dot wero-dot--business"></i><?php esc_html_e( 'Business', 'wero-core' ); ?></li><li><i class="wero-dot wero-dot--ricerca"></i><?php esc_html_e( 'Ricerca', 'wero-core' ); ?></li></ul>
	<?php endif; ?>
	<div class="wero-tilted" data-options="<?php echo esc_attr( wp_json_encode( $wero_opts ) ); ?>"></div>
	<ul class="screen-reader-text">
		<?php foreach ( $wero_tiles as $wero_t ) : ?>
			<li><?php echo esc_html( $wero_t['alt'] . ' (' . ( 'ricerca' === $wero_t['type'] ? __( 'Ricerca', 'wero-core' ) : __( 'Business', 'wero-core' ) ) . ')' ); ?></li>
		<?php endforeach; ?>
	</ul>
</div>
