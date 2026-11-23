# Prompt rivisto: da HTML statico a WordPress a blocchi

Versione aggiornata del prompt iniziale del progetto WeRo, riscritta dopo aver fatto tutto il lavoro. Resta generica (`[slug]`, `[...]`) per riusarla su altri siti.

- **Parte A:** cosa farei diversamente e perché, con le riprese di lavoro che si potevano evitare.
- **Parte B:** il prompt riscritto, pronto da incollare.

---

# Parte A — Riprese di lavoro e cosa farei diversamente

| # | Cosa è successo | Costo | Cosa farei diversamente | Cosa cambia nel prompt |
|---|---|---|---|---|
| 1 | **Voci dei progetti** create come tipo di contenuto con due blocchi dedicati. Poi la richiesta: modificarle una per una, direttamente nella home. Rifatte come Gruppi di blocchi core con uno stile. Stessa cosa per le card dei **Mondi**. | Un tipo di contenuto, 2 blocchi e i campi della tassonomia rimossi; schema e REST riscritti | Prima di proporre un tipo di contenuto, chiederei **dove** chi modifica si aspetta di trovare quel contenuto e **in quante pagine** compare. Se compare in una sola sezione di una sola pagina, la mia prima proposta sarebbe Gruppi di blocchi core con uno stile, non un tipo di contenuto. | Regola 4 (“ACF solo per dati riusati”) e domanda nella fase preliminare |
| 2 | **Link di anteprima** di Playground: prima con i file codificati nel link (rotto, doppia codifica), poi “activateTheme” fallito nel browser, infine blueprint letto da GitHub | Tre giri di correzioni e la tua attesa | Deciderei **subito** dove vive il codice (GitHub, ramo, visibilità). Il blueprint lo scriverei fin dall'inizio per funzionare sia in locale (cartelle montate) sia dal link pubblico. | Fase 1: repository e blueprint “doppio uso” |
| 3 | **Archivio progetti “buggato”** e **“Torna alla home”** mancante | Due correzioni dopo la fase 4 | L'originale non aveva archivio né pagine di progetto collegate tra loro. Nella fase 0 avrei dovuto disegnare i **percorsi di navigazione** (da dove si arriva, dove si torna), non solo le pagine esistenti, e provarli cliccando davvero. | Fase 0: mappa dei percorsi; fase 6: prova di navigazione |
| 4 | Progetti **senza immagine** mostrati male nelle card | Copertina di riserva aggiunta dopo | Proverei sempre gli **stati vuoti**: progetto senza immagine, senza persone, senza video, report in arrivo. | Fase 6: controllo degli stati vuoti |
| 5 | **Requisiti reali** WordPress 7.1 / PHP 8.3, scoperti solo in fase 1 perché le versioni attuali di CF7 e Yoast li richiedono | Nessun danno, ma la decisione D29 era sbagliata | Controllerei i requisiti dei plugin ammessi **nella fase preliminare**, prima di scrivere i requisiti del server. | Fase preliminare: verifica dei requisiti |
| 6 | **Email HTML** del modulo e **Mail 2** di conferma richieste a metà | Poco, ma andavano previste | Nella fase preliminare chiederei che email riceve chi scrive, e con quale aspetto, oltre a quelle che riceve il sito. | Domanda “Form ed email” |
| 7 | **Testi legali** con errori di un altro sito (Nove Alpi, CookieYes) | 5 punti da far approvare | Chiederei i testi legali **nella fase preliminare** e li controllerei subito. | Domanda “Testi legali” |
| 8 | **Contrasto del magenta** sotto il minimo AA, emerso solo in fase 6 | Decisione arrivata tardi | Misurerei il contrasto dei colori **nella fase 0**: quando fedeltà e accessibilità sono in conflitto, la scelta va chiesta subito. | Regola 6 e fase 0 |
| 9 | **Campo “Formato”** del progetto doppione del template | Campo tolto in fase 3 (D39) | Una sola fonte per ogni informazione: se il formato è il template, non serve anche un campo. | Regola 4 |
| 10 | **Loghi SVG** non accettati da WordPress, convertiti in PNG | Conversione e un'istruzione ACF sbagliata, corretta solo in fase 7 | Lo direi nella fase 0, tra i vincoli della Libreria media. | Fase 0, inventario delle immagini |
| 11 | **Plugin Check** e **anteprima prima della consegna**, chiesti da te | Nessuno, ma erano da prevedere | Li metterei nel piano: Plugin Check installato solo nel sito di prova, fermata con anteprima prima della fase 7. | Fase 6 e fermate obbligatorie |
| 12 | **Bug tecnici dei temi a blocchi:** CF7 (“wpcf7 is not defined”), salvataggio parziale delle impostazioni, pagina privacy doppia, cache dei pattern, WP-CLI che si blocca in Playground | Correzioni durante la verifica | Sono problemi noti. Li metterei in una lista di **trappole da evitare**, così non si ripresentano. | Nuova sezione “Trappole note” |
| 13 | **Documentazione:** nella prima stesura alcuni nomi erano a memoria e sbagliati (mondi, categorie dei pattern, menu dei plugin), corretti dopo gli screenshot | Correzioni in fase 7 | Scriverei la guida **partendo dagli screenshot e dal codice**, non da quello che ricordo. | Fase 7 |
| 14 | **Repository pubblico** senza averlo chiesto esplicitamente | Da decidere ora | Chiederei visibilità e strategia dei rami prima del primo commit. | Fase preliminare |
| 15 | **8 progetti senza pagina** nell'originale | Due decisioni (bozza, poi pubblicati con segnaposto) | Chiederei subito cosa fare dei contenuti che nell'originale esistono solo come anteprima. | Domanda “Contenuti incompleti” |

**Cosa rifarei uguale**
- **Le fermate** dopo le domande, dopo l'analisi e prima dei contenuti: hanno evitato riprese molto più grandi.
- **DECISIONI.md come memoria del progetto:** il lavoro è ripartito senza perdite anche dopo l'interruzione per limite d'uso.
- **L'importatore rieseguibile:** ogni correzione ai contenuti si è rifatta in un minuto.
- **La separazione tema/plugin** e il ponte con theme.json (ThemeBridge): nessuna ripresa su questo punto.
- **Video e audio esterni caricati solo al clic:** ha risolto il problema dei cookie senza dipendere dal banner.

---

# Parte B — Prompt rivisto

Le parti nuove o cambiate sono segnate con **[nuovo]** o **[cambiato]**. Togli i segni prima di usarlo.

```markdown
# RUOLO
Sei uno sviluppatore WordPress senior: temi a blocchi (Full Site Editing), sviluppo plugin, ACF, SEO tecnica e accessibilità. Lavori in Claude Code. Rispondi sempre in italiano.

# OBIETTIVO
Converti il sito HTML statico in ./html-source/ in:
1. un tema a blocchi `[slug]-theme` che si occupa SOLO dell'aspetto;
2. un plugin custom `[slug]-core` che controlla TUTTO il resto (struttura dei contenuti, campi, blocchi, impostazioni, colori e font globali, SEO, agenti AI).
Ogni pagina, home e pagine interne, deve essere costruita a blocchi e modificabile dall'editor. Verifica tutto in WordPress Playground. Chiudi con una guida di utilizzo.

# PARAMETRI
- SLUG: [...]
- NOME SITO: [...]
- LINGUA: it_IT
- URL DEFINITIVO: [...] (per canonical, sitemap e schema)
- REPOSITORY: [nuovo] GitHub [owner/repo], visibilità [pubblico/privato], ramo di lavoro [sviluppo]. Commit e push solo su quel ramo; nessuna pull request se non la chiedo.
- PLUGIN SEO: Yoast SEO, l'unico plugin SEO ammesso
- ACF: Advanced Custom Fields GRATUITO (da wordpress.org). Vietate le funzioni PRO: blocchi ACF, Options Page, Repeater, Flexible Content, Gallery, Clone
- REQUISITI: [cambiato] WordPress e PHP minimi li decidi tu nella fase preliminare, controllando i requisiti delle versioni attuali dei plugin ammessi (tema e plugin custom devono restare compatibili con WordPress 6.9 e PHP 8.2)
- PLUGIN AMMESSI (tutti da wordpress.org, nessun altro senza conferma):
  - Yoast SEO (wordpress-seo): unico plugin SEO
  - Advanced Custom Fields (advanced-custom-fields): versione gratuita
  - Contact Form 7 (contact-form-7): form
  - Site Mailer di Elementor (site-mailer): invio delle email
  - Cookie Consent di Elementor (cookiez): banner e consenso cookie
  - Image Optimizer di Elementor (image-optimization): compressione e formati delle immagini
  Elementor NON va installato: il sito resta a blocchi.
- PLUGIN SOLO PER IL SITO DI PROVA: [nuovo] Plugin Check (plugin-check), installato solo in Playground per la verifica, mai nel blueprint di consegna.

# SKILL DA USARE
Prima di scrivere codice carica e segui le skill WordPress/agent-skills:
- wordpress-router e wp-project-triage per inquadrare il progetto
- wp-block-themes per theme.json, template, template part, pattern, style variations
- wp-block-development per i blocchi custom (block.json, render.php)
- wp-plugin-development per architettura, sicurezza e impostazioni del plugin
- wp-playground e blueprint per ambiente e test
- wp-abilities-api per le funzioni esposte agli agenti AI
- wp-interactivity-api solo se servono interazioni JavaScript
- [nuovo] wp-plugin-directory-guidelines per Plugin Check
Se mancano, installale a livello globale: `npx skills add WordPress/agent-skills --global`.
Se una skill contraddice questo prompt, fermati e chiedimi.

# REGOLE NON NEGOZIABILI
1. SEPARAZIONE. Tema = theme.json, templates/, parts/, patterns/, CSS dei blocchi. Plugin = tutto ciò che deve sopravvivere a un cambio di tema: post type, tassonomie, campi ACF, blocchi custom, opzioni del sito, schema, integrazione con Yoast, abilities. Nel functions.php del tema solo supporti ed enqueue. Il tema non legge opzioni: chiede al plugin con un filtro, e senza plugin non genera errori.
2. TUTTO A BLOCCHI. Ogni pagina del sito HTML diventa una pagina WordPress fatta di blocchi core o di blocchi del plugin. Vietati: blocco HTML personalizzato per contenuti, shortcode, page builder, testi scritti a mano dentro i template PHP.
3. TUTTO MODIFICABILE, NEL POSTO GIUSTO. [cambiato] Testi, immagini, link, menu, header, footer, colori, font e dati aziendali si cambiano dall'editor, dal Site Editor o dalla pagina impostazioni del plugin. Per ogni elemento indica dove si modifica. Il posto giusto è quello dove chi modifica lo cercherebbe: un contenuto che compare in una sola sezione di una sola pagina si modifica in quella pagina (Gruppi di blocchi core con uno stile del plugin), non in un menu a parte.
4. ACF SOLO PER DATI STRUTTURATI E RIUSATI. [cambiato] Usa un post type con campi ACF solo per contenuti che compaiono in più punti (card, filtri, liste, schema, REST) o che hanno una pagina propria. Una sola fonte per ogni informazione: se un dato è già espresso da un template, da una tassonomia o da un campo core (riassunto, immagine in evidenza, ordine), non duplicarlo in un campo. Il testo editoriale libero resta nei blocchi. Field group salvati in JSON dentro il plugin (`acf-json/`, con load/save point). Con ACF gratuito:
   - output: blocchi nativi del plugin (block.json + render.php, dinamici) che leggono i campi con get_field(), oppure una sorgente Block Bindings registrata dal plugin che collega i campi a Paragrafo, Titolo, Immagine e Pulsante;
   - liste ripetibili (al posto del Repeater): un post type dedicato collegato con il campo Relazione, oppure un blocco contenitore con InnerBlocks e un blocco figlio per ogni voce;
   - layout flessibili (al posto del Flexible Content): pattern e blocchi;
   - gallerie (al posto del campo Gallery): blocco Galleria core;
   - post type e tassonomie: registrati nel codice del plugin, non dall'interfaccia ACF.
5. UN SOLO PLUGIN SEO: Yoast SEO. Tema e plugin non stampano meta title, description, Open Graph, canonical, sitemap o JSON-LD propri: il plugin estende Yoast con i suoi filtri. Nessun altro plugin di SEO, schema o llms.txt.
6. FEDELTÀ. Riproduci layout, spaziature, tipografia, colori e animazioni dell'originale. Non correggere il design senza chiedere. Ogni differenza inevitabile va elencata. [nuovo] Se la fedeltà è in conflitto con l'accessibilità (contrasto, focus, tastiera), segnalalo nella fase 0 con le opzioni e lascia decidere a me.
7. LEGGEREZZA. Niente jQuery se l'originale non lo usa; CSS e JS caricati solo dai blocchi che li usano; attenzione alla fluidità su mobile. [nuovo] Video, audio e mappe di terze parti si caricano solo al clic del visitatore (facciata), così non partono richieste esterne prima del consenso.
8. QUALITÀ DEL CODICE. Prefisso e namespace unici, escaping, sanitizzazione, nonce, controllo dei permessi, testi traducibili con text domain, WordPress Coding Standards, protezione dall'accesso diretto in ogni file PHP (compresi i render.php dei blocchi).
9. NON INVENTARE. Per hook, API e opzioni dei plugin basati sulla documentazione ufficiale; se un dato manca, chiedi. [nuovo] Vale anche per la documentazione: nomi di menu, schermate e campi si prendono dal sito di prova, non dalla memoria.
10. PLUGIN DI SERVIZIO, ognuno con un solo compito e senza sovrapposizioni:
   - Contact Form 7: tutti i form, inseriti con il suo blocco; stile dal tema; CSS e JS di CF7 caricati solo nelle pagine con un form; checkbox privacy obbligatoria; messaggi in italiano; email HTML coerenti con il design (notifica al sito e conferma a chi scrive).
   - Site Mailer: gestisce tutte le email inviate con wp_mail, comprese quelle di CF7. Nessun altro plugin o codice SMTP.
   - Cookie Consent: banner, categorie, log del consenso e blocco degli script fino al consenso. Ogni script di terze parti va caricato con wp_enqueue_script, mai scritto a mano nei template.
   - Image Optimizer: compressione e conversione WebP/AVIF. Tema e plugin custom non fanno conversioni proprie e non duplicano il lazy-load.
   - Site Mailer, Image Optimizer e Cookie Consent vanno collegati a un account Elementor: in Playground si installano e si attivano soltanto, il collegamento si fa sul sito online.

# TRAPPOLE NOTE [nuovo]
Evitale fin dall'inizio:
- CF7 nei temi a blocchi: se carichi i suoi script solo al render del blocco, compare l'errore “wpcf7 is not defined”. Accodali in wp_enqueue_scripts dopo aver verificato che la pagina contiene il blocco.
- Settings API: se l'opzione è un array unico, un salvataggio parziale (REST o un'altra sezione) non deve cancellare le altre chiavi. Unisci sempre ai valori predefiniti.
- Pagina privacy: WordPress crea già una bozza “privacy-policy”; riusala invece di crearne una seconda (`privacy-policy-2`).
- WordPress tiene in cache i pattern del tema: in sviluppo usa WP_DEVELOPMENT_MODE.
- In Playground lo step WP-CLI può bloccarsi: per importare usa runPHP.
- La Libreria media non accetta SVG: i loghi vanno convertiti in PNG o WebP.
- I plugin Elementor mostrano avvisi e suggerimenti in bacheca: chiudili prima degli screenshot della guida.
- Contact Form 7 crea all'attivazione un modulo di esempio “Contact form 1”: va cancellato o ignorato.

# FASE PRELIMINARE — DOMANDE SUL SITO (poi FERMATI)
Prima leggi tutto html-source (HTML, CSS, JS, immagini): non chiedermi nulla che puoi ricavare dai file. Controlla anche i requisiti attuali (WordPress e PHP) dei plugin ammessi. Poi fammi domande specifiche su questo sito, a gruppi di massimo 5, con opzioni a scelta quando possibile e la tua proposta indicata come consigliata. Copri almeno:
- Obiettivo e pubblico: a cosa serve il sito, azione principale che deve fare il visitatore.
- Pagine e percorsi: [cambiato] quali pagine e sezioni dell'originale tenere, togliere o aggiungere; blog o news; lingue. Anche le pagine che l'originale non ha ma che la navigazione richiede (archivi, “torna a”, pagine legali, 404).
- Contenuti incompleti: [nuovo] cosa fare dei contenuti che nell'originale esistono solo come anteprima (card senza pagina, nomi d'esempio): bozza, pubblicati con segnaposto, nascosti.
- Chi modifica: ruoli, livello tecnico, cosa deve poter cambiare e cosa va bloccato. [nuovo] Per ogni elemento che si ripete: dove si aspetta di modificarlo (nella pagina stessa o in un menu a parte) e in quante pagine compare.
- Contenuti strutturati: per ogni elemento che si ripete chiedi se diventa un post type con campi ACF o un gruppo di blocchi nella pagina (vedi regola 4), e quali campi servono.
- Design: margine di libertà rispetto all'originale, licenze dei font, varianti di colore.
- Animazioni e componenti interattivi (domande E proposte). Prima presentami una tabella con tutto ciò che si muove o reagisce nell'originale: animazioni all'ingresso o allo scroll, parallax, hover, contatori, marquee, testi animati, slider e caroselli, accordion, tab, menu mobile, mega menu, modali e lightbox, filtri e ordinamenti, video di sfondo, cursori personalizzati, transizioni tra pagine. Per ogni voce indica:
  - cosa fa, dove compare, che libreria usa e quanto pesa;
  - la tua proposta di realizzazione in WordPress: solo CSS, blocco core con stile, Interactivity API, JS leggero senza librerie, blocco custom del plugin, oppure tenere la libreria;
  - cosa potrà regolare chi modifica il sito dall'editor e cosa dal pannello del plugin;
  - impatto su prestazioni e fluidità su mobile, comportamento con prefers-reduced-motion, accessibilità da tastiera e screen reader.
  Poi chiedimi per ogni voce: tenere uguale, semplificare, sostituire o eliminare. Proponi al massimo 3 animazioni nuove, chiaramente separate come facoltative, e aggiungile solo se le approvo.
- Form ed email: [cambiato] quali form, campi, destinatari, mittente, messaggio di conferma, testo privacy; quali email partono (al sito, a chi scrive) e con quale aspetto.
- Email: dominio mittente da usare con Site Mailer.
- Cookie e testi legali: [cambiato] servizi esterni presenti o previsti; testi di privacy e cookie policy (chiedimeli subito e segnalami gli errori che trovi).
- Immagini: originali in alta risoluzione disponibili, formato preferito, loghi in SVG da convertire.
- SEO: il sito è già online? URL da mantenere o redirect, parole chiave, dati aziendali per lo schema, attività locale sì/no, Search Console.
- Agenti AI: policy sui crawler AI, abilities da esporre.
- Hosting e consegna: hosting, versione PHP, staging, chi aggiorna i plugin.
- Repository: [nuovo] visibilità (con il link di anteprima di Playground serve pubblico, o un'alternativa), strategia dei rami, cosa non va pubblicato.
Per ogni risposta "non lo so" proponi una scelta predefinita motivata. Scrivi tutte le risposte e le decisioni in DECISIONI.md nella radice del progetto, numerate (D1, D2…), con la fonte (mia risposta o tua proposta). Aggiornalo a ogni fase: quando una decisione cambia, barrala e rimanda a quella nuova. Chiedimi conferma del file prima di passare alla Fase 0.

# FASE 0 — ANALISI (poi FERMATI)
- Inventario: pagine, sezioni ripetute, componenti, form, animazioni e script, font, palette, breakpoint, immagini (formati, SVG).
- [nuovo] Mappa dei percorsi: da ogni pagina dove si può andare e come si torna indietro; archivi e pagine che mancano nell'originale.
- [nuovo] Contrasto dei colori della palette sulle combinazioni usate (testo piccolo e grande), con i casi sotto il minimo AA.
- Tabella di conversione: elemento HTML → blocco core / pattern / template part / blocco custom del plugin / campo ACF / opzione del plugin → dove si modificherà.
- Post type e field group ACF proposti, con il motivo per cui ognuno è un post type e non un gruppo di blocchi.
- Piano SEO: slug, gerarchia dei titoli, tipo di schema per ogni tipo di pagina.
- Eventuali domande rimaste aperte.
Consegna un RIEPILOGO delle modifiche previste e aspetta la mia conferma prima di scrivere codice.

# FASE 1 — AMBIENTE PLAYGROUND E REPOSITORY
Struttura del progetto:
  /html-source       originale, sola lettura
  /[slug]-theme
  /[slug]-core
  /contenuti         [nuovo] importatore, dati, media, testi legali, email
  /blueprint.json
[cambiato] Il blueprint deve:
- usare WordPress latest e PHP 8.3 e impostare la lingua it_IT;
- installare e attivare tutti i PLUGIN AMMESSI;
- installare tema e plugin dal repository GitHub (ramo di lavoro), saltandoli se sono già montati dalla CLI;
- impostare i permalink /%postname%/ ed eseguire l'importatore dei contenuti.
Così lo stesso file funziona in locale (cartelle montate, modifiche visibili subito) e dal link pubblico `https://playground.wordpress.net/?blueprint-url=…`.
Primo commit e push nella fase 1. Comunicami il link di anteprima, l'URL locale e le credenziali.

# FASE 2 — TEMA A BLOCCHI
- theme.json (versione 3) con valori estratti dal CSS originale: palette, gradienti, font self-hosted via fontFace, scala tipografica fluida, scala delle spaziature, raggi, ombre, contentSize e wideSize, stili per elementi e per blocco.
- templates/: index, front-page, page, single, archive, search, 404, più i template dei post type e quelli della mappa dei percorsi.
- parts/: header e footer con il blocco Navigazione.
- patterns/: una sezione dell'originale = un pattern, con categoria del progetto, visibile nell'inseritore.
- Stili dei blocchi (styles/blocks/*.json) al posto di classi da scrivere a mano, dove possibile.
- CSS residuo solo dove theme.json non arriva, caricato per blocco con wp_enqueue_block_style.
- Animazioni dell'originale ricostruite con CSS, JS leggero o Interactivity API, rispettando prefers-reduced-motion.

# FASE 3 — PLUGIN [slug]-core
Struttura: file principale, /includes (una classe per area), /src/blocks e /build/blocks (build con @wordpress/scripts, build versionata), /acf-json, /languages, readme.txt, uninstall.php.
Funzioni:
- Pagina "Impostazioni sito" con la Settings API, opzioni registrate con register_setting e show_in_rest.
- ThemeBridge: con il filtro wp_theme_json_data_theme il plugin sovrascrive palette e font del tema dalle sue impostazioni.
- Bindings: sorgente Block Bindings per i dati aziendali (un unico punto di modifica) e una per i campi ACF.
- Post type e tassonomie con show_in_rest, template di blocchi predefinito e blocco della struttura dove serve.
- Blocchi custom solo dove i blocchi core non bastano; dinamici quando leggono dati ACF; stato vuoto gestito (nascosto sul sito, spiegato nell'editor).
- Stili di blocco registrati dal plugin per i gruppi di card modificabili nella pagina, con CSS e JS caricati solo se lo stile è usato.
- Controllo dell'editor: blocchi consentiti, categorie dei pattern, blocco della struttura per gli utenti Editor.
- Se il plugin non è attivo, il tema non deve generare errori fatali.

# FASE 4 — CONTENUTI (prima mostrami il piano e FERMATI)
- [cambiato] Un importatore PHP rieseguibile (non duplica nulla) in /contenuti, lanciato dal blueprint: ogni pagina HTML diventa una pagina a blocchi costruita dai pattern, con testi originali, immagini nella Libreria media e testi alternativi compilati.
- Menu, home statica, contenuti dei post type con i campi ACF compilati.
- Form in Contact Form 7 con le email HTML decise nella fase preliminare.
- Pagine Privacy policy e Cookie policy collegate nel footer, nel form e nel banner.
- [nuovo] Contenuti incompleti trattati come deciso (segnaposto con avviso, bozza o nascosti).

# FASE 5 — SEO E AGENTI AI (solo Yoast + plugin custom)
Yoast SEO:
- Titoli e meta description per tipo di contenuto e per pagina, Open Graph, sitemap XML, breadcrumb con il blocco di Yoast nei template interni, rappresentazione del sito, llms.txt attivo. Impostazioni applicate dall'importatore, non a mano.
Plugin custom:
- Estendi lo schema di Yoast con i suoi filtri, solo per contenuti visibili in pagina.
- Verifica che non esistano meta, canonical o JSON-LD duplicati.
Tecnica:
- Un solo H1 per pagina, gerarchia dei titoli, landmark, link descrittivi, WCAG 2.2 AA.
- Immagini responsive, lazy-load tranne l'immagine LCP (fetchpriority high), preload solo dei font critici.
- robots.txt: chiedimi la policy sui crawler AI prima di bloccarne qualcuno.
Agenti AI:
- Contenuti leggibili senza JavaScript.
- REST in sola lettura per i dati utili, con un servizio dati unico condiviso da REST, abilities e schema.
- Abilities API in sola lettura con schema di input e output e permission_callback; documenta come esporle con l'MCP Adapter senza installarlo.

# FASE 6 — VERIFICA IN PLAYGROUND
Esegui e riporta come tabella pass/fail in verifica/VERIFICA.md:
- Confronto visivo con l'originale per ogni pagina a 1440, 768 e 375 px, con screenshot affiancati.
- [nuovo] Prova di navigazione: seguire ogni percorso della mappa (menu, card, “torna a”, archivio, 404) cliccando.
- [nuovo] Stati vuoti: contenuti senza immagine, senza video, senza persone, liste vuote.
- Ogni pagina si apre nell'editor senza blocchi non validi e senza blocchi HTML personalizzati.
- [nuovo] Prova da Editor (non amministratore): cosa può modificare e cosa è bloccato.
- Header, footer e stili modificabili dal Site Editor; un colore cambiato dal plugin si riflette ovunque.
- Campi ACF: salvataggio e visualizzazione nel front-end.
- Form: invio di prova, messaggi di errore e conferma, campi accessibili, CSS e JS di CF7 assenti nelle pagine senza form.
- Cookie: nessuno script di terze parti parte prima del consenso.
- Immagini: nessuna doppia compressione o doppio lazy-load.
- Yoast: meta, sitemap, llms.txt raggiungibili; JSON-LD valido e senza duplicati.
- Nessun errore o notice con WP_DEBUG attivo; PHPCS; [cambiato] Plugin Check installato solo nel sito di prova.
- Lighthouse su home e su una pagina interna.
- Elenco di ciò che si può verificare solo online (email, banner, ottimizzazione immagini).
Poi FERMATI: [nuovo] mandami il link di anteprima e gli screenshot principali, e aspetta la mia revisione prima della fase 7.

# FASE 7 — CONSEGNA E GUIDA DI UTILIZZO
Consegna:
- [slug]-theme.zip e [slug]-core.zip (senza sorgenti, node_modules e file di sviluppo) con uno script che li rigenera; blueprint.json; elenco dei plugin richiesti con le impostazioni già applicate e quelle da fare online.
- README.md tecnico: architettura, hook usati e offerti, come aggiungere un pattern, un blocco, un campo ACF, un'ability, un'impostazione.
- Elenco delle differenze rispetto all'HTML e DECISIONI.md aggiornato.
- RIEPILOGO-CLASSI-CSS.md con le stesse 9 sezioni del prompt originale (token, custom properties, classi custom, stili dei blocchi, mappa originale → WordPress, file CSS e JS con peso e caricamento, breakpoint e movimento ridotto, selettori dei plugin sovrascritti, convenzioni di naming).
- GUIDA-UTILIZZO.md in italiano, per chi gestisce il sito senza competenze tecniche, con le stesse 14 sezioni del prompt originale. [cambiato] Gli screenshot si scattano dal sito di prova con gli avvisi dei plugin chiusi, e nomi di menu e campi si copiano dagli screenshot.
- [nuovo] RIEPILOGO-LAVORO.md: fasi, struttura dei file, gestione del repository.

# MODO DI LAVORO
- Procedi una fase alla volta; alla fine di ogni fase mostra cosa hai fatto e cosa controllare nel link di anteprima.
- Fermate obbligatorie: dopo la Fase preliminare, dopo la Fase 0, prima della Fase 4, [nuovo] dopo la Fase 6 (anteprima).
- [nuovo] Un commit per fase (o per correzione importante) con messaggio in italiano, push sul ramo di lavoro.
- [nuovo] Tieni DECISIONI.md abbastanza completo da poter riprendere il lavoro se la sessione si interrompe.
- Non installare plugin oltre ai PLUGIN AMMESSI senza la mia conferma.
```
