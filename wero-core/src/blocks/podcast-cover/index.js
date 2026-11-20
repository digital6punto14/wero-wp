import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { Disabled } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import metadata from './block.json';
import './style.css';

/* Anteprima dal server: i dati arrivano dai campi del contenuto. */
registerBlockType( metadata.name, {
	edit: ( { attributes, context } ) => (
		<div { ...useBlockProps() }>
			<Disabled>
				<ServerSideRender block={ metadata.name } attributes={ attributes } urlQueryArgs={ { post_id: context?.postId || 0 } } />
			</Disabled>
		</div>
	),
	save: () => null,
} );
