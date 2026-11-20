/* Editor: mostra i valori veri della sorgente "wero/site" (email, LinkedIn, copyright…)
   nei blocchi collegati, invece dell'etichetta della sorgente. I valori arrivano dal server
   (window.weroSiteBindings); si modificano in Impostazioni › WeRo. */
( function ( wp ) {
	if ( ! wp || ! wp.blocks || ! wp.blocks.registerBlockBindingsSource ) return;
	var values = window.weroSiteBindings || {};
	wp.blocks.registerBlockBindingsSource( {
		name: 'wero/site',
		label: 'Impostazioni sito WeRo',
		getValues: function ( args ) {
			var out = {};
			Object.keys( args.bindings || {} ).forEach( function ( attr ) {
				var key = ( args.bindings[ attr ].args || {} ).key;
				out[ attr ] = values[ key ] !== undefined ? values[ key ] : '';
			} );
			return out;
		},
		canUserEditValue: function () { return false; },
	} );
} )( window.wp );
