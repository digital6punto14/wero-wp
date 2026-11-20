import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, useInnerBlocksProps, InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import './style.css';

const TEMPLATE = [
	[ 'core/heading', { level: 1, className: 'is-style-heading-gradient wero-hero__title', fontSize: 'hero', content: __( 'Titolo', 'wero-core' ) } ],
	[ 'core/paragraph', { className: 'wero-hero__lead', content: __( 'Testo introduttivo.', 'wero-core' ) } ],
	[ 'core/buttons', { className: 'wero-hero__actions' }, [ [ 'core/button', { className: 'wero-play', text: __( 'Guarda il video', 'wero-core' ), url: '#guarda' } ] ] ],
];

function Edit( { attributes, setAttributes } ) {
	const { poster, posterId, mediaUrl, mediaId, videoLabel } = attributes;
	const blockProps = useBlockProps( { className: 'wero-hero is-editor' } );
	const innerBlocksProps = useInnerBlocksProps( { className: 'wero-hero__inner' }, { template: TEMPLATE, templateLock: false } );
	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Copertina e video', 'wero-core' ) }>
					<MediaUploadCheck>
						<MediaUpload
							allowedTypes={ [ 'image' ] }
							value={ posterId }
							onSelect={ ( m ) => setAttributes( { posterId: m.id, poster: m.url } ) }
							render={ ( { open } ) => (
								<Button variant="secondary" onClick={ open }>{ poster ? __( 'Cambia copertina', 'wero-core' ) : __( 'Scegli copertina', 'wero-core' ) }</Button>
							) }
						/>
					</MediaUploadCheck>
					<p />
					<TextControl
						__nextHasNoMarginBottom
						label={ __( 'Link del video', 'wero-core' ) }
						help={ __( 'YouTube, Vimeo o l’indirizzo di un file. Oppure carica un file qui sotto.', 'wero-core' ) }
						value={ mediaUrl }
						onChange={ ( v ) => setAttributes( { mediaUrl: v, mediaId: 0 } ) }
					/>
					<p />
					<MediaUploadCheck>
						<MediaUpload
							allowedTypes={ [ 'video' ] }
							value={ mediaId }
							onSelect={ ( m ) => setAttributes( { mediaId: m.id, mediaUrl: m.url } ) }
							render={ ( { open } ) => <Button variant="secondary" onClick={ open }>{ __( 'Carica o scegli un file video', 'wero-core' ) }</Button> }
						/>
					</MediaUploadCheck>
					<p />
					<TextControl
						__nextHasNoMarginBottom
						label={ __( 'Titolo del video (per i lettori di schermo)', 'wero-core' ) }
						value={ videoLabel }
						onChange={ ( v ) => setAttributes( { videoLabel: v } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<section { ...blockProps }>
				<div className="wero-hero__media">{ poster && <img className="wero-hero__poster" src={ poster } alt="" /> }</div>
				<div className="wero-hero__scrim" aria-hidden="true" />
				<div className="wero-hero__content"><div { ...innerBlocksProps } /></div>
			</section>
		</>
	);
}

registerBlockType( metadata.name, {
	edit: Edit,
	save: () => <InnerBlocks.Content />,
} );
