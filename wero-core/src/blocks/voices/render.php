<?php
/**
 * Blocco wero/voices.
 *
 * @var array $attributes Attributi.
 *
 * @package WeroCore
 */

use Wero\Core\Fields;
use Wero\Core\Media;

$wero_voices = get_posts(
	array(
		'post_type'      => 'voce',
		'post_status'    => 'publish',
		'posts_per_page' => max( 1, (int) ( $attributes['max'] ?? 3 ) ),
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'no_found_rows'  => true,
	)
);
if ( ! $wero_voices ) {
	return;
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-voices' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php
	foreach ( $wero_voices as $wero_v ) :
		$wero_media = Media::of_post( $wero_v->ID );
		$wero_title = get_the_title( $wero_v );
		$wero_data  = $wero_media ? sprintf( ' data-kind="%s" data-src="%s"', esc_attr( $wero_media['kind'] ), esc_url( $wero_media['src'] ) ) : '';
		?>
		<article class="wero-voice">
			<button class="wero-voice__media" type="button"<?php echo $wero_data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				aria-label="<?php echo esc_attr( sprintf( /* translators: %s: titolo */ __( 'Guarda il video: %s', 'wero-core' ), $wero_title ) ); ?>">
				<?php echo get_the_post_thumbnail( $wero_v, 'large', array( 'alt' => '', 'sizes' => '(max-width: 900px) 100vw, 400px' ) ); ?>
				<span class="wero-play-dot" aria-hidden="true"></span>
			</button>
			<p class="wero-voice__name"><?php echo esc_html( $wero_title ); ?></p>
			<p class="wero-voice__desc"><?php echo esc_html( (string) Fields::post( $wero_v->ID, 'descrizione' ) ); ?></p>
			<p class="wero-voice__note" role="status"></p>
		</article>
	<?php endforeach; ?>
</div>
