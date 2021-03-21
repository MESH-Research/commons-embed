/**
 * WordPress dependencies.
 */
import apiFetch from '@wordpress/api-fetch';

/**
 * External dependencies
 */
import parser from 'xml-js';

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

	/**
	 * Generates a query string from an array of key-comparator-value objects.
	 *
	 * @see: https://wiki.lyrasis.org/display/FEDORA38/REST+API#RESTAPI-findObjects
	 * 
	 * query: a sequence of space-separated conditions. A condition consists of
	 * a metadata element name followed directly by an operator, followed
	 * directly by a value. Valid element names are (pid, label, state,
	 * ownerId, cDate, mDate, dcmDate, title, creator, subject, description,
	 * publisher, contributor, date, type, format, identifier, source,
	 * language, relation, coverage, rights). Valid operators are: contains (~),
	 * equals (=), greater than (>), less than (<), greater than or equals
	 * (>=), less than or equals (<=). The contains (~) operator may be used in
	 * combination with the ? and * wildcards to query for simple string
	 * patterns. Space-separators should be encoded in the URL as %20.
	 * Operators must be encoded when used in the URL syntax as follows: the
	 * (=) operator must be encoded as %3D, the (>) operator as %3E, the (<)
	 * operator as %3C, the (>=) operator as %3E%3D, the (<=) operator as
	 * %3C%3D, and the (~) operator as %7E. Values may be any string. If the
	 * string contains a space, the value should begin and end with a single
	 * quote character ('). If all conditions are met for an object, the object
	 * is considered a match. Do NOT use this parameter in combination with the
	 * "terms" parameter
	 */
	static generateQuery( searchParameters ) {
		const comparatorEncodings = {
			'='  : '%3D',
			'>'  : '%3E',
			'<'  : '%3C',
			'~'  : '%7E',
			'>=' : '%3E%3D',
			'<=' : '%3C%3D'
		}
		
		const queryString = searchParameters.reduce( ( qString, { field, comparator, searchText } ) => {
			if ( searchText )  {
				if ( qString ) {
					qString += '%20'; // Space separator between query items
				}
				qString += field;
				qString += comparatorEncodings[ comparator ];
				qString += "'" + searchText.replace( ' ', '%20' ) + "'"; 
			}
			return qString; 
		}, '' );

		return queryString;
	}

	static parseXMLResponse( response ) {
		const parsedResponse = JSON.parse( parser.xml2json( response ) );
		return parsedResponse;
	}

	getFieldList() {
		return Object.keys( this.searchResultSchema );
	}

	findObjects( searchParameters ) {
		const fields = this.getFieldList();
		const parameterDefaults = fields.reduce( ( params, field ) => {
			params[ field ] = true;
			return params;
		}, {} );

		parameterDefaults.maxResults = 25;
		parameterDefaults.resultFormat = 'xml';

		const newParameters = { ...searchParameters }
		if ( newParameters.query && typeof newParameters.query !== 'string' ) {
			newParameters.query = FedoraRepository.generateQuery( newParameters.query );
		}

		const finalParameters = {
			...parameterDefaults,
			...newParameters
		}

		const parameterString = Object.entries( finalParameters )
			.reduce( ( paramString, params) => {
				if ( paramString ) {
					paramString += '&';
				}
				paramString += params[0] + '=' + params[1];
				return paramString;
		}, '' );

		const fetchAddress = `${ this.baseURL }objects/?${ parameterString }`;

		return apiFetch( { 
			path: '/fem-embed/v1/find',
			method: 'POST',
			data: { baseURL: this.baseURL, parameterString: parameterString } 
		} ).then( result => { 
			return result 
		} );
	}

	getItemData( pid ) {
		return apiFetch( { 
			path: '/fem-embed/v1/item',
			method: 'POST',
			data: { baseURL: this.baseURL, pid: pid } 
		} ).then( result => { 
			return result 
		} );
	}
}