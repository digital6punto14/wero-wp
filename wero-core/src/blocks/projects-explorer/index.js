import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, useInnerBlocksProps, InnerBlocks, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import './style.css';

const TEMPLATE = [ [ 'core/heading', { content: __( 'Progetti', 'wero-core' ), className: 'is-style-heading-gradient', fontSize: 'section-small' } ] ];

registerBlockType( metadata.name, {
	edit: ( { attributes, setAttributes } ) => {
		const blockProps = useBlockProps( { className: 'wero-explorer is-editor' } );
		const inner = useInnerBlocksProps( { className: 'wero-explorer__title' }, { template: TEMPLATE, templateLock: 'all', allowedBlocks: [ 'core/heading' ] } );
		return (
			<>
				<InspectorControls>
					<PanelBody title={ __( 'Progetti', 'wero-core' ) }>
						<RangeControl __nextHasNoMarginBottom label={ __( 'Progetti per pagina nella lista', 'wero-core' ) } min={ 3 } max={ 20 } value={ attributes.perPage } onChange={ ( perPage ) => setAttributes( { perPage } ) } />
						<RangeControl __nextHasNoMarginBottom label={ __( 'Numero massimo di progetti', 'wero-core' ) } min={ 6 } max={ 80 } value={ attributes.max } onChange={ ( max ) => setAttributes( { max } ) } />
						<ToggleControl __nextHasNoMarginBottom label={ __( 'Giro iniziale della sfera', 'wero-core' ) } checked={ attributes.spin } onChange={ ( spin ) => setAttributes( { spin } ) } />
						<p className="description">{ __( 'I progetti, i mondi e le descrizioni si modificano nel menu Progetti. Ordine: data di pubblicazione o campo “Ordine”.', 'wero-core' ) }</p>
					</PanelBody>
				</InspectorControls>
				<div { ...blockProps }>
					<div { ...inner } />
					<div className="wero-editor-placeholder">{ __( 'Sfera 3D dei progetti, filtri, lista e griglia: si vedono nell’anteprima del sito.', 'wero-core' ) }</div>
				</div>
			</>
		);
	},
	save: () => <InnerBlocks.Content />,
} );
