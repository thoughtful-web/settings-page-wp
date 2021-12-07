<?php
/**
 * The file that extends WP_Error notification capabilities.
 *
 * @package    Thoughtful_Web\Library_WP
 * @subpackage Admin\Page
 * @author     Zachary Kendall Watkins <zwatkins.it@gmail.com>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/class-settings.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace Thoughtful_Web\Library_WP\Admin\Page;

use \Thoughtful_Web\Library_WP\File\Auth_Include as TWL_File_Include;

/**
 * Undocumented class
 */
class Settings {

	/**
	 * Settings page and field Parameters.
	 *
	 * @var array $fieldset The Settings page and fieldset parameters.
	 */
	private $params = array(
		'method'      => 'add_menu_page',
		'method_args' => array(
			'page_title' => 'Settings',
			'menu_title' => 'Settings',
			'capability' => 'manage_options',
			'menu_slug'  => 'twl-admin-settings',
			'function'   => null,
			'icon_url'   => '',
			'position'   => null,
		),
		'heading'     => array(),
		'fieldsets'   => array(
			array(
				'group'  => 'thoughtful-settings-group-1',
				'fields' => array(),
			),
		),
	);

	/**
	 * Settings page fieldset file.
	 *
	 * @var string $fieldset_file The fieldset file to load.
	 */
	private $fieldset_file_path;

	/**
	 * Admin settings class constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param string $params  The settings page parameters file path relative to the root directory.
	 */
	public function __construct( $params = '' ) {

		if ( is_string( $params ) ) {
			$fieldset_file_path = $this->validate_file_path( $params );
			if ( $fieldset_file_path ) {
				$this->params = include $fieldset_file_path;
			} else {
				return false;
			}
		} elseif ( ! empty( $params ) ) {
			$this->params = $params;
		} else {
			return false;
		}

		// Initialize.
		$this->add_hooks();

	}

	/**
	 * Configure class properties.
	 *
	 * @since 0.1.0
	 *
	 * @param string $fieldset_file_path The fieldset file path relative to the root directory.
	 *
	 * @return string|false
	 */
	public function validate_file_path( $file_path = '' ) {

		// Discern the correct path to the file.
		$file_path = realpath( $file_path );

		return file_exists( $file_path ) ? $file_path : false;

	}

	/**
	 * Add action and filter hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function add_hooks() {

		add_action( 'init', array( $this, 'fieldset_init' ) );

	}

	/**
	 * Initialize the fieldset array declaration.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function fieldset_init() {

		$fieldset = $this->validate_fieldset();
		$method   = $this->validate_fieldset_method();
		$hook     = isset( $fieldset['add_menu_page'] ) ? 'add_menu' : 'add_submenu';
		$fields   = $fieldset[ "{$hook}_page" ];

		if ( is_multisite() ) {

			add_action( 'network_admin_menu', array( $this, $hook ) );

			foreach ($variable as $key => $value) {
				# code...
			}
			add_action( 'network_admin_edit_' . $fields['menu_slug'], array( $this, 'save_site_option' ) );

		} else {

			add_action( 'admin_menu', array( $this, $hook ) );
			add_action( 'admin_edit_' . $fieldset[ "{$hook}_page" ], array( $this, 'save_site_option' ) );

		}

		add_action( 'admin_init', array( $this, 'register_settings' ) );

	}

	/**
	 * Validate the fieldset declaration.
	 *
	 * @return array
	 */
	private function validate_fieldset() {

		$valid = false;

		$fieldset = $this->fieldset;

		if ( array_key_exists( 'add_menu_page', $fieldset ) ) {
			$fieldset['add_menu_page']['function'] = array( $this, 'content_admin_menu_page' );
			$valid = true;
		}

		return $fieldset;

	}

	/**
	 * Validate the fieldset declaration.
	 *
	 * @return array
	 */
	private function validate_fieldset_method() {

		$valid = false;

		$fieldset = $this->fieldset;
		$keys     = array_keys( $fieldset );

		if ( array_key_exists( 'add_menu_page', $fieldset ) ) {
			$fieldset['add_menu_page']['function'] = array( $this, 'content_admin_menu_page' );
			$valid = true;
		}

		return $fieldset;

	}
}
