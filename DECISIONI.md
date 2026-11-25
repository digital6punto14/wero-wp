# DECISIONI — WeRo su WordPress

Documento vivo: raccoglie ciò che è stato ricavato dai file, le domande aperte con la proposta consigliata e, man mano, le decisioni prese. Si aggiorna a ogni fase.

Stato: **Decisioni confermate dal cliente il 20/11/2026. Fase 0 confermata il 20/11/2026. Fase 1 completata. Fase 2 (tema) completata. Fase 3 (plugin) completata. Fase 4 (contenuti) completata. Fase 5 (SEO e agenti AI) completata. Fase 6 (verifica) completata: vedi verifica/VERIFICA.md. Fase 7 (consegna) completata il 23/11/2026: vedi README.md e GUIDA-UTILIZZO.md. Modifiche del 25/11/2026: D53–D57.**

---

## A. Cosa ho ricavato dai file (html-source)

### Pagine
| File | Cosa è | H1 |
|---|---|---|
| index.html | Home one-page: hero video, Chi siamo + partner, foto eventi, I tre mondi, Progetti (sfera + lista + griglia), Report (timeline), Le voci dei progetti, Perché lo facciamo, form "Diventa partner", footer | "Il primo Robot Communication Studio" |
| progetto-articolo.html | Modello progetto "articolo semplice" (immagine + testo + scheda laterale) | Accessible Robotics |
| progetto-articolo-complesso.html | Modello "articolo complesso" (sequenza di blocchi immagine/testo alternati) | Generative Bionics |
| progetto-video.html | Modello "video" (player, capitoli, persone) | Cultura robotica al Gazometro |
| progetto-podcast.html | Modello "podcast" (copertina, player audio, note, trascrizione) | Tools for human beings |

Le quattro pagine interne sono **modelli di un unico tipo di contenuto, il progetto**, in quattro formati. Tutte chiudono con "Altri progetti" e il riquadro "Vuoi diventare un nostro partner?".

### Contenuti che si ripetono (candidati a post type)
- **Progetti**: 12 nella lista della home. Campi già usati: titolo, descrizione breve, mondo, tipo (Evento, Video, Podcast, Branding…), anno, icona della sfera, immagine, link.
- **Mondi**: 3, cioè Robotics Intelligence, Conversation & Events e Language & Design, con titolo, descrizione, immagine e filtro.
- **Report**: 3 nella timeline. Campi: anno, titolo, mondo, descrizione, PDF o "In arrivo".
- **Partner e collaboratori**:
  - 2 founder, Omnicom Advertising e 6.14, con logo, descrizione e link;
  - 9 collaboratori con logo e tipo Business o Ricerca.
- **Voci dei progetti**: 3 video, con titolo, descrizione e copertina.
- **Foto eventi**: 4 immagini. Per le gallerie si usa il blocco core.

### Design
- **Font:** Space Grotesk, pesi 400, 500, 600 e 700, oggi caricato da Google Fonts. Licenza OFL, quindi può essere ospitato sul sito.
- **Colori:**

  | Nome | Valore |
  |---|---|
  | paper | #f4f2e9 |
  | paper-2 | #fbfaf5 |
  | ink | #15121a |
  | ink-2 | #3b3640 |
  | muted | #6c6670 |
  | pink | #ff3f7a |
  | magenta | #ff00c7 |
  | Business | #ff3f7a |
  | Ricerca | #7b5cff |

- **Gradiente:** pink → magenta, a 90°.
- **Spaziature:**

  | Nome | Valore |
  |---|---|
  | section | clamp(56px, 6vw, 88px) |
  | head | clamp(32px, 4vw, 56px) |
  | block | clamp(48px, 6vw, 80px) |
  | inner | clamp(24px, 3.5vw, 44px) |

- **Layout:** larghezza del contenuto 1280px, margini laterali clamp(16px, 4vw, 48px).
- **Arrotondamenti:** 22px e 14px.
- **Breakpoint:** 900px, che separa mobile e desktop, e 560px.
- Il CSS gestisce già `prefers-reduced-motion` in più punti.

### Contatti e link presenti
- Email: contact@we-ro.it, quindi il dominio è we-ro.it.
- LinkedIn: il link punta a linkedin.com generico, va sostituito con l'URL reale.
- Nessun servizio di terze parti caricato: niente analytics, mappe o YouTube. L'unico esterno è Google Fonts.
- I PDF dei report e i file video e audio non sono ancora disponibili: ci sono segnaposti.

### Script (tutti JavaScript scritto a mano, nessuna libreria)
Il JS non è minificato: in totale pesa circa 92 KB.

| File | Peso | Cosa fa |
|---|---|---|
| card-sphere.js | 38 KB | sfera 3D dei progetti |
| main.js | 20 KB | logica della home |
| background.js | 8,5 KB | sfondo WebGL |
| tilted-grid.js | 6,6 KB | riga curva dei loghi |
| page.js | 6 KB | pagine interne |
| dot-trail.js | 5 KB | cursore |
| intro.js | 1,5 KB | intro del logo |

---

## B. Animazioni e componenti interattivi

Nessuna voce usa librerie (GSAP, Swiper o simili): è tutto JS e CSS propri.

Legenda delle proposte:
- **CSS**: solo CSS.
- **IAPI**: Interactivity API.
- **JS**: script leggero senza librerie, caricato solo dal blocco che lo usa.
- **Blocco**: blocco custom del plugin.

Ogni animazione ha anche un interruttore globale nelle Impostazioni del plugin.

| # | Elemento | Dove | Cosa fa | Tecnica e peso oggi | Proposta in WordPress | Regolabile dall'editor | Mobile, movimento ridotto, accessibilità |
|---|---|---|---|---|---|---|---|
| 1 | Intro del logo | Home, a ogni caricamento | animazione del logo per 5 s, con "Salta" ed Esc | CSS keyframes + intro.js 1,5 KB | Blocco "Intro" con CSS + JS | attiva o no; solo prima visita o sempre; durata | Copre la pagina per 5 s e peggiora la metrica LCP. Saltata con movimento ridotto. Propongo di mostrarla solo alla **prima visita** della sessione. |
| 2 | Sfondo "pixel beams" | Tutto il sito, dietro le pagine | griglia di pixel che si accendono a onde e cambiano per sezione; reagisce al puntatore | WebGL, 30 fps, background.js 8,5 KB | JS caricato dal tema in tutte le pagine (è un elemento globale) | dal plugin: attivo o no, intensità | Si ferma con la scheda nascosta. Con movimento ridotto è fermo. Senza WebGL resta il colore crema. |
| 3 | Cursore "computer vision" | Tutto il sito, solo con mouse | mirino con coordinate al posto del cursore | canvas, dot-trail.js 5 KB | JS globale | dal plugin: attivo o no | Disattivato su touch e con movimento ridotto. |
| 4 | Sfera 3D dei progetti | Home, Progetti | card dei progetti su una sfera: trascinamento, frecce, filtri, giro iniziale. Su mobile è una nuvola 3D con transizioni; su PC lenti passa a una versione leggera. | card-sphere.js 38 KB + main.js | Blocco "Sfera progetti", dinamico: legge i progetti dal post type | filtro per mondo; quanti progetti; giro iniziale sì o no | Il componente più pesante, già ottimizzato. Comandi da tastiera: frecce e pulsanti. Con movimento ridotto niente giro iniziale. |
| 5 | Pannello info progetto | Home, accanto alla sfera | descrizione e link del progetto scelto, con dissolvenza | CSS keyframes | Parte del blocco 4 | — | Contenuto annunciato agli screen reader (aria-live). |
| 6 | Lista, paginazione e vista a griglia | Home, sotto la sfera | lista paginata (5 per pagina); pulsante "Guarda come griglia"; filtri per mondo | main.js | Parte del blocco 4 (stessi dati) | progetti per pagina; vista iniziale | Elementi da tastiera già presenti. |
| 7 | Riga curva dei loghi | Home, collaboratori | i loghi scorrono su un cilindro curvo | animazione CSS + tilted-grid.js 6,6 KB | Blocco "Riga loghi": legge i collaboratori dal post type | velocità; curvatura; quali loghi | Con movimento ridotto si ferma. Testo alternativo su ogni logo. |
| 8 | Popover dei partner | Home, loghi founder | al passaggio o al tocco si apre una scheda con descrizione e link | CSS + main.js | Blocco "Partner" con IAPI (Interactivity API) | contenuti dal post type | Pulsante con aria-expanded. Si chiude con Esc. |
| 9 | Mondi su mobile | Home, I tre mondi | scorrimento orizzontale con puntini sincronizzati | CSS scroll-snap + main.js | Blocco "Mondi" (pattern a colonne) + JS per i puntini | — | Scorrimento nativo, leggero. |
| 10 | Timeline dei report | Home, Report | su desktop la sezione resta ferma e scorrendo la timeline avanza in orizzontale; su mobile scorrimento orizzontale con frecce | main.js + CSS sticky | Blocco "Timeline report": legge i report dal post type | effetto attivo o no (fallback a scorrimento semplice) | Su mobile e con movimento ridotto è statica. Frecce da tastiera. |
| 11 | Foto eventi | Home | quattro foto a scorrimento orizzontale con frecce (desktop) | CSS scroll-snap + main.js | Blocco Galleria core con stile "a scorrimento" + JS leggero per le frecce | foto, ordine, testo alternativo | Nativo, leggero. |
| 12 | Video dell'hero | Home | copertina con pulsante; il video parte solo dal clic e il titolo sparisce durante la riproduzione | main.js | Blocco Video core + stile, oppure blocco "Hero video" | video, copertina, testi | Nessun autoplay. Comandi nativi. |
| 13 | Le voci (video) | Home | tre video su copertina, oggi segnaposti | main.js | Blocco Video core o YouTube incorporato (vedi Cookie) | video, copertine, testi | Se YouTube: caricato solo dopo il consenso. |
| 14 | Menu | Tutte le pagine | menu a pillola fisso; link attivo che segue lo scroll; menu a tendina su mobile | main.js e page.js | Blocco Navigazione core + JS leggero per il link attivo | voci di menu dal Site Editor | Il menu mobile core è accessibile. |
| 15 | Effetti al passaggio del mouse | Pulsanti, chip, card, link, loghi | circa 25 transizioni CSS | CSS | CSS del tema e stili dei blocchi | — | Nessun impatto. |
| 16 | Scorrimento morbido verso le ancore | Tutto il sito | scroll-behavior: smooth | CSS | CSS | — | Disattivato con movimento ridotto. |
| 17 | Player del podcast | Pagina podcast | play e pausa, ±15 s, velocità, barra, tempo | page.js | Blocco Audio core + stile, oppure blocco "Player podcast" | file audio, link alle piattaforme | Il player personalizzato richiede etichette ARIA, quello core è già accessibile. |
| 18 | Video con capitoli | Pagina video | player e lista capitoli che salta al punto | page.js | Blocco Video core + blocco "Capitoli" (IAPI) | capitoli: tempo e titolo | Da tastiera. |
| 19 | "Altri progetti" | Pagine interne | tre card; su mobile scorrimento orizzontale | CSS | Blocco Query Loop core con stile | quanti progetti; criterio | Nativo. |
| 20 | Selettore "immagine prima / dopo" | Pagina articolo | solo per la bozza | page.js | **Da eliminare**: diventa un'opzione del progetto | — | — |
| 21 | Validazione del form | Home, form partner | controllo dei campi lato browser | main.js | Sostituita da Contact Form 7 | — | Messaggi accessibili di CF7. |

**Facoltative.** Le aggiungo solo se le approvi:
- **N1:** comparsa morbida delle sezioni allo scroll. Solo CSS, con `animation-timeline` dove il browser la supporta e nulla dove non la supporta; costo quasi zero.
- **N2:** contatore animato sugli anni o sui numeri dei report.
- **N3:** transizione tra pagine con la View Transitions API, nativa del browser e senza JS.

---

## C. Domande aperte (con proposta consigliata)

### Gruppo 1 — Parametri, obiettivo, pagine, ruoli
1. **Parametri.** Slug `wero` (tema `wero-theme`, plugin `wero-core`); nome "WeRo — Robot Communication Studio"; URL definitivo `https://we-ro.it`. *Consigliato*: questi. Confermi o correggi?
2. **Obiettivo e azione principale.** a) richiesta di partnership dal form *(consigliato: è la CTA "Diventa partner" ripetuta in tutto il sito)*; b) download dei report; c) contatto generico.
3. **Pagine.** Tengo home + pagine progetto (un modello per formato) + Privacy + Cookie policy. Blog o news: a) no, i progetti fanno da contenuto editoriale *(consigliato)*; b) sì, sezione News. Lingue: a) solo italiano, con la struttura pronta per aggiungere l'inglese *(consigliato)*; b) italiano e inglese da subito (servirebbe un plugin multilingua, oggi non ammesso).
4. **Chi modifica.** a) un amministratore tecnico + editor non tecnici *(consigliato)*: gli Editor modificano testi, immagini e contenuti ma non possono cambiare la struttura di header, footer, template e home; b) tutti amministratori.
5. **Modelli dei progetti.** Le quattro pagine interne sono un unico tipo "Progetto" con un campo "Formato" (articolo, articolo complesso, video, podcast) che sceglie il modello. *Consigliato*: sì. In alternativa, quattro tipi di contenuto separati.

### Gruppo 2 — Contenuti strutturati (post type e campi ACF)
1. **Progetto.** Campi:
   - campi ACF: formato, anno, tipo, descrizione breve, icona della sfera, immagine card, cliente o partner, link esterni (pulsanti chip), URL video o file audio, durata, piattaforme di ascolto;
   - persone: un post type "Persona" collegato con il campo Relazione, al posto del Repeater;
   - capitoli del video: un blocco contenitore con un blocco figlio per ogni capitolo;
   - "Mondo" è una tassonomia;
   - il testo resta nei blocchi.

   Ti va bene questo elenco? Manca qualcosa?
2. **Report.** Post type con: anno, mondo (tassonomia), descrizione, file PDF, stato (Pubblicato o In arrivo), pulsante. *Consigliato*: sì.
3. **Partner e collaboratori.** Post type con: logo, tipo (Business o Ricerca), founder sì/no, descrizione, URL. Alimenta sia i loghi founder sia la riga curva. *Consigliato*: sì.
4. **Voci dei progetti.** a) post type "Voce" (video, copertina, descrizione) *(consigliato se ne aggiungerete altre)*; b) semplice pattern con tre blocchi Video.
5. **Mondi.** Tassonomia "Mondo" con campi ACF: immagine, descrizione, colore. Le card della home leggono da lì. *Consigliato*: sì.

### Gruppo 3 — Design e animazioni
1. **Fedeltà.** a) identico all'originale *(consigliato)*; b) piccoli adattamenti permessi se migliorano l'editing.
2. **Font.** Space Grotesk ospitato sul sito (licenza OFL, niente Google Fonts per la privacy). *Consigliato*: sì.
3. **Varianti.** Una style variation scura? a) no *(consigliato)*
4. **Animazioni.** Per ogni voce della tabella in B: tenere, semplificare, sostituire o eliminare. *Consigliato*: tutte "tenere", tranne:
   - n. 1: solo prima visita;
   - n. 17: blocco Audio core con stile;
   - n. 20: eliminare.
5. **Animazioni nuove** N1, N2, N3: quali? *Consigliato*: solo N1.

### Gruppo 4 — Form, email, cookie, immagini
1. **Form.**
   - Form: uno, "Diventa partner", con Nome, Azienda/Ente, Email, Ambito (Ricerca, Business, Comunicazione & creatività), Messaggio e Privacy obbligatoria.
   - Destinatario: contact@we-ro.it?
   - Messaggio di conferma: "Grazie! Ti ricontatteremo presto."
   - Altri form: per esempio "Avvisami" sui report in arrivo.
2. **Email.**
   - Mittente: noreply@we-ro.it, con nome "WeRo".
   - Il dominio we-ro.it va verificato su Site Mailer.
   - *Consigliato*: sì.
3. **Cookie.** Servizi esterni previsti:
   - YouTube o Vimeo per i video;
   - Google Analytics 4 o Tag Manager;
   - LinkedIn Insight;
   - nessuno.

   Esistono già le pagine Privacy e Cookie policy, o vanno create come segnaposto?
4. **Immagini.** per orausa quelli poi devo poterle sostituire in futuro
5. **File multimediali.** PDF dei report libreria multimediale, video dell'hero e delle voci, audio del podcast: TBD fai una cosa che possa accogliere link diversi

### Gruppo 5 — SEO, agenti AI, hosting
1. **Online.** Il sito è già online? Se sì, quali URL vanno mantenuti o reindirizzati? Proposta di slug:
   - progetti: /progetti/nome-progetto/;
   - report: /report/nome/.
2. **Parole chiave e dati aziendali per lo schema.** Servono parole chiave principali, ragione sociale, P.IVA, indirizzo e sede. È un'attività locale? *Consigliato*: Organization, non LocalBusiness.
3. **Search Console.** Va collegata? Chi ha l'accesso?
4. **Agenti AI.** a) consentire i crawler AI *(consigliato: visibilità del brand)*; b) bloccare quelli di addestramento (GPTBot, CCBot, Google-Extended) e consentire quelli di ricerca. Abilities proposte, tutte in sola lettura:
   - wero/get-projects
   - wero/get-reports
   - wero/get-partners
   - wero/get-contact-info
5. **Hosting.** Fornitore, versione PHP (serve la 8.2 o superiore), staging sì/no, chi aggiorna plugin e WordPress.

---

## D. Decisioni prese

Legenda: **R** = risposta tua; **P** = mia proposta applicata perché la domanda è rimasta senza risposta (si può cambiare in qualsiasi momento prima della Fase 4).

### Parametri e obiettivi
| # | Decisione | Fonte |
|---|---|---|
| D1 | Slug `wero`, tema `wero-theme`, plugin `wero-core` | R |
| D2 | Nome sito "WeRo Robot Communication Studio"; lingua it_IT; URL definitivo `https://we-ro.it` | R |
| D3 | Azioni del visitatore, in ordine di priorità: 1) richiesta di partnership dal form, 2) download dei report, 3) contatto generico (email nel footer) | R, ordine P |
| D4 | Niente blog o news: i progetti sono il contenuto editoriale | R |
| D5 | Solo italiano; testi traducibili e struttura pronta per l'inglese, senza plugin multilingua | R |
| D6 | Ruoli: amministratore tecnico + Editor. Gli Editor modificano testi, immagini e contenuti; header, footer, template e struttura della home sono bloccati per loro | R |

### Contenuti strutturati
| # | Decisione | Fonte |
|---|---|---|
| D7 | **Progetti**: un post type "Progetto" con **quattro template a blocchi diversi**, uno per formato: articolo, articolo complesso, video, podcast. Ogni template ha i suoi blocchi di testo, modificabili e personalizzabili. Alla creazione di un progetto l'editor sceglie il modello di partenza; il template si può cambiare dal pannello laterale. | R |
| D8 | Campi ACF del progetto (dati strutturati, non il testo): anno, tipo, descrizione breve, icona della sfera, immagine card, cliente o partner, link esterni, URL video o audio, durata, piattaforme di ascolto. Il testo editoriale resta nei blocchi. | P |
| D9 | **Persone** (video, podcast): post type "Persona" collegato al progetto con il campo Relazione | P |
| D10 | **Capitoli del video**: blocco contenitore "Capitoli" con un blocco figlio per ogni capitolo (tempo + titolo) | P |
| D11 | **Mondo**: tassonomia con campi ACF immagine, descrizione, colore; le card "I tre mondi" e i filtri leggono da lì | P |
| D12 | **Report**: post type con anno, mondo, descrizione, PDF dalla Libreria media, stato (Pubblicato / In arrivo), testo del pulsante | P + R (PDF in libreria) |
| D13 | **Partner e collaboratori**: post type con logo, tipo (Business / Ricerca), founder sì/no, descrizione, URL; alimenta i loghi founder e la riga curva | P |
| D14 | **Voci dei progetti**: post type "Voce" con video, copertina, descrizione | P |
| D15 | **Video e audio**: i campi accettano qualsiasi link — file della Libreria media, YouTube, Vimeo, Spotify, SoundCloud, Apple Podcasts — e l'output usa il blocco Incorpora o Video/Audio core in base al link. I servizi esterni partono solo dopo il consenso cookie. | R |

### Design e animazioni
| # | Decisione | Fonte |
|---|---|---|
| D16 | Fedeltà: identico all'originale; ogni differenza inevitabile viene elencata | P |
| D17 | Space Grotesk ospitato sul sito (niente Google Fonts) | P |
| D18 | Nessuna style variation scura | R |
| D19 | Animazioni: tutte tenute come nella tabella B, tranne: n. 1 Intro solo alla prima visita della sessione; n. 17 player del podcast con blocco Audio core con stile (o incorporato, vedi D15); n. 20 selettore "prima / dopo" eliminato | P |
| D20 | Animazioni nuove: **N1** (comparsa morbida delle sezioni allo scroll, solo CSS con `animation-timeline`) e **N3** (transizione tra pagine con la View Transitions API, solo CSS, menu fermo durante il passaggio). Entrambe spente con “movimento ridotto” e disattivabili da Impostazioni › WeRo › Animazioni. | R (N3 aggiunta il 23/11) |

### Form, email, cookie, immagini
| # | Decisione | Fonte |
|---|---|---|
| D21 | Form "Diventa partner" in Contact Form 7, stessi campi dell'originale + privacy obbligatoria; destinatario contact@we-ro.it; conferma "Grazie! Ti ricontatteremo presto."; nessun altro form per ora ("Avvisami" sui report in arrivo porta al form) | P |
| D22 | Email inviate da noreply@we-ro.it, nome "WeRo", tramite Site Mailer (dominio da verificare sul sito online) | P |
| D23 | Cookie: servizi presenti, come da cookie policy: **YouTube** (video incorporati) e **Google Analytics 4**. Cookie Consent con le categorie della policy: Necessari, Funzionali (YouTube), Analitici (GA4, YouTube), Pubblicitari (YouTube), Non categorizzati. YouTube e GA4 caricati con wp_enqueue_script / blocco Incorpora e bloccati fino al consenso. | R |
| D24 | Immagini: si usano quelle attuali, tutte caricate nella Libreria media e sostituibili dall'editor. Image Optimizer converte in WebP. | R + P (WebP) |

### SEO, agenti AI, hosting
| # | Decisione | Fonte |
|---|---|---|
| D25 | Il sito nuovo non sostituisce URL esistenti (nessun redirect). Slug: `/progetti/nome-progetto/`, `/report/nome-report/`. | P |
| D26 | Schema: Organization "WeRo", **joint venture tra 6.14 S.r.l. e Omnicom Advertising** (founder / parentOrganization), non attività locale. Dati di 6.14 dalla privacy: 6.14 S.r.l., Via Don Bosco 31, 20139 Milano, C.F./P.I. 07376100967. Ragione sociale e P.IVA propri di WeRo, se esistono, restano campi da compilare nelle Impostazioni sito. | R |
| D30 | Privacy e Cookie policy: **testi di 6.14 S.r.l.** forniti dal cliente (titolare del trattamento 6.14 S.r.l., info@6punto14.com), salvati in `contenuti/` e importati come pagine WordPress nella Fase 4, linkati nel footer, nel form e nel banner. | R |
| D31 | Testi legali: approvate le 5 correzioni elencate sotto ("Da verificare nei testi legali"), già applicate in `contenuti/cookie-policy.md`. Emoji di WordPress disattivati dal plugin. | R (confermate il 23/11/2026) |
| D32 | WeRo **non ha ragione sociale né P.IVA proprie**: nello schema WeRo è un'Organization senza vatID, con founder 6.14 S.r.l. (dati completi) e Omnicom Advertising. Il footer non mostra una P.IVA di WeRo. | R |
| D33 | Google Analytics 4: **nessun ID inserito da noi**. Il cliente installerà il plugin ufficiale di Google (Site Kit by Google). Note: è un plugin fuori dall'elenco ammesso, aggiunto su indicazione del cliente; va configurato in modo che GA4 parta solo dopo il consenso (Consent Mode di Site Kit + categoria "Analitici" di Cookie Consent). Il plugin custom non aggiunge codice di Analytics. | R |
| D34 | Archivio `/progetti/` con la griglia di tutti i progetti, raggiunto dal link "Tutti i progetti" | R |
| D35 | Gli 8 progetti senza pagina nell'originale vengono creati come **bozze** con testo segnaposto | R |
| D36 | Cookie policy in **noindex** (Yoast) | R |
| D37 | **Requisiti reali del server: WordPress 7.1+ e PHP 8.3+.** Le versioni attuali dei plugin ammessi lo richiedono: Contact Form 7 6.2.5 vuole WP 7.1 e PHP 8.3, Yoast SEO 28.9 vuole WP 7.0. Tema e plugin custom restano compatibili con WP 6.9 / PHP 8.2 come da specifica. | Verifica Fase 1 |
| D38 | **Riga dei loghi aggiornabile ed estendibile**: ogni logo è un Partner (post type). Per aggiungere un logo basta creare un Partner e caricare il logo: la "tessera" con l'etichetta Business / Ricerca viene generata dal blocco, non serve più preparare un SVG a parte. Ordine, tipo e attivo/nascosto si cambiano dal Partner; velocità e curvatura dalle impostazioni del blocco. Nessun limite al numero di loghi. | R |
| D39 | Il campo “Formato” del progetto non serve: il formato è il **template** scelto nel pannello laterale (4 template). Il campo “Tipo” (Evento, Video, Report…) resta per le etichette. | Fase 3 |
| D40 | Video e audio esterni (YouTube, Vimeo, Spotify, SoundCloud, Apple Podcasts) si caricano **solo al clic** del visitatore (“facciata”): nessuna richiesta a terze parti all'apertura della pagina. Il collegamento con le categorie di Cookie Consent si verifica nella Fase 6. | Fase 3 |
| D41 | Loghi SVG (6.14, Italdesign, Eni Joule, Rehabilia, Alto Robotics, logo WeRo): WordPress non accetta SVG nella Libreria media, quindi vengono convertiti in PNG ad alta risoluzione. | Fase 3 |
| D42 | Gli 8 progetti senza pagina sono **pubblicati** con testo segnaposto (avviso in testa alla pagina), da completare prima della messa online: la sfera resta come l'originale (sostituisce D35). | R |
| D43 | **Nessuna persona segnaposto**: il blocco Persone (con il suo titolo “Intervengono”/“Ospiti”) resta nascosto finché non si collegano persone vere. | R |
| D44 | Modulo “Diventa partner”: **due email HTML con lo stile WeRo**. Mail 1 a contact@we-ro.it (riepilogo, pulsante “Rispondi”, Reply-To a chi scrive). Mail 2 di conferma a chi scrive (ringraziamento, riepilogo, link ai progetti, Reply-To contact@we-ro.it). Mittente WeRo <noreply@we-ro.it>. Modelli in `contenuti/email/`. | R |
| D45 | Pagine progetto: nella testata **“Torna alla home”** oltre a “Tutti i progetti”. | R |
| D46 | Pagina `/progetti/` **uguale alla vista a griglia** della home: stesse card (immagine 4:3, bollino del mondo, tipo e anno, titolo), stesso ordine della sfera, tutti i progetti in una pagina; i progetti senza immagine hanno la copertina di riserva col colore del mondo. | R |
| D47 | ~~Voci come tipo di contenuto~~ → sostituita da D51. | — |
| D48 | Yoast: rappresentazione “Organizzazione” WeRo con logo; titoli “Titolo — WeRo” come l'originale; meta description dei progetti dal riassunto; breadcrumb (blocco di Yoast) nelle pagine interne; llms.txt attivo; robots.txt senza blocchi (crawler AI consentiti, D28). | Fase 5 |
| D49 | Schema esteso dal plugin (filtri di Yoast): Organization con descrizione, email, LinkedIn e founder (6.14 S.r.l. con indirizzo e P.IVA, Omnicom Advertising); VideoObject per i progetti video e per le Voci, PodcastEpisode per i podcast, solo quando il video o l'audio c'è davvero. | Fase 5 |
| D50 | Agenti AI: REST pubblica in sola lettura `wero/v1` (projects, reports, partners, contact; `voices` tolta con D53) e 4 abilities (`wero/get-projects`, `get-reports`, `get-partners`, `get-contact-info`). Le abilities su `/wp-json/wp-abilities/v1` richiedono un utente collegato (regola di WordPress); sono pronte per l'MCP Adapter, non installato. | Fase 5 |
| D51 | ~~Voci nella Home~~ (per le Voci sostituita da D53; per i Mondi resta valida). **Mondi e Voci si modificano direttamente nella Home con i blocchi Gutenberg**, niente sezioni a parte: ogni sezione è un Gruppo che contiene un Gruppo per card (Immagine, Titolo, Testo, Pulsante/link). Gruppo con stile “Mondi” (card a griglia, a scorrimento con puntini su mobile; il pulsante “Vedi i progetti” ha il link `#progetti?mondo=slug` e filtra la sfera) e stile “Voci” (la copertina è un’Immagine collegata al video: YouTube, Vimeo o file; il video parte al clic). Rimossi il tipo di contenuto Voce e i blocchi wero/worlds e wero/voices; la tassonomia Mondo resta per bollini e filtri dei progetti (campi: stile, ordine). Schema VideoObject e REST `wero/v1/voices` leggono le card della Home. | R |
| D52 | Contrasto del magenta `#ff00c7` sui testi piccoli (3,3:1, sotto il minimo AA di 4,5:1): **resta com'è**, fedele all'originale. Riguarda il bollino “Language & Design” e l'etichetta nelle card della sfera. Si può cambiare in qualsiasi momento da Impostazioni › WeRo › Aspetto o dagli Stili globali. | R (23/11/2026) |
| D53 | **Voci tolte dalla Home**: le tre Voci sono diventate **progetti con template Video** (titolo, copertina e descrizione di prima; mondo Conversation & Events; il link al video va nel campo del progetto). Tolti la sezione e la voce di menu “Voci”, lo stile di Gruppo “Voci”, la REST `wero/v1/voices` e il VideoObject delle Voci in Home (resta quello dei progetti video). | R (25/11/2026) |
| D54 | **Download dei report tramite modulo**: il pulsante della timeline porta alla pagina **Scarica i report** (`/scarica-report/?report=Titolo`) con il report già spuntato. Modulo CF7 “Scarica i report”: Nome*, Email*, Azienda / Ente, Ruolo, report da scaricare* (si possono spuntare anche gli altri), privacy*. L'elenco dei report si compila da solo con i Report **pubblicati con un PDF** (`data:wero_reports`): i report futuri compaiono senza toccare il modulo. Dopo l'invio: link ai PDF **sulla pagina e per email** a chi compila (Mail 2, `[_wero_report_links]`); **email al gestore** (contact@we-ro.it) con chi ha scaricato e cosa (Mail 1). REST e abilities danno il link alla pagina di download, non il PDF. Nota: i PDF restano file pubblici della Libreria media; il modulo li fa trovare, non li protegge. PDF attuali: segnaposto. | R (25/11/2026) |
| D55 | **Foto a scorrimento nei progetti**: galleria “A scorrimento” (la stessa della home) in fondo al contenuto di partenza di tutti e 4 i template; vuota non compare sul sito. Pattern “Progetto – Foto a scorrimento” per aggiungerla ai progetti esistenti. | R (25/11/2026) |
| D56 | **Progetti video: il video è la prima cosa della pagina**, sopra breadcrumb, titolo e testo. | R (25/11/2026) |
| D57 | **Tutte le foto aggiornabili facilmente** dalla Libreria media: immagini dei blocchi (home, pagine, progetti), immagine in evidenza (progetti, report, persone), logo dei partner, copertina dell'hero e foto delle gallerie. I loghi del footer sono blocchi Immagine nel Site Editor (Aspetto › Editor › Footer › Sostituisci). | R (25/11/2026) |
| D27 | Search Console non gestita in questo progetto | R (domanda tolta) |
| D28 | Crawler AI consentiti; robots.txt senza blocchi. Abilities in sola lettura: wero/get-projects, wero/get-reports, wero/get-partners, wero/get-contact-info | P |
| D29 | Hosting non ancora noto: requisiti minimi documentati (WordPress 6.9+, PHP 8.2+); consigliato uno staging | P |

### Da verificare nei testi legali (decide il cliente, non li modifico senza conferma)
1. La cookie policy cita "i prodotti e i servizi di **Nove Alpi**": sembra un residuo di un altro sito. Proposta: "di WeRo".
2. `cookieyes-consent` è il cookie di CookieYes; qui il banner è **Cookie Consent di Elementor**, che usa un suo cookie. Proposta: sostituire la riga con il cookie reale dopo la scansione del plugin sul sito online.
3. `wpEmojiSettingsSupports`: proposta di disattivare gli emoji di WordPress (script inutile per questo sito, più leggerezza) e togliere la riga.
4. Link ai browser: quello di Internet Explorer (browser dismesso) e quello di Safari (punta alla privacy Apple, non alla gestione dei cookie). Proposta: aggiornarli a Edge e alla guida Safari.
5. La tabella finale si genera dalla scansione di Cookie Consent sul sito online, quando YouTube e GA4 saranno attivi.

### Ancora da ricevere (non bloccano le prossime fasi)
- URL reale della pagina LinkedIn.
- Parole chiave principali.
- PDF dei report, video e audio definitivi.
