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

## 7. Registro degli aggiornamenti

Tieni una riga per ogni aggiornamento, anche in un semplice foglio. Serve a capire cosa è cambiato quando qualcosa smette di funzionare.

| Data | Chi | Cosa (da → a) | Backup | Controlli | Note |
|---|---|---|---|---|---|
| 23/11/2026 | — | Installazione iniziale: WordPress 7.1.5, PHP 8.3 | — | Fase 6 | — |

---

## Riepilogo in una riga

**Backup → leggi le novità → aggiorna uno alla volta (WordPress, ACF, Yoast, CF7, gli altri) → controllo rapido in incognito → prova del modulo → annota.**
