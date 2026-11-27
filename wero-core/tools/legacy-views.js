/**
 * Dopo `wp-scripts build`: converte gli script dei blocchi (build/blocks/{blocco}/view.js)
 * in JavaScript leggibile anche dai browser indicati in "browserslist" di package.json
 * (Safari/iOS 12+, Chrome 70+), poi li rimpicciolisce senza reintrodurre sintassi recente.
 *
 * Serve perché la build standard lascia in uscita `??` e `?.` (Safari 13.1+, Chrome 80+).
 * Uso: npm run build (eseguito in automatico come "postbuild").
 */
const fs = require( 'fs' );
const path = require( 'path' );
const babel = require( '@babel/core' );
const terser = require( 'terser' );
const browserslist = require( 'browserslist' );

const root = path.join( __dirname, '..', 'build', 'blocks' );
const targets = browserslist.findConfig( path.join( __dirname, '..' ) ).defaults;

( async () => {
	for ( const block of fs.readdirSync( root ) ) {
		const file = path.join( root, block, 'view.js' );
		if ( ! fs.existsSync( file ) ) {
			continue;
		}
		const source = fs.readFileSync( file, 'utf8' );
		const out = babel.transformSync( source, {
			babelrc: false,
			configFile: false,
			sourceType: 'script',
			presets: [ [ require.resolve( '@babel/preset-env' ), { targets: { browsers: targets }, modules: false } ] ],
		} );
		const min = await terser.minify( out.code, {
			ecma: 5,
			compress: { passes: 2 },
			format: { comments: false },
		} );
		fs.writeFileSync( file, min.code );
		console.log( 'legacy-views:', block, source.length, '→', min.code.length, 'byte' );
	}
} )();
