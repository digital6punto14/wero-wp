/* Voci: al clic sulla copertina il video (file, YouTube o Vimeo) prende il suo posto. Nessuna richiesta esterna prima del clic. */
( function () {
	document.querySelectorAll( '.wero-voice__media' ).forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			var kind = btn.dataset.kind, src = btn.dataset.src;
			var note = btn.parentNode.querySelector( '.wero-voice__note' );
			if ( ! src ) { note.textContent = 'Il video sarà disponibile a breve.'; return; }
			if ( kind === 'link' ) { window.open( src, '_blank', 'noopener' ); return; }
			var wrap = document.createElement( 'div' );
			wrap.className = 'wero-voice__player';
			if ( kind === 'video' ) {
				var v = document.createElement( 'video' );
				v.src = src; v.controls = true; v.autoplay = true; v.playsInline = true;
				wrap.appendChild( v );
			} else {
				var f = document.createElement( 'iframe' );
				f.src = src; f.title = btn.getAttribute( 'aria-label' ); f.allow = 'autoplay; fullscreen; picture-in-picture; encrypted-media'; f.allowFullscreen = true;
				wrap.appendChild( f );
			}
			btn.replaceWith( wrap );
		} );
	} );
} )();
