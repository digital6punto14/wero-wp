# Fase 6: verifica in WordPress Playground

- **Data:** 20/11/2026.
- **Ambiente:** WordPress 7.1.5, PHP 8.3, Playground CLI con tema, plugin e contenuti del ramo `sviluppo`.
- **Confronto visivo:** screenshot affiancati originale/WordPress in `verifica/confronto/`, 5 pagine × 3 larghezze.

Legenda:
- ✅ superato;
- ⚠️ superato con nota;
- ❌ non superato;
- ⏳ verificabile solo sul sito online.

## Risultati

| # | Controllo | Esito | Dettagli |
|---|---|---|---|
| 1 | Confronto visivo a 1440 / 768 / 375 px (home, articolo, articolo complesso, video, podcast) | ✅ | Layout, colori, tipografia e animazioni corrispondono. Altezza delle pagine tra il 90% e il 104% dell'originale: le differenze sono quelle volute, elencate più sotto. Nessuno scorrimento orizzontale, nessun errore JavaScript. |
| 2 | Pagine nell'editor senza blocchi non validi | ✅ | 21 contenuti, 370 blocchi: tutti validi. 17 pattern, 13 template e 5 parti: validi. |
| 3 | Nessun blocco HTML personalizzato, Shortcode o Classico | ✅ | Nessuno nei contenuti. I tre blocchi sono anche esclusi dall'editor. |
| 4 | Header, footer e stili modificabili dal Site Editor | ✅ | Footer e header si aprono nel Site Editor. Email, LinkedIn e copyright mostrano i valori veri anche nell'editor. |
| 5 | Colore cambiato dal plugin riflesso ovunque | ✅ | Magenta cambiato in Impostazioni › WeRo: la variabile e la sfumatura cambiano in tutto il sito. Ripristinato dopo la prova. |
| 6 | Campi ACF: salvataggio e visualizzazione | ✅ | Anno del progetto cambiato: compare subito nella testata. Ripristinato dopo la prova. |
| 7 | Form: validazione e messaggi | ✅ | Campi obbligatori segnalati con “Compila questo campo.”, messaggi in italiano, etichette collegate ai campi. |
| 8 | Form: invio e email | ⏳ | In Playground non c'è un server di posta, quindi l'invio dà l'errore previsto. Le email (notifica e conferma, in HTML) partiranno online dopo aver collegato Site Mailer. |
| 9 | CSS e JS di CF7 solo nelle pagine con un form | ✅ | Presenti solo in home, assenti in progetti, archivio e pagine legali. Corretto l'errore “wpcf7 is not defined” dei temi a blocchi. |
| 10 | Nessuno script di terze parti prima del consenso | ✅ | Zero richieste a domini esterni su tutte le pagine controllate. YouTube e Vimeo si caricano solo al clic del visitatore. |
| 11 | Banner dei cookie coerente con il design | ⏳ | Cookie Consent è installato ma “Not connected”: senza account Elementor il banner non compare. Va collegato e stilizzato sul sito online. |
| 12 | Immagini WebP/AVIF, nessuna doppia compressione o doppio lazy-load | ⚠️ | Le immagini dell'originale sono già WebP. Nessun `<img>` ha due attributi `loading`. Image Optimizer va collegato online. Tema e plugin non comprimono né convertono nulla. |
| 13 | Immagine LCP | ✅ | Copertina dell'hero con `fetchpriority="high"` e senza lazy-load. Preload solo del font critico. |
| 14 | Yoast: meta, canonical, Open Graph | ✅ | Un title, una description e un canonical per pagina. Cookie policy in noindex. |
| 15 | Sitemap, llms.txt, robots.txt | ✅ | La sitemap contiene pagine e progetti. llms.txt è attivo. robots.txt non blocca nessun crawler. |
| 16 | JSON-LD valido e senza duplicati | ✅ | Un solo grafo (Yoast) per pagina, con Organization completa dei founder. VideoObject verificato con un link di prova. |
| 17 | Un solo H1 e gerarchia dei titoli | ✅ | Un H1 per pagina. Nell'archivio i titoli delle card sono H2. |
| 18 | Accessibilità (axe-core, WCAG 2.2 AA) su home e pagine interne, desktop e mobile | ⚠️ | Nessuna violazione, salvo il contrasto del magenta `#ff00c7` su fondo chiaro nei testi piccoli (vedi sotto). La galleria a scorrimento ora si usa da tastiera. |
| 19 | Nessun errore o notice con WP_DEBUG | ✅ | Registro vuoto per tema e plugin. Restano solo gli avvisi del Playground che non raggiunge wordpress.org per aggiornamenti e traduzioni. |
| 20 | PHPCS (WordPress Coding Standards 3) | ✅ | Tema e plugin: 0 errori, 0 avvisi. L'importatore ha solo note di documentazione. |
| 21 | Plugin Check (plugin ufficiale, installato solo nel sito di prova locale) | ✅ | Su `wero-core`: 0 errori, 1 avviso di prestazioni (`post__not_in` in “Altri progetti”, query di 3 elementi: trascurabile, commentato nel codice). Corretti durante la verifica: protezione dall’accesso diretto in 14 file dei blocchi, readme.txt aggiunto (in inglese, come richiede la directory), file nascosto in languages/ rimosso. |
| 22 | Lighthouse, home (mobile) | ⚠️ | Performance 80, Accessibilità 97, Best practices 100, SEO 92. LCP 2,2 s, CLS 0, TBT 480 ms. |
| 23 | Lighthouse, progetto (mobile) | ✅ | Performance 93, Accessibilità 96, Best practices 100, SEO 92. LCP 2,7 s, CLS 0, TBT 110 ms. |

**Note su Lighthouse**
- **Tempo di risposta:** Playground esegue PHP nel browser (WebAssembly) e risponde in circa 2,3 s. Su un hosting reale il tempo di risposta scende di molto, e con lui FCP e LCP.
- **SEO 92:** il voto è più basso solo perché Lighthouse va in timeout leggendo robots.txt. Il file è valido.
- **TBT della home:** viene dalla sfera 3D, la stessa dell'originale.
- **Categoria “agentic browsing”:** cerca un “ARD manifest”, che non fa parte dei requisiti. Non lo aggiungo senza indicazioni.

## Contrasto del magenta (deciso: resta com'è, D52)

Il magenta `#ff00c7` su fondo crema ha un contrasto di 3,3:1, mentre il minimo AA per i testi piccoli è 4,5:1. Riguarda:
- il bollino “Language & Design” (stile “Contorno”);
- l'etichetta magenta nelle card della sfera.

È un colore dell'originale. Le opzioni:
- **a)** lasciarlo così;
- **b)** usare un magenta più scuro (per esempio `#c2009a`, circa 4,6:1) solo per i testi piccoli su fondo chiaro, lasciando sfumature e titoli come sono.

Il cliente ha scelto **a)** il 23/11/2026.

## Differenze rispetto all'HTML

**Differenze volute:**
- **Testata dei progetti:**
  - c'è la breadcrumb di Yoast;
  - c'è “Torna alla home” insieme a “Tutti i progetti”;
  - il selettore “immagine prima / dopo” della bozza non c'è più.
- **Footer:** ci sono i link a Privacy e Cookie policy.
- **Altri progetti:** seguono l'ordine della sfera; i progetti senza immagine hanno la copertina di riserva col colore del mondo.
- **Menu su mobile:** usa la tendina del blocco Navigazione core, ristilizzata.
- **Player del podcast:** è il player del browser, con lo stile del sito.
- **Intro del logo:** compare solo alla prima visita della sessione.
- **Animazioni nuove:** comparsa morbida delle sezioni allo scroll (N1) e transizione morbida tra le pagine con la View Transitions API (N3). Entrambe solo CSS, spente con “movimento ridotto” e disattivabili da Impostazioni › WeRo.

**Contenuti:** gli 8 progetti pubblicati con testo segnaposto non hanno ancora immagine.
