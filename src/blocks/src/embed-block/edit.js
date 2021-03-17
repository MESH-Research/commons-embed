
/**
 * WordPress dependencies
 */
import {
	useState,
	useEffect,
	useRef
} from '@wordpress/element';

import {
	Button
} from '@wordpress/components';

const FedoraEmbedEdit = props => {
	const {
		attributes,
		setAttributes
	} = props;

	const {
		baseURL,
		searchValues
	} = attributes;

	const urlInput = useRef( null );

	const cleanUrl = ( newUrl ) => {
		cleanedUrl = cleanedUrl.trim();
		cleanedUrl = cleanedUrl.endsWith('/') ? cleanedUrl.slice(0, -1 ) : cleanedUrl;
		cleanedUrl = cleanedUrl
			.startsWith('http://') || cleanedUrl.startsWith('https://' ) ?
			cleanedUrl :
			'http://' + cleanedUrl;
		return cleanedUrl;
	}

	const onUrlChange = event => {
		setAttributes( { baseURL: event.target.value } );
	}

	const maybeSaveUrl = event => {
		if ( event.key === 'Enter' ) {
			event.preventDefault();
			event.stopPropagation();
			saveUrl();
		}
	}

	const saveUrl = () => {
			const cleanedUrl = cleanUrl( baseURL );
			setAttributes( { baseURL: cleanedUrl } );
		}
	}

	let blockContent = '';

	if ( ! baseURL ) {
		blockContent = (
			<div>
				<input
					type        = 'url'
					ref         = { urlInput }
					placeholder = 'http://example.com'
					pattern     = "https?:\/\/.*"
					onChange    = { onUrlChange}
					onKeyDown   = { maybeSaveUrl }
					value       = { remoteData.url }
				/>
				<Button
					isPrimary
					onClick = { saveUrl }
				>
					Save
				</Button>
			</div>
		);
	}

	if ( ! searchValues ) {
		
	}

	return (
		<>
		{ blockContent }
		</>
	)
}