/* Gruppo "Mondi" su mobile: puntini sincronizzati con lo scorrimento orizzontale delle card. */
( function () {
	document.querySelectorAll( '.is-style-wero-worlds' ).forEach( function ( wl ) {
		var cards = wl.querySelectorAll( ':scope > .wp-block-group' );
		if ( cards.length < 2 ) return;
		var dots = document.createElement( 'div' );
		dots.className = 'wero-worlds-dots'; dots.setAttribute( 'aria-hidden', 'true' );
		cards.forEach( function ( c, i ) {
			var b = document.createElement( 'button' ); b.type = 'button'; b.tabIndex = -1; if ( ! i ) b.className = 'is-on';
			b.addEventListener( 'click', function () { wl.scrollTo( { left: Math.min( wl.scrollWidth - wl.clientWidth, c.offsetLeft - cards[ 0 ].offsetLeft ), behavior: 'smooth' } ); } );
			dots.appendChild( b );
		} );
		wl.after( dots );
		var bs = dots.querySelectorAll( 'button' );
		wl.addEventListener( 'scroll', function () {
			var max = wl.scrollWidth - wl.clientWidth, x = wl.scrollLeft, k = 0;
			if ( x >= max - 4 ) k = cards.length - 1;
			else cards.forEach( function ( c, i ) { if ( c.offsetLeft - cards[ 0 ].offsetLeft <= x + 20 ) k = i; } );
			bs.forEach( function ( d, i ) { d.classList.toggle( 'is-on', i === k ); } );
		}, { passive: true } );
	} );
} )();
