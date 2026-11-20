import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, Disabled } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import './style.css';

registerBlockType( metadata.name, {
	edit: ( { attributes, setAttributes, context } ) => (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title={ __( 'Persone', 'wero-core' ) }>
					<TextControl __nextHasNoMarginBottom label={ __( 'Titolo', 'wero-core' ) } value={ attributes.heading } onChange={ ( heading ) => setAttributes( { heading } ) } help={ __( 'Le persone si collegano nel campo “Persone” del progetto. Senza persone il blocco non compare.', 'wero-core' ) } />
				</PanelBody>
			</InspectorControls>
			<Disabled><ServerSideRender block={ metadata.name } attributes={ attributes } urlQueryArgs={ { post_id: context?.postId || 0 } } EmptyResponsePlaceholder={ () => <p className="wero-editor-note">{ __( 'Nessuna persona collegata: il blocco non compare nel sito.', 'wero-core' ) }</p> } /></Disabled>
		</div>
	),
	save: () => null,
} );
