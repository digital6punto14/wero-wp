/* Mondi su mobile: puntini sincronizzati con lo scorrimento orizzontale. */
( function () {
	document.querySelectorAll( '.wero-worlds' ).forEach( function ( root ) {
		var wl = root.querySelector( '.wero-worlds__list' ), wd = root.querySelectorAll( '.wero-worlds__dots button' );
		var cards = wl.querySelectorAll( '.wero-world' );
		if ( ! wd.length ) return;
		function idx() {
			var max = wl.scrollWidth - wl.clientWidth;
			if ( wl.scrollLeft >= max - 4 ) return cards.length - 1;
			var x = wl.scrollLeft, k = 0;
			cards.forEach( function ( c, i ) { if ( c.offsetLeft - cards[ 0 ].offsetLeft <= x + 20 ) k = i; } );
			return k;
		}
		wl.addEventListener( 'scroll', function () { var k = idx(); wd.forEach( function ( d, i ) { d.classList.toggle( 'is-on', i === k ); } ); }, { passive: true } );
		wd.forEach( function ( d, i ) {
			d.addEventListener( 'click', function () { wl.scrollTo( { left: Math.min( wl.scrollWidth - wl.clientWidth, cards[ i ].offsetLeft - cards[ 0 ].offsetLeft ), behavior: 'smooth' } ); } );
		} );
	} );
} )();
