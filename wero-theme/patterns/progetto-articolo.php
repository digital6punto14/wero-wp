<?php
/**
 * Title: Progetto – Articolo (contenuto di partenza)
 * Slug: wero-theme/progetto-articolo
 * Categories: wero-progetti
 * Description: Immagine con didascalia, testo, sottotitolo e citazione. La scheda del progetto a lato si compila dai campi.
 * Block Types: core/post-content
 * Post Types: progetto
 * Viewport Width: 1280
 *
 * @package WeroTheme
 */

$wero_img = get_theme_file_uri( 'assets/images/patterns/' );
?>
<!-- wp:image {"sizeSlug": "full", "linkDestination": "none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( $wero_img . 'segnaposto.svg' ); ?>" alt=""/><figcaption class="wp-element-caption">Didascalia dell'immagine.</figcaption></figure>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p>WeRo ha accompagnato il progetto dalla strategia al racconto: un lavoro condiviso tra ricerca, comunicazione e creatività per rendere comprensibile a tutti una tecnologia complessa.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Il punto di partenza è stato l’ascolto: interviste con il team di ricerca, analisi del linguaggio usato finora e dei pubblici a cui il progetto vuole parlare.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"wero-prose-h","fontSize":"large"} -->
<h2 class="wp-block-heading wero-prose-h has-large-font-size">Il risultato</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Da qui è nato un sistema di identità e di contenuti che mette al centro le persone e il rapporto tra uomo e macchina, invece della sola tecnica.</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p>“Una frase chiave del cliente o del team, in evidenza.”</p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->

<!-- wp:paragraph -->
<p>Testo di chiusura del progetto, con i prossimi passi e i riferimenti utili.</p>
<!-- /wp:paragraph -->

<!-- wp:pattern {"slug":"wero-theme/foto-progetto"} /-->
