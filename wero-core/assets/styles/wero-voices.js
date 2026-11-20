/* Gruppo "Voci": la copertina (blocco Immagine collegato al video) apre il player al clic.
   YouTube (senza cookie), Vimeo o file video; nessuna richiesta esterna prima del clic. */
( function () {
	function embed( url ) {
		var u; try { u = new URL( url, location.href ); } catch ( e ) { return null; }
		var h = u.hostname.replace( /^www\./, '' ), id;
		if ( /\.(mp4|webm|mov|m4v)$/i.test( u.pathname ) ) return { kind: 'video', src: u.href };
		if ( h === 'youtu.be' ) id = u.pathname.slice( 1 );
		else if ( /(^|\.)youtube(-nocookie)?\.com$/.test( h ) ) id = u.searchParams.get( 'v' ) || ( u.pathname.match( /\/(embed|shorts|live)\/([^/?]+)/ ) || [] )[ 2 ];
		if ( id ) return { kind: 'embed', src: 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent( id ) + '?autoplay=1&rel=0' };
		if ( /(^|\.)vimeo\.com$/.test( h ) && ( id = ( u.pathname.match( /\/(\d+)/ ) || [] )[ 1 ] ) ) return { kind: 'embed', src: 'https://player.vimeo.com/video/' + id + '?autoplay=1&dnt=1' };
		return null;
	}
	document.querySelectorAll( '.is-style-wero-voices > .wp-block-group' ).forEach( function ( card ) {
		var fig = card.querySelector( '.wp-block-image' );
		if ( ! fig ) return;
		var a = fig.querySelector( 'a' );
		var title = ( card.querySelector( '.wp-block-image + *' ) || {} ).textContent || '';
		var target = a || fig;
		if ( ! a ) { fig.setAttribute( 'role', 'button' ); fig.tabIndex = 0; fig.style.cursor = 'pointer'; }
		target.setAttribute( 'aria-label', 'Guarda il video: ' + title.trim() );
		function play( e ) {
			var m = a && a.getAttribute( 'href' ) && a.getAttribute( 'href' ) !== '#' ? embed( a.href ) : null;
			if ( e ) e.preventDefault();
			if ( ! m ) {
				if ( a && a.getAttribute( 'href' ) && a.getAttribute( 'href' ) !== '#' ) { window.open( a.href, '_blank', 'noopener' ); return; }
				var n = card.querySelector( '.wero-voice-note' ) || card.appendChild( Object.assign( document.createElement( 'p' ), { className: 'wero-voice-note' } ) );
				n.setAttribute( 'role', 'status' ); n.textContent = 'Il video sarà disponibile a breve.'; return;
			}
			var box = document.createElement( 'div' ); box.className = 'wero-voice-player';
			var el = document.createElement( m.kind === 'video' ? 'video' : 'iframe' );
			el.src = m.src;
			if ( m.kind === 'video' ) { el.controls = true; el.autoplay = true; el.playsInline = true; } else { el.allow = 'autoplay; fullscreen; picture-in-picture; encrypted-media'; el.allowFullscreen = true; el.title = title.trim(); }
			box.appendChild( el ); fig.replaceWith( box );
		}
		target.addEventListener( 'click', play );
		if ( ! a ) fig.addEventListener( 'keydown', function ( e ) { if ( e.key === 'Enter' || e.key === ' ' ) play( e ); } );
	} );
} )();
