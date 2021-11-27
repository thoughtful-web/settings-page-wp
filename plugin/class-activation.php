<?php
/**
 * The file that handles plugin activation and deactivation with annotated dependency checks.
 *
 * Links to PHP core documentation are included but this file will not be easy to grasp for beginners.
 *
 * @package    ThoughtfulWeb\Library
 * @subpackage Plugin
 * @copyright  Zachary Watkins 2021
 * @author     Zachary Watkins <watkinza@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0-or-later
 * @link       https://github.com/zachwatkins/wordpress-plugin-name/blob/master/util/class-activation.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\Library\Plugin;

use ThoughtfulWeb\Library\Plugin\Requirements as TWLP_Requirements;
use ThoughtfulWeb\Library\Monitor\WP_Err as TWL_Monitor_WP_Err;

/**
 * The class that handles plugin activation and deactivation.
 *
 * @see   https://www.php.net/manual/en/language.oop5.basic.php
 * @since 0.1.0
 */
class Activation {

	/**
	 * Plugin root file.
	 *
	 * @var string $file Placeholder until construction. The path to the root plugin file. A Class
	 *                   variable cannot be defined using functions or variables so it is
	 *                   incomplete until construction.
	 */
	private $file = __DIR__ . '../../../plugin-file.php';

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
	 * Initialize the class
	 *
	 * @todo Add support for an array of plugin clauses.
	 *
	 * @see   https://www.php.net/manual/en/function.version-compare.php
	 * @see   https://developer.wordpress.org/reference/functions/register_activation_hook/
	 * @since 0.1.0
	 *
	 * @param string       $file         The root plugin file.
	 * @param string|array $requirements File path or array of activation requirements. Default empty string.
	 *
	 * @return void
	 */
	public function __construct( $file, $requirements = '' ) {

		$this->file         = $file;
		$this->requirements = $this->get_requirements_array( $requirements );

		$this->get_plugin_data();
		if ( is_array( $requirements ) && array_key_exists( 'plugins', $requirements ) ) {
			$this->plugin_queries = $requirements['plugins'];
		}

		// Register activation hook.
		register_activation_hook( $this->file, array( $this, 'activate_plugin' ) );

	}

	/**
	 * Sanitize and return array of requirements, maybe from a file.
	 *
	 * @since 0.1.0
	 *
	 * @param string|array $requirements File path or array of activation requirements. Default empty string.
	 *
	 * @return array
	 */
	private function get_requirements_array( $requirements ) {

		if ( is_array( $requirements ) ) {
			return $requirements;
		} elseif ( is_string( $requirements ) && ! file_exists( $requirements ) ) {
			return array();
		} else {
			return include $requirements;
		}
	}

	/**
	 * Get plugin data.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function get_plugin_data() {

		$plugin_data = array();

		if ( is_admin() ) {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$plugin_data       = get_plugin_data( $this->file );
			$this->plugin_data = $plugin_data;
		}
	}

	/**
	 * Ensure plugin activation requirements are met and a graceful deactivation if not.
	 *
	 * @since  0.1.0
	 *
	 * @return void
	 */
	public function activate_plugin() {

		$config_or_error = new TWLP_Requirements( $this->requirements );

		// Handle result.
		if ( is_wp_error( $config_or_error ) ) {
			$this->deactivate_plugin( $config_or_error );
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
	public function deactivate_plugin( $wp_error ) {

		// Deactivate the plugin.
		deactivate_plugins( plugin_basename( $this->file ) );

		// Alert the user to the issue.
		new TWL_Monitor_WP_Err( $wp_error, '', '', 'die' );

	}
}
