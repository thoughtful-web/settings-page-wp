<?php
/**
 * The file that extends WP_Error notification capabilities.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/Admin/Page/Settings.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Admin\Page;

use \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Section;
use \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Config;
/**
 * The Admin Settings Page Class.
 *
 * @since 0.1.0
 */
class Settings {

	/**
	 * Field type to class associations.
	 *
	 * @var array $field_classes The field types and their associated class names under the current fully qualified namespace plus the current class.
	 */
	private $field_classes = array(
		'text' => 'Text_Field',
	);

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
	 * Admin settings class constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param array|string $settings The settings page parameters array or file path relative to the root directory.
	 */
	public function __construct( $settings = array() ) {

		// Store attributes from the compiled parameters.
		$config_obj = new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Config( $settings );

		// Assign compiled values.
		$this->config       = $config_obj->get();
		$this->capability   = $this->config['method_args']['capability'];
		$this->menu_slug    = $this->config['method_args']['menu_slug'];
		$this->option_group = $this->config['option_group'];

		// Initialize.
		if ( isset( $this->config['network'] ) && $this->config['network'] ) {
			add_action( 'network_admin_menu', array( $this, 'add_menu_page' ) );
		} else {
			add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		}

		add_action( 'admin_init', array( $this, 'twlwp_settings_init' ) );

	}

	/**
	 * Add action and filter hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function twlwp_settings_init() {

		$this->add_sections();
		$this->add_fields();

	}

	/**
	 * Add the settings page to the Admin navigation menu.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function add_menu_page() {

		add_menu_page(
			$this->config['method_args']['page_title'],
			$this->config['method_args']['menu_title'],
			$this->config['method_args']['capability'],
			$this->config['method_args']['menu_slug'],
			array( $this, 'menu_page_content' ),
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
	public function menu_page_content() {

		// Check user capabilities.
		if ( ! current_user_can( $this->capability ) ) {
			return;
		}

		// add error/update messages

		// check if the user have submitted the settings
		// WordPress will add the "settings-updated" $_GET parameter to the url
		if ( isset( $_GET['settings-updated'] ) ) {
			// add settings saved message with the class of "updated"
			add_settings_error( "{$this->option_group}_messages", "{$this->option_group}_message", __( 'Settings Saved', 'thoughtful-web-library-wp' ), 'updated' );
		}

		// show error/update messages
		settings_errors( "{$this->option_group}_messages" );

		$method_args = $this->config['method_args'];
		?>
		<div class="wrap">
			<h1><?php $method_args['page_title']; ?></h1>
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php
					settings_fields( $this->option_group );

					do_settings_sections( $this->menu_slug );
					submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php

	}

	/**
	 * Add settings sections.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function add_sections() {

		foreach ( $this->config['sections'] as $id => $section ) {
			new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Section( $id, $section['title'], $section['description'], $this->menu_slug, $this->capability );
		}

	}

	/**
	 * Add each settings field to the page.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function add_fields() {

		$network = $this->config['network'];

        foreach ( $this->config['sections'] as $section ) {

            $section_id = $section['section'];
            $fields     = $section['fields'];

            foreach ( $fields as $field ) {

                switch( $field['type'] ) {
                    case 'text':
                        new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\TextField( $field, $this->menu_slug, $section_id, $this->option_group, $network );
                        break;
                    default:
                        break;
                }

            }

        }

    }
}
