# WeRo su WordPress

Sito di **WeRo Robot Communication Studio** (`https://we-ro.it`) convertito dall'HTML statico a WordPress con un tema a blocchi e un plugin.

| Pacchetto | Cosa contiene | Regola |
|---|---|---|
| `wero-theme` | Solo l'aspetto: theme.json, template, parti, pattern, CSS, piccoli script visivi, font | Nessun contenuto, nessuna opzione, nessun tipo di contenuto |
| `wero-core` | Tutto il resto: tipi di contenuto, campi ACF, 14 blocchi, sorgenti dei binding, impostazioni, form, schema per Yoast, REST e abilities | Funziona con qualsiasi tema; senza `wero-theme` perde solo l'aspetto |

Documenti collegati:
- `INSTALLAZIONE.md`: procedura di installazione sul sito online e controlli prima della pubblicazione.
- `GUIDA-UTILIZZO.md`: guida per chi modifica il sito, con screenshot.
- `PLUGIN-IMPOSTAZIONI.md`: i 6 plugin e le impostazioni da applicare.
- `RIEPILOGO-CLASSI-CSS.md`: token, classi, file CSS/JS e convenzioni.
- `DIFFERENZE.md`: cosa cambia rispetto all'HTML originale.
- `DECISIONI.md`: tutte le decisioni (D1–D52).
- `RIEPILOGO-LAVORO.md`: riepilogo del lavoro, struttura dei file e gestione di GitHub.
- `verifica/compatibilita-browser.html`: compatibilità con Safari, Safari iOS, Chrome e Android, versioni e anni.
- `PROCEDURA-AGGIORNAMENTI.md`: cosa fare a ogni aggiornamento di WordPress, plugin e PHP.
- `PROMPT-RIVISTO.md`: il prompt iniziale riscritto con quanto imparato.
- `verifica/VERIFICA.md`: esito dei controlli della fase 6.

---

## 1. Requisiti e installazione

La procedura completa, passo per passo e provata su un WordPress pulito, è in **INSTALLAZIONE.md**. In breve:

1. **Requisiti:** WordPress **7.1+** e PHP **8.3+** (D37), HTTPS. Tema e plugin da soli funzionano già con WordPress 6.9 e PHP 8.2.
2. **Plugin:** installa e attiva Advanced Custom Fields (gratuito), Contact Form 7, Yoast SEO, Site Mailer, Cookie Consent (`cookiez`), Image Optimizer.
3. **Tema e plugin WeRo:** carica e attiva `consegna/wero-theme.zip`, `consegna/wero-core.zip` e `consegna/wero-installer.zip`.
4. **Contenuti:** Strumenti › **Installa contenuti WeRo** › Importa i contenuti, poi elimina il plugin dell'installer. In alternativa, con WP-CLI:
   ```bash
   wp eval-file contenuti/import.php
   ```
   L'importatore (`contenuti/import.php`) è rieseguibile: ogni elemento ha la meta `_wero_import` e viene aggiornato, non duplicato.
5. **Servizi:** collega Site Mailer, Cookie Consent e Image Optimizer (PLUGIN-IMPOSTAZIONI.md).

**Rifare i pacchetti** dopo una modifica (tema, plugin e installer con dentro `contenuti/`):

```bash
./crea-zip.sh
```

**Anteprima in WordPress Playground (nel browser, niente da installare):**
https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/digital6punto14/wero-wp/sviluppo/blueprint.json

**Playground in locale** (le modifiche ai file si vedono subito, utente `admin` / `password`):

```bash
./avvia-playground.sh
```

Dietro un proxy servono `NODE_USE_ENV_PROXY=1` e `NODE_EXTRA_CA_CERTS`.

---

## 2. Architettura

### Struttura del repository

```text
wero-theme/            tema a blocchi (solo aspetto)
  theme.json           token: colori, sfumature, font, dimensioni, spazi, raggi, ombre, custom
  styles/blocks/       12 varianti di stile dei blocchi core (JSON)
  templates/           13 template (4 per i progetti)
  parts/               header, footer, cta-partner, progetto-testata, progetto-chiusura
  patterns/            17 pattern (sezioni della home, modelli di progetto, footer)
  assets/css/          site.css, view-transitions.css, blocks/{namespace}--{blocco}.css
  assets/js/           background, cursor, nav, gallery-scroller (senza librerie)
  assets/fonts/        Space Grotesk ospitato sul sito
  functions.php        SOLO supporti ed enqueue
wero-core/             plugin
  wero-core.php        avvio: carica le classi e chiama register() su ognuna
  includes/            una classe per area (vedi tabella)
  src/blocks/          sorgenti dei 14 blocchi (@wordpress/scripts)
  build/blocks/        blocchi compilati (quelli che WordPress carica)
  assets/styles/       CSS/JS dello stile di Gruppo “Mondi”
  assets/forms/        JS del modulo “Scarica i report” (link ai PDF dopo l'invio)
  assets/editor/       sorgente JS dei binding per l'editor
  acf-json/            5 gruppi di campi ACF in JSON
contenuti/             importatore, dati, media, email HTML, testi legali
installer/             plugin temporaneo “WeRo – Installazione contenuti” (zip con contenuti/)
consegna/              i tre zip da caricare in WordPress
blueprint.json         Playground: installa plugin, tema, plugin WeRo e contenuti
```

### Le classi del plugin (`namespace Wero\Core`)

| Classe | File | Cosa fa | Hook principali |
|---|---|---|---|
| `Settings` | class-settings.php | Pagina Impostazioni › WeRo, opzione `wero_settings` (Settings API, esposta in REST) | `admin_init`, `admin_menu`, `rest_api_init`, `option_wero_settings` |
| `Post_Types` | class-post-types.php | Tipi `progetto` (pubblico, `/progetti/`), `report`, `partner`, `persona`; ordine dell'archivio come la sfera | `init`, `pre_get_posts` |
| `Taxonomies` | class-taxonomies.php | Tassonomia `mondo` (Robotics Intelligence, Conversation & Events, Language & Design) | `init` |
| `Acf` | class-acf.php | Punto di caricamento e salvataggio dei JSON in `acf-json/`; avviso se ACF manca | `acf/settings/load_json`, `acf/settings/save_json`, `admin_notices` |
| `Fields` | class-fields.php | Lettura dei campi con fallback se ACF è spento | — |
| `Blocks` | class-blocks.php | Registra i 14 blocchi dal manifest e la categoria “WeRo” | `init`, `block_categories_all` |
| `Block_Styles` | class-block-styles.php | Stile di Gruppo “Mondi”, CSS/JS caricati solo se usato | `init`, `render_block_core/group`, `enqueue_block_assets` |
| `Bindings` | class-bindings.php | Sorgenti `wero/site` e `wero/field` | `init`, `enqueue_block_editor_assets` |
| `Theme_Bridge` | class-theme-bridge.php | Colori e font delle impostazioni dentro theme.json; interruttori delle animazioni; intro; emoji | `wp_theme_json_data_theme`, `wero_theme_feature`, `body_class`, `wp_head` |
| `Editor` | class-editor.php | Blocchi esclusi (HTML, Shortcode, Classico), categorie dei pattern, blocco della struttura della home per gli Editor, query “Altri progetti”, copertina di riserva | `allowed_block_types_all`, `block_editor_settings_all`, `query_loop_block_query_vars`, `render_block_core/post-featured-image` |
| `Forms` | class-forms.php | CF7: CSS/JS solo dove c'è un modulo, niente `autop`, modulo scelto nelle impostazioni | `wpcf7_load_js`, `wpcf7_load_css`, `wpcf7_autop_or_not`, `render_block_contact-form-7/contact-form-selector`, `wero_partner_form_block` |
| `Reports` | class-reports.php | Download dei report (D54): elenco automatico dei report scaricabili nel modulo CF7 (`data:wero_reports`), codice email `[_wero_report_links]`, link della timeline alla pagina di download, script che mostra i link dopo l'invio | `wpcf7_form_tag_data_option`, `wpcf7_special_mail_tags`, `wero_report_form_block` |
| `Media` | class-media.php | Riconosce file, YouTube, Vimeo, Spotify, SoundCloud, Apple Podcasts; incorporamento solo al clic | — |
| `Data` | class-data.php | Fonte unica dei dati per REST, abilities e schema | — |
| `Seo` | class-seo.php | Estende il grafo di Yoast: Organization, founder, VideoObject, PodcastEpisode | `wpseo_schema_organization`, `wpseo_schema_organization_social_profiles`, `wpseo_schema_graph` |
| `Rest` | class-rest.php | REST pubblica in sola lettura `wero/v1` | `rest_api_init` |
| `Abilities` | class-abilities.php | 4 abilities in sola lettura per agenti AI | `wp_abilities_api_categories_init`, `wp_abilities_api_init` |

### Come comunicano tema e plugin
- Il tema **non legge opzioni**. Chiede al plugin se una funzione visiva è attiva con il filtro `wero_theme_feature`. Senza plugin tutto resta attivo.
- Il plugin cambia l'aspetto globale **solo** attraverso theme.json (`wp_theme_json_data_theme`): un colore cambiato in Impostazioni › WeRo arriva a ogni blocco e al Site Editor.
- I pattern del tema usano i blocchi del plugin (`wero/*`). Senza plugin quei blocchi non compaiono, il resto della pagina sì.
- Testi come email, LinkedIn e copyright nel footer sono blocchi core collegati con i **Block Bindings** alla sorgente `wero/site`: si modificano in un punto solo e nell'editor si vede il valore vero.

### Dati
| Contenuto | Dove vive | Si modifica da |
|---|---|---|
| Testo delle pagine e dei progetti | Blocchi | Editor a blocchi |
| Dati dei progetti (anno, tipo, mondo, media, persone…) | Campi ACF + tassonomia | Pannello sotto l'editor del progetto |
| Mondi della home | Blocchi Gruppo con stile “Mondi” | Home, nell'editor (D51) |
| Foto dei progetti | Galleria “A scorrimento” nel contenuto | Editor del progetto (D55) |
| Partner, report, persone | Tipi di contenuto + ACF | Menu della bacheca |
| Email, LinkedIn, founder, colori, animazioni | Opzione `wero_settings` | Impostazioni › WeRo |
| Title, description, schema, breadcrumb, sitemap, llms.txt | Yoast | Yoast SEO e pannello Yoast nell'editor |

### SEO e agenti AI
- **Yoast è l'unico strumento SEO.** Il plugin non stampa meta né JSON-LD: aggiunge dati al grafo di Yoast con i suoi filtri.
- REST `wero/v1` (pubblica, sola lettura): `/projects`, `/reports` (con il link alla pagina di download, non al PDF), `/partners`, `/contact`.
- Abilities (Abilities API di WordPress, sola lettura, `show_in_rest`): `wero/get-projects`, `wero/get-reports`, `wero/get-partners`, `wero/get-contact-info`. Su `/wp-json/wp-abilities/v1` richiedono un utente collegato. Sono marcate `mcp.public`: se in futuro si installa l'MCP Adapter ufficiale di WordPress, un client MCP (per esempio Claude) le vede come strumenti senza altro codice.

---

## 3. Hook del progetto

### Filtri offerti

| Filtro | Dove | Argomenti | Uso |
|---|---|---|---|
| `wero_theme_feature` | tema, `functions.php` | `bool $enabled` (predefinito true), `string $feature` | Accende o spegne una funzione visiva: `background`, `cursor`, `reveal`, `transitions`, `nav-spy`. Il plugin lo collega alle Impostazioni › WeRo. |
| `wero_partner_form_block` | pattern `home-partner.php` | `string $markup` (predefinito vuoto) | Markup del blocco del modulo “Diventa partner”. Il plugin restituisce il blocco CF7 del modulo scelto nelle impostazioni. |
| `wero_report_form_block` | pattern `pagina-scarica-report.php` | `string $markup` (predefinito vuoto) | Markup del blocco del modulo “Scarica i report” (modulo scelto in Impostazioni › WeRo). |
| `option_wero_settings` | core WordPress | `array $value` | Il plugin lo usa per unire sempre i valori predefiniti: un'impostazione nuova non è mai vuota. |

Esempio: spegnere il cursore “computer vision” da un altro plugin.

```php
add_filter(
	'wero_theme_feature',
	static fn( bool $on, string $feature ): bool => 'cursor' === $feature ? false : $on,
	20,
	2
);
```

### Hook di WordPress e dei plugin usati
Sono quelli nella tabella delle classi. Riepilogo per origine:
- **WordPress:** `init`, `plugins_loaded`, `admin_init`, `admin_menu`, `rest_api_init`, `wp_head`, `wp_enqueue_scripts`, `enqueue_block_assets`, `enqueue_block_editor_assets`, `pre_get_posts`, `body_class`, `allowed_block_types_all`, `block_categories_all`, `block_editor_settings_all`, `query_loop_block_query_vars`, `render_block_*`, `wp_theme_json_data_theme`, `wp_abilities_api_categories_init`, `wp_abilities_api_init`, `emoji_svg_url`.
- **Yoast:** `wpseo_schema_organization`, `wpseo_schema_organization_social_profiles`, `wpseo_schema_graph`.
- **ACF:** `acf/settings/load_json`, `acf/settings/save_json`.
- **Contact Form 7:** `wpcf7_load_js`, `wpcf7_load_css`, `wpcf7_autop_or_not`, `wpcf7_form_tag_data_option`, `wpcf7_special_mail_tags`; nel browser l'evento `wpcf7mailsent`.
- **Tema:** `after_setup_theme`, `wp_enqueue_scripts`, `init` (CSS per blocco), `render_block` (script legati a un blocco), `wp_head` (preload del font).

---

## 4. Come si aggiunge…

### …un pattern (tema)
1. Crea `wero-theme/patterns/nome-pattern.php` con l'intestazione:
   ```php
   <?php
   /**
    * Title: Nome leggibile
    * Slug: wero-theme/nome-pattern
    * Categories: wero-comuni
    * Keywords: parola, altra parola
    * Description: Una riga su cosa fa.
    *
    * @package WeroTheme
    */
   ?>
   <!-- wp:group {"tagName":"section","align":"full"} -->
   ...
   ```
2. Dentro solo markup di blocchi (copialo dall'editor con “Copia tutti i blocchi”). Testi traducibili con `esc_html_e( '…', 'wero-theme' )`, immagini con `get_theme_file_uri()`.
3. Categorie disponibili (registrate dal plugin): `wero-home` (sezioni della home), `wero-progetti` (contenuti di partenza dei progetti), `wero-comuni` (“WeRo – Sezioni comuni”: racconto, foto, fasce). Per un contenuto di partenza di un progetto aggiungi `Block Types: core/post-content` e `Post Types: progetto`: compare quando si crea un nuovo progetto.
4. Niente blocchi HTML, Shortcode o Classico. Usa token di theme.json (`var:preset|spacing|section`), non valori fissi.
5. WordPress tiene in cache l'elenco dei pattern: su un sito di sviluppo usa `define( 'WP_DEVELOPMENT_MODE', 'theme' );` (o `all`) per vederli subito; in produzione basta riattivare il tema o aggiornarne la versione.

### …un blocco (plugin)
1. Crea la cartella `wero-core/src/blocks/nome/` con:
   - `block.json` (`"apiVersion": 3`, `"name": "wero/nome"`, `"category": "wero"`, `"textdomain": "wero-core"`, `"render": "file:./render.php"`, `"style": "file:./style-index.css"`, `"viewScript": "file:./view.js"` se serve);
   - `index.js` (`registerBlockType` con `edit` che usa `ServerSideRender` o controlli dell'Inspector; `save: () => null` per i blocchi dinamici);
   - `render.php` (inizia con `defined( 'ABSPATH' ) || exit;`, usa `get_block_wrapper_attributes()`, scappa tutto l'output);
   - `style.css` e, se serve, `view.js` (JavaScript vanilla, rispetta `prefers-reduced-motion`).
2. Compila:
   ```bash
   cd wero-core && npm install && npm run build
   ```
   Lo script genera `build/blocks/nome/` e aggiorna `build/blocks/blocks-manifest.php`. Subito dopo (`postbuild`) `tools/legacy-views.js` converte gli script `view.js` per i browser elencati in `browserslist` di `package.json` (Safari/iOS 12+, Chrome 70+): non scrivere a mano sintassi che lì non esiste senza ricompilare. `Blocks::register()` registra tutto il manifest con `wp_register_block_types_from_metadata_collection`: non c'è altro da aggiungere in PHP.
3. Fai il commit anche di `build/`: è ciò che WordPress carica (lo zip non contiene `src/` né `node_modules/`).

### …un campo ACF (plugin)
1. Su un sito di sviluppo con `wero-core` attivo, vai in ACF › Gruppi di campi e modifica il gruppo (per esempio “Progetto – dati”).
2. Aggiungi il campo. Il **nome** in minuscolo con il trattino basso (`data_evento`). Salva.
3. ACF scrive il JSON direttamente in `wero-core/acf-json/` (punto di salvataggio del plugin). Fai il commit del file.
4. Per mostrarlo:
   - in un blocco del plugin: `Fields::post( $post_id, 'data_evento' )`;
   - in un blocco core, senza codice: collega il blocco (Paragrafo, Titolo, Immagine, Pulsante) alla sorgente `wero/field` con `"key":"data_evento"` dal pannello Attributi del blocco.
5. Solo funzioni di ACF gratuito: niente Ripetitore, Contenuto flessibile, Galleria, pagine opzioni. Per elenchi usa un tipo di contenuto con Relazione (come Persone) o blocchi Gutenberg.

### …un'ability (plugin)
1. Aggiungi il metodo che legge i dati in `Data` (così REST, schema e abilities restano allineati).
2. In `Abilities::abilities()` registra la nuova ability:
   ```php
   wp_register_ability(
   	'wero/get-nome',
   	array(
   		'label'               => __( 'Leggi …', 'wero-core' ),
   		'description'         => __( 'Cosa restituisce e quando usarla.', 'wero-core' ),
   		'category'            => 'wero',
   		'input_schema'        => array( 'type' => 'object', 'properties' => array(), 'additionalProperties' => false ),
   		'output_schema'       => array( 'type' => 'array', 'items' => array( 'type' => 'object' ) ),
   		'execute_callback'    => static fn() => Data::nome(),
   		'permission_callback' => '__return_true',
   		'meta'                => array(
   			'annotations'  => array( 'readonly' => true, 'destructive' => false, 'idempotent' => true ),
   			'show_in_rest' => true,
   			'mcp'          => array( 'public' => true ),
   		),
   	)
   );
   ```
3. Se l'ability **scrive** dati: `readonly` false, `permission_callback` con `current_user_can()` e nessun `mcp.public`.
4. Prova: `GET /wp-json/wp-abilities/v1/abilities/wero/get-nome` da utente collegato, poi `POST …/run`.

### …un tipo di contenuto o un'impostazione
- Tipo di contenuto: in `Post_Types::register_post_types()`, poi un gruppo ACF con location su quel tipo, poi riattiva il plugin per aggiornare i permalink.
- Impostazione: aggiungi una voce in `Settings::fields()` (tipo, predefinito, etichetta, sezione). Pagina, salvataggio, sanificazione e REST sono automatici. Per mostrarla in un blocco core aggiungi la chiave a `Bindings::site_keys()`.

---

## 5. Sviluppo e controlli

```bash
cd wero-core && npm install && npm run build
```

```bash
phpcs --standard=WordPress wero-theme wero-core --ignore=node_modules,build,vendor
```

- PHPCS (WordPress Coding Standards 3): 0 errori su tema e plugin.
- Plugin Check su `wero-core`: 0 errori, 1 avviso documentato (`post__not_in` su una query di 3 elementi).
- I CSS e JS sono scritti a mano, senza build, tranne i blocchi del plugin.

### Rifare gli zip

```bash
./crea-zip.sh
```

Crea `consegna/wero-theme.zip`, `consegna/wero-core.zip` (senza `src/`, `node_modules/`, file di build e log) e `consegna/wero-installer.zip` (installer con una copia di `contenuti/`).

## 6. Aggiornamenti
- **Tema e plugin WeRo:** non sono nella directory di WordPress, quindi non si aggiornano da soli. Per una nuova versione alza `Version` in `style.css` / `wero-core.php` (e `VERSION`, e `Stable tag` in `readme.txt`), rifai lo zip e caricalo da Plugin › Aggiungi nuovo › Carica (WordPress chiede se sostituire).
- **Compatibilità con una nuova versione di WordPress:** procedura in PROCEDURA-AGGIORNAMENTI.md, sezione 7.
- **Contenuti:** restano nel database; aggiornare tema o plugin non li tocca. Disinstallare `wero-core` (non solo disattivarlo) cancella solo l'opzione `wero_settings`: progetti, report e partner restano.
