<?php
/**
 * Contact Form 7.
 *
 * - CSS e JS di CF7 caricati solo nelle pagine che contengono un modulo
 *   (filtri wpcf7_load_js / wpcf7_load_css + caricamento quando il blocco viene reso).
 * - Blocco del modulo "Diventa partner" per il pattern del tema (filtro wero_partner_form_block).
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
		if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
			wpcf7_enqueue_scripts();
		}
		if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
			wpcf7_enqueue_styles();
		}
		return $content;
	}

	/**
	 * Markup del blocco CF7 del modulo scelto in Impostazioni sito.
	 *
	 * @param string $markup Markup predefinito.
	 */
	public static function partner_form_block( string $markup ): string {
		$id = (int) Settings::get( 'partner_form' );
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
