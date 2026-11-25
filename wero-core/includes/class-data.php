<?php
/**
 * Servizio dati condiviso (sola lettura).
 *
 * Una sola fonte per REST (wero/v1), Abilities API e schema: le tre superfici
 * sono adattatori sottili sopra questi metodi, così non divergono.
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Dati pubblici del sito.
 */
final class Data {

	/**
	 * Progetti pubblicati.
	 *
	 * @param string $world Slug del mondo (vuoto = tutti).
	 * @param int    $limit Numero massimo.
	 * @return array<int, array<string, mixed>>
	 */
	public static function projects( string $world = '', int $limit = 50 ): array {
		$args = array(
			'post_type'      => 'progetto',
			'post_status'    => 'publish',
			'posts_per_page' => max( 1, min( 100, $limit ) ),
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
			'no_found_rows'  => true,
		);
		if ( '' !== $world ) {
			$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'taxonomy' => 'mondo',
					'field'    => 'slug',
					'terms'    => sanitize_title( $world ),
				),
			);
		}
		return array_map(
			static function ( \WP_Post $p ): array {
				$w     = Taxonomies::world_of( $p->ID );
				$image = Fields::image_url( Fields::post( $p->ID, 'immagine_card', 0 ), 'large' );
				return array(
					'id'         => $p->ID,
					'title'      => get_the_title( $p ),
					'url'        => get_permalink( $p ),
					'summary'    => (string) Fields::post( $p->ID, 'descrizione_breve', '' ),
					'excerpt'    => wp_strip_all_tags( get_the_excerpt( $p ) ),
					'world'      => $w ? $w->name : '',
					'world_slug' => $w ? $w->slug : '',
					'type'       => (string) Fields::post( $p->ID, 'tipo', '' ),
					'year'       => (string) Fields::post( $p->ID, 'anno', '' ),
					'format'     => self::format( $p->ID ),
					'image'      => $image ? $image : (string) get_the_post_thumbnail_url( $p, 'large' ),
					'media_url'  => ( Media::of_post( $p->ID ) ?? array( 'url' => '' ) )['url'],
				);
			},
			get_posts( $args )
		);
	}

	/**
	 * Formato del progetto (dal template scelto).
	 *
	 * @param int $post_id Progetto.
	 */
	public static function format( int $post_id ): string {
		return match ( (string) get_page_template_slug( $post_id ) ) {
			'progetto-articolo-complesso' => 'articolo-complesso',
			'progetto-video'              => 'video',
			'progetto-podcast'            => 'podcast',
			default                       => 'articolo',
		};
	}

	/**
	 * Report.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public static function reports(): array {
		return array_map(
			static function ( \WP_Post $r ): array {
				$w    = Taxonomies::world_of( $r->ID );
				$proj = Fields::relation( $r->ID, 'progetto' );
				return array(
					'title'       => get_the_title( $r ),
					'year'        => (string) Fields::post( $r->ID, 'anno', '' ),
					'status'      => (string) Fields::post( $r->ID, 'stato', 'pubblicato' ),
					'description' => (string) Fields::post( $r->ID, 'descrizione', '' ),
					// Il PDF si scarica dalla pagina con il modulo (D54): niente link diretto.
					'download'    => Reports::is_downloadable( $r->ID ) ? Reports::page_url( $r->ID ) : '',
					'world'       => $w ? $w->name : '',
					'project_url' => $proj ? (string) get_permalink( $proj[0] ) : '',
				);
			},
			get_posts(
				array(
					'post_type'      => 'report',
					'post_status'    => 'publish',
					'posts_per_page' => 50,
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'no_found_rows'  => true,
				)
			)
		);
	}

	/**
	 * Partner.
	 *
	 * @param string $group founder|business|ricerca|all.
	 * @return array<int, array<string, mixed>>
	 */
	public static function partners( string $group = 'all' ): array {
		$out = array();
		foreach ( get_posts(
			array(
				'post_type'      => 'partner',
				'post_status'    => 'publish',
				'posts_per_page' => 100,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'no_found_rows'  => true,
			)
		) as $p ) {
			$founder = (bool) Fields::post( $p->ID, 'founder', false );
			$type    = (string) Fields::post( $p->ID, 'tipo', 'business' );
			if ( 'founder' === $group && ! $founder ) {
				continue;
			}
			if ( in_array( $group, array( 'business', 'ricerca' ), true ) && ( $founder || $type !== $group ) ) {
				continue;
			}
			$out[] = array(
				'name'        => get_the_title( $p ),
				'founder'     => $founder,
				'type'        => $type,
				'description' => (string) Fields::post( $p->ID, 'descrizione', '' ),
				'url'         => (string) Fields::post( $p->ID, 'sito', '' ),
				'logo'        => Fields::image_url( Fields::post( $p->ID, 'logo', 0 ), 'medium' ),
			);
		}
		return $out;
	}

	/**
	 * Contatti e dati dell'organizzazione.
	 *
	 * @return array<string, mixed>
	 */
	public static function contact(): array {
		return array(
			'name'         => (string) Settings::get( 'org_name' ),
			'full_name'    => (string) Settings::get( 'org_alternate' ),
			'description'  => (string) Settings::get( 'org_description' ),
			'email'        => (string) Settings::get( 'email' ),
			'linkedin'     => (string) Settings::get( 'linkedin' ),
			'website'      => home_url( '/' ),
			'partner_form' => home_url( '/#partner' ),
			'privacy'      => (string) get_privacy_policy_url(),
			'founders'     => array(
				array(
					'name'    => (string) Settings::get( 'founder1_name' ),
					'url'     => (string) Settings::get( 'founder1_url' ),
					'address' => trim( Settings::get( 'founder1_street' ) . ', ' . Settings::get( 'founder1_postal' ) . ' ' . Settings::get( 'founder1_city' ), ', ' ),
					'vat'     => (string) Settings::get( 'founder1_vat' ),
				),
				array(
					'name' => (string) Settings::get( 'founder2_name' ),
					'url'  => (string) Settings::get( 'founder2_url' ),
				),
			),
		);
	}
}
