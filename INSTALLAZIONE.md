# Installazione del sito WeRo

Procedura per mettere online `https://we-ro.it`, da un WordPress vuoto al sito pubblicato. Tempo previsto: circa 1 ora, più i tempi del DNS per le email.

**Cosa serve, tutto nella cartella `consegna/` del repository:**

| File | Cos'è | Si carica da |
|---|---|---|
| `wero-theme.zip` | Il tema (aspetto del sito) | Aspetto › Temi › Aggiungi tema › Carica tema |
| `wero-core.zip` | Il plugin del sito (progetti, report, blocchi, impostazioni, SEO) | Plugin › Aggiungi nuovo › Carica plugin |
| `wero-installer.zip` | Plugin **temporaneo** che importa i contenuti iniziali. Si elimina dopo l'uso | Plugin › Aggiungi nuovo › Carica plugin |

Se gli zip vanno rifatti (dopo una modifica al codice):

```bash
./crea-zip.sh
```

La procedura è stata provata per intero su un WordPress pulito (25/11/2026): installazione dei 6 plugin, caricamento dei 3 zip, importazione dalla bacheca, controllo delle pagine.

---

## 0. Prima di iniziare

**Hosting**
- [ ] **WordPress 7.1 o successivo** e **PHP 8.3 o successivo**: le versioni attuali di Contact Form 7 e Yoast li richiedono (D37).
- [ ] **HTTPS** attivo sul dominio (certificato SSL).
- [ ] Limite di caricamento file di almeno **64 MB**, utile per i video caricati sul sito (Strumenti › Salute del sito › Info › Server).
- [ ] **Backup automatico** giornaliero dell'hosting.
- [ ] Se possibile, uno **staging** (copia di prova): fai tutta la procedura prima lì.

**Account e dati da avere sotto mano**
- [ ] Accesso al pannello dell'hosting e al **DNS** del dominio `we-ro.it`, per verificare il dominio delle email in Site Mailer.
- [ ] Account Elementor del cliente, per collegare Site Mailer, Cookie Consent e Image Optimizer.
- [ ] Email che deve ricevere i moduli: oggi `contact@we-ro.it`.

## 1. WordPress

1. Installa WordPress dal pannello dell'hosting (o carica i file e segui l'installazione guidata):
   - lingua **Italiano**;
   - titolo del sito qualsiasi (lo imposta poi l'importatore);
   - un utente amministratore con password robusta: **non** chiamarlo `admin`.
2. Impostazioni › Generali:
   - **Indirizzo WordPress** e **Indirizzo sito**: `https://we-ro.it`;
   - fuso orario Roma; formato data `d/m/Y`.
3. Impostazioni › Lettura: lascia spuntato **“Scoraggia i motori di ricerca”** finché il sito non è pronto (lo togli al punto 7).

## 2. I sei plugin

Plugin › Aggiungi nuovo: cerca, installa e **attiva**, in quest'ordine:

1. **Advanced Custom Fields** (di WP Engine; versione gratuita)
2. **Contact Form 7**
3. **Yoast SEO**
4. **Site Mailer** (di Elementor)
5. **Cookie Consent** (di Elementor; slug `cookiez`)
6. **Image Optimizer** (di Elementor)

Non installare altri plugin. In particolare **non installare Elementor**: la voce “Elementor” che compare nel menu viene da questi tre plugin ed è normale.

Compariranno avvisi rosa “not connected” e un riquadro “Image Optimization”: chiudi il riquadro con **Ignora**, altrimenti copre i pulsanti della pagina. Il collegamento degli account si fa al punto 5.

## 3. Tema e plugin WeRo

1. Aspetto › Temi › **Aggiungi tema** › **Carica tema** › `wero-theme.zip` › Installa › **Attiva**.
2. Plugin › Aggiungi nuovo › **Carica plugin** › `wero-core.zip` › Installa › **Attiva**.
3. Plugin › Aggiungi nuovo › **Carica plugin** › `wero-installer.zip` › Installa › **Attiva**.

## 4. Contenuti iniziali

1. **Strumenti › Installa contenuti WeRo**.
2. Controlla che tutte le righe siano **✔ OK**. Se una riga è **✖ Manca**, torna al punto 2 o 3.
3. Premi **Importa i contenuti** e attendi circa un minuto, senza chiudere la pagina.
4. Alla fine compare il registro, che termina con **“Fatto.”**.

L'importazione crea:
- le immagini nella Libreria media;
- i 3 mondi, gli 11 partner, i 15 progetti e i 3 report (con PDF);
- i moduli **Diventa partner** e **Scarica i report**, con le loro email;
- le pagine **Home**, **Scarica i report**, **Privacy policy** e **Cookie policy**;
- il menu e i permalink `/%postname%/`;
- le impostazioni di WeRo e di Yoast.

Toglie anche la pagina e l'articolo di esempio di WordPress e il modulo di esempio di Contact Form 7.

5. **Plugin › WeRo – Installazione contenuti › Disattiva › Elimina.** Non serve più.

L'importazione va fatta **una sola volta**. Rifarla riporta i contenuti importati (testi della Home, progetti, report, moduli, menu) allo stato iniziale e cancella le modifiche fatte nel frattempo. Per questo, se è già stata fatta, l'installer chiede una conferma.

**Senza bacheca (WP-CLI)**, in alternativa al plugin, dalla cartella del repository sul server:

```bash
wp eval-file contenuti/import.php
```

In questo caso imposta prima i permalink su “Nome articolo”.

## 5. Servizi da collegare

Dettagli e impostazioni consigliate in **PLUGIN-IMPOSTAZIONI.md**.

1. **Site Mailer**, in Impostazioni › Site Mailer:
   - collega l'account;
   - **verifica il dominio** `we-ro.it` aggiungendo i record DNS indicati;
   - mittente **WeRo**, `noreply@we-ro.it`;
   - invia l'email di prova.
2. **Cookie Consent**, nel menu Cookiez:
   - collega l'account con **Connect to start**;
   - avvia la **scansione** dei cookie;
   - controlla le categorie (Necessari, Funzionali, Analitici, Pubblicitari);
   - imposta testi italiani, link a Cookie policy e Privacy policy e colori del sito;
   - copia la tabella dei cookie trovati nella pagina **Cookie policy**.
3. **Image Optimizer**, in Media › Image Optimizer:
   - collega l'account;
   - attiva l'ottimizzazione automatica in **WebP** e il **backup degli originali**;
   - lancia una volta **Bulk Optimization**.
4. **Yoast SEO:** niente da fare, è già configurato. Ignora la “configurazione iniziale” che propone.
5. **Google Analytics (facoltativo, D33):** se installate **Site Kit by Google**, attiva la **Consent Mode**, così GA4 parte solo dopo il consenso.

## 6. Contenuti da completare prima della pubblicazione

- [ ] **Impostazioni › WeRo:** l'indirizzo vero della pagina **LinkedIn** (ora c'è un segnaposto).
- [ ] **Report:** sostituisci i due **PDF segnaposto** (Report › Accessible Robotics / How can we disrupt robotics perceptions › campo “File PDF”).
- [ ] **8 progetti con testo segnaposto:** hanno un avviso in testa alla pagina. Scrivi il testo vero e **togli il blocco dell'avviso**:
  - Evento e toolkit di robotica;
  - R1;
  - IIT;
  - CDP Tech Transfer Day;
  - CNR;
  - Milano Sala Next;
  - ROAD;
  - How can we disrupt robotics perceptions.
- [ ] **Progetti video:** link al video (YouTube) o MP4 caricato nei progetti:
  - When robotics become care;
  - When robotics meet the world of work;
  - When robots enter our space;
  - Cultura robotica al Gazometro.
- [ ] **Hero della home:** il video “The future of robotics” (blocco Hero con video › pannello a destra).
- [ ] **Foto a scorrimento** dei progetti: aggiungi le foto dove ci sono (vuote non compaiono).
- [ ] **Persone:** ospiti e relatori dei progetti video e podcast (Persone › Aggiungi, poi collegale dal progetto).
- [ ] **Testi legali:** fai confermare a 6.14 S.r.l. che privacy e cookie policy coprono anche il modulo **Scarica i report** (nome, email, azienda, ruolo).

## 7. Controlli e pubblicazione

1. In una **finestra in incognito** esegui il **controllo completo** di PROCEDURA-AGGIORNAMENTI.md (sezione 4): home, progetti, `/progetti/`, `/scarica-report/`, pagine legali, menu da telefono.
2. **Moduli:**
   - [ ] **Diventa partner:** arrivano 2 email (a `contact@we-ro.it` e a chi scrive).
   - [ ] **Scarica i report:** i link compaiono sulla pagina, arriva l'email con i link a chi scrive e l'avviso a `contact@we-ro.it`.
   - [ ] In Impostazioni › Site Mailer › Email logs risultano tutte inviate.
3. **Cookie:** in incognito compare il banner e i video partono solo al clic.
4. Impostazioni › Lettura: **togli** “Scoraggia i motori di ricerca”.
5. Controlla che rispondano:
   - `https://we-ro.it/sitemap_index.xml`;
   - `https://we-ro.it/llms.txt`;
   - `https://we-ro.it/robots.txt`.
6. [PageSpeed Insights](https://pagespeed.web.dev/) sulla home e su un progetto, versione mobile.
7. **Utenti:**
   - crea gli account **Editor** per chi aggiornerà i contenuti (Utenti › Aggiungi nuovo);
   - consegna **GUIDA-UTILIZZO.md**.
8. Fai un **backup completo** e annotalo nel registro di PROCEDURA-AGGIORNAMENTI.md (sezione 8).

## Se qualcosa va storto

| Problema | Cosa fare |
|---|---|
| Nell'installer una riga è “✖ Manca” | Attiva il tema o il plugin indicato (punti 2–3) e ricarica la pagina |
| Il pulsante “Importa i contenuti” non si clicca | Chiudi il riquadro “Image Optimization” con **Ignora** |
| Il registro non finisce con “Fatto.” | Probabile limite di tempo dell'hosting: premi di nuovo **Importa** (non crea doppioni). Se si ripete, usa WP-CLI o chiedi all'hosting di alzare `max_execution_time` |
| Le pagine `/progetti/` o `/scarica-report/` danno 404 | Impostazioni › Permalink › scegli “Nome articolo” › Salva |
| Le immagini mancano | Controlla che la cartella `wp-content/uploads` sia scrivibile (pannello dell'hosting) e rifai l'importazione |
| “Si è verificato un errore critico” | WordPress invia un'email con il link di ripristino; disattiva il plugin indicato. Verifica la versione di PHP (8.3+) |
| Le email non arrivano | Site Mailer collegato e dominio verificato? Controlla Email logs e la cartella spam |

## Ambiente di prova

- **Anteprima nel browser**, sempre aggiornata al ramo `sviluppo`, senza installare nulla: https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/digital6punto14/wero-wp/sviluppo/blueprint.json
- **In locale** (utente `admin` / `password`):
  ```bash
  ./avvia-playground.sh
  ```
