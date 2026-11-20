import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, Disabled } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import './style.css';

registerBlockType( metadata.name, {
	edit: ( { attributes, setAttributes } ) => (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title={ __( 'Timeline', 'wero-core' ) }>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __( 'Effetto “sezione ferma” su desktop', 'wero-core' ) }
						help={ __( 'Spento: la timeline scorre in orizzontale anche su desktop, come su mobile.', 'wero-core' ) }
						checked={ attributes.sticky }
						onChange={ ( sticky ) => setAttributes( { sticky } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<Disabled><ServerSideRender block={ metadata.name } attributes={ attributes } /></Disabled>
		</div>
	),
	save: () => null,
} );
