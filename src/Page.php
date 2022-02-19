<?php
/**
 * The file that extends WP_Error notification capabilities.
 *
 * @package    ThoughtfulWeb\SettingsPageWP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/settings-page-wp/blob/main/src/Page.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\SettingsPageWP;

use \ThoughtfulWeb\SettingsPageWP\Settings\Section;
use \ThoughtfulWeb\SettingsPageWP\Settings\Config;
use \ThoughtfulWeb\SettingsPageWP\Settings\Field;

/**
 * The Admin Settings Page Class.
 *
 * @since 0.1.0
 */
class Page {

	/**
	 * Settings page and field Parameters.
	 *
	 * @var array $config The Settings page and fieldset parameters.
	 */
	private $config = array();

	/**
	 * User capability requirement for accessing the settings page.
	 *
	 * @var string $capability The user capability string.
	 */
	private $capability = 'manage_options';

	/**
	 * Name the group of database options which the fields represent.
	 *
	 * @var string $option_group The database option group name. Lowercase letters and underscores only. If not configured it will default  to the menu_slug method argument with hyphens replaced with underscores.
	 */
	private $option_group = 'options';

	/**
	 * The menu page slug.
	 *
	 * @var string $menu_slug The settings page slug for a URL.
	 */
	private $menu_slug;

	/**
	 * The Settings class page form hook.
	 * 
	 * @var array $settings_form_callable The Settings class callable form.
	 */

	/**
	 * Admin settings class constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param array|string $config (Optional) The settings page configuration parameters.
	 *                                        Either a configuration file name, file path, or array.
	 */
	public function __construct( $config = array() ) {

		// Store attributes from the compiled parameters.
		$config_obj = new \ThoughtfulWeb\SettingsPageWP\Settings\Config( $config );
		$config     = $config_obj->get();

		// Run the Settings API related classes.
		$settings_obj = new \ThoughtfulWeb\SettingsPageWP\Settings( $config );
		$this->settings_form_callable = $settings_obj->settings_form_callable();

		// Assign compiled values.
		$this->config       = $config;
		$this->capability   = $this->config['method_args']['capability'];
		$this->menu_slug    = $this->config['method_args']['menu_slug'];
		$this->option_group = $this->config['option_group'];

		// Initialize.
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );

	}

	/**
	 * Detect if the config has valid stylesheet parameters.
	 *
	 * @return boolean
	 */
	private function has_stylesheet() {

		$has_stylesheet = false;

		// Exit early if the user lacks the capability provided.
		if ( ! current_user_can( $this->capability ) ) {
			return $has_stylesheet;
		}

		if (
			array_key_exists( 'stylesheet', $this->config )
			&& ! empty( $this->config['stylesheet'] )
			&& array_key_exists( 'file', $this->config['stylesheet'] )
		) {
			$has_stylesheet = true;
		}

		return $has_stylesheet;

	}

	/**
	 * Detect if the config has valid script parameters.
	 *
	 * @return boolean
	 */
	private function has_script() {

		$has_script = false;

		// Exit early if the user lacks the capability provided.
		if ( ! current_user_can( $this->capability ) ) {
			return $has_script;
		}

		if (
			array_key_exists( 'script', $this->config )
			&& ! empty( $this->config['script'] )
			&& array_key_exists( 'file', $this->config['script'] )
		) {
			$has_script = true;
		}

		return $has_script;

	}

	/**
	 * Enqueue the Settings page's stylesheet file.
	 *
	 * @return void
	 */
	public function register_stylesheet() {

		$slug        = $this->menu_slug;
		$plugin_root = dirname( __FILE__, 5 );
		$deps        = array_key_exists( 'deps', $this->config['stylesheet'] ) ? $this->config['stylesheet']['deps'] : array();
		$file_url    = plugins_url( basename( $plugin_root ) . $this->config['stylesheet']['file'] );
		$file_path   = $plugin_root . $this->config['stylesheet']['file'];
		$version     = filemtime( $file_path );
		// Register the stylesheet.
		wp_register_style( 'settings-' . $slug, $file_url, $deps, $version );

	}

	/**
	 * Enqueue the Settings page stylesheet.
	 *
	 * @param string $hook_suffix The current admin page.
	 *
	 * @return void
	 */
	public function enqueue_stylesheet( $hook_suffix ) {

		if ( false !== strpos( $hook_suffix, 'page_' . $this->menu_slug ) ) {
			wp_enqueue_style( 'settings-' . $this->menu_slug );
		}

	}

	/**
	 * Enqueue the Settings page's script file.
	 *
	 * @return void
	 */
	public function register_script() {

		$slug        = $this->menu_slug;
		$plugin_root = dirname( __FILE__, 5 );
		$deps        = array_key_exists( 'deps', $this->config['script'] ) ? $this->config['script']['deps'] : array();
		$file_url    = plugins_url( basename( $plugin_root ) . $this->config['script']['file'] );
		$file_path   = $plugin_root . $this->config['script']['file'];
		$version     = filemtime( $file_path );
		$in_footer   = array_key_exists( 'position', $this->config['script'] ) ? boolval( $this->config['script'] ) : false;
		// Register the stylesheet.
		wp_register_script( 'settings-' . $slug, $file_url, $deps, $version, $in_footer );

	}

	/**
	 * Enqueue the Settings page script.
	 *
	 * @param string $hook_suffix The current admin page.
	 *
	 * @return void
	 */
	public function enqueue_script( $hook_suffix ) {

		if ( false !== strpos( $hook_suffix, 'page_' . $this->menu_slug ) ) {
			wp_enqueue_style( 'settings-' . $this->menu_slug );
		}

	}

	/**
	 * Add the settings page to the Admin navigation menu.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function add_settings_page() {

		if (
			! isset( $this->config['method_args']['parent_slug'] )
		) {
			$page = add_menu_page(
				$this->config['method_args']['page_title'],
				$this->config['method_args']['menu_title'],
				$this->capability,
				$this->menu_slug,
				$this->settings_form_callable,
				$this->config['method_args']['icon_url'],
				$this->config['method_args']['position']
			);
		} else {
			$page = add_submenu_page(
				$this->config['method_args']['parent_slug'],
				$this->config['method_args']['page_title'],
				$this->config['method_args']['menu_title'],
				$this->capability,
				$this->menu_slug,
				$this->settings_form_callable,
				$this->config['method_args']['position']
			);
		}

		// Register the stylesheet if present.
		if ( $this->has_stylesheet() ) {
			add_action( 'admin_init', array( $this, 'register_stylesheet' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_stylesheet' ) );
		}
		
		// Register the script if present.
		if ( $this->has_script() ) {
			add_action( 'admin_init', array( $this, 'register_script' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );
		}

	}
}
