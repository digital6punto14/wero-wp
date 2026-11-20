/* =========================================================
   Sfondo "pixel beams" chiaro
   Ispirato a "Pixel Beams" di HorizonX, riscritto da zero in
   versione chiara: su fondo crema, una griglia di quadratini bianchi
   luminosi, con un leggero alone, si accende e si spegne seguendo onde di
   "plasma" che scorrono lente; una retinatura ordinata (Bayer 4×4)
   decide quali pixel restano accesi.
   - WebGL1, un solo canvas fisso dietro tutta la pagina, disegnato
     a 1 pixel CSS per pixel così i quadrati restano netti
   - ogni sezione ha direzione e frequenza delle onde diverse, e
     scorrendo si trasformano con continuità
   - attorno al puntatore i pixel si addensano leggermente
   - 30 fps; fermo con la scheda nascosta; con prefers-reduced-motion
     niente animazione nel tempo (cambia solo con lo scroll)
   - senza WebGL resta il colore crema del body
   ========================================================= */
(function () {
  /* WordPress: il canvas lo crea lo script, così i template non contengono markup scritto a mano */
  var canvas = document.getElementById('bgGlow');
  if (!canvas) { canvas = document.createElement('canvas'); canvas.id = 'bgGlow'; canvas.className = 'bg-glow'; canvas.setAttribute('aria-hidden', 'true'); document.body.prepend(canvas); }
  var gl = canvas.getContext('webgl', { alpha: false, antialias: false, depth: false, stencil: false, powerPreference: 'low-power' });
  if (!gl) return;

  var CELL = 13;        // passo della griglia in pixel CSS
  var DOT = 0.34;       // lato del quadratino rispetto alla cella

  var VERT = 'attribute vec2 a;void main(){gl_Position=vec4(a,0.,1.);}';

  var FRAG = [
    'precision mediump float;',
    'uniform vec2 uRes;uniform float uTime;uniform float uScene;uniform vec2 uMouse;uniform float uCell;uniform float uDot;',
    // parametri della sezione: variano con continuità lungo uScene
    'vec4 P(float s){return vec4(sin(s*1.7+.3),cos(s*1.3+1.1),sin(s*2.3+2.),cos(s*.9+.5));}',
    // soglia della retinatura ordinata 4×4
    'float bayer(vec2 c){vec2 m=mod(c,4.);',
    '  float i=m.x+m.y*4.;',
    '  if(i<1.)return 0.;if(i<2.)return 8.;if(i<3.)return 2.;if(i<4.)return 10.;',
    '  if(i<5.)return 12.;if(i<6.)return 4.;if(i<7.)return 14.;if(i<8.)return 6.;',
    '  if(i<9.)return 3.;if(i<10.)return 11.;if(i<11.)return 1.;if(i<12.)return 9.;',
    '  if(i<13.)return 15.;if(i<14.)return 7.;if(i<15.)return 13.;return 5.;}',
    // intensità delle onde in un punto (coordinate isotrope, altezza = 1), 0..1
    'float field(vec2 c,vec4 k,float t){',
    '  float an=.6+1.2*k.x;',
    '  vec2 d1=vec2(cos(an),sin(an)), d2=vec2(cos(an+1.9+.6*k.y),sin(an+1.9+.6*k.y));',
    '  float v=sin(dot(c,d1)*(5.+1.5*k.z)+t*.55)',
    '         +sin(dot(c,d2)*(3.4+1.2*k.w)-t*.4)',
    '         +sin(length(c-vec2(.9+.5*k.y+.25*sin(t*.12),.5+.3*k.z))*(6.+2.*k.x)-t*.7);',
    '  return smoothstep(.38,.92,v/3.*.5+.5);}',
    'void main(){',
    '  vec2 cell=floor(gl_FragCoord.xy/uCell);',
    '  vec2 f=fract(gl_FragCoord.xy/uCell)-.5;',
    '  vec4 k=P(uScene); float t=uTime;',
    '  vec2 c=cell*uCell/uRes.y;',                       // centro cella, coordinate isotrope
    '  vec2 cs=gl_FragCoord.xy/uRes.y;',                  // stesso spazio ma continuo, per il chiarore
    '  float v=field(c,k,t), vs=field(cs,k,t);',
    // il puntatore addensa i pixel attorno a sé
    '  vec2 m=uMouse*vec2(uRes.x/uRes.y,1.);',
    '  v=max(v,.85*exp(-length(c-m)*7.)); vs=max(vs,.85*exp(-length(cs-m)*7.));',
    '  float on=step((bayer(cell)+.5)/16.,v);',
    // quadratino centrato nella cella, bordo appena ammorbidito
    '  float px=1./uCell;',
    '  float dd=max(abs(f.x),abs(f.y));',
    '  float sq=1.-smoothstep(uDot*.5-px,uDot*.5+px,dd);',
    // alone morbido attorno a ogni pixel acceso: li fa sembrare luminosi
    '  float halo=exp(-length(f)*5.5)*(1.-sq);',
    // fondo crema appena più profondo di quello dei pannelli, così i pixel bianchi risaltano
    '  vec2 uv=gl_FragCoord.xy/uRes;',
    '  vec3 bg=mix(vec3(.868,.852,.806),vec3(.912,.896,.855),uv.y);',
    // chiarore morbido dietro i gruppi, come una lampadina che illumina da dietro
    '  bg=mix(bg,vec3(.985,.975,.95),.38*smoothstep(.15,1.,vs)*smoothstep(.15,1.,vs));',
    '  vec3 lit=vec3(1.);',
    '  float lamp=v*v;',
    // ogni gruppo ha una luce propria: pixel pieni e brillanti al centro, più tenui verso i bordi
    '  float a=on*(sq*(.22+.78*lamp)+halo*(.08+.62*lamp));',
    '  gl_FragColor=vec4(mix(bg,lit,clamp(a,0.,1.)),1.);',
    '}'
  ].join('\n');

  function shader(type, src) {
    var s = gl.createShader(type); gl.shaderSource(s, src); gl.compileShader(s);
    if (!gl.getShaderParameter(s, gl.COMPILE_STATUS)) { console.warn('[sfondo]', gl.getShaderInfoLog(s)); return null; }
    return s;
  }
  var vs = shader(gl.VERTEX_SHADER, VERT), fs = shader(gl.FRAGMENT_SHADER, FRAG);
  if (!vs || !fs) return;
  var prog = gl.createProgram();
  gl.attachShader(prog, vs); gl.attachShader(prog, fs); gl.linkProgram(prog);
  if (!gl.getProgramParameter(prog, gl.LINK_STATUS)) { console.warn('[sfondo]', gl.getProgramInfoLog(prog)); return; }
  gl.useProgram(prog);

  var buf = gl.createBuffer();
  gl.bindBuffer(gl.ARRAY_BUFFER, buf);
  gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW);
  var aLoc = gl.getAttribLocation(prog, 'a');
  gl.enableVertexAttribArray(aLoc);
  gl.vertexAttribPointer(aLoc, 2, gl.FLOAT, false, 0, 0);
  var U = {
    res: gl.getUniformLocation(prog, 'uRes'), time: gl.getUniformLocation(prog, 'uTime'),
    scene: gl.getUniformLocation(prog, 'uScene'), mouse: gl.getUniformLocation(prog, 'uMouse'),
    cell: gl.getUniformLocation(prog, 'uCell'), dot: gl.getUniformLocation(prog, 'uDot')
  };

  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)');

  function resize() {
    // 1 pixel del canvas = 1 pixel CSS: i quadratini restano netti senza pesare sugli schermi ad alta densità
    var w = Math.max(1, window.innerWidth), h = Math.max(1, window.innerHeight);
    if (canvas.width !== w || canvas.height !== h) { canvas.width = w; canvas.height = h; gl.viewport(0, 0, w, h); }
    gl.uniform2f(U.res, w, h);
    gl.uniform1f(U.cell, CELL);
    gl.uniform1f(U.dot, DOT);
  }

  /* sezione corrente come numero continuo: 2.4 = 40% dentro la terza sezione */
  var sections = Array.prototype.slice.call(document.querySelectorAll('.wero-section, .wp-site-blocks > footer'));
  function sceneFromScroll() {
    var mid = window.innerHeight / 2;
    for (var i = 0; i < sections.length; i++) {
      var r = sections[i].getBoundingClientRect();
      if (r.bottom >= mid) return i + Math.min(1, Math.max(0, (mid - r.top) / Math.max(1, r.height)));
    }
    return sections.length;
  }

  var time = 3, scene = sceneFromScroll(), sceneT = scene;
  var mouse = { x: -5, y: -5 }, mouseT = { x: -5, y: -5 };   // fuori schermo finché il mouse non si muove
  window.addEventListener('pointermove', function (e) {
    if (e.pointerType !== 'mouse') return;
    mouseT.x = e.clientX / window.innerWidth; mouseT.y = 1 - e.clientY / window.innerHeight;
    if (mouse.x < -1) { mouse.x = mouseT.x; mouse.y = mouseT.y; }
    wake();
  }, { passive: true });
  document.addEventListener('pointerleave', function () { mouseT.x = -5; mouseT.y = -5; wake(); });
  window.addEventListener('scroll', function () { sceneT = sceneFromScroll(); wake(); }, { passive: true });
  window.addEventListener('resize', function () { resize(); sceneT = sceneFromScroll(); wake(); });

  function draw() {
    gl.uniform1f(U.time, time);
    gl.uniform1f(U.scene, scene * 1.15);
    gl.uniform2f(U.mouse, mouse.x, mouse.y);
    gl.drawArrays(gl.TRIANGLES, 0, 3);
  }

  var raf = 0, last = 0, acc = 0;
  function tick(now) {
    raf = 0;
    var dt = Math.min(0.1, (now - last) / 1000); last = now;
    acc += dt;
    var animate = !reduce.matches;
    if (acc >= 1 / 30) {   // 30 fps bastano: le onde scorrono lente
      if (animate) time += acc;
      var k = 1 - Math.pow(0.02, acc);
      scene += (sceneT - scene) * k;
      if (mouseT.x < -1) { mouse.x = mouseT.x; mouse.y = mouseT.y; }
      else { mouse.x += (mouseT.x - mouse.x) * k * 1.6; mouse.y += (mouseT.y - mouse.y) * k * 1.6; }
      acc = 0;
      draw();
    }
    var settling = Math.abs(sceneT - scene) > 0.001 || Math.abs(mouseT.x - mouse.x) > 0.001 || Math.abs(mouseT.y - mouse.y) > 0.001;
    if ((animate || settling) && !document.hidden) raf = requestAnimationFrame(tick);
  }
  function wake() { if (!raf && !document.hidden) { last = performance.now(); raf = requestAnimationFrame(tick); } }

  resize();
  draw();
  canvas.classList.add('is-ready');
  document.addEventListener('visibilitychange', wake);
  wake();
})();
