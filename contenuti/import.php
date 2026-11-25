<?php
/**
 * WeRo — importazione dei contenuti (Fase 4).
 *
 * Ricrea il sito dall'originale HTML: media, mondi, partner, progetti, report,
 * pagine (Home a blocchi, Privacy, Cookie), modulo Contact Form 7, menu, logo, impostazioni.
 *
 * Si può rieseguire: ogni elemento importato è riconosciuto dal metadato `_wero_import`
 * e viene aggiornato invece di essere duplicato.
 *
 * Uso: dal blueprint (runPHP) oppure con WP-CLI:  wp eval-file contenuti/import.php
 *
 * @package WeroCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( "Da eseguire dentro WordPress (wp eval-file).\n" );
}

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/post.php';

/**
 * Importatore.
 */
final class Wero_Import {

	/** @var string Cartella dei contenuti. */
	private string $dir;
	/** @var array<string, mixed> Dati. */
	private array $data;
	/** @var array<string, int> File → ID allegato. */
	private array $media = array();
	/** @var array<string, int> Codice mondo → ID termine. */
	private array $worlds = array();
	/** @var string[] Registro. */
	public array $log = array();

	/**
	 * @param string $dir Cartella dei contenuti.
	 */
	public function __construct( string $dir ) {
		$this->dir  = rtrim( $dir, '/' );
		$this->data = json_decode( (string) file_get_contents( $this->dir . '/dati.json' ), true );
	}

	/** Esegue tutto. */
	public function run(): void {
		$admins = get_users(
			array(
				'role'   => 'administrator',
				'number' => 1,
			)
		);
		if ( $admins ) {
			wp_set_current_user( $admins[0]->ID );
		}
		$this->site();
		$this->worlds();
		$this->partners();
		$this->projects();
		$this->reports();
		$form   = $this->form();
		$report = $this->report_form();
		$this->settings( $form, $report );
		$this->legal_pages();
		$this->report_page();
		$this->home();
		$this->navigation();
		$this->seo();
		flush_rewrite_rules( false );
		$this->log[] = 'Fatto.';
	}

	/* ------------------------------------------------------------ utilità */

	/**
	 * Trova un contenuto importato in precedenza.
	 *
	 * @param string $type Tipo.
	 * @param string $key  Chiave.
	 */
	private function find( string $type, string $key ): int {
		$found = get_posts(
			array(
				'post_type'      => $type,
				'post_status'    => 'any',
				'meta_key'       => '_wero_import', // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'     => $key, // phpcs:ignore WordPress.DB.SlowDBQuery
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		return $found ? (int) $found[0] : 0;
	}

	/**
	 * Crea o aggiorna un contenuto.
	 *
	 * @param string               $type   Tipo.
	 * @param string               $key    Chiave univoca.
	 * @param array<string, mixed> $args   Argomenti di wp_insert_post.
	 * @param array<string, mixed> $fields Campi ACF.
	 */
	private function upsert( string $type, string $key, array $args, array $fields = array() ): int {
		$id   = $this->find( $type, $key );
		$args = array_merge(
			array(
				'post_type'   => $type,
				'post_status' => 'publish',
			),
			$args
		);
		if ( $id ) {
			$args['ID'] = $id;
			wp_update_post( wp_slash( $args ) );
		} else {
			$id = (int) wp_insert_post( wp_slash( $args ) );
			update_post_meta( $id, '_wero_import', $key );
		}
		foreach ( $fields as $name => $value ) {
			$this->field( $name, $value, $id );
		}
		return $id;
	}

	/**
	 * Scrive un campo ACF (o un metadato se ACF non c'è).
	 *
	 * @param string     $name  Campo.
	 * @param mixed      $value Valore.
	 * @param int|string $obj   ID o "term_ID".
	 */
	private function field( string $name, $value, $obj ): void {
		if ( function_exists( 'update_field' ) ) {
			update_field( $name, $value, $obj );
		} elseif ( is_int( $obj ) ) {
			update_post_meta( $obj, $name, $value );
		}
	}

	/**
	 * Carica un file di contenuti/media nella Libreria media (una sola volta).
	 *
	 * @param string $file Nome del file.
	 * @param string $alt  Testo alternativo.
	 */
	private function media( string $file, string $alt = '' ): int {
		if ( '' === $file ) {
			return 0;
		}
		if ( isset( $this->media[ $file ] ) ) {
			return $this->media[ $file ];
		}
		$path = $this->dir . '/media/' . $file;
		$id   = $this->find( 'attachment', 'media:' . $file );
		if ( ! $id && file_exists( $path ) ) {
			$tmp = wp_tempnam( $file );
			copy( $path, $tmp );
			$id = media_handle_sideload(
				array(
					'name'     => $file,
					'tmp_name' => $tmp,
				),
				0
			);
			if ( is_wp_error( $id ) ) {
				$this->log[] = 'Media non caricato: ' . $file . ' (' . $id->get_error_message() . ')';
				return 0;
			}
			update_post_meta( $id, '_wero_import', 'media:' . $file );
		}
		if ( $id && '' !== $alt ) {
			update_post_meta( $id, '_wp_attachment_image_alt', $alt );
		}
		$this->media[ $file ] = (int) $id;
		return (int) $id;
	}

	/**
	 * Sostituisce i riferimenti alle immagini nel markup dei blocchi:
	 * {{media:file}} e gli URL delle immagini dei pattern del tema diventano
	 * allegati della Libreria media (con ID nei blocchi Immagine, Galleria, Video, Hero).
	 *
	 * @param string $markup Markup.
	 */
	private function bind_media( string $markup ): string {
		$theme  = get_theme_file_uri( 'assets/images/patterns/' );
		$markup = preg_replace_callback(
			'#' . preg_quote( $theme, '#' ) . '([A-Za-z0-9._-]+)#',
			function ( $m ) {
				return ( 'segnaposto.svg' === $m[1] || ! file_exists( $this->dir . '/media/' . $m[1] ) ) ? $m[0] : '{{media:' . $m[1] . '}}';
			},
			$markup
		);
		$blocks = $this->walk( parse_blocks( $markup ) );
		$markup = serialize_blocks( $blocks );
		return preg_replace_callback(
			'/\{\{media:([A-Za-z0-9._-]+)\}\}/',
			function ( $m ) {
				$id = $this->media( $m[1] );
				return $id ? (string) wp_get_attachment_url( $id ) : '';
			},
			$markup
		);
	}

	/**
	 * Aggiunge gli ID degli allegati agli attributi dei blocchi.
	 *
	 * @param array<int, array<string, mixed>> $blocks Blocchi.
	 * @return array<int, array<string, mixed>>
	 */
	private function walk( array $blocks ): array {
		foreach ( $blocks as &$b ) {
			$html = (string) ( $b['innerHTML'] ?? '' );
			if ( in_array( $b['blockName'], array( 'core/image', 'core/video' ), true ) && preg_match( '/(?:src)="\{\{media:([A-Za-z0-9._-]+)\}\}"/', $html, $m ) ) {
				$alt = preg_match( '/alt="([^"]*)"/', $html, $a ) ? html_entity_decode( $a[1] ) : '';
				$id  = $this->media( $m[1], $alt );
				if ( $id ) {
					$b['attrs']['id'] = $id;
					if ( 'core/image' === $b['blockName'] ) {
						$rep               = static fn( $s ) => str_replace( '<img src=', '<img class="wp-image-' . $id . '" src=', $s );
						$b['innerHTML']    = $rep( $b['innerHTML'] );
						$b['innerContent'] = array_map( static fn( $c ) => is_string( $c ) ? $rep( $c ) : $c, $b['innerContent'] );
					}
				}
			}
			if ( 'wero/hero-video' === $b['blockName'] && preg_match( '/\{\{media:([A-Za-z0-9._-]+)\}\}/', (string) ( $b['attrs']['poster'] ?? '' ), $m ) ) {
				$b['attrs']['posterId'] = $this->media( $m[1] );
				$b['attrs']['poster']   = (string) wp_get_attachment_url( $b['attrs']['posterId'] );
			}
			if ( ! empty( $b['innerBlocks'] ) ) {
				$b['innerBlocks'] = $this->walk( $b['innerBlocks'] );
			}
		}
		return $blocks;
	}

	/**
	 * Markup di un pattern del tema, letto dal file (così i filtri attivi ora,
	 * come il modulo di CF7, vengono applicati) e con i pattern annidati espansi.
	 *
	 * @param string $slug Slug senza "wero-theme/".
	 */
	private function pattern( string $slug ): string {
		$file = get_theme_file_path( 'patterns/' . $slug . '.php' );
		if ( ! file_exists( $file ) ) {
			$this->log[] = 'Pattern mancante: ' . $slug;
			return '';
		}
		ob_start();
		include $file;
		return $this->expand( (string) ob_get_clean() );
	}

	/**
	 * Espande i pattern annidati (<!-- wp:pattern {"slug":"wero-theme/…"} /-->) in blocchi modificabili.
	 *
	 * @param string $markup Markup.
	 */
	private function expand( string $markup ): string {
		return preg_replace_callback(
			'#<!-- wp:pattern \{"slug":"wero-theme/([a-z0-9-]+)"\} /-->#',
			fn( $m ) => $this->pattern( $m[1] ),
			$markup
		);
	}

	/* ------------------------------------------------------------ contenuti */

	/** Titolo, descrizione, logo, icona. */
	private function site(): void {
		update_option( 'blogname', $this->data['site']['title'] );
		update_option( 'blogdescription', $this->data['site']['tagline'] );
		$logo = $this->media( 'wero-mark.png', 'WeRo' );
		if ( $logo ) {
			set_theme_mod( 'custom_logo', $logo );
		}
		$icon = $this->media( 'wero-icona.png', 'WeRo' );
		if ( $icon ) {
			update_option( 'site_icon', $icon );
		}
		$this->log[] = 'Sito: titolo, logo e icona.';
	}

	/** Mondi (tassonomia + campi). */
	private function worlds(): void {
		foreach ( $this->data['worlds'] as $w ) {
			$term = term_exists( $w['name'], 'mondo' );
			if ( ! $term ) {
				$term = wp_insert_term( $w['name'], 'mondo' );
			}
			if ( is_wp_error( $term ) ) {
				continue;
			}
			$tid                        = (int) $term['term_id'];
			$this->worlds[ $w['code'] ] = $tid;
			$obj                        = 'term_' . $tid;
			// Le card dei mondi sono blocchi nella Home: qui solo bollino e ordine dei filtri.
			$this->field( 'stile', $w['stile'], $obj );
			$this->field( 'ordine', $w['ordine'], $obj );
		}
		$this->log[] = 'Mondi: ' . count( $this->worlds );
	}

	/** Partner (founder e collaboratori). */
	private function partners(): void {
		foreach ( $this->data['partners'] as $i => $p ) {
			$this->upsert(
				'partner',
				'partner:' . $p['name'],
				array(
					'post_title' => $p['name'],
					'menu_order' => $i,
				),
				array(
					'logo'         => $this->media( $p['logo'], $p['alt'] ),
					'tipo'         => $p['tipo'],
					'founder'      => ! empty( $p['founder'] ) ? 1 : 0,
					'altezza_logo' => $p['altezza'],
					'descrizione'  => $p['desc'] ?? '',
					'sito'         => $p['sito'] ?? '',
				)
			);
		}
		$this->log[] = 'Partner: ' . count( $this->data['partners'] );
	}

	/** Progetti: 4 completi + 8 con testo segnaposto (D35, pubblicati). */
	private function projects(): void {
		foreach ( $this->data['projects'] as $p ) {
			$content = match ( $p['content'] ) {
				'generative-bionics' => $this->expand( (string) file_get_contents( $this->dir . '/blocchi/generative-bionics.html' ) ),
				'video'              => $this->pattern( 'progetto-video' ),
				'podcast'            => $this->pattern( 'progetto-podcast' ),
				// Ex “Voci” della home (D53): descrizione + foto a scorrimento; il video va nel campo del progetto.
				'voce'               => "<!-- wp:paragraph -->\n<p>" . esc_html( $p['body'] ?? '' ) . "</p>\n<!-- /wp:paragraph -->\n\n" . $this->pattern( 'foto-progetto' ),
				default              => $this->pattern( 'progetto-articolo' ),
			};
			if ( empty( $p['completo'] ) ) {
				$content = "<!-- wp:paragraph {\"className\":\"wero-todo\"} -->\n<p class=\"wero-todo\"><strong>Testo segnaposto:</strong> questa pagina va completata prima della messa online.</p>\n<!-- /wp:paragraph -->\n\n" . $content;
			}
			$id = $this->upsert(
				'progetto',
				'progetto:' . $p['title'],
				array(
					'post_title'   => $p['title'],
					'post_excerpt' => $p['excerpt'],
					'post_content' => $this->bind_media( $content ),
					'menu_order'   => $p['ordine'],
				),
				array(
					'tipo'              => $p['tipo'],
					'anno'              => $p['anno'],
					'descrizione_breve' => $p['descrizione_breve'],
					'icona'             => $p['icona'],
					'con'               => $p['con'],
					'episodio'          => $p['episodio'],
					'media_durata'      => $p['media_durata'],
				)
			);
			update_post_meta( $id, '_wp_page_template', $p['template'] );
			wp_set_object_terms( $id, array( $this->worlds[ $p['world'] ] ?? 0 ), 'mondo' );
			if ( $p['image'] ) {
				set_post_thumbnail( $id, $this->media( $p['image'], $p['image_alt'] ) );
			}
			if ( ! empty( $p['meta'] ) ) {
				update_post_meta( $id, '_yoast_wpseo_metadesc', $p['meta'] );
			}
		}
		$this->log[] = 'Progetti: ' . count( $this->data['projects'] );
	}

	/** Report. */
	private function reports(): void {
		foreach ( $this->data['reports'] as $r ) {
			$id = $this->upsert(
				'report',
				'report:' . $r['title'],
				array(
					'post_title' => $r['title'],
					'menu_order' => $r['ordine'],
				),
				array(
					'anno'           => $r['anno'],
					'stato'          => $r['stato'],
					'descrizione'    => $r['desc'],
					'testo_pulsante' => $r['testo_pulsante'],
					'link_pulsante'  => $r['link_pulsante'],
					'pdf'            => ! empty( $r['pdf'] ) ? $this->media( $r['pdf'], '' ) : 0,
				)
			);
			wp_set_object_terms( $id, array( $this->worlds[ $r['world'] ] ?? 0 ), 'mondo' );
			if ( $r['progetto'] ) {
				$pid = $this->find( 'progetto', 'progetto:' . $r['progetto'] );
				$this->field( 'progetto', $pid ? array( $pid ) : array(), $id );
			}
		}
		$this->log[] = 'Report: ' . count( $this->data['reports'] );
	}

	/** Modulo "Diventa partner" (Contact Form 7). */
	private function form(): int {
		if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
			$this->log[] = 'Contact Form 7 non attivo: modulo non creato.';
			return 0;
		}
		// Il modulo di esempio che CF7 crea all'attivazione non serve.
		// phpcs:ignore WordPress.WP.I18n.TextDomainMismatch, WordPress.WP.I18n.MissingTranslatorsComment -- stesso titolo, tradotto, che usa Contact Form 7.
		foreach ( array_unique( array( 'Contact form 1', 'Modulo di contatto 1', sprintf( __( 'Contact form %d', 'contact-form-7' ), 1 ) ) ) as $sample_title ) {
			$samples = get_posts(
				array(
					'post_type'      => 'wpcf7_contact_form',
					'title'          => $sample_title,
					'posts_per_page' => 5,
					'fields'         => 'ids',
				)
			);
			foreach ( $samples as $sample ) {
				if ( ! get_post_meta( $sample, '_wero_import', true ) ) {
					wp_trash_post( $sample );
				}
			}
		}
		$id   = $this->find( 'wpcf7_contact_form', 'form:partner' );
		$form = $id ? WPCF7_ContactForm::get_instance( $id ) : WPCF7_ContactForm::get_template( array( 'title' => 'Diventa partner' ) );
		$body = <<<'CF7'
<div class="wero-form-row">
<label>Nome *[text* nome autocomplete:name]</label>
<label>Azienda / Ente[text azienda autocomplete:organization]</label>
</div>
<label>Email *[email* email autocomplete:email]</label>
<div class="wero-chips"><span class="wero-chips__legend">Ambito di interesse</span>[checkbox ambito use_label_element "Ricerca" "Business" "Comunicazione & creatività"]</div>
<label>Messaggio[textarea messaggio x4]</label>
[acceptance privacy]Ho letto l'<a href="/privacy-policy/" target="_blank">informativa privacy</a>[/acceptance]
[submit "Invia richiesta"]
CF7;
		$form->set_title( 'Diventa partner' );
		$props         = $form->get_properties();
		$props['form'] = $body;
		$logo          = (string) wp_get_attachment_url( $this->media( 'wero-mark.png', 'WeRo' ) );
		$email         = fn( string $name ) => str_replace( '{{logo}}', esc_url( $logo ), (string) file_get_contents( $this->dir . '/email/' . $name . '.html' ) );
		// Mail 1: notifica a WeRo (HTML, risposta diretta a chi ha scritto).
		$props['mail'] = array_merge(
			(array) $props['mail'],
			array(
				'active'             => true,
				'subject'            => '[WeRo] Nuova richiesta di partnership da [nome]',
				'sender'             => 'WeRo <noreply@we-ro.it>',
				'recipient'          => 'contact@we-ro.it',
				'body'               => $email( 'notifica' ),
				'additional_headers' => 'Reply-To: [nome] <[email]>',
				'attachments'        => '',
				'use_html'           => true,
				'exclude_blank'      => true,
			)
		);
		// Mail 2: conferma a chi ha compilato il modulo (HTML, risposte a contact@we-ro.it).
		$props['mail_2']   = array_merge(
			(array) ( $props['mail_2'] ?? array() ),
			array(
				'active'             => true,
				'subject'            => 'Grazie per averci scritto, [nome] – WeRo',
				'sender'             => 'WeRo <noreply@we-ro.it>',
				'recipient'          => '[email]',
				'body'               => $email( 'conferma' ),
				'additional_headers' => 'Reply-To: WeRo <contact@we-ro.it>',
				'attachments'        => '',
				'use_html'           => true,
				'exclude_blank'      => true,
			)
		);
		$props['messages'] = array_merge(
			(array) $props['messages'],
			array(
				'mail_sent_ok'      => 'Grazie! Ti ricontatteremo presto.',
				'mail_sent_ng'      => 'Si è verificato un errore durante l’invio. Riprova più tardi o scrivi a contact@we-ro.it.',
				'validation_error'  => 'Controlla i campi evidenziati e riprova.',
				'spam'              => 'Si è verificato un errore durante l’invio. Riprova più tardi.',
				'accept_terms'      => 'Per inviare la richiesta devi accettare l’informativa privacy.',
				'invalid_required'  => 'Compila questo campo.',
				'invalid_too_long'  => 'Il testo è troppo lungo.',
				'invalid_too_short' => 'Il testo è troppo corto.',
				'invalid_email'     => 'Inserisci un indirizzo email valido.',
			)
		);
		$form->set_properties( $props );
		$form->save();
		$id = (int) $form->id();
		update_post_meta( $id, '_wero_import', 'form:partner' );
		$this->log[] = 'Modulo CF7: ' . $id;
		return $id;
	}

	/**
	 * Modulo "Scarica i report" (Contact Form 7, D54).
	 *
	 * L'elenco dei report non è scritto nel modulo: `data:wero_reports` lo fa compilare
	 * al plugin con i Report pubblicati che hanno un PDF; `default:get` spunta il report
	 * arrivato dal pulsante della timeline (?report=Titolo).
	 */
	private function report_form(): int {
		if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
			return 0;
		}
		$id   = $this->find( 'wpcf7_contact_form', 'form:report' );
		$form = $id ? WPCF7_ContactForm::get_instance( $id ) : WPCF7_ContactForm::get_template( array( 'title' => 'Scarica i report' ) );
		$body = <<<'CF7'
<div class="wero-form-row">
<label>Nome *[text* nome autocomplete:name]</label>
<label>Email *[email* email autocomplete:email]</label>
</div>
<div class="wero-form-row">
<label>Azienda / Ente[text azienda autocomplete:organization]</label>
<label>Ruolo[text ruolo autocomplete:organization-title]</label>
</div>
<div class="wero-chips wero-chips--reports"><span class="wero-chips__legend">Report da scaricare *</span>[checkbox* report use_label_element default:get data:wero_reports]</div>
[acceptance privacy]Ho letto l'<a href="/privacy-policy/" target="_blank">informativa privacy</a>[/acceptance]
[submit "Scarica i report"]
CF7;
		$form->set_title( 'Scarica i report' );
		$props         = $form->get_properties();
		$props['form'] = $body;
		$logo          = (string) wp_get_attachment_url( $this->media( 'wero-mark.png', 'WeRo' ) );
		$email         = fn( string $name ) => str_replace( '{{logo}}', esc_url( $logo ), (string) file_get_contents( $this->dir . '/email/' . $name . '.html' ) );
		// Mail 1: avviso al gestore del sito a ogni download.
		$props['mail'] = array_merge(
			(array) $props['mail'],
			array(
				'active'             => true,
				'subject'            => '[WeRo] Download report: [report] – [nome]',
				'sender'             => 'WeRo <noreply@we-ro.it>',
				'recipient'          => 'contact@we-ro.it',
				'body'               => $email( 'notifica-report' ),
				'additional_headers' => 'Reply-To: [nome] <[email]>',
				'attachments'        => '',
				'use_html'           => true,
				'exclude_blank'      => true,
			)
		);
		// Mail 2: a chi ha compilato, con i link ai PDF scelti.
		$props['mail_2']   = array_merge(
			(array) ( $props['mail_2'] ?? array() ),
			array(
				'active'             => true,
				'subject'            => 'I report di WeRo che hai richiesto',
				'sender'             => 'WeRo <noreply@we-ro.it>',
				'recipient'          => '[email]',
				'body'               => $email( 'conferma-report' ),
				'additional_headers' => 'Reply-To: WeRo <contact@we-ro.it>',
				'attachments'        => '',
				'use_html'           => true,
				'exclude_blank'      => true,
			)
		);
		$props['messages'] = array_merge(
			(array) $props['messages'],
			array(
				'mail_sent_ok'      => 'Grazie! I link per scaricare i report sono qui sotto e te li abbiamo inviati anche per email.',
				'mail_sent_ng'      => 'Si è verificato un errore durante l’invio. Riprova più tardi o scrivi a contact@we-ro.it.',
				'validation_error'  => 'Controlla i campi evidenziati e riprova.',
				'spam'              => 'Si è verificato un errore durante l’invio. Riprova più tardi.',
				'accept_terms'      => 'Per scaricare i report devi accettare l’informativa privacy.',
				'invalid_required'  => 'Compila questo campo.',
				'invalid_too_long'  => 'Il testo è troppo lungo.',
				'invalid_too_short' => 'Il testo è troppo corto.',
				'invalid_email'     => 'Inserisci un indirizzo email valido.',
			)
		);
		$form->set_properties( $props );
		$form->save();
		$id = (int) $form->id();
		update_post_meta( $id, '_wero_import', 'form:report' );
		$this->log[] = 'Modulo report CF7: ' . $id;
		return $id;
	}

	/** Pagina "Scarica i report" (pattern del tema con il modulo) e impostazione collegata. */
	private function report_page(): void {
		$id = $this->upsert(
			'page',
			'page:scarica-report',
			array(
				'post_title'   => 'Scarica i report',
				'post_name'    => 'scarica-report',
				'post_content' => $this->pattern( 'pagina-scarica-report' ),
			)
		);
		update_post_meta( $id, '_yoast_wpseo_metadesc', 'Scarica i report di WeRo sulla robotica: ricerca, percezione pubblica e comunicazione dei robot.' );
		$s                = (array) get_option( 'wero_settings', array() );
		$s['report_page'] = $id;
		update_option( 'wero_settings', $s );
		$this->log[] = 'Pagina Scarica i report: ' . $id;
	}

	/**
	 * Impostazioni sito del plugin.
	 *
	 * @param int $form   Modulo partner.
	 * @param int $report Modulo "Scarica i report".
	 */
	private function settings( int $form, int $report = 0 ): void {
		$s = (array) get_option( 'wero_settings', array() );
		if ( class_exists( '\\Wero\\Core\\Settings' ) ) {
			$s = array_merge( \Wero\Core\Settings::defaults(), $s );
		}
		$s['partner_form'] = $form;
		$s['report_form']  = $report;
		update_option( 'wero_settings', $s );
	}

	/**
	 * Markdown semplice (titoli, paragrafi, elenchi, tabelle, grassetto, link) → blocchi.
	 *
	 * @param string $md Testo.
	 */
	private function md_to_blocks( string $md ): string {
		$md     = preg_replace( '/<!--.*?-->/s', '', $md );
		$lines  = preg_split( '/\R/', trim( $md ) );
		$out    = array();
		$inline = static function ( string $t ): string {
			$t = esc_html( $t );
			$t = preg_replace( '/\*\*(.+?)\*\*/', '<strong>$1</strong>', $t );
			$t = preg_replace( '/\[([^\]]+)\]\((https?:[^)\s]+)\)/', '<a href="$2">$1</a>', $t );
			return preg_replace( '#(?<![">])(https?://[^\s<]+)#', '<a href="$1">$1</a>', $t );
		};
		$i      = 0;
		$n      = count( $lines );
		while ( $i < $n ) {
			$l = rtrim( $lines[ $i ] );
			if ( '' === $l || str_starts_with( $l, '# ' ) ) {
				++$i;
				continue;
			}
			if ( preg_match( '/^(#{2,3}) (.+)$/', $l, $m ) ) {
				$lv    = strlen( $m[1] );
				$attr  = 3 === $lv ? ' {"level":3}' : '';
				$out[] = "<!-- wp:heading{$attr} -->\n<h{$lv} class=\"wp-block-heading\">" . $inline( $m[2] ) . "</h{$lv}>\n<!-- /wp:heading -->";
				++$i;
				continue;
			}
			if ( str_starts_with( $l, '- ' ) ) {
				$items = array();
				while ( $i < $n && str_starts_with( $lines[ $i ], '- ' ) ) {
					$items[] = "<!-- wp:list-item -->\n<li>" . $inline( substr( $lines[ $i ], 2 ) ) . "</li>\n<!-- /wp:list-item -->";
					++$i;
				}
				$out[] = "<!-- wp:list -->\n<ul class=\"wp-block-list\">" . implode( "\n\n", $items ) . "</ul>\n<!-- /wp:list -->";
				continue;
			}
			if ( str_starts_with( $l, '|' ) ) {
				$rows = array();
				while ( $i < $n && str_starts_with( $lines[ $i ], '|' ) ) {
					if ( ! preg_match( '/^\|[\s|:-]+\|$/', trim( $lines[ $i ] ) ) ) {
						$rows[] = array_map( 'trim', explode( '|', trim( $lines[ $i ], " |\t" ) ) );
					}
					++$i;
				}
				$head  = array_shift( $rows );
				$th    = implode( '', array_map( static fn( $c ) => '<th>' . $inline( $c ) . '</th>', $head ) );
				$tb    = implode( '', array_map( static fn( $r ) => '<tr>' . implode( '', array_map( static fn( $c ) => '<td>' . $inline( $c ) . '</td>', $r ) ) . '</tr>', $rows ) );
				$out[] = "<!-- wp:table -->\n<figure class=\"wp-block-table\"><table class=\"has-fixed-layout\"><thead><tr>{$th}</tr></thead><tbody>{$tb}</tbody></table></figure>\n<!-- /wp:table -->";
				continue;
			}
			$out[] = "<!-- wp:paragraph -->\n<p>" . $inline( $l ) . "</p>\n<!-- /wp:paragraph -->";
			++$i;
		}
		return implode( "\n\n", $out );
	}

	/** Privacy policy e Cookie policy (testi di 6.14). */
	private function legal_pages(): void {
		// WordPress crea già una bozza "Privacy Policy" (slug privacy-policy): la riuso, così l'indirizzo resta /privacy-policy/.
		$default = get_page_by_path( 'privacy-policy' );
		if ( $default && ! get_post_meta( $default->ID, '_wero_import', true ) ) {
			update_post_meta( $default->ID, '_wero_import', 'page:privacy' );
		}
		$privacy = $this->upsert(
			'page',
			'page:privacy',
			array(
				'post_title'   => 'Privacy policy',
				'post_name'    => 'privacy-policy',
				'post_content' => $this->md_to_blocks( (string) file_get_contents( $this->dir . '/privacy-policy.md' ) ),
			)
		);
		update_option( 'wp_page_for_privacy_policy', $privacy );
		$cookie = $this->upsert(
			'page',
			'page:cookie',
			array(
				'post_title'   => 'Cookie policy',
				'post_name'    => 'cookie-policy',
				'post_content' => $this->md_to_blocks( (string) file_get_contents( $this->dir . '/cookie-policy.md' ) ),
			)
		);
		update_post_meta( $cookie, '_yoast_wpseo_meta-robots-noindex', '1' );
		// La pagina "Pagina di esempio" e l'articolo "Ciao mondo" di WordPress non servono.
		foreach ( array( 'pagina-di-esempio', 'sample-page' ) as $slug ) {
			$p = get_page_by_path( $slug );
			if ( $p ) {
				wp_trash_post( $p->ID );
			}
		}
		$hello = get_posts(
			array(
				'post_name__in'  => array( 'ciao-mondo', 'hello-world' ),
				'post_type'      => 'post',
				'posts_per_page' => 1,
			)
		);
		if ( $hello ) {
			wp_trash_post( $hello[0]->ID );
		}
		$this->log[] = 'Pagine legali: ' . $privacy . ', ' . $cookie;
	}

	/** Home a blocchi (sezioni del tema espanse) e impostata come home statica. */
	private function home(): void {
		$sections = array( 'home-hero', 'home-chi-siamo', 'home-mondi', 'home-progetti', 'home-report', 'home-perche', 'home-partner' );
		$markup   = implode( "\n\n", array_map( array( $this, 'pattern' ), $sections ) );
		$id       = $this->upsert(
			'page',
			'page:home',
			array(
				'post_title'   => 'Home',
				'post_name'    => 'home',
				'post_content' => $this->bind_media( $markup ),
			)
		);
		update_post_meta( $id, '_yoast_wpseo_metadesc', $this->data['site']['home_meta'] );
		update_post_meta( $id, '_yoast_wpseo_title', 'WeRo %%sep%% Il primo Robot Communication Studio' );
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $id );
		$this->log[] = 'Home: ' . $id;
	}

	/** Yoast SEO: rappresentazione del sito, titoli e descrizioni per tipo, breadcrumb, llms.txt (Fase 5). */
	private function seo(): void {
		if ( ! class_exists( 'WPSEO_Options' ) ) {
			$this->log[] = 'Yoast SEO non attivo: impostazioni SEO non applicate.';
			return;
		}
		$logo = $this->media( 'wero-mark.png', 'WeRo' );
		$set  = array(
			// Rappresentazione del sito: Organizzazione (WeRo, joint venture 6.14 + Omnicom Advertising).
			'company_or_person'            => 'company',
			'company_name'                 => 'WeRo',
			'company_logo'                 => (string) wp_get_attachment_url( $logo ),
			'company_logo_id'              => $logo,
			'website_name'                 => 'WeRo',
			'alternate_website_name'       => 'WeRo Robot Communication Studio',
			'org-description'              => 'Il primo Robot Communication Studio: un ecosistema di partner tra ricerca, business, comunicazione e creatività. Joint venture tra 6.14 e Omnicom Advertising.',
			'org-email'                    => 'contact@we-ro.it',
			// Home.
			'title-home-wpseo'             => 'WeRo %%sep%% Il primo Robot Communication Studio',
			'metadesc-home-wpseo'          => $this->data['site']['home_meta'],
			// Progetti (dai tag dell'originale: "Titolo — WeRo").
			'title-progetto'               => '%%title%% %%sep%% WeRo',
			'metadesc-progetto'            => '%%excerpt%%',
			'schema-article-type-progetto' => 'Article',
			'title-ptarchive-progetto'     => 'Progetti %%sep%% WeRo',
			'metadesc-ptarchive-progetto'  => 'Tutti i progetti di WeRo tra ricerca, eventi, linguaggi e design della robotica.',
			'title-page'                   => '%%title%% %%sep%% WeRo',
			'separator'                    => 'sc-mdash',
			// Breadcrumb (blocco di Yoast nei template interni).
			'breadcrumbs-enable'           => true,
			'breadcrumbs-home'             => 'Home',
			'breadcrumbs-sep'              => '›',
		);
		foreach ( $set as $key => $value ) {
			WPSEO_Options::set( $key, $value );
		}
		// Funzioni del sito: llms.txt per gli agenti AI.
		WPSEO_Options::set( 'enable_llms_txt', true );
		// LinkedIn: il plugin lo aggiunge allo schema dalle Impostazioni sito (filtro), qui niente duplicati.
		$this->log[] = 'Yoast: rappresentazione, titoli, descrizioni, breadcrumb, llms.txt.';
	}

	/** Menu (wp_navigation) e header che lo usa. */
	private function navigation(): void {
		$links = array(
			'Chi siamo' => '/#chi',
			'Mondi'     => '/#mondi',
			'Progetti'  => '/#progetti',
			'Report'    => '/#report',
			'Perché'    => '/#perche',
		);
		$items = array();
		foreach ( $links as $label => $url ) {
			$items[] = '<!-- wp:navigation-link ' . wp_json_encode(
				array(
					'label' => $label,
					'url'   => $url,
					'kind'  => 'custom',
				),
				JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
			) . ' /-->';
		}
		$items[] = "<!-- wp:buttons {\"className\":\"wero-header__cta\"} -->\n<div class=\"wp-block-buttons wero-header__cta\"><!-- wp:button -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link wp-element-button\" href=\"/#partner\">Diventa partner</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->";
		$nav     = $this->upsert(
			'wp_navigation',
			'nav:principale',
			array(
				'post_title'   => 'Menu principale',
				'post_content' => implode( "\n\n", $items ),
			)
		);
		// Header del tema con il menu salvato (modificabile da Aspetto › Editor › Navigazione).
		$file   = get_theme_file_path( 'parts/header.html' );
		$header = (string) file_get_contents( $file );
		$header = preg_replace(
			'#<!-- wp:navigation (\{.*?\}) -->.*<!-- /wp:navigation -->#s',
			'<!-- wp:navigation ' . wp_json_encode( array_merge( array( 'ref' => $nav ), (array) json_decode( preg_match( '#<!-- wp:navigation (\{.*?\}) -->#s', $header, $m ) ? $m[1] : '{}', true ) ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . ' /-->',
			$header
		);
		$part   = get_block_template( get_stylesheet() . '//header', 'wp_template_part' );
		$args   = array(
			'post_type'    => 'wp_template_part',
			'post_status'  => 'publish',
			'post_name'    => 'header',
			'post_title'   => 'Header',
			'post_content' => $header,
			'tax_input'    => array(
				'wp_theme'              => array( get_stylesheet() ),
				'wp_template_part_area' => array( 'header' ),
			),
		);
		if ( $part && $part->wp_id ) {
			$args['ID'] = $part->wp_id;
			wp_update_post( wp_slash( $args ) );
		} else {
			$pid = wp_insert_post( wp_slash( $args ) );
			wp_set_object_terms( $pid, get_stylesheet(), 'wp_theme' );
			wp_set_object_terms( $pid, 'header', 'wp_template_part_area' );
		}
		$this->log[] = 'Menu: ' . $nav;
	}
}

$wero_dir = defined( 'WERO_CONTENT_DIR' ) ? WERO_CONTENT_DIR : __DIR__;
$wero_imp = new Wero_Import( $wero_dir );
$wero_imp->run();
echo implode( "\n", $wero_imp->log ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- uscita da riga di comando.
