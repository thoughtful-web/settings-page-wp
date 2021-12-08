<?php
/**
 * The file that extends WP_Error notification capabilities.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Admin
 * @author     Zachary Kendall Watkins <zwatkins.it@gmail.com>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/zachwatkins/wordpress-plugin-name/blob/master/thoughtfulweb/library/admin/class-acf-fieldset.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Admin;

use ThoughtfulWeb\LibraryWP\File\Auth_Include as TWL_File_Include;

/**
 * Undocumented class
 */
class Menu {

	/**
	 * Settings page fieldset array.
	 *
	 * @var array $fieldset The fieldset for form data to show on the Settings page.
	 */
	private $fieldset = array();

	private $view_404 = '../../config/admin/menu/404.php';

	/**
	 * Admin menu array.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_menu_page/
	 * @link https://developer.wordpress.org/reference/functions/add_submenu_page/
	 *
	 * @var array $menus The menu arguments array for registering menus and submenus.
	 */
	private $menus = array(
		'hook' => 'admin_menu',
		array(
			'page_title' => 'My Settings',
			'menu_title' => 'My Settings',
			'capability' => 'manage_options',
			'menu_slug'  => 'my-settings',
			'icon_url'   => 'dashicons-admin-settings',
			'position'   => 2,
		),
		array(
			array(
				'parent_slug' => 'my-settings',
				'page_title'  => 'My Settings',
				'menu_title'  => 'My Settings',
				'capability'  => 'manage_options',
				'menu_slug'   => 'my-submenu-1',
				'position'    => 0,
			),
			array(
				'parent_slug' => 'my-settings',
				'page_title'  => 'My Settings',
				'menu_title'  => 'My Settings',
				'capability'  => 'manage_options',
				'menu_slug'   => 'my-submenu-2',
				'position'    => 1,
			),
		),
	);

	/**
	 * Admin menu hook.
	 *
	 * @var string $hook The admin menu hook for all of the items.
	 */
	private $hook = 'admin_menu';

	/**
	 * Admin settings class constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param string $menu_file     The menu file path relative to the root directory.
	 * @param string $fieldset_file The fieldset file path relative to the root directory.
	 */
	public function __construct( $menu_file = '', $fieldset_file = '' ) {

		if ( ! $menu_file ) {
			return;
		}

		$menus      = include $menu_file;
		$this->hook = $menus['hook'];
		unset( $menus['hook'] );

		if ( file_exists( $fieldset_file ) ) {
			$this->fieldset = include $fieldset_file;
			$this->configure_fieldset();
		}

		// Initialize loading the file.
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

		$hook     = isset( $fieldset['add_menu_page'] ) ? 'add_menu_page' : 'add_submenu_page';
		$fields   = $fieldset[ "{$hook}_page" ];

		if ( is_multisite() ) {

			add_action( 'network_admin_edit_' . $fields['menu_slug'], array( $this, 'save_site_option' ) );
			add_action( 'network_admin_menu', array( $this, $hook ) );

		} else {

			add_action( 'admin_menu', array( $this, $hook ) );
			add_action( 'admin_edit_' . $fieldset[ "{$hook}_page" ], array( $this, 'save_site_option' ) );

		}

		add_action( 'admin_init', array( $this, 'register_settings' ) );

	}

	/**
	 * Configure the menu array.
	 *
	 * @return void
	 */
	private function register_menus() {

		// Assign the top-level array members of the $menus array as Menu pages.
		// Assign deeper nested arrays as Submenu pages.
		$menus    = array();
		$submenus = array();

		foreach ( $this->menus as $key => $value ) {
			$first_key = array_key_first( $value );
			if ( ! is_array( $value[ $first_key ] ) ) {
				$menus[] = $value;
			} else {
				foreach ( $value as $submenu_args ) {
					$submenus[] = $submenu_args;
				}
			}
		}

		foreach ( $menus as $menu ) {
			add_menu_page( $menu['page_title'], $menu['menu_title'], $menu['capability'], $menu['menu_slug'], '', $menu['icon_url'], $menu['position'] );
		}

		foreach ( $submenus as $submenu ) {
			add_submenu_page( $menu['parent_slug'], $menu['page_title'], $menu['menu_title'], $menu['capability'], $menu['menu_slug'], '', $menu['position'] );
		}
	}

	/**
	 * Validate the fieldset declaration.
	 *
	 * @return array
	 */
	private function configure_fieldset() {

		if ( array_key_exists( 'add_menu_page', $fieldset ) ) {
			$fieldset['add_menu_page']['function'] = '';
		} elseif ( array_key_exists( 'add_submenu_page', $fieldset ) ) {
			$fieldset['add_submenu_page']['function'] = '';
		}

		return $fieldset;

	}

	/**
	 * Add menu page.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function add_menu_page() {

		$args = $this->fieldset['add_menu_page']['args'];

		add_menu_page(
			$args['page_title'],
			$args['menu_title'],
			$args['capability'],
			$args['menu_slug'],
			$args['function'],
			$args['icon_url'],
			$args['position']
		);

	}

	/**
	 * Add submenu page.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function add_submenu_page() {

		$args = $this->fieldset['add_submenu_page']['args'];

		add_submenu_page(
			$args['page_title'],
			$args['menu_title'],
			$args['capability'],
			$args['menu_slug'],
			$args['function'],
			$args['icon_url'],
			$args['position']
		);

	}
}
