<?php
/**
 * Title: Home – Hero video
 * Slug: wero-theme/home-hero
 * Categories: wero-home
 * Description: Copertina a tutto schermo con il video “The future of robotics”: titolo, testo e pulsanti modificabili, video e copertina dalle impostazioni del blocco.
 *
 * @package WeroTheme
 */

$wero_img = get_theme_file_uri( 'assets/images/patterns/' );
?>
<!-- wp:wero/hero-video {"align":"full","poster":"<?php echo esc_url( $wero_img . 'showreel-poster.webp' ); ?>"} -->
<!-- wp:heading {"level":1,"className":"wero-hero__title","fontSize":"hero","textColor":"white","style":{"typography":{"lineHeight":"1","letterSpacing":"-0.04em"}}} -->
<h1 class="wp-block-heading wero-hero__title has-white-color has-text-color has-hero-font-size" style="letter-spacing:-0.04em;line-height:1">Il primo Robot Communication Studio</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className": "wero-hero__lead"} -->
<p class="wero-hero__lead">Un ecosistema di partner che esplora la robotica e ne racconta l'evoluzione.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className": "wero-hero__actions"} -->
<div class="wp-block-buttons wero-hero__actions"><!-- wp:button {"className": "wero-play"} -->
<div class="wp-block-button wero-play"><a class="wp-block-button__link wp-element-button" href="#guarda">Guarda “The future of robotics”</a></div>
<!-- /wp:button -->

<!-- wp:button {"className": "is-style-button-ghost"} -->
<div class="wp-block-button is-style-button-ghost"><a class="wp-block-button__link wp-element-button" href="#chi">Scopri WeRo</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:wero/hero-video -->
