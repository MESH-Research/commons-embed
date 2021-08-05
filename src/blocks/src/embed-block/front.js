/**
 * Frontend view of the block.
 */

/**
 * WordPress dependencies
 */
import {
	useState
} from  '@wordpress/element';

/**
 * Internal dependencies
 */
import RepositoryObjectList from '../components/repository-object-list';
import RemoteRepository from '../class-remote-repository';

const CommonsEmbedFront = props => {
	const {
		attributes
	} = props;

	const {
		searchValues : searchValuesString
	} = attributes;

	const searchValues = searchValuesString ? JSON.parse( searchValuesString ) : [];

	const [ searchResults, setSearchResults ] = useState( null );

	if ( searchValues.length > 0 && ! searchResults ) {
		const repository = new RemoteRepository();
		repository.findObjects( searchValues ).then( results => setSearchResults( results ) );
	} else if ( searchValues.length === 0 ) {
		return (
			<div className = 'mcc-embed-block-front'>
				<em>No search terms have been saved.</em>
			</div>
		)
	}

	return (
		<div className = 'mcc-embed-block-front'>
			{ searchResults &&
				<RepositoryObjectList
					objectsData = { searchResults }
				/>
			}
		</div>
	);
}

export default CommonsEmbedFront;