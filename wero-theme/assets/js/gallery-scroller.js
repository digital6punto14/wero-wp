/* =========================================================
   WeRo — Galleria con stile "A scorrimento"
   Aggiunge sotto la galleria due frecce (solo desktop, via CSS) che
   scorrono di una foto; si disattivano agli estremi.
   Le foto restano un normale blocco Galleria: si aggiungono,
   tolgono e riordinano dall'editor.
   ========================================================= */
(function () {
  document.querySelectorAll('.wp-block-gallery.is-style-gallery-scroller').forEach(function (row) {
    if (row.dataset.weroScroller) return;
    row.dataset.weroScroller = '1';
    /* raggiungibile e scorrevole anche da tastiera (frecce sinistra/destra) */
    if (!row.hasAttribute('tabindex')) row.tabIndex = 0;
    row.setAttribute('role', 'region');
    if (!row.getAttribute('aria-label')) row.setAttribute('aria-label', 'Foto a scorrimento');
    var nav = document.createElement('div');
    nav.className = 'wero-scroller-nav';
    nav.innerHTML =
      '<button type="button" class="wero-round" data-dir="-1" aria-label="Foto precedente">←</button>' +
      '<button type="button" class="wero-round" data-dir="1" aria-label="Foto successiva">→</button>';
    row.after(nav);
    var prev = nav.children[0], next = nav.children[1];

    function step(dir) {
      var items = row.querySelectorAll(':scope > .wp-block-image');
      var pad = parseFloat(getComputedStyle(row).paddingLeft) || 0, x = row.scrollLeft, target = null;
      for (var i = 0; i < items.length; i++) {
        var off = items[i].offsetLeft - pad;   // la galleria è position: relative (CSS), quindi è il riferimento di offsetLeft
        if (dir > 0 && off > x + 4) { target = off; break; }
        if (dir < 0 && off < x - 4) target = off;
      }
      row.scrollTo({ left: target == null ? (dir > 0 ? row.scrollWidth : 0) : target, behavior: 'smooth' });
    }
    function update() {
      prev.disabled = row.scrollLeft < 4;
      next.disabled = row.scrollLeft > row.scrollWidth - row.clientWidth - 4;
    }
    nav.addEventListener('click', function (e) { var b = e.target.closest('button'); if (b) step(+b.dataset.dir); });
    row.addEventListener('scroll', update, { passive: true });
    addEventListener('resize', update);
    update();
  });
})();
