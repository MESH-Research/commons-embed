/**
 * Class RemoteRepository.
 */

/**
 * WordPress dependencies.
 */
import apiFetch from '@wordpress/api-fetch';

/**
 * Represents a remote Humanities Commons repository.
 *
 * Connects to the REST API, which in turn connects to the Humanities Commons
 * CORE API.
 */
export default class RemoteRepository {
	/**
	 * Returns a list of fields based on the results schema.
	 */
	getFieldList() {
		return apiFetch( { 
			path: '/commons-connect/v1/fields',
			method: 'GET'
		} ).then( result => { 
			return result 
		} );
	}

	/**
	 * Find objects in the repository.
	 *
	 * @param Object searchParameters Each object key corresponds to a search
	 * parameter and the value is the value of that parameter for the search. 
	 */
	findObjects( searchParameters ) {
		return apiFetch( { 
			path: '/commons-connect/v1/find',
			method: 'POST',
			data: { searchParameters: searchParameters } 
		} ).then( result => { 
			return result 
		} );
	}

	/**
	 * Gets data for a particular Humanities Commons item.
	 *
	 * @param string pid The Persistent Identifier of the object. On HC, in the form HC:#####
	 */
	getItemData( pid ) {
		return apiFetch( { 
			path: '/commons-connect/v1/item',
			method: 'POST',
			data: { pid: pid } 
		} ).then( result => { 
			return result 
		} );
	}
}