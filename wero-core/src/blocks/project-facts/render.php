<?php
/**
 * Blocco wero/project-facts.
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

$wero_world = Taxonomies::world_of( $wero_id );
$wero_rows  = array_filter(
	array(
		__( 'Mondo', 'wero-core' )   => $wero_world ? $wero_world->name : '',
		__( 'Formato', 'wero-core' ) => (string) Fields::post( $wero_id, 'tipo' ),
		__( 'Anno', 'wero-core' )    => (string) Fields::post( $wero_id, 'anno' ),
		__( 'Con', 'wero-core' )     => (string) Fields::post( $wero_id, 'con' ),
	)
);
if ( ! $wero_rows ) {
	return;
}
?>
<dl <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-facts' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php foreach ( $wero_rows as $wero_k => $wero_v ) : ?>
		<div><dt><?php echo esc_html( $wero_k ); ?></dt><dd><?php echo esc_html( $wero_v ); ?></dd></div>
	<?php endforeach; ?>
</dl>
