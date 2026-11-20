/* Player del progetto: video o servizio esterno caricato solo al clic; i capitoli saltano al punto del video. */
( function () {
	document.querySelectorAll( '.wero-player--video' ).forEach( function ( box ) {
		var btn = box.querySelector( '.wero-player__play' );
		btn.addEventListener( 'click', function () { start( box, 0 ); } );
	} );
	function start( box, t ) {
		var kind = box.dataset.kind, src = box.dataset.src;
		var note = box.querySelector( '.wero-player__note' );
		if ( ! src ) { if ( note ) note.textContent = 'Il video sarà disponibile a breve.'; return null; }
		if ( kind === 'link' ) { window.open( src, '_blank', 'noopener' ); return null; }
		var el = box.querySelector( 'video, iframe' );
		if ( kind === 'video' ) {
			if ( ! el ) { el = document.createElement( 'video' ); el.src = src; el.controls = true; el.playsInline = true; box.appendChild( el ); }
			el.currentTime = t || 0; el.play && el.play().catch( function () {} );
		} else {
			if ( el ) el.remove();
			el = document.createElement( 'iframe' );
			var sep = src.indexOf( '?' ) > -1 ? '&' : '?';
			el.src = src + ( t ? sep + ( src.indexOf( 'vimeo' ) > -1 ? '#t=' + t + 's' : 'start=' + Math.floor( t ) ) : '' );
			el.allow = 'autoplay; fullscreen; picture-in-picture; encrypted-media'; el.allowFullscreen = true; el.title = document.title;
			box.appendChild( el );
		}
		box.classList.add( 'is-playing' );
		return el;
	}
	/* capitoli (blocco wero/chapters) */
	document.addEventListener( 'click', function ( e ) {
		var b = e.target.closest( '[data-wero-seek]' );
		if ( ! b ) return;
		var box = document.querySelector( '.wero-player--video' );
		if ( ! box ) return;
		start( box, +b.dataset.weroSeek || 0 );
		box.scrollIntoView( { behavior: 'smooth', block: 'center' } );
	} );
	/* audio esterno al clic */
	document.querySelectorAll( '.wero-player__load' ).forEach( function ( b ) {
		b.addEventListener( 'click', function () {
			var f = document.createElement( 'iframe' );
			f.src = b.dataset.src; f.height = b.dataset.height || 152; f.allow = 'autoplay; clipboard-write; encrypted-media'; f.loading = 'lazy'; f.title = b.textContent.trim();
			var note = b.parentNode.querySelector( '.wero-player__note' ); if ( note ) note.remove();
			b.replaceWith( f );
		} );
	} );
} )();
