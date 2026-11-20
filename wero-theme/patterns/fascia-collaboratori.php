<?php
/**
 * Title: Fascia collaboratori
 * Slug: wero-theme/fascia-collaboratori
 * Categories: wero-home, wero-comuni
 * Description: Fascia scura con la riga curva dei loghi dei collaboratori (dai Partner) e il riquadro “Diventa partner”.
 *
 * @package WeroTheme
 */

$wero_img = get_theme_file_uri( 'assets/images/patterns/' );
?>
<!-- wp:group {"align":"full","className":"is-style-group-dark wero-band","style":{"spacing":{"margin":{"top":"var:preset|spacing|block"},"padding":{"top":"var:preset|spacing|block","bottom":"var:preset|spacing|medium"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-group-dark wero-band" style="margin-top:var(--wp--preset--spacing--block);padding-top:var(--wp--preset--spacing--block);padding-bottom:var(--wp--preset--spacing--medium)"><!-- wp:heading {"level":3,"className":"wero-band__title","textColor":"white","style":{"typography":{"fontWeight":"500","letterSpacing":"-0.03em"}}} -->
<h3 class="wp-block-heading wero-band__title has-white-color has-text-color" style="font-weight:500;letter-spacing:-0.03em">In questi anni abbiamo collaborato con</h3>
<!-- /wp:heading -->

<!-- wp:wero/logo-row {"align":"full"} /-->

<!-- wp:template-part {"slug":"cta-partner","tagName":"aside","className":"wero-band__cta"} /--></div>
<!-- /wp:group -->
