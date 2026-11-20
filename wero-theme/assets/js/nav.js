/* =========================================================
   WeRo — menu (blocco Navigazione core)
   - voce attiva: segue la sezione della home visibile a schermo
     (link del tipo "/#chi" o "#chi")
   - nel menu mobile aperto, un clic su una voce chiude il menu
   Nessuna libreria; nessun effetto se la pagina non ha le ancore.
   ========================================================= */
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
