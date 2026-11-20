/* =========================================================
   Riga curva di immagini — port vanilla di TiltedGridHero
   Una riga di tile scorre da destra a sinistra sull'interno di un
   cilindro: al centro è piatta, ai lati si piega verso chi guarda e
   sfuma. Ogni tile è tagliato in SLICES strisce verticali (facce del
   cilindro); il tile intero ruota con un'unica animazione CSS.
   Il JS serve solo a misurare il loop (ResizeObserver) e a passare al
   tile l'immagine successiva quando si riavvolge, fuori campo.

   Uso: TiltedGrid(el, { images: [{src, alt}], speed, tileHeight,
        aspectRatio, gap, axis, curve, fade })
   ========================================================= */
(function () {
  var SLICES = 16;
  var CAMERA = 1.6;
  var MAX_WIDTH = 55;
  var uid = 0;

  function arc(bend) { return (CAMERA - 1 + Math.cos(bend)) / (2 * CAMERA * Math.sin(bend)); }
  function r4(n) { return +n.toFixed(4); }

  function measure(width, height, tile, aspect, gap, bend) {
    var h = Math.min((tile / 100) * height, ((MAX_WIDTH / 100) * width) / aspect);
    var radius = width * arc(bend);
    if (!(h > 0) || !(radius > 0)) return { columns: 2, sweep: 0, unit: 0, limit: 0 };
    var deg = function (rad) { return (rad * 180) / Math.PI; };
    var unit = deg(h / radius);
    var pitch = (aspect + gap / 100) * unit;
    var limit = deg(bend) + (aspect * unit) / 2;
    var columns = Math.min(60, Math.max(2, Math.ceil((2 * limit) / pitch)));
    var sweep = r4((columns * pitch) / 2);
    return { columns: columns, sweep: sweep, unit: r4(unit), limit: r4(Math.min(sweep, limit)) };
  }

  function TiltedGrid(root, opts) {
    var o = Object.assign({ speed: 4, tileHeight: 26, aspectRatio: 16 / 9, gap: 6, axis: 56, curve: 80, fade: 12 }, opts);
    var images = o.images || [];
    var id = 'tg' + (++uid);
    var orbit = id + '-o', tileCls = id + '-t';
    var bend = (Math.min(85, Math.max(5, o.curve)) * Math.PI) / 180;
    var A = o.aspectRatio;

    root.style.containerType = 'size';
    var style = document.createElement('style');
    var band = document.createElement('div');
    band.className = 'tilted__band';
    band.setAttribute('aria-hidden', 'true');
    band.style.opacity = '0';
    root.append(style, band);

    // ogni lunghezza è un multiplo dell'altezza del tile
    function u(n) {
      return 'calc(' + r4(n) + ' * min(' + o.tileHeight + 'cqh, ' + r4(MAX_WIDTH / A) + 'cqw))';
    }
    var R = 100 * arc(bend);
    var radius = r4(R) + 'cqw';
    function turn(d) { return 'translateZ(' + radius + ') rotateY(' + r4(d) + 'deg) translateZ(-' + radius + ')'; }

    // precarica tutte le immagini
    images.forEach(function (im) { new Image().src = im.src; });

    var layout = null;
    var shown = {};

    function build(L) {
      var hide = r4(((L.sweep - L.limit) / (2 * L.sweep || 1)) * 100);
      style.textContent =
        '@keyframes ' + orbit + '{' +
        'from{transform:' + turn(-L.sweep) + '}to{transform:' + turn(L.sweep) + '}' +
        '0%,' + hide + '%,' + (100 - hide) + '%,100%{visibility:hidden}' +
        (hide + 0.001) + '%,' + (100 - hide - 0.001) + '%{visibility:visible}}' +
        '@media(prefers-reduced-motion:reduce){.' + tileCls + '{animation-play-state:paused}}';

      var share = A / SLICES;
      var duration = L.columns * o.speed;
      var mask = 'linear-gradient(90deg,transparent,#000 ' + o.fade + '%,#000 ' + (100 - o.fade) + '%,transparent)';
      band.style.perspective = r4(R * CAMERA) + 'cqw';
      band.style.perspectiveOrigin = '50% ' + o.axis + '%';
      band.style.maskImage = mask;
      band.style.webkitMaskImage = mask;
      band.textContent = '';

      for (var t = 0; t < L.columns; t++) {
        (function (t) {
          var first = L.columns - 1 - t;
          var tile = document.createElement('div');
          tile.className = tileCls + ' tilted__tile';
          tile.style.left = 'calc(50% - ' + u(A / 2) + ')';
          tile.style.top = 'calc(' + o.axis + '% - ' + u(0.5) + ')';
          tile.style.width = u(A);
          tile.style.height = u(1);
          tile.style.animation = orbit + ' ' + duration + 's linear ' + (-t * o.speed) + 's infinite';

          var imgs = [];
          for (var k = 0; k < SLICES; k++) {
            var s = document.createElement('div');
            s.className = 'tilted__slice';
            if (k === 0) s.style.borderRadius = '12px 0 0 12px';
            if (k === SLICES - 1) s.style.borderRadius = '0 12px 12px 0';
            s.style.left = u((A - share) / 2);
            s.style.width = k === SLICES - 1 ? u(share) : 'calc(' + u(share) + ' + 1px)';
            s.style.height = u(1);
            s.style.transform = turn((A / 2 - (k + 0.5) * share) * L.unit);
            var img = document.createElement('img');
            img.draggable = false;
            img.alt = '';
            img.style.left = u(-k * share);
            img.style.width = u(A);
            img.style.height = u(1);
            s.appendChild(img);
            tile.appendChild(s);
            imgs.push(img);
          }
          function paint() {
            var im = images[(shown[t] != null ? shown[t] : first) % Math.max(images.length, 1)];
            if (!im) return;
            for (var i = 0; i < imgs.length; i++) if (imgs[i].src !== im.src) imgs[i].src = im.src;
          }
          paint();
          tile.addEventListener('animationiteration', function (e) {
            var lap = Math.round(e.elapsedTime / duration);
            var next = lap * L.columns + first;
            if (shown[t] !== next) { shown[t] = next; paint(); }
          });
          band.appendChild(tile);
        })(t);
      }
    }

    var ro = new ResizeObserver(function (entries) {
      var r = entries[0].contentRect;
      var next = measure(r.width, r.height, o.tileHeight, A, o.gap, bend);
      if (layout && layout.columns === next.columns && layout.sweep === next.sweep && layout.unit === next.unit) return;
      layout = next;
      build(layout);
      band.style.opacity = '1';
    });
    ro.observe(root);

    // fuori schermo: pausa le animazioni
    var io = new IntersectionObserver(function (en) {
      root.classList.toggle('is-paused', !en[0].isIntersecting);
      band.style.setProperty('--play', en[0].isIntersecting ? 'running' : 'paused');
      Array.prototype.forEach.call(band.children, function (c) { c.style.animationPlayState = en[0].isIntersecting ? '' : 'paused'; });
    });
    io.observe(root);

    return { destroy: function () { ro.disconnect(); io.disconnect(); style.remove(); band.remove(); } };
  }

  window.TiltedGrid = TiltedGrid;
})();
