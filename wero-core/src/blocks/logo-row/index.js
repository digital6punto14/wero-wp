import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, ToggleControl, Disabled } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import './style.css';

registerBlockType( metadata.name, {
	edit: ( { attributes, setAttributes } ) => (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title={ __( 'Riga dei loghi', 'wero-core' ) }>
					<RangeControl __nextHasNoMarginBottom label={ __( 'Lentezza (secondi per logo)', 'wero-core' ) } min={ 1 } max={ 10 } step={ 0.2 } value={ attributes.speed } onChange={ ( speed ) => setAttributes( { speed } ) } />
					<RangeControl __nextHasNoMarginBottom label={ __( 'Curvatura', 'wero-core' ) } min={ 10 } max={ 85 } value={ attributes.curve } onChange={ ( curve ) => setAttributes( { curve } ) } />
					<RangeControl __nextHasNoMarginBottom label={ __( 'Altezza dei loghi (%)', 'wero-core' ) } min={ 15 } max={ 60 } value={ attributes.tileHeight } onChange={ ( tileHeight ) => setAttributes( { tileHeight } ) } />
					<ToggleControl __nextHasNoMarginBottom label={ __( 'Mostra la legenda Business / Ricerca', 'wero-core' ) } checked={ attributes.showLegend } onChange={ ( showLegend ) => setAttributes( { showLegend } ) } />
					<p className="description">{ __( 'I loghi si aggiungono e si ordinano nel menu Partner (tutti tranne i founder).', 'wero-core' ) }</p>
				</PanelBody>
			</InspectorControls>
			<Disabled><ServerSideRender block={ metadata.name } attributes={ attributes } /></Disabled>
		</div>
	),
	save: () => null,
} );
