<?php
/**
 * Title: Home – Perché lo facciamo
 * Slug: wero-theme/home-perche
 * Categories: wero-home
 * Description: Pannello scuro con titolo, foto e testo del manifesto.
 *
 * @package WeroTheme
 */

$wero_img = get_theme_file_uri( 'assets/images/patterns/' );
?>
<!-- wp:group {"tagName": "section", "anchor": "perche", "align": "full", "className": "wero-section is-style-group-dark wero-band", "style": {"spacing": {"padding": {"top": "var:preset|spacing|section", "bottom": "var:preset|spacing|section"}}}, "layout": {"type": "constrained"}} -->
<section id="perche" class="wp-block-group alignfull wero-section is-style-group-dark wero-band has-white-color has-ink-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--section);padding-bottom:var(--wp--preset--spacing--section)"><!-- wp:columns {"className":"wero-why","style":{"spacing":{"blockGap":{"left":"clamp(28px, 6vw, 96px)","top":"clamp(28px, 4vw, 48px)"}}}} -->
<div class="wp-block-columns wero-why"><!-- wp:column {"width":"45%","style":{"spacing":{"blockGap":"clamp(28px, 4vw, 48px)"}}} -->
<div class="wp-block-column" style="flex-basis:45%"><!-- wp:heading {"className": "is-style-heading-gradient"} -->
<h2 class="wp-block-heading is-style-heading-gradient">Perché lo<br>facciamo?</h2>
<!-- /wp:heading -->

<!-- wp:image {"sizeSlug": "full", "linkDestination": "none", "aspectRatio": "4/3", "scale": "cover", "className": "wero-why__photo", "style": {"border": {"radius": "24px"}}} -->
<figure class="wp-block-image size-full has-custom-border wero-why__photo"><img src="<?php echo esc_url( $wero_img . 'perche-wero.webp' ); ?>" alt="Il team WeRo presenta il primo Robot Communication Studio" style="border-radius:24px;aspect-ratio:4/3;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"bottom","width":"55%","className":"wero-why__body"} -->
<div class="wp-block-column is-vertically-aligned-bottom wero-why__body" style="flex-basis:55%"><!-- wp:paragraph {"className": "wero-why__claim", "fontSize": "x-large", "style": {"typography": {"fontWeight": "500", "lineHeight": "1.2", "letterSpacing": "-0.02em"}}} -->
<p class="wero-why__claim has-x-large-font-size" style="font-weight:500;line-height:1.2;letter-spacing:-0.02em">Vogliamo cambiare la percezione dei robot: da semplici macchine a <mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color wero-grad-text">“tools for human beings”</mark>.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Siamo nati per creare un nuovo concetto di comunicazione tra persone, macchine e brand, per dare vita a nuove interconnessioni e nuove comunità.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Ci impegniamo per portare la robotica anche a chi oggi la vede solo come un complesso di codici e bulloni, dando sempre più importanza al linguaggio e alla relazione H2M: una connessione emotiva che è la chiave per progettare i robot e le loro interfacce.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
