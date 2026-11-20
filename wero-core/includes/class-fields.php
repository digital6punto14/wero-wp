<?php
/**
 * Lettura dei campi: usa ACF se attivo, altrimenti i metadati (stessi nomi),
 * così il sito non va in errore senza ACF.
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Accesso ai campi.
 */
final class Fields {

	/**
	 * Campo di un contenuto.
	 *
	 * @param int    $post_id Contenuto.
	 * @param string $name    Nome del campo.
	 * @param mixed  $fallback Valore se vuoto.
	 * @return mixed
	 */
	public static function post( int $post_id, string $name, $fallback = '' ) {
		$v = function_exists( 'get_field' ) ? get_field( $name, $post_id, false ) : get_post_meta( $post_id, $name, true );
		return ( null === $v || '' === $v || false === $v || array() === $v ) ? $fallback : $v;
	}

	/**
	 * Campo di un termine.
	 *
	 * @param \WP_Term $term    Termine.
	 * @param string   $name    Nome del campo.
	 * @param mixed    $fallback Valore se vuoto.
	 * @return mixed
	 */
	public static function term( \WP_Term $term, string $name, $fallback = '' ) {
		$v = function_exists( 'get_field' ) ? get_field( $name, $term, false ) : get_term_meta( $term->term_id, $name, true );
		return ( null === $v || '' === $v || false === $v ) ? $fallback : $v;
	}

	/**
	 * URL di un'immagine salvata come ID allegato.
	 *
	 * @param mixed  $id   ID.
	 * @param string $size Dimensione.
	 */
	public static function image_url( $id, string $size = 'large' ): string {
		$id = absint( $id );
		if ( ! $id ) {
			return '';
		}
		$url = wp_get_attachment_image_url( $id, $size );
		return $url ? $url : '';
	}

	/**
	 * Tag <img> responsive di un allegato (srcset, sizes, lazy di WordPress).
	 *
	 * @param mixed                $id    ID.
	 * @param string               $size  Dimensione.
	 * @param array<string,string> $attr  Attributi.
	 */
	public static function image( $id, string $size = 'large', array $attr = array() ): string {
		$id = absint( $id );
		return $id ? wp_get_attachment_image( $id, $size, false, $attr ) : '';
	}

	/**
	 * URL di un file (campo File salvato come ID).
	 *
	 * @param mixed $id ID.
	 */
	public static function file_url( $id ): string {
		$id = absint( $id );
		if ( ! $id ) {
			return '';
		}
		$url = wp_get_attachment_url( $id );
		return $url ? $url : '';
	}

	/**
	 * ID dei contenuti di un campo Relazione.
	 *
	 * @param int    $post_id Contenuto.
	 * @param string $name    Nome del campo.
	 * @return int[]
	 */
	public static function relation( int $post_id, string $name ): array {
		$v = self::post( $post_id, $name, array() );
		if ( is_string( $v ) ) {
			$v = maybe_unserialize( $v );
		}
		return array_values( array_filter( array_map( 'absint', (array) $v ) ) );
	}
}
