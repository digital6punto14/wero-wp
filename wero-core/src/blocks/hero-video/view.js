/* Hero con video: il video parte solo dal pulsante (nessuna richiesta a YouTube/Vimeo prima del clic).
   Durante la riproduzione titolo e velo spariscono; tornano in pausa, alla fine o con "Chiudi". */
( function () {
	document.querySelectorAll( '.wero-hero' ).forEach( function ( hero ) {
		var player = hero.querySelector( '.wero-hero__player' );
		var note = hero.querySelector( '.wero-hero__note' );
		var close = hero.querySelector( '.wero-hero__close' );
		var kind = hero.dataset.kind, src = hero.dataset.src;

		function stop() {
			hero.classList.remove( 'is-playing' );
			close.hidden = true;
		}
		function play( e ) {
			if ( e ) e.preventDefault();
			if ( ! src ) {
				note.textContent = 'Il video sarà disponibile a breve.';
				return;
			}
			player.innerHTML = '';
			if ( kind === 'video' ) {
				var v = document.createElement( 'video' );
				v.src = src; v.controls = true; v.playsInline = true; v.autoplay = true;
				v.addEventListener( 'pause', stop );
				v.addEventListener( 'ended', function () { stop(); player.innerHTML = ''; } );
				player.appendChild( v );
				v.play && v.play().catch( function () {} );
			} else if ( kind === 'embed' ) {
				var f = document.createElement( 'iframe' );
				f.src = src; f.allow = 'autoplay; fullscreen; picture-in-picture; encrypted-media'; f.allowFullscreen = true;
				f.title = hero.querySelector( '.wero-hero__bigplay' ).getAttribute( 'aria-label' );
				player.appendChild( f );
				close.hidden = false;
			} else {
				window.open( src, '_blank', 'noopener' );
				return;
			}
			hero.classList.add( 'is-playing' );
		}
		hero.querySelector( '.wero-hero__bigplay' ).addEventListener( 'click', play );
		hero.querySelectorAll( '.wero-play a, a[href="#guarda"]' ).forEach( function ( a ) { a.addEventListener( 'click', play ); } );
		close.addEventListener( 'click', function () { player.innerHTML = ''; stop(); } );
	} );
} )();
