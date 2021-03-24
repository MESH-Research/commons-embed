/**
 * A list of repository objects.
 */

/**
 * Internal dependencies
 */
import RepositoryObject from './repository-object';

const RepositoryObjectList = props => {
	const {
		baseURL,
		objectsData
	} = props;

	/**
	 * The Fedora API ( or the simpleXML parsed XML anyways ) returns unique
	 * results as simply the resultant object, while multiple results are in an
	 * array.
	 */
	let objectArray = [];
	if ( Array.isArray( objectsData.resultList.objectFields ) ) {
		objectArray = objectsData.resultList.objectFields;
	} else {
		objectArray[0] = objectsData.resultList.objectFields;
	}

	const objectsList = objectArray.map( ( objectItem, index ) => {
		return (
			<RepositoryObject
				key        = { index }
				objectData = { objectItem }
				baseURL    = { baseURL }
			/>
		);
	} );

	return (
		<div className = 'fem-repository-object-list'>
			{ objectsList }
		</div>
	)
}

export default RepositoryObjectList;