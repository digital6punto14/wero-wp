<?php
/**
 * Title: Home – Report
 * Slug: wero-theme/home-report
 * Categories: wero-home
 * Description: Titolo, testo e timeline dei report (dal tipo di contenuto Report).
 *
 * @package WeroTheme
 */

$wero_img = get_theme_file_uri( 'assets/images/patterns/' );
?>
<!-- wp:group {"tagName": "section", "anchor": "report", "align": "full", "className": "wero-section", "style": {"spacing": {"padding": {"top": "var:preset|spacing|section", "bottom": "var:preset|spacing|section"}}}, "layout": {"type": "constrained"}} -->
<section id="report" class="wp-block-group alignfull wero-section" style="padding-top:var(--wp--preset--spacing--section);padding-bottom:var(--wp--preset--spacing--section)"><!-- wp:columns {"verticalAlignment":"bottom","className":"wero-split","style":{"spacing":{"blockGap":{"left":"clamp(24px, 5vw, 80px)","top":"24px"}}}} -->
<div class="wp-block-columns are-vertically-aligned-bottom wero-split"><!-- wp:column {"verticalAlignment":"bottom","width":"52%"} -->
<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:52%"><!-- wp:heading {"className": "is-style-heading-gradient"} -->
<h2 class="wp-block-heading is-style-heading-gradient">Report</h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"bottom","width":"48%"} -->
<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:48%"><!-- wp:paragraph {"fontSize": "lead"} -->
<p class="has-lead-font-size">Le nostre ricerche su robotica e percezione delle persone, da leggere e scaricare liberamente.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:wero/report-timeline {"align":"full"} /--></section>
<!-- /wp:group -->
