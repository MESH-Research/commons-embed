/**
 * A block for embedding items from a Fedora repository.
 * 
 * Attributes
 *  - baseURL      string Base URL of the repository.
 *  - searchValues string JSON enconded search values.
 */

/**
 * WordPress dependencies.
 */
 import { registerBlockType } from '@wordpress/blocks';
 import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import edit from './edit';

registerBlockType( 'fedora-embed', {
	title    : __( 'Fedora Embed', 'fedora-embed' ),
	caregory : 'embed',
	edit,
	save     : () => null
} );