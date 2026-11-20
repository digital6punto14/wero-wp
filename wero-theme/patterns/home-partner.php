<?php
/**
 * Title: Home – Diventa partner
 * Slug: wero-theme/home-partner
 * Categories: wero-home
 * Description: Pannello “vetro” con titolo, testo e il modulo di Contact Form 7 (scegli il modulo nel blocco).
 *
 * @package WeroTheme
 */

$wero_img = get_theme_file_uri( 'assets/images/patterns/' );
?>
<!-- wp:group {"tagName": "section", "anchor": "partner", "align": "full", "className": "wero-section", "style": {"spacing": {"padding": {"top": "var:preset|spacing|section", "bottom": "var:preset|spacing|section"}}}, "layout": {"type": "constrained"}} -->
<section id="partner" class="wp-block-group alignfull wero-section" style="padding-top:var(--wp--preset--spacing--section);padding-bottom:var(--wp--preset--spacing--section)"><!-- wp:columns {"className":"is-style-group-glass wero-contact","style":{"spacing":{"blockGap":{"left":"clamp(28px, 5vw, 72px)","top":"clamp(28px, 4vw, 48px)"}}}} -->
<div class="wp-block-columns is-style-group-glass wero-contact"><!-- wp:column {"style":{"spacing":{"blockGap":"20px"}}} -->
<div class="wp-block-column"><!-- wp:heading {"className": "is-style-heading-gradient"} -->
<h2 class="wp-block-heading is-style-heading-gradient">Vuoi diventare<br>un nostro partner?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize": "lead"} -->
<p class="has-lead-font-size">Raccontaci chi sei e su cosa vorresti collaborare: ti ricontatteremo.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:contact-form-7/contact-form-selector /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
