<?php
/**
 * Plugin Name:       WeRo – Installazione contenuti
 * Description:       Importa una sola volta i contenuti iniziali del sito WeRo (progetti, report, partner, pagine, moduli, menu, impostazioni). Da eliminare dopo l'uso.
 * Version:           0.2.1
 * Requires at least: 6.9
 * Requires PHP:      8.2
 * Author:            WeRo
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wero-installer
 *
 * @package WeroInstaller
 */

defined( 'ABSPATH' ) || exit;

/**
 * Pagina Strumenti › Installa contenuti WeRo.
 */
function wero_installer_menu(): void {
	add_management_page(
		__( 'Installa contenuti WeRo', 'wero-installer' ),
		__( 'Installa contenuti WeRo', 'wero-installer' ),
		'manage_options',
		'wero-installer',
		'wero_installer_page'
	);
}
add_action( 'admin_menu', 'wero_installer_menu' );

/**
 * Controlli prima dell'importazione.
 *
 * @return array<int, array{0:string, 1:bool, 2:bool}> Etichetta, superato, obbligatorio.
 */
function wero_installer_checks(): array {
	global $wp_version;
	return array(
		array( __( 'WordPress 6.9 o successivo', 'wero-installer' ), version_compare( $wp_version, '6.9', '>=' ), true ),
		array( __( 'PHP 8.2 o successivo', 'wero-installer' ), version_compare( PHP_VERSION, '8.2', '>=' ), true ),
		array( __( 'Tema “WeRo” attivo', 'wero-installer' ), 'wero-theme' === get_stylesheet(), true ),
		array( __( 'Plugin “WeRo Core” attivo', 'wero-installer' ), class_exists( '\\Wero\\Core\\Settings' ), true ),
		array( __( 'Advanced Custom Fields attivo', 'wero-installer' ), function_exists( 'update_field' ), true ),
		array( __( 'Contact Form 7 attivo', 'wero-installer' ), class_exists( 'WPCF7_ContactForm' ), true ),
		array( __( 'Yoast SEO attivo', 'wero-installer' ), class_exists( 'WPSEO_Options' ), false ),
		array( __( 'Cartella dei contenuti presente', 'wero-installer' ), file_exists( __DIR__ . '/contenuti/import.php' ), true ),
	);
}

/**
 * Pagina.
 */
function wero_installer_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$done   = get_option( 'wero_installer_done' );
	$checks = wero_installer_checks();
	$ready  = ! in_array( false, array_map( static fn( $c ) => $c[1] || ! $c[2], $checks ), true );
	$log    = '';

	if ( isset( $_POST['wero_installer_run'] ) ) {
		check_admin_referer( 'wero_installer_run' );
		$confirm = ! empty( $_POST['wero_installer_confirm'] );
		if ( ! $ready ) {
			$log = __( 'Importazione non eseguita: alcuni controlli obbligatori non sono superati.', 'wero-installer' );
		} elseif ( $done && ! $confirm ) {
			$log = __( 'Importazione non eseguita: i contenuti sono già stati importati. Spunta la conferma per importarli di nuovo.', 'wero-installer' );
		} else {
			$log  = wero_installer_run();
			$done = get_option( 'wero_installer_done' );
		}
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Installa contenuti WeRo', 'wero-installer' ); ?></h1>
		<p><?php esc_html_e( 'Crea i contenuti iniziali del sito: immagini nella Libreria media, mondi, partner, progetti, report con PDF, moduli “Diventa partner” e “Scarica i report”, pagine (Home, Scarica i report, Privacy, Cookie), menu, impostazioni di WeRo e di Yoast, permalink.', 'wero-installer' ); ?></p>

		<h2><?php esc_html_e( 'Controlli', 'wero-installer' ); ?></h2>
		<table class="widefat striped" style="max-width:640px">
			<tbody>
			<?php foreach ( $checks as $c ) : ?>
				<tr>
					<td><?php echo esc_html( $c[0] ); ?><?php echo $c[2] ? '' : ' <em>(' . esc_html__( 'consigliato', 'wero-installer' ) . ')</em>'; ?></td>
					<td style="width:120px"><?php echo $c[1] ? '<strong style="color:#008a20">✔ ' . esc_html__( 'OK', 'wero-installer' ) . '</strong>' : '<strong style="color:#d63638">✖ ' . esc_html__( 'Manca', 'wero-installer' ) . '</strong>'; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<?php if ( $log ) : ?>
			<h2><?php esc_html_e( 'Risultato', 'wero-installer' ); ?></h2>
			<pre style="background:#fff;border:1px solid #c3c4c7;padding:12px;max-width:900px;white-space:pre-wrap"><?php echo esc_html( $log ); ?></pre>
		<?php endif; ?>

		<?php if ( $done ) : ?>
			<div class="notice notice-success inline"><p>
				<?php
				/* translators: %s: data e ora dell'importazione */
				echo esc_html( sprintf( __( 'Contenuti importati il %s. Ora puoi disattivare ed eliminare questo plugin (Plugin › WeRo – Installazione contenuti › Disattiva › Elimina).', 'wero-installer' ), (string) $done ) );
				?>
			</p></div>
		<?php endif; ?>

		<form method="post" style="margin-top:20px">
			<?php wp_nonce_field( 'wero_installer_run' ); ?>
			<?php if ( $done ) : ?>
				<p><label><input type="checkbox" name="wero_installer_confirm" value="1">
					<?php esc_html_e( 'Importa di nuovo: i contenuti importati (testi della Home, progetti, report, moduli, menu) tornano come all’inizio e le modifiche fatte dopo si perdono. I contenuti creati a mano non vengono toccati.', 'wero-installer' ); ?>
				</label></p>
			<?php endif; ?>
			<p><button type="submit" name="wero_installer_run" value="1" class="button button-primary button-hero" <?php disabled( ! $ready ); ?>>
				<?php $done ? esc_html_e( 'Importa di nuovo', 'wero-installer' ) : esc_html_e( 'Importa i contenuti', 'wero-installer' ); ?>
			</button></p>
			<p class="description"><?php esc_html_e( 'Richiede circa un minuto. Fai prima un backup se il sito contiene già dei dati.', 'wero-installer' ); ?></p>
		</form>
	</div>
	<?php
}

/**
 * Esegue l'importatore (contenuti/import.php) e restituisce il registro.
 */
function wero_installer_run(): string {
	if ( function_exists( 'set_time_limit' ) ) {
		set_time_limit( 600 ); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged -- importazione una tantum di immagini e PDF.
	}
	// Permalink “Nome articolo”: servono per /progetti/, /scarica-report/ e le pagine legali.
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure( '/%postname%/' );

	if ( ! defined( 'WERO_CONTENT_DIR' ) ) {
		define( 'WERO_CONTENT_DIR', __DIR__ . '/contenuti' );
	}
	ob_start();
	try {
		require WERO_CONTENT_DIR . '/import.php';
	} catch ( \Throwable $e ) {
		echo "\n" . esc_html__( 'Errore:', 'wero-installer' ) . ' ' . $e->getMessage(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- il registro viene scappato quando viene mostrato.
	}
	$log = (string) ob_get_clean();
	flush_rewrite_rules( false );
	if ( str_contains( $log, 'Fatto.' ) ) {
		update_option( 'wero_installer_done', wp_date( 'd/m/Y H:i' ), false );
	}
	return trim( $log );
}

/**
 * Link alla pagina dall'elenco dei plugin.
 *
 * @param string[] $links Link.
 * @return string[]
 */
function wero_installer_action_link( array $links ): array {
	array_unshift( $links, '<a href="' . esc_url( admin_url( 'tools.php?page=wero-installer' ) ) . '">' . esc_html__( 'Apri', 'wero-installer' ) . '</a>' );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wero_installer_action_link' );

/**
 * Alla disinstallazione toglie il segno di importazione fatta.
 */
function wero_installer_uninstall(): void {
	delete_option( 'wero_installer_done' );
}
register_uninstall_hook( __FILE__, 'wero_installer_uninstall' );
