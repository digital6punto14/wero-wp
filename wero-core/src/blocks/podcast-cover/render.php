<?php
/**
 * Blocco wero/podcast-cover.
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

$wero_img = Fields::image(
	Fields::post( $wero_id, 'immagine_card', 0 ),
	'large',
	array(
		'class' => 'wero-cover__img',
		'alt'   => '',
	)
);
$wero_ep  = (string) Fields::post( $wero_id, 'episodio' );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-cover' . ( $wero_img ? ' has-image' : '' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-hidden="true">
	<?php if ( $wero_img ) : ?>
		<?php echo $wero_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php else : ?>
		<img class="wero-cover__mark" src="<?php echo esc_url( Blocks::mark_url() ); ?>" alt="">
		<div>
		<?php
		if ( $wero_ep ) :
			?>
			<p><?php echo esc_html( $wero_ep ); ?></p><?php endif; ?><strong><?php echo esc_html( get_the_title( $wero_id ) ); ?></strong></div>
	<?php endif; ?>
</div>
