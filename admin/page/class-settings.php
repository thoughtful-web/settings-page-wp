<?php
/**
 * The file that extends WP_Error notification capabilities.
 *
 * @package    ThoughtfulWeb\Library
 * @subpackage Admin
 * @author     Zachary Kendall Watkins <zwatkins.it@gmail.com>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/zachwatkins/wordpress-plugin-name/blob/master/thoughtfulweb/library/admin/class-acf-fieldset.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\Library\Admin\Page;

use ThoughtfulWeb\Library\File\Auth_Include as TWL_File_Include;

/**
 * Undocumented class
 */
class Settings {

	/**
	 * Settings page fieldset file.
	 *
	 * @var string $fieldset_file The fieldset file to load.
	 */
	private $fieldset_file = __DIR__ . '../../../../fields/admin-page-settings.php';

	/**
	 * Settings page fieldset array.
	 *
	 * @var array $fieldset THe fieldset for form data to show on the Settings page.
	 */
	private $fieldset = array(
		'add_menu_page' => array(
			'args'   => array(
				'page_title' => 'Settings',
				'menu_title' => 'Settings',
				'capability' => 'manage_options',
				'menu_slug'  => 'twl-admin-settings',
				'function'   => null,
				'icon_url'   => '',
				'position'   => null,
			),
			'fields' => array(
				array(
					'group_slug' => 'twl-admin-group-1',
				),
			),
		),
	);

	/**
	 * Admin settings class constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param string $fieldset_file The fieldset file path relative to the root directory.
	 * @param string $basedir       The root directory path.
	 */
	public function __construct( $fieldset_file = '', $basedir = '' ) {

		$fieldset_file = $this->validate_file_path( $fieldset_file, $basedir );

		if ( ! $fieldset_file ) {
			return;
		}

		// Initialize loading the file.
		$this->fieldset = include $fieldset_file;
		$this->add_hooks();

	}

	/**
	 * Configure class properties.
	 *
	 * @since 0.1.0
	 *
	 * @param string $fieldset_file The fieldset file path relative to the root directory.
	 * @param string $basedir       The root directory path.
	 *
	 * @return string|false
	 */
	public function validate_file_path( $fieldset_file = '', $basedir = '' ) {

		if ( empty( $fieldset_file ) || ! is_string( $fieldset_file ) ) {
			return '';
		}

		// Discern the correct path to the Settings fieldset file.
		$path = $fieldset_file;
		if ( ! file_exists( $path ) ) {
			if ( 0 === strpos( $path, './' ) ) {
				$path = ltrim( $path, '.' );
			}
			$path = "$basedir$path";
		}

		return file_exists( $path ) ? $path : false;

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
