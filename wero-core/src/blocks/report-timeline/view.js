/* Timeline dei report guidata dallo scroll (dall'originale): su desktop la sezione resta ferma
   e scorrendo la timeline avanza in orizzontale; su mobile e con movimento ridotto scorre col dito. */
(function () {
  /* ---------- report: timeline guidata dallo scroll ----------
     L'altezza della sezione = schermo + quanto la timeline è più larga dello schermo:
     così scorrendo in verticale la si percorre tutta in orizzontale. */
  var rtl = document.getElementById('rtl');
  if (rtl) {
    var track = rtl.querySelector('.rtl__track'), prog = rtl.querySelector('.rtl__progress');
    var items = rtl.querySelectorAll('.rtl__item');
    var mq = window.matchMedia('(max-width: 900px), (prefers-reduced-motion: reduce)');
    var forceStatic = !!rtl.closest('.is-static');   /* opzione del blocco: niente effetto "sezione ferma" */
    var mqStatic = { get matches() { return forceStatic || mq.matches; } };
    var extra = 0, stickyTop = 0;
    var sticky = rtl.querySelector('.rtl__sticky');
    function rtlSize() {
      if (mqStatic.matches) { rtl.style.height = ''; sticky.style.top = ''; return; }
      sticky.style.top = '';
      extra = Math.max(0, track.scrollWidth - window.innerWidth);
      // il blocco resta fermo centrato nello schermo (sotto il menu), alto solo quanto il contenuto
      var h = sticky.offsetHeight, navEl = document.querySelector('.wero-header'), navH = navEl ? navEl.getBoundingClientRect().bottom : 0;
      stickyTop = Math.max(navH + 16, Math.round((window.innerHeight - h) / 2));
      sticky.style.top = stickyTop + 'px';
      rtl.style.height = (h + extra) + 'px';
      rtlScroll();
    }
    function rtlScroll() {
      if (mqStatic.matches) return;
      var r = rtl.getBoundingClientRect();
      var t = Math.min(1, Math.max(0, (stickyTop - r.top) / Math.max(1, extra)));
      track.style.transform = 'translate3d(' + (-t * extra) + 'px,0,0)';
      prog.style.width = (t * 100) + '%';
      var lineX = t * window.innerWidth;
      items.forEach(function (it) { it.classList.toggle('is-past', it.getBoundingClientRect().left <= Math.max(lineX, 40)); });
    }
    /* pulsanti: portano alla tappa precedente / successiva */
    var prevB = document.getElementById('rtlPrev'), nextB = document.getElementById('rtlNext'), countB = document.getElementById('rtlCount');
    // tappa corrente dall'avanzamento (0..1): così anche l'ultima, che non arriva al bordo sinistro, è raggiungibile
    function rtlProgress() {
      if (mqStatic.matches) { var m = sticky.scrollWidth - sticky.clientWidth; return m > 0 ? sticky.scrollLeft / m : 0; }
      return extra ? Math.min(1, Math.max(0, (stickyTop - rtl.getBoundingClientRect().top) / extra)) : 0;
    }
    function rtlPad() { return parseFloat(getComputedStyle(track).paddingLeft) || 0; }
    function rtlIndex() {
      if (!mqStatic.matches) return Math.round(rtlProgress() * (items.length - 1));
      var max = sticky.scrollWidth - sticky.clientWidth, x = sticky.scrollLeft;
      if (x >= max - 4) return items.length - 1;
      var idx = 0;
      items.forEach(function (it, k) { if (it.offsetLeft - rtlPad() <= x + 10) idx = k; });
      return idx;
    }
    function rtlUpdateNav() {
      var i = rtlIndex();
      countB.textContent = (i + 1) + ' di ' + items.length;
      prevB.disabled = i === 0; nextB.disabled = i === items.length - 1;
    }
    function rtlGo(i) {
      i = Math.max(0, Math.min(items.length - 1, i));
      var f = items.length > 1 ? i / (items.length - 1) : 0;
      if (mqStatic.matches) { sticky.scrollTo({ left: Math.min(sticky.scrollWidth - sticky.clientWidth, items[i].offsetLeft - rtlPad()), behavior: 'smooth' }); return; }
      var top = rtl.getBoundingClientRect().top + window.scrollY;
      window.scrollTo({ top: top - stickyTop + f * extra + 1, behavior: 'smooth' });
    }
    prevB.addEventListener('click', function () { rtlGo(rtlIndex() - 1); });
    nextB.addEventListener('click', function () { rtlGo(rtlIndex() + 1); });
    sticky.addEventListener('scroll', rtlUpdateNav, { passive: true });
    window.addEventListener('scroll', rtlUpdateNav, { passive: true });

    rtlSize();
    window.addEventListener('resize', rtlSize);
    rtlUpdateNav();
    window.addEventListener('scroll', rtlScroll, { passive: true });
    window.addEventListener('load', rtlSize);
  }

  /* ---------- report: finché i PDF non ci sono, il clic non porta in cima alla pagina ---------- */
  document.querySelectorAll('.rtl a[download]').forEach(function (l) {
    l.addEventListener('click', function (e) {
      if (l.getAttribute('href') !== '#') return;   // report-download: con il file vero il download parte normalmente
      e.preventDefault(); l.textContent = 'PDF disponibile a breve';
    });
  });
})();
