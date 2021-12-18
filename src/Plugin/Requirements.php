<?php
/**
 * The file that provides plugin activation requirement features via a configuration file or an array
 * passed to the ThoughtfulWeb\LibraryWP\Plugin_Activation constructor.
 *
 * @package    Thoughtful_Web\Library_WP
 * @subpackage Plugin
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0-or-later
 * @link       https://github.com/zachwatkins/wordpress-plugin-name/blob/master/twlibrary/validate/class-activation-requirements.php
 * @since      0.1.0
 * @todo       https://tommcfarlin.com/registry-pattern-in-wordpress/
 */

declare(strict_types=1);
namespace Thoughtful_Web\Library_WP\Plugin;

/**
 * The class that validates configuration requirements.
 *
 * @see   https://www.php.net/manual/en/language.oop5.basic.php
 * @since 0.1.0
 */
class Requirements {

	/**
	 * Plugin requirements.
	 *
	 * @var array $requirements {
	 *     File path or array of activation requirements. Default empty array.
	 *
	 *     @type array $plugins {
	 *         Optional. Array of plugin clauses. Inspired by the WP_Meta_Query class constructor parameter.
	 *
	 *         @type string $relation Optional. The keyword used to compare the activation status
	 *                                of the plugins. Accepts 'AND', or 'OR'. Default 'AND'.
	 *         @type array  ...$0 {
	 *             An array of a plugin's data.
	 *
	 *             @type string $name Required. Display name of the plugin.
	 *             @type string $path Required. Path to the plugin file relative to the plugins directory.
	 *         }
	 *     }
	 *     @key array $templates {
	 *         Page template data not able to be stored in the file header.
	 *
	 *         @key string $path The relative file path to the page template.
	 *     }
	 * }
	 */
	private $requirements = array();

	/**
	 * Plugin requirements.
	 *
	 * @var array $plugin_queries The separately stored plugin array
	 */
	private $plugin_queries;

	/**
	 * Class constructor function.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Apply plugin requirements from all relevant sources.
	 *
	 * @since 0.1.0
	 *
	 * @param string|array $requirements The plugin requirements file path or array.
	 *
	 * @return array
	 */
	public function query( $requirements ) {

		if ( is_string( $requirements ) ) {
			$requirements = include $requirements;
		}
		$this->requirements = $requirements;
		$query_results      = $this->query_plugins( $requirements['plugins'] );

		return $query_results;

	}

	/**
	 * Validate required plugin[s] in a similar way to WP_Meta_Query().
	 *
	 * If the requirements are not satisfied then deactivate the plugin and
	 * notify the user of the requirements they must meet first through an
	 * admin notice.
	 *
	 * @see https://developer.wordpress.org/reference/classes/wp_meta_query/
	 * @see https://developer.wordpress.org/reference/functions/is_plugin_active/
	 *
	 * @since  0.1.0
	 *
	 * @return array
	 */
	private function query_plugins( $plugin_query ) {

		/**
		 * Results structure for this class's sole public function.
		 *
		 * @var array $default_results The default results provided by this class to those calling the evaluate() function.
		 */
		$results = array(
			'passed'   => true,
			'results'  => array(),
			'active'   => array(),
			'inactive' => array(),
			'notify'   => array(),
			'message'  => false,
		);

		// Enforce a default value of 'AND' for $relation.
		if ( isset( $plugin_query['relation'] ) && 'OR' === strtoupper( $plugin_query['relation'] ) ) {
			$relation = 'OR';
		} else {
			$relation = 'AND';
		}
		if ( isset( $plugin_query['relation'] ) ) {
			unset( $plugin_query['relation'] );
		}

		// Retrieve plugin active status.
		foreach ( $plugin_query as $key => $plugin ) {
			// Get active status.
			$active = is_plugin_active( $plugin['file'] );
			// Store activation status.
			$plugin_query[ $key ]['active'] = $active;
			// Assign active or inactive plugins to their own results.
			if ( $active ) {
				$results['active'] = $plugin;
			} else {
				$results['inactive'] = $plugin;
			}
		}
		$this->plugin_queries = $plugin_query;
		$results['status']    = $plugin_query;

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

		return $results;
	}
}
