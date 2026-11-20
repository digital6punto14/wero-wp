import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, useInnerBlocksProps, InnerBlocks } from '@wordpress/block-editor';
import metadata from './block.json';
import './style.css';

registerBlockType( metadata.name, {
	edit: () => {
		const inner = useInnerBlocksProps( useBlockProps( { className: 'wero-chapters is-editor' } ), {
			allowedBlocks: [ 'wero/chapter' ],
			template: [ [ 'wero/chapter', { time: '00:00', seconds: 0 } ] ],
		} );
		return <div { ...inner } />;
	},
	save: () => <InnerBlocks.Content />,
} );
