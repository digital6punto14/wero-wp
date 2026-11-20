<?php
/**
 * Tassonomia "Mondo" (Robotics Intelligence, Conversation & Events, Language & Design).
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Tassonomie.
 */
final class Taxonomies {

	/**
	 * Hook.
	 */
	public static function register(): void {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ) );
	}

	/**
	 * Registrazione.
	 */
	public static function register_taxonomies(): void {
		register_taxonomy(
			'mondo',
			array( 'progetto', 'report' ),
			array(
				'labels'             => array(
					'name'          => __( 'Mondi', 'wero-core' ),
					'singular_name' => __( 'Mondo', 'wero-core' ),
					'menu_name'     => __( 'Mondi', 'wero-core' ),
					'all_items'     => __( 'Tutti i mondi', 'wero-core' ),
					'edit_item'     => __( 'Modifica mondo', 'wero-core' ),
					'add_new_item'  => __( 'Nuovo mondo', 'wero-core' ),
					'search_items'  => __( 'Cerca mondi', 'wero-core' ),
					'not_found'     => __( 'Nessun mondo', 'wero-core' ),
				),
				'description'        => __( 'I tre mondi di WeRo: card della home e filtri dei progetti.', 'wero-core' ),
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_rest'       => true,
				'show_admin_column'  => true,
				'hierarchical'       => true,
				'rewrite'            => false,
			)
		);
	}

	/**
	 * Mondi ordinati per il campo "ordine" (poi per nome).
	 *
	 * @return \WP_Term[]
	 */
	public static function worlds(): array {
		$terms = get_terms(
			array(
				'taxonomy'   => 'mondo',
				'hide_empty' => false,
			)
		);
		if ( ! is_array( $terms ) ) {
			return array();
		}
		usort(
			$terms,
			static function ( \WP_Term $a, \WP_Term $b ): int {
				$oa = (int) Fields::term( $a, 'ordine', 0 );
				$ob = (int) Fields::term( $b, 'ordine', 0 );
				return 0 !== ( $oa <=> $ob ) ? ( $oa <=> $ob ) : strcmp( $a->name, $b->name );
			}
		);
		return $terms;
	}

	/**
	 * Primo mondo di un contenuto.
	 *
	 * @param int $post_id Contenuto.
	 */
	public static function world_of( int $post_id ): ?\WP_Term {
		$terms = get_the_terms( $post_id, 'mondo' );
		return ( is_array( $terms ) && $terms ) ? $terms[0] : null;
	}
}
