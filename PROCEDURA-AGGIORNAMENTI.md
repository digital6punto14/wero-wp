# Procedura di aggiornamento di WordPress

Cosa fare quando si aggiornano WordPress, i plugin o PHP sul sito di WeRo. Tempo previsto: 20–30 minuti, compresi i controlli.

---

## Quando aggiornare

| Cosa | Ogni quanto | Note |
|---|---|---|
| WordPress, versioni di sicurezza (es. 7.1.4 → 7.1.5) | Si aggiornano da sole | WordPress le installa in automatico. Basta fare i controlli del punto 4 il giorno dopo. |
| Plugin | Ogni 2 settimane | Subito se l'aggiornamento è di sicurezza. |
| WordPress, versioni principali (es. 7.1 → 7.2) | 1–2 settimane dopo l'uscita | Lascia il tempo ai plugin di adeguarsi. |
| PHP (dal pannello dell'hosting) | Quando l'hosting lo propone | Solo dopo averlo provato sullo staging. |

Aggiorna in un momento tranquillo: non il venerdì sera, non prima di un evento o di una campagna.

---

## 1. Prima di aggiornare

1. **Backup completo** di file e database dal pannello dell'hosting. Controlla che sia andato a buon fine e annota data e ora: è il punto a cui tornare se qualcosa si rompe.
2. **Leggi cosa cambia.** In Bacheca › Aggiornamenti, per ogni plugin clicca “Visualizza i dettagli della versione”. Fai attenzione alle versioni principali (il primo o il secondo numero cambia) e a frasi come “breaking change”, “requires PHP”, “requires WordPress”.
3. **Controlla i requisiti.** Le versioni attuali di Contact Form 7 e Yoast vogliono WordPress 7.1 e PHP 8.3 (D37). Se un aggiornamento chiede un PHP più nuovo di quello del server, fermati e aggiorna prima PHP, sullo staging.
4. **Se esiste uno staging**, fai tutta la procedura prima lì, poi ripetila sul sito online.

## 2. Ordine degli aggiornamenti

Uno alla volta, controllando la home dopo ognuno: se qualcosa si rompe, sai subito chi è stato.

1. **WordPress**
2. **Advanced Custom Fields**: i campi di progetti, report e partner dipendono da lui.
3. **Yoast SEO**
4. **Contact Form 7**
5. **Site Mailer**
6. **Cookie Consent** (Cookiez)
7. **Image Optimizer**
8. **Traduzioni**, in fondo alla pagina Aggiornamenti.

Il tema e il plugin WeRo non compaiono qui: si aggiornano solo caricando uno zip nuovo (vedi README, “Aggiornamenti”).

Se dopo un aggiornamento la Bacheca chiede di **aggiornare il database** (succede con WordPress, Yoast, ACF), conferma.

## 3. Subito dopo: controllo rapido (5 minuti)

Apri il sito in una **finestra in incognito**: vedi il sito come un visitatore, senza la barra di amministrazione e senza cache del browser.

**Home**
- [ ] Si apre senza errori; l'intro del logo parte (solo alla prima visita).
- [ ] La **sfera dei progetti** ruota, i filtri dei mondi funzionano, la vista griglia si apre.
- [ ] La **timeline dei report** scorre.
- [ ] I **loghi dei partner** e la riga curva dei collaboratori si muovono.
- [ ] Le card di **Mondi** e **Voci** si vedono; il video di una Voce parte al clic.
- [ ] Il **modulo “Diventa partner”** si vede con il suo stile.
- [ ] Menu: su desktop in riga; su telefono (o finestra stretta) la tendina si apre e si chiude.

**Pagine interne**
- [ ] Un progetto per tipo (articolo, articolo complesso, video, podcast):
  - testata con breadcrumb e “Torna alla home”;
  - player;
  - capitoli;
  - “Altri progetti”.
- [ ] La pagina `/progetti/` (griglia).
- [ ] Privacy e Cookie policy.
- [ ] Una pagina inesistente (es. `/prova-404/`) mostra la pagina 404 del sito.

## 4. Controllo completo (15 minuti)

Da fare dopo un aggiornamento di WordPress principale, di ACF, di Yoast o di PHP.

**Bacheca**
- [ ] Nessun avviso rosso o giallo in Bacheca e in Plugin.
- [ ] Strumenti › Salute del sito: nessun problema “critico” nuovo.
- [ ] ACF › Gruppi di campi: se compare “Sincronizzazione disponibile”, sincronizza.

**Editor**
- [ ] Apri la Home nell'editor: nessun blocco con “Questo blocco contiene contenuti inattesi o non validi”.
- [ ] Apri un progetto: sotto l'editor si vedono i campi (Scheda, Video o audio, Podcast, Persone) e si salvano.
- [ ] Aspetto › Editor: header e footer si aprono; email e LinkedIn del footer mostrano i valori veri.
- [ ] Impostazioni › WeRo si apre e salva.

**Modulo ed email**
- [ ] Invia il modulo “Diventa partner” con un tuo indirizzo.
- [ ] Arrivano **due** email: la notifica a contact@we-ro.it e la conferma a te.
- [ ] In Impostazioni › Site Mailer › Email logs risultano inviate.

**SEO**
- [ ] `https://we-ro.it/sitemap_index.xml` si apre.
- [ ] `https://we-ro.it/llms.txt` si apre.
- [ ] Il codice sorgente di un progetto (tasto destro › Visualizza sorgente, cerca `<title>`) contiene un solo `<title>` e un solo blocco `application/ld+json`.

**Cookie**
- [ ] In incognito il banner compare.
- [ ] Rifiutando i cookie, i video si caricano solo al clic.
- [ ] Se hai aggiunto un servizio nuovo: Cookiez › Cookie management › **Scan now** e aggiorna la tabella della Cookie policy.

**Velocità**
- [ ] [PageSpeed Insights](https://pagespeed.web.dev/) sulla home, versione mobile: i valori non devono essere peggiorati molto rispetto all'ultima volta. Riferimento in fase di sviluppo: prestazioni 80, accessibilità 97.

## 5. Se qualcosa si rompe

**Una sezione non si vede o si vede male** (es. la sfera)
1. Svuota la cache: quella dell'hosting, se c'è, e poi ricarica con `Ctrl+F5` (Mac `Cmd+Shift+R`).
2. Se persiste, controlla quale plugin hai appena aggiornato.
3. Sul sito di wordpress.org, nella pagina di quel plugin › **Sviluppo** › **Opzioni avanzate**, scarica la versione precedente. Caricala da Plugin › Aggiungi nuovo › Carica plugin, scegliendo di sostituire.

**Il sito mostra “Si è verificato un errore critico”**
1. WordPress di solito invia un'email all'amministratore con un link di **modalità di ripristino**: aprilo, entra e disattiva il plugin indicato.
2. Se l'email non arriva, ripristina il **backup** fatto al punto 1.

**Le email non partono più**
- Impostazioni › Site Mailer: controlla che risulti ancora collegato e il dominio verificato.

**Blocchi “non validi” nell'editor**
- **Non** cliccare “Ripristina” o “Converti in HTML”.
- Chiudi senza salvare e contatta lo sviluppatore: di solito serve un aggiornamento del plugin WeRo.

**In ogni caso**
- Annota cosa hai aggiornato, a che ora e cosa vedi, con uno screenshot. Se è il caso, ripristina il backup e scrivi allo sviluppatore.

## 6. Aggiornare PHP

1. Fai prima tutto sullo **staging**, o almeno fai un backup.
2. Pannello dell'hosting › versione PHP: passa alla versione nuova, almeno **8.3**.
3. Esegui il **controllo completo** del punto 4.
4. Se compare un errore critico, torna subito alla versione PHP precedente dallo stesso pannello e avvisa lo sviluppatore.

## 7. Aggiornare tema e plugin WeRo quando cambia WordPress

Questa parte è per lo **sviluppatore**. WordPress, Yoast, ACF e Contact Form 7 cambiano nel tempo. A volte una novità tocca il codice del tema `wero-theme` o del plugin `wero-core`, e allora vanno aggiornati anche loro.

### Quando serve
**In anticipo** (meglio), per ogni versione principale di WordPress (es. 7.2):
- leggi la **Field Guide** e le **Dev Notes** su [make.wordpress.org/core](https://make.wordpress.org/core/);
- cerca le parole: blocchi, theme.json, Navigation, Block Bindings, Abilities API, funzioni deprecate.

**Dopo**, se dopo un aggiornamento compare uno di questi segnali:
- avvisi “deprecated” o “notice” nel registro degli errori (con `WP_DEBUG` attivo, solo su staging o in locale);
- un blocco WeRo che nell'editor dice “contenuto non valido” o non si carica;
- una parte del sito che cambia aspetto, tipicamente il menu (usa le classi del blocco Navigazione core);
- Plugin Check o Salute del sito che segnalano problemi nuovi;
- un plugin (Yoast, ACF, CF7) che annuncia la rimozione di un filtro o di una funzione.

### Le parti più sensibili di questo progetto
| Parte | File | Cosa può cambiare in WordPress | Cosa controllare |
|---|---|---|---|
| Menu (header a pillola e tendina) | `wero-theme/assets/css/blocks/core--navigation.css` | Classi interne del blocco Navigazione (`wp-block-navigation__responsive-container…`) | Menu su desktop e telefono, apertura e chiusura della tendina |
| CSS per blocco | `wero-theme/functions.php` (`wp_enqueue_block_style`) | Nomi dei blocchi core, regole di caricamento degli stili | Ogni file `assets/css/blocks/*.css` si carica ancora dove serve |
| theme.json | `wero-theme/theme.json` (versione 3) | Nuova versione dello schema, impostazioni rinominate | Stili globali nel Site Editor, avvisi nel registro |
| Pattern e template | `wero-theme/patterns/`, `templates/`, `parts/` | Markup dei blocchi core salvato | Nessun blocco “non valido” in home, progetti e Site Editor |
| Blocchi WeRo | `wero-core/src/blocks/*` (block.json `apiVersion: 3`) | Nuova `apiVersion`, cambi in `@wordpress/scripts` | Build senza errori, blocchi visibili nell'editor e sul sito |
| Registrazione dei blocchi | `includes/class-blocks.php` (`wp_register_block_types_from_metadata_collection`) | Funzione recente: può evolvere | I 14 blocchi compaiono nell'inseritore |
| Block Bindings | `includes/class-bindings.php`, `assets/editor/bindings.js` | API ancora in evoluzione (sorgenti lato editor) | Email, LinkedIn e copyright del footer, nell'editor e sul sito |
| Abilities API | `includes/class-abilities.php` | API nuova: nomi degli hook e dei parametri possono cambiare | `/wp-json/wp-abilities/v1/abilities` elenca le 4 abilities |
| Ponte colori e font | `includes/class-theme-bridge.php` (`wp_theme_json_data_theme`) | Struttura dei dati di theme.json | Un colore cambiato in Impostazioni › WeRo cambia ovunque |
| Contact Form 7 | `includes/class-forms.php` | Filtri `wpcf7_load_js` / `wpcf7_load_css`, script nei temi a blocchi | Modulo funzionante, nessun “wpcf7 is not defined” in console |
| Yoast | `includes/class-seo.php` | Filtri `wpseo_schema_*` | Un solo grafo JSON-LD, con i founder |
| ACF | `includes/class-acf.php`, `acf-json/` | Formato dei JSON, punti di caricamento | I campi compaiono e si salvano |

Il codice controlla già le funzioni più recenti prima di usarle (`function_exists`), quindi una funzione rimossa spegne quella parte senza bloccare il sito. Va comunque sistemata.

### Procedura passo per passo
1. **Prepara l'ambiente di prova con la nuova versione di WordPress.**
   - In `blueprint.json`, alla voce `preferredVersions`, metti `"wp": "7.2"` (la versione da provare) e avvia:
     ```bash
     ./avvia-playground.sh
     ```
   - Il sito di prova si apre su `http://127.0.0.1:9400` (utente `admin`, password `password`), con `WP_DEBUG` attivo. Il registro degli errori finisce in `wero-core/debug.log`.
   - Finite le prove, rimetti `"latest"`.
2. **Lavora su un ramo a parte:**
   ```bash
   git switch sviluppo && git pull && git switch -c aggiornamento-wp-7-2
   ```
3. **Leggi il registro** `wero-core/debug.log` dopo aver aperto home, un progetto per tipo, l'editor e il Site Editor. Ogni “deprecated” indica la funzione da sostituire e con cosa.
4. **Aggiorna gli strumenti dei blocchi e ricompila:**
   ```bash
   cd wero-core && npm install --save-dev @wordpress/scripts@latest && npm run build
   ```
   Se `build/` cambia, fai il commit anche dei file compilati: WordPress usa quelli.
5. **Correggi** quello che il registro e i controlli segnalano. Regole:
   - Il codice nuovo deve funzionare **sia con la versione vecchia sia con la nuova** di WordPress. Il sito online va aggiornato prima del passaggio a WordPress 7.2, e per un po' girerà sulla versione vecchia. Usa `function_exists()` o `class_exists()` per le funzioni nuove.
   - Se cambia il markup di un blocco core usato nei pattern, apri il pattern nell'editor, lascia che WordPress lo aggiorni, poi copia il nuovo markup nel file del pattern.
   - Se cambiano le classi del blocco Navigazione, aggiorna solo `core--navigation.css`.
   - Se cambia lo schema di theme.json, segui la guida di migrazione e alza `"version"`.
   - Per i blocchi WeRo, che sono dinamici (`render.php`): se cambi gli attributi, mantieni i vecchi o aggiungi una voce `deprecated` in `index.js`, così i contenuti già salvati restano validi.
6. **Controlli prima di consegnare:**
   - PHPCS, che deve restare a 0 errori:
     ```bash
     phpcs --standard=WordPress wero-theme wero-core --ignore=node_modules,build,vendor
     ```
   - Plugin Check su `wero-core`, installato solo nel sito di prova;
   - il **controllo completo** del punto 4, fatto sul sito di prova;
   - un confronto visivo con gli screenshot in `verifica/confronto/`, almeno home e un progetto, a 1440 e 375 px.
7. **Nuova versione:**
   - alza il numero, per una correzione di compatibilità il terzo: da `0.1.0` a `0.1.1`. Va cambiato in tre punti:
     - `wero-theme/style.css`, riga `Version`;
     - `wero-core/wero-core.php`, riga `Version` e costante `VERSION`;
     - `readme.txt`, riga `Stable tag`;
   - aggiorna **“Tested up to”** con la nuova versione di WordPress, in `wero-theme/style.css` e `wero-core/readme.txt`;
   - se serve, alza anche “Requires at least” / “Requires PHP”;
   - rifai gli zip:
     ```bash
     ./crea-zip.sh
     ```
8. **Salva e pubblica il codice:**
   ```bash
   git add -A && git commit -m "Compatibilità con WordPress 7.2" && git switch sviluppo && git merge aggiornamento-wp-7-2 && git push
   ```
   Annota in DECISIONI.md cosa è cambiato e perché.
9. **Installa sul sito, in quest'ordine:**
   1. backup;
   2. carica `wero-core.zip` e `wero-theme.zip` (sostituendo quelli installati);
   3. controllo rapido;
   4. solo dopo, aggiorna WordPress e fai il controllo completo.

   Sempre prima sullo staging, se c'è.

### Se il problema è già sul sito online
1. Se è grave (pagine rotte, errore critico), **ripristina il backup** fatto prima dell'aggiornamento di WordPress. In alternativa, tieni WordPress com'è e disattiva gli aggiornamenti automatici principali finché la correzione non è pronta.
2. Riproduci il problema nel sito di prova con la stessa versione di WordPress e segui la procedura qui sopra.

### Master prompt per Claude Code

Per far fare il controllo e l'aggiornamento a Claude Code:
1. apri una sessione sul repository `digital6punto14/wero-wp`;
2. compila i campi tra parentesi quadre;
3. incolla il testo qui sotto.

Claude Code lavora in due tempi:
- **prima** controlla e ti consegna un rapporto, poi si ferma;
- **dopo** la tua conferma corregge, prova e prepara la nuova versione.

Non tocca il sito online e non aggiorna WordPress al posto tuo.

````markdown
# RUOLO
Sei uno sviluppatore WordPress senior e mantieni il tema a blocchi `wero-theme` e il plugin `wero-core` del sito WeRo (https://we-ro.it). Lavori in Claude Code sul repository digital6punto14/wero-wp. Rispondi in italiano.

# OBIETTIVO
Verificare se tema e plugin WeRo sono compatibili con le versioni nuove di WordPress e dei plugin del sito. Se serve, produrre una nuova versione corretta di `wero-theme` e `wero-core`, provata in WordPress Playground e pronta da caricare.

# PARAMETRI
- VERSIONE WORDPRESS DA PROVARE: [es. 7.2 | latest | beta]
- VERSIONE WORDPRESS ATTUALE DEL SITO ONLINE: [es. 7.1.5]
- VERSIONE PHP DEL SITO ONLINE: [es. 8.3]
- ALTRI CAMBIAMENTI DA CONSIDERARE: [es. Yoast 29, ACF 7 — oppure "controlla tu le ultime versioni"]
- PROBLEMI GIÀ NOTATI SUL SITO: [descrizione + screenshot, oppure "nessuno"]
- RAMO DI PARTENZA: sviluppo

# PRIMA DI INIZIARE
1. Leggi README.md, DECISIONI.md, PROCEDURA-AGGIORNAMENTI.md (sezione 7: tabella delle parti sensibili), RIEPILOGO-CLASSI-CSS.md e verifica/VERIFICA.md. Sono le regole del progetto: non contraddirle.
2. Carica le skill WordPress/agent-skills: wordpress-router, wp-block-themes, wp-block-development, wp-plugin-development, wp-playground, blueprint, wp-abilities-api, wp-plugin-directory-guidelines.
3. Crea il ramo `aggiornamento-wp-[versione]` da `sviluppo`.

# FASE A — CONTROLLO (poi FERMATI)
1. Fonti ufficiali. Raccogli cosa cambia, citando ogni fonte con il link:
   - per WordPress: Field Guide e Dev Notes della versione (make.wordpress.org/core);
   - per Yoast SEO, ACF, Contact Form 7, Site Mailer, Cookie Consent e Image Optimizer: changelog su wordpress.org, con i requisiti minimi di WordPress e PHP.
   Considera solo ciò che tocca le parti della tabella “parti sensibili”. Non inventare: se una fonte non è chiara, dillo.
2. Controllo del codice. Cerca nel codice di `wero-theme` e `wero-core` ogni funzione, hook, filtro, classe CSS core o chiave di theme.json citata come deprecata, rinominata o cambiata.
3. Prova reale in Playground:
   - avvia il sito di prova con la versione da provare: in `blueprint.json` → `preferredVersions.wp`, poi `./avvia-playground.sh`, con `NODE_USE_ENV_PROXY=1` e `NODE_EXTRA_CA_CERTS` se c'è un proxy; per l'importazione usa runPHP, non WP-CLI;
   - apri e salva gli screenshot (1440 e 375 px) di: home, un progetto per ciascun template (articolo, articolo complesso, video, podcast), `/progetti/`, privacy, una pagina 404;
   - nell'editor apri Home, un progetto e il Site Editor (header e footer) e conta i blocchi non validi;
   - leggi `wero-core/debug.log`;
   - esegui PHPCS (WordPress Coding Standards) e Plugin Check su `wero-core`. Plugin Check va installato solo nel sito di prova, mai nel blueprint;
   - confronta gli screenshot con `verifica/confronto/`.
4. Rapporto. Scrivi `verifica/AGGIORNAMENTO-[versione].md` con:
   - tabella pass/fail degli stessi controlli di verifica/VERIFICA.md;
   - elenco dei problemi trovati, ognuno con: gravità (bloccante / da correggere / estetico), file e riga, causa, fonte ufficiale, correzione proposta;
   - cosa NON è stato possibile verificare (email, banner dei cookie, Image Optimizer: solo online);
   - la tua raccomandazione: nessuna modifica necessaria / nuova versione di correzione / attendere un aggiornamento di un plugin.

Mostrami il rapporto e FERMATI. Non correggere nulla prima della mia conferma.

# FASE B — CORREZIONE (dopo la mia conferma)
Regole:
- Correggi solo i problemi approvati. Niente miglioramenti non richiesti, niente cambi di design.
- Il codice deve funzionare sia con la versione di WordPress attuale del sito sia con quella nuova: proteggi le funzioni nuove con `function_exists()` / `class_exists()`.
- Rispetta la separazione: aspetto nel tema, tutto il resto nel plugin; `functions.php` solo supporti ed enqueue.
- Restano vietati blocchi HTML personalizzati, shortcode e page builder. Yoast resta l'unico strumento SEO. Si usano solo le funzioni di ACF gratuito.
- Blocchi WeRo:
  - aggiorna `@wordpress/scripts` e ricompila: `cd wero-core && npm install --save-dev @wordpress/scripts@latest && npm run build`;
  - se cambi attributi, mantieni la compatibilità con i contenuti salvati (attributi vecchi o `deprecated`);
  - fai il commit anche di `build/`.
- Pattern: se cambia il markup di un blocco core, rigenera il markup dall'editor e riportalo nel file del pattern.
- Non installare plugin nuovi senza chiedere.

Verifica dopo le correzioni:
- ripeti tutta la prova della Fase A, punto 3;
- il risultato deve essere:
  - 0 blocchi non validi;
  - registro senza deprecated o notice di tema e plugin;
  - PHPCS a 0 errori;
  - Plugin Check senza errori nuovi;
  - aspetto uguale agli screenshot di riferimento (le differenze vanno spiegate).

Nuova versione:
- alza il numero di versione:
  - correzione di compatibilità: 0.1.0 → 0.1.1;
  - funzioni nuove: 0.2.0 (solo se le ho chieste);
- i punti da cambiare:
  - `wero-theme/style.css` (`Version`, `Tested up to`);
  - `wero-core/wero-core.php` (`Version` e costante `VERSION`);
  - `wero-core/readme.txt` (`Stable tag`, `Tested up to`, sezione Changelog);
  - se cambiano i requisiti minimi, `Requires at least` / `Requires PHP` ovunque, e avvisami;
- rigenera gli zip con `./crea-zip.sh`;
- aggiorna i documenti:
  - `verifica/AGGIORNAMENTO-[versione].md` con l'esito finale;
  - DECISIONI.md (nuova decisione numerata: cosa è cambiato e perché);
  - la riga del registro in PROCEDURA-AGGIORNAMENTI.md (sezione 8);
  - README, RIEPILOGO-CLASSI-CSS o GUIDA-UTILIZZO solo se qualcosa che descrivono è cambiato davvero.

# CONSEGNA
- Commit con messaggio in italiano sul ramo `aggiornamento-wp-[versione]` e push. Non unire a `sviluppo` e non aprire pull request finché non lo chiedo.
- Link di anteprima Playground del ramo nuovo. Nel `blueprint.json` del ramo:
  - cambia i tre `"ref": "sviluppo"` in `"ref": "aggiornamento-wp-[versione]"`, così l'anteprima installa il codice nuovo;
  - imposta `preferredVersions.wp` sulla versione provata.

  Il link è `https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/digital6punto14/wero-wp/aggiornamento-wp-[versione]/blueprint.json`. Prima dell'unione in `sviluppo` rimetti `"ref": "sviluppo"` e `"wp": "latest"`.
- Riepilogo breve:
  - cosa è cambiato, file per file;
  - esito dei controlli;
  - zip da caricare;
  - ordine di installazione sul sito: backup → zip del plugin e del tema → controllo rapido → aggiornamento di WordPress → controllo completo;
  - cosa va controllato online.

# NON FARE
- Non toccare il sito online, non usare credenziali del sito, non aggiornare WordPress al posto mio.
- Non cancellare contenuti, campi ACF o impostazioni.
- Non modificare `contenuti/` salvo che serva all'ambiente di prova, e spiegalo.
- Non procedere oltre la Fase A senza la mia conferma.
````

**Uso periodico (facoltativo)**
- Lo stesso prompt si può programmare una volta al mese, con VERSIONE WORDPRESS DA PROVARE impostata su `latest`, fermandosi alla Fase A.
- Così ricevi solo il rapporto e decidi tu se procedere.

## 8. Registro degli aggiornamenti

Tieni una riga per ogni aggiornamento, anche in un semplice foglio. Serve a capire cosa è cambiato quando qualcosa smette di funzionare.

| Data | Chi | Cosa (da → a) | Backup | Controlli | Note |
|---|---|---|---|---|---|
| 23/11/2026 | — | Installazione iniziale: WordPress 7.1.5, PHP 8.3, tema e plugin WeRo 0.1.0 | — | Fase 6 | — |

---

## Riepilogo in una riga

**Backup → leggi le novità → aggiorna uno alla volta (WordPress, ACF, Yoast, CF7, gli altri) → controllo rapido in incognito → prova del modulo → annota.**

Se la novità tocca il codice WeRo: **prova con la nuova versione nel sito di prova → correggi su un ramo → nuova versione di tema e plugin → installala prima di aggiornare WordPress.**
