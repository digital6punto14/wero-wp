/* =========================================================
   WeRo — script comune delle pagine interne
   - menu mobile
   - selettore "immagine prima / dopo" (solo per la bozza)
   - video segnaposto
   - player del podcast (con un file audio usa <audio>;
     senza file simula la riproduzione per mostrare il player)
   ========================================================= */
(function () {
  /* la pagina di un progetto si apre sempre dall'inizio: il browser (o l'anteprima)
     non deve riportare la posizione di scorrimento della pagina precedente */
  if ('scrollRestoration' in history) history.scrollRestoration = 'manual';
  if (!location.hash) {
    var toTop = function () { document.documentElement.style.scrollBehavior = 'auto'; window.scrollTo(0, 0); document.documentElement.style.scrollBehavior = ''; };
    toTop(); window.addEventListener('load', toTop); window.addEventListener('pageshow', toTop);
  }

  /* ---------- menu mobile ---------- */
  var nav = document.getElementById('nav');
  var burger = document.getElementById('burger');
  if (nav && burger) {
    burger.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  /* ---------- articolo semplice: immagine prima o dopo il testo ---------- */
  var article = document.querySelector('.article');
  // il box dei dati parte sempre all'altezza dell'immagine, sia prima sia dopo il testo
  function alignFacts() {
    var facts = document.querySelector('.facts'), media = document.querySelector('.article__flow .media'), flow = document.querySelector('.article__flow');
    if (!facts || !media || !flow) return;
    facts.style.marginTop = window.matchMedia('(max-width: 900px)').matches ? '' : (media.offsetTop - flow.offsetTop) + 'px';
  }
  alignFacts(); window.addEventListener('resize', alignFacts); window.addEventListener('load', alignFacts);
  document.querySelectorAll('[data-media]').forEach(function (b) {
    b.addEventListener('click', function () {
      var after = b.dataset.media === 'after';
      article.classList.toggle('article--media-after', after);
      alignFacts();
      document.querySelectorAll('[data-media]').forEach(function (x) {
        var on = x === b; x.classList.toggle('is-on', on); x.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
    });
  });

  /* ---------- video segnaposto ---------- */
  var player = document.querySelector('.player');
  if (player) {
    var play = player.querySelector('.player__play'), note = player.querySelector('.player__note');
    var video = player.querySelector('video');
    play.addEventListener('click', function () {
      if (video && video.getAttribute('src')) { video.hidden = false; play.hidden = true; var pst = player.querySelector('.player__poster'); if (pst) pst.hidden = true; video.play(); return; }
      player.classList.add('is-clicked');
      note.textContent = 'Il video sarà disponibile a breve.';
    });
    document.querySelectorAll('.chapters button').forEach(function (b) {
      b.addEventListener('click', function () {
        if (video && video.getAttribute('src')) { video.currentTime = +b.dataset.t; video.play(); return; }
        player.classList.add('is-clicked');
        note.textContent = 'Il video sarà disponibile a breve.';
        player.scrollIntoView({ behavior: 'smooth', block: 'center' });
      });
    });
  }

  /* ---------- player del podcast ---------- */
  var box = document.querySelector('.audio');
  if (box) {
    var audio = box.querySelector('audio');
    var real = audio && audio.getAttribute('src');
    var bar = box.querySelector('.audio__bar'), time = box.querySelector('.audio__time');
    var btn = box.querySelector('.audio__play'), speedBtn = box.querySelector('.audio__speed');
    var DURATION = +box.dataset.duration || 1934;   // durata simulata (s) senza file audio
    var pos = 0, playing = false, speeds = [1, 1.25, 1.5, 2], si = 0, timer = 0, last = 0;
    function fmt(s) { s = Math.max(0, Math.round(s)); var m = Math.floor(s / 60); return String(m).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0'); }
    function dur() { return real && audio.duration ? audio.duration : DURATION; }
    function cur() { return real ? audio.currentTime : pos; }
    function paint() {
      bar.max = Math.round(dur()); bar.value = Math.round(cur());
      time.textContent = fmt(cur()) + ' / ' + fmt(dur());
      box.classList.toggle('is-playing', playing);
      btn.setAttribute('aria-label', playing ? 'Pausa' : 'Riproduci');
    }
    function tick(now) {
      if (!playing) return;
      pos = Math.min(DURATION, pos + (now - last) / 1000 * speeds[si]); last = now;
      if (pos >= DURATION) { playing = false; }
      paint();
      timer = requestAnimationFrame(tick);
    }
    function setPlaying(on) {
      playing = on;
      if (real) { on ? audio.play() : audio.pause(); }
      else if (on) { last = performance.now(); timer = requestAnimationFrame(tick); }
      else cancelAnimationFrame(timer);
      paint();
    }
    function seek(t) {
      t = Math.max(0, Math.min(dur(), t));
      if (real) audio.currentTime = t; else pos = t;
      paint();
    }
    btn.addEventListener('click', function () { setPlaying(!playing); });
    box.querySelectorAll('.audio__skip').forEach(function (b) { b.addEventListener('click', function () { seek(cur() + +b.dataset.skip); }); });
    bar.addEventListener('input', function () { seek(+bar.value); });
    speedBtn.addEventListener('click', function () {
      si = (si + 1) % speeds.length; speedBtn.textContent = speeds[si] + '×';
      if (real) audio.playbackRate = speeds[si];
    });
    if (real) { audio.addEventListener('timeupdate', paint); audio.addEventListener('loadedmetadata', paint); audio.addEventListener('ended', function () { playing = false; paint(); }); }
    paint();
  }
})();
