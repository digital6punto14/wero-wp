import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, Disabled } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import './style.css';

registerBlockType( metadata.name, {
	edit: ( { attributes, setAttributes, context } ) => (
		<div { ...useBlockProps() }>
			{ attributes.variant === 'bar' && (
				<InspectorControls>
					<PanelBody title={ __( 'Barra del progetto', 'wero-core' ) }>
						<ToggleControl __nextHasNoMarginBottom label={ __( 'Mostra “Torna alla home”', 'wero-core' ) } checked={ attributes.showHome } onChange={ ( showHome ) => setAttributes( { showHome } ) } />
					</PanelBody>
				</InspectorControls>
			) }
			<Disabled><ServerSideRender block={ metadata.name } attributes={ attributes } urlQueryArgs={ { post_id: context?.postId || 0 } } /></Disabled>
		</div>
	),
	save: () => null,
} );
