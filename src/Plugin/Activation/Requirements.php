<?php
/**
 * The file that handles plugin activation and deactivation with annotated dependency checks.
 *
 * Links to PHP core documentation are included but this file will not be easy to grasp for beginners.
 *
 * @package    Thoughtful_Web\Library_WP
 * @subpackage Plugin
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0-or-later
 * @link       https://github.com/zachwatkins/wordpress-plugin-name/blob/master/util/class-activation.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace Thoughtful_Web\Library_WP\Plugin\Activation;

use \Thoughtful_Web\Library_WP\Plugin\Query as TWLP_Plugin_Query;

/**
 * The class that handles plugin activation and deactivation.
 *
 * @see   https://www.php.net/manual/en/language.oop5.basic.php
 * @since 0.1.0
 */
class Requirements {

	/**
	 * The main plugin file.
	 *
	 * @var string $root_plugin_file
	 */
	private $root_plugin_file;

	/**
	 * The plugin query clause.
	 *
	 * @var array $plugin_query
	 */
	private $plugin_query;

	/**
	 * Initialize the class
	 *
	 * @todo Add support for an array of plugin clauses.
	 *
	 * @see   https://www.php.net/manual/en/function.version-compare.php
	 * @see   https://developer.wordpress.org/reference/functions/register_activation_hook/
	 * @since 0.1.0
	 *
	 * @param string       $root_plugin_file     The main plugin file in the root directory of the plugin folder.
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
	 * @return void
	 */
	public function __construct( $root_plugin_file, $plugin_clause ) {

		$this->root_plugin_file = $root_plugin_file;
		$this->plugin_clause    = $plugin_clause;

		// Register activation hook.
		register_activation_hook( $root_plugin_file, array( $this, 'activate_plugin' ) );

	}

	/**
	 * Ensure plugin activation requirements are met and a graceful deactivation if not.
	 *
	 * @since  0.1.0
	 *
	 * @return void
	 */
	public function activate_plugin() {

		$plugin_query         = new TWLP_Plugin_Query( $this->plugin_clause );
		$plugin_query_results = $plugin_query->results();

		// Handle result.
		if ( ! $plugin_query_results['passed'] ) {
			$this->deactivate_plugin( $plugin_query_results );
		}

	}

	/**
	 * Deactivate the plugin.
	 *
	 * @see    https://developer.wordpress.org/reference/functions/deactivate_plugins/
	 * @see    https://developer.wordpress.org/reference/functions/plugin_basename/
	 * @see    https://developer.wordpress.org/reference/hooks/wp_die/
	 * @since  0.1.0
	 *
	 * @param  WP_Error $wp_error The WP_Error object.
	 *
	 * @return void
	 */
	public function deactivate_plugin( $plugin_query_results ) {

		// Deactivate the plugin.
		deactivate_plugins( plugin_basename( $this->root_plugin_file ) );

		// Alert the user to the issue.

	}
}
