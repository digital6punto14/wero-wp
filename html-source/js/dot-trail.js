/* =========================================================
   Cursore "computer vision"
   Sul puntatore un mirino con angoli a squadra (come il riquadro
   di un oggetto riconosciuto da una telecamera) e le coordinate.
   Niente scia.
   Disattivato su touch e con prefers-reduced-motion.
   ========================================================= */
(function () {
  var canvas = document.getElementById('dotTrail');
  if (!canvas) return;
  var fine = window.matchMedia('(hover: hover) and (pointer: fine)');
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
  if (!fine.matches || reduce.matches) { canvas.remove(); return; }

  var ctx = canvas.getContext('2d');
  var GRID = 14;          // passo della griglia
  var LIFE = 1000;        // ms di vita di un elemento della scia
  var CELL = 10;          // lato dei quadrati vuoti
  var MONO = '600 12px ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace';
  var items = [];         // {x, y, t, kind: 'sq'|'bit', bit, c}
  var seen = new Map();   // cella → ultimo istante, per non duplicare
  var mouse = { x: -100, y: -100, sx: -100, sy: -100, on: false };
  var hue = 0;
  var W = 0, H = 0, dpr = 1;

  function resize() {
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    W = window.innerWidth; H = window.innerHeight;
    canvas.width = W * dpr; canvas.height = H * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }
  resize();
  window.addEventListener('resize', resize);

  function mix(t) {       // #ff3f7a → #ff00c7
    var g = Math.round(0x3f * (1 - t)), b = Math.round(0x7a + (0xc7 - 0x7a) * t);
    return 'rgb(255,' + g + ',' + b + ')';
  }

  function addAt(x, y, now) {
    var cx = Math.round(x / GRID) * GRID, cy = Math.round(y / GRID) * GRID;
    var key = cx + ',' + cy;
    if (now - (seen.get(key) || 0) < LIFE * 0.6) return;
    seen.set(key, now);
    hue = (hue + 0.035) % 2;
    var bit = Math.random() < 0.45;
    items.push({ x: cx, y: cy, t: now, kind: bit ? 'bit' : 'sq', bit: Math.random() < 0.5 ? '0' : '1', c: mix(hue > 1 ? 2 - hue : hue) });
  }

  window.addEventListener('pointermove', function (e) {
    if (e.pointerType !== 'mouse') return;
    var now = performance.now();
    // riempie lo spazio tra due eventi, così la scia non ha buchi
    var dx = e.clientX - mouse.x, dy = e.clientY - mouse.y;
    var steps = mouse.on ? Math.min(24, Math.ceil(Math.hypot(dx, dy) / (GRID * 0.8))) : 1;
    // scia tolta: resta solo il mirino con le coordinate
    mouse.x = e.clientX; mouse.y = e.clientY;
    if (!mouse.on) { mouse.sx = mouse.x; mouse.sy = mouse.y; }
    mouse.on = true;
    wake();
  }, { passive: true });
  document.addEventListener('pointerleave', function () { mouse.on = false; wake(); });

  var raf = 0;
  function wake() { if (!raf) raf = requestAnimationFrame(draw); }

  // mirino: quattro angoli a squadra attorno al puntatore
  function reticle(x, y, s, col) {
    var a = 6;
    ctx.strokeStyle = col; ctx.lineWidth = 1.5;
    ctx.beginPath();
    ctx.moveTo(x - s, y - s + a); ctx.lineTo(x - s, y - s); ctx.lineTo(x - s + a, y - s);
    ctx.moveTo(x + s - a, y - s); ctx.lineTo(x + s, y - s); ctx.lineTo(x + s, y - s + a);
    ctx.moveTo(x + s, y + s - a); ctx.lineTo(x + s, y + s); ctx.lineTo(x + s - a, y + s);
    ctx.moveTo(x - s + a, y + s); ctx.lineTo(x - s, y + s); ctx.lineTo(x - s, y + s - a);
    ctx.stroke();
    ctx.fillStyle = col; ctx.fillRect(x - 1, y - 1, 2, 2);
  }

  function draw(now) {
    raf = 0;
    ctx.clearRect(0, 0, W, H);
    ctx.font = MONO; ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
    var alive = 0;
    for (var i = 0; i < items.length; i++) {
      var d = items[i], k = (now - d.t) / LIFE;
      if (k >= 1) continue;
      items[alive++] = d;
      var e = 1 - k;
      ctx.globalAlpha = e * 0.95;
      if (d.kind === 'sq') {
        var s = CELL * (0.55 + 0.45 * e);
        ctx.strokeStyle = d.c; ctx.lineWidth = 1.25;
        ctx.strokeRect(d.x - s / 2, d.y - s / 2, s, s);
      } else {
        // la cifra sfarfalla tra 0 e 1 mentre svanisce
        var b = (k > 0.35 && Math.floor(now / 90) % 2) ? (d.bit === '0' ? '1' : '0') : d.bit;
        ctx.fillStyle = d.c;
        ctx.fillText(b, d.x, d.y);
      }
    }
    items.length = alive;
    if (seen.size > 2000) seen.clear();

    // mirino che segue il puntatore con un leggero ritardo, più le coordinate
    if (mouse.on) {
      mouse.sx += (mouse.x - mouse.sx) * 0.3;
      mouse.sy += (mouse.y - mouse.sy) * 0.3;
      ctx.globalAlpha = 1;
      reticle(mouse.sx, mouse.sy, 11, '#ff00c7');
      ctx.textAlign = 'left'; ctx.textBaseline = 'top';
      ctx.font = '500 10px ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace';
      ctx.fillStyle = '#15121a'; ctx.globalAlpha = 0.75;
      ctx.fillText('x' + String(Math.round(mouse.x)).padStart(4, '0') + ' y' + String(Math.round(mouse.y)).padStart(4, '0'), mouse.sx + 15, mouse.sy + 12);
    }
    ctx.globalAlpha = 1;
    if (alive || (mouse.on && Math.hypot(mouse.x - mouse.sx, mouse.y - mouse.sy) > 0.3)) wake();
  }
})();
