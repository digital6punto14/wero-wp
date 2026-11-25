<?php
/**
 * SEO: estende Yoast SEO (l'unico plugin SEO). Tema e plugin non stampano meta,
 * canonical, Open Graph o JSON-LD propri: aggiungono dati al grafo di Yoast.
 *
 * - Organization (Yoast): descrizione, email, LinkedIn e founder della joint venture
 *   presi dalle Impostazioni sito (un solo punto di modifica).
 * - Progetto video: VideoObject; progetto podcast: PodcastEpisode — solo se il video
 *   o l'audio c'è davvero (contenuto visibile in pagina).
 *
 * Filtri Yoast usati: wpseo_schema_organization, wpseo_schema_organization_social_profiles,
 * wpseo_schema_graph.
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Integrazione con Yoast.
 */
final class Seo {

	/**
	 * Hook (senza Yoast non fanno nulla).
	 */
	public static function register(): void {
		add_filter( 'wpseo_schema_organization', array( __CLASS__, 'organization' ) );
		add_filter( 'wpseo_schema_organization_social_profiles', array( __CLASS__, 'social' ) );
		add_filter( 'wpseo_schema_graph', array( __CLASS__, 'graph' ), 10, 2 );
	}

	/**
	 * Organization: dati di WeRo e founder.
	 *
	 * @param array<string, mixed> $data Nodo Organization.
	 * @return array<string, mixed>
	 */
	public static function organization( $data ): array {
		$data     = is_array( $data ) ? $data : array();
		$c        = Data::contact();
		$f1       = $c['founders'][0];
		$f2       = $c['founders'][1];
		$data    += array_filter(
			array(
				'description'   => $c['description'],
				'email'         => $c['email'],
				'alternateName' => $c['full_name'],
			)
		);
		$founders = array();
		if ( $f1['name'] ) {
			$founders[] = array_filter(
				array(
					'@type'   => 'Organization',
					'name'    => $f1['name'],
					'url'     => $f1['url'],
					'vatID'   => $f1['vat'],
					'address' => array(
						'@type'           => 'PostalAddress',
						'streetAddress'   => (string) Settings::get( 'founder1_street' ),
						'postalCode'      => (string) Settings::get( 'founder1_postal' ),
						'addressLocality' => (string) Settings::get( 'founder1_city' ),
						'addressCountry'  => 'IT',
					),
				)
			);
		}
		if ( $f2['name'] ) {
			$founders[] = array_filter(
				array(
					'@type' => 'Organization',
					'name'  => $f2['name'],
					'url'   => $f2['url'],
				)
			);
		}
		if ( $founders ) {
			$data['founder']            = $founders;
			$data['parentOrganization'] = $founders;
		}
		return $data;
	}

	/**
	 * LinkedIn dalle Impostazioni sito.
	 *
	 * @param string[] $profiles Profili.
	 * @return string[]
	 */
	public static function social( $profiles ): array {
		$profiles = is_array( $profiles ) ? $profiles : array();
		$linkedin = (string) Settings::get( 'linkedin' );
		if ( $linkedin && 'https://www.linkedin.com/' !== $linkedin && ! in_array( $linkedin, $profiles, true ) ) {
			$profiles[] = $linkedin;
		}
		return $profiles;
	}

	/**
	 * "16:24" o "1:02:03" → durata ISO 8601.
	 *
	 * @param string $time Durata.
	 */
	private static function iso_duration( string $time ): string {
		$parts = array_map( 'intval', explode( ':', trim( $time ) ) );
		if ( ! $time || count( $parts ) < 2 ) {
			return '';
		}
		$s = 0;
		foreach ( $parts as $p ) {
			$s = $s * 60 + $p;
		}
		return sprintf( 'PT%dH%dM%dS', intdiv( $s, 3600 ), intdiv( $s % 3600, 60 ), $s % 60 );
	}

	/**
	 * Nodo VideoObject.
	 *
	 * @param string               $id    @id.
	 * @param string               $name  Titolo.
	 * @param string               $desc  Descrizione.
	 * @param string               $thumb Copertina.
	 * @param string               $date  Data ISO.
	 * @param array<string,string> $media Media analizzato.
	 * @param string               $page  @id della pagina.
	 * @param string               $dur   Durata.
	 * @return array<string, mixed>
	 */
	private static function video_node( string $id, string $name, string $desc, string $thumb, string $date, array $media, string $page, string $dur = '' ): array {
		return array_filter(
			array(
				'@type'            => 'VideoObject',
				'@id'              => $id,
				'name'             => $name,
				'description'      => $desc ? $desc : $name,
				'thumbnailUrl'     => $thumb,
				'uploadDate'       => $date,
				'duration'         => self::iso_duration( $dur ),
				'contentUrl'       => 'video' === $media['kind'] ? $media['src'] : '',
				'embedUrl'         => 'embed' === $media['kind'] ? $media['src'] : '',
				'url'              => 'embed' === $media['kind'] ? $media['url'] : '',
				'inLanguage'       => 'it-IT',
				'mainEntityOfPage' => array( '@id' => $page ),
			)
		);
	}

	/**
	 * Aggiunge al grafo di Yoast i nodi dei contenuti visibili in pagina.
	 *
	 * @param array<int, array<string, mixed>> $graph   Grafo.
	 * @param object                           $context Contesto Yoast (Meta_Tags_Context).
	 * @return array<int, array<string, mixed>>
	 */
	public static function graph( $graph, $context ): array {
		$graph = is_array( $graph ) ? $graph : array();
		$page  = isset( $context->canonical ) ? (string) $context->canonical : '';

		if ( is_singular( 'progetto' ) ) {
			$id     = get_queried_object_id();
			$media  = Media::of_post( $id );
			$format = Data::format( $id );
			if ( ! $media || 'link' === $media['kind'] ) {
				return $graph;
			}
			$thumb = Fields::image_url( Fields::post( $id, 'media_poster', 0 ), 'large' );
			$thumb = $thumb ? $thumb : (string) get_the_post_thumbnail_url( $id, 'large' );
			$desc  = wp_strip_all_tags( get_the_excerpt( $id ) );
			$date  = (string) get_the_date( 'c', $id );
			$dur   = (string) Fields::post( $id, 'media_durata', '' );
			if ( 'video' === $format && in_array( $media['kind'], array( 'video', 'embed' ), true ) ) {
				$graph[] = self::video_node( $page . '#video', get_the_title( $id ), $desc, $thumb, $date, $media, $page, $dur );
			}
			if ( 'podcast' === $format ) {
				$graph[] = array_filter(
					array(
						'@type'            => 'PodcastEpisode',
						'@id'              => $page . '#podcast',
						'name'             => get_the_title( $id ),
						'description'      => $desc,
						'datePublished'    => $date,
						'timeRequired'     => self::iso_duration( $dur ),
						'url'              => $page,
						'inLanguage'       => 'it-IT',
						'associatedMedia'  => array_filter(
							array(
								'@type'      => 'audio' === $media['kind'] ? 'AudioObject' : 'MediaObject',
								'contentUrl' => 'audio' === $media['kind'] ? $media['src'] : '',
								'embedUrl'   => 'embed' === $media['kind'] ? $media['src'] : '',
							)
						),
						'mainEntityOfPage' => array( '@id' => $page ),
					)
				);
			}
			return $graph;
		}

		return $graph;
	}
}
