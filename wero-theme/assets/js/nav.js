/* =========================================================
   WeRo — menu (blocco Navigazione core)
   - voce attiva: segue la sezione della home visibile a schermo
     (link del tipo "/#chi" o "#chi")
   - nel menu mobile aperto, un clic su una voce chiude il menu
   - ripiego per i browser senza import map (Safari/iOS < 16.4,
     Chrome < 89): lì l'Interactivity API di WordPress non parte e il
     pulsante del menu mobile non farebbe nulla; qui il menu si apre e
     chiude lo stesso, solo se WordPress non l'ha già fatto.
   Nessuna libreria; nessun effetto se la pagina non ha le ancore.
   ========================================================= */
(function () {
  if (!document.querySelectorAll || !document.documentElement.classList) return;
  var opens = document.querySelectorAll('.wp-block-navigation__responsive-container-open');
  Array.prototype.forEach.call(opens, function (open) {
    var nav = open.closest ? open.closest('.wp-block-navigation') : null;
    var box = nav && nav.querySelector('.wp-block-navigation__responsive-container');
    if (!box) return;
    var close = box.querySelector('.wp-block-navigation__responsive-container-close');
    var dialog = box.querySelector('.wp-block-navigation__responsive-dialog');
    var isOpen = function () { return box.classList.contains('is-menu-open'); };
    function set(on) {
      box.classList.toggle('is-menu-open', on);
      box.classList.toggle('has-modal-open', on);
      document.documentElement.classList.toggle('has-modal-open', on);
      if (dialog) {
        if (on) { dialog.setAttribute('role', 'dialog'); dialog.setAttribute('aria-modal', 'true'); }
        else { dialog.removeAttribute('role'); dialog.removeAttribute('aria-modal'); }
      }
      if (on) { var first = box.querySelector('a[href], button'); if (first) first.focus(); }
      else { open.focus(); }
    }
    /* aspetta che WordPress abbia gestito il clic: interviene solo se lo stato non è cambiato */
    function ensure(want) { setTimeout(function () { if (isOpen() !== want) set(want); }, 80); }
    open.addEventListener('click', function () { ensure(true); });
    if (close) close.addEventListener('click', function () { ensure(false); });
    document.addEventListener('keydown', function (e) {
      if ((e.key === 'Escape' || e.key === 'Esc') && isOpen()) ensure(false);
    });
  });
})();

(function () {
  var navs = document.querySelectorAll('.wp-block-navigation');
  if (!navs.length) return;

  var links = {};
  navs.forEach(function (nav) {
    nav.querySelectorAll('a[href*="#"]').forEach(function (a) {
      var url;
      try { url = new URL(a.getAttribute('href'), location.href); } catch (e) { return; }
      if (url.pathname.replace(/\/$/, '') !== location.pathname.replace(/\/$/, '') || !url.hash) return;
      var id = decodeURIComponent(url.hash.slice(1));
      (links[id] = links[id] || []).push(a);
    });
    /* il menu mobile del core resta aperto dopo il clic su un'ancora della stessa pagina: lo chiudo */
    nav.addEventListener('click', function (e) {
      var a = e.target.closest('a[href*="#"]');
      if (!a) return;
      var close = nav.querySelector('.wp-block-navigation__responsive-container.is-menu-open .wp-block-navigation__responsive-container-close');
      if (close) close.click();
    });
  });

  var ids = Object.keys(links);
  if (!ids.length || !('IntersectionObserver' in window)) return;
  var spy = new IntersectionObserver(function (entries) {
    entries.forEach(function (en) {
      if (!en.isIntersecting) return;
      ids.forEach(function (k) {
        links[k].forEach(function (a) {
          var on = k === en.target.id;
          a.classList.toggle('is-active', on);
          if (on) a.setAttribute('aria-current', 'location'); else a.removeAttribute('aria-current');
        });
      });
    });
  }, { rootMargin: '-45% 0px -50% 0px' });
  ids.forEach(function (id) { var el = document.getElementById(id); if (el) spy.observe(el); });
})();
