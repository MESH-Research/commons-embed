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
import FedoraRepository from '../class-fedora-repository';

const FedoraEmbedFront = props => {
	const {
		attributes
	} = props;

	const {
		baseURL,
		searchValues : searchValuesString
	} = attributes;

	const searchValues = searchValuesString ? JSON.parse( searchValuesString ) : [];

	const [ searchResults, setSearchResults ] = useState( null );

	if ( searchValues && baseURL && ! searchResults ) {
		const repository = new FedoraRepository( baseURL );
		const searchParameters = { query: searchValues };
		repository.findObjects( searchParameters ).then( results => setSearchResults( results ) );
	}

	return (
		<div className = 'fem-embed-block-front'>
			{ searchResults &&
				<RepositoryObjectList
					baseURL     = { baseURL }
					objectsData = { searchResults }
				/>
			}
		</div>
	);
}

export default FedoraEmbedFront;