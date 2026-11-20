import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import './style.css';

/* "mm:ss" o "hh:mm:ss" → secondi */
const toSeconds = ( t ) => ( t || '' ).split( ':' ).map( Number ).reduce( ( a, n ) => a * 60 + ( isNaN( n ) ? 0 : n ), 0 );

registerBlockType( metadata.name, {
	edit: ( { attributes, setAttributes } ) => (
		<div { ...useBlockProps( { className: 'wero-chapter-edit' } ) }>
			<input
				type="text"
				aria-label={ __( 'Tempo (mm:ss)', 'wero-core' ) }
				value={ attributes.time }
				placeholder="00:00"
				onChange={ ( e ) => setAttributes( { time: e.target.value, seconds: toSeconds( e.target.value ) } ) }
			/>
			<RichText
				tagName="span"
				aria-label={ __( 'Titolo del capitolo', 'wero-core' ) }
				placeholder={ __( 'Titolo del capitolo', 'wero-core' ) }
				value={ attributes.label }
				allowedFormats={ [ 'core/bold', 'core/italic' ] }
				onChange={ ( label ) => setAttributes( { label } ) }
			/>
		</div>
	),
	save: () => null,
} );
