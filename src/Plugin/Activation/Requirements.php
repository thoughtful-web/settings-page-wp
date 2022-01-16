<?php
/**
 * The file that handles plugin activation and deactivation with annotated dependency checks.
 *
 * Links to PHP core documentation are included but this file will not be easy to grasp for beginners.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Plugin
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0-or-later
 * @link       https://github.com/zachwatkins/wordpress-plugin-name/blob/master/util/class-activation.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Plugin\Activation;

use \ThoughtfulWeb\LibraryWP\Plugin\Query;

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
	 * @var string $root_plugin_path
	 */
	private $root_plugin_path;

	/**
	 * The plugin query clause.
	 *
	 * @var array $plugin_clause
	 */
	private $plugin_clause;

	/**
	 * The plugin query results.
	 *
	 * @var array $plugin_query_results
	 */
	private $plugin_query_results;

	/**
	 * Initialize the class
	 *
	 * @todo Add support for an array of plugin clauses.
	 *
	 * @see   https://www.php.net/manual/en/function.version-compare.php
	 * @see   https://developer.wordpress.org/reference/functions/register_activation_hook/
	 * @since 0.1.0
	 *
	 * @param string       $root_plugin_path     The main plugin file in the root directory of the plugin folder.
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
	public function __construct( $root_plugin_path, $plugin_clause ) {

		$this->root_plugin_path = $root_plugin_path;
		$this->plugin_clause    = $plugin_clause;

		// Register activation hook.
		register_activation_hook( $root_plugin_path, array( $this, 'activate_plugin' ) );

	}

	/**
	 * Ensure plugin activation requirements are met and a graceful deactivation if not.
	 *
	 * @since  0.1.0
	 *
	 * @return void
	 */
	public function activate_plugin() {

		$plugin_query               =( $this->plugin_clause );
		$this->plugin_query_results = $plugin_query->results();

		// Handle result.
		if ( ! $this->plugin_query_results['passed'] ) {

			$this->deactivate_plugin();

			// Alert the user to the issue.
			add_action( 'admin_notices', array( $this, 'show_admin_error' ) );

		}

	}

	/**
	 * Deactivate the plugin.
	 *
	 * @see    https://developer.wordpress.org/reference/functions/deactivate_plugins/
	 * @see    https://developer.wordpress.org/reference/functions/plugin_basename/
	 * @see    https://developer.wordpress.org/reference/functions/is_plugin_active_for_network/
	 * @since  0.1.0
	 *
	 * @return void
	 */
	public function deactivate_plugin() {

		// Deactivate the plugin in a multisite-friendly way.
		if ( ! is_multisite() ) {
			deactivate_plugins( $this->root_plugin_path );
		} elseif ( is_network_admin() && is_plugin_active_for_network( $this->root_plugin_path ) ) {
			deactivate_plugins( $this->root_plugin_path, false, true );
		} else {
			deactivate_plugins( $this->root_plugin_path, false, false );
		}

	}

	/**
	 * Show admin notice
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function show_admin_error() {

		$class         = 'notice notice-error is-dismissible';
		$message_pre   =
		$message_str   = sprintf(
			/* translators: %s: Name or names of the plugins which did not meet requirements. */
			__( 'The plugin could not be activated. Install and activate the %s plugin(s) first and then activate this plugin again.', 'thoughtful-web-library-wp' ),
			esc_html( $this->plugin_query_results['message'] )
		);

		// Display the notice element.
		$message_output = sprintf(
			/* translators: 1: The notice element class 2: The full notice message */
			'<div class="%1$s"><p>%2$s</p></div>',
			esc_attr( $class ),
			esc_html( $message_str )
		);

		$message_output = apply_filters( 'twpl_activation_requirement_error', $message_output );

		echo wp_kses_post( $message_output );

	}
}
