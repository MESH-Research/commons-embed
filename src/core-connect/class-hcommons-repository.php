<?php
/**
 * Hcommons_Repository class.
 *
 * @since 0.3.0
 *
 * @package MESHResearch\CommonsConnect
 * @subpackage CoreConnect
 * @author Mike Thicke
 */

namespace MESHResearch\CommonsConnect\CoreConnect;

/**
 * API for Humanities Commons CORE repository.
 *
 * The repository is currently based on Fedora (@link
 * https://wiki.lyrasis.org/display/FEDORA38/REST+API).
 *
 * @since 0.3.0
 * @see Remote_Repository
 */
class Hcommons_Repository extends Remote_Repository {

	/**
	 * Constructor.
	 *
	 * Sets the results schema for Fedora repository search results.
	 *
	 * @since 0.3.0
	 *
	 * @param string $base_url Base URL of the repository. Leave null for default.
	 */
	public function __construct( $base_url = null ) {
		$results_schema = new JSON_Schema(
			[
				'$schema'    => 'http://json-schema.org/draft-04/schema#',
				'title'      => 'fedora-search-results',
				'type'       => 'array',
				'properties' => [
					'pid'         => [
						'description' => 'Fedora persistent identifier',
						'type'        => 'string',
					],
					'label'       => [
						'description' => 'Label of object',
						'type'        => 'string',
					],
					'state'       => [
						'description' => 'State of object',
						'type'        => 'string',
					],
					'ownerId'     => [
						'description' => 'ID of object owner',
						'type'        => 'array',
						'items'       => [
							'type' => 'string',
						],
					],
					'cDate'       => [
						'description' => 'Creation date of object',
						'type'        => 'string',
						'format'      => 'date-time',
					],
					'mDate'       => [
						'description' => 'Modification date',
						'type'        => 'string',
						'format'      => 'date-time',
					],
					'dcmDate'     => [
						'description' => 'Dublin core modification date',
						'type'        => 'string',
						'format'      => 'date-time',
					],
					'title'       => [
						'description' => 'Title of the object',
						'type'        => 'string',
					],
					'creator'     => [
						'description' => 'Creator(s) of object',
						'type'        => 'string',
					],
					'subject'     => [
						'description' => 'Subject(s) of object',
						'type'        => [ 'array', 'string' ],
						'items'       => [
							'type' => 'string',
						],
					],
					'description' => [
						'description' => 'Description of the object',
						'type'        => 'string',
					],
					'publisher'   => [
						'description' => 'Publisher(s) of the object',
						'type'        => 'string',
					],
					'contributor' => [
						'description' => 'Contributor(s) to the object',
						'type'        => 'array',
						'items'       => [
							'type' => 'string',
						],
					],
					'date'        => [
						'description' => 'Dublin Core date (publication date) of object',
						'type'        => 'string',
					],
					'type'        => [
						'description' => 'Dublin Core type of the object',
						'type'        => 'string',
					],
					'format'      => [
						'description' => 'Dublin Core format of the object',
						'type'        => 'string',
					],
					'identifier'  => [
						'description' => 'Dublin Core identifier(s) of the object',
						'type'        => 'array',
						'items'       => [
							'type' => 'string',
						],
					],
					'source'      => [
						'description' => 'Dublin Core source(s) of object',
						'type'        => 'array',
						'items'       => [
							'type' => 'string',
						],
					],
					'language'    => [
						'description' => 'Dublin Core language(s) of object',
						'type'        => 'array',
						'items'       => [
							'type' => 'string',
						],
					],
					'relation'    => [
						'description' => 'Dublin Core relation(s) of object',
						'type'        => 'array',
						'items'       => [
							'type' => 'string',
						],
					],
					'coverage'    => [
						'description' => 'Dublin Core coverage(s) of object',
						'type'        => 'array',
						'items'       => [
							'type' => 'string',
						],
					],
					'rights'      => [
						'description' => 'Dublin core rights of object',
						'type'        => 'array',
						'items'       => [
							'type' => 'string',
						],
					],
				],
			],
		);
		parent::__construct( $results_schema, $base_url );
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
	 * "terms" parameter.
	 *
	 * @since 0.3.0
	 *
	 * @param array $search_parameters {
	 *     An array of search parameters.
	 *
	 *     @type string field      The field to be queried.
	 *
	 *     @type string comparator { =, >, <, ~, >=, <= }
	 * }
	 *
	 * @return string A query string formatted for Fedora API.
	 */
	private static function generate_query( $search_parameters ) : string {
		$comparator_encodings = [
			'='  => '%3D',
			'>'  => '%3E',
			'<'  => '%3C',
			'~'  => '%7E',
			'>=' => '%3E%3D',
			'<=' => '%3C%3D',
		];

		$query_string = array_reduce(
			$search_parameters,
			function( $carry, $parameters_item ) use ( $comparator_encodings ) {
				if ( $parameters_item['field'] && $parameters_item['searchText'] && $parameters_item['comparator'] ) {
					if ( $carry ) {
						$carry .= '%20';
					}
					$carry .= $parameters_item['field'];
					$carry .= $comparator_encodings[ $parameters_item['comparator'] ];
					$carry .= "'" . str_replace( ' ', '%20', $parameters_item['searchText'] ) . "'";
				}
				return $carry;
			},
			''
		);

		return $query_string;
	}

	/**
	 * Finds objects in the remote repository.
	 *
	 * @see Remote_Repository::find_objects
	 *
	 * @since 0.3.0
	 *
	 * @param array $search_parameters Array of search parameters.
	 * @see Hcommons::generate_query
	 *
	 * @return array The found objects, conforming to $results_schema
	 */
	public function find_objects( $search_parameters ): ?array {
		$fields = $this->get_field_list();

		if ( ! $this->base_url ) {
			return null;
		}

		$parameters = array_reduce(
			$fields,
			function( $carry, $field ) {
				$carry[ $field ] = 'true';
				return $carry;
			},
			[]
		);

		$parameters['maxResults']   = 25;
		$parameters['resultFormat'] = 'xml';

		if ( count( $search_parameters ) > 0 ) {
			$query_string = self::generate_query( $search_parameters );
			if ( $query_string ) {
				$parameters['query'] = $query_string;
			}
		}

		$parameter_string = '';
		foreach ( $parameters as $key => $value ) {
			if ( $parameter_string ) {
				$parameter_string .= '&';
			}
			$parameter_string .= "$key=$value";
		}

		$fetch_address  = "{$this->base_url}objects/?{$parameter_string}";
		$response_xml   = \simplexml_load_file( $fetch_address );
		$response_array = json_decode( wp_json_encode( $response_xml ), true );
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		if ( $response_array['resultList'] && $response_array['resultList']['objectFields'] ) {
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$sanitized_response = $this->results_schema->validate_and_sanitize_data( $response_array['resultList']['objectFields'] );
			foreach ( $sanitized_response as &$response_item ) {
				if ( array_key_exists( 'pid', $response_item ) && array_key_exists( 'label', $response_item ) ) {
					$download_url = "{$this->base_url}download/{$response_item['pid']}/CONTENT/{$response_item['label']}";
					$view_url     = "{$this->base_url}view/{$response_item['pid']}/CONTENT/{$response_item['label']}";
				} else {
					$download_url = '';
					$view_url     = '';
				}
				$response_item['download'] = $download_url;
				$response_item['view']     = $view_url;
			}
			return $sanitized_response;
		}
		return null;
	}

	/**
	 * Get data for a particular object.
	 *
	 * @see Remote_Repository::get_object_data
	 *
	 * @since 0.3.0
	 *
	 * @param int|string $object_id The identifier for the object.
	 *
	 * @return array Associative array of object data.
	 */
	public function get_object_data( $object_id ) : ?array {
		if ( ! $this->base_url ) {
			return null;
		}

		$fetch_address  = "{$this->base_url}objects/{$object_id}/datastreams/descMetadata/content";
		$response_xml   = \simplexml_load_file( $fetch_address );
		$response_array = json_decode( wp_json_encode( $response_xml ), true );

		return $response_array;
	}
}
