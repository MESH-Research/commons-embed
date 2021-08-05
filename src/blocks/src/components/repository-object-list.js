/**
 * A list of repository objects.
 */

/*
 * Internal dependencies
 */
import RepositoryObject from './repository-object';

const RepositoryObjectList = props => {
	const {
		objectsData,
	} = props;

	/*
	 * The Fedora API ( or the simpleXML parsed XML anyways ) returns unique
	 * results as simply the resultant object, while multiple results are in an
	 * array.
	 */
	let objectArray = [];
	if ( Array.isArray( objectsData ) ) {
		objectArray = objectsData;
	} else {
		objectArray[0] = objectsData;
	}

	// If there is no object data (yet), create some empty objects to render as
	// placeholders.
	const placeholderCount = 3;
	if ( objectArray.length === 0 ) {
		objectArray = new Array( placeholderCount );
		objectArray.fill( null );
	}

	const objectsList = objectArray.map( ( objectItem, index ) => {
		return (
			<RepositoryObject
				key        = { index }
				objectData = { objectItem }
			/>
		);
	} );

	return (
		<div className = 'mcc-repository-object-list'>
			{ objectsList }
		</div>
	)
}

export default RepositoryObjectList;