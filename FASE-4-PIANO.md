# Fase 4: piano dei contenuti (da approvare)

Nessun contenuto vero è stato ancora importato. I dati di prova usati per verificare i blocchi esistono solo nel Playground locale e non vanno nel progetto.

## Come li importo

- **Importatore unico:** uno script PHP, `contenuti/import.php`, lanciato dal blueprint con un passaggio WP-CLI (`wp eval-file`). Si può rieseguire: se un contenuto esiste già, lo aggiorna invece di duplicarlo.
- **Stesso risultato ovunque:** chi apre il link di Playground o installa il sito trova lo stesso contenuto, partendo dai file del repository.
- **Immagini:** vengono tutte caricate nella **Libreria media** con il testo alternativo dell'originale. Le pagine usano le immagini della Libreria, non i file del tema, quindi si sostituiscono dall'editor (D24).
- **Loghi SVG:** quelli di 6.14, Italdesign, Eni Joule, Rehabilia, Alto Robotics e il logo WeRo vengono convertiti in PNG ad alta risoluzione (D41).
- **Home:** è costruita con le sezioni del tema trasformate in **blocchi veri**, non con richiami alla sezione. Così ogni testo e ogni immagine si modifica direttamente nella pagina.

## Cosa viene creato

| Contenuto | Quantità | Origine | Note |
|---|---|---|---|
| Pagina **Home** (home statica) | 1 | index.html | 9 sezioni: hero, chi siamo con founder, fascia loghi e foto, mondi, progetti, report, voci, perché, form |
| Pagina **Privacy policy** | 1 | testo di 6.14 (`contenuti/privacy-policy.md`) | impostata come pagina privacy di WordPress |
| Pagina **Cookie policy** | 1 | testo di 6.14 corretto (D31) | noindex con Yoast (D36) |
| **Mondi** | 3 | index.html | immagine, testo, stile del bollino, ordine |
| **Partner** | 11 | index.html | 2 founder (con scheda) e 9 collaboratori (Business/Ricerca) |
| **Progetti** completi | 4 | le 4 pagine interne | Accessible Robotics (Articolo), Generative Bionics (Articolo complesso, testi e immagini veri), Cultura robotica al Gazometro (Video, capitoli), Tools for human beings (Podcast) |
| **Progetti** da completare | 8 | lista della home | titolo, mondo, tipo, anno, icona e descrizione breve veri; testo della pagina segnaposto |
| **Report** | 3 | timeline | 2 pubblicati (PDF da caricare quando arrivano) e 1 “In arrivo” con “Avvisami” |
| **Voci** | 3 | sezione voci | copertine e testi; link dei video da inserire (D15) |
| **Menu** | 1 | header | 6 voci verso le sezioni della home + “Diventa partner” |
| **Logo e icona del sito** | — | wero-mark | logo del menu e favicon |
| **Modulo CF7** “Diventa partner” | 1 | form dell'originale | vedi sotto |
| **Impostazioni sito** | — | D2, D32 | modulo partner collegato |

### Modulo “Diventa partner” (Contact Form 7)

- **Campi:**
  - Nome *;
  - Azienda / Ente;
  - Email *;
  - Ambito, a chip: Ricerca, Business, Comunicazione & creatività;
  - Messaggio;
  - privacy obbligatoria: “Ho letto l'[informativa privacy](/privacy-policy/)”.
- **Email inviata:**
  - destinatario contact@we-ro.it;
  - mittente “WeRo <noreply@we-ro.it>”;
  - oggetto “[WeRo] Nuova richiesta di partnership da [nome]”;
  - Reply-To impostato sull'email di chi scrive.
- **Messaggi:** tutti in italiano. La conferma è “Grazie! Ti ricontatteremo presto.”
- **Caricamento:** CSS e JS di CF7 solo nella home, l'unica pagina con il modulo.

## Tre cose da decidere

1. **Gli 8 progetti senza pagina.** In DECISIONI.md li avevamo decisi come **bozze** (D35). Una bozza però non compare in home, quindi la sfera e la lista mostrerebbero solo 4 progetti invece di 12.
   - a) **Pubblicati**, con testo segnaposto nella pagina, da completare prima della messa online. La sfera resta come l'originale. *(Consigliato)*
   - b) Bozze, come deciso: la sfera mostra solo i 4 progetti completi.
2. **Persone nelle pagine video e podcast.** L'originale mostra “Nome Cognome, Ruolo, ente” come segnaposto.
   - a) Le creo come segnaposto evidenti (“Nome Cognome”), da sostituire. *(Consigliato: la pagina resta come l'originale)*
   - b) Non le creo: il blocco “Persone” resta nascosto finché non inserite nomi veri.
3. **Video e audio.** Non ci sono ancora link per hero, voci, video del Gazometro e podcast. I player mostrano “Il video sarà disponibile a breve”, come l'originale. Se hai già qualche link (YouTube, Vimeo, Spotify…), mandamelo e lo inserisco.

## Cosa non faccio in questa fase

- **Collegamenti agli account Elementor:** Site Mailer, Image Optimizer e probabilmente Cookie Consent si collegano all'account Elementor solo sul sito online.
- **Tabella dei cookie:** arriva dalla scansione di Cookie Consent online.
- **Yoast (titoli, descrizioni, schema, llms.txt):** è la Fase 5. In questa fase importo solo le meta description dell'originale dove ci sono.
