<?php
/**
 * Title: Progetto – Podcast (contenuto di partenza)
 * Slug: wero-theme/progetto-podcast
 * Categories: wero-progetti
 * Description: Note dell’episodio, trascrizione apribile e ospiti a lato. Copertina, player e piattaforme si compilano dai campi.
 * Block Types: core/post-content
 * Post Types: progetto
 * Viewport Width: 1280
 *
 * @package WeroTheme
 */

$wero_img = get_theme_file_uri( 'assets/images/patterns/' );
?>
<!-- wp:columns {"className":"wero-notes","style":{"spacing":{"blockGap":{"left":"clamp(28px, 5vw, 80px)","top":"clamp(28px, 4vw, 48px)"}}}} -->
<div class="wp-block-columns wero-notes"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"className":"wero-prose-h","fontSize":"large"} -->
<h2 class="wp-block-heading wero-prose-h has-large-font-size">In questo episodio</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>WeRo ha accompagnato il progetto dalla strategia al racconto: un lavoro condiviso tra ricerca, comunicazione e creatività per rendere comprensibile a tutti una tecnologia complessa.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Da qui è nato un sistema di identità e di contenuti che mette al centro le persone e il rapporto tra uomo e macchina, invece della sola tecnica.</p>
<!-- /wp:paragraph -->

<!-- wp:details {"className":"wero-transcript"} -->
<details class="wp-block-details wero-transcript"><summary>Leggi la trascrizione</summary><!-- wp:paragraph -->
<p><strong>Voce 1:</strong> Testo della trascrizione dell'episodio.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Voce 2:</strong> Risposta dell'ospite.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"320px","className":"wero-notes__aside"} -->
<div class="wp-block-column wero-notes__aside" style="flex-basis:320px"><!-- wp:paragraph {"className":"wero-side-h","fontSize":"small"} -->
<p class="wero-side-h has-small-font-size">Ospiti</p>
<!-- /wp:paragraph -->

<!-- wp:wero/people /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
