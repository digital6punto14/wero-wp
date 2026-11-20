<?php
/**
 * Blocchi del plugin (build/blocks, generati con @wordpress/scripts).
 *
 * Registrati dai metadati (block.json) con il manifest dei blocchi.
 * Ogni blocco porta con sé CSS e JS, caricati solo dove il blocco compare.
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Blocchi.
 */
final class Blocks {

	/**
	 * Hook.
	 */
	public static function register(): void {
		add_action( 'init', array( __CLASS__, 'register_blocks' ) );
		add_filter( 'block_categories_all', array( __CLASS__, 'category' ) );
	}

	/**
	 * Registrazione.
	 */
	public static function register_blocks(): void {
		$dir      = WERO_CORE_DIR . 'build/blocks';
		$manifest = $dir . '/blocks-manifest.php';
		if ( ! file_exists( $manifest ) ) {
			return;
		}
		if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
			wp_register_block_types_from_metadata_collection( $dir, $manifest );
			return;
		}
		foreach ( array_keys( require $manifest ) as $name ) {
			register_block_type( $dir . '/' . $name );
		}
	}

	/**
	 * Categoria "WeRo" nell'inseritore.
	 *
	 * @param array<int, array<string, string>> $categories Categorie.
	 * @return array<int, array<string, string>>
	 */
	public static function category( array $categories ): array {
		array_unshift(
			$categories,
			array(
				'slug'  => 'wero',
				'title' => __( 'WeRo', 'wero-core' ),
			)
		);
		return $categories;
	}

	/**
	 * Bollino del mondo (stile dal campo "Stile del bollino").
	 *
	 * @param \WP_Term|null $term Mondo.
	 * @param string        $cls  Classe base.
	 */
	public static function world_tag( ?\WP_Term $term, string $cls = 'wero-tag' ): string {
		if ( ! $term ) {
			return '';
		}
		$style = (string) Fields::term( $term, 'stile', 'scuro' );
		return sprintf( '<span class="%1$s %1$s--%2$s">%3$s</span>', esc_attr( $cls ), esc_attr( $style ), esc_html( $term->name ) );
	}

	/**
	 * "Tipo, anno" di un progetto.
	 *
	 * @param int $post_id Progetto.
	 */
	public static function type_year( int $post_id ): string {
		return implode( ', ', array_filter( array( (string) Fields::post( $post_id, 'tipo' ), (string) Fields::post( $post_id, 'anno' ) ) ) );
	}

	/**
	 * Logo WeRo (marchio) in SVG, per copertine generate.
	 */
	public static function mark_url(): string {
		return WERO_CORE_URL . 'assets/wero-mark.svg';
	}
}
