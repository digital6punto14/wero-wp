<?php
/**
 * Title: Footer
 * Slug: wero-theme/footer
 * Categories: wero-comuni
 * Block Types: core/template-part/footer
 * Inserter: no
 * Description: Footer del sito: logo, contatti, social, founder, copyright.
 *
 * Contatti e LinkedIn sono collegati alle Impostazioni sito del plugin
 * (Block Bindings, sorgente "wero/site"): si cambiano in un solo punto.
 *
 * @package WeroTheme
 */

$wero_img = get_theme_file_uri( 'assets/images/' );
?>
<!-- wp:group {"align":"full","className":"wero-footer","style":{"spacing":{"padding":{"top":"var:preset|spacing|block","bottom":"32px","left":"var:custom|gutter","right":"var:custom|gutter"}},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"backgroundColor":"ink","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull wero-footer has-white-color has-ink-background-color has-text-color has-background has-link-color" style="padding-top:var(--wp--preset--spacing--block);padding-right:var(--wp--custom--gutter);padding-bottom:32px;padding-left:var(--wp--custom--gutter)"><!-- wp:columns {"className":"wero-footer__top","style":{"spacing":{"blockGap":{"left":"48px","top":"48px"}}}} -->
<div class="wp-block-columns wero-footer__top"><!-- wp:column {"width":"45%"} -->
<div class="wp-block-column" style="flex-basis:45%"><!-- wp:image {"width":"440px","sizeSlug":"full","linkDestination":"none","className":"wero-footer__logo","style":{"border":{"radius":"0px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border wero-footer__logo"><img src="<?php echo esc_url( $wero_img . 'wero-logo.svg' ); ?>" alt="<?php esc_attr_e( 'WeRo, Robot Communication Studio', 'wero-theme' ); ?>" style="border-radius:0px;width:440px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"55%"} -->
<div class="wp-block-column" style="flex-basis:55%"><!-- wp:columns {"isStackedOnMobile":true,"className":"wero-footer__cols","style":{"spacing":{"blockGap":{"left":"24px","top":"24px"}}}} -->
<div class="wp-block-columns wero-footer__cols"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"className":"is-style-heading-gradient wero-footer__h","fontSize":"small"} -->
<p class="is-style-heading-gradient wero-footer__h has-small-font-size"><?php esc_html_e( 'Contatti', 'wero-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"wero/site","args":{"key":"email_link"}}}}} -->
<p><a href="mailto:contact@we-ro.it">contact@we-ro.it</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"className":"is-style-heading-gradient wero-footer__h","fontSize":"small"} -->
<p class="is-style-heading-gradient wero-footer__h has-small-font-size"><?php esc_html_e( 'Seguici', 'wero-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"wero/site","args":{"key":"linkedin_link"}}}}} -->
<p><a href="https://www.linkedin.com/" target="_blank" rel="noreferrer noopener">LinkedIn</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"className":"is-style-heading-gradient wero-footer__h","fontSize":"small"} -->
<p class="is-style-heading-gradient wero-footer__h has-small-font-size"><?php esc_html_e( 'Founder', 'wero-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"wero-footer__logos","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"28px"}}} -->
<div class="wp-block-group wero-footer__logos"><!-- wp:image {"height":"40px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( $wero_img . 'omnicom-advertising.png' ); ?>" alt="Omnicom Advertising" style="border-radius:0px;height:40px"/></figure>
<!-- /wp:image -->

<!-- wp:image {"height":"40px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( $wero_img . '6punto14.svg' ); ?>" alt="6.14" style="border-radius:0px;height:40px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"className":"wero-footer__bottom","style":{"spacing":{"margin":{"top":"var:preset|spacing|block"},"padding":{"top":"22px"}},"border":{"top":{"color":"rgba(255,255,255,0.15)","width":"1px","style":"solid"}}},"fontSize":"small","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group wero-footer__bottom has-small-font-size" style="border-top-color:rgba(255,255,255,0.15);border-top-style:solid;border-top-width:1px;margin-top:var(--wp--preset--spacing--block);padding-top:22px"><!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"wero/site","args":{"key":"copyright"}}}}} -->
<p>© 2026 WeRo, Robot Communication Studio</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"wero-footer__legal"} -->
<p class="wero-footer__legal"><a href="/privacy-policy/"><?php esc_html_e( 'Privacy policy', 'wero-theme' ); ?></a> · <a href="/cookie-policy/"><?php esc_html_e( 'Cookie policy', 'wero-theme' ); ?></a> · <a href="#top"><?php esc_html_e( 'Torna su', 'wero-theme' ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
