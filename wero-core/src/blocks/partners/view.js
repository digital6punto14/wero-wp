/* Partner: la scheda si apre al passaggio del mouse (CSS) e al clic/tocco (fissa aperta).
   Esc o un clic fuori la chiude. */
( function () {
	var partners = document.querySelectorAll( '.wero-partner' );
	if ( ! partners.length ) return;
	function closeAll( except ) {
		partners.forEach( function ( p ) {
			if ( p === except ) return;
			p.classList.remove( 'is-open' );
			p.querySelector( '.wero-partner__logo' ).setAttribute( 'aria-expanded', 'false' );
		} );
	}
	partners.forEach( function ( p ) {
		var btn = p.querySelector( '.wero-partner__logo' );
		btn.addEventListener( 'click', function () {
			var open = ! p.classList.contains( 'is-open' );
			closeAll( p );
			p.classList.toggle( 'is-open', open );
			btn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		} );
	} );
	document.addEventListener( 'click', function ( e ) { if ( ! e.target.closest( '.wero-partner' ) ) closeAll(); } );
	document.addEventListener( 'keydown', function ( e ) { if ( e.key === 'Escape' ) closeAll(); } );
} )();
