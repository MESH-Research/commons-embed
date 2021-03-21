import RepositoryObject from './repository-object';

const RepositoryObjectList = props => {
	const {
		baseURL,
		objectsData
	} = props;

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