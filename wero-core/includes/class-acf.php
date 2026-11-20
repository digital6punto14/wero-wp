<?php
/**
 * ACF: i field group stanno in JSON dentro il plugin (acf-json/),
 * con punti di caricamento e salvataggio. Si usano solo campi di ACF gratuito.
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Integrazione ACF.
 */
final class Acf {

	/**
	 * Hook.
	 */
	public static function register(): void {
		add_filter( 'acf/settings/load_json', array( __CLASS__, 'load_point' ) );
		add_filter( 'acf/settings/save_json', array( __CLASS__, 'save_point' ) );
		add_action( 'admin_notices', array( __CLASS__, 'missing_notice' ) );
	}

	/**
	 * Cartella dei JSON.
	 */
	public static function dir(): string {
		return WERO_CORE_DIR . 'acf-json';
	}

	/**
	 * Punto di caricamento.
	 *
	 * @param string[] $paths Percorsi.
	 * @return string[]
	 */
	public static function load_point( array $paths ): array {
		$paths[] = self::dir();
		return $paths;
	}

	/**
	 * Punto di salvataggio: i gruppi modificati dall'interfaccia ACF finiscono nel plugin.
	 *
	 * @param string $path Percorso.
	 */
	public static function save_point( string $path ): string {
		unset( $path );
		return self::dir();
	}

	/**
	 * Avviso se ACF non è attivo: i campi tornano, ma non si possono modificare.
	 */
	public static function missing_notice(): void {
		if ( function_exists( 'acf_get_field_groups' ) || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		echo '<div class="notice notice-warning"><p>' . esc_html__( 'WeRo Core: attiva Advanced Custom Fields per modificare i campi di progetti, report, partner e voci. I blocchi continuano a mostrare i dati già salvati.', 'wero-core' ) . '</p></div>';
	}
}
