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
		add_filter( 'render_block_core/post-featured-image', array( __CLASS__, 'project_placeholder' ), 10, 3 );
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
		$class      = (string) ( $block->context['query']['className'] ?? '' );
		$is_related = str_contains( (string) ( $block->parsed_block['attrs']['className'] ?? '' ), 'wero-scroll-mobile' );
		if ( ( $is_related || str_contains( $class, 'wero-related' ) ) && is_singular( 'progetto' ) ) {
			// Esclude un solo contenuto (quello aperto) da una query di 3 elementi: impatto trascurabile.
			$query['post__not_in'] = array_merge( (array) ( $query['post__not_in'] ?? array() ), array( get_queried_object_id() ) );
			// Stesso ordine della sfera in home (campo Ordine), non gli ultimi creati.
			$query['orderby'] = array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			);
		}
		return $query;
	}

	/**
	 * Progetti senza immagine in evidenza: copertina di riserva con il colore del mondo
	 * (come le card dell'originale), così le card di archivio e "Altri progetti" restano uniformi.
	 *
	 * @param string    $content Markup del blocco.
	 * @param array     $parsed  Blocco analizzato.
	 * @param \WP_Block $block   Istanza (contesto postId).
	 */
	public static function project_placeholder( string $content, array $parsed, $block ): string {
		$post_id = (int) ( $block->context['postId'] ?? 0 );
		if ( '' !== trim( $content ) || ! $post_id || 'progetto' !== get_post_type( $post_id ) ) {
			return $content;
		}
		$card  = Fields::image( Fields::post( $post_id, 'immagine_card', 0 ), 'large', array( 'alt' => '' ) );
		$world = Taxonomies::world_of( $post_id );
		$style = $world ? (string) Fields::term( $world, 'stile', 'scuro' ) : 'scuro';
		$inner = $card ? $card : '<span class="wero-ph wero-ph--' . esc_attr( $style ) . '" aria-hidden="true"></span>';
		$ratio = (string) ( $parsed['attrs']['aspectRatio'] ?? '4/3' );
		if ( ! empty( $parsed['attrs']['isLink'] ) ) {
			$inner = '<a href="' . esc_url( get_permalink( $post_id ) ) . '" tabindex="-1" aria-hidden="true">' . $inner . '</a>';
		}
		return '<figure class="wp-block-post-featured-image wero-ph-figure" style="aspect-ratio:' . esc_attr( $ratio ) . '">' . $inner . '</figure>';
	}
}
