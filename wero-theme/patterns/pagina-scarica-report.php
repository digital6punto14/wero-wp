<?php
/**
 * Title: Pagina – Scarica i report
 * Slug: wero-theme/pagina-scarica-report
 * Categories: wero-comuni
 * Keywords: report, download, pdf, modulo
 * Description: Testo introduttivo e pannello “vetro” con il modulo di Contact Form 7 per scaricare i report (scegli il modulo nel blocco). L'elenco dei report si aggiorna da solo.
 *
 * @package WeroTheme
 */

?>
<!-- wp:paragraph {"fontSize":"lead"} -->
<p class="has-lead-font-size">Scegli i report che ti interessano e lasciaci i tuoi dati: dopo l’invio trovi subito i link per scaricarli, e te li mandiamo anche per email.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"is-style-group-glass wero-download-form","style":{"spacing":{"margin":{"top":"var:preset|spacing|medium"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-group-glass wero-download-form" style="margin-top:var(--wp--preset--spacing--medium)">
<?php
/**
 * Blocco del modulo di Contact Form 7: lo fornisce il plugin WeRo Core
 * (modulo scelto in Impostazioni sito › Modulo “Scarica i report”). Senza plugin
 * il pannello resta vuoto e il modulo si inserisce a mano con il blocco di Contact Form 7.
 */
echo apply_filters( 'wero_report_form_block', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup di blocchi generato dal plugin.
?>
</div>
<!-- /wp:group -->

<!-- wp:paragraph {"textColor":"muted","fontSize":"small","style":{"spacing":{"margin":{"top":"var:preset|spacing|small"}}}} -->
<p class="has-muted-color has-text-color has-small-font-size" style="margin-top:var(--wp--preset--spacing--small)">I nuovi report compaiono nell’elenco appena vengono pubblicati.</p>
<!-- /wp:paragraph -->
