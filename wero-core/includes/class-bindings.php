<?php
/**
 * Block Bindings.
 *
 * - `wero/site`: dati delle Impostazioni sito (email, LinkedIn, copyright, nome…)
 *   in qualsiasi Paragrafo, Titolo, Pulsante o Immagine. Un solo punto di modifica.
 * - `wero/field`: un campo ACF del contenuto corrente (anche dentro un Query Loop).
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Sorgenti Block Bindings.
 */
final class Bindings {

	/**
	 * Hook.
	 */
	public static function register(): void {
		add_action( 'init', array( __CLASS__, 'sources' ) );
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'editor' ) );
	}

	/**
	 * Registrazione delle sorgenti.
	 */
	public static function sources(): void {
		if ( ! function_exists( 'register_block_bindings_source' ) ) {
			return;
		}
		register_block_bindings_source(
			'wero/site',
			array(
				'label'              => __( 'Impostazioni sito WeRo', 'wero-core' ),
				'get_value_callback' => array( __CLASS__, 'site_value' ),
			)
		);
		register_block_bindings_source(
			'wero/field',
			array(
				'label'              => __( 'Campo del contenuto (ACF)', 'wero-core' ),
				'get_value_callback' => array( __CLASS__, 'field_value' ),
				'uses_context'       => array( 'postId', 'postType' ),
			)
		);
	}

	/**
	 * Chiavi disponibili per wero/site.
	 *
	 * @return array<string, string>
	 */
	public static function site_keys(): array {
		return array(
			'org_name'      => __( 'Nome', 'wero-core' ),
			'email'         => __( 'Email', 'wero-core' ),
			'email_link'    => __( 'Email come link', 'wero-core' ),
			'email_url'     => __( 'Email (mailto: per pulsanti)', 'wero-core' ),
			'linkedin'      => __( 'URL LinkedIn', 'wero-core' ),
			'linkedin_link' => __( 'LinkedIn come link', 'wero-core' ),
			'copyright'     => __( 'Copyright con l’anno', 'wero-core' ),
		);
	}

	/**
	 * Valore per wero/site.
	 *
	 * @param array<string, mixed> $args Argomenti (key).
	 * @return string|null
	 */
	public static function site_value( array $args ) {
		$key      = (string) ( $args['key'] ?? '' );
		$email    = (string) Settings::get( 'email' );
		$linkedin = (string) Settings::get( 'linkedin' );
		return match ( $key ) {
			'org_name'      => esc_html( (string) Settings::get( 'org_name' ) ),
			'email'         => esc_html( $email ),
			'email_url'     => esc_url( 'mailto:' . $email ),
			'email_link'    => $email ? sprintf( '<a href="%s">%s</a>', esc_url( 'mailto:' . $email ), esc_html( $email ) ) : '',
			'linkedin'      => esc_url( $linkedin ),
			'linkedin_link' => $linkedin ? sprintf( '<a href="%s" target="_blank" rel="noreferrer noopener">LinkedIn</a>', esc_url( $linkedin ) ) : '',
			'copyright'     => esc_html( '© ' . wp_date( 'Y' ) . ' ' . Settings::get( 'copyright' ) ),
			default         => null,
		};
	}

	/**
	 * Valore per wero/field.
	 *
	 * @param array<string, mixed> $args           Argomenti (key, size per le immagini).
	 * @param \WP_Block            $block_instance Blocco.
	 * @param string               $attribute_name Attributo collegato.
	 * @return string|null
	 */
	public static function field_value( array $args, $block_instance, string $attribute_name ) {
		$key     = sanitize_key( (string) ( $args['key'] ?? '' ) );
		$post_id = (int) ( $block_instance->context['postId'] ?? get_the_ID() );
		if ( ! $key || ! $post_id ) {
			return null;
		}
		$value = Fields::post( $post_id, $key, '' );
		if ( 'url' === $attribute_name && is_numeric( $value ) ) {
			$url = wp_get_attachment_url( (int) $value );
			return $url ? esc_url( $url ) : null;
		}
		if ( is_array( $value ) ) {
			return null;
		}
		return in_array( $attribute_name, array( 'url', 'href' ), true ) ? esc_url( (string) $value ) : esc_html( (string) $value );
	}

	/**
	 * Editor: valori veri di wero/site nei blocchi collegati (sola lettura).
	 */
	public static function editor(): void {
		wp_enqueue_script( 'wero-bindings-editor', WERO_CORE_URL . 'assets/editor/bindings.js', array( 'wp-blocks' ), VERSION, true );
		$values = array();
		foreach ( array_keys( self::site_keys() ) as $key ) {
			$values[ $key ] = (string) self::site_value( array( 'key' => $key ) );
		}
		wp_add_inline_script( 'wero-bindings-editor', 'window.weroSiteBindings = ' . wp_json_encode( $values ) . ';', 'before' );
	}
}
