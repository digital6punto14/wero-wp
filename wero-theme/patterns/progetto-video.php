<?php
/**
 * Title: Progetto – Video (contenuto di partenza)
 * Slug: wero-theme/progetto-video
 * Categories: wero-progetti
 * Description: Testo del video con capitoli e persone a lato. Il player si compila dal campo “Link del media”.
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
<div class="wp-block-column"><!-- wp:paragraph -->
<p>WeRo ha accompagnato il progetto dalla strategia al racconto: un lavoro condiviso tra ricerca, comunicazione e creatività per rendere comprensibile a tutti una tecnologia complessa.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Il punto di partenza è stato l’ascolto: interviste con il team di ricerca, analisi del linguaggio usato finora e dei pubblici a cui il progetto vuole parlare.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"320px","className":"wero-notes__aside"} -->
<div class="wp-block-column wero-notes__aside" style="flex-basis:320px"><!-- wp:paragraph {"className":"wero-side-h","fontSize":"small"} -->
<p class="wero-side-h has-small-font-size">Capitoli</p>
<!-- /wp:paragraph -->

<!-- wp:wero/chapters -->
<!-- wp:wero/chapter {"time":"00:00","seconds":0} -->
<p class="wp-block-wero-chapter">Apertura</p>
<!-- /wp:wero/chapter -->

<!-- wp:wero/chapter {"time":"01:35","seconds":95} -->
<p class="wp-block-wero-chapter">Perché portare i robot fuori dai laboratori</p>
<!-- /wp:wero/chapter -->

<!-- wp:wero/chapter {"time":"06:52","seconds":412} -->
<p class="wp-block-wero-chapter">Il dialogo con il pubblico</p>
<!-- /wp:wero/chapter -->

<!-- wp:wero/chapter {"time":"13:00","seconds":780} -->
<p class="wp-block-wero-chapter">Cosa resta dopo l’evento</p>
<!-- /wp:wero/chapter -->
<!-- /wp:wero/chapters -->

<!-- wp:paragraph {"className":"wero-side-h","fontSize":"small","style":{"spacing":{"margin":{"top":"28px"}}}} -->
<p class="wero-side-h has-small-font-size" style="margin-top:28px">Intervengono</p>
<!-- /wp:paragraph -->

<!-- wp:wero/people /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
