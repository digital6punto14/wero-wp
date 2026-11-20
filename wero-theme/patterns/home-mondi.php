<?php
/**
 * Title: Home – I tre mondi
 * Slug: wero-theme/home-mondi
 * Categories: wero-home
 * Description: Titolo, testo e tre card dei mondi (gruppo “Mondi”): immagine, titolo, testo e pulsante “Vedi i progetti” che filtra la sfera. Tutto si modifica qui; il link del pulsante è #progetti?mondo=slug-del-mondo.
 *
 * @package WeroTheme
 */

$wero_img = get_theme_file_uri( 'assets/images/patterns/' );
?>
<!-- wp:group {"tagName": "section", "anchor": "mondi", "align": "full", "className": "wero-section", "style": {"spacing": {"padding": {"top": "var:preset|spacing|section", "bottom": "var:preset|spacing|section"}}}, "layout": {"type": "constrained"}} -->
<section id="mondi" class="wp-block-group alignfull wero-section" style="padding-top:var(--wp--preset--spacing--section);padding-bottom:var(--wp--preset--spacing--section)"><!-- wp:columns {"verticalAlignment":"bottom","className":"wero-split","style":{"spacing":{"blockGap":{"left":"clamp(24px, 5vw, 80px)","top":"24px"}}}} -->
<div class="wp-block-columns are-vertically-aligned-bottom wero-split"><!-- wp:column {"verticalAlignment":"bottom","width":"52%"} -->
<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:52%"><!-- wp:heading {"className": "is-style-heading-gradient"} -->
<h2 class="wp-block-heading is-style-heading-gradient">Different minds.<br>Shared impact.</h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"bottom","width":"48%"} -->
<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:48%"><!-- wp:paragraph {"fontSize": "lead", "style": {"typography": {"fontWeight": "600"}}} -->
<p class="has-lead-font-size" style="font-weight:600">Prospettive diverse per un impatto condiviso</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize": "lead"} -->
<p class="has-lead-font-size">La collaborazione tra ricerca, business e creatività ci permette di esplorare la robotica da prospettive diverse. Dall'analisi delle trasformazioni tecnologiche alla creazione di occasioni di confronto, fino allo sviluppo di nuovi linguaggi e identità.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"metadata": {"name": "Card dei mondi"}, "className": "is-style-wero-worlds", "layout": {"type": "grid", "columnCount": 3, "minimumColumnWidth": null}, "style": {"spacing": {"margin": {"top": "var:preset|spacing|head"}}}} -->
<div class="wp-block-group is-style-wero-worlds" style="margin-top:var(--wp--preset--spacing--head)"><!-- wp:group {"metadata":{"name":"Mondo: Robotics Intelligence"},"className":"wero-world","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group wero-world"><!-- wp:image {"sizeSlug": "large", "linkDestination": "none", "className": "wero-world__media", "aspectRatio": "4/3", "scale": "cover"} -->
<figure class="wp-block-image size-large wero-world__media"><img src="<?php echo esc_url( $wero_img . 'showreel-poster.webp' ); ?>" alt="Primo piano di un robot con gli occhi a led" style="aspect-ratio:4/3;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Robotics Intelligence</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Ricerca, report e analisi per leggere le trasformazioni della robotica e la percezione delle persone.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-button-line"} -->
<div class="wp-block-button is-style-button-line"><a class="wp-block-button__link wp-element-button" href="#progetti?mondo=robotics-intelligence">Vedi i progetti</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Mondo: Conversation & Events"},"className":"wero-world","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group wero-world"><!-- wp:image {"sizeSlug": "large", "linkDestination": "none", "className": "wero-world__media", "aspectRatio": "4/3", "scale": "cover"} -->
<figure class="wp-block-image size-large wero-world__media"><img src="<?php echo esc_url( $wero_img . 'voce-space.webp' ); ?>" alt="Copertina del talk “When robots enter our space”" style="aspect-ratio:4/3;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Conversation &amp; Events</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Eventi, talk e occasioni di confronto che mettono in dialogo ricerca, aziende e pubblico.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-button-line"} -->
<div class="wp-block-button is-style-button-line"><a class="wp-block-button__link wp-element-button" href="#progetti?mondo=conversation-events">Vedi i progetti</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Mondo: Language & Design"},"className":"wero-world","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group wero-world"><!-- wp:image {"sizeSlug": "large", "linkDestination": "none", "className": "wero-world__media", "aspectRatio": "4/3", "scale": "cover"} -->
<figure class="wp-block-image size-large wero-world__media"><img src="<?php echo esc_url( $wero_img . 'gb-human-side.webp' ); ?>" alt="Visual “The human side of robotics” per Generative Bionics" style="aspect-ratio:4/3;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Language &amp; Design</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Nuovi linguaggi, identità e storytelling per dare voce ai robot e ai progetti di ricerca.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-button-line"} -->
<div class="wp-block-button is-style-button-line"><a class="wp-block-button__link wp-element-button" href="#progetti?mondo=language-design">Vedi i progetti</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
