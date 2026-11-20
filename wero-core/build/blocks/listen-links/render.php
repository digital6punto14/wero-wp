<?php
/**
 * Blocco wero/listen-links.
 *
 * @var array    $attributes Attributi.
 * @var WP_Block $block      Blocco (contesto postId).
 *
 * @package WeroCore
 */

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

$wero_links = array_filter(
	array(
		'Spotify'        => (string) Fields::post( $wero_id, 'ascolta_spotify' ),
		'Apple Podcasts' => (string) Fields::post( $wero_id, 'ascolta_apple' ),
		'YouTube'        => (string) Fields::post( $wero_id, 'ascolta_youtube' ),
		(string) Fields::post( $wero_id, 'ascolta_altro_nome', __( 'Altro', 'wero-core' ) ) => (string) Fields::post( $wero_id, 'ascolta_altro' ),
	)
);
if ( ! $wero_links ) {
	return;
}
?>
<nav <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-listen' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-label="<?php esc_attr_e( 'Ascolta anche su', 'wero-core' ); ?>">
	<?php foreach ( $wero_links as $wero_name => $wero_url ) : ?>
		<a class="wero-chip" href="<?php echo esc_url( $wero_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( (string) $wero_name ); ?></a>
	<?php endforeach; ?>
</nav>
