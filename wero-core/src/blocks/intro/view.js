/* =========================================================
   Intro: animazione del logo WeRo all'apertura (HTML/CSS/SVG)
   L'animazione (5 s, identica al video originale) è tutta in CSS:
   vedi "INTRO" in style.css. Qui solo la regia: a fine animazione
   (o con "Salta"/Esc) l'intro sfuma e la pagina compare,
   menu compreso, con una transizione sull'opacità.
   A ogni caricamento; saltata con prefers-reduced-motion o ?intro=0.
   ========================================================= */
(function () {
  var root = document.documentElement;
  var intro = document.getElementById('intro');
  if (!root.classList.contains('intro-on') || !intro) { if (intro) intro.remove(); return; }

  var DURATION = 5050;   // ms: fine dell'animazione CSS
  var FADE = 800;        // ms: dissolvenza dell'intro (uguale alla transition di .intro)
  var done = false;

  function finish() {
    if (done) return; done = true;
    clearTimeout(timer);
    window.scrollTo(0, 0);
    root.classList.remove('intro-on');     // sblocca lo scroll e fa comparire il menu in dissolvenza
    root.classList.add('intro-out');       // tiene l'intro sopra la pagina mentre sfuma
    intro.classList.add('is-leaving');
    try { sessionStorage.setItem('weroIntroSeen', '1'); } catch (e) {}
    setTimeout(function () { intro.classList.add('is-gone'); root.classList.remove('intro-out'); }, FADE);
  }

  document.getElementById('introSkip').addEventListener('click', finish);
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') finish(); });
  var timer = setTimeout(finish, DURATION);
})();
