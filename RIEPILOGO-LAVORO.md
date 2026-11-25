# Riepilogo del lavoro

Conversione del sito di **WeRo Robot Communication Studio** (`https://we-ro.it`) da HTML statico a WordPress.
- **Periodo:** 20–23 novembre 2026.
- **Repository:** https://github.com/digital6punto14/wero-wp, ramo `sviluppo`.

---

## 1. Cosa è stato fatto

### Punto di partenza
Una home e quattro pagine di progetto in HTML statico (`html-source/`):
- articolo, articolo complesso, video, podcast;
- sfera 3D dei progetti, timeline dei report, loghi animati, intro del logo, sfondo a pixel e cursore “computer vision”.

### Risultato
Un sito WordPress con lo stesso aspetto, in cui ogni testo, immagine, progetto, report e logo si modifica dalla bacheca, senza codice.
- **Tema a blocchi `wero-theme`:** solo l'aspetto.
- **Plugin `wero-core`:** tutto il resto (contenuti strutturati, blocchi, impostazioni, SEO, dati per gli agenti AI).
- **Plugin usati:** solo i 6 concordati. Yoast SEO, ACF gratuito, Contact Form 7, Site Mailer, Cookie Consent, Image Optimizer.
- **Contenuti:** nessun page builder, nessun blocco HTML o shortcode.

### Le fasi
| Fase | Cosa | Esito |
|---|---|---|
| Preliminare | Domande aperte con proposte, raccolte in DECISIONI.md | Confermate il 20/11 |
| 0 | Analisi dell'HTML: sezioni, componenti, 20 animazioni, colori, font, spazi | FASE-0-ANALISI.md |
| 1 | Ambiente di prova WordPress Playground con blueprint | Funziona in locale e nel browser |
| 2 | Tema: theme.json, 12 stili di blocco, 13 template, 5 parti, 17 pattern, CSS per blocco | Completata |
| 3 | Plugin: 4 tipi di contenuto, 1 tassonomia, 5 gruppi di campi ACF, 14 blocchi, impostazioni, block bindings | Completata |
| 4 | Contenuti: piano approvato, poi importatore automatico (mondi, partner, 12 progetti, 3 report, modulo con 2 email HTML, pagine legali, home, menu) | FASE-4-PIANO.md |
| 5 | SEO con Yoast (titoli, schema esteso, breadcrumb, llms.txt) e agenti AI (REST `wero/v1`, 4 abilities) | Completata |
| 6 | Verifica: 23 controlli (confronto visivo, editor, accessibilità, Lighthouse, PHPCS, Plugin Check) | verifica/VERIFICA.md |
| 7 | Consegna: zip, documentazione, guida con screenshot | Questo documento |

### Richieste arrivate durante il lavoro
- **Quattro template per i progetti** (articolo, articolo complesso, video, podcast), scelti dal pannello laterale.
- **Pagine legali:** testi di privacy e cookie policy di 6.14 S.r.l., con le 5 correzioni approvate.
- **Riga dei loghi** aggiornabile ed estendibile senza limiti: ogni logo è un Partner.
- **Email:** due email HTML con lo stile WeRo per il modulo, la notifica a WeRo e la conferma a chi scrive.
- **Pagine progetto:** “Torna alla home” nei progetti; archivio `/progetti/` a griglia come la vista della home.
- **Home:** Mondi e Voci modificabili direttamente nella home con i blocchi Gutenberg (le Voci poi spostate nei progetti, D53).
- **Animazioni nuove:** comparsa delle sezioni allo scroll (N1) e transizione tra pagine (N3), entrambe disattivabili.
- **Controllo in più:** Plugin Check, installato solo nel sito di prova.
- **Contrasto del magenta:** lasciato com'è, fedele all'originale (D52).
- **25/11:** Voci tolte dalla home e trasformate in progetti video; pagina “Scarica i report” con modulo, email al gestore e link ai PDF; foto a scorrimento nei progetti; video in cima ai progetti video; tutte le foto dalla Libreria media (D53–D57).

### Numeri finali
| Controllo | Risultato |
|---|---|
| Blocchi nei contenuti | 370, tutti validi nell'editor |
| PHPCS (WordPress Coding Standards) | 0 errori, 0 avvisi |
| Plugin Check | 0 errori, 1 avviso documentato |
| Accessibilità (axe-core) | Nessuna violazione, salvo il contrasto del magenta voluto |
| Lighthouse mobile, home | 80 / 97 / 100 / 92 (prestazioni, accessibilità, buone pratiche, SEO) |
| Lighthouse mobile, progetto | 93 / 96 / 100 / 92 |
| Script di terze parti prima del consenso | Nessuno (video e audio esterni partono al clic) |

---

## 2. Struttura dei file

```text
wero-wp/
├── README.md                  architettura, hook, come estendere il sito (per sviluppatori)
├── GUIDA-UTILIZZO.md          guida per chi aggiorna il sito, con screenshot
├── PLUGIN-IMPOSTAZIONI.md     i 6 plugin: cosa è già impostato e cosa fare online
├── RIEPILOGO-CLASSI-CSS.md    token, classi, file CSS/JS, breakpoint, convenzioni
├── DIFFERENZE.md              differenze rispetto all'HTML originale
├── DECISIONI.md               tutte le decisioni prese (D1–D52)
├── RIEPILOGO-LAVORO.md        questo documento
├── FASE-0-ANALISI.md          analisi iniziale dell'HTML
├── FASE-4-PIANO.md            piano dei contenuti approvato
│
├── consegna/
│   ├── wero-theme.zip         tema, pronto da caricare in WordPress
│   └── wero-core.zip          plugin, pronto da caricare in WordPress
├── crea-zip.sh                rigenera i due zip
├── blueprint.json             installa tutto in WordPress Playground
├── avvia-playground.sh        Playground in locale (porta 9400)
│
├── wero-theme/                TEMA: solo aspetto
│   ├── theme.json             colori, sfumature, font, dimensioni, spazi, raggi, ombre
│   ├── style.css              intestazione del tema
│   ├── functions.php          solo supporti ed enqueue
│   ├── styles/blocks/         12 stili dei blocchi (Chip, Scuro, Vetro, Sfumatura…)
│   ├── templates/             13 template (home, pagina, 4 progetti, archivio, 404…)
│   ├── parts/                 header, footer, CTA partner, testata e chiusura progetto
│   ├── patterns/              17 pattern (sezioni home, contenuti di partenza progetti)
│   └── assets/
│       ├── css/               site.css, view-transitions.css, blocks/ (CSS per blocco)
│       ├── js/                sfondo, cursore, menu, galleria (senza librerie)
│       ├── fonts/             Space Grotesk ospitato sul sito
│       └── images/            immagini dei pattern
│
├── wero-core/                 PLUGIN: contenuti e funzioni
│   ├── wero-core.php          avvio del plugin
│   ├── readme.txt             descrizione per WordPress
│   ├── uninstall.php          pulizia alla disinstallazione
│   ├── includes/              16 classi, una per area (tipi di contenuto, ACF, blocchi,
│   │                          impostazioni, form, SEO, REST, abilities…)
│   ├── acf-json/              5 gruppi di campi ACF (progetto, report, partner, persona, mondo)
│   ├── src/blocks/            sorgenti dei 14 blocchi
│   ├── build/blocks/          blocchi compilati (quelli che WordPress usa)
│   ├── assets/                stile di Gruppo “Mondi”, script dell'editor e del modulo report
│   └── package.json           build dei blocchi con @wordpress/scripts
│
├── contenuti/                 CONTENUTI DA IMPORTARE
│   ├── import.php             importatore (rieseguibile, non duplica)
│   ├── dati.json              mondi, partner, progetti, report, testi
│   ├── media/                 27 immagini e loghi
│   ├── email/                 modelli HTML delle due email del modulo
│   ├── blocchi/               contenuto a blocchi dell'articolo complesso
│   ├── privacy-policy.md      testo della privacy policy
│   └── cookie-policy.md       testo della cookie policy
│
├── guida/                     26 screenshot della guida d'uso
├── verifica/
│   ├── VERIFICA.md            rapporto della fase 6
│   └── confronto/             15 confronti affiancati originale / WordPress
└── html-source/               sito HTML originale (riferimento)
```

**Dove intervenire**
| Per cambiare… | Dove |
|---|---|
| Colori, font, spazi del sito | `wero-theme/theme.json`, oppure Impostazioni › WeRo, oppure Stili globali |
| Aspetto di un blocco | `wero-theme/assets/css/blocks/{namespace}--{blocco}.css` |
| Una sezione della home come modello | `wero-theme/patterns/` |
| Campi di progetti, report, partner | `wero-core/acf-json/`, modificati da ACF su un sito di sviluppo |
| Un blocco WeRo (sfera, timeline, loghi…) | `wero-core/src/blocks/`, poi `npm run build` |
| I contenuti iniziali | `contenuti/dati.json` e `contenuti/import.php` |

---

## 3. Come è stato gestito GitHub

### Repository
- **Indirizzo:** `digital6punto14/wero-wp`.
- **Visibilità: pubblico.** Serve perché WordPress Playground nel browser possa scaricare blueprint, tema, plugin e contenuti senza credenziali. Il repository non contiene password, chiavi o dati personali. Contiene però i testi legali, i dati di 6.14 S.r.l. già pubblici sul suo sito e le immagini del sito. Se lo volete privato, si può rendere privato a fine progetto: il link di anteprima in Playground smetterà di funzionare.
- **Ramo unico:** `sviluppo`, che è anche il ramo predefinito. Non sono stati creati altri rami né pull request: nessuno li ha chiesti e il lavoro è andato avanti in sequenza, una fase dopo l'altra.

### Commit
15 commit, uno o due per fase. Ogni messaggio dice in italiano cosa cambia e perché.

| Data | Commit |
|---|---|
| 20/11 | Progetto WeRo per WordPress: sito HTML originale, decisioni, analisi Fase 0 e ambiente Playground |
| 20/11 | Blueprint unico e autonomo: tema e plugin installati da GitHub, saltati se montati dalla CLI |
| 20/11 | Fase 2 (in corso): theme.json, stili di blocco, header, footer, template, pattern e CSS per blocco |
| 20/11 | Fase 2: tema a blocchi wero-theme |
| 20/11 | Fase 3: plugin wero-core |
| 20/11 | Piano della Fase 4 (contenuti) da approvare |
| 20/11 | Fase 4: importatore dei contenuti (bozza in prova) |
| 20/11 | Fase 4: contenuti importati, email del modulo, archivio progetti a griglia, Torna alla home |
| 20/11 | Fase 5: SEO con Yoast e agenti AI |
| 20/11 | Mondi e Voci modificabili nella Home con i blocchi Gutenberg |
| 20/11 | Nomi delle card di Mondi e Voci nella Vista elenco dell'editor |
| 20/11 | Fase 6 (in corso): correzioni dalla verifica |
| 20/11 | Fase 6: rapporto di verifica (verifica/VERIFICA.md) |
| 23/11 | Plugin Check senza errori, transizione tra pagine (N3) |
| 23/11 | Fase 7: consegna |

### Cosa è nel repository e cosa no
- **Dentro:**
  - sorgenti e blocchi compilati del plugin. `build/` è versionato perché WordPress carica quello e Playground lo prende direttamente da GitHub;
  - i due zip di consegna in `consegna/`;
  - screenshot e confronti.
- **Fuori (`.gitignore`):**
  - `node_modules/`;
  - log di debug;
  - file di sistema;
  - gli altri zip;
  - file di lavoro dell'assistente dentro `html-source/`.

### GitHub e Playground
Il `blueprint.json` legge direttamente dal ramo `sviluppo`:
1. installa i 6 plugin dalla directory di WordPress;
2. installa tema e plugin WeRo dalle cartelle del repository;
3. copia `contenuti/` ed esegue l'importatore.

Ogni commit inviato su `sviluppo` aggiorna quindi l'anteprima: basta ricaricare il link.

https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/digital6punto14/wero-wp/sviluppo/blueprint.json

Con `avvia-playground.sh`, in locale, le cartelle vengono invece montate dal disco: le modifiche si vedono subito, senza commit.

### Per continuare a lavorarci
- **Ramo `main` per il sito online:** è consigliabile crearlo quando il sito va in produzione, e lasciare `sviluppo` per le modifiche in corso.
- **Versioni:**
  1. alza la versione in `wero-theme/style.css` e in `wero-core/wero-core.php` (anche la costante `VERSION`);
  2. esegui `./crea-zip.sh`;
  3. fai il commit;
  4. facoltativo: crea un tag (`v0.2.0`) e una release su GitHub con i due zip allegati.
- **Collaboratori:** si aggiungono da Settings › Collaborators del repository.
