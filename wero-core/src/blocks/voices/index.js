import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, Disabled, Button } from '@wordpress/components';
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
					<p>{ __( 'Titolo, copertina, descrizione e link del video di ogni voce si modificano nel menu “Voci dei progetti”. L’ordine si cambia con il campo “Ordine”.', 'wero-core' ) }</p>
					<Button variant="secondary" href="edit.php?post_type=voce" target="_blank">{ __( 'Modifica le voci', 'wero-core' ) }</Button>
				</PanelBody>
			</InspectorControls>
			<Disabled><ServerSideRender block={ metadata.name } attributes={ attributes } /></Disabled>
		</div>
	),
	save: () => null,
} );
