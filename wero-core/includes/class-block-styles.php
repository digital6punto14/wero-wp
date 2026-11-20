<?php
/**
 * Stili di blocco del plugin per le sezioni fatte con blocchi core.
 *
 * - core/group "Mondi": card a griglia su desktop, a scorrimento con i puntini su mobile.
 * - core/group "Voci": card video; l'immagine (copertina) collegata al video
 *   apre il player al clic, senza caricare YouTube/Vimeo prima.
 *
 * Testi, immagini e link restano blocchi Gutenberg modificabili nella pagina.
 * CSS e JS si caricano solo se la pagina contiene un gruppo con quello stile.
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Stili di blocco.
 */
final class Block_Styles {

	/** @var array<string, string> Stile => etichetta. */
	private const STYLES = array(
		'wero-worlds' => 'Mondi: card (scorrono su mobile)',
		'wero-voices' => 'Voci: video al clic sulla copertina',
	);

	/**
	 * Hook.
	 */
	public static function register(): void {
		add_action( 'init', array( __CLASS__, 'styles' ) );
		add_filter( 'render_block_core/group', array( __CLASS__, 'enqueue' ), 10, 2 );
		add_action( 'enqueue_block_assets', array( __CLASS__, 'editor' ) );
	}

	/**
	 * Registrazione degli stili (compaiono nel pannello Stili del blocco Gruppo).
	 */
	public static function styles(): void {
		foreach ( self::STYLES as $name => $label ) {
			register_block_style(
				'core/group',
				array(
					'name'  => $name,
					'label' => $label,
				)
			);
			wp_register_style( 'wero-style-' . $name, WERO_CORE_URL . 'assets/styles/' . $name . '.css', array(), VERSION );
			wp_register_script(
				'wero-style-' . $name,
				WERO_CORE_URL . 'assets/styles/' . $name . '.js',
				array(),
				VERSION,
				array(
					'in_footer' => true,
					'strategy'  => 'defer',
				)
			);
		}
	}

	/**
	 * Carica CSS e JS quando il gruppo con lo stile compare nella pagina.
	 *
	 * @param string               $content Markup.
	 * @param array<string, mixed> $block   Blocco.
	 */
	public static function enqueue( string $content, array $block ): string {
		$class = (string) ( $block['attrs']['className'] ?? '' );
		foreach ( array_keys( self::STYLES ) as $name ) {
			if ( str_contains( $class, 'is-style-' . $name ) ) {
				wp_enqueue_style( 'wero-style-' . $name );
				wp_enqueue_script( 'wero-style-' . $name );
			}
		}
		return $content;
	}

	/**
	 * Nell'editor (iframe) carica il CSS degli stili, così l'anteprima è fedele.
	 */
	public static function editor(): void {
		if ( ! is_admin() ) {
			return;
		}
		foreach ( array_keys( self::STYLES ) as $name ) {
			wp_enqueue_style( 'wero-style-' . $name );
		}
	}
}
