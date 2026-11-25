# Plugin del sito e impostazioni

Sei plugin, tutti dalla directory di WordPress, più il plugin del progetto `wero-core`. Nessun altro plugin serve al sito.

- **✔ già fatto:** impostato da `contenuti/import.php` o dal blueprint.
- **☐ da fare:** sul sito online, a mano.

| Plugin | Slug | Ruolo |
|---|---|---|
| Yoast SEO | `wordpress-seo` | Unico strumento SEO: title, description, canonical, Open Graph, schema, sitemap, breadcrumb, llms.txt |
| Advanced Custom Fields | `advanced-custom-fields` | Campi strutturati (versione gratuita) |
| Contact Form 7 | `contact-form-7` | Modulo “Diventa partner” |
| Site Mailer | `site-mailer` | Invio affidabile delle email del sito, con registro |
| Cookie Consent | `cookiez` | Banner e blocco dei servizi di terze parti fino al consenso |
| Image Optimizer | `image-optimization` | Compressione e WebP delle immagini caricate |
| WeRo Core | `wero-core` | Plugin del progetto |

Requisiti del server: **WordPress 7.1+ e PHP 8.3+** (D37).

---

## 1. Yoast SEO

**Già fatto dall'importatore**
- ✔ Rappresentazione del sito: **Organizzazione** “WeRo”, nome alternativo “WeRo Robot Communication Studio”, logo, descrizione, email `contact@we-ro.it`.
- ✔ Separatore del titolo: **—** (trattino lungo), come l'originale.
- ✔ Home: title “WeRo — Il primo Robot Communication Studio” e description dell'originale.
- ✔ Pagine: `%%title%% — WeRo`.
- ✔ Progetti:
  - title `%%title%% — WeRo`;
  - description dal riassunto (`%%excerpt%%`);
  - tipo di schema “Articolo”;
  - archivio “Progetti — WeRo” con la sua description.
- ✔ Breadcrumb attive: “Home”, separatore `›`. Le mostra il blocco di Yoast nella testata dei progetti.
- ✔ llms.txt attivo.
- ✔ Cookie policy in **noindex**.

**Da fare sul sito online**
- ☐ Impostazioni › Lettura: **togli** la spunta “Scoraggia i motori di ricerca” se l'hosting l'ha messa (sullo staging lasciala).
- ☐ Yoast › Impostazioni › Rappresentazione del sito › Profili social: **non** inserire LinkedIn qui. Lo aggiunge già il plugin WeRo dalle Impostazioni › WeRo. Inserirlo in entrambi i posti non rompe niente, ma lo duplica.
- ☐ Yoast › Impostazioni › Account e collegamenti: nessuna verifica dei motori di ricerca, perché Search Console è fuori progetto (D27).
- ☐ Funzioni del sito: lascia attive sitemap XML, llms.txt e breadcrumb. Le altre funzioni restano come le imposta Yoast.
- ☐ Dopo la messa online, apri `https://we-ro.it/sitemap_index.xml` e `https://we-ro.it/llms.txt` per controllare che rispondano.
- ☐ Per ogni progetto completato: compila nel pannello Yoast la **frase chiave** e, se serve, una description diversa dal riassunto.

**Da non fare**
- Non attivare in Yoast altri tipi di contenuto nella ricerca. Report, Partner e Persona non hanno pagine pubbliche e Yoast li esclude già.
- Non aggiungere altri plugin SEO o di schema: il grafo JSON-LD è uno solo, quello di Yoast esteso da WeRo.

## 2. Advanced Custom Fields (gratuito)

- ✔ Nessuna impostazione da fare. I 5 gruppi di campi (Progetto, Report, Partner, Persona, Mondo) sono caricati dal plugin WeRo, cartella `wero-core/acf-json/`.
- ☐ Sul sito online **non modificare i gruppi di campi** da ACF › Gruppi di campi: le modifiche restano nel database e si perdono al prossimo aggiornamento del plugin. I campi si cambiano in sviluppo (vedi README, “Come si aggiunge un campo ACF”).
- Se ACF segnala “Sincronizzazione disponibile”, vuol dire che il JSON del plugin è più nuovo del database. Puoi sincronizzare senza rischi.
- Non installare ACF PRO: il progetto non usa funzioni PRO.

## 3. Contact Form 7

**Già fatto dall'importatore**
- ✔ Modulo **“Diventa partner”**:
  - campi Nome*, Azienda / Ente, Email*, Ambito di interesse (Ricerca, Business, Comunicazione & creatività) e Messaggio;
  - casella **privacy obbligatoria** con link a `/privacy-policy/`;
  - pulsante “Invia richiesta”.
- ✔ **Mail 1** (notifica a WeRo):
  - a `contact@we-ro.it`, da `WeRo <noreply@we-ro.it>`;
  - oggetto “[WeRo] Nuova richiesta di partnership da [nome]”;
  - HTML con lo stile WeRo; Reply-To a chi ha scritto.
- ✔ **Mail 2** (conferma a chi scrive):
  - oggetto “Grazie per averci scritto, [nome] – WeRo”;
  - HTML; Reply-To `contact@we-ro.it`.
- ✔ Messaggi in italiano, tra cui “Grazie! Ti ricontatteremo presto.”, “Compila questo campo.” e “Per inviare la richiesta devi accettare l'informativa privacy.”.
- ✔ Modulo **“Scarica i report”** (D54), nella pagina `/scarica-report/`:
  - campi Nome*, Email*, Azienda / Ente, Ruolo, Report da scaricare*, privacy obbligatoria; pulsante “Scarica i report”;
  - l'elenco dei report è il campo `[checkbox* report use_label_element default:get data:wero_reports]`: lo compila il plugin con i Report pubblicati che hanno un PDF, e spunta quello arrivato dal pulsante della timeline. **Non scrivere i report a mano nel modulo**;
  - **Mail 1** al gestore (`contact@we-ro.it`): oggetto “[WeRo] Download report: [report] – [nome]”, con nome, email, azienda, ruolo, report, data; Reply-To a chi ha scaricato;
  - **Mail 2** a chi compila: “I report di WeRo che hai richiesto”, con i link ai PDF (`[_wero_report_links]`);
  - dopo l'invio i link compaiono anche sulla pagina.
- ✔ Moduli collegati in Impostazioni › WeRo › Moduli e funzioni (modulo partner, modulo report, pagina “Scarica i report”).
- ✔ CSS e JS di CF7 caricati **solo** nelle pagine con il modulo. Se ne occupa il plugin WeRo, non serve nessun filtro in `wp-config.php`.

**Da fare**
- ☐ Cancella il modulo di esempio **“Contact form 1”** che CF7 crea all'attivazione: il sito non lo usa.
- ☐ Contact › Contact Forms › Diventa partner › scheda Mail: se l'indirizzo che riceve cambia, aggiorna il campo **A** della Mail 1 e il **Reply-To** della Mail 2. Stessa cosa nel modulo **Scarica i report** (campo **A** della Mail 1).
- ☐ Sostituisci i due **PDF segnaposto** dei report con quelli definitivi (Report › campo “File PDF”).
- ☐ Privacy: il modulo dei report raccoglie nome, email, azienda e ruolo per inviare i PDF e avvisare WeRo. Fai verificare al titolare che l'informativa copra anche questo uso.
- ☐ Il dominio del mittente (`noreply@we-ro.it`) deve essere lo stesso del sito e verificato in Site Mailer, altrimenti le email finiscono nello spam.
- ☐ Antispam (facoltativo): CF7 supporta Cloudflare Turnstile o reCAPTCHA (Contact › Integration). Sono servizi di terze parti: se li attivi, aggiungili alla cookie policy e a Cookie Consent.
- ☐ Fai un invio di prova dal sito online e controlla che arrivino **entrambe** le email.

## 4. Site Mailer

Nel Playground è solo installato e attivo: il collegamento all'account si fa sul sito online.
- ☐ Impostazioni › Site Mailer: **collega** l'account (l'account Elementor/Site Mailer del cliente).
- ☐ **Verifica il dominio** `we-ro.it`: Site Mailer mostra i record DNS da aggiungere presso il provider del dominio. Finché il dominio non è verificato, le email partono da un indirizzo di Site Mailer.
- ☐ Mittente: nome **WeRo**, indirizzo **noreply@we-ro.it** (D22). Deve coincidere con il mittente impostato in CF7.
- ☐ Invia l'email di prova dal plugin.
- ☐ **Registro (Log):** Site Mailer registra ogni email inviata e il suo stato (consegnata, rimbalzata, aperta). È il primo posto da guardare se qualcuno dice di non aver ricevuto la conferma.

## 5. Cookie Consent (cookiez)

Nel Playground risulta “Not connected” e il banner non compare: si configura solo sul sito online.
- ☐ Menu **Cookiez** › **Connect to start**: collega il plugin all'account, poi avvia la **scansione** dei cookie (Cookie management › Scan now).
- ☐ Categorie (D23, come la cookie policy):

  | Categoria | Servizi |
  |---|---|
  | Necessari | WordPress, preferenza del consenso |
  | Funzionali | YouTube |
  | Analitici | Google Analytics 4 (quando installerete Site Kit), YouTube |
  | Pubblicitari | YouTube |
  | Non categorizzati | Ciò che la scansione non riconosce |

- ☐ Testi in italiano e link alla **Cookie policy** (`/cookie-policy/`) e alla **Privacy policy**.
- ☐ Aspetto del banner, con i colori del sito:
  - fondo `#fbfaf5` (crema chiaro), testo `#15121a`;
  - pulsante principale `#ff00c7` con testo bianco;
  - angoli arrotondati (pillola), font del sito.
- ☐ Pulsanti “Accetta” e “Rifiuta” con lo stesso peso visivo (come indicano le linee guida del Garante privacy).
- ☐ Dopo la scansione, copia la tabella dei cookie reali nella **Cookie policy**, al posto della riga segnaposto (punti 2 e 5 dei testi legali).
- **Video e audio esterni:** il sito non carica YouTube, Vimeo o Spotify all'apertura della pagina. Partono solo quando il visitatore clicca (D40), quindi non ci sono richieste a terze parti prima del consenso.
- **Google Analytics 4** (Site Kit by Google, D33): attiva in Site Kit la **Consent Mode** e controlla che GA4 parta solo dopo il consenso alla categoria “Analitici”.

## 6. Image Optimizer

Nel Playground è solo installato e attivo.
- ☐ Media › Image Optimizer: **collega** l'account.
- ☐ Ottimizzazione **automatica al caricamento**: attiva.
- ☐ Formato: **WebP**. Le immagini dell'originale sono già WebP e il plugin le lascerà come sono.
- ☐ Tieni attivo il **backup degli originali**, così puoi ripristinare un'immagine.
- ☐ Ottimizza in blocco la Libreria media una volta, dopo aver caricato le immagini definitive.
- **Da non fare:** non attivare un secondo plugin di compressione o di lazy-load. WordPress applica già `loading="lazy"` e il blocco “Hero con video” carica subito, senza lazy-load, la copertina principale della home.

## 7. WeRo Core (Impostazioni › WeRo)

| Sezione | Campo | Valore attuale | Da fare |
|---|---|---|---|
| Organizzazione | Nome / Nome esteso | WeRo / WeRo Robot Communication Studio | — |
| | Descrizione | Testo dell'originale | — |
| | Email contatti | contact@we-ro.it | — |
| | Pagina LinkedIn | `https://www.linkedin.com/` (segnaposto) | ☐ **URL reale della pagina** |
| | Copyright | WeRo, Robot Communication Studio | — |
| Joint venture | Founder 1 | 6.14 S.r.l., Via Don Bosco 31, 20139 Milano, P.IVA 07376100967 | — |
| | Founder 2 | Omnicom Advertising | — |
| Aspetto | Colori, font | Vuoti: si usano quelli del tema | Solo se cambia l'identità |
| Animazioni | Interruttore generale, intro, intro solo alla prima visita, sfondo a pixel, cursore, comparsa allo scroll, transizioni tra pagine | Tutte attive | — |
| Moduli e funzioni | Modulo “Diventa partner” | Diventa partner | — |
| | Modulo “Scarica i report” | Scarica i report | — |
| | Pagina “Scarica i report” | Scarica i report (`/scarica-report/`) | — |
| | Disattiva gli emoji | Attivo | — |

## Plugin da non installare
- Elementor e altri page builder.
- Altri plugin SEO, schema o sitemap.
- Altri banner cookie.
- Altri plugin SMTP.
- Altri ottimizzatori di immagini o di cache del frontend senza averli provati su uno staging.

Eccezione già decisa: **Site Kit by Google** per GA4 (D33), da configurare con la Consent Mode.
