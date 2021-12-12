<?php
/**
 * The file that extends WP_Error notification capabilities.
 *
 * @package    Thoughtful_Web\Library_WP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/Admin/Page/Settings.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace Thoughtful_Web\Library_WP\Admin\Page;

use \Thoughtful_Web\Library_WP\Admin\Page\Settings\Field as TWPL_Settings_Field;
use \Thoughtful_Web\Library_WP\Admin\Page\Settings\Config as TWPL_Settings_Config;

/**
 * The Admin Settings Page Class.
 *
 * @since 0.1.0
 */
class Settings {

	/**
	 * Default parameters.
	 *
	 * @var array defaults The default values for the settings page registration parameters.
	 */
	private $defaults = array(
		'method'      => 'add_menu_page',
		'method_args' => array(
			'page_title' => 'A Thoughtful Settings Page',
			'menu_title' => 'Thoughtful Settings',
			'capability' => 'manage_options',
			'menu_slug'  => 'thoughtful-settings',
			'function'   => null,
			'icon_url'   => 'dashicons-admin-settings',
			'position'   => 2,
		),
		'description' => 'A thoughtful settings page description.',
		'network'     => false,
	);

	/**
	 * Settings page and field Parameters.
	 *
	 * @var array $fieldset The Settings page and fieldset parameters.
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
	private $option_group = 'my_option';

	/**
	 * Admin settings class constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param array|string $settings The settings page parameters array or file path relative to the root directory.
	 */
	public function __construct( $settings = array() ) {

		// Store attributes from the compiled parameters.
		$configurer = new TWPL_Settings_Config();
		$compiled   = $configurer->compile( $settings, $this->defaults );

		// Assign compiled values.
		$this->config       = $compiled;
		$this->capability   = $compiled['method_args']['capability'];
		$this->option_group = $compiled['option_group'];

		// Initialize.
		$this->add_hooks();

	}

	/**
	 * Add action and filter hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function add_hooks() {

		if ( isset( $this->config['network'] ) && $this->config['network'] ) {
			add_action( 'network_admin_menu', array( $this, 'add_settings' ) );
			add_action( 'network_admin_edit_' . $this->option_group, array( $this, 'save_site_option' ) );
		} else {
			add_action( 'admin_menu', array( $this, 'add_settings' ) );
			add_action( 'admin_edit_' . $this->option_group, array( $this, 'save_site_option' ) );
		}
		add_action( 'admin_init', array( $this, 'add_sections' ) );
		add_action( 'admin_init', array( $this, 'add_fields' ) );

		// add_action( 'admin_init', array( $this, 'settings_init' ) );
	}

	/**
	 * Add the settings page to the Admin navigation menu.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function add_settings() {

		add_menu_page(
			$this->config['method_args']['page_title'],
			$this->config['method_args']['menu_title'],
			$this->config['method_args']['capability'],
			$this->config['method_args']['menu_slug'],
			array( $this, 'add_settings_content' ),
			$this->config['method_args']['icon_url'],
			$this->config['method_args']['position']
		);

	}

	/**
	 * Add content to the Admin settings page.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function add_settings_content() {

		$method_args = $this->config['method_args'];
		if ( current_user_can( $method_args['capability'] ) ) {
			?>
			<div class="wrap">
				<h1><?php $method_args['page_title']; ?></h1>
				<?php settings_errors(); ?>
				<form method="POST" action="edit.php?action=<?php echo $this->option_group; ?>">
					<?php
						foreach ( $this->config['fieldsets'] as $fieldset ) {
							settings_fields( $fieldset['section'] );
						}
						do_settings_sections( $method_args['menu_slug'] );
						submit_button();
					?>
				</form>
			</div>
			<?php
		}

	}

	/**
	 * Add settings sections.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function add_sections() {

		$menu_slug = $this->config['method_args']['menu_slug'];

		foreach ( $this->config['fieldsets'] as $section_key => $fieldset ) {
			add_settings_section(
				$section_key,
				$fieldset['title'],
				array( $this, 'add_section_description' ),
				$menu_slug
			);
		}

	}

	/**
	 * Add a section description.
	 *
	 * @since 0.1.0
	 *
	 * @param array $args {
	 *     The section arguments.
	 *
	 *     @key string $id       The section ID.
	 *     @key string $title    The section title.
	 *     @key string $callback This function's name.
	 * }
	 *
	 * @return void
	 */
	public function add_section_description( $args ) {

		if ( ! current_user_can( $this->capability ) ) {
			return;
		}

		$section_desc = $this->config['fieldsets'][ $args['id'] ]['description'];

		if ( empty( $section_desc ) ) {
			return;
		}

		echo wp_kses_post( $section_desc );

	}

	/**
	 * Add each settings field to the page.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function add_fields() {

		$page = $this->config['method_args']['menu_slug'];

		foreach( $this->config['fieldsets'] as $fieldset ){

			$section   = $fieldset['section'];
			$fields    = $fieldset['fields'];

			foreach ( $fields as $args ) {

				new TWPL_Settings_Field( $args, $page, $section, $this->option_group );

			}

		}

	}

	public function save_site_option() {

		// Verify nonce.
		wp_verify_nonce( $_POST['_wpnonce'], 'update' );

		// Save the option.
		$option = $_POST[ $this->option_key ];
		update_site_option( $this->option_key, $option );

		// Redirect to settings page.
		wp_redirect(
			add_query_arg(
				array(
					'page'    => $this->config['method_args']['menu_slug'],
					'updated' => 'true',
				),
				( is_multisite() ? network_admin_url( 'admin.php' ) : admin_url( 'admin.php' ) )
			)
		);
		exit;

	}
}
