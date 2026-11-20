<?php
/**
 * Title: Home – Le voci dei progetti
 * Slug: wero-theme/home-voci
 * Categories: wero-home
 * Description: Titolo e card delle voci (gruppo “Voci”): copertina collegata al video (YouTube, Vimeo o file), titolo e descrizione. Il video parte al clic sulla copertina.
 *
 * @package WeroTheme
 */

$wero_img = get_theme_file_uri( 'assets/images/patterns/' );
?>
<!-- wp:group {"tagName": "section", "anchor": "voci", "align": "full", "className": "wero-section", "style": {"spacing": {"padding": {"top": "var:preset|spacing|section", "bottom": "var:preset|spacing|section"}}}, "layout": {"type": "constrained"}} -->
<section id="voci" class="wp-block-group alignfull wero-section" style="padding-top:var(--wp--preset--spacing--section);padding-bottom:var(--wp--preset--spacing--section)"><!-- wp:heading {"className": "is-style-heading-gradient"} -->
<h2 class="wp-block-heading is-style-heading-gradient">Le voci dei progetti</h2>
<!-- /wp:heading -->

<!-- wp:group {"metadata": {"name": "Card delle voci"}, "className": "is-style-wero-voices", "layout": {"type": "grid", "columnCount": 3, "minimumColumnWidth": null}, "style": {"spacing": {"margin": {"top": "var:preset|spacing|head"}}}} -->
<div class="wp-block-group is-style-wero-voices" style="margin-top:var(--wp--preset--spacing--head)"><!-- wp:group {"className":"wero-voice","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group wero-voice"><!-- wp:image {"sizeSlug": "large", "linkDestination": "custom", "className": "wero-voice__cover", "aspectRatio": "16/9", "scale": "cover"} -->
<figure class="wp-block-image size-large wero-voice__cover"><a href="#"><img src="<?php echo esc_url( $wero_img . 'voce-care.webp' ); ?>" alt="Copertina del video “When robotics become care”" style="aspect-ratio:16/9;object-fit:cover"/></a></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"wero-voice__name"} -->
<p class="wero-voice__name">When robotics become care</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"wero-voice__desc"} -->
<p class="wero-voice__desc">Come la robotica può prendersi cura delle persone: assistenza, riabilitazione e tecnologie indossabili che restituiscono autonomia nella vita di tutti i giorni.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"wero-voice","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group wero-voice"><!-- wp:image {"sizeSlug": "large", "linkDestination": "custom", "className": "wero-voice__cover", "aspectRatio": "16/9", "scale": "cover"} -->
<figure class="wp-block-image size-large wero-voice__cover"><a href="#"><img src="<?php echo esc_url( $wero_img . 'voce-work.webp' ); ?>" alt="Copertina del video “When robotics meet the world of work”" style="aspect-ratio:16/9;object-fit:cover"/></a></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"wero-voice__name"} -->
<p class="wero-voice__name">When robotics meet the world of work</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"wero-voice__desc"} -->
<p class="wero-voice__desc">Robot e persone che lavorano fianco a fianco: cosa cambia nelle fabbriche, negli uffici e nelle competenze, e come costruire una collaborazione basata sulla fiducia.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"wero-voice","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group wero-voice"><!-- wp:image {"sizeSlug": "large", "linkDestination": "custom", "className": "wero-voice__cover", "aspectRatio": "16/9", "scale": "cover"} -->
<figure class="wp-block-image size-large wero-voice__cover"><a href="#"><img src="<?php echo esc_url( $wero_img . 'voce-space.webp' ); ?>" alt="Copertina del video “When robots enter our space”" style="aspect-ratio:16/9;object-fit:cover"/></a></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"wero-voice__name"} -->
<p class="wero-voice__name">When robots enter our space</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"wero-voice__desc"} -->
<p class="wero-voice__desc">Quando i robot escono dai laboratori ed entrano in case, negozi e città: come progettare una convivenza chiara, sicura e comprensibile per tutti.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
