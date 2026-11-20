<?php
/**
 * Abilities API (WordPress 6.9+): funzioni in sola lettura per gli agenti AI.
 *
 * Abilities: wero/get-projects, wero/get-reports, wero/get-partners, wero/get-contact-info.
 * Visibili su /wp-json/wp-abilities/v1 (show_in_rest) e pronte per il plugin
 * ufficiale MCP Adapter (meta.mcp.public), che non è installato: vedi README.
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Abilities.
 */
final class Abilities {

	/**
	 * Hook (attivi solo se l'API esiste).
	 */
	public static function register(): void {
		add_action( 'wp_abilities_api_categories_init', array( __CLASS__, 'category' ) );
		add_action( 'wp_abilities_api_init', array( __CLASS__, 'abilities' ) );
	}

	/**
	 * Categoria.
	 */
	public static function category(): void {
		if ( function_exists( 'wp_register_ability_category' ) ) {
			wp_register_ability_category(
				'wero',
				array(
					'label'       => __( 'WeRo', 'wero-core' ),
					'description' => __( 'Informazioni pubbliche di WeRo, Robot Communication Studio.', 'wero-core' ),
				)
			);
		}
	}

	/**
	 * Meta comuni: sola lettura, REST, MCP.
	 *
	 * @return array<string, mixed>
	 */
	private static function meta(): array {
		return array(
			'show_in_rest' => true,
			'mcp'          => array( 'public' => true ),
			'annotations'  => array(
				'readonly'    => true,
				'destructive' => false,
				'idempotent'  => true,
			),
		);
	}

	/**
	 * Schema di una lista di oggetti.
	 *
	 * @param array<string, array<string, mixed>> $props Proprietà.
	 * @return array<string, mixed>
	 */
	private static function list_of( array $props ): array {
		return array(
			'type'  => 'array',
			'items' => array(
				'type'       => 'object',
				'properties' => $props,
			),
		);
	}

	/**
	 * Registrazione.
	 */
	public static function abilities(): void {
		if ( ! function_exists( 'wp_register_ability' ) ) {
			return;
		}
		$str = array( 'type' => 'string' );

		wp_register_ability(
			'wero/get-projects',
			array(
				'label'               => __( 'Progetti di WeRo', 'wero-core' ),
				'description'         => __( 'Elenco dei progetti pubblicati: titolo, link, descrizione, mondo, tipo, anno, formato e immagine. Filtrabile per mondo.', 'wero-core' ),
				'category'            => 'wero',
				'input_schema'        => array(
					'type'                 => 'object',
					'properties'           => array(
						'world' => array(
							'type'        => 'string',
							'description' => __( 'Slug del mondo (es. robotics-intelligence). Vuoto = tutti.', 'wero-core' ),
							'default'     => '',
						),
						'limit' => array(
							'type'    => 'integer',
							'minimum' => 1,
							'maximum' => 100,
							'default' => 50,
						),
					),
					'additionalProperties' => false,
				),
				'output_schema'       => self::list_of(
					array(
						'id'         => array( 'type' => 'integer' ),
						'title'      => $str,
						'url'        => $str,
						'summary'    => $str,
						'excerpt'    => $str,
						'world'      => $str,
						'world_slug' => $str,
						'type'       => $str,
						'year'       => $str,
						'format'     => $str,
						'image'      => $str,
						'media_url'  => $str,
					)
				),
				'execute_callback'    => static function ( $input = array() ): array {
					$input = is_array( $input ) ? $input : array();
					return Data::projects( (string) ( $input['world'] ?? '' ), (int) ( $input['limit'] ?? 50 ) );
				},
				'permission_callback' => '__return_true',
				'meta'                => self::meta(),
			)
		);

		wp_register_ability(
			'wero/get-reports',
			array(
				'label'               => __( 'Report di WeRo', 'wero-core' ),
				'description'         => __( 'Report pubblicati e in arrivo: titolo, anno, stato, descrizione, PDF e progetto collegato.', 'wero-core' ),
				'category'            => 'wero',
				'output_schema'       => self::list_of(
					array(
						'title'       => $str,
						'year'        => $str,
						'status'      => $str,
						'description' => $str,
						'pdf'         => $str,
						'world'       => $str,
						'project_url' => $str,
					)
				),
				'execute_callback'    => static fn(): array => Data::reports(),
				'permission_callback' => '__return_true',
				'meta'                => self::meta(),
			)
		);

		wp_register_ability(
			'wero/get-partners',
			array(
				'label'               => __( 'Partner di WeRo', 'wero-core' ),
				'description'         => __( 'Founder e collaboratori (Business, Ricerca) con descrizione e sito.', 'wero-core' ),
				'category'            => 'wero',
				'input_schema'        => array(
					'type'                 => 'object',
					'properties'           => array(
						'group' => array(
							'type'    => 'string',
							'enum'    => array( 'all', 'founder', 'business', 'ricerca' ),
							'default' => 'all',
						),
					),
					'additionalProperties' => false,
				),
				'output_schema'       => self::list_of(
					array(
						'name'        => $str,
						'founder'     => array( 'type' => 'boolean' ),
						'type'        => $str,
						'description' => $str,
						'url'         => $str,
						'logo'        => $str,
					)
				),
				'execute_callback'    => static function ( $input = array() ): array {
					$input = is_array( $input ) ? $input : array();
					return Data::partners( (string) ( $input['group'] ?? 'all' ) );
				},
				'permission_callback' => '__return_true',
				'meta'                => self::meta(),
			)
		);

		wp_register_ability(
			'wero/get-contact-info',
			array(
				'label'               => __( 'Contatti di WeRo', 'wero-core' ),
				'description'         => __( 'Nome, descrizione, email, LinkedIn, modulo partner, privacy e founder della joint venture.', 'wero-core' ),
				'category'            => 'wero',
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'name'         => $str,
						'full_name'    => $str,
						'description'  => $str,
						'email'        => $str,
						'linkedin'     => $str,
						'website'      => $str,
						'partner_form' => $str,
						'privacy'      => $str,
						'founders'     => array( 'type' => 'array' ),
					),
				),
				'execute_callback'    => static fn(): array => Data::contact(),
				'permission_callback' => '__return_true',
				'meta'                => self::meta(),
			)
		);
	}
}
