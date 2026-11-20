<?php
/**
 * Video e audio da "qualsiasi link" (D15).
 *
 * Riconosce file (mp4, webm, mp3…), YouTube, Vimeo, Spotify, SoundCloud e Apple Podcasts.
 * Per i servizi esterni restituisce un URL di incorporamento che i blocchi caricano
 * SOLO al clic del visitatore (facciata): nessuna richiesta a terze parti a caricamento pagina.
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Media.
 */
final class Media {

	/**
	 * Analizza un link o un file.
	 *
	 * @param string $url URL.
	 * @return array{kind:string, provider:string, src:string, url:string}|null kind: video|audio|embed.
	 */
	public static function parse( string $url ): ?array {
		$url = trim( $url );
		if ( '' === $url ) {
			return null;
		}
		$path = strtolower( (string) wp_parse_url( $url, PHP_URL_PATH ) );
		$host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
		$host = preg_replace( '/^www\./', '', $host );

		if ( preg_match( '/\.(mp4|webm|mov|m4v|ogv)$/', $path ) ) {
			return array( 'kind' => 'video', 'provider' => 'file', 'src' => $url, 'url' => $url );
		}
		if ( preg_match( '/\.(mp3|m4a|ogg|oga|wav|aac)$/', $path ) ) {
			return array( 'kind' => 'audio', 'provider' => 'file', 'src' => $url, 'url' => $url );
		}
		if ( in_array( $host, array( 'youtube.com', 'm.youtube.com', 'youtu.be', 'youtube-nocookie.com' ), true ) ) {
			$id = '';
			if ( 'youtu.be' === $host ) {
				$id = ltrim( (string) wp_parse_url( $url, PHP_URL_PATH ), '/' );
			} else {
				parse_str( (string) wp_parse_url( $url, PHP_URL_QUERY ), $q );
				$id = (string) ( $q['v'] ?? '' );
				if ( ! $id && preg_match( '#/(embed|shorts|live)/([^/?]+)#', $path, $m ) ) {
					$id = $m[2];
				}
			}
			$id = preg_replace( '/[^A-Za-z0-9_-]/', '', $id );
			return $id ? array( 'kind' => 'embed', 'provider' => 'youtube', 'src' => 'https://www.youtube-nocookie.com/embed/' . $id . '?autoplay=1&rel=0', 'url' => $url ) : null;
		}
		if ( 'vimeo.com' === $host || 'player.vimeo.com' === $host ) {
			if ( preg_match( '#/(\d+)#', $path, $m ) ) {
				return array( 'kind' => 'embed', 'provider' => 'vimeo', 'src' => 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1&dnt=1', 'url' => $url );
			}
			return null;
		}
		if ( 'open.spotify.com' === $host && preg_match( '#^/(episode|show|track|playlist|album)/([A-Za-z0-9]+)#', (string) wp_parse_url( $url, PHP_URL_PATH ), $m ) ) {
			return array( 'kind' => 'embed', 'provider' => 'spotify', 'src' => 'https://open.spotify.com/embed/' . $m[1] . '/' . $m[2], 'url' => $url );
		}
		if ( 'soundcloud.com' === $host ) {
			return array( 'kind' => 'embed', 'provider' => 'soundcloud', 'src' => 'https://w.soundcloud.com/player/?url=' . rawurlencode( $url ) . '&auto_play=true', 'url' => $url );
		}
		if ( 'podcasts.apple.com' === $host ) {
			return array( 'kind' => 'embed', 'provider' => 'apple', 'src' => 'https://embed.podcasts.apple.com' . (string) wp_parse_url( $url, PHP_URL_PATH ) . ( wp_parse_url( $url, PHP_URL_QUERY ) ? '?' . wp_parse_url( $url, PHP_URL_QUERY ) : '' ), 'url' => $url );
		}
		return array( 'kind' => 'link', 'provider' => 'link', 'src' => $url, 'url' => $url );
	}

	/**
	 * Media di un contenuto: prima il file caricato, poi il link.
	 *
	 * @param int $post_id Contenuto.
	 * @return array{kind:string, provider:string, src:string, url:string}|null
	 */
	public static function of_post( int $post_id ): ?array {
		$file = Fields::file_url( Fields::post( $post_id, 'media_file', 0 ) );
		return self::parse( $file ? $file : (string) Fields::post( $post_id, 'media_link', '' ) );
	}

	/**
	 * Nome leggibile del servizio.
	 *
	 * @param string $provider Provider.
	 */
	public static function provider_label( string $provider ): string {
		return match ( $provider ) {
			'youtube'    => 'YouTube',
			'vimeo'      => 'Vimeo',
			'spotify'    => 'Spotify',
			'soundcloud' => 'SoundCloud',
			'apple'      => 'Apple Podcasts',
			default      => '',
		};
	}
}
