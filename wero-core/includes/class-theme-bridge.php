<?php
/**
 * ThemeBridge: il plugin controlla anche l'aspetto globale.
 *
 * - Colori e font delle Impostazioni sito sovrascrivono theme.json del tema
 *   (filtro wp_theme_json_data_theme): cambiano ovunque, nei blocchi e nel Site Editor.
 * - Interruttori delle animazioni: rispondono al filtro `wero_theme_feature` del tema
 *   e aggiungono le classi sul body.
 * - Intro: prepara la pagina prima del primo paint (solo se il template della home
 *   contiene il blocco wero/intro).
 * - Emoji di WordPress disattivabili.
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Ponte con il tema.
 */
final class Theme_Bridge {

	/**
	 * Hook.
	 */
	public static function register(): void {
		add_filter( 'wp_theme_json_data_theme', array( __CLASS__, 'theme_json' ) );
		add_filter( 'wero_theme_feature', array( __CLASS__, 'feature' ), 10, 2 );
		add_filter( 'body_class', array( __CLASS__, 'body_class' ) );
		add_action( 'wp_head', array( __CLASS__, 'intro_head' ), 1 );
		add_action( 'init', array( __CLASS__, 'emoji' ) );
	}

	/**
	 * Sovrascrive palette, sfumatura e font.
	 *
	 * @param \WP_Theme_JSON_Data $theme_json Dati del tema.
	 * @return \WP_Theme_JSON_Data
	 */
	public static function theme_json( $theme_json ) {
		$map    = array(
			'pink'    => Settings::get( 'color_pink' ),
			'magenta' => Settings::get( 'color_magenta' ),
			'ink'     => Settings::get( 'color_ink' ),
			'paper'   => Settings::get( 'color_paper' ),
		);
		$map    = array_filter( $map );
		$font   = Settings::get( 'font' );
		$update = array( 'version' => 3 );

		if ( $map ) {
			$data    = $theme_json->get_data();
			$palette = $data['settings']['color']['palette']['theme'] ?? array();
			foreach ( $palette as $i => $entry ) {
				if ( isset( $map[ $entry['slug'] ] ) ) {
					$palette[ $i ]['color'] = $map[ $entry['slug'] ];
				}
			}
			$update['settings']['color']['palette'] = $palette;

			$pink      = $map['pink'] ?? self::palette_value( $palette, 'pink', '#ff3f7a' );
			$magenta   = $map['magenta'] ?? self::palette_value( $palette, 'magenta', '#ff00c7' );
			$gradients = $data['settings']['color']['gradients']['theme'] ?? array();
			foreach ( $gradients as $i => $entry ) {
				if ( 'wero' === $entry['slug'] ) {
					$gradients[ $i ]['gradient'] = sprintf( 'linear-gradient(90deg, %s 0%%, %s 100%%)', $pink, $magenta );
				}
			}
			$update['settings']['color']['gradients'] = $gradients;
		}

		if ( 'system' === $font ) {
			$update['styles']['typography']['fontFamily'] = 'var(--wp--preset--font-family--system)';
		}

		return count( $update ) > 1 ? $theme_json->update_with( $update ) : $theme_json;
	}

	/**
	 * Valore di un colore della palette.
	 *
	 * @param array<int, array{slug:string, color:string}> $palette Palette.
	 * @param string                                       $slug    Slug.
	 * @param string                                       $fallback Predefinito.
	 */
	private static function palette_value( array $palette, string $slug, string $fallback ): string {
		foreach ( $palette as $entry ) {
			if ( $slug === $entry['slug'] ) {
				return $entry['color'];
			}
		}
		return $fallback;
	}

	/**
	 * Funzioni visive del tema (sfondo, cursore, comparsa, voce attiva del menu).
	 *
	 * @param bool   $enabled Valore del tema.
	 * @param string $feature Nome.
	 */
	public static function feature( bool $enabled, string $feature ): bool {
		if ( in_array( $feature, array( 'background', 'cursor', 'reveal', 'transitions' ), true ) ) {
			return $enabled && Settings::animation( $feature );
		}
		return $enabled;
	}

	/**
	 * Classi sul body.
	 *
	 * @param string[] $classes Classi.
	 * @return string[]
	 */
	public static function body_class( array $classes ): array {
		if ( Settings::animation( 'reveal' ) ) {
			$classes[] = 'wero-reveal';
		}
		return $classes;
	}

	/**
	 * L'intro è attiva in questa richiesta? (home + interruttori + template con il blocco).
	 */
	public static function intro_active(): bool {
		if ( ! is_front_page() || ! Settings::animation( 'intro' ) ) {
			return false;
		}
		$template = get_block_template( get_stylesheet() . '//front-page' );
		return $template && str_contains( (string) $template->content, 'wp:wero/intro' );
	}

	/**
	 * Prima del primo paint: decide se mostrare l'intro (non con movimento ridotto,
	 * ?intro=0, o — se impostato — se già vista nella sessione).
	 */
	public static function intro_head(): void {
		if ( ! self::intro_active() ) {
			return;
		}
		$once = Settings::get( 'intro_once' ) ? 'true' : 'false';
		$js   = "(function(){var s=false,d=document.documentElement;try{if(matchMedia('(prefers-reduced-motion: reduce)').matches)s=true;if(/[?&]intro=0/.test(location.search))s=true;if({$once}&&sessionStorage.getItem('weroIntroSeen'))s=true;}catch(e){}d.classList.add(s?'intro-skip':'intro-on');})();";
		wp_print_inline_script_tag( $js, array( 'id' => 'wero-intro-head' ) );
	}

	/**
	 * Emoji di WordPress (script e stili) disattivati se richiesto.
	 */
	public static function emoji(): void {
		if ( ! Settings::get( 'disable_emoji' ) ) {
			return;
		}
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
		remove_action( 'admin_enqueue_scripts', 'wp_enqueue_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'emoji_svg_url', '__return_false' );
	}
}
