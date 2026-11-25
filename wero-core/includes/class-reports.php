<?php
/**
 * Download dei report.
 *
 * Il pulsante “Scarica il report” della timeline porta alla pagina di download
 * (Impostazioni sito › Pagina “Scarica i report”) con il report già scelto
 * (?report=Titolo). La pagina contiene un modulo di Contact Form 7 che:
 * - elenca da solo i Report pubblicati con un PDF (opzione `data:wero_reports` del
 *   campo checkbox, filtro wpcf7_form_tag_data_option): un report nuovo compare
 *   senza toccare il modulo;
 * - spunta il report di partenza con l'opzione `default:get`;
 * - nelle email usa [_wero_report_links] (link ai PDF scelti, filtro wpcf7_special_mail_tags);
 * - dopo l'invio mostra i link sulla pagina (evento DOM wpcf7mailsent).
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Report scaricabili.
 */
final class Reports {

	/** Nome della sorgente dati da usare nel modulo: [checkbox* report data:wero_reports]. */
	public const DATA = 'wero_reports';

	/** Nome del campo del modulo e del parametro dell'indirizzo (?report=). */
	public const FIELD = 'report';

	/** Codice speciale per le email. */
	public const MAIL_TAG = '_wero_report_links';

	/**
	 * Cache per richiesta.
	 *
	 * @var array<int, array{id:int, title:string, year:string, url:string}>|null
	 */
	private static ?array $cache = null;

	/**
	 * Hook.
	 */
	public static function register(): void {
		add_filter( 'wpcf7_form_tag_data_option', array( __CLASS__, 'data_option' ), 10, 3 );
		add_filter( 'wpcf7_special_mail_tags', array( __CLASS__, 'mail_tag' ), 10, 4 );
		add_filter( 'wero_report_form_block', array( __CLASS__, 'form_block' ) );
		add_action( 'save_post_report', array( __CLASS__, 'flush' ) );
	}

	/**
	 * Report scaricabili: pubblicati, non “in arrivo”, con un PDF. Dal più recente.
	 *
	 * @return array<int, array{id:int, title:string, year:string, url:string}>
	 */
	public static function downloadable(): array {
		if ( null !== self::$cache ) {
			return self::$cache;
		}
		$posts = get_posts(
			array(
				'post_type'      => 'report',
				'post_status'    => 'publish',
				'posts_per_page' => 50,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				),
				'no_found_rows'  => true,
			)
		);
		$out   = array();
		foreach ( $posts as $post ) {
			if ( 'in-arrivo' === (string) Fields::post( $post->ID, 'stato', 'pubblicato' ) ) {
				continue;
			}
			$url = Fields::file_url( Fields::post( $post->ID, 'pdf', 0 ) );
			if ( '' === $url ) {
				continue;
			}
			$out[] = array(
				'id'    => (int) $post->ID,
				'title' => (string) $post->post_title,
				'year'  => (string) Fields::post( $post->ID, 'anno', '' ),
				'url'   => $url,
			);
		}
		usort( $out, static fn( $a, $b ) => (int) $b['year'] <=> (int) $a['year'] );
		self::$cache = $out;
		return $out;
	}

	/**
	 * Svuota la cache quando un report cambia.
	 */
	public static function flush(): void {
		self::$cache = null;
	}

	/**
	 * Indirizzo della pagina di download, con il report già scelto.
	 *
	 * @param int $report_id Report da preselezionare (0 = nessuno).
	 */
	public static function page_url( int $report_id = 0 ): string {
		$page = (int) Settings::get( 'report_page' );
		$url  = $page && 'publish' === get_post_status( $page ) ? (string) get_permalink( $page ) : '';
		if ( '' === $url || ! $report_id ) {
			return $url;
		}
		return add_query_arg( self::FIELD, rawurlencode( (string) get_post_field( 'post_title', $report_id ) ), $url );
	}

	/**
	 * Indica se un report ha un PDF scaricabile.
	 *
	 * @param int $report_id Report.
	 */
	public static function is_downloadable( int $report_id ): bool {
		return in_array( $report_id, wp_list_pluck( self::downloadable(), 'id' ), true );
	}

	/**
	 * Valori del campo checkbox con `data:wero_reports`: i titoli dei report scaricabili.
	 *
	 * @param mixed                $data    Valori già forniti (null se nessuno).
	 * @param string[]             $options Opzioni `data:` del campo.
	 * @param array<string, mixed> $args    Argomenti.
	 * @return mixed
	 */
	public static function data_option( $data, $options, $args ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		if ( ! in_array( self::DATA, (array) $options, true ) ) {
			return $data;
		}
		$items = self::downloadable();
		self::enqueue( $items );
		return array_merge( (array) $data, wp_list_pluck( $items, 'title' ) );
	}

	/**
	 * Script che, dopo l'invio, mostra i link ai PDF scelti.
	 *
	 * @param array<int, array{id:int, title:string, year:string, url:string}> $items Report.
	 */
	private static function enqueue( array $items ): void {
		if ( is_admin() || wp_script_is( 'wero-report-download', 'enqueued' ) ) {
			return;
		}
		wp_enqueue_script(
			'wero-report-download',
			WERO_CORE_URL . 'assets/forms/report-download.js',
			array(),
			VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
		$files = array();
		foreach ( $items as $item ) {
			$files[ $item['title'] ] = $item['url'];
		}
		wp_add_inline_script(
			'wero-report-download',
			'window.weroReports = ' . wp_json_encode(
				array(
					'field'    => self::FIELD,
					'files'    => $files,
					'heading'  => __( 'I tuoi report', 'wero-core' ),
					'note'     => __( 'Ti abbiamo inviato gli stessi link anche per email.', 'wero-core' ),
					'download' => __( 'Scarica il PDF', 'wero-core' ),
				)
			) . ';',
			'before'
		);
	}

	/**
	 * [_wero_report_links]: link ai PDF scelti, per le email del modulo.
	 *
	 * @param string|null $output   Valore.
	 * @param string      $name     Nome del codice.
	 * @param bool        $html     Email in HTML.
	 * @param object|null $mail_tag Codice (WPCF7_MailTag).
	 * @return string|null
	 */
	public static function mail_tag( $output, $name, $html, $mail_tag = null ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		if ( self::MAIL_TAG !== $name || ! class_exists( 'WPCF7_Submission' ) ) {
			return $output;
		}
		$submission = \WPCF7_Submission::get_instance();
		if ( ! $submission ) {
			return '';
		}
		$chosen = array_map( 'strval', (array) $submission->get_posted_data( self::FIELD ) );
		$lines  = array();
		foreach ( self::downloadable() as $item ) {
			if ( ! in_array( $item['title'], $chosen, true ) ) {
				continue;
			}
			if ( $html ) {
				$lines[] = sprintf(
					'<tr><td style="padding:12px 0;border-bottom:1px solid rgba(21,18,26,0.12);font-family:\'Space Grotesk\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size:15px;color:#15121a;">%1$s%2$s</td><td align="right" style="padding:12px 0;border-bottom:1px solid rgba(21,18,26,0.12);"><a href="%3$s" style="display:inline-block;padding:10px 18px;border-radius:999px;background:#ff00c7;background-image:linear-gradient(90deg,#ff3f7a,#ff00c7);font-family:\'Space Grotesk\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size:14px;font-weight:600;color:#ffffff;text-decoration:none;white-space:nowrap;">%4$s</a></td></tr>',
					esc_html( $item['title'] ),
					$item['year'] ? ' <span style="color:#6c6670;">(' . esc_html( $item['year'] ) . ')</span>' : '',
					esc_url( $item['url'] ),
					esc_html__( 'Scarica il PDF', 'wero-core' )
				);
			} else {
				$lines[] = $item['title'] . ': ' . $item['url'];
			}
		}
		if ( ! $lines ) {
			return '';
		}
		return $html
			? '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">' . implode( '', $lines ) . '</table>'
			: implode( "\n", $lines );
	}

	/**
	 * Markup del blocco CF7 del modulo “Scarica i report” (per il pattern del tema).
	 *
	 * @param string $markup Markup predefinito.
	 */
	public static function form_block( string $markup ): string {
		return Forms::form_block( (int) Settings::get( 'report_form' ), $markup );
	}
}
