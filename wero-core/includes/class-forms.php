<?php
/**
 * Contact Form 7.
 *
 * - CSS e JS di CF7 caricati solo nelle pagine che contengono un modulo
 *   (filtri wpcf7_load_js / wpcf7_load_css + caricamento quando il blocco viene reso).
 * - Blocco del modulo "Diventa partner" per il pattern del tema (filtro wero_partner_form_block).
 * - form_block(): markup del blocco CF7 di un modulo, usato anche per “Scarica i report”.
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Moduli.
 */
final class Forms {

	/**
	 * Hook.
	 */
	public static function register(): void {
		add_filter( 'wpcf7_load_js', '__return_false' );
		add_filter( 'wpcf7_load_css', '__return_false' );
		// Il markup dei moduli è scritto per il CSS del tema: niente <p> e <br> automatici.
		add_filter( 'wpcf7_autop_or_not', '__return_false' );
		add_filter( 'render_block_contact-form-7/contact-form-selector', array( __CLASS__, 'enqueue_on_render' ) );
		add_filter( 'wero_partner_form_block', array( __CLASS__, 'partner_form_block' ) );
	}

	/**
	 * Carica gli asset di CF7 quando un modulo compare nella pagina.
	 *
	 * @param string $content Markup.
	 */
	public static function enqueue_on_render( string $content ): string {
		// Nei temi a blocchi il template si rende prima di wp_head, quando CF7 non ha
		// ancora registrato i suoi script: li carico su wp_enqueue_scripts (dopo CF7).
		if ( did_action( 'wp_enqueue_scripts' ) ) {
			self::enqueue_assets();
		} else {
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ), 20 );
		}
		return $content;
	}

	/**
	 * Script e stili di CF7 (solo nelle pagine con un modulo).
	 */
	public static function enqueue_assets(): void {
		if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
			wpcf7_enqueue_scripts();
		}
		if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
			wpcf7_enqueue_styles();
		}
	}

	/**
	 * Markup del blocco CF7 del modulo “Diventa partner” scelto in Impostazioni sito.
	 *
	 * @param string $markup Markup predefinito.
	 */
	public static function partner_form_block( string $markup ): string {
		return self::form_block( (int) Settings::get( 'partner_form' ), $markup );
	}

	/**
	 * Markup del blocco di Contact Form 7 per un modulo.
	 *
	 * @param int    $id     ID del modulo.
	 * @param string $markup Markup da restituire se il modulo non c'è.
	 */
	public static function form_block( int $id, string $markup = '' ): string {
		if ( ! $id || ! function_exists( 'wpcf7_contact_form' ) ) {
			return $markup;
		}
		$form = wpcf7_contact_form( $id );
		if ( ! $form ) {
			return $markup;
		}
		$hash  = method_exists( $form, 'hash' ) ? substr( (string) $form->hash(), 0, 7 ) : (string) $id;
		$title = $form->title();
		$attrs = wp_json_encode(
			array(
				'id'    => $id,
				'hash'  => $hash,
				'title' => $title,
			)
		);
		return '<!-- wp:contact-form-7/contact-form-selector ' . $attrs . ' -->' . "\n"
			. '<div class="wp-block-contact-form-7-contact-form-selector">[contact-form-7 id="' . esc_attr( $hash ) . '" title="' . esc_attr( $title ) . '"]</div>' . "\n"
			. '<!-- /wp:contact-form-7/contact-form-selector -->';
	}
}
