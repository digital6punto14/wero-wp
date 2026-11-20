<?php
/**
 * Title: Home – I tre mondi
 * Slug: wero-theme/home-mondi
 * Categories: wero-home
 * Description: Titolo, testo e le card dei tre mondi (dalla tassonomia Mondo).
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

<!-- wp:wero/worlds /--></section>
<!-- /wp:group -->
