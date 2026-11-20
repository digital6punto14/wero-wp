<?php
/**
 * Controllo dell'editor.
 *
 * - Blocchi esclusi per tutti: HTML personalizzato, Shortcode, Classico
 *   (i contenuti si fanno solo con blocchi).
 * - Categorie dei pattern del progetto.
 * - Editor (ruolo senza "edit_theme_options"): niente editor di codice e,
 *   nella home, solo modifica dei contenuti (struttura bloccata).
 * - Query Loop "Altri progetti" (classe wero-related): esclude il progetto corrente.
 *
 * @package WeroCore
 */

namespace Wero\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Editor.
 */
final class Editor {

	/**
	 * Hook.
	 */
	public static function register(): void {
		add_filter( 'allowed_block_types_all', array( __CLASS__, 'allowed_blocks' ), 10, 2 );
		add_action( 'init', array( __CLASS__, 'pattern_categories' ), 9 );
		add_filter( 'block_editor_settings_all', array( __CLASS__, 'editor_settings' ), 10, 2 );
		add_filter( 'query_loop_block_query_vars', array( __CLASS__, 'related_query' ), 10, 2 );
	}

	/**
	 * Blocchi esclusi.
	 *
	 * @param bool|string[]            $allowed Blocchi consentiti.
	 * @param \WP_Block_Editor_Context $context Contesto.
	 * @return bool|string[]
	 */
	public static function allowed_blocks( $allowed, $context ) {
		unset( $context );
		$blocked = array( 'core/html', 'core/shortcode', 'core/freeform' );
		if ( true === $allowed ) {
			$allowed = array_keys( \WP_Block_Type_Registry::get_instance()->get_all_registered() );
		}
		if ( ! is_array( $allowed ) ) {
			return $allowed;
		}
		return array_values( array_diff( $allowed, $blocked ) );
	}

	/**
	 * Categorie dei pattern (usate dai pattern del tema).
	 */
	public static function pattern_categories(): void {
		register_block_pattern_category( 'wero-home', array( 'label' => __( 'WeRo – Home', 'wero-core' ) ) );
		register_block_pattern_category( 'wero-progetti', array( 'label' => __( 'WeRo – Progetti', 'wero-core' ) ) );
		register_block_pattern_category( 'wero-comuni', array( 'label' => __( 'WeRo – Sezioni comuni', 'wero-core' ) ) );
	}

	/**
	 * Restrizioni per chi non è amministratore.
	 *
	 * @param array<string, mixed>     $settings Impostazioni dell'editor.
	 * @param \WP_Block_Editor_Context $context  Contesto.
	 * @return array<string, mixed>
	 */
	public static function editor_settings( array $settings, $context ): array {
		if ( current_user_can( 'edit_theme_options' ) ) {
			return $settings;
		}
		$settings['codeEditingEnabled'] = false;
		$post                           = $context->post ?? null;
		if ( $post instanceof \WP_Post && (int) get_option( 'page_on_front' ) === $post->ID ) {
			$settings['templateLock'] = 'contentOnly';
		}
		return $settings;
	}

	/**
	 * "Altri progetti": esclude il progetto che si sta guardando.
	 *
	 * @param array<string, mixed> $query Variabili della query.
	 * @param \WP_Block            $block Blocco Query Loop (post-template).
	 * @return array<string, mixed>
	 */
	public static function related_query( array $query, $block ): array {
		$class = (string) ( $block->context['query']['className'] ?? '' );
		$is_related = str_contains( (string) ( $block->parsed_block['attrs']['className'] ?? '' ), 'wero-scroll-mobile' );
		if ( ( $is_related || str_contains( $class, 'wero-related' ) ) && is_singular( 'progetto' ) ) {
			$query['post__not_in'] = array_merge( (array) ( $query['post__not_in'] ?? array() ), array( get_queried_object_id() ) );
		}
		return $query;
	}
}
