<?php
/**
 * Plugin Name:       WeRo Core
 * Plugin URI:        https://we-ro.it
 * Description:       Struttura dei contenuti, campi, blocchi, impostazioni e SEO del sito WeRo. Indipendente dal tema: resta valido anche cambiando tema.
 * Version:           0.2.1
 * Requires at least: 6.9
 * Requires PHP:      8.2
 * Author:            WeRo
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wero-core
 * Domain Path:       /languages
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

const VERSION = '0.2.1';
const FILE    = __FILE__;
define( 'WERO_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'WERO_CORE_URL', plugin_dir_url( __FILE__ ) );

/*
 * Una classe per area. Ogni classe registra i propri hook in register().
 */
require_once WERO_CORE_DIR . 'includes/class-settings.php';
require_once WERO_CORE_DIR . 'includes/class-post-types.php';
require_once WERO_CORE_DIR . 'includes/class-taxonomies.php';
require_once WERO_CORE_DIR . 'includes/class-acf.php';
require_once WERO_CORE_DIR . 'includes/class-fields.php';
require_once WERO_CORE_DIR . 'includes/class-blocks.php';
require_once WERO_CORE_DIR . 'includes/class-block-styles.php';
require_once WERO_CORE_DIR . 'includes/class-bindings.php';
require_once WERO_CORE_DIR . 'includes/class-theme-bridge.php';
require_once WERO_CORE_DIR . 'includes/class-editor.php';
require_once WERO_CORE_DIR . 'includes/class-forms.php';
require_once WERO_CORE_DIR . 'includes/class-reports.php';
require_once WERO_CORE_DIR . 'includes/class-media.php';
require_once WERO_CORE_DIR . 'includes/class-data.php';
require_once WERO_CORE_DIR . 'includes/class-seo.php';
require_once WERO_CORE_DIR . 'includes/class-rest.php';
require_once WERO_CORE_DIR . 'includes/class-abilities.php';

add_action(
	'plugins_loaded',
	static function (): void {
		Settings::register();
		Post_Types::register();
		Taxonomies::register();
		Acf::register();
		Blocks::register();
		Block_Styles::register();
		Bindings::register();
		Theme_Bridge::register();
		Editor::register();
		Forms::register();
		Reports::register();
		Seo::register();
		Rest::register();
		Abilities::register();
	}
);

/**
 * Attivazione: registra i tipi di contenuto e aggiorna i permalink.
 */
function activate(): void {
	Post_Types::register_post_types();
	Taxonomies::register_taxonomies();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\activate' );

/**
 * Disattivazione: aggiorna i permalink (i dati restano).
 */
function deactivate(): void {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\deactivate' );
