/**
 * Return icon element for associated mime type.
 */
export function getFileIcon( mime_type ) {
	let iconClass= null;
	switch ( mime_type ) {
		case 'application/pdf':
			iconClass= 'fiv-icon-pdf';
			break;
		case 'application/msword':
			iconClass= 'fiv-icon-doc';
			break;
		case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			iconClass= 'fiv-icon-docx';
			break;
		case 'application/vnd.ms-powerpoint':
			iconClass= 'fiv-icon-ppt';
			break;
		case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
			iconClass= 'pptx';
			break;
	}

	const spanElement = iconClass ? <span className = { `fiv-viv ${ iconClass}`}></span> : null;

	return spanElement;
}

/**
 * Parses attributes passed as JSON (json encoded php associative array).
 *
 * @todo: Use a schema to check datatypes, so that we know whether to cast
 * strings as booleans, etc. Currently an attribute with value 'true' is
 * converted to boolean regardless of the attribute's type.
 *
 * @param {*} attributeJSON
 * @return {Object} Attributes object in same format as WordPress attributes
 * objects.
 */
 export function attributesFromJSON( attributeJSON ) {
	const attributes = JSON.parse( attributeJSON );
	for ( const [ key, value ] of Object.entries( attributes ) ) {
		if ( value === 'false' ) {
			attributes[key] = false;
		}
		if ( value === 'true' ) {
			attributes[key] = true;
		}
	}
	return attributes;
}