<?php
/**
 * Blocco wero/project-media.
 *
 * @var array    $attributes Attributi.
 * @var WP_Block $block      Blocco (contesto postId).
 *
 * @package WeroCore
 */

defined( 'ABSPATH' ) || exit;

use Wero\Core\Blocks;
use Wero\Core\Fields;
use Wero\Core\Media;
use Wero\Core\Taxonomies;

$wero_id = (int) ( $block->context['postId'] ?? 0 );
if ( ! $wero_id && isset( $_GET['post_id'] ) && current_user_can( 'edit_posts' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- solo anteprima nell'editor.
	$wero_id = absint( $_GET['post_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}
if ( ! $wero_id ) {
	$wero_id = (int) get_the_ID();
}
if ( ! $wero_id ) {
	return;
}

$wero_media = Media::of_post( $wero_id );
$wero_mode  = 'audio' === ( $attributes['mode'] ?? 'video' ) ? 'audio' : 'video';
$wero_dur   = (string) Fields::post( $wero_id, 'media_durata' );
$wero_title = get_the_title( $wero_id );

if ( 'audio' === $wero_mode ) :
	?>
	<div <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-player wero-player--audio' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php if ( $wero_media && 'audio' === $wero_media['kind'] ) : ?>
			<audio controls preload="none" src="<?php echo esc_url( $wero_media['src'] ); ?>" aria-label="<?php echo esc_attr( $wero_title ); ?>"></audio>
		<?php elseif ( $wero_media && 'embed' === $wero_media['kind'] ) : ?>
			<button type="button" class="wero-player__load" data-src="<?php echo esc_url( $wero_media['src'] ); ?>" data-height="152">
				<?php echo esc_html( sprintf( /* translators: %s: servizio */ __( 'Ascolta su %s', 'wero-core' ), Media::provider_label( $wero_media['provider'] ) ) ); ?>
			</button>
			<p class="wero-player__note"><?php esc_html_e( 'Il player viene caricato dal servizio esterno solo quando premi il pulsante.', 'wero-core' ); ?></p>
		<?php else : ?>
			<p class="wero-player__note"><?php esc_html_e( 'L’episodio sarà disponibile a breve.', 'wero-core' ); ?></p>
		<?php endif; ?>
		<?php if ( $wero_dur ) : ?>
			<p class="wero-player__dur"><?php echo esc_html( sprintf( /* translators: %s: durata */ __( 'Durata %s', 'wero-core' ), $wero_dur ) ); ?></p>
		<?php endif; ?>
	</div>
	<?php
	return;
endif;

$wero_poster = Fields::image(
	Fields::post( $wero_id, 'media_poster', 0 ),
	'large',
	array(
		'class' => 'wero-player__poster',
		'alt'   => '',
	)
);
if ( ! $wero_poster ) {
	$wero_poster = get_the_post_thumbnail(
		$wero_id,
		'large',
		array(
			'class' => 'wero-player__poster',
			'alt'   => '',
		)
	);
}
$wero_data = $wero_media ? sprintf( ' data-kind="%s" data-src="%s"', esc_attr( $wero_media['kind'] ), esc_url( $wero_media['src'] ) ) : '';
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-player wero-player--video' ) ) . $wero_data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php echo $wero_poster; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<button class="wero-player__play" type="button" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: titolo */ __( 'Riproduci il video: %s', 'wero-core' ), $wero_title ) ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 4.5v15l13-7.5z"/></svg></button>
	<p class="wero-player__note" role="status"><?php echo $wero_dur ? esc_html( sprintf( /* translators: %s: durata */ __( 'Durata %s', 'wero-core' ), $wero_dur ) ) : ''; ?></p>
</div>
