/* WeRo — modulo “Scarica i report” (Contact Form 7).
   Dopo l'invio riuscito (evento wpcf7mailsent) mostra sotto il modulo
   i link ai PDF dei report scelti. Dati in window.weroReports (plugin). */
( function () {
	var cfg = window.weroReports || {};
	var files = cfg.files || {};
	var field = cfg.field || 'report';

	document.addEventListener( 'wpcf7mailsent', function ( event ) {
		var inputs = ( event.detail && event.detail.inputs ) || [];
		var chosen = inputs
			.filter( function ( input ) { return input.name === field + '[]' || input.name === field; } )
			.map( function ( input ) { return String( input.value ); } )
			.filter( function ( title, i, all ) { return files[ title ] && all.indexOf( title ) === i; } );
		if ( ! chosen.length ) {
			return;
		}

		var form = event.target;
		var wrap = form.closest( '.wpcf7' ) || form.parentNode;
		var box = wrap.querySelector( '.wero-downloads' );
		if ( ! box ) {
			box = document.createElement( 'section' );
			box.className = 'wero-downloads';
			box.setAttribute( 'tabindex', '-1' );
			box.setAttribute( 'aria-live', 'polite' );
			wrap.appendChild( box );
		}
		box.textContent = '';

		var h = document.createElement( 'h3' );
		h.className = 'wero-downloads__title';
		h.textContent = cfg.heading || 'I tuoi report';
		box.appendChild( h );

		var list = document.createElement( 'ul' );
		list.className = 'wero-downloads__list';
		chosen.forEach( function ( title ) {
			var li = document.createElement( 'li' );
			var name = document.createElement( 'span' );
			name.className = 'wero-downloads__name';
			name.textContent = title;
			var a = document.createElement( 'a' );
			a.className = 'wero-downloads__link';
			a.href = files[ title ];
			a.setAttribute( 'download', '' );
			a.textContent = cfg.download || 'Scarica il PDF';
			a.setAttribute( 'aria-label', ( cfg.download || 'Scarica il PDF' ) + ': ' + title );
			li.appendChild( name );
			li.appendChild( a );
			list.appendChild( li );
		} );
		box.appendChild( list );

		if ( cfg.note ) {
			var p = document.createElement( 'p' );
			p.className = 'wero-downloads__note';
			p.textContent = cfg.note;
			box.appendChild( p );
		}
		box.focus();
	} );
} )();
