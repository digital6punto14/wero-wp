# Riepilogo classi CSS

Come è organizzato l'aspetto del sito: token, classi, file e regole.

**Regola generale:** prima theme.json, poi le varianti di stile dei blocchi, solo alla fine CSS scritto a mano. Il CSS scritto a mano:
- usa sempre i token (`var(--wp--preset--…)`, `var(--wp--custom--…)`), mai colori o misure fisse ripetute;
- si carica solo nelle pagine dove c'è il blocco a cui serve.

---

## 1. Token (theme.json)

### Colori (`--wp--preset--color--{slug}`)
| Slug | Valore | Nome nell'editor | Uso | Variabile originale |
|---|---|---|---|---|
| `paper` | `#f4f2e9` | Carta | Fondo del sito | `--paper` |
| `paper-light` | `#fbfaf5` | Carta chiara | Card, pannelli, campi | `--paper-2` |
| `ink` | `#15121a` | Inchiostro | Testo, fondi scuri | `--ink` |
| `ink-soft` | `#3b3640` | Inchiostro tenue | Testo secondario | `--ink-2` |
| `muted` | `#6c6670` | Grigio | Note, didascalie | `--muted` |
| `pink` | `#ff3f7a` | Rosa | Inizio sfumatura, partner Business | `--pink`, `--biz` |
| `magenta` | `#ff00c7` | Magenta | Accento, focus, fine sfumatura | `--magenta` |
| `research` | `#7b5cff` | Viola ricerca | Partner di Ricerca (riga dei loghi) | `--res` |
| `white` | `#ffffff` | Bianco | Testo su scuro | — |

### Sfumature (`--wp--preset--gradient--{slug}`)
| Slug | Valore | Originale |
|---|---|---|
| `wero` | `linear-gradient(90deg, #ff3f7a 0%, #ff00c7 100%)` | `--grad` |
| `night` | `linear-gradient(160deg, #241b2a 0%, #15121a 70%)` | sfondo dei pannelli scuri |

### Font (`--wp--preset--font-family--{slug}`)
| Slug | Valore | Note |
|---|---|---|
| `space-grotesk` | `"Space Grotesk", system-ui, …` | Ospitato in `assets/fonts/`; peso variabile 300–700; preload del solo sottoinsieme latino |
| `system` | `system-ui, -apple-system, …` | Alternativa scelta da Impostazioni › WeRo › Font |

### Dimensioni del testo (`--wp--preset--font-size--{slug}`)
| Slug | Valore | Uso |
|---|---|---|
| `tiny` | 13px | Bollini, etichette |
| `small` | 14px | Meta, note |
| `medium` | 16px | Pulsanti, interfaccia |
| `body` | 18px | Testo (17px sotto i 560px) |
| `lead` | clamp(18px, 1.5vw, 21px) | Paragrafo introduttivo |
| `large` | clamp(22px, 2.4vw, 30px) | Titoli delle card |
| `x-large` | clamp(24px, 2.4vw, 32px) | H3 |
| `heading-story` | clamp(28px, 3.2vw, 44px) | Titoli dei blocchi racconto |
| `hero` | clamp(32px, 4.4vw, 64px) | Titolo dei progetti |
| `section-small` | clamp(40px, 5vw, 72px) | Titoli di sezione secondari |
| `section` | clamp(40px, 6vw, 88px) | Titoli di sezione (H2) |
| `display` | clamp(40px, 6.4vw, 96px) | Titolo della home (H1) |

### Spazi (`--wp--preset--spacing--{slug}`)
| Slug | Valore | Originale |
|---|---|---|
| `xx-small` | 6px | — |
| `x-small` | 12px | — |
| `small` | 18px | — |
| `medium` | clamp(24px, 3.5vw, 44px) | `--sp-inner` (dentro i pannelli) |
| `head` | clamp(32px, 4vw, 56px) | `--sp-head` (dal titolo al contenuto) |
| `block` | clamp(48px, 6vw, 80px) | `--sp-block` (tra due blocchi) |
| `section` | clamp(56px, 6vw, 88px) | `--sp-section` (sopra e sotto ogni sezione) |

### Raggi, ombre, layout
| Tipo | Slug | Valore | Originale |
|---|---|---|---|
| Raggio | `small` | 14px | `--radius-s` |
| | `medium` | 22px | `--radius` |
| | `large` | 28px | — |
| | `x-large` | 40px | fasce scure |
| | `pill` | 999px | pillole |
| Ombra | `glow` | `0 10px 30px -10px rgba(255,0,199,.6)` | alone dei pulsanti |
| | `card` | `0 20px 60px -30px rgba(21,18,26,.35)` | card |
| | `nav` | `0 10px 40px -20px rgba(21,18,26,.35)` | header a pillola |
| Layout | `contentSize` / `wideSize` | 1184px | `--wrap` 1280px meno i margini laterali |

## 2. Custom properties (`settings.custom`)

theme.json le trasforma in `--wp--custom--{nome-in-kebab}`.

| Proprietà | Valore | Uso | Originale |
|---|---|---|---|
| `--wp--custom--ease` | `cubic-bezier(.2, .8, .2, 1)` | Curva di tutte le transizioni | `--ease` |
| `--wp--custom--gutter` | `clamp(16px, 4vw, 48px)` | Margine laterale delle sezioni | `--gutter` |
| `--wp--custom--nav-height` | `76px` | Altezza dell'header, `scroll-padding-top` | `--nav-h` |
| `--wp--custom--line` | `rgba(21, 18, 26, .12)` | Bordi sottili, separatori | `--line` |
| `--wp--custom--glass` | `rgba(251, 250, 245, .9)` | Fondo “vetro” di header e pannelli | `--glass-strong` |
| `--wp--custom--wrap` | `1280px` | Larghezza massima con i margini | `--wrap` |

Variabile locale: `--wero-edge` (in `core--query.css`), il margine del carosello “Altri progetti” su mobile, calcolato dal gutter.

**Colori dalle impostazioni.** Impostazioni › WeRo › Aspetto scrive nel theme.json del tema (filtro `wp_theme_json_data_theme`). Le variabili restano le stesse e cambia solo il loro valore: il CSS non va toccato.

## 3. Classi personalizzate

Tutte con prefisso `wero-` e scrittura BEM:
- `wero-blocco` per il blocco;
- `wero-blocco__parte` per una sua parte;
- `wero-blocco--variante` per una variante.

Gli stati usano `is-…` (`is-open`, `is-active`, `is-playing`).

### Tema (classi da assegnare nel pannello Avanzate › Classi CSS aggiuntive)
| Classe | Dove si usa | File CSS | Cosa fa |
|---|---|---|---|
| `wero-site-header` | Template, parte header | site.css, core--navigation.css | Header fisso a pillola |
| `wero-header`, `wero-header__nav`, `wero-header__cta` | Parte header | core--navigation.css | Pillola, menu, pulsante “Diventa partner” |
| `wero-main--home` | front-page.html | core--group.css | Contenitore della home |
| `wero-section` | Gruppi delle sezioni della home | site.css, core--group.css | Spazio sopra e sotto, ancora con l'offset dell'header, comparsa allo scroll (N1) |
| `wero-split` | Colonne (Mondi, Chi siamo, Report) | core--columns.css | Titolo a sinistra, testo a destra |
| `wero-why`, `wero-why__photo`, `wero-why__claim`, `wero-why__body` | Pattern “Perché” | core--columns.css | Sezione scura con foto e claim |
| `wero-band`, `wero-band__title`, `wero-band__cta` | Fascia collaboratori, parte cta-partner | core--group.css | Fascia scura arrotondata |
| `wero-founders`, `wero-founders__lead` | Chi siamo | core--group.css | Riquadro dei founder |
| `wero-contact` | Home, partner | core--columns.css | Colonne testo + modulo |
| `wero-grad-text` | Testo con sfumatura in un paragrafo | site.css | Sfumatura di una parola o frase |
| `wero-play` | Pulsante dell'hero | core--button.css | Pulsante con icona “play” |
| `wero-round` | Frecce di gallerie e timeline | site.css | Pulsante tondo 42px |
| `wero-article-top`, `wero-article-head`, `wero-article-title`, `wero-article-lead` | Testata dei progetti | core--post-excerpt.css | Testata, titolo, sottotitolo |
| `wero-article-content`, `wero-article-aside` | Template dei progetti | core--columns.css | Colonna di testo + colonna laterale |
| `wero-prose`, `wero-prose-h` | Contenuto degli articoli | core--post-content.css | Larghezza di lettura, titoli interni |
| `wero-story`, `wero-story__block`, `wero-story__text` | Articolo complesso | core--post-content.css | Blocchi racconto testo + immagine |
| `wero-side-h` | Video e podcast | core--columns.css | Titoletto della colonna laterale |
| `wero-notes` | Video e podcast | core--columns.css | Note dell'episodio |
| `wero-episode` | Podcast | core--columns.css | Copertina + player |
| `wero-media-square` | Articolo complesso | core--video.css | Video quadrato |
| `wero-related`, `wero-more`, `wero-scroll-mobile` | Parte progetto-chiusura | core--query.css, core--group.css | “Altri progetti”: griglia su desktop, carosello su mobile |
| `wero-ph`, `wero-ph--sfumatura`, `--scuro`, `--contorno`, `wero-ph-figure` | Generate dal plugin | core--query.css | Copertina di riserva col colore del mondo |
| `wero-meta--card`, `wero-meta__info` | Card dei progetti | core--query.css | Bollino del mondo, tipo e anno nella card |
| `wero-footer`, `wero-footer__logos`, `wero-footer__logo`, `wero-footer__h`, `wero-footer__bottom` | Pattern footer | core--group.css | Footer scuro |
| `wero-form-row`, `wero-chips`, `wero-chips__legend` | Dentro il modulo CF7 | contact-form-7--….css | Due campi affiancati, scelte a “chip” |
| `wero-chips--reports` | Modulo “Scarica i report” | contact-form-7--….css | Report da scegliere a riga intera, con pallino di selezione |
| `wero-download-form` | Pattern “Pagina – Scarica i report” | — (usa lo stile Vetro) | Pannello del modulo |
| `wero-downloads`, `__title`, `__list`, `__name`, `__link`, `__note` | Creati da report-download.js dopo l’invio | contact-form-7--….css | Riquadro con i link ai PDF scelti |
| `wero-project-photos` | Pattern “Progetto – Foto a scorrimento” | core--gallery.css (regole `:not(.alignfull)`) | Galleria a scorrimento dentro il testo di un progetto; vuota non compare |
| `wero-scroller-nav` | Creata da gallery-scroller.js | core--gallery.css | Frecce della galleria a scorrimento |

### Classi del body e della pagina (aggiunte dal codice)
| Classe | Chi la mette | Effetto |
|---|---|---|
| `wero-reveal` (body) | Plugin, se “Comparsa allo scroll” è attiva | Abilita N1 |
| `intro-on` / `intro-skip` (html) | Plugin, script in `<head>` prima del primo disegno | Mostra o salta l'intro |
| `bg-glow`, `dot-trail` | background.js, cursor.js | Canvas dello sfondo a pixel e del cursore |
| `wero-legacy` (html) | Controllo in `<head>` del tema (functions.php), solo nei browser senza `:where()` | Attiva `assets/css/legacy.css`: versione semplificata di pulsanti, link, stili dei blocchi e larghezze |
| `is-empty` (galleria) | gallery-scroller.js | Nasconde la galleria “A scorrimento” senza foto anche dove manca `:has()` |

### Blocchi del plugin (classi interne, non da usare a mano)
| Blocco | Classe radice | Parti principali |
|---|---|---|
| `wero/intro` | `wero-logo` | `__sub`, stati `is-leaving`, `is-gone` |
| `wero/hero-video` | `wero-hero` | `__media`, `__poster`, `__scrim`, `__inner`, `__content`, `__title`, `__lead`, `__actions`, `__note`, `__player`, `__bigplay`, `__close`; stato `is-playing` |
| `wero/projects-explorer` | `wero-explorer` | sfera, filtri, pannello, lista, griglia; stati `is-active`, `is-grid`, `is-open`, `is-on`, `is-swap` |
| `wero/report-timeline` | `wero-rtl-block` | stati `is-past`, `is-static` |
| `wero/partners` | `wero-partners` | `__list`, `wero-partner`, `wero-partner__logo`, `wero-pcard` (`__name`, `__desc`, `__link`); `is-founder`, `is-open` |
| `wero/logo-row` | `wero-logo-row` | `wero-tilted`, `wero-legend`, `wero-dot--business`, `wero-dot--ricerca` |
| `wero/project-meta` | `wero-meta` | `wero-meta__back`, `wero-meta__info`, `wero-tag`, `wero-tag--scuro`, `wero-tag--contorno`, `is-secondary` |
| `wero/project-facts` | `wero-facts` | — |
| `wero/project-media` | `wero-player` | `--video`, `--audio`, `__poster`, `__play`, `__load`, `__note`; `is-playing` |
| `wero/chapters`, `wero/chapter` | `wero-chapters` | `wero-chapter` |
| `wero/people` | `wero-people-block` | `wero-people`, `wero-people__h`, `wero-people__avatar` |
| `wero/podcast-cover` | `wero-cover` | `__img`, `__mark` |
| `wero/listen-links` | `wero-listen` | `wero-chip` |

## 4. Stili dei blocchi e varianti

### Varianti di stile del tema (`wero-theme/styles/blocks/*.json`)
Compaiono nel pannello **Stili** del blocco. Classe generata: `is-style-{slug}`.

| Slug | Nome | Blocchi | Aspetto |
|---|---|---|---|
| `button-chip` | Chip | Pulsante | Pillola piccola chiara (filtri, link secondari) |
| `button-dark` | Scuro | Pulsante | Fondo inchiostro, testo bianco |
| `button-ghost` | Contorno chiaro | Pulsante | Bordo bianco su fondo scuro |
| `button-line` | Contorno | Pulsante | Bordo inchiostro su fondo chiaro |
| `gallery-scroller` | A scorrimento | Galleria | Riga orizzontale con frecce (script dedicato) |
| `group-card` | Card chiara | Gruppo, Colonna | Fondo carta chiara, raggio medio, ombra card |
| `group-dark` | Pannello scuro | Gruppo, Colonne | Fondo `night`, testo bianco, raggio grande |
| `group-glass` | Vetro | Gruppo, Colonne | Fondo vetro con sfocatura |
| `heading-gradient` | Sfumatura | Titolo, Paragrafo, Titolo del contenuto | Testo con sfumatura `wero` |
| `image-rounded` | Arrotondata grande | Immagine, Video, Copertina | Raggio grande |
| `paragraph-pill` | Bollino | Paragrafo | Etichetta a pillola (es. “Language & Design”) |
| `query-cards` | Card progetti | Query | Griglia di card dei progetti |

Stile del **pulsante predefinito** (pillola con sfumatura e alone): in theme.json, `styles.elements.button`.

### Stili di Gruppo del plugin (`wero-core/assets/styles/`)
| Classe | Nome | CSS/JS | Cosa fa |
|---|---|---|---|
| `is-style-wero-worlds` | Mondi | wero-worlds.css / .js | Card dei mondi in griglia; su mobile a scorrimento con puntini (`wero-worlds-dots`); il pulsante filtra la sfera |

### Varianti dei blocchi del plugin
- `wero/project-meta`, attributo `variant`: `card` (bollini) o `bar` (barra della testata con “Torna alla home” / “Tutti i progetti”).
- `wero/project-media`, attributo `mode`: video o audio, deciso dal link.

Non ci sono varianti di blocco registrate in JS (`registerBlockVariation`).

## 5. Mappa classi: HTML originale → WordPress

| Originale | WordPress |
|---|---|
| `.wrap` | Layout “contenuto” di theme.json (1184px) + padding `--wp--custom--gutter` |
| `.nav`, `.nav__links` | Parte header: Gruppo `.wero-header` + blocco Navigazione core (`.wero-header__nav`) |
| `.btn--grad`, `.btn--ink`, `.btn--line`, `.btn--ghost`, `.btn--block` | Blocco Pulsante: stile predefinito (sfumatura), `is-style-button-dark`, `-line`, `-ghost`; larghezza piena dal pannello del blocco |
| `.chip`, `.pill` | `is-style-button-chip`, `is-style-paragraph-pill` |
| `.grad-text` | `is-style-heading-gradient` (blocco intero) o `.wero-grad-text` (parte di testo) |
| `.h2`, `.lead` | Dimensioni `section` / `lead` di theme.json (`has-section-font-size`, `has-lead-font-size`) |
| `.section`, `.section--dark`, `.section--projects` | Gruppo `.wero-section`, + `is-style-group-dark` per le sezioni scure |
| `.intro`, `.wero-logo` | Blocco `wero/intro` |
| `.hero`, `.hero__content` | Blocco `wero/hero-video` (`.wero-hero`) |
| `.worlds`, `.world`, `.worlds-dots` | Gruppo `is-style-wero-worlds` con Gruppi-card |
| `.projects`, `.sphere`, `.pviews`, `.pinfo`, `.plist`, `.pgrid`, `.filters`, `.pager` | Blocco `wero/projects-explorer` (`.wero-explorer`) |
| `.why`, `.why__side`, `.why__body` | Colonne `.wero-why` |
| `.rtl`, `.report`, `.report__cover` | Blocco `wero/report-timeline` (`.wero-rtl-block`) |
| `.voices`, `.voice` | Eliminate: le Voci sono diventate progetti video (D53) |
| `.partners`, `.pgroup`, `.partner`, `.pcard` | Blocco `wero/partners` |
| `.collab`, `.tilted`, `.legend` | Blocco `wero/logo-row` |
| `.split` | Colonne `.wero-split` |
| `.cta-band` | Parte `cta-partner` / Gruppo `.wero-band` |
| `.form`, `.field`, `.check` | Modulo CF7 + `.wero-form-row`, `.wero-chips` |
| `.footer`, `.footer__top`, `.footer__cols`, `.footer__logos`, `.footer__bottom` | Pattern footer: `.wero-footer`, `__logos`, `__bottom` |
| `.article__bar`, `.tag-world`, `.tag-mini` | Blocco `wero/project-meta` (variante `bar`, `.wero-tag`) |
| `.article__head` | Parte `progetto-testata`: `.wero-article-head`, Titolo del contenuto, Riassunto (`.wero-article-lead`) |
| `.prose` | Gruppo `.wero-prose` / Contenuto del post |
| `.story`, `.story__block`, `.story__text` | Pattern “Blocco racconto”: `.wero-story`, `__block`, `__text` |
| `.facts` | Blocco `wero/project-facts` |
| `.player`, `.audio`, `.video-info` | Blocco `wero/project-media` |
| `.chapters` | Blocchi `wero/chapters` + `wero/chapter` |
| `.people`, `.avatar` | Blocco `wero/people` |
| `.cover` (podcast) | Blocco `wero/podcast-cover` |
| `.listen` | Blocco `wero/listen-links` |
| `.episode`, `.notes`, `.side-h` | Colonne `.wero-episode`, `.wero-notes`, `.wero-side-h` |
| `.transcript` | Blocco Dettagli core (stile in `core--details.css`) |
| `.photos`, `.photos__nav` | Galleria `is-style-gallery-scroller` + `.wero-scroller-nav` |
| `.more`, `.more__list` | Parte `progetto-chiusura`: `.wero-related` (Query `is-style-query-cards`) |
| `.round` | `.wero-round` |
| `.ph` | `.wero-ph` (copertina di riserva) |
| `.media` | `is-style-image-rounded`, `.wero-media-square` |
| `.contact` | Colonne `.wero-contact` |
| `.bg-glow`, `.dot-trail` | Uguali, create da `background.js` e `cursor.js` |
| `.sr-only` | `.screen-reader-text` di WordPress |
| `.demo-switch` | Eliminato (selettore “prima / dopo” della bozza, D19) |

## 6. File CSS e JS: peso e caricamento

Pesi misurati sui file di questa versione: originale / compresso gzip.

### Tema
| File | Peso | gzip | Quando si carica |
|---|---|---|---|
| `assets/css/site.css` | 3,9 KB | 1,7 KB | Tutte le pagine (e nell'editor) |
| `assets/css/legacy.css` | 3,3 KB | 1,1 KB | **Solo** nei browser senza `:where()` (Safari < 14, Chrome < 88), caricato dal controllo in `<head>` |
| `assets/css/view-transitions.css` | 0,7 KB | 0,4 KB | Tutte le pagine, se “Transizioni tra pagine” è attiva |
| `blocks/core--navigation.css` | 4,8 KB | 1,4 KB | Con il blocco Navigazione |
| `blocks/core--query.css` | 3,6 KB | 1,1 KB | Con il blocco Query |
| `blocks/contact-form-7--contact-form-selector.css` | 3,5 KB | 1,2 KB | Con il modulo CF7 |
| `blocks/core--group.css` | 2,7 KB | 1,0 KB | Con il blocco Gruppo |
| `blocks/core--gallery.css` | 1,9 KB | 0,8 KB | Con il blocco Galleria |
| `blocks/core--columns.css` | 1,8 KB | 0,7 KB | Con il blocco Colonne |
| `blocks/core--button.css` | 1,2 KB | 0,5 KB | Con il blocco Pulsante |
| `blocks/core--post-content.css` | 0,7 KB | 0,4 KB | Con Contenuto del post |
| `blocks/core--post-excerpt.css` | 0,5 KB | 0,3 KB | Con Riassunto |
| `blocks/yoast-seo--breadcrumbs.css` | 0,4 KB | 0,3 KB | Con il blocco Breadcrumb di Yoast |
| `blocks/core--details.css` | 0,3 KB | 0,2 KB | Con il blocco Dettagli |
| `blocks/core--video.css` | 0,2 KB | 0,2 KB | Con il blocco Video |
| `assets/js/background.js` | 8,8 KB | 3,6 KB | Tutte le pagine, `defer`, se “Sfondo a pixel” è attivo |
| `assets/js/cursor.js` | 5,2 KB | 2,2 KB | Tutte le pagine, `defer`, se “Cursore” è attivo (si attiva solo con il mouse) |
| `assets/js/gallery-scroller.js` | 2,3 KB | 1,1 KB | Solo con una Galleria “A scorrimento” |
| `assets/js/nav.js` | 2,0 KB | 0,9 KB | Con il blocco Navigazione (voce attiva allo scroll) |

### Plugin
| File | Peso | gzip | Quando si carica |
|---|---|---|---|
| `projects-explorer` style / view.js | 9,6 / 26,0 KB | 2,2 / 10,5 KB | Solo con la sfera (home) |
| `report-timeline` style / view.js | 8,7 / 2,5 KB | 2,1 / 1,0 KB | Solo con la timeline (home) |
| `logo-row` style / view.js | 1,1 / 4,6 KB | 0,5 / 2,2 KB | Solo con la riga dei loghi |
| `intro` style / view.js | 3,2 / 0,6 KB | 1,2 / 0,3 KB | Solo con l'intro (home) |
| `hero-video` style / view.js | 2,6 / 1,2 KB | 1,0 / 0,6 KB | Solo con l'hero |
| `partners` style / view.js | 2,5 / 0,6 KB | 1,0 / 0,3 KB | Solo con i loghi dei partner |
| `project-media` style / view.js | 1,7 / 1,6 KB | 0,7 / 0,7 KB | Solo nei progetti con video/audio |
| `project-meta` style | 1,8 KB | 0,6 KB | Testata e card dei progetti |
| altri 6 blocchi (style) | 0,2–0,8 KB ciascuno | 0,2–0,5 KB | Solo dove c'è il blocco |
| `wero-worlds` css / js | 3,1 / 1,2 KB | 1,0 / 0,6 KB | Solo con un Gruppo “Mondi” |
| `assets/forms/report-download.js` | 2,2 KB | 0,9 KB | Solo nella pagina con il modulo “Scarica i report” |
| `assets/editor/bindings.js` | 0,8 KB | 0,5 KB | Solo nell'editor |

**Come si caricano**
- **CSS per blocco:** `wp_enqueue_block_style()`, quindi WordPress li stampa solo se il blocco è nella pagina. Quelli piccoli li mette in linea nell'`<head>`, così si evitano richieste in più.
- **JS:** tutti senza librerie, `defer` e nel footer. Rispettano `prefers-reduced-motion`.
- **CF7:** il suo CSS e JS si caricano solo nelle pagine con il modulo.
- **Totale indicativo della home** (la pagina più pesante): circa 25 KB gzip di JS del sito, di cui 10,5 KB della sfera 3D.

## 7. Breakpoint e movimento ridotto

| Larghezza | Cosa cambia | Originale |
|---|---|---|
| > 1080px | Menu in riga nella pillola | `@media (max-width: 1080px)` |
| ≤ 1080px | Menu a tendina (overlay del blocco Navigazione, ristilizzato) | idem |
| ≤ 900px | Colonne impilate, sfera in versione mobile, timeline a scorrimento con il dito, “Altri progetti” a carosello, Mondi a scorrimento con puntini | `@media (max-width: 900px)` |
| ≤ 560px | Testo a 17px, campi del modulo uno sotto l'altro, margini ridotti | `@media (max-width: 560px)` |

I valori fluidi (`clamp()`) per testi e spazi riducono il numero di breakpoint necessari.

**Movimento ridotto** (`prefers-reduced-motion: reduce`):
- niente intro del logo: la pagina parte già pronta;
- sfondo a pixel e cursore spenti;
- niente comparsa allo scroll (N1) né transizioni tra pagine (N3): sono dentro `prefers-reduced-motion: no-preference`;
- la sfera non ruota da sola e la timeline dei report non è legata allo scroll (`@media (max-width: 900px), (prefers-reduced-motion: reduce)`, come l'originale);
- `scroll-behavior` torna `auto`.

Le stesse animazioni si spengono anche per tutti da Impostazioni › WeRo › Animazioni.

## 8. Selettori dei plugin sovrascritti

### Contact Form 7 (`blocks/contact-form-7--contact-form-selector.css`)
Sempre dentro `.wpcf7`, così non tocca altri moduli fuori dal sito:
- `form`, `form p`, `label`;
- `.wpcf7-form-control-wrap`;
- `input[type=text|email|tel|url]`, `textarea`, `:focus`;
- `.wpcf7-checkbox`, `.wpcf7-list-item`, `.wpcf7-list-item-label`, `input:checked + .wpcf7-list-item-label` (scelte a chip);
- `.wpcf7-acceptance` (casella privacy);
- `.wpcf7-submit`, `:hover` (pulsante a pillola con sfumatura);
- `.wpcf7-spinner`;
- `.wpcf7-not-valid-tip`;
- `.wpcf7-response-output`, `form.sent`, `form.invalid`, `form.failed`, `form.spam`.

### Yoast SEO, breadcrumb (`blocks/yoast-seo--breadcrumbs.css`)
- `.yoast-breadcrumbs`, `.yoast-breadcrumbs a`, `a:hover`, `.breadcrumb_last`.

### Cookie Consent (cookiez)
- **Nessun selettore sovrascritto.** Il banner è disegnato dal servizio collegato e nel Playground non compare (“Not connected”).
- I colori si impostano nelle opzioni del plugin (vedi PLUGIN-IMPOSTAZIONI.md). Se dopo il collegamento serve un ritocco, va in un file `assets/css/cookie.css` del tema, con selettori presi dal banner reale. Non vanno indovinati prima.

## 9. Convenzioni di nome

| Cosa | Regola | Esempio |
|---|---|---|
| Classi del sito | Prefisso `wero-`, BEM | `wero-hero__title`, `wero-tag--scuro` |
| Stati | `is-` | `is-open`, `is-playing` |
| Varianti di stile | `is-style-` + slug; tema: `{blocco}-{nome}`, plugin: `wero-{nome}` | `is-style-button-dark`, `is-style-wero-worlds` |
| File CSS per blocco | `{namespace}--{blocco}.css` | `core--navigation.css` |
| Handle degli asset | `wero-theme-…`, `wero-style-…` | `wero-theme-site` |
| Blocchi | `wero/{nome}` | `wero/project-meta` |
| Pattern | `wero-theme/{area}-{nome}` | `wero-theme/home-mondi` |
| Token | slug in kebab-case | `paper-light`, `heading-story` |
| Variabili | Solo quelle di theme.json; locali con `--wero-` | `--wero-edge` |
| Lingua | Commenti, etichette e nomi visibili in italiano; codice e classi in inglese o italiano breve | — |
