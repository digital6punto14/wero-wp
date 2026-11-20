import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, Disabled } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import './style.css';

registerBlockType( metadata.name, {
	edit: ( { attributes, setAttributes } ) => (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title={ __( 'Voci', 'wero-core' ) }>
					<RangeControl __nextHasNoMarginBottom label={ __( 'Quante voci', 'wero-core' ) } min={ 1 } max={ 12 } value={ attributes.max } onChange={ ( max ) => setAttributes( { max } ) } />
				</PanelBody>
			</InspectorControls>
			<Disabled><ServerSideRender block={ metadata.name } attributes={ attributes } /></Disabled>
		</div>
	),
	save: () => null,
} );
