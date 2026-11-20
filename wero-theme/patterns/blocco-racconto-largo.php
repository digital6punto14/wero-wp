<?php
/**
 * Title: Blocco racconto largo
 * Slug: wero-theme/blocco-racconto-largo
 * Categories: wero-progetti
 * Description: Immagine a tutta larghezza seguita da titolo e testo.
 *
 * @package WeroTheme
 */

$wero_img = get_theme_file_uri( 'assets/images/patterns/' );
?>
<!-- wp:group {"className":"wero-story__block is-wide","style":{"spacing":{"blockGap":"clamp(28px, 4vw, 48px)"}},"layout":{"type":"default"}} -->
<div class="wp-block-group wero-story__block is-wide"><!-- wp:image {"sizeSlug": "full", "linkDestination": "none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( $wero_img . 'segnaposto.svg' ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"wero-story__text","style":{"spacing":{"blockGap":"16px"}},"layout":{"type":"default"}} -->
<div class="wp-block-group wero-story__text"><!-- wp:heading {"fontSize":"heading-story","style":{"typography":{"lineHeight":"1.04","letterSpacing":"-0.035em","fontWeight":"500"}}} -->
<h2 class="wp-block-heading has-heading-story-font-size" style="font-weight:500;letter-spacing:-0.035em;line-height:1.04">Titolo del blocco</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Testo del blocco.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
