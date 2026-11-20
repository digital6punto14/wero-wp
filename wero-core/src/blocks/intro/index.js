import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import './style.css';

registerBlockType( metadata.name, {
	edit: () => (
		<div { ...useBlockProps( { className: 'wero-editor-note' } ) }>
			{ __( 'Intro del logo: si vede solo nel sito, all’apertura della home. Attiva/disattiva da Impostazioni › WeRo.', 'wero-core' ) }
		</div>
	),
	save: () => null,
} );
