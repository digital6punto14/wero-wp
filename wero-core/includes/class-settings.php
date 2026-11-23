<?php
/**
 * Impostazioni sito (Settings API).
 *
 * Una sola opzione `wero_settings` (array), registrata con register_setting e
 * show_in_rest. Pagina: Impostazioni › WeRo. Solo gli amministratori
 * (manage_options) possono modificarla.
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Impostazioni del sito.
 */
final class Settings {

	public const OPTION = 'wero_settings';
	public const PAGE   = 'wero-settings';

	/**
	 * Hook.
	 */
	public static function register(): void {
		add_action( 'admin_init', array( __CLASS__, 'register_setting' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'register_setting' ) );
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( FILE ), array( __CLASS__, 'action_link' ) );
		add_filter( 'option_' . self::OPTION, array( __CLASS__, 'with_defaults' ) );
	}

	/**
	 * Definizione dei campi: chiave => [tipo, predefinito, etichetta, sezione, descrizione].
	 *
	 * @return array<string, array{type:string, default:mixed, label:string, section:string, help?:string, choices?:array<string,string>}>
	 */
	public static function fields(): array {
		return array(
			// Organizzazione.
			'org_name'        => array(
				'type'    => 'text',
				'default' => 'WeRo',
				'label'   => __( 'Nome', 'wero-core' ),
				'section' => 'org',
			),
			'org_alternate'   => array(
				'type'    => 'text',
				'default' => 'WeRo Robot Communication Studio',
				'label'   => __( 'Nome esteso', 'wero-core' ),
				'section' => 'org',
			),
			'org_description' => array(
				'type'    => 'textarea',
				'default' => 'Il primo Robot Communication Studio: un ecosistema di partner tra ricerca, business, comunicazione e creatività. Joint venture tra 6.14 e Omnicom Advertising.',
				'label'   => __( 'Descrizione', 'wero-core' ),
				'section' => 'org',
				'help'    => __( 'Usata nei dati strutturati (Yoast) e dagli agenti AI.', 'wero-core' ),
			),
			'email'           => array(
				'type'    => 'email',
				'default' => 'contact@we-ro.it',
				'label'   => __( 'Email contatti', 'wero-core' ),
				'section' => 'org',
				'help'    => __( 'Compare nel footer e nei dati strutturati.', 'wero-core' ),
			),
			'linkedin'        => array(
				'type'    => 'url',
				'default' => 'https://www.linkedin.com/',
				'label'   => __( 'Pagina LinkedIn', 'wero-core' ),
				'section' => 'org',
			),
			'copyright'       => array(
				'type'    => 'text',
				'default' => 'WeRo, Robot Communication Studio',
				'label'   => __( 'Testo del copyright', 'wero-core' ),
				'section' => 'org',
				'help'    => __( 'Nel footer diventa “© anno corrente” seguito da questo testo.', 'wero-core' ),
			),
			// Joint venture.
			'founder1_name'   => array(
				'type'    => 'text',
				'default' => '6.14 S.r.l.',
				'label'   => __( 'Founder 1 – ragione sociale', 'wero-core' ),
				'section' => 'jv',
			),
			'founder1_url'    => array(
				'type'    => 'url',
				'default' => 'https://6punto14.com/',
				'label'   => __( 'Founder 1 – sito', 'wero-core' ),
				'section' => 'jv',
			),
			'founder1_street' => array(
				'type'    => 'text',
				'default' => 'Via Don Bosco 31',
				'label'   => __( 'Founder 1 – indirizzo', 'wero-core' ),
				'section' => 'jv',
			),
			'founder1_postal' => array(
				'type'    => 'text',
				'default' => '20139',
				'label'   => __( 'Founder 1 – CAP', 'wero-core' ),
				'section' => 'jv',
			),
			'founder1_city'   => array(
				'type'    => 'text',
				'default' => 'Milano',
				'label'   => __( 'Founder 1 – città', 'wero-core' ),
				'section' => 'jv',
			),
			'founder1_vat'    => array(
				'type'    => 'text',
				'default' => '07376100967',
				'label'   => __( 'Founder 1 – P.IVA', 'wero-core' ),
				'section' => 'jv',
			),
			'founder2_name'   => array(
				'type'    => 'text',
				'default' => 'Omnicom Advertising',
				'label'   => __( 'Founder 2 – nome', 'wero-core' ),
				'section' => 'jv',
			),
			'founder2_url'    => array(
				'type'    => 'url',
				'default' => 'https://www.omnicomgroup.com/',
				'label'   => __( 'Founder 2 – sito', 'wero-core' ),
				'section' => 'jv',
			),
			// Aspetto (ThemeBridge).
			'color_pink'      => array(
				'type'    => 'color',
				'default' => '',
				'label'   => __( 'Colore rosa', 'wero-core' ),
				'section' => 'look',
				'help'    => __( 'Vuoto = colore del tema (#ff3f7a). Cambia anche la sfumatura.', 'wero-core' ),
			),
			'color_magenta'   => array(
				'type'    => 'color',
				'default' => '',
				'label'   => __( 'Colore magenta', 'wero-core' ),
				'section' => 'look',
				'help'    => __( 'Vuoto = colore del tema (#ff00c7).', 'wero-core' ),
			),
			'color_ink'       => array(
				'type'    => 'color',
				'default' => '',
				'label'   => __( 'Colore inchiostro (testo e fondi scuri)', 'wero-core' ),
				'section' => 'look',
				'help'    => __( 'Vuoto = colore del tema (#15121a).', 'wero-core' ),
			),
			'color_paper'     => array(
				'type'    => 'color',
				'default' => '',
				'label'   => __( 'Colore di fondo', 'wero-core' ),
				'section' => 'look',
				'help'    => __( 'Vuoto = colore del tema (#f4f2e9).', 'wero-core' ),
			),
			'font'            => array(
				'type'    => 'select',
				'default' => 'theme',
				'label'   => __( 'Font del sito', 'wero-core' ),
				'section' => 'look',
				'choices' => array(
					'theme'  => __( 'Quello del tema (Space Grotesk)', 'wero-core' ),
					'system' => __( 'Font di sistema', 'wero-core' ),
				),
			),
			// Animazioni.
			'anim_enabled'    => array(
				'type'    => 'checkbox',
				'default' => 1,
				'label'   => __( 'Animazioni attive (interruttore generale)', 'wero-core' ),
				'section' => 'anim',
				'help'    => __( 'Spento: niente intro, sfondo animato, cursore e comparsa delle sezioni. Sfera, timeline e scorrimenti restano.', 'wero-core' ),
			),
			'intro'           => array(
				'type'    => 'checkbox',
				'default' => 1,
				'label'   => __( 'Intro del logo in home', 'wero-core' ),
				'section' => 'anim',
			),
			'intro_once'      => array(
				'type'    => 'checkbox',
				'default' => 1,
				'label'   => __( 'Intro solo alla prima visita della sessione', 'wero-core' ),
				'section' => 'anim',
			),
			'background'      => array(
				'type'    => 'checkbox',
				'default' => 1,
				'label'   => __( 'Sfondo animato a pixel', 'wero-core' ),
				'section' => 'anim',
			),
			'cursor'          => array(
				'type'    => 'checkbox',
				'default' => 1,
				'label'   => __( 'Cursore “computer vision” (solo con il mouse)', 'wero-core' ),
				'section' => 'anim',
			),
			'reveal'          => array(
				'type'    => 'checkbox',
				'default' => 1,
				'label'   => __( 'Comparsa morbida delle sezioni allo scroll', 'wero-core' ),
				'section' => 'anim',
			),
			'transitions'     => array(
				'type'    => 'checkbox',
				'default' => 1,
				'label'   => __( 'Transizione morbida tra le pagine (View Transitions)', 'wero-core' ),
				'section' => 'anim',
			),
			// Moduli e funzioni.
			'partner_form'    => array(
				'type'    => 'cf7',
				'default' => 0,
				'label'   => __( 'Modulo “Diventa partner”', 'wero-core' ),
				'section' => 'tools',
				'help'    => __( 'Modulo di Contact Form 7 usato dalla sezione “Diventa partner”.', 'wero-core' ),
			),
			'disable_emoji'   => array(
				'type'    => 'checkbox',
				'default' => 1,
				'label'   => __( 'Disattiva gli emoji di WordPress (script e cookie in meno)', 'wero-core' ),
				'section' => 'tools',
			),
		);
	}

	/**
	 * Sezioni della pagina.
	 *
	 * @return array<string, array{0:string, 1:string}>
	 */
	private static function sections(): array {
		return array(
			'org'   => array( __( 'Organizzazione', 'wero-core' ), __( 'Dati di WeRo usati nel footer, nei dati strutturati e dagli agenti AI.', 'wero-core' ) ),
			'jv'    => array( __( 'Joint venture', 'wero-core' ), __( 'WeRo è una joint venture tra 6.14 e Omnicom Advertising e non ha ragione sociale propria: questi dati compaiono nei dati strutturati come “founder”.', 'wero-core' ) ),
			'look'  => array( __( 'Aspetto', 'wero-core' ), __( 'Sovrascrivono colori e font del tema in tutto il sito. Lascia vuoto per usare quelli del tema.', 'wero-core' ) ),
			'anim'  => array( __( 'Animazioni', 'wero-core' ), __( 'Con “movimento ridotto” attivo nel dispositivo del visitatore le animazioni sono comunque ridotte.', 'wero-core' ) ),
			'tools' => array( __( 'Moduli e funzioni', 'wero-core' ), '' ),
		);
	}

	/**
	 * Valori predefiniti.
	 *
	 * @return array<string, mixed>
	 */
	public static function defaults(): array {
		return array_map( static fn( $f ) => $f['default'], self::fields() );
	}

	/**
	 * Legge un'impostazione (con il predefinito).
	 *
	 * @param string $key Chiave.
	 * @return mixed
	 */
	public static function get( string $key ) {
		$all = get_option( self::OPTION, array() );
		$all = is_array( $all ) ? $all : array();
		$def = self::defaults();
		return array_key_exists( $key, $all ) ? $all[ $key ] : ( $def[ $key ] ?? null );
	}

	/**
	 * Indica se un'animazione è attiva (interruttore generale + singola).
	 *
	 * @param string $key intro|background|cursor|reveal.
	 */
	public static function animation( string $key ): bool {
		return (bool) self::get( 'anim_enabled' ) && (bool) self::get( $key );
	}

	/**
	 * Registra l'opzione con lo schema REST (register_setting).
	 */
	public static function register_setting(): void {
		$props = array();
		foreach ( self::fields() as $key => $f ) {
			$type = match ( $f['type'] ) {
				'checkbox' => 'boolean',
				'cf7'      => 'integer',
				default    => 'string',
			};
			$props[ $key ] = array( 'type' => $type );
		}
		register_setting(
			'wero_settings_group',
			self::OPTION,
			array(
				'type'              => 'object',
				'default'           => self::defaults(),
				'sanitize_callback' => array( __CLASS__, 'sanitize' ),
				'show_in_rest'      => array(
					'name'   => 'wero_settings',
					'schema' => array(
						'type'                 => 'object',
						'properties'           => $props,
						'additionalProperties' => false,
					),
				),
			)
		);
	}

	/**
	 * L'opzione letta (anche dalla REST) contiene sempre tutte le chiavi.
	 *
	 * @param mixed $value Valore salvato.
	 * @return array<string, mixed>
	 */
	public static function with_defaults( $value ): array {
		return array_merge( self::defaults(), is_array( $value ) ? $value : array() );
	}

	/**
	 * Sanitizzazione.
	 *
	 * @param mixed $input Valori inviati.
	 * @return array<string, mixed>
	 */
	public static function sanitize( $input ): array {
		$input   = is_array( $input ) ? $input : array();
		$current = self::with_defaults( get_option( self::OPTION, array() ) );
		// Dal modulo della pagina Impostazioni le caselle non spuntate non arrivano: valgono 0.
		// Da REST o da codice arrivano solo le chiavi da cambiare: le altre restano com'erano.
		$from_form = isset( $_POST['option_page'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- il nonce lo verifica options.php.
		$out       = array();
		foreach ( self::fields() as $key => $f ) {
			if ( ! array_key_exists( $key, $input ) && ! ( $from_form && 'checkbox' === $f['type'] ) ) {
				$out[ $key ] = $current[ $key ];
				continue;
			}
			$v = $input[ $key ] ?? null;
			switch ( $f['type'] ) {
				case 'checkbox':
					$out[ $key ] = empty( $v ) ? 0 : 1;
					break;
				case 'email':
					$out[ $key ] = sanitize_email( (string) $v );
					break;
				case 'url':
					$out[ $key ] = esc_url_raw( (string) $v );
					break;
				case 'color':
					$out[ $key ] = (string) ( sanitize_hex_color( (string) $v ) ?? '' );
					break;
				case 'textarea':
					$out[ $key ] = sanitize_textarea_field( (string) $v );
					break;
				case 'select':
					$out[ $key ] = array_key_exists( (string) $v, $f['choices'] ) ? (string) $v : $f['default'];
					break;
				case 'cf7':
					$out[ $key ] = absint( $v );
					break;
				default:
					$out[ $key ] = sanitize_text_field( (string) $v );
			}
		}
		return $out;
	}

	/**
	 * Voce di menu.
	 */
	public static function menu(): void {
		add_options_page(
			__( 'Impostazioni sito WeRo', 'wero-core' ),
			__( 'WeRo', 'wero-core' ),
			'manage_options',
			self::PAGE,
			array( __CLASS__, 'render_page' )
		);
		foreach ( self::sections() as $id => $s ) {
			add_settings_section(
				'wero_' . $id,
				$s[0],
				static function () use ( $s ): void {
					if ( $s[1] ) {
						echo '<p>' . esc_html( $s[1] ) . '</p>';
					}
				},
				self::PAGE
			);
		}
		foreach ( self::fields() as $key => $f ) {
			add_settings_field(
				'wero_' . $key,
				$f['label'],
				array( __CLASS__, 'render_field' ),
				self::PAGE,
				'wero_' . $f['section'],
				array(
					'key'       => $key,
					'label_for' => 'wero_' . $key,
				)
			);
		}
	}

	/**
	 * Link "Impostazioni" nell'elenco dei plugin.
	 *
	 * @param string[] $links Link.
	 * @return string[]
	 */
	public static function action_link( array $links ): array {
		array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=' . self::PAGE ) ) . '">' . esc_html__( 'Impostazioni', 'wero-core' ) . '</a>' );
		return $links;
	}

	/**
	 * Un campo.
	 *
	 * @param array{key:string} $args Argomenti.
	 */
	public static function render_field( array $args ): void {
		$key   = $args['key'];
		$f     = self::fields()[ $key ];
		$value = self::get( $key );
		$name  = self::OPTION . '[' . $key . ']';
		$id    = 'wero_' . $key;
		switch ( $f['type'] ) {
			case 'checkbox':
				printf( '<input type="checkbox" id="%s" name="%s" value="1" %s>', esc_attr( $id ), esc_attr( $name ), checked( (bool) $value, true, false ) );
				break;
			case 'textarea':
				printf( '<textarea id="%s" name="%s" rows="3" class="large-text">%s</textarea>', esc_attr( $id ), esc_attr( $name ), esc_textarea( (string) $value ) );
				break;
			case 'color':
				printf( '<input type="text" id="%s" name="%s" value="%s" class="regular-text" placeholder="#rrggbb" pattern="#[0-9a-fA-F]{6}">', esc_attr( $id ), esc_attr( $name ), esc_attr( (string) $value ) );
				if ( $value ) {
					printf( ' <span style="display:inline-block;width:22px;height:22px;border-radius:50%%;vertical-align:middle;background:%s;border:1px solid #ccc"></span>', esc_attr( (string) $value ) );
				}
				break;
			case 'select':
				printf( '<select id="%s" name="%s">', esc_attr( $id ), esc_attr( $name ) );
				foreach ( $f['choices'] as $k => $label ) {
					printf( '<option value="%s" %s>%s</option>', esc_attr( $k ), selected( $value, $k, false ), esc_html( $label ) );
				}
				echo '</select>';
				break;
			case 'cf7':
				$forms = post_type_exists( 'wpcf7_contact_form' ) ? get_posts(
					array(
						'post_type'      => 'wpcf7_contact_form',
						'posts_per_page' => 50,
						'orderby'        => 'title',
						'order'          => 'ASC',
					)
				) : array();
				printf( '<select id="%s" name="%s"><option value="0">%s</option>', esc_attr( $id ), esc_attr( $name ), esc_html__( '— nessuno —', 'wero-core' ) );
				foreach ( $forms as $form ) {
					printf( '<option value="%d" %s>%s</option>', (int) $form->ID, selected( (int) $value, (int) $form->ID, false ), esc_html( get_the_title( $form ) ) );
				}
				echo '</select>';
				break;
			default:
				$type = in_array( $f['type'], array( 'email', 'url' ), true ) ? $f['type'] : 'text';
				printf( '<input type="%s" id="%s" name="%s" value="%s" class="regular-text">', esc_attr( $type ), esc_attr( $id ), esc_attr( $name ), esc_attr( (string) $value ) );
		}
		if ( ! empty( $f['help'] ) ) {
			echo '<p class="description">' . esc_html( $f['help'] ) . '</p>';
		}
	}

	/**
	 * Pagina.
	 */
	public static function render_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		echo '<div class="wrap"><h1>' . esc_html__( 'Impostazioni sito WeRo', 'wero-core' ) . '</h1>';
		echo '<form action="options.php" method="post">';
		settings_fields( 'wero_settings_group' );
		do_settings_sections( self::PAGE );
		submit_button();
		echo '</form></div>';
	}
}
