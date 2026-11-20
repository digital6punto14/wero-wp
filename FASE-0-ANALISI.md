# Fase 0: analisi del sito WeRo per WordPress

Basata su `html-source/` e sulle decisioni D1–D33 di DECISIONI.md. Nessun codice è stato scritto.

**Divisione dei compiti**
- **Tema `wero-theme`:** solo l'aspetto.
- **Plugin `wero-core`:** tutto il resto.

**Dove si modifica**
- **Editor:** le pagine e i progetti.
- **Site Editor:** header, footer, template e stili.
- **Impostazioni sito:** la pagina del plugin.
- **Campi:** i campi ACF nel pannello del contenuto.

---

## 1. Inventario

### 1.1 Pagine
| Originale | In WordPress | URL |
|---|---|---|
| index.html | Pagina "Home", impostata come home statica | `/` |
| progetto-articolo.html | Progetto "Accessible Robotics", template **Progetto – Articolo** | `/progetti/accessible-robotics/` |
| progetto-articolo-complesso.html | Progetto "Generative Bionics", template **Progetto – Articolo complesso** | `/progetti/generative-bionics/` |
| progetto-video.html | Progetto "Cultura robotica al Gazometro", template **Progetto – Video** | `/progetti/cultura-robotica-al-gazometro/` |
| progetto-podcast.html | Progetto "Tools for human beings", template **Progetto – Podcast** | `/progetti/tools-for-human-beings/` |
| — (nuova) | Pagina "Privacy policy", testo di 6.14 | `/privacy-policy/` |
| — (nuova) | Pagina "Cookie policy", testo di 6.14 corretto | `/cookie-policy/` |
| — (nuova) | Archivio progetti: griglia di tutti i progetti, serve anche alla sitemap | `/progetti/` |

Altri 8 progetti della lista in home non hanno una pagina nell'originale. Diventano progetti con una pagina di partenza dal modello più adatto e testo segnaposto, da completare. Sono:
- IIT – Talk Robotica
- CDP Tech Transfer Day
- CNR
- Milano Sala Next
- ROAD
- e altri, fino ai 12 totali.

### 1.2 Sezioni e componenti

**Elementi comuni a tutte le pagine**
| Elemento | Note |
|---|---|
| Header | Menu a pillola fisso: logo, 6 link alle sezioni della home, pulsante "Diventa partner", menu su mobile |
| Footer | Logo grande; colonne Contatti, Seguici e Founder (loghi Omnicom e 6.14); copyright; "Torna su" |
| Sfondo e cursore | Sfondo "pixel beams" (WebGL) e cursore "computer vision": elementi globali |

**Home**
| Elemento | Note |
|---|---|
| Intro | Logo animato all'apertura |
| Hero | Copertina del video, titolo, testo, due pulsanti, video al clic |
| Chi siamo | Titolo e testo; loghi founder con scheda; fascia nera "In questi anni abbiamo collaborato con" (riga curva dei loghi + CTA); foto eventi a scorrimento |
| Mondi | Titolo, testo, 3 card con immagine, titolo, testo e link-filtro |
| Progetti | Sfera, pannello info, lista paginata, vista a griglia, filtri |
| Report | Timeline con 3 report |
| Voci | 3 video |
| Perché | Riquadro scuro con titolo, foto e testi |
| Partner | Form "Diventa partner" |

**Pagine progetto**
| Elemento | Note |
|---|---|
| Testata | Barra "Tutti i progetti" + bollino del mondo + tipo e anno; titolo; sottotitolo |
| Corpo | Diverso per formato |
| Chiusura | "Altri progetti" + riquadro "Vuoi diventare un nostro partner?" |

### 1.3 Design token, estratti dal CSS
| Gruppo | Valori |
|---|---|
| Colori | paper `#f4f2e9` · paper-2 `#fbfaf5` · ink `#15121a` · ink-2 `#3b3640` · muted `#6c6670` · pink `#ff3f7a` · magenta `#ff00c7` · business `#ff3f7a` · ricerca `#7b5cff` · bianco |
| Gradiente | `linear-gradient(90deg, #ff3f7a, #ff00c7)` |
| Font | Space Grotesk 400/500/600/700, ospitato sul sito in WOFF2 |
| Titoli | h2 `clamp(40px, 6vw, 88px)`, interlinea 0,98, spaziatura −0,045em, peso 500 · titoli dei progetti `clamp(40px, 6.4vw, 96px)` · testo introduttivo `clamp(18px, 1.5vw, 21px)` |
| Spaziature | section `clamp(56px,6vw,88px)` · head `clamp(32px,4vw,56px)` · block `clamp(48px,6vw,80px)` · inner `clamp(24px,3.5vw,44px)` |
| Layout | contentSize 1280px − margini · margini laterali `clamp(16px,4vw,48px)` |
| Raggi | 22px · 14px · 28px (media) · 40px (fasce scure) · 999px (pillole) |
| Ombre | pulsanti a gradiente (alone magenta) · card |
| Easing | `cubic-bezier(.2,.8,.2,1)` |
| Breakpoint | 900px (mobile ↔ desktop) · 560px |

### 1.4 Form
Un solo form, "Diventa partner", con questi campi:
- Nome *
- Azienda / Ente
- Email *
- Ambito: Ricerca, Business, Comunicazione & creatività
- Messaggio
- Privacy *

### 1.5 Script
Sette file JS, circa 92 KB in tutto, senza librerie. Tabella completa e decisioni in DECISIONI.md (sezione B, D19–D20).

### 1.6 Immagini
| Tipo | Contenuto |
|---|---|
| Foto | hero, mondi, voci, foto eventi (4), Perché, Generative Bionics (3 + 1 video MP4) |
| Loghi | founder (2) e collaboratori: 9 loghi + 9 "tessere" SVG con etichetta |
| Logo WeRo | completo e marchio, in SVG |

Tutte vanno nella Libreria media con testo alternativo, sostituibili (D24).

---

## 2. Tabella di conversione

Legenda:
- **Core**: blocco nativo di WordPress.
- **Pattern**: sezione pronta da inserire.
- **Part**: template part (header e footer).
- **Blocco wero/**: blocco del plugin.
- **ACF**: campo.
- **Opz.**: Impostazioni sito.

### Elementi globali
| Elemento HTML | Diventa | Dove si modifica |
|---|---|---|
| `<header class="nav">` | Part **header**: Gruppo con Logo del sito, Navigazione core con link alle ancore della home, Pulsanti "Diventa partner"; stile a pillola da CSS del tema | Site Editor › Template part › Header; voci da Aspetto › Navigazione |
| Link attivo del menu durante lo scroll | piccolo JS del tema, caricato con la Navigazione | — |
| `<footer>` | Part **footer**: Logo, Colonne, Titoli, Paragrafi; email e LinkedIn collegati a Impostazioni sito (Block Bindings `wero/site`); loghi founder come Immagini; copyright con anno automatico (binding) | Site Editor › Footer; email e LinkedIn da Impostazioni sito |
| Sfondo pixel beams e cursore | script del tema, attivati dalle interruttori di Impostazioni sito | Impostazioni sito › Animazioni |
| Intro del logo | Blocco **wero/intro** (solo nel template della home) | Impostazioni: attivo, solo prima visita |
| Riquadro "Vuoi diventare un nostro partner?" | Pattern **sincronizzato** "CTA partner" (Gruppo + Paragrafo + Pulsanti) | Editor › Pattern › CTA partner: cambia ovunque |

### Home
La pagina "Home" è composta da pattern. Ogni sezione è un Gruppo con ancora (`chi`, `mondi`, …) bloccato in modalità "solo contenuto" per gli Editor (D6).

| Sezione HTML | Diventa | Dove si modifica |
|---|---|---|
| Hero | Blocco **wero/hero-video** con InnerBlocks: Titolo H1, Paragrafo, Pulsanti. Attributi: copertina (Libreria media) e link del video (file, YouTube o Vimeo, D15). Il video parte al clic. | Editor della Home |
| Chi siamo, testo | Pattern: Colonne + Titolo H2 + Paragrafo | Editor |
| Loghi founder con scheda | Blocco **wero/partners** (dinamico): legge i Partner con "Founder" attivo; scheda con Interactivity API | Partner (post type); titolo e testo dall'editor |
| Fascia "In questi anni abbiamo collaborato con" | Pattern: Gruppo scuro + Titolo + legenda + blocco **wero/logo-row** (dinamico: Partner non founder, riga curva) + pattern sincronizzato "CTA partner" | Partner; velocità e curvatura nelle impostazioni del blocco |
| Foto eventi | **Galleria** core con stile **"A scorrimento"** (`is-style-wero-scroller`) + frecce (JS del tema caricato solo con quello stile) | Editor: aggiungi, togli, riordina foto |
| Mondi | Pattern: Titolo + Paragrafi + blocco **wero/worlds** (dinamico: termini della tassonomia Mondo con immagine, testo, link-filtro; puntini su mobile) | Progetti › Mondi (termini + campi ACF) |
| Progetti | Blocco **wero/projects-explorer** (dinamico): sfera 3D, pannello info, filtri, lista paginata, vista a griglia. Il titolo "Progetti" è un Titolo core sopra. | Progetti (post type); impostazioni del blocco: progetti per pagina, giro iniziale |
| Report | Pattern: Titolo + Paragrafo + blocco **wero/report-timeline** (dinamico dai Report) | Report (post type) |
| Voci | Titolo + blocco **wero/voices** (dinamico dalle Voci; video al clic dopo il consenso) | Voci (post type) |
| Perché | Pattern: Gruppo scuro arrotondato + Colonne + Titolo + Immagine + Paragrafi | Editor |
| Form partner | Pattern: Gruppo "vetro" + Colonne + Titolo + Paragrafo + blocco **Contact Form 7** | Testi dall'editor; campi e messaggi da Contatto › Moduli |

### Pagine progetto
Un template di blocchi per formato, scelto nel pannello "Template".

| Parte | Diventa | Dove si modifica |
|---|---|---|
| Barra "Tutti i progetti" + bollino + tipo e anno | Blocco **wero/project-meta** (dinamico: link all'archivio, termine Mondo, campi tipo e anno) | Campi ACF del progetto + tassonomia Mondo |
| Titolo H1 | **Titolo del post** core | Editor |
| Sottotitolo | **Riassunto** core (post-excerpt) con stile | Editor › pannello Riassunto |
| Corpo | **Contenuto del post**: l'editor parte da un **pattern iniziale** del formato (vedi sotto) e lo personalizza con i blocchi | Editor |
| Scheda laterale (Articolo) | Blocco **wero/project-facts** (dinamico: Mondo, Formato/Tipo, Anno, Con) | Campi ACF |
| "Altri progetti" | **Query Loop** core: 3 progetti, escluso quello corrente, con immagine, titolo e meta; scorrimento su mobile | Impostazioni del Query Loop nel template |
| CTA partner | Pattern sincronizzato | Una volta per tutto il sito |

**Pattern iniziali del corpo, uno per formato**
| Formato | Contenuto del pattern iniziale |
|---|---|
| Articolo | Immagine core con didascalia + Paragrafi + Titolo H2 + Citazione. "Immagine prima o dopo il testo" si ottiene spostando il blocco: il selettore della bozza viene eliminato (D19). |
| Articolo complesso | 3 sezioni "racconto", ognuna un pattern **"Blocco racconto"** (Colonne: media + Titolo H2 + Paragrafi + chip-link), alternate a sinistra e a destra, più una variante larga. Il video del logo usa il blocco Video core (autoplay, muto, loop). |
| Video | Blocco **wero/project-media** (player dal campo "link del video": file, YouTube o Vimeo; copertina; durata) + Colonne: Paragrafi · blocco **wero/chapters** con figli **wero/chapter** (tempo + titolo; il clic porta al punto del video) + blocco **wero/people** (Persone collegate con il campo Relazione) |
| Podcast | Colonne: blocco **wero/podcast-cover** (copertina a gradiente con logo, "Episodio" e titolo dai campi) + blocco **wero/project-media** in modalità audio (D19: player nativo con stile) + blocco **wero/listen-links** (piattaforme dai campi URL) · Paragrafi · **Dettagli** core per la trascrizione |

### Altre pagine
| Pagina | Diventa | Dove si modifica |
|---|---|---|
| Privacy e Cookie policy | Pagina con Titolo + Paragrafi + Elenchi + Tabelle core (template **page**) | Editor |
| Archivio `/progetti/` | Template **archive-progetto**: Titolo + filtri Mondo (termini) + Query Loop a griglia | Site Editor |
| 404, ricerca, index | Template semplici con lo stile del sito | Site Editor |

### Blocchi custom del plugin
Tutti hanno block.json e render.php; sono dinamici quando leggono dati.

| Blocco | Perché non basta il core | JS |
|---|---|---|
| wero/intro | Animazione SVG a più fasi | 1,5 KB, solo home |
| wero/hero-video | Copertina che diventa video al clic, con i link di D15 | piccolo, view |
| wero/partners | Dati dal post type + scheda accessibile | Interactivity API |
| wero/logo-row | Riga curva animata | 6,6 KB, view |
| wero/worlds | Termini della tassonomia con campi ACF + puntini | piccolo, view |
| wero/projects-explorer | Sfera 3D + lista + griglia + filtri | 38 KB + 8 KB, view, solo dove usato |
| wero/report-timeline | Timeline sticky orizzontale dai Report | piccolo, view |
| wero/voices | Video dalle Voci, al clic | piccolo, view |
| wero/project-meta, wero/project-facts | Dati ACF del progetto | nessuno |
| wero/project-media | Player da link qualsiasi (D15) | piccolo, view |
| wero/chapters + wero/chapter | Elenco ripetibile senza Repeater; salta al punto del video | Interactivity API |
| wero/people | Relazione con le Persone | nessuno |
| wero/podcast-cover, wero/listen-links | Dati ACF del podcast | nessuno |

---

## 3. Post type, tassonomie e campi ACF

Post type e tassonomie sono registrati nel codice del plugin con `show_in_rest`. I field group stanno in `wero-core/acf-json/`. Si usano solo campi di ACF gratuito.

### Post type
| Post type | Pubblico | Pagina singola | Template di blocchi | Campi ACF |
|---|---|---|---|---|
| **progetto** ("Progetti") | sì | sì, `/progetti/slug/` | per formato (4 template + 4 pattern iniziali) | **Formato** (select: articolo, articolo complesso, video, podcast; guida il pattern iniziale e la scheda) · **Tipo** (text: Evento, Video, Podcast, Branding, Report…) · **Anno** (number) · **Descrizione breve** (textarea, per sfera, lista e card) · **Icona della sfera** (select: robot, wave, eye, hand, gear…) · **Immagine card** (image; se vuota usa l'immagine in evidenza) · **Con / partner** (text) · **Partner collegati** (relationship → Partner) · **Link del media** (url) + **File del media** (file) · **Durata** (text) · **Copertina del player** (image) · **Persone** (relationship → Persona) · **Piattaforme di ascolto**: Spotify, Apple Podcasts, YouTube, altro (url, 4 campi) · **Numero episodio** (text) · **Link esterni**: etichetta + url, 2 coppie (al posto del Repeater; oltre servono blocchi Pulsanti nel contenuto) |
| **report** | no (niente pagina singola, come l'originale) | — | — | **Anno** (number) · **Descrizione** (textarea) · **PDF** (file, Libreria media) · **Stato** (select: Pubblicato / In arrivo) · **Testo del pulsante** (text) · Mondo (tassonomia) |
| **partner** | no | — | — | **Logo** (image) · **Tessera per la riga curva** (image, opzionale) · **Tipo** (select: Business / Ricerca) · **Founder** (true/false) · **Descrizione** (textarea) · **Sito web** (url) · ordine dal campo "Ordine" nativo |
| **voce** | no | — | — | **Link del video** (url) + **File** (file) · **Copertina** (immagine in evidenza) · **Descrizione** (textarea) |
| **persona** | no | — | — | **Ruolo** (text) · **Ente** (text) · **Foto** (immagine in evidenza, facoltativa) |

### Tassonomia
| Tassonomia | Su | Campi ACF del termine |
|---|---|---|
| **mondo** | progetto, report | Immagine (image) · Descrizione (textarea) · Colore (color picker) · Testo del link (text) |

### Al posto delle funzioni PRO di ACF
| Funzione PRO | Sostituita da |
|---|---|
| Repeater | post type collegati (Persone, Partner) e blocchi contenitore (Capitoli) |
| Flexible Content | pattern |
| Gallery | Galleria core |
| Options Page | Impostazioni sito con la Settings API |

---

## 4. Impostazioni sito (plugin) e tema

### Impostazioni sito
Menu Impostazioni › WeRo. Costruita con la Settings API: `register_setting` con `show_in_rest`, sanitizzazione e permesso `manage_options`.

| Sezione | Opzioni |
|---|---|
| **Organizzazione** | nome (WeRo), descrizione, joint venture: 6.14 S.r.l. (via Don Bosco 31, 20139 Milano, P.IVA 07376100967) e Omnicom Advertising, email contatti (contact@we-ro.it), LinkedIn |
| **Aspetto** (ThemeBridge) | colori principali (pink, magenta, ink, paper) e font, che sovrascrivono theme.json con `wp_theme_json_data_theme` |
| **Animazioni** | interruttore globale; intro (sì/no, solo prima visita); sfondo pixel beams; cursore; comparsa delle sezioni (N1) |
| **Funzioni** | blocchi attivi; emoji di WordPress disattivati (D31) |
| **Script di terze parti** | nessuno per ora. GA4 arriva da Site Kit (D33). Eventuali script futuri vanno registrati con `wp_enqueue_script` per il blocco dei cookie. |

### Controllo dell'editor
- **Blocchi esclusi per tutti:** HTML personalizzato, Shortcode e Classico.
- **Editor:** niente Site Editor (già così in WordPress), sezioni della home in modalità "solo contenuto".
- **Pattern:** categorie "WeRo – Home", "WeRo – Progetti" e "WeRo – Comuni".

### Se il plugin non è attivo
Il tema non va in errore:
- i blocchi wero/ mostrano l'avviso standard di "blocco non disponibile" solo nell'editor;
- i binding tornano al testo di riserva;
- gli interruttori usano i valori predefiniti.

---

## 5. Piano SEO

### Strumenti
- **Yoast SEO** si occupa di title, meta description, Open Graph, canonical, sitemap, breadcrumb e llms.txt.
- **Il plugin** estende solo lo schema di Yoast. Non stampa meta propri.

### URL
| Contenuto | URL | Sitemap |
|---|---|---|
| Home | `/` (le ancore `#chi`, `#progetti`… restano) | sì |
| Progetti | `/progetti/nome/`, archivio `/progetti/` | sì |
| Privacy e Cookie | `/privacy-policy/`, `/cookie-policy/` | sì, noindex consigliato per la cookie policy |
| Report, Partner, Voci, Persone, Mondi | nessuna pagina pubblica | esclusi |

Nessun redirect: il sito non è ancora online (D25).

### Titoli
Un solo H1 per pagina:
- **Home:** H1 nell'hero, H2 per ogni sezione, H3 per le card dei mondi, i report e le voci.
- **Progetti:** H1 il titolo, H2 le sezioni del racconto o "Altri progetti", H3 i titoli delle card.
- **Correzione rispetto all'originale:** nel footer "Contatti", "Seguici" e "Founder" restano paragrafi, come nell'originale, e non diventano titoli.

### Schema
Tramite i filtri `wpseo_schema_*` di Yoast.

| Pagina | Schema |
|---|---|
| Tutte | **Organization** "WeRo" (Yoast) + `founder`: 6.14 S.r.l. (Organization con indirizzo e vatID) e Omnicom Advertising; `sameAs` LinkedIn; `email` |
| Home | WebPage + WebSite (Yoast) |
| Progetto articolo | Article (Yoast) + `about` e `contributor` dalle Persone |
| Progetto video | Article + **VideoObject**, solo se c'è un link video, con nome, copertina e durata |
| Progetto podcast | Article + **PodcastEpisode**, solo se c'è un audio |
| Voci (in home) | **VideoObject** per ogni voce con video |

Event e FAQPage non si usano: il contenuto corrispondente non è in pagina.

### Agenti AI
- **Crawler AI:** consentiti (D28).
- **llms.txt:** dalla funzione di Yoast.
- **REST in sola lettura:** progetti, report, partner, voci e mondi, solo con i campi utili.
- **Abilities:** `wero/get-projects`, `wero/get-reports`, `wero/get-partners`, `wero/get-contact-info`.

---

## 6. Differenze previste rispetto all'HTML
1. **Font:** Space Grotesk ospitato sul sito invece che da Google Fonts. L'aspetto è identico.
2. **Menu su mobile:** si usa il blocco Navigazione core. L'apertura avviene come sovrapposizione del core, ristilizzata come la tendina originale. L'animazione d'apertura potrebbe differire di poco.
3. **Intro:** solo alla prima visita della sessione (D19).
4. **Player del podcast:** player audio nativo con stile, al posto del player su misura (D19). Spariscono i pulsanti ±15 s e velocità, perché il controllo della velocità dipende dal browser.
5. **Selettore "prima / dopo" della bozza:** eliminato. La posizione dell'immagine si sceglie spostando il blocco.
6. **Form:** il markup e i messaggi sono quelli di Contact Form 7, ristilizzati. La validazione avviene al momento dell'invio e non mentre si scrive.
7. **Card "Altri progetti":** generate dal Query Loop. Il primo campo è l'ultimo progetto pubblicato, non una scelta manuale (si può cambiare nel template).
8. **Anno del copyright:** automatico.

---

## 7. Domande rimaste aperte
1. **Archivio `/progetti/`:** propongo una pagina con la griglia di tutti i progetti, utile per sitemap e SEO e richiamata dal link "Tutti i progetti". *Consigliato*: sì. In alternativa "Tutti i progetti" porta a `/#progetti` e l'archivio non c'è.
2. **Gli 8 progetti senza pagina:** li creo come bozze con testo segnaposto, da completare prima della messa online? *Consigliato*: sì, in bozza.
3. **Cookie policy in noindex** su Yoast? *Consigliato*: sì.

---

## 8. Riepilogo delle modifiche previste
- **Tema `wero-theme`:**
  - theme.json v3 con tutti i token;
  - template: front-page, page, single-progetto e 4 template per i formati, archive-progetto, index, search, 404;
  - part: header e footer;
  - circa 15 pattern, tra cui "CTA partner" sincronizzato e "Blocco racconto";
  - stile "A scorrimento" per la Galleria e stili per Pulsanti, Gruppo e Query Loop;
  - sfondo e cursore, con CSS per blocco.
- **Plugin `wero-core`:**
  - 5 post type e la tassonomia Mondo;
  - 6 field group ACF in JSON;
  - circa 16 blocchi, tra cui la sfera dei progetti;
  - Impostazioni sito, ThemeBridge per colori e font, Block Bindings `wero/site`;
  - controllo dell'editor;
  - schema esteso di Yoast, REST in sola lettura, 4 abilities;
  - gestione di CF7, cioè CSS e JS caricati solo dove c'è il form;
  - emoji di WordPress disattivati.
- **Contenuti (Fase 4):** Home, 12 progetti, 3 report, 11 partner, 3 voci, 3 mondi, Privacy, Cookie, form CF7, menu.
- **Fuori dal mio lavoro:**
  - collegamento di Site Mailer e Image Optimizer all'account Elementor, sul sito online;
  - Site Kit, installato dal cliente;
  - scansione dei cookie online.
