<?php
/**
 * Blocco wero/partners: loghi con scheda.
 *
 * @var array $attributes Attributi.
 *
 * @package WeroCore
 */

use Wero\Core\Fields;

$wero_group = (string) ( $attributes['group'] ?? 'founder' );
$wero_query = array(
	'post_type'      => 'partner',
	'posts_per_page' => 50,
	'orderby'        => array(
		'menu_order' => 'ASC',
		'title'      => 'ASC',
	),
	'no_found_rows'  => true,
);
$wero_items = get_posts( $wero_query );
$wero_items = array_filter(
	$wero_items,
	static function ( WP_Post $p ) use ( $wero_group ): bool {
		$founder = (bool) Fields::post( $p->ID, 'founder', false );
		return match ( $wero_group ) {
			'founder' => $founder,
			'all'     => true,
			default   => ! $founder && Fields::post( $p->ID, 'tipo', 'business' ) === $wero_group,
		};
	}
);
if ( ! $wero_items ) {
	return;
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-partners is-' . esc_attr( $wero_group ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<ul class="wero-partners__list">
		<?php
		foreach ( $wero_items as $wero_p ) :
			$wero_h    = (int) Fields::post( $wero_p->ID, 'altezza_logo', 64 );
			$wero_desc = (string) Fields::post( $wero_p->ID, 'descrizione' );
			$wero_url  = (string) Fields::post( $wero_p->ID, 'sito' );
			$wero_name = get_the_title( $wero_p );
			$wero_id   = 'wero-pcard-' . $wero_p->ID;
			$wero_logo = Fields::image(
				Fields::post( $wero_p->ID, 'logo', 0 ),
				'medium_large',
				array(
					'alt'     => $wero_name,
					'loading' => 'lazy',
					'style'   => '--h:' . $wero_h . 'px',
				)
			);
			?>
			<li class="wero-partner">
				<button class="wero-partner__logo" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $wero_id ); ?>">
					<?php echo $wero_logo ? $wero_logo : esc_html( $wero_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
				<div class="wero-pcard" id="<?php echo esc_attr( $wero_id ); ?>" role="region" aria-label="<?php echo esc_attr( $wero_name ); ?>">
					<p class="wero-pcard__name"><?php echo esc_html( $wero_name ); ?></p>
					<?php if ( $wero_desc ) : ?>
						<p class="wero-pcard__desc"><?php echo esc_html( $wero_desc ); ?></p>
					<?php endif; ?>
					<?php if ( $wero_url ) : ?>
						<a class="wero-pcard__link" href="<?php echo esc_url( $wero_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Visita il sito', 'wero-core' ); ?> <span aria-hidden="true">↗</span></a>
					<?php endif; ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
