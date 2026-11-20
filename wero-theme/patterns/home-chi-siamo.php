<?php
/**
 * Title: Home – Chi siamo
 * Slug: wero-theme/home-chi-siamo
 * Categories: wero-home
 * Description: Titolo e testo, loghi dei founder con scheda, fascia dei collaboratori e foto degli eventi.
 *
 * @package WeroTheme
 */

$wero_img = get_theme_file_uri( 'assets/images/patterns/' );
?>
<!-- wp:group {"tagName": "section", "anchor": "chi", "align": "full", "className": "wero-section", "style": {"spacing": {"padding": {"top": "var:preset|spacing|section", "bottom": "var:preset|spacing|section"}}}, "layout": {"type": "constrained"}} -->
<section id="chi" class="wp-block-group alignfull wero-section" style="padding-top:var(--wp--preset--spacing--section);padding-bottom:var(--wp--preset--spacing--section)"><!-- wp:columns {"verticalAlignment":"bottom","className":"wero-split","style":{"spacing":{"blockGap":{"left":"clamp(24px, 5vw, 80px)","top":"24px"}}}} -->
<div class="wp-block-columns are-vertically-aligned-bottom wero-split"><!-- wp:column {"verticalAlignment":"bottom","width":"52%"} -->
<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:52%"><!-- wp:heading {"className": "is-style-heading-gradient"} -->
<h2 class="wp-block-heading is-style-heading-gradient">Competenze diverse.<br>Un'intelligenza condivisa.</h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"bottom","width":"48%"} -->
<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:48%"><!-- wp:paragraph {"fontSize": "lead"} -->
<p class="has-lead-font-size">WeRo nasce dall'incontro tra ricerca, business, comunicazione e creatività. Un ecosistema di partner che unisce competenze complementari per esplorare le potenzialità della robotica, interpretarne l'evoluzione e costruire nuove connessioni tra persone e tecnologia.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"className":"wero-founders","style":{"spacing":{"blockGap":"clamp(16px, 2vw, 28px)","margin":{"top":"var:preset|spacing|head"},"padding":{"top":"clamp(28px, calc(6vw - 20px), 68px)"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
<div class="wp-block-group wero-founders" style="margin-top:var(--wp--preset--spacing--head);padding-top:clamp(28px, calc(6vw - 20px), 68px)"><!-- wp:paragraph {"className": "is-style-paragraph-pill"} -->
<p class="is-style-paragraph-pill">Founder</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className": "wero-founders__lead", "align": "center"} -->
<p class="has-text-align-center wero-founders__lead">WeRo nasce dall’incontro tra Omnicom Advertising e 6.14.</p>
<!-- /wp:paragraph -->

<!-- wp:wero/partners {"group":"founder"} /--></div>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"wero-theme/fascia-collaboratori"} /-->

<!-- wp:pattern {"slug":"wero-theme/foto-eventi"} /--></section>
<!-- /wp:group -->
