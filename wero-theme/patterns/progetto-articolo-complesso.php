<?php
/**
 * Title: Progetto – Articolo complesso (contenuto di partenza)
 * Slug: wero-theme/progetto-articolo-complesso
 * Categories: wero-progetti
 * Description: Sequenza di blocchi racconto: immagine e testo alternati, un video in loop e un blocco largo.
 * Block Types: core/post-content
 * Post Types: progetto
 * Viewport Width: 1280
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
<h2 class="wp-block-heading has-heading-story-font-size" style="font-weight:500;letter-spacing:-0.035em;line-height:1.04">Un marchio che nasce dalla ricerca</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Testo del primo blocco del racconto.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:columns {"verticalAlignment":"center","className":"wero-story__block is-reversed","style":{"spacing":{"blockGap":{"left":"clamp(28px, 5vw, 80px)","top":"clamp(28px, 4vw, 48px)"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center wero-story__block is-reversed"><!-- wp:column {"verticalAlignment":"center","width":"47%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:47%"><!-- wp:group {"className":"wero-story__text","style":{"spacing":{"blockGap":"16px"}},"layout":{"type":"default"}} -->
<div class="wp-block-group wero-story__text"><!-- wp:heading {"fontSize":"heading-story","style":{"typography":{"lineHeight":"1.04","letterSpacing":"-0.035em","fontWeight":"500"}}} -->
<h2 class="wp-block-heading has-heading-story-font-size" style="font-weight:500;letter-spacing:-0.035em;line-height:1.04">Un segno che si muove</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Testo del secondo blocco del racconto.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"53%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:53%"><!-- wp:video {"className":"wero-media-square"} -->
<figure class="wp-block-video wero-media-square"><video autoplay loop muted poster="<?php echo esc_url( $wero_img . 'gb-logo-poster.jpg' ); ?>" preload="auto" src="<?php echo esc_url( $wero_img . 'gb-logo.mp4' ); ?>" playsinline></video></figure>
<!-- /wp:video --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"className":"wero-story__block is-wide","style":{"spacing":{"blockGap":"clamp(28px, 4vw, 48px)"}},"layout":{"type":"default"}} -->
<div class="wp-block-group wero-story__block is-wide"><!-- wp:image {"sizeSlug": "full", "linkDestination": "none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( $wero_img . 'segnaposto.svg' ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"wero-story__text","style":{"spacing":{"blockGap":"16px"}},"layout":{"type":"default"}} -->
<div class="wp-block-group wero-story__text"><!-- wp:heading {"fontSize":"heading-story","style":{"typography":{"lineHeight":"1.04","letterSpacing":"-0.035em","fontWeight":"500"}}} -->
<h2 class="wp-block-heading has-heading-story-font-size" style="font-weight:500;letter-spacing:-0.035em;line-height:1.04">Un’identità distintiva</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Testo del blocco largo.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-button-chip"} -->
<div class="wp-block-button is-style-button-chip"><a class="wp-block-button__link wp-element-button" href="#" target="_blank" rel="noreferrer noopener">Il case study</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"wero-theme/foto-progetto"} /-->
