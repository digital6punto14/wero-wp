<?php
/**
 * Title: Blocco racconto: immagine e testo
 * Slug: wero-theme/blocco-racconto
 * Categories: wero-progetti
 * Description: Immagine affiancata a titolo, testo e link. Inverti l’ordine delle colonne per alternarlo.
 *
 * @package WeroTheme
 */

$wero_img = get_theme_file_uri( 'assets/images/patterns/' );
?>
<!-- wp:columns {"verticalAlignment":"center","className":"wero-story__block","style":{"spacing":{"blockGap":{"left":"clamp(28px, 5vw, 80px)","top":"clamp(28px, 4vw, 48px)"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center wero-story__block"><!-- wp:column {"verticalAlignment":"center","width":"53%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:53%"><!-- wp:image {"sizeSlug": "full", "linkDestination": "none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( $wero_img . 'segnaposto.svg' ); ?>" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"47%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:47%"><!-- wp:group {"className":"wero-story__text","style":{"spacing":{"blockGap":"16px"}},"layout":{"type":"default"}} -->
<div class="wp-block-group wero-story__text"><!-- wp:heading {"fontSize":"heading-story","style":{"typography":{"lineHeight":"1.04","letterSpacing":"-0.035em","fontWeight":"500"}}} -->
<h2 class="wp-block-heading has-heading-story-font-size" style="font-weight:500;letter-spacing:-0.035em;line-height:1.04">Titolo del blocco</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Testo del blocco: racconta una fase o un aspetto del progetto.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-button-chip"} -->
<div class="wp-block-button is-style-button-chip"><a class="wp-block-button__link wp-element-button" href="#" target="_blank" rel="noreferrer noopener">Link di approfondimento</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
