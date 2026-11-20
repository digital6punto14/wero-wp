import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, Disabled } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import './style.css';

registerBlockType( metadata.name, {
	edit: ( { attributes, setAttributes } ) => (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title={ __( 'Quali partner', 'wero-core' ) }>
					<SelectControl
						__nextHasNoMarginBottom
						label={ __( 'Mostra', 'wero-core' ) }
						value={ attributes.group }
						options={ [
							{ label: __( 'Founder', 'wero-core' ), value: 'founder' },
							{ label: __( 'Business', 'wero-core' ), value: 'business' },
							{ label: __( 'Ricerca', 'wero-core' ), value: 'ricerca' },
							{ label: __( 'Tutti', 'wero-core' ), value: 'all' },
						] }
						onChange={ ( group ) => setAttributes( { group } ) }
						help={ __( 'Loghi, testi e link si modificano nel menu Partner.', 'wero-core' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<Disabled><ServerSideRender block={ metadata.name } attributes={ attributes } /></Disabled>
		</div>
	),
	save: () => null,
} );
