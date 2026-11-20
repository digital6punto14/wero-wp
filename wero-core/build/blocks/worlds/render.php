<?php
/**
 * Blocco wero/worlds: card dei mondi (tassonomia Mondo + campi ACF del termine).
 *
 * @package WeroCore
 */

use Wero\Core\Fields;
use Wero\Core\Taxonomies;

$wero_worlds = Taxonomies::worlds();
if ( ! $wero_worlds ) {
	return;
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-worlds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="wero-worlds__list">
		<?php
		foreach ( $wero_worlds as $wero_w ) :
			$wero_img  = Fields::image(
				Fields::term( $wero_w, 'immagine', 0 ),
				'large',
				array(
					'loading' => 'lazy',
					'sizes'   => '(max-width: 900px) 86vw, 400px',
				)
			);
			$wero_desc = (string) Fields::term( $wero_w, 'descrizione', $wero_w->description );
			$wero_link = (string) Fields::term( $wero_w, 'testo_link', __( 'Vedi i progetti', 'wero-core' ) );
			?>
			<article class="wero-world">
				<figure class="wero-world__media"><?php echo $wero_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></figure>
				<h3 class="wero-world__t"><?php echo esc_html( $wero_w->name ); ?></h3>
				<p class="wero-world__d"><?php echo esc_html( $wero_desc ); ?></p>
				<a class="wero-world__link" href="#progetti" data-wero-filter="<?php echo esc_attr( $wero_w->slug ); ?>"><?php echo esc_html( $wero_link ); ?></a>
			</article>
		<?php endforeach; ?>
	</div>
	<div class="wero-worlds__dots" aria-hidden="true">
		<?php foreach ( $wero_worlds as $wero_i => $wero_w ) : ?>
			<button type="button" tabindex="-1" class="<?php echo 0 === $wero_i ? 'is-on' : ''; ?>"></button>
		<?php endforeach; ?>
	</div>
</div>
