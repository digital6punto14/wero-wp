<?php
/**
 * REST in sola lettura per agenti AI e integrazioni: namespace wero/v1.
 *
 * Solo GET, solo dati già pubblici nel sito. Report, partner e voci non hanno
 * pagine pubbliche, quindi la REST standard di WordPress non li mostra a chi
 * non è collegato: queste rotte li espongono in forma pulita e senza scrittura.
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * REST.
 */
final class Rest {

	public const NS = 'wero/v1';

	/**
	 * Hook.
	 */
	public static function register(): void {
		add_action( 'rest_api_init', array( __CLASS__, 'routes' ) );
	}

	/**
	 * Rotte.
	 */
	public static function routes(): void {
		$read = array(
			'methods'             => \WP_REST_Server::READABLE,
			'permission_callback' => '__return_true', // Dati pubblici, sola lettura.
		);
		register_rest_route(
			self::NS,
			'/projects',
			$read + array(
				'callback' => static fn( \WP_REST_Request $r ) => rest_ensure_response( Data::projects( (string) $r['world'], (int) $r['limit'] ) ),
				'args'     => array(
					'world' => array(
						'type'              => 'string',
						'default'           => '',
						'sanitize_callback' => static fn( $v ) => sanitize_title( (string) $v ),
						'description'       => __( 'Slug del mondo per filtrare (vuoto = tutti).', 'wero-core' ),
					),
					'limit' => array(
						'type'    => 'integer',
						'default' => 50,
						'minimum' => 1,
						'maximum' => 100,
					),
				),
			)
		);
		register_rest_route( self::NS, '/reports', $read + array( 'callback' => static fn() => rest_ensure_response( Data::reports() ) ) );
		register_rest_route(
			self::NS,
			'/partners',
			$read + array(
				'callback' => static fn( \WP_REST_Request $r ) => rest_ensure_response( Data::partners( (string) $r['group'] ) ),
				'args'     => array(
					'group' => array(
						'type'    => 'string',
						'enum'    => array( 'all', 'founder', 'business', 'ricerca' ),
						'default' => 'all',
					),
				),
			)
		);
		register_rest_route( self::NS, '/voices', $read + array( 'callback' => static fn() => rest_ensure_response( Data::voices() ) ) );
		register_rest_route( self::NS, '/contact', $read + array( 'callback' => static fn() => rest_ensure_response( Data::contact() ) ) );
	}
}
