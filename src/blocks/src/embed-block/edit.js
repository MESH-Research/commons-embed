
/**
 * WordPress dependencies
 */
import {
	useState,
	useEffect,
	useRef
} from '@wordpress/element';

import {
	Button,
	SelectControl
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import FedoraRepository from '../class-fedora-repository';
import RepositoryObjectList from '../components/repository-object-list';

const FedoraEmbedEdit = props => {
	const {
		attributes,
		setAttributes
	} = props;

	const {
		baseURL,
		searchValues : searchValuesString
	} = attributes;

	const searchValues = searchValuesString ? JSON.parse( searchValuesString ) : [];

	const [ searchResults, setSearchResults ] = useState( null );
	const [ editSearch, setEditSearch ] = useState( false );

	useEffect( () => {
		if ( baseURL && searchValues.length === 0 ) {
			setAttributes( {
				searchValues: JSON.stringify( [
					{ field: '', searchText: '', comparator: '' },
					{ field: '', searchText: '', comparator: '' },
					{ field: '', searchText: '', comparator: '' }
				] )
			} );
		}
	 }, [ baseURL ] );

	const urlInput = useRef( null );

	const cleanUrl = ( newUrl ) => {
		let cleanedUrl = newUrl.trim();
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

	let blockContent = '';

	const setSearchField = ( index, field ) => {
		const newSearchValues = [ ...searchValues ];
		newSearchValues[ index ].field = field;
		setAttributes( { searchValues: JSON.stringify( newSearchValues ) } );
	}

	const setSearchText = ( index, text ) => {
		const newSearchValues = [ ...searchValues ];
		newSearchValues[ index ].searchText = text;
		setAttributes( { searchValues: JSON.stringify( newSearchValues ) } );
	}

	const setComparator = ( index, comparator ) => {
		const newSearchValues = [ ...searchValues ];
		newSearchValues[ index ].comparator = comparator;
		setAttributes( { searchValues: JSON.stringify( newSearchValues ) } );
	}

	const doSearch = () => {
		setEditSearch( false );
		const repository = new FedoraRepository( baseURL );
		setSearchResults( null );
		const searchParameters = { query: searchValues };
		repository.findObjects( searchParameters ).then( results => setSearchResults( results ) );
	}

	if ( searchResults && ! editSearch ) {
		blockContent = (
			<>
			<div className = 'fem-repository-object-list-edit'>
				<Button className = 'fem-repository-object-list-edit-button'
					isPrimary
					onClick = { () => setEditSearch( true ) }
				>
					Edit Search
				</Button>
			</div>
			<RepositoryObjectList
				baseURL     = { baseURL }
				objectsData = { searchResults }
			/>
			</>
		);
	} else if ( baseURL ) {
		const repository = new FedoraRepository( baseURL );
		const fields = repository.getFieldList();
		const selectOptions = fields.map( field => ( { label: field, value: field } ) );
		const comparatorOptions = [
			{ label: 'EQUALS',                value: '='  },
			{ label: 'CONTAINS',              value: '~'  },
			{ label: 'LESS THAN',             value: '<'  },
			{ label: 'GREATER THAN',          value: '>'  },
			{ label: 'LESS THAN OR EQUAL',    value: '<=' },
			{ label: 'GREATER THAN OR EQUAL', value: '>=' }
		];

		const searchFields = [];
		for ( let row = 0; row < 3; row++ ) {
			const selectedField = searchValues[ row ] && searchValues[ row ].field ? 
				searchValues[ row ].field :
				fields[0];
			const searchText = searchValues[ row ] ?
				searchValues[ row ].searchText :
				'';
			const comparator = searchValues[ row ] && searchValues[ row ].comparator ?
				searchValues[ row ].comparator :
				comparatorOptions[0].value;
			searchFields[ row ] = (
				<div className = 'fem-edit-search'
					key = { row }
				>
					<SelectControl className = 'fem-edit-search-fields-select'
						value    = { selectedField }
						options  = { selectOptions }
						onChange = { val => setSearchField( row, val ) }
					/>
					<SelectControl className = 'fem-edit-search-comparator-select'
						value    = { comparator }
						options  = { comparatorOptions }
						onChange = { val => setComparator( row, val ) }
					/>
					<input className = 'fem-edit-search-input'
						type     = 'text'
						onChange = { event => setSearchText( row, event.target.value ) }
						value    = { searchText }
					/>
				</div>
			)
		}

		blockContent = (
			<div className = 'fem-edit-search-wrapper'>
				<div className = 'fem-edit-search-instructions'>
					Search for items in the remote repository.
				</div>
				{ searchFields }
				<Button className = 'fem-edit-search-button'
					isPrimary
					onClick = { doSearch }
				>
					Search
				</Button>
			</div>
		);
	} else {
		blockContent = (
			<div>
				<input
					type        = 'url'
					ref         = { urlInput }
					placeholder = 'http://example.com'
					pattern     = "https?:\/\/.*"
					onChange    = { onUrlChange }
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

	return (
		<>
		{ blockContent }
		</>
	);
}

export default FedoraEmbedEdit;