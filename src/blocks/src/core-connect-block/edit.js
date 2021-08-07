/**
 * Block editor view of the block.
 */

/**
 * WordPress dependencies
 */
import {
	useState,
	useEffect
} from '@wordpress/element';

import {
	Button,
	SelectControl
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import RemoteRepository from '../class-remote-repository';
import RepositoryObjectList from '../components/repository-object-list';

/**
 * Block editor view of the block.
 */
const CommonsEmbedEdit = props => {
	const {
		attributes,
		setAttributes
	} = props;

	const {
		searchValues : searchValuesString
	} = attributes;

	const searchValues = searchValuesString ? JSON.parse( searchValuesString ) : [];

	const [ searchResults, setSearchResults ] = useState( null );
	const [ editSearch, setEditSearch ] = useState( false );
	const [ fieldList, setFieldList ] = useState( [] );

	useEffect( () => {
		if ( searchValues.length === 0 ) {
			setAttributes( {
				searchValues: JSON.stringify( [
					{ field: '', searchText: '', comparator: '' },
					{ field: '', searchText: '', comparator: '' },
					{ field: '', searchText: '', comparator: '' }
				] )
			} );
		}
	 }, [] );

	 useEffect( () => {
		 if ( fieldList.length === 0 ) {
			const repository = new RemoteRepository();
			repository.getFieldList().then( result => setFieldList( result ) );
		 }
	 }, [] );

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
		const repository = new RemoteRepository();
		setSearchResults( null );
		repository.findObjects( searchValues ).then( results => setSearchResults( results ) );
	}

	/**
	 * Renders block content based on three possible states:
	 * 
	 * (1) There are search results to display and the user has not clicked 'Edit Search'
	 *      -> display search results
	 * (2) The user has clicked 'Search' but there are no results
	 *      -> display 'Search returned no results.'
	 * (3) The search has not been performed, or the user has clicked 'Edit Search'
	 *      -> display search form
	 */
	if ( searchResults && ! editSearch ) {
		blockContent = (
			<>
			<div className = 'mcc-repository-object-list-edit'>
				<Button className = 'mcc-repository-object-list-edit-button'
					isPrimary
					onClick = { () => setEditSearch( true ) }
				>
					Edit Search
				</Button>
			</div>
			{ searchResults.length > 0 ?
				<RepositoryObjectList
					objectsData = { searchResults }
				/>
				:
				<div className = 'mcc-edit-search-wrapper no-results'>
					Search returned no results.
				</div>
			}
			</>
		);
	} else {
		const selectOptions = fieldList.map( field => ( { label: field, value: field } ) );
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
				fieldList[0];
			const searchText = searchValues[ row ] ?
				searchValues[ row ].searchText :
				'';
			const comparator = searchValues[ row ] && searchValues[ row ].comparator ?
				searchValues[ row ].comparator :
				comparatorOptions[0].value;
			searchFields[ row ] = (
				<div className = 'mcc-edit-search'
					key = { row }
				>
					<SelectControl className = 'mcc-edit-search-fields-select'
						value    = { selectedField }
						options  = { selectOptions }
						onChange = { val => setSearchField( row, val ) }
					/>
					<SelectControl className = 'mcc-edit-search-comparator-select'
						value    = { comparator }
						options  = { comparatorOptions }
						onChange = { val => setComparator( row, val ) }
					/>
					<input className = 'mcc-edit-search-input'
						type     = 'text'
						onChange = { event => setSearchText( row, event.target.value ) }
						value    = { searchText }
					/>
				</div>
			)
		}

		blockContent = (
			<div className = 'mcc-edit-search-wrapper'>
				<div className = 'mcc-edit-search-instructions'>
					Search for items in the remote repository.
				</div>
				{ searchFields }
				<Button className = 'mcc-edit-search-button'
					isPrimary
					onClick = { doSearch }
				>
					Search
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

export default CommonsEmbedEdit;