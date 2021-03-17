

export default class FedoraRepository {
	constructor( baseURL ) {
		this.baseURL = baseURL;

		this.searchResultSchema = {
			pid : {
				description: 'Fedora persistent identifier',
				type: 'string',
			},
			label : {
				description: 'Label of object',
				type: 'string',
			},
			state : {
				description: 'State of object',
				type: 'string'
			},
			ownerId : {
				description: 'ID of object owner',
				type: 'string'
			},
			cDate : {
				description: 'Creation date of object',
				type: 'string',
				format: 'date-time'
			},
			mDate : {
				description: 'Modification date',
				type: 'string',
				format: 'date-time'
			},
			dcmDate : {
				description: 'Dublin core modification date',
				type: 'string',
				format: 'date-time'
			},
			title : {
				description: 'Title of the object',
				type: 'string'
			},
			creator : {
				description: 'Creator(s) of object',
				type: 'array',
				items: {
					type: 'string'
				}
			},
			subject : {
				description: 'Subject(s) of object',
				type: 'array',
				items: {
					type: 'string'
				}
			},
			description : {
				description : 'Description of the object',
				type: 'string'
			},
			publisher : {
				description : 'Publisher(s) of the object',
				type: 'array',
				items: {
					type: 'string'
				}
			},
			contributor : {
				description: 'Contributor(s) to the object',
				type: 'array',
				items: {
					type: 'string'
				}
			},
			date : {
				description: 'Dublin Core date (publication date) of object',
				type: 'string'
			},
			type : {
				description: 'Dublin Core type of the object',
				type: 'string'
			},
			format : {
				description: 'Dublin Core format of the object',
				type: 'string'
			},
			identifier : {
				description: 'Dublin Core identifier(s) of the object',
				type: 'array',
				items: {
					type: 'string'
				}
			},
			source : {
				description: 'Dublin Core source(s) of object',
				type: 'array',
				items: {
					type: 'string'
				}
			},
			language : {
				description: 'Dublin Core language(s) of object',
				type: 'array',
				items: {
					type: 'string'
				}
			},
			relation : {
				description: 'Dublin Core relation(s) of object',
				type: 'array',
				items: {
					type: 'string'
				}
			},
			coverage : {
				description: 'Dublin Core coverage(s) of object',
				type: 'array',
				items: {
					type: 'string'
				}
			},
			rights : {
				description: 'Dublin core rights of object',
				type: 'array',
				items: {
					type: 'string'
				}
			}
		}
	}

	getFieldList() {
		return Object.keys( this.searchResultSchema );
	}

	findObjects( searchParameters ) {
		const parameterDefaults = this.getFieldList().reduce( ( params, field ) => {
			params[ field ] = true;
			return params;
		} );

		parameterDefaults.maxResults = 25;
		parameterDefaults.resultFormat = 'xml';

		const finalParameters = {
			...parameterDefaults,
			searchParameters
		}

		const parameterString = Object.entries( finalParameters )
			.reduce( ( paramString, params) => {
				if ( paramString ) {
					paramString += '&';
				}
				paramString += params[0] + '=' + params[1];
				return paramString;
		} );

		return fetch( `${ this.baseURL }/objects/?${ parameterString }` );
	}
}