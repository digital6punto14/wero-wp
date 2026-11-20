/* =========================================================
   WeRo — sfera dei progetti (blocco wero/projects-explorer)
   Componente CardSphere dell'originale (invariato, salvo la variante
   delle card presa dal Mondo) + logica della sezione Progetti:
   pannello informativo, filtri per mondo, lista paginata, vista a griglia.
   I dati arrivano dalla lista #projectList stampata dal server
   (leggibile anche senza JavaScript, da screen reader e motori di ricerca).
   ========================================================= */
/* =========================================================
   Sfera di card — port vanilla di CardSphere (Cue cue200),
   ribrandizzata WeRo.
   Trascina / lancia (o frecce) per ruotarla: plana e si ferma con una
   card progetto ("hero") al centro. Click su una card → in primo piano.
   Rimossi rispetto all'originale: rotella (non ruba lo scroll della
   pagina), doppio click per il tema. Su touch lo scroll verticale
   resta alla pagina (touch-action: pan-y).

   Uso: var s = CardSphere(el, { projects: [...], onSettle(i) })
        s.go(i)  s.next()  s.prev()  s.setFilter('ri'|'ce'|'ld'|'all')
   ========================================================= */
(function () {
  var INK = '#15121a', CARD = '#221d26', PAPER = '#f4f2e9', WHITE = '#fbfaf5', PINK = '#ff3f7a', MAG = '#ff00c7', MID = '#ff20a6';
  var GRAD = 'linear-gradient(135deg,' + PINK + ',' + MAG + ')';

  /* ---------- matematica ---------- */
  function clamp(x, a, b) { a = a == null ? 0 : a; b = b == null ? 1 : b; return Math.min(b, Math.max(a, x)); }
  function lerp(a, b, t) { return a + (b - a) * t; }
  function bez(x1, y1, x2, y2) {
    return function (x) {
      var t = x;
      for (var i = 0; i < 6; i++) {
        var cx = 3 * x1 * t * Math.pow(1 - t, 2) + 3 * x2 * t * t * (1 - t) + Math.pow(t, 3) - x;
        var d = 3 * x1 * Math.pow(1 - t, 2) + 6 * (x2 - x1) * t * (1 - t) + 3 * (1 - x2) * t * t;
        if (Math.abs(d) < 1e-6) break;
        t = clamp(t - cx / d);
      }
      return 3 * y1 * t * Math.pow(1 - t, 2) + 3 * y2 * t * t * (1 - t) + Math.pow(t, 3);
    };
  }
  var SETTLE = bez(0.25, 0.75, 0.2, 1);
  function V(x, y, z) { return { x: x, y: y, z: z }; }
  function norm(a) { var n = Math.hypot(a.x, a.y, a.z) || 1; return V(a.x / n, a.y / n, a.z / n); }
  function dot(a, b) { return a.x * b.x + a.y * b.y + a.z * b.z; }
  function cross(a, b) { return V(a.y * b.z - a.z * b.y, a.z * b.x - a.x * b.z, a.x * b.y - a.y * b.x); }
  function qmul(a, b) {
    return [a[0] * b[0] - a[1] * b[1] - a[2] * b[2] - a[3] * b[3], a[0] * b[1] + a[1] * b[0] + a[2] * b[3] - a[3] * b[2],
      a[0] * b[2] - a[1] * b[3] + a[2] * b[0] + a[3] * b[1], a[0] * b[3] + a[1] * b[2] - a[2] * b[1] + a[3] * b[0]];
  }
  function qaxis(axis, ang) { var s = Math.sin(ang / 2), a = norm(axis); return [Math.cos(ang / 2), a.x * s, a.y * s, a.z * s]; }
  function qnorm(q) { var n = Math.hypot(q[0], q[1], q[2], q[3]); return [q[0] / n, q[1] / n, q[2] / n, q[3] / n]; }
  function qrot(q, v) {
    var w = q[0], x = q[1], y = q[2], z = q[3];
    var ix = w * v.x + y * v.z - z * v.y, iy = w * v.y + z * v.x - x * v.z, iz = w * v.z + x * v.y - y * v.x, iw = -x * v.x - y * v.y - z * v.z;
    return V(ix * w + iw * -x + iy * -z - iz * -y, iy * w + iw * -y + iz * -x - ix * -z, iz * w + iw * -z + ix * -y - iy * -x);
  }
  function qslerp(a, b, t) {
    var c = a[0] * b[0] + a[1] * b[1] + a[2] * b[2] + a[3] * b[3];
    if (c < 0) { b = [-b[0], -b[1], -b[2], -b[3]]; c = -c; }
    if (c > 0.9995) return qnorm([lerp(a[0], b[0], t), lerp(a[1], b[1], t), lerp(a[2], b[2], t), lerp(a[3], b[3], t)]);
    var th = Math.acos(c), s = Math.sin(th), ka = Math.sin((1 - t) * th) / s, kb = Math.sin(t * th) / s;
    return [a[0] * ka + b[0] * kb, a[1] * ka + b[1] * kb, a[2] * ka + b[2] * kb, a[3] * ka + b[3] * kb];
  }
  function qbetween(from, to) {
    var c = clamp(dot(from, to), -1, 1);
    if (c > 0.99999) return [1, 0, 0, 0];
    return qaxis(c < -0.99999 ? V(0, 1, 0) : cross(from, to), Math.acos(c));
  }
  function fib(n, i, off) {
    var y = 1 - (2 * (i + 0.5)) / n, r = Math.sqrt(1 - y * y), t = Math.PI * (3 - Math.sqrt(5)) * i + (off || 0);
    return V(Math.cos(t) * r, y, Math.sin(t) * r);
  }

  /* ---------- grafica delle card (in px "base", poi scalate) ---------- */
  var ICONS = {
    robot: 'M34 40 H66 A8 8 0 0 1 74 48 V70 A8 8 0 0 1 66 78 H34 A8 8 0 0 1 26 70 V48 A8 8 0 0 1 34 40 Z M42 56 V60 M58 56 V60 M50 40 V28 M50 24 V24.1 M18 54 V64 M82 54 V64',
    hand: 'M36 82 V46 M36 46 V30 M46 44 V22 M56 44 V24 M66 48 V32 M36 60 C24 54 22 66 30 72 L44 86 H62 C70 86 72 78 72 70 V48',
    chat: 'M22 30 H78 V64 H48 L34 78 V64 H22 Z M36 46 H64 M36 54 H54',
    eye: 'M16 50 C32 26 68 26 84 50 C68 74 32 74 16 50 Z M50 40 A10 10 0 1 1 49.9 40',
    gear: 'M50 34 A16 16 0 1 1 49.9 34 M50 18 V26 M50 74 V82 M18 50 H26 M74 50 H82 M27 27 L33 33 M67 67 L73 73 M73 27 L67 33 M33 67 L27 73',
    wave: 'M14 50 C24 30 34 30 44 50 S64 70 74 50 S84 36 88 44'
  };
  var ICK = Object.keys(ICONS);
  function iconSvg(k, size, col) {
    return '<svg viewBox="0 0 100 100" width="' + size + '" height="' + size + '"><path d="' + ICONS[k] + '" fill="none" stroke="' + (col || '#fff') +
      '" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="0.01 7"/></svg>';
  }
  function dotCluster(w, h, col, seedN) {
    var s = seedN * 9301 + 49297; var r = function () { return ((s = (s * 9301 + 49297) % 233280) / 233280); };
    var out = '';
    for (var y = 0; y < 5; y++) for (var x = 0; x < 4; x++) { var v = r(); if (v < 0.22) continue; out += '<circle cx="' + (16 + x * 23) + '" cy="' + (14 + y * 24) + '" r="' + (2.5 + v * 8.5) + '"/>'; }
    return '<svg viewBox="0 0 100 124" width="' + w * 0.8 + '" height="' + h * 0.8 + '"><g fill="' + col + '">' + out + '</g></svg>';
  }
  function pixels(w, h, seedN) {
    var s = seedN * 16807 % 2147483647 || 7; var r = function () { return ((s = (s * 16807) % 2147483647) / 2147483647); };
    var cols = [PINK, MID, MAG, '#ffd1e4', INK], out = '', n = 6, m = 8, cw = 100 / n, ch = 124 / m;
    for (var y = 0; y < m; y++) for (var x = 0; x < n; x++) { var v = r(); if (v < 0.45 - y * 0.04) continue; out += '<rect x="' + (x * cw + 1) + '" y="' + (y * ch + 1) + '" width="' + (cw - 2) + '" height="' + (ch - 2) + '" rx="2" fill="' + cols[Math.floor(r() * cols.length)] + '"/>'; }
    return '<svg viewBox="0 0 100 124" width="' + w + '" height="' + h + '" preserveAspectRatio="none" style="position:absolute;inset:0">' + out + '</svg>';
  }
  var SHAPES = {   // tratti "a tubo" come il logo
    u: '<path d="M26 22 V54 A24 24 0 0 0 74 54 V22" />',
    s: '<path d="M76 24 H44 A16 16 0 0 0 44 56 H56 A16 16 0 0 1 56 88 H24" />',
    o: '<rect x="24" y="24" width="52" height="52" rx="12" />',
    l: '<path d="M30 20 V58 A18 18 0 0 0 48 76 H80" />'
  };
  var SK = Object.keys(SHAPES);
  var WORDS = ['H2M', 'Ricerca', 'Business', 'Creatività', 'Tools for human beings', 'Collaborative Intelligence', 'Robot Communication Studio'];
  var mono = '';
  var lbl = "position:absolute;font-family:'Space Grotesk',system-ui,sans-serif;" + 'font-weight:600;line-height:1;letter-spacing:-.03em;';

  var ART = {
    project: function (w, h, o) {
      var p = o.project, dark = o.variant !== 'paper', fg = dark ? '#fff' : INK, sub = dark ? 'rgba(255,255,255,.62)' : 'rgba(21,18,26,.6)';
      return '' +
        '<div style="' + lbl + mono + 'left:' + w * 0.07 + 'px;top:' + w * 0.07 + 'px;font-size:' + w * 0.034 + 'px;font-size:' + w * 0.04 + 'px;font-weight:600;letter-spacing:0;color:' + (o.variant === 'grad' ? '#fff' : PINK) + '">' + p.type + '</div>' +
        '<div style="' + lbl + mono + 'right:' + w * 0.07 + 'px;top:' + w * 0.07 + 'px;font-size:' + w * 0.034 + 'px;color:' + sub + '">' + (p.year || '') + '</div>' +
        '<div style="position:absolute;left:' + w * 0.07 + 'px;top:' + h * 0.17 + 'px;right:' + w * 0.07 + 'px;height:' + h * 0.4 + 'px;border-radius:' + w * 0.03 + 'px;overflow:hidden;' +
          (dark ? 'background:rgba(255,255,255,.06)' : 'background:rgba(21,18,26,.06)') + '">' +
          // con un'immagine vera (data-image nella lista) la mostra; altrimenti il segnaposto grafico
          (p.image ? '<img src="' + p.image + '" alt="" draggable="false" style="display:block;width:100%;height:100%;object-fit:cover">' :
          (o.variant === 'grad' ? dotCluster(w * 0.86, h * 0.4, 'rgba(255,255,255,.75)', o.seed) : iconSvg(p.icon || 'robot', h * 0.36, dark ? PINK : MAG).replace('<svg', '<svg style="display:block;margin:' + h * 0.02 + 'px auto"'))) +
        '</div>' +
        '<div style="' + lbl + 'left:' + w * 0.07 + 'px;right:' + w * 0.07 + 'px;bottom:' + h * 0.15 + 'px;font-size:' + w * 0.082 + 'px;line-height:1.02;font-weight:700;color:' + fg + '">' + p.title + '</div>' +
        '<div style="' + lbl + 'left:' + w * 0.07 + 'px;bottom:' + w * 0.07 + 'px;font-size:' + w * 0.03 + 'px;padding:' + w * 0.018 + 'px ' + w * 0.03 + 'px;border-radius:99px;font-weight:600;letter-spacing:0;' +
          (o.variant === 'grad' ? 'background:#fff;color:' + MAG : 'background:' + GRAD + ';color:#fff') + '">' + p.worldName + '</div>';
    },
    icon: function (w, h, o) { return iconSvg(o.icon, w * 0.72, o.fg || '#fff'); },
    dots: function (w, h, o) { return dotCluster(w, h, o.col || PINK, o.seed || 3); },
    pixel: function (w, h, o) { return pixels(w, h, o.seed || 5); },
    shape: function (w, h, o) { return '<svg viewBox="0 0 100 100" width="' + w * 0.7 + '" height="' + w * 0.7 + '"><g fill="none" stroke="' + (o.fg || INK) + '" stroke-width="13" stroke-linecap="butt" stroke-linejoin="round">' + SHAPES[o.shape] + '</g></svg>'; },
    word: function (w, h, o) { var long = o.word.length > 10; return '<div style="font:700 ' + w * (long ? 0.13 : 0.24) + 'px/1.02 Space Grotesk,system-ui,sans-serif;letter-spacing:-.04em;color:' + (o.fg || '#fff') + ';padding:' + w * 0.09 + 'px;align-self:end">' + o.word + '</div>'; },
    chip: function (w) { return '<span style="font-family:Space Grotesk,sans-serif;font-size:' + w * 0.11 + 'px;font-weight:600;padding:' + w * 0.06 + 'px ' + w * 0.08 + 'px;border-radius:99px;background:' + GRAD + ';color:#fff;white-space:nowrap">WeRo</span>'; },
    mark: function (w) { return '<div style="font:600 ' + w * 0.22 + 'px/.95 Space Grotesk,sans-serif;color:#fff;letter-spacing:-.04em;text-align:left;width:100%;padding:0 ' + w * 0.1 + 'px">We<br>Ro</div>'; }
  };

  var CSS =
    '.csph{position:relative;width:100%;height:100%;min-height:320px;overflow:hidden;contain:strict;user-select:none;-webkit-user-select:none;outline:none;touch-action:pan-y;cursor:grab}' +
    '.csph.grabbing{cursor:grabbing}' +
    '.csph:focus-visible{box-shadow:inset 0 0 0 3px ' + MAG + '}' +
    '.csph-c{position:absolute;left:0;top:0;transform-origin:0 0;will-change:transform,opacity;overflow:hidden;backface-visibility:hidden;box-sizing:border-box;box-shadow:0 16px 30px -16px rgba(21,18,26,.38);transition:outline-color .35s ease;outline:3px solid transparent;outline-offset:-3px}' +
    '.csph--lite .csph-c{box-shadow:0 8px 16px -10px rgba(21,18,26,.35);transition:none}' +
    '.csph-c.is-active{outline-color:rgba(255,0,199,.9)}' +
    
    '.csph-project{background:' + INK + '}' +
    '.csph-icon{background:' + CARD + ';display:grid;place-items:center}' +
    '.csph-dots{background:' + INK + ';display:grid;place-items:center}' +
    '.csph-cloud{position:absolute;left:0;top:0;width:100%;height:100%;pointer-events:none;opacity:0;will-change:opacity,transform;z-index:0}' +
    '.csph--lowmove .csph-c{opacity:0!important}' +
    '.csph-pixel{background:' + WHITE + '}' +
    '.csph-shape{background:' + WHITE + ';display:grid;place-items:center}' +
    '.csph-word{background:' + INK + ';display:grid;place-items:end start}' +
    '.csph-chip{background:' + WHITE + ';display:grid;place-items:center}' +
    '.csph-mark{background:' + GRAD + ';display:grid;place-items:center}' +
    '@media (prefers-reduced-motion:reduce){.csph-c{will-change:auto}}';

  var P = 2;     // distanza camera / raggio
  var NS = 48;          // posti candidati per le card decorative
  // card decorative effettive: poche, solo forme del brand (ancora meno su mobile: meno livelli per la GPU)
  var MAX_SMALL = window.matchMedia('(max-width: 900px), (pointer: coarse)').matches ? 10 : 18;
  // un colore per mondo: Robotics Intelligence = inchiostro, Conversation & Events = gradiente, Language & Design = carta
  var WORLD_VARIANT = { ri: 'ink', ce: 'grad', ld: 'paper' };

  function buildCards(projects) {
    var seed = 7; var rnd = function () { return ((seed = (seed * 16807) % 2147483647) / 2147483647); };
    var heroes = projects.map(function (p, i) {
      var v = p.variant || WORLD_VARIANT[p.world] || 'ink';   /* WordPress: variante dallo stile del bollino del Mondo */
      return { type: 'project', project: p, index: i, w: 400 + (i % 3) * 10, h: 510 + (i % 2) * 14, rz: [-1.5, 0.6, -0.8, 1.2][i % 4],
        p: fib(projects.length, i, 1.1), hero: true, variant: v, seed: i + 3,
        bg: v === 'grad' ? GRAD : v === 'paper' ? WHITE : INK };
    });
    var minDot = projects.length > 10 ? 0.93 : 0.9;
    var TYPES = ['shape', 'shape', 'shape', 'pixel', 'dots'];   // niente parole né icone
    var small = [];
    for (var i = 0; i < NS; i++) {
      var p = fib(NS, i, 0.37);
      p.x += (rnd() - 0.5) * 0.08; p.y += (rnd() - 0.5) * 0.08; p.z += (rnd() - 0.5) * 0.08;
      var q = norm(p);
      if (heroes.some(function (h) { return dot(h.p, q) > minDot; })) continue;
      var type = TYPES[Math.floor(rnd() * TYPES.length)];
      var w = type === 'chip' ? 150 + rnd() * 50 : 110 + rnd() * 120;
      var h = type === 'chip' ? w * 0.5 : w * (1.05 + rnd() * 0.3);
      var o = { type: type, w: w, h: h, p: q, rz: (rnd() - 0.5) * 8, seed: Math.floor(rnd() * 999) };
      if (type === 'icon') { o.icon = ICK[Math.floor(rnd() * ICK.length)]; if (rnd() < 0.35) { o.bg = GRAD; o.fg = '#fff'; } else o.fg = PINK; }
      if (type === 'dots' && rnd() < 0.45) { o.bg = GRAD; o.col = INK; }
      if (type === 'shape') { var pair = [[WHITE, MAG], [PINK, '#fff'], [INK, PINK], [MAG, INK]][Math.floor(rnd() * 4)]; o.bg = pair[0]; o.fg = pair[1]; o.shape = SK[Math.floor(rnd() * SK.length)]; }
      if (type === 'word') { o.word = WORDS[Math.floor(rnd() * WORDS.length)]; if (rnd() < 0.4) { o.bg = WHITE; o.fg = MAG; } }
      small.push(o);
    }
    // tiene MAX_SMALL card distribuite su tutta la sfera (non solo le prime, che starebbero in alto)
    if (small.length > MAX_SMALL) {
      var step = small.length / MAX_SMALL, pick = [];
      for (var j = 0; j < MAX_SMALL; j++) pick.push(small[Math.floor(j * step)]);
      small = pick;
    }
    return heroes.concat(small);
  }

  function injectCSS() {
    if (document.getElementById('csph-css')) return;
    var s = document.createElement('style'); s.id = 'csph-css'; s.textContent = CSS; document.head.appendChild(s);
  }

  function CardSphere(root, opts) {
    opts = opts || {};
    injectCSS();
    root.classList.add('csph');
    if (window.matchMedia('(max-width: 900px), (pointer: coarse)').matches) root.classList.add('csph--lite');
    var ALL = buildCards(opts.projects || []);
    var heroes = ALL.filter(function (o) { return o.hero; });
    var filter = 'all';

    ALL.forEach(function (o) {
      var e = document.createElement('div');
      e.className = 'csph-c csph-' + o.type;
      e.style.width = o.w + 'px'; e.style.height = o.h + 'px';
      e.style.borderRadius = Math.max(6, o.w * 0.045) + 'px';
      if (o.bg) e.style.background = o.bg;
      e.innerHTML = ART[o.type](o.w, o.h, o);
      if (o.hero) { e.setAttribute('aria-hidden', 'true'); }
      e._o = o;
      root.appendChild(e); o.el = e; o.hv = 1;
    });

    var W = 0, H = 0, u = 1, F = 1, Ax = 1, Ay = 1;
    function size() {
      var r = root.getBoundingClientRect(); W = r.width; H = r.height;
      u = Math.min(W, H * 0.8) / 800; F = 502 * u;
      // la nuvola sta tutta dentro il riquadro: niente card tagliate ai lati o in alto
      Ax = Math.max(.9, (W / 2) / (F * 0.577) * 0.74); Ay = Math.max(.8, (H / 2) / (F * 0.577) * 0.64);
    }
    size();
    var ro = new ResizeObserver(function () { size(); wake(); }); ro.observe(root);

    var FRONT = V(0, 0, 1);
    var rot = qbetween(heroes[0].p, FRONT);
    var wv = V(0, 0, 0);
    var drag = null, snap = null, hover = null, lastInput = 0, settled = true, current = 0;

    function eligible() { return heroes.filter(function (h) { return filter === 'all' || h.project.world === filter; }); }

    function startSnap(target) {
      var sp = Math.hypot(wv.x, wv.y, wv.z);
      var ahead = sp > 0 ? qnorm(qmul(qaxis(wv, Math.min(2.4, sp * 0.3)), rot)) : rot;
      var pool = eligible(); if (!pool.length) pool = heroes;
      var best = target || pool[0], bz = -2;
      if (!target) pool.forEach(function (h) { var z = qrot(ahead, h.p).z; if (z > bz) { bz = z; best = h; } });
      var to = qnorm(qmul(qbetween(qrot(rot, best.p), FRONT), rot));
      var ang = 2 * Math.acos(clamp(Math.abs(rot[0] * to[0] + rot[1] * to[1] + rot[2] * to[2] + rot[3] * to[3])));
      snap = { from: rot, to: to, t0: performance.now(), ms: lite ? Math.min(620, 360 + ang * 160) : 420 + ang * 260, hero: heroes.indexOf(best) }; settled = false;
    }

    var busy = true;   // c'è ancora qualcosa da animare?
    /* Modalità "focus" (mobile): la nuvola si vede solo mentre si naviga; a riposo
       resta in primo piano solo la card del progetto, dritta e più grande.
       opts.focusOnRest può essere un MediaQueryList (si legge a ogni frame) o un booleano. */
    var focus = 0, calm = 0;
    /* Desktop adattivo: se mentre la sfera si muove il browser fa fatica (fotogrammi lenti),
       durante il movimento si passa alla nuvola a sagome su canvas; a sfera ferma tornano le card vere.
       ?sphere=low nell'indirizzo forza questa modalità (per provarla). */
    var low = /[?&]sphere=low\b/.test(location.search), moving = false, perf = [], slowRuns = 0;
    if (low) root.classList.add('csph--low');
    var liteMq = window.matchMedia('(max-width: 900px), (pointer: coarse)'), lite = liteMq.matches;
    if (liteMq.addEventListener) liteMq.addEventListener('change', function () { lite = liteMq.matches; root.classList.toggle('csph--lite', lite); flat = null; wake(); });
    function focusEnabled() { var f = opts.focusOnRest; return !!(f && (typeof f === 'object' ? f.matches : f)); }
    /* Modalità piatta (mobile): niente nuvola 3D. Si vede solo la card del progetto,
       a tutto riquadro; il cambio progetto è uno scorrimento orizzontale tra due card.
       Due soli elementi visibili: nessun fotogramma vuoto sui telefoni. */
    var flat = null;   // { from: indice hero uscente, t0, dir }
    function flatOn() { return lite && focusEnabled(); }
    /* Nuvola mobile in 3D su un solo canvas. La geometria (centro e quattro angoli di ogni
       card, orientata sulla superficie della sfera) si calcola UNA volta; durante il cambio
       progetto la sfera ruota davvero e gli angoli vengono proiettati in prospettiva.
       Card monocolore: decine di poligoni su un canvas costano molto meno di decine di livelli. */
    var cloud = null, cg = null, geo = null, cloudW = 0, dprC = 1;
    function flatColor(o) {
      var bg = o.bg || getComputedStyle(o.el).backgroundColor || INK;
      if (/gradient/.test(bg)) return '#ff1f9f';
      if (/rgba\(0, 0, 0, 0\)|transparent/.test(bg)) return WHITE;
      return bg;
    }
    function buildGeo() {
      var UP = V(0, 1, 0);
      geo = ALL.map(function (o) {
        var n = norm(o.p), t1 = cross(UP, n);
        t1 = Math.hypot(t1.x, t1.y, t1.z) < 0.01 ? V(1, 0, 0) : norm(t1);
        var t2 = cross(n, t1), a = o.rz * Math.PI / 180, ca = Math.cos(a), sa = Math.sin(a);
        var e1 = V(t1.x * ca + t2.x * sa, t1.y * ca + t2.y * sa, t1.z * ca + t2.z * sa);
        var e2 = V(t2.x * ca - t1.x * sa, t2.y * ca - t1.y * sa, t2.z * ca - t1.z * sa);
        var col = flatColor(o);
        return { o: o, n: n, e1: e1, e2: e2, col: col, light: col === WHITE };
      });
    }
    function sizeCloud() {
      if (!cloud) { cloud = document.createElement('canvas'); cloud.className = 'csph-cloud'; cloud.setAttribute('aria-hidden', 'true'); root.insertBefore(cloud, root.firstChild); cg = cloud.getContext('2d'); }
      if (!geo) buildGeo();
      if (!W || W === cloudW) return;
      cloudW = W; dprC = Math.min(2, window.devicePixelRatio || 1);
      cloud.width = Math.round(W * dprC); cloud.height = Math.round(H * dprC);
    }
    // fo: card del progetto che entra/esce dal centro; tf: 0 = al suo posto nella sfera, 1 = al centro a tutto riquadro;
    // oa: opacità del resto della sfera
    function drawCloud(R, fo, tf, oa) {
      var g = cg, cx = W / 2, cy = H / 2;
      g.setTransform(dprC, 0, 0, dprC, 0, 0); g.clearRect(0, 0, W, H);
      var list = [], focusIt = null;
      for (var i = 0; i < geo.length; i++) {
        // come la sfera desktop: card rivolte verso chi guarda, inclinate in base alla posizione,
        // più piccole quando sono lontane (stessa formula di scala)
        var G = geo[i], o = G.o, c = qrot(R, G.n);
        var kz = clamp((c.z - 0.2) / 0.55), hf = o.hero ? lerp(0.5, 1, kz * kz * (3 - 2 * kz)) : 0.5;
        var ry = Math.atan2(c.x, Math.max(0.2, c.z)) * 0.35, rx = Math.atan2(c.y, Math.max(0.2, c.z)) * 0.35, rz = o.rz * Math.PI / 180;
        var cyr = Math.cos(ry), syr = Math.sin(ry), cxr = Math.cos(rx), sxr = Math.sin(rx), czr = Math.cos(rz), szr = Math.sin(rz);
        var hw = o.w * u * hf / (2 * F * Ax), hh = o.h * u * hf / (2 * F * Ay), pts = [];
        var C = [[-1, -1], [1, -1], [1, 1], [-1, 1]];
        for (var j = 0; j < 4; j++) {
          var lx = C[j][0] * hw, ly = -C[j][1] * hh;
          var x1 = lx * czr - ly * szr, y1 = lx * szr + ly * czr;            // rotazione nel piano
          var y2 = y1 * cxr, z2 = -y1 * sxr;                                   // inclinazione su X
          var x3 = x1 * cyr + z2 * syr, z3 = -x1 * syr + z2 * cyr;             // inclinazione su Y
          var x = c.x + x3, y = c.y + y2, z = c.z + z3, sp = 1 / (P - z);
          pts.push(cx + x * F * sp * Ax, cy - y * F * sp * Ay);
        }
        var op = o.hero ? clamp((c.z + 0.9) / 0.3) : clamp((c.z + 0.8) / 0.35);
        if (o === fo) {
          var fit = Math.min((W * 0.74) / o.w, (H * 0.88) / o.h), w2 = o.w * fit / 2, h2 = o.h * fit / 2;
          for (var m = 0; m < 4; m++) { pts[m * 2] = lerp(pts[m * 2], cx + C[m][0] * w2, tf); pts[m * 2 + 1] = lerp(pts[m * 2 + 1], cy + C[m][1] * h2, tf); }
          var focusIt = { z: 99, pts: pts, G: G, op: 1, focus: true, rect: tf >= 1 ? [cx - w2, cy - h2, 2 * w2, 2 * h2, Math.max(6, o.w * 0.045) * fit] : null };
          continue;
        }
        if (op > 0.02 && oa > 0.01) list.push({ z: c.z, pts: pts, G: G, op: op * oa });
      }
      list.sort(function (a, b) { return a.z - b.z; });
      if (focusIt) list.push(focusIt);
      for (var k = 0; k < list.length; k++) {
        var it = list[k], q = it.pts, depth = it.focus ? 1 : clamp((it.z + 1) / 2);
        g.globalAlpha = it.op * (0.45 + 0.55 * depth);
        g.beginPath();
        if (it.rect && g.roundRect) g.roundRect(it.rect[0], it.rect[1], it.rect[2], it.rect[3], it.rect[4]);
        else { g.moveTo(q[0], q[1]); g.lineTo(q[2], q[3]); g.lineTo(q[4], q[5]); g.lineTo(q[6], q[7]); g.closePath(); }
        g.fillStyle = it.G.col; g.fill();
        // ombra di profondità: le card dietro sono più scure, quelle chiare hanno un filo di bordo
        if (depth < 0.6) { g.globalAlpha = (0.6 - depth) * 0.5; g.fillStyle = INK; g.fill(); }
        if (it.G.light) { g.globalAlpha = 0.18; g.lineWidth = 1; g.strokeStyle = INK; g.stroke(); }
      }
      g.globalAlpha = 1;
    }
    var FLAT_MS = 1400;
    function ease(t) { return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2; }
    function renderFlat() {
      sizeCloud();
      var cx = W / 2, cy = H / 2, front = heroes[current], p = 1, out = null;
      if (flat) { p = clamp((performance.now() - flat.t0) / FLAT_MS); out = heroes[flat.from]; }
      var dx = drag ? drag.dx || 0 : 0, dir = flat ? flat.dir : 1;
      /* sequenza (p da 0 a 1):
         0–0.10  la card vera sfuma e resta la sua sagoma monocolore a tutto riquadro
         0.10–0.30  la sagoma torna al suo posto nella sfera, il resto della sfera compare
         0.30–0.64  la sfera ruota fino al nuovo progetto
         0.64–0.86  la sagoma del nuovo progetto viene al centro, il resto della sfera sparisce
         0.86–1  la card vera compare sopra la sagoma */
      var ph = function (a, b) { return ease(clamp((p - a) / (b - a))); };
      if (flat) {
        var back = p < 0.3, fo = back ? out : front;
        var tf = back ? 1 - ph(0.10, 0.30) : ph(0.64, 0.86);
        var oa = back ? ph(0.10, 0.30) : 1 - ph(0.64, 0.86);
        drawCloud(qslerp(flat.r0, flat.r1, ph(0.30, 0.64)), fo, tf, oa);
      }
      var cOp = flat && p < 1 ? 1 : 0;
      if (cloud._op !== cOp) { cloud.style.opacity = String(cOp); cloud._op = cOp; }
      for (var i = 0; i < ALL.length; i++) {
        var o = ALL[i], el = o.el, role = o === front ? 1 : (out && o === out && out !== front && p < 0.1 ? 2 : 0);
        if (!role) { if (el.style.display !== 'none') el.style.display = 'none'; continue; }
        if (el.style.display === 'none') el.style.display = '';
        var fit = Math.min((W * 0.74) / o.w, (H * 0.88) / o.h), x = cx + dx, op = 1, clip = '';
        if (role === 2) op = 1 - clamp(p / 0.1);
        else if (flat) op = clamp((p - 0.86) / 0.14);
        el.style.transform = 'translate(' + x + 'px,' + cy + 'px) scale(' + fit + ') translate(' + (-o.w / 2) + 'px,' + (-o.h / 2) + 'px)';
        el.style.opacity = String(op);
        if (el._clip !== clip) { el.style.clipPath = clip; el._clip = clip; }
        el.style.zIndex = role === 1 ? '2' : '1';
        if (el._f !== '') { el.style.filter = ''; el._f = ''; }
        if (el._v !== '') { el.style.visibility = ''; el._v = ''; }
        if (el._on) { el.classList.remove('is-active'); el._on = false; }
      }
      if (flat && p >= 1) flat = null;
      busy = !!flat;
    }
    // porta subito la sfera sul progetto (serve se poi si passa a desktop) e avvia lo scorrimento
    function flatGo(h, dir) {
      var idx = heroes.indexOf(h); if (idx === current) return;
      var r0 = rot; startSnap(h); rot = snap.to; snap = null; wv = V(0, 0, 0); settled = true;
      flat = { from: current, t0: performance.now(), dir: dir || 1, r0: r0, r1: rot };
      current = idx; opts.onSettle && opts.onSettle(idx); wake();
    }

    function render(dt) {
      if (flatOn()) { renderFlat(); return; }
      if (cloud && !low) cloud.style.opacity = '0';
      for (var ci = 0; ci < heroes.length; ci++) if (heroes[ci].el._clip) { heroes[ci].el.style.clipPath = ''; heroes[ci].el._clip = ''; }
      var cx = W / 2, cy = H / 2, hoverBusy = false;
      if (!drag && !snap) {
        var sp = Math.hypot(wv.x, wv.y, wv.z);
        if (sp > 0.02) { rot = qnorm(qmul(qaxis(wv, sp * dt), rot)); var k0 = Math.pow(0.88, dt * 30); wv = V(wv.x * k0, wv.y * k0, wv.z * k0); }
        if (!settled && sp < 1.6 && performance.now() - lastInput > 60) startSnap();
      }
      if (snap) {
        var t = clamp((performance.now() - snap.t0) / snap.ms);
        rot = qslerp(snap.from, snap.to, SETTLE(t));
        if (t >= 1) { var hIdx = snap.hero; snap = null; wv = V(0, 0, 0); settled = true; current = hIdx; opts.onSettle && opts.onSettle(hIdx); }
      }
      // calcolato dopo l'eventuale assestamento, così la messa a fuoco parte nello stesso fotogramma
      var resting = settled && !drag && !snap && Math.hypot(wv.x, wv.y, wv.z) <= 0.02;
      moving = !resting;
      if (low && moving) {
        // in movimento: solo la nuvola a sagome (un livello), le card vere restano invisibili ma pronte
        sizeCloud();
        if (!root.classList.contains('csph--lowmove')) { root.classList.add('csph--lowmove'); cloud.style.transition = 'none'; cloud.style.opacity = '1'; cloud._op = 1; }
        drawCloud(rot, null, 0, 1);
        busy = true;
        return;
      }
      if (root.classList.contains('csph--lowmove')) {
        // a sfera ferma: tornano le card vere e la nuvola sfuma via
        root.classList.remove('csph--lowmove');
        cloud.style.transition = 'opacity .3s ease'; cloud.style.opacity = '0'; cloud._op = 0;
      }
      var focusT = focusEnabled() && resting ? 1 : 0;
      // entra in ~0,4 s, esce più in fretta (~0,15 s) appena si tocca la sfera
      focus = lerp(focus, focusT, 1 - Math.pow(focusT ? 0.002 : 0.00001, dt));
      if (Math.abs(focus - focusT) < 0.002) focus = focusT;
      var front = heroes[current];
      // attenuazione delle altre card: graduale, mai a scatto
      calm = lerp(calm, resting && !lite ? 1 : 0, 1 - Math.pow(0.004, dt));
      if (Math.abs(calm - (resting && !lite ? 1 : 0)) < 0.002) calm = resting && !lite ? 1 : 0; else hoverBusy = true;
      for (var i = 0; i < ALL.length; i++) {
        var o = ALL[i], e = o.el;
        var q = qrot(rot, o.p), d = P - q.z, s = 1 / d;
        var sx = cx + q.x * F * s * Ax, sy = cy - q.y * F * s * Ay;
        var k = clamp((q.z - 0.2) / 0.55);
        var hf = o.hero ? lerp(0.5, 1, k * k * (3 - 2 * k)) : 0.5;
        var sc = s * u * hf;
        var ry = Math.atan2(q.x, Math.max(0.2, q.z)) * 57.3 * 0.35, rx = Math.atan2(q.y, Math.max(0.2, q.z)) * 57.3 * 0.35;
        // card attiva in rilievo: a sfera ferma la card davanti cresce, si stacca con un'ombra forte
        // e le altre si attenuano un po'
        var actT = (!lite && resting && o === front) ? 1 : 0;   // su mobile la messa a fuoco basta: niente ingrandimento extra
        o.act = lerp(o.act || 0, actT, 1 - Math.pow(0.002, dt));
        if (Math.abs(o.act - actT) > 0.002) hoverBusy = true; else o.act = actT;
        var hvT = (o === hover ? 1.04 : 1) * (1 + 0.12 * o.act);
        o.hv = lerp(o.hv, hvT, 1 - Math.pow(0.001, dt));
        if (Math.abs(o.hv - hvT) > 0.0005) hoverBusy = true; else o.hv = hvT;
        var op = o.hero ? clamp((q.z + 0.9) / 0.3) : clamp((q.z + 0.8) / 0.35);
        var rz = o.rz;
        if (focus > 0) {
          if (o === front) {
            // la card in primo piano si raddrizza e si ingrandisce fino a riempire il riquadro
            var fit = Math.min((W * 0.74) / o.w, (H * 0.88) / o.h);
            sc = lerp(sc, Math.max(sc, fit), focus);
            sx = lerp(sx, cx, focus); sy = lerp(sy, cy, focus);
            rx *= 1 - focus; ry *= 1 - focus; rz *= 1 - focus;
          } else {
            op *= 1 - focus;   // il resto della nuvola sparisce
          }
        }
        var ew = o.w * sc * o.hv, eh = o.h * sc * o.hv;
        if (sx + ew < -40 || sx - ew > W + 40 || sy + eh < -40 || sy - eh > H + 40) { if (e.style.display !== 'none') e.style.display = 'none'; continue; }
        if (e.style.display === 'none') e.style.display = '';
        e.style.transform = 'translate(' + sx + 'px,' + sy + 'px) scale(' + sc * o.hv + ') perspective(' + o.w * 2.6 + 'px) rotateX(' + rx + 'deg) rotateY(' + ry + 'deg) rotate(' + rz + 'deg) translate(' + (-o.w / 2) + 'px,' + (-o.h / 2) + 'px)';
        e.style.zIndex = String(o === front && (focus > 0 || o.act > 0.01) ? 9999 : Math.round((3 - d) * 1000));
        if (o.hero) {
          var on = o.act > 0.5;
          if (e._on !== on) { e.classList.toggle('is-active', on); e._on = on; }
          if (o !== front) op *= 1 - 0.2 * calm;
        }
        if (o.dim && lite) op *= 0.3;
        e.style.opacity = String(op);
        // niente sfocatura di profondità: su 30 card faceva sfarfallare e rallentare
        // su mobile le card fuori filtro si attenuano solo con l'opacità (niente filtri da ricalcolare)
        var f = o.dim && !lite ? 'grayscale(1) opacity(.35)' : '';
        if (e._f !== f) { e.style.filter = f; e._f = f; }
        // su mobile le card non vengono mai nascoste: il browser le terrebbe fuori memoria e,
        // alla ricomparsa della nuvola, dovrebbe ridisegnarle tutte insieme (fotogrammi vuoti)
        var vis = (!lite && op < 0.01) ? 'hidden' : '';
        if (e._v !== vis) { e.style.visibility = vis; e._v = vis; }
      }
      busy = !!drag || !!snap || !settled || Math.hypot(wv.x, wv.y, wv.z) > 0.02 || hoverBusy || focus !== focusT;
    }

    var samples = [];
    function turn(dx, dy) {
      var ang = Math.hypot(dx / Ax, dy / Ay) / (F * 0.5);
      if (ang > 0) rot = qnorm(qmul(qaxis(V(dy / Ay, dx / Ax, 0), ang), rot));
    }
    function flick(vx, vy) { var k = 1 / (F * 0.5); wv = V((vy / Ay) * k, (vx / Ax) * k, 0); }
    function cardAt(t) { var el = t && t.closest ? t.closest('.csph-c') : null; return el ? el._o : null; }

    function onDown(e) {
      if (e.button != null && e.button !== 0) return;
      if (flatOn()) { drag = { x0: e.clientX, y0: e.clientY, dx: 0, flat: true }; return; }
      root.setPointerCapture(e.pointerId); drag = { x: e.clientX, y: e.clientY, moved: 0, target: e.target }; snap = null; wv = V(0, 0, 0); settled = false;
      samples.length = 0; root.classList.add('grabbing'); wake();
    }
    function onMove(e) {
      if (!drag) { var hv = cardAt(e.target); if (hv !== hover) { hover = hv; wake(); } return; }
      if (drag.flat) {   // la card segue il dito in orizzontale (lo scorrimento verticale della pagina resta libero)
        var fx = e.clientX - drag.x0, fy = e.clientY - drag.y0;
        if (Math.abs(fx) > Math.abs(fy)) { drag.dx = fx * 0.6; renderFlat(); }
        return;
      }
      var dx = e.clientX - drag.x, dy = e.clientY - drag.y;
      drag.x = e.clientX; drag.y = e.clientY; drag.moved += Math.abs(dx) + Math.abs(dy);
      turn(dx, dy);
      var now = performance.now(); samples.push([now, dx, dy]);
      while (samples.length && now - samples[0][0] > 90) samples.shift();
    }
    function onUp(e) {
      if (!drag) return;
      if (drag.flat) {
        var sw = drag.dx; drag = null;
        if (Math.abs(sw) > 30) { if (sw < 0) api.next(); else api.prev(); } else renderFlat();
        return;
      }
      root.classList.remove('grabbing');
      var moved = drag.moved, tgt = drag.target; drag = null; lastInput = performance.now(); wake();
      if (moved < 6) { var o = cardAt(tgt); startSnap(o && o.hero ? o : null); return; }
      var span = samples.length > 1 ? Math.max(0.016, (samples[samples.length - 1][0] - samples[0][0]) / 1000) : 0.016;
      var sx = 0, sy = 0; samples.forEach(function (s) { sx += s[1]; sy += s[2]; });
      flick(sx / span, sy / span);
    }
    function onLeave() { if (!drag) { hover = null; wake(); } }
    function onKey(e) {
      var m = { ArrowLeft: [-1, 0], ArrowRight: [1, 0], ArrowUp: [0, -1], ArrowDown: [0, 1] }[e.key];
      if (m) { e.preventDefault(); snap = null; settled = false; flick(-m[0] * 1500 * u, -m[1] * 1500 * u); lastInput = performance.now(); wake(); }
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); api.next(); }
    }
    root.addEventListener('pointerdown', onDown);
    root.addEventListener('pointermove', onMove);
    root.addEventListener('pointerup', onUp);
    root.addEventListener('pointercancel', onUp);
    root.addEventListener('pointerleave', onLeave);
    root.addEventListener('keydown', onKey);

    // ciclo di disegno: gira solo finché c'è movimento, poi si ferma (zero lavoro a sfera ferma)
    var raf = 0, last = performance.now(), visible = true;
    function loop(now) {
      var raw = now - last, dt = Math.min(0.05, raw / 1000); last = now;
      render(dt);
      // misura la fluidità mentre la sfera si muove (solo desktop, finché non è già in modalità leggera)
      // due finestre lente di fila (circa 80 fotogrammi) prima di cambiare: un singolo intoppo non basta
      if (!lite && !low && moving && raw < 1000) {
        perf.push(raw);
        if (perf.length >= 40) {
          perf.sort(function (a, b) { return a - b; });
          slowRuns = perf[20] > 28 ? slowRuns + 1 : 0;                         // mediana sotto ~36 fotogrammi al secondo
          if (slowRuns >= 2) { low = true; root.classList.add('csph--low'); }
          perf.length = 0;
        }
      }
      raf = (busy && visible && !document.hidden) ? requestAnimationFrame(loop) : 0;
    }
    function wake() {
      if (raf || !visible || document.hidden) return;
      last = performance.now();
      raf = requestAnimationFrame(loop);
    }
    render(0);
    var io = new IntersectionObserver(function (en) { visible = en[0].isIntersecting; if (visible) wake(); });
    io.observe(root);
    document.addEventListener('visibilitychange', wake);
    if (opts.focusOnRest && opts.focusOnRest.addEventListener) opts.focusOnRest.addEventListener('change', wake);

    var api = {
      go: function (i, dir) { var h = heroes[(i + heroes.length) % heroes.length]; if (flatOn()) { flatGo(h, dir); return; } snap = null; wv = V(0, 0, 0); startSnap(h); wake(); },
      // giro breve orizzontale (circa 60°), poi si ferma sul progetto più vicino
      spin: function () { if (flatOn()) return; snap = null; settled = false; wv = V(0, -4.2, 0); lastInput = performance.now(); wake(); },
      next: function () { var pool = eligible(); var pos = pool.indexOf(heroes[current]); api.go(heroes.indexOf(pool[(pos + 1) % pool.length]), 1); },
      prev: function () { var pool = eligible(); var pos = pool.indexOf(heroes[current]); api.go(heroes.indexOf(pool[(pos - 1 + pool.length) % pool.length]), -1); },
      setFilter: function (f) {
        filter = f;
        heroes.forEach(function (h) { h.dim = f !== 'all' && h.project.world !== f; h.el.classList.toggle('is-dim', h.dim); });
        wake();
        var pool = eligible();
        if (pool.length && pool.indexOf(heroes[current]) < 0) api.go(heroes.indexOf(pool[0]));
        return pool.length;
      },
      eligible: function () { return eligible().map(function (h) { return heroes.indexOf(h); }); },
      current: function () { return current; },
      // progetto verso cui la sfera sta andando (o quello fermo davanti)
      target: function () { return snap ? snap.hero : current; },
      destroy: function () { cancelAnimationFrame(raf); ro.disconnect(); io.disconnect(); ALL.forEach(function (o) { o.el.remove(); }); }
    };
    return api;
  }

  window.CardSphere = CardSphere;
})();


(function () {
  if (!document.getElementById('projectList')) return;
  /* ---------- progetti: letti dalla lista HTML #projectList ----------
     In WordPress il tema stampa la lista; la sfera ne è solo la vista. */
  var VARIANTS = { scuro: 'ink', sfumatura: 'grad', contorno: 'paper' };
  var PROJECTS = Array.prototype.map.call(document.querySelectorAll('#projectList .plist__item'), function (li) {
    var link = li.querySelector('.plist__link');
    return {
      title: li.querySelector('.plist__title').textContent.trim(),
      desc: li.querySelector('.plist__desc').textContent.trim(),
      url: link ? link.getAttribute('href') : '#',
      type: li.dataset.type || '', year: li.dataset.year || '',
      world: li.dataset.world, worldName: li.dataset.worldName || '', style: li.dataset.worldStyle || 'scuro', variant: VARIANTS[li.dataset.worldStyle] || 'ink', icon: li.dataset.icon || 'robot', image: li.dataset.image || ''
    };
  });

  var sphere = null;
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
    if (sphereEl.dataset.spin !== '0' && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
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
  var currentFilter = 'all', page = 1, PER_PAGE = +document.getElementById('projectList').dataset.perPage || 5, gridMode = false;
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
      var th = p.image ? '<img src="' + esc(p.image) + '" alt="" loading="lazy">' : '<span class="pgcard__ph pgcard__ph--' + p.style + '"></span>';
      return '<li class="prow"><a href="' + esc(p.url) + '">' +
        '<span class="prow__thumb" aria-hidden="true">' + th + '</span>' +
        '<span class="prow__title">' + esc(p.title) + '</span>' +
        '<span class="prow__world tag-mini tag-mini--' + p.style + '">' + esc(p.worldName) + '</span>' +
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
      var img = p.image ? '<img src="' + esc(p.image) + '" alt="" loading="lazy">' : '<span class="pgcard__ph pgcard__ph--' + p.style + '" aria-hidden="true"></span>';
      return '<li class="pgcard"><a href="' + esc(p.url) + '"><span class="pgcard__media">' + img + '</span>' +
        '<span class="pgcard__meta"><span class="tag-mini tag-mini--' + p.style + '">' + esc(p.worldName) + '</span>' + esc([p.type, p.year].filter(Boolean).join(', ')) + '</span>' +
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
  /* link dei Mondi (blocco wero/worlds) e qualsiasi elemento con data-wero-filter */
  document.addEventListener('click', function (e) { var a = e.target.closest('[data-wero-filter]'); if (a) setFilter(a.dataset.weroFilter); });

  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

})();
