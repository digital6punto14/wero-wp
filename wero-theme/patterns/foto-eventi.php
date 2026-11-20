<?php
/**
 * Title: Foto a scorrimento
 * Slug: wero-theme/foto-eventi
 * Categories: wero-home, wero-comuni
 * Description: Galleria di foto a scorrimento orizzontale con frecce su desktop. Si aggiungono, tolgono e riordinano le foto come in una normale galleria.
 *
 * @package WeroTheme
 */

$wero_img = get_theme_file_uri( 'assets/images/patterns/' );
?>
<!-- wp:gallery {"linkTo":"none","align":"full","className":"is-style-gallery-scroller","style":{"spacing":{"margin":{"top":"var:preset|spacing|block"}}}} -->
<figure class="wp-block-gallery alignfull has-nested-images columns-default is-cropped is-style-gallery-scroller" style="margin-top:var(--wp--preset--spacing--block)"><!-- wp:image {"sizeSlug": "full", "linkDestination": "none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( $wero_img . 'evento-get-ready.webp' ); ?>" alt="Presentazione WeRo durante un evento: sullo schermo la slide Get Ready"/></figure>
<!-- /wp:image -->

<!-- wp:image {"sizeSlug": "full", "linkDestination": "none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( $wero_img . 'r1.webp' ); ?>" alt="Il robot R1 visto di fronte"/></figure>
<!-- /wp:image -->

<!-- wp:image {"sizeSlug": "full", "linkDestination": "none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( $wero_img . 'evento-panel.webp' ); ?>" alt="Panel WeRo sulle sfide e opportunità della robotica per la ricerca"/></figure>
<!-- /wp:image -->

<!-- wp:image {"sizeSlug": "full", "linkDestination": "none", "className": "is-tall"} -->
<figure class="wp-block-image size-full is-tall"><img src="<?php echo esc_url( $wero_img . 'gasometro.webp' ); ?>" alt="L'evento WeRo How can we disrupt robotics perception al Gazometro di Roma"/></figure>
<!-- /wp:image --></figure>
<!-- /wp:gallery -->
