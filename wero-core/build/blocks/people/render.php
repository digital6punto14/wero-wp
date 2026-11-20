<?php
/**
 * Blocco wero/people.
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

$wero_people = Fields::relation( $wero_id, 'persone' );
if ( ! $wero_people ) {
	return;
}
?>
<ul <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-people' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php
	foreach ( $wero_people as $wero_pid ) :
		if ( 'persona' !== get_post_type( $wero_pid ) ) {
			continue;
		}
		$wero_role = implode( ', ', array_filter( array( (string) Fields::post( $wero_pid, 'ruolo' ), (string) Fields::post( $wero_pid, 'ente' ) ) ) );
		$wero_pic  = get_the_post_thumbnail( $wero_pid, 'thumbnail', array( 'class' => 'wero-people__avatar', 'alt' => '' ) );
		?>
		<li>
			<?php echo $wero_pic ? $wero_pic : '<span class="wero-people__avatar" aria-hidden="true"></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div><strong><?php echo esc_html( get_the_title( $wero_pid ) ); ?></strong><?php if ( $wero_role ) : ?><span><?php echo esc_html( $wero_role ); ?></span><?php endif; ?></div>
		</li>
	<?php endforeach; ?>
</ul>
