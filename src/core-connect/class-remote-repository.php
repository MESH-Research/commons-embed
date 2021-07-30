<?php
/**
 * Remote_Repository abstract class.
 *
 * @since 0.3.0
 *
 * @package MESHResearch\CommonsConnect
 * @subpackage CoreConnect
 * @author Mike Thicke
 */

namespace MESHResearch\CommonsConnect\CoreConnect;

/**
 * Abstract class for remote repository classes.
 *
 * @since 0.3.0
 */
abstract class Remote_Repository {

	/**
	 * Schema for search results.
	 *
	 * @since 0.3.0
	 *
	 * @var JSON_Schema $results_schema
	 */
	protected ?JSON_Schema $results_schema = null;

	/**
	 * Base URL of the repository.
	 *
	 * @since 0.3.0
	 *
	 * @var string $base_url
	 */
	protected ?string $base_url = null;

	/**
	 * Constructor
	 *
	 * @since 0.3.0
	 *
	 * @param JSON_Schema $results_schema The JSON schema for individual objects returned by the repository.
	 * @param string      $base_url       Base URL for the repository.
	 */
	public function __construct( $results_schema = null, $base_url = null ) {
		$this->set_search_results_schema( $results_schema );

		if ( is_null( $base_url ) ) {
			$cc_options = get_option( CC_PREFIX . 'options' );
			if ( $cc_options ) {
				$global_base_url = $cc_options['base_url'];
			}
			if ( ! $global_base_url ) {
				$global_base_url = HC_BASE_URL;
			}
			$global_base_url = esc_url_raw( $global_base_url );
			$this->set_base_url( $global_base_url );
		} else {
			$this->set_base_url( $base_url );
		}
	}

	/**
	 * Returns search result schema.
	 *
	 * @since 0.3.0
	 *
	 * @return JSON_Schema The JSON schema for individual objects returned by the repository.
	 */
	public function get_search_result_schema() : JSON_Schema {
		return $this->results_schema;
	}

	/**
	 * Sets search result schema.
	 *
	 * @since 0.3.0
	 *
	 * @param JSON_Schema $schema The JSON schema for individual objects returned by the repository.
	 */
	public function set_search_results_schema( JSON_Schema $schema ) {
		$this->results_schema = $schema;
	}

	/**
	 * Sets base url.
	 *
	 * @since 0.3.0
	 *
	 * @param string $base_url The base url of the repository.
	 */
	public function set_base_url( string $base_url ) {
		$this->base_url = $base_url;
	}

	/**
	 * List of fields returned by the remote repository.
	 *
	 * @since 0.3.0
	 *
	 * @return array List of fields.
	 */
	public function get_field_list() : array {
		if ( is_null( $this->results_schema ) ) {
			return [];
		}
		return $this->results_schema->get_schema_property_list();
	}

	/**
	 * Find objects in the repository.
	 *
	 * @since 0.3.0
	 *
	 * @param array $search_parameters Associative array of search parameter keys and values.
	 *
	 * @return array Array of found objects.
	 */
	abstract public function find_objects( $search_parameters ) : ?array;

	/**
	 * Get data for a particular object.
	 *
	 * @since 0.3.0
	 *
	 * @param int|string $object_id The identifier for the object.
	 *
	 * @return array Associative array of object data.
	 */
	abstract public function get_object_data( $object_id ) : ?array;
}
