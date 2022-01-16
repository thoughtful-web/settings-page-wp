<?php
/**
 * The file that provides plugin activation status detection via a configuration file or an array
 * passed to the constructor.
 *
 * Inspired by the meta_query parameter of WP_Meta_Query().
 * https://developer.wordpress.org/reference/classes/wp_meta_query/
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Plugin
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/src/plugin/query.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Plugin;

/**
 * The class that validates configuration requirements.
 *
 * @see   https://www.php.net/manual/en/language.oop5.basic.php
 * @since 0.1.0
 */
class Query {

	/**
	 * Query results.
	 *
	 * @var array $results The plugin query results.
	 */
	private $query_results = array(
		'passed'   => true,
		'results'  => array(),
		'active'   => array(),
		'inactive' => array(),
		'notify'   => array(),
		'message'  => '',
	);

	/**
	 * Class constructor.
	 *
	 * @since  0.1.0
	 *
	 * @param array|string $plugin_clause {
	 *     The details for plugins which may or may not be present and/or active on the site.
	 *
	 *     @type string $relation Optional. The keyword used to compare the activation status of the
	 *                            plugins. Accepts 'AND' or 'OR'. Default 'AND'.
	 *     @type array  ...$0 {
	 *         An array of a plugin's data.
	 *
	 *         @type string $name Required. Display name of the plugin.
	 *         @type string $path Required. Path to the plugin file relative to the plugins
	 *                            directory.
	 *     }
	 * }
	 *
	 * @return array
	 */
	public function __construct( $plugin_clause ) {

		if ( is_string( $plugin_clause ) ) {
			$plugin_clause = include $plugin_clause;
		}

		// Results structure for this class's sole public function.
		$results = $this->query_results;

		// Enforce a default value of 'AND' for $relation.
		if ( isset( $plugin_clause['relation'] ) && 'OR' === strtoupper( $plugin_clause['relation'] ) ) {
			$relation = 'OR';
		} else {
			$relation = 'AND';
		}
		if ( isset( $plugin_clause['relation'] ) ) {
			unset( $plugin_clause['relation'] );
		}

		// Retrieve plugin active status.
		foreach ( $plugin_clause as $key => $plugin ) {
			// Get active status.
			$active = is_plugin_active( $plugin['file'] );
			// Store activation status.
			$plugin_clause[ $key ]['active'] = $active;
			// Assign active or inactive plugins to their own results.
			if ( $active ) {
				$results['active'] = $plugin;
			} else {
				$results['inactive'] = $plugin;
			}
		}
		$results['status'] = $plugin_clause;

		// Determine if the currently active and inactive plugins meet the requirements configuration.
		if ( 'AND' === $relation ) {
			if ( empty( $results['inactive'] ) ) {
				$results['passed'] = true;
			} else {
				$results['passed'] = false;
			}
		} else {
			if ( 1 === count( $results['active'] ) ) {
				$results['passed'] = true;
			} else {
				$results['passed'] = false;
			}
		}

		// Determine which plugins to report failure for.
		if ( 'AND' === $relation ) {

			$results['notify'] = $results['inactive'];

		} elseif ( 'OR' === $relation ) {

			if ( 1 < count( $results['active'] ) ) {

				$results['notify'] = $results['active'];

			} elseif ( 0 === count( $results['active'] ) ) {

				$results['notify'] = $results['inactive'];

			}

		}

		/**
		 * Assemble all inactive plugins as a phrase using the relation parameter.
		 * Example 1: "Advanced Custom Fields"
		 * Example 2: "Advanced Custom Fields or Advanced Custom Fields Pro"
		 * Example 3: "Advanced Custom Fields and Admin Columns"
		 * Example 4: "Advanced Custom Fields, Admin Columns, and Gravity Forms"
		 */
		if ( 0 < count( $results['notify'] ) ) {

			$notify_plugins_phrase   = '';
			$plural                  = 'OR' === $relation ? 1 : count( $results['notify'] );

			if ( 2 >= $plural ) {
				$notify_plugins_phrase = implode( strtolower( " $relation " ), $results['inactive'] );
			} else {
				$plugin_last            = array_pop( $results['notify'] );
				$notify_plugins_phrase  = implode( ', ', $results['notify'] );
				$notify_plugins_phrase .= strtolower( ", $relation " ) . $plugin_last;
			}

			$results['message'] = $notify_plugins_phrase;

		}

		$this->query_results = $results;
	}

	/**
	 * Return the query results.
	 *
	 * @return array
	 */
	public function results() {

		return $this->query_results;

	}
}
