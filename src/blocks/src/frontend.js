/**
 * WordPress dependencies
 */
import {
	render
} from '@wordpress/element';

/**
 * Internal dependencies
 */
import EmbedBlockFront from './embed-block/front';
import {
	attributesFromJSON
} from './util';

import './style.scss';

const embedBlockElements = document.getElementsByClassName('fem-embed-block-frontend');
if ( !! embedBlockElements ) {
	for ( let i = 0; i < embedBlockElements.length; i++ ) {
		const embedBlockElement = embedBlockElements[i];
		const attributes = attributesFromJSON( embedBlockElement.dataset.attributes );
		render (
			<EmbedBlockFront
				attributes = { attributes }
			/>,
			embedBlockElement
		);
	}
}