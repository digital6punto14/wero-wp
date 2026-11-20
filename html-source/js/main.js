/* =========================================================
   WeRo — logica della homepage
   Dati segnaposto in cima: in WordPress arriveranno da CPT/ACF.
   ========================================================= */
(function () {
  var WORLDS = { ri: 'Robotics Intelligence', ce: 'Conversation & Events', ld: 'Language & Design' };

  /* ---------- progetti: letti dalla lista HTML #projectList ----------
     In WordPress il tema stampa la lista; la sfera ne è solo la vista. */
  var PROJECTS = Array.prototype.map.call(document.querySelectorAll('#projectList .plist__item'), function (li) {
    var link = li.querySelector('.plist__link');
    return {
      title: li.querySelector('.plist__title').textContent.trim(),
      desc: li.querySelector('.plist__desc').textContent.trim(),
      url: link ? link.getAttribute('href') : '#',
      type: li.dataset.type || '', year: li.dataset.year || '',
      world: li.dataset.world, worldName: WORLDS[li.dataset.world] || '', icon: li.dataset.icon || 'robot', image: li.dataset.image || ''
    };
  });

  /* ---------- partner: anteprima sotto il logo ----------
     Con il mouse si apre al passaggio (CSS); il clic/tocco la fissa aperta,
     così funziona anche su telefono. Esc o un clic fuori la chiude. */
  var partners = document.querySelectorAll('.partner');
  function closePartners(except) {
    partners.forEach(function (p) { if (p !== except) { p.classList.remove('is-open'); p.querySelector('.partner__logo').setAttribute('aria-expanded', 'false'); } });
  }
  partners.forEach(function (p) {
    var btn = p.querySelector('.partner__logo');
    btn.addEventListener('click', function () {
      var open = !p.classList.contains('is-open');
      closePartners(p);
      p.classList.toggle('is-open', open); btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });
  document.addEventListener('click', function (e) { if (!e.target.closest('.partner')) closePartners(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closePartners(); });

  /* ---------- riga curva: loghi dei collaboratori ----------
     Ogni tessera (assets/img/loghi/tile-*.svg) contiene il logo e l'etichetta Business/Ricerca. */
  var COLLAB = [
    ['iit', 'IIT, Istituto Italiano di Tecnologia'], ['italdesign', 'Italdesign'], ['elevo', 'Elevo'],
    ['generative-bionics', 'Generative Bionics'], ['eni-joule', 'Eni Joule'], ['rehabilia', 'Rehabilia Technologies'],
    ['alto-robotics', 'Alto Robotics'], ['iuvo', 'IUVO'], ['spqr-team', 'SPQR Team']
  ].map(function (l) { return { src: 'assets/img/loghi/tile-' + l[0] + '.svg', alt: l[1] }; });

  var collabEl = document.getElementById('collabRow');
  if (collabEl && window.TiltedGrid) {
    window.TiltedGrid(collabEl, { images: COLLAB, speed: 3.2, tileHeight: 34, aspectRatio: 1.6, gap: 8, axis: 52, curve: 72, fade: 10 });
  }

  /* ---------- sfera progetti ---------- */
  var sphereEl = document.getElementById('sphere');
  var pinfo = {
    box: document.getElementById('pinfo'),
    desc: document.getElementById('pinfoDesc'), link: document.getElementById('pinfoLink'), count: document.getElementById('pinfoCount')
  };
  var sphere = null;

  // il pannello dice solo ciò che la card non dice: descrizione e link
  var shown = -1, shownPool = '';
  function showProject(i) {
    var p = PROJECTS[i]; if (!p || !pinfo.box) return;
    pinfo.desc.textContent = p.desc;
    pinfo.link.href = p.url;
    pinfo.link.setAttribute('aria-label', 'Scopri il progetto ' + p.title);
    var pool = sphere ? sphere.eligible() : PROJECTS.map(function (_, j) { return j; });
    pinfo.count.textContent = (pool.indexOf(i) + 1) + ' di ' + pool.length;
    if (i === shown && pool.join() === shownPool) return;   // già mostrato: niente animazione doppia
    shown = i; shownPool = pool.join();
    pinfo.box.classList.remove('is-swap'); void pinfo.box.offsetWidth; pinfo.box.classList.add('is-swap');
  }

  // il riquadro non cambia altezza tra un progetto e l'altro: la descrizione riserva
  // lo spazio della più lunga, misurata alla larghezza attuale (ricalcolata al resize)
  function fitDesc() {
    var d = pinfo.desc; if (!d || !d.offsetWidth) return;
    var probe = d.cloneNode(false);
    probe.removeAttribute('id'); probe.removeAttribute('aria-live');
    probe.style.cssText = 'position:absolute;visibility:hidden;min-height:0;animation:none;width:' + d.offsetWidth + 'px';
    d.parentNode.appendChild(probe);
    var max = 0;
    PROJECTS.forEach(function (p) { probe.textContent = p.desc; max = Math.max(max, probe.offsetHeight); });
    probe.remove();
    d.style.minHeight = max + 'px';
  }
  if (pinfo.desc && window.ResizeObserver) {
    var fitW = 0;
    new ResizeObserver(function () { var w = pinfo.desc.offsetWidth; if (w && w !== fitW) { fitW = w; fitDesc(); } }).observe(pinfo.desc);
  }
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(fitDesc);

  if (sphereEl && window.CardSphere && PROJECTS.length) {
    // su mobile la nuvola si vede solo mentre si naviga; a riposo resta la card in primo piano
    sphere = window.CardSphere(sphereEl, { projects: PROJECTS, onSettle: showProject, focusOnRest: window.matchMedia('(max-width: 900px)') });
    showProject(0);
    document.getElementById('pNext').addEventListener('click', function () { sphere.next(); showProject(sphere.target()); });
    document.getElementById('pPrev').addEventListener('click', function () { sphere.prev(); showProject(sphere.target()); });

    // un giro breve la prima volta che la sfera entra in vista: fa capire che si muove
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      var spinIO = new IntersectionObserver(function (en) {
        if (!en[0].isIntersecting) return;
        spinIO.disconnect();
        setTimeout(function () { sphere.spin(); }, 300);
      }, { threshold: 0.5 });
      spinIO.observe(sphereEl);
    }
  }

  /* ---------- progetti: lista con paginazione e vista a griglia ----------
     La lista sta sempre sotto; "Guarda come griglia" sostituisce la sfera con una griglia.
     Entrambe seguono i filtri per mondo. */
  var currentFilter = 'all', page = 1, PER_PAGE = 5, gridMode = false;
  var rowList = document.getElementById('prowList'), pager = document.getElementById('pager');
  var grid = document.getElementById('pgrid'), toggle = document.getElementById('viewToggle');
  var countEl = document.getElementById('pviewsCount');
  var projectsBox = document.querySelector('.projects');
  function esc(t) { return String(t).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }
  function visible() { return PROJECTS.filter(function (p) { return currentFilter === 'all' || p.world === currentFilter; }); }
  function renderViews() {
    if (!rowList) return;
    var list = visible(), pages = Math.max(1, Math.ceil(list.length / PER_PAGE));
    page = Math.min(page, pages);
    countEl.textContent = list.length + (list.length === 1 ? ' progetto' : ' progetti');
    rowList.innerHTML = list.slice((page - 1) * PER_PAGE, page * PER_PAGE).map(function (p) {
      var th = p.image ? '<img src="' + esc(p.image) + '" alt="" loading="lazy">' : '<span class="pgcard__ph pgcard__ph--' + p.world + '"></span>';
      return '<li class="prow"><a href="' + esc(p.url) + '">' +
        '<span class="prow__thumb" aria-hidden="true">' + th + '</span>' +
        '<span class="prow__title">' + esc(p.title) + '</span>' +
        '<span class="prow__world tag-mini tag-mini--' + p.world + '">' + esc(p.worldName) + '</span>' +
        '<span class="prow__meta">' + esc([p.type, p.year].filter(Boolean).join(', ')) + '</span>' +
        '<span class="prow__go" aria-hidden="true">Scopri</span></a></li>';
    }).join('');
    var h = '';
    if (pages > 1) {
      h += '<button type="button" class="round" data-page="' + (page - 1) + '"' + (page === 1 ? ' disabled' : '') + ' aria-label="Pagina precedente">←</button>';
      for (var i = 1; i <= pages; i++) h += '<button type="button" class="pager__n' + (i === page ? ' is-on' : '') + '" data-page="' + i + '"' + (i === page ? ' aria-current="page"' : '') + '>' + i + '</button>';
      h += '<button type="button" class="round" data-page="' + (page + 1) + '"' + (page === pages ? ' disabled' : '') + ' aria-label="Pagina successiva">→</button>';
    }
    pager.innerHTML = h;
    grid.innerHTML = list.map(function (p) {
      var img = p.image ? '<img src="' + esc(p.image) + '" alt="" loading="lazy">' : '<span class="pgcard__ph pgcard__ph--' + p.world + '" aria-hidden="true"></span>';
      return '<li class="pgcard"><a href="' + esc(p.url) + '"><span class="pgcard__media">' + img + '</span>' +
        '<span class="pgcard__meta"><span class="tag-mini tag-mini--' + p.world + '">' + esc(p.worldName) + '</span>' + esc([p.type, p.year].filter(Boolean).join(', ')) + '</span>' +
        '<span class="pgcard__title">' + esc(p.title) + '</span></a></li>';
    }).join('');
  }
  if (pager) pager.addEventListener('click', function (e) {
    var b = e.target.closest('[data-page]'); if (!b || b.disabled) return;
    page = +b.dataset.page; renderViews();
    rowList.querySelector('a') && rowList.querySelector('a').focus({ preventScroll: true });
  });
  if (toggle) toggle.addEventListener('click', function () {
    gridMode = !gridMode;
    projectsBox.hidden = gridMode; grid.hidden = !gridMode;
    document.querySelector('.pviews').classList.toggle('is-grid', gridMode);
    toggle.textContent = gridMode ? 'Guarda come sfera' : 'Guarda come griglia';
    toggle.setAttribute('aria-pressed', gridMode ? 'true' : 'false');
    document.getElementById('progetti').scrollIntoView({ behavior: 'smooth' });
  });
  renderViews();

  function setFilter(f) {
    document.querySelectorAll('.filters .chip').forEach(function (c) { c.classList.toggle('is-on', c.dataset.filter === f); c.setAttribute('aria-pressed', c.dataset.filter === f ? 'true' : 'false'); });
    if (sphere) { sphere.setFilter(f); showProject(sphere.target()); }
    currentFilter = f; page = 1; renderViews();
  }
  document.querySelectorAll('.filters .chip').forEach(function (c) {
    c.addEventListener('click', function () { setFilter(c.dataset.filter); });
  });
  document.querySelectorAll('.world__link').forEach(function (a) {
    a.addEventListener('click', function () { setFilter(a.dataset.filter); });
  });

  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- mondi su mobile: puntini sincronizzati con lo scorrimento ---------- */
  var wl = document.querySelector('.worlds'), wd = document.querySelectorAll('.worlds-dots button');
  if (wl && wd.length) {
    var wCards = wl.querySelectorAll('.world');
    function wIdx() { var max = wl.scrollWidth - wl.clientWidth; if (wl.scrollLeft >= max - 4) return wCards.length - 1; var x = wl.scrollLeft, k = 0; wCards.forEach(function (c, i) { if (c.offsetLeft - wCards[0].offsetLeft <= x + 20) k = i; }); return k; }
    wl.addEventListener('scroll', function () { var k = wIdx(); wd.forEach(function (d, i) { d.classList.toggle('is-on', i === k); }); }, { passive: true });
    wd.forEach(function (d, i) { d.addEventListener('click', function () { wl.scrollTo({ left: Math.min(wl.scrollWidth - wl.clientWidth, wCards[i].offsetLeft - wCards[0].offsetLeft), behavior: 'smooth' }); }); });
  }

  /* ---------- nav: link attivo + menu mobile ---------- */
  var nav = document.getElementById('nav');
  var burger = document.getElementById('burger');
  burger.addEventListener('click', function () {
    var open = nav.classList.toggle('is-open');
    burger.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
  nav.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () { nav.classList.remove('is-open'); burger.setAttribute('aria-expanded', 'false'); });
  });
  var links = {};
  nav.querySelectorAll('.nav__links a').forEach(function (a) { links[a.getAttribute('href').slice(1)] = a; });
  var spy = new IntersectionObserver(function (entries) {
    entries.forEach(function (en) {
      if (!en.isIntersecting) return;
      Object.keys(links).forEach(function (k) { links[k].classList.toggle('is-active', k === en.target.id); });
    });
  }, { rootMargin: '-45% 0px -50% 0px' });
  document.querySelectorAll('main section[id]').forEach(function (s) { spy.observe(s); });

  /* ---------- hero: il video "The future of robotics" parte solo dal pulsante ----------
     Con il file (src sul <video>): titolo e velo spariscono durante la
     riproduzione e ricompaiono in pausa o alla fine. Senza file: un avviso. */
  var hero = document.getElementById('hero');
  var heroVideo = document.getElementById('heroVideo');
  var heroPlay = document.getElementById('heroPlay');
  var heroNote = document.getElementById('heroNote');
  function heroStop() { hero.classList.remove('is-playing'); }
  if (heroPlay) heroPlay.addEventListener('click', function () {
    if (heroVideo && heroVideo.getAttribute('src')) {
      heroVideo.hidden = false; hero.classList.add('is-playing'); heroVideo.play();
    } else {
      heroNote.textContent = 'Il video \u201cThe future of robotics\u201d sarà disponibile a breve.';
    }
  });
  var bigPlay = document.querySelector('[data-play-hero]');
  if (bigPlay && heroPlay) bigPlay.addEventListener('click', function () { heroPlay.click(); });
  if (heroVideo) {
    heroVideo.addEventListener('pause', heroStop);
    heroVideo.addEventListener('ended', function () { heroStop(); heroVideo.hidden = true; });
  }

  /* ---------- video di "Le voci dei progetti" (segnaposto) ---------- */
  document.querySelectorAll('.voice__media').forEach(function (b) {
    b.addEventListener('click', function () {
      var r = b.parentNode.querySelector('.voice__note') || b.parentNode.appendChild(Object.assign(document.createElement('p'), { className: 'voice__note' }));
      r.textContent = 'Il video sarà disponibile a breve.';
    });
  });

  /* ---------- report: timeline guidata dallo scroll ----------
     L'altezza della sezione = schermo + quanto la timeline è più larga dello schermo:
     così scorrendo in verticale la si percorre tutta in orizzontale. */
  var rtl = document.getElementById('rtl');
  if (rtl) {
    var track = rtl.querySelector('.rtl__track'), prog = rtl.querySelector('.rtl__progress');
    var items = rtl.querySelectorAll('.rtl__item');
    var mqStatic = window.matchMedia('(max-width: 900px), (prefers-reduced-motion: reduce)');
    var extra = 0, stickyTop = 0;
    var sticky = rtl.querySelector('.rtl__sticky');
    function rtlSize() {
      if (mqStatic.matches) { rtl.style.height = ''; sticky.style.top = ''; return; }
      sticky.style.top = '';
      extra = Math.max(0, track.scrollWidth - window.innerWidth);
      // il blocco resta fermo centrato nello schermo (sotto il menu), alto solo quanto il contenuto
      var h = sticky.offsetHeight, navEl = document.querySelector('.nav'), navH = navEl ? navEl.getBoundingClientRect().bottom : 0;
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

  /* ---------- form partner (bozza: nessun invio reale) ---------- */
  var form = document.getElementById('partnerForm');
  var msg = document.getElementById('formMsg');
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var ok = form.nome.value.trim() && /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(form.email.value) && form.privacy.checked;
    msg.className = 'form__msg ' + (ok ? 'is-ok' : 'is-err');
    msg.textContent = ok
      ? 'Grazie! (Bozza: in WordPress il form invierà la richiesta ai referenti WeRo.)'
      : 'Compila nome, un\'email valida e accetta l\'informativa privacy.';
    if (ok) form.reset();
  });
})();

/* foto eventi: frecce che scorrono di una foto, disattivate agli estremi */
(function () {
  var row = document.getElementById('photos'), prev = document.getElementById('photosPrev'), next = document.getElementById('photosNext');
  if (!row || !prev || !next) return;
  function step(dir) {
    var items = row.children, pad = parseFloat(getComputedStyle(row).paddingLeft) || 0, x = row.scrollLeft, target = null;
    for (var i = 0; i < items.length; i++) {
      var off = items[i].offsetLeft - row.offsetLeft - pad;
      if (dir > 0 && off > x + 4) { target = off; break; }
      if (dir < 0 && off < x - 4) target = off;
    }
    row.scrollTo({ left: target == null ? (dir > 0 ? row.scrollWidth : 0) : target, behavior: 'smooth' });
  }
  function update() {
    prev.disabled = row.scrollLeft < 4;
    next.disabled = row.scrollLeft > row.scrollWidth - row.clientWidth - 4;
  }
  prev.addEventListener('click', function () { step(-1); });
  next.addEventListener('click', function () { step(1); });
  row.addEventListener('scroll', update, { passive: true });
  addEventListener('resize', update);
  update();
})();
