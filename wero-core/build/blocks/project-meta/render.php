<?php
/**
 * Blocco wero/project-meta.
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

$wero_world = Taxonomies::world_of( $wero_id );
$wero_ty    = Blocks::type_year( $wero_id );
$wero_var   = 'card' === ( $attributes['variant'] ?? 'bar' ) ? 'card' : 'bar';
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-meta wero-meta--' . $wero_var ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( 'bar' === $wero_var ) : ?>
		<?php if ( ! empty( $attributes['showHome'] ) ) : ?>
			<a class="wero-meta__back" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span aria-hidden="true">←</span> <?php esc_html_e( 'Torna alla home', 'wero-core' ); ?></a>
		<?php endif; ?>
		<a class="wero-meta__back is-secondary" href="<?php echo esc_url( (string) get_post_type_archive_link( 'progetto' ) ); ?>"><?php esc_html_e( 'Tutti i progetti', 'wero-core' ); ?></a>
	<?php endif; ?>
	<p class="wero-meta__info"><?php echo Blocks::world_tag( $wero_world, 'bar' === $wero_var ? 'wero-tag' : 'tag-mini' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php
	if ( $wero_ty ) :
		?>
		<span><?php echo esc_html( $wero_ty ); ?></span><?php endif; ?></p>
</div>
