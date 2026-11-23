<?php
/**
 * WeRo theme: solo supporti ed enqueue.
 *
 * Tutto ciò che non è aspetto (contenuti, campi, blocchi custom, opzioni, SEO)
 * vive nel plugin WeRo Core. Il tema non legge opzioni: chiede al plugin,
 * tramite il filtro `wero_theme_feature`, se una funzione visiva è attiva.
 * Senza plugin valgono i valori predefiniti (tutto attivo).
 *
 * @package WeroTheme
 */

defined( 'ABSPATH' ) || exit;

define( 'WERO_THEME_VERSION', wp_get_theme( get_template() )->get( 'Version' ) );

/**
 * Indica se una funzione visiva del tema è attiva.
 *
 * @param string $feature Nome: background, cursor, reveal, nav-spy.
 * @return bool
 */
function wero_theme_feature( string $feature ): bool {
	/**
	 * Filtra l'attivazione di una funzione visiva del tema.
	 *
	 * @param bool   $enabled Predefinito true.
	 * @param string $feature Nome della funzione.
	 */
	return (bool) apply_filters( 'wero_theme_feature', true, $feature );
}

/**
 * Supporti del tema.
 */
function wero_theme_setup(): void {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 44,
			'width'       => 160,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_editor_style( 'assets/css/site.css' );
	load_theme_textdomain( 'wero-theme', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'wero_theme_setup' );

/**
 * CSS globale minimo (sfondo, canvas, focus, utilità) per tutte le pagine.
 */
function wero_theme_enqueue_styles(): void {
	wp_enqueue_style(
		'wero-theme-site',
		get_theme_file_uri( 'assets/css/site.css' ),
		array(),
		WERO_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'wero_theme_enqueue_styles' );

/**
 * N3 — transizione tra pagine con la View Transitions API (solo CSS, nativa del browser).
 * Si spegne dalle Impostazioni sito (filtro wero_theme_feature, funzione "transitions").
 */
function wero_theme_enqueue_transitions(): void {
	if ( ! wero_theme_feature( 'transitions' ) ) {
		return;
	}
	wp_enqueue_style(
		'wero-theme-transitions',
		get_theme_file_uri( 'assets/css/view-transitions.css' ),
		array(),
		WERO_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'wero_theme_enqueue_transitions' );

/**
 * CSS per blocco: ogni file assets/css/blocks/{namespace}--{blocco}.css
 * (es. core--navigation.css) viene caricato solo nelle pagine in cui quel blocco è presente.
 */
function wero_theme_enqueue_block_styles(): void {
	$files = glob( get_theme_file_path( 'assets/css/blocks/*.css' ) );
	if ( ! $files ) {
		return;
	}
	foreach ( $files as $file ) {
		$name = basename( $file, '.css' );           // Es. core--navigation.
		if ( false === strpos( $name, '--' ) ) {
			continue;
		}
		$block = str_replace( '--', '/', $name );    // Es. core/navigation.
		wp_enqueue_block_style(
			$block,
			array(
				'handle' => 'wero-theme-' . $name,
				'src'    => get_theme_file_uri( 'assets/css/blocks/' . $name . '.css' ),
				'path'   => $file,
				'ver'    => WERO_THEME_VERSION,
			)
		);
	}
}
add_action( 'init', 'wero_theme_enqueue_block_styles' );

/**
 * Script globali leggeri (nessuna libreria), caricati in fondo con defer:
 * sfondo "pixel beams" e cursore "computer vision". Ognuno si può spegnere dal plugin.
 */
function wero_theme_enqueue_scripts(): void {
	$scripts = array(
		'background' => 'background.js',
		'cursor'     => 'cursor.js',
	);
	foreach ( $scripts as $feature => $file ) {
		if ( ! wero_theme_feature( $feature ) ) {
			continue;
		}
		wp_enqueue_script(
			'wero-theme-' . $feature,
			get_theme_file_uri( 'assets/js/' . $file ),
			array(),
			WERO_THEME_VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'wero_theme_enqueue_scripts' );

/**
 * Script legati a un blocco: caricati solo quando il blocco è nella pagina.
 * - Navigazione: voce attiva che segue lo scorrimento della home.
 * - Galleria con stile "A scorrimento": frecce avanti/indietro.
 *
 * @param string $content Markup del blocco.
 * @param array  $block   Blocco analizzato.
 * @return string
 */
function wero_theme_enqueue_block_scripts( string $content, array $block ): string {
	$map  = array(
		'core/navigation' => array( 'nav', 'nav-spy' ),
		'core/gallery'    => array( 'gallery-scroller', '' ),
	);
	$name = $block['blockName'] ?? '';
	if ( ! isset( $map[ $name ] ) ) {
		return $content;
	}
	list( $file, $feature ) = $map[ $name ];
	if ( 'core/gallery' === $name && false === strpos( $block['attrs']['className'] ?? '', 'is-style-gallery-scroller' ) ) {
		return $content;
	}
	if ( $feature && ! wero_theme_feature( $feature ) ) {
		return $content;
	}
	wp_enqueue_script(
		'wero-theme-' . $file,
		get_theme_file_uri( 'assets/js/' . $file . '.js' ),
		array(),
		WERO_THEME_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	return $content;
}
add_filter( 'render_block', 'wero_theme_enqueue_block_scripts', 10, 2 );

/**
 * Preload del solo font critico (Space Grotesk, alfabeto latino): il testo
 * compare subito con il font giusto. Gli altri sottoinsiemi si caricano se servono.
 */
function wero_theme_preload_font(): void {
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( get_theme_file_uri( 'assets/fonts/space-grotesk/space-grotesk-latin-wght-normal.woff2' ) )
	);
}
add_action( 'wp_head', 'wero_theme_preload_font', 1 );
