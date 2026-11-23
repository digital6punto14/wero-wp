# Differenze rispetto all'HTML originale

Il sito WordPress riproduce l'originale (`html-source/`): layout, colori, tipografia, animazioni e testi. Il confronto è in `verifica/confronto/`: 5 pagine a 3 larghezze. Qui sono elencate tutte le differenze, divise per motivo.

## 1. Differenze volute (decise insieme)

| # | Dove | Originale | WordPress | Decisione |
|---|---|---|---|---|
| 1 | Intro del logo | A ogni apertura della home | Solo alla prima visita della sessione, con “Salta”; spenta con “movimento ridotto” | D19 |
| 2 | Selettore “prima / dopo” (articolo complesso) | Presente (strumento della bozza) | Eliminato | D19 |
| 3 | Player del podcast | Player disegnato a mano | Player audio del browser con lo stile del sito, oppure Spotify/Apple incorporati al clic | D19, D15 |
| 4 | Comparsa delle sezioni allo scroll | Assente | **N1**, solo CSS, disattivabile | D20 |
| 5 | Passaggio tra le pagine | Ricarica secca | **N3**, dissolvenza con la View Transitions API, menu fermo, disattivabile | D20 |
| 6 | Testata dei progetti | Barra con “Tutti i progetti” | Breadcrumb di Yoast + “Torna alla home” + “Tutti i progetti” | D45, D48 |
| 7 | Footer | Senza link legali | Link a Privacy policy e Cookie policy | D30 |
| 8 | Pagina `/progetti/` | Non esiste | Archivio a griglia, uguale alla vista griglia della home | D34, D46 |
| 9 | “Altri progetti” | Tre progetti fissi | Ordine della sfera, escluso il progetto aperto; copertina di riserva col colore del mondo per i progetti senza immagine | D46 |
| 10 | 8 progetti senza pagina | Solo card nella sfera | Pagine pubblicate con testo segnaposto e avviso in testa | D42 |
| 11 | Riga curva dei loghi | Tessere SVG preparate a mano | Tessere generate dal blocco dai Partner: si aggiungono senza grafica | D38 |
| 12 | Video e audio esterni | Iframe caricati con la pagina | Facciata: il servizio si carica solo al clic | D40 |
| 13 | Modulo “Diventa partner” | Finto (nessun invio) | Contact Form 7 con casella privacy obbligatoria, due email HTML | D21, D44 |
| 14 | Loghi SVG | SVG | PNG ad alta risoluzione (WordPress non accetta SVG nella Libreria media) | D41 |
| 15 | Persone (“Intervengono”, “Ospiti”) | Nomi d'esempio | Il blocco resta nascosto finché non si collegano persone vere | D43 |
| 16 | Emoji di WordPress | — | Disattivati (uno script e un cookie in meno) | D31 |

## 2. Differenze dovute a WordPress

| Dove | Cosa cambia | Perché |
|---|---|---|
| Menu sotto i 1080px | Tendina del blocco Navigazione core, ristilizzata per sembrare quella originale. Apertura e chiusura, focus e tasto Esc sono gestiti da WordPress. | Il menu si modifica dal Site Editor senza codice |
| Galleria foto degli eventi | Blocco Galleria core con stile “A scorrimento”; ora si usa anche da tastiera (frecce e Tab) | Le foto si cambiano dalla Libreria media |
| Mondi e Voci | Gruppi di blocchi core con stile, non markup fisso | D51: ogni card si modifica direttamente nella home |
| Classi CSS | Nomi diversi (`wero-…`, `is-style-…`) | Mappa completa in RIEPILOGO-CLASSI-CSS.md, sezione 5 |
| Altezza delle pagine | Tra il 90% e il 104% dell'originale | Effetto delle voci sopra (breadcrumb, link legali, avviso segnaposto) e degli spazi di theme.json arrotondati ai token |
| Markup | Wrapper in più dei blocchi (`wp-block-…`, `is-layout-…`) | Struttura standard dei blocchi; non cambia l'aspetto |
| Titoli `<title>` | “Titolo — WeRo”, gestiti da Yoast | Uguali all'originale nella forma |

## 3. Cose aggiunte che non si vedono

- **Dati strutturati (Yoast):**
  - Organization WeRo con i founder 6.14 S.r.l. e Omnicom Advertising;
  - VideoObject e PodcastEpisode, solo quando il media c'è davvero.
- **Indicizzazione e crawler:** sitemap XML; llms.txt; robots.txt senza blocchi per i crawler AI; cookie policy in noindex.
- **Dati per agenti AI:** REST in sola lettura `wero/v1` e 4 abilities (D50).
- **Accessibilità:**
  - un solo H1 per pagina;
  - etichette dei campi collegate;
  - focus visibile magenta;
  - galleria e sfera usabili da tastiera.

## 4. Differenze rimaste (note)

- **Contrasto del magenta** `#ff00c7` sui testi piccoli: 3,3:1 contro il minimo AA di 4,5:1. Riguarda il bollino “Language & Design” e l'etichetta nelle card. È così anche nell'originale, e il cliente ha scelto di lasciarlo (D52).
- **Prestazioni della home su mobile:** Lighthouse 80 in Playground. Il tempo di blocco viene dalla sfera 3D, uguale all'originale; su un hosting reale la risposta del server sarà molto più veloce.
- **Da collegare online:**
  - Site Mailer (invio email);
  - Cookie Consent (banner);
  - Image Optimizer (compressione).
