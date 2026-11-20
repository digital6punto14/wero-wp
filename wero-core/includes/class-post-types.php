<?php
/**
 * Tipi di contenuto: Progetto, Report, Partner, Voce, Persona.
 *
 * Registrati nel codice (non dall'interfaccia ACF) per restare sotto controllo di versione.
 * Solo i Progetti hanno pagine pubbliche; gli altri alimentano i blocchi della home.
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Post type.
 */
final class Post_Types {

	/**
	 * Hook.
	 */
	public static function register(): void {
		add_action( 'init', array( __CLASS__, 'register_post_types' ) );
		add_action( 'pre_get_posts', array( __CLASS__, 'archive_order' ) );
	}

	/**
	 * Etichette italiane.
	 *
	 * @param string $singular Singolare.
	 * @param string $plural   Plurale.
	 * @param bool   $female   Femminile.
	 * @return array<string,string>
	 */
	private static function labels( string $singular, string $plural, bool $female = false ): array {
		$new = $female ? __( 'Nuova', 'wero-core' ) : __( 'Nuovo', 'wero-core' );
		return array(
			'name'               => $plural,
			'singular_name'      => $singular,
			'menu_name'          => $plural,
			'add_new'            => __( 'Aggiungi', 'wero-core' ),
			'add_new_item'       => $new . ' ' . strtolower( $singular ),
			'edit_item'          => __( 'Modifica', 'wero-core' ) . ' ' . strtolower( $singular ),
			'new_item'           => $new . ' ' . strtolower( $singular ),
			'view_item'          => __( 'Visualizza', 'wero-core' ) . ' ' . strtolower( $singular ),
			'search_items'       => __( 'Cerca', 'wero-core' ) . ' ' . strtolower( $plural ),
			'not_found'          => __( 'Nessun elemento trovato', 'wero-core' ),
			'not_found_in_trash' => __( 'Nessun elemento nel cestino', 'wero-core' ),
			'all_items'          => __( 'Tutti', 'wero-core' ) . ( $female ? 'e' : '' ) . ' ' . strtolower( $plural ),
		);
	}

	/**
	 * Registrazione.
	 */
	public static function register_post_types(): void {
		register_post_type(
			'progetto',
			array(
				'labels'        => self::labels( __( 'Progetto', 'wero-core' ), __( 'Progetti', 'wero-core' ) ),
				'description'   => __( 'Progetti di WeRo: articoli, articoli complessi, video e podcast.', 'wero-core' ),
				'public'        => true,
				'show_in_rest'  => true,
				'has_archive'   => 'progetti',
				'rewrite'       => array(
					'slug'       => 'progetti',
					'with_front' => false,
				),
				'menu_icon'     => 'dashicons-portfolio',
				'menu_position' => 5,
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes' ),
			)
		);

		$hidden = array(
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'has_archive'         => false,
			'rewrite'             => false,
		);

		register_post_type(
			'report',
			array_merge(
				$hidden,
				array(
					'labels'        => self::labels( __( 'Report', 'wero-core' ), __( 'Report', 'wero-core' ) ),
					'description'   => __( 'Report scaricabili mostrati nella timeline della home.', 'wero-core' ),
					'menu_icon'     => 'dashicons-media-document',
					'menu_position' => 6,
					'supports'      => array( 'title', 'thumbnail', 'custom-fields', 'page-attributes' ),
				)
			)
		);
		register_post_type(
			'partner',
			array_merge(
				$hidden,
				array(
					'labels'        => self::labels( __( 'Partner', 'wero-core' ), __( 'Partner', 'wero-core' ) ),
					'description'   => __( 'Founder e collaboratori: loghi della home.', 'wero-core' ),
					'menu_icon'     => 'dashicons-groups',
					'menu_position' => 7,
					'supports'      => array( 'title', 'custom-fields', 'page-attributes' ),
				)
			)
		);
		register_post_type(
			'voce',
			array_merge(
				$hidden,
				array(
					'labels'        => array_merge(
						self::labels( __( 'Voce', 'wero-core' ), __( 'Voci dei progetti', 'wero-core' ), true ),
						array(
							'featured_image'        => __( 'Copertina', 'wero-core' ),
							'set_featured_image'    => __( 'Imposta la copertina', 'wero-core' ),
							'remove_featured_image' => __( 'Rimuovi la copertina', 'wero-core' ),
							'use_featured_image'    => __( 'Usa come copertina', 'wero-core' ),
						)
					),
					'description'   => __( 'Video della sezione “Le voci dei progetti”.', 'wero-core' ),
					'menu_icon'     => 'dashicons-video-alt3',
					'menu_position' => 8,
					'supports'      => array( 'title', 'thumbnail', 'custom-fields', 'page-attributes' ),
				)
			)
		);
		register_post_type(
			'persona',
			array_merge(
				$hidden,
				array(
					'labels'        => self::labels( __( 'Persona', 'wero-core' ), __( 'Persone', 'wero-core' ), true ),
					'description'   => __( 'Ospiti e relatori collegati ai progetti video e podcast.', 'wero-core' ),
					'menu_icon'     => 'dashicons-id',
					'menu_position' => 9,
					'supports'      => array( 'title', 'thumbnail', 'custom-fields' ),
				)
			)
		);
	}

	/**
	 * Archivio /progetti/: stesso ordine e stessi progetti della sfera in home
	 * (campo "Ordine", poi data), tutti in una pagina come la vista a griglia.
	 *
	 * @param \WP_Query $query Query.
	 */
	public static function archive_order( \WP_Query $query ): void {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'progetto' ) ) {
			return;
		}
		$query->set(
			'orderby',
			array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			)
		);
		$query->set( 'posts_per_page', 40 );
	}
}
