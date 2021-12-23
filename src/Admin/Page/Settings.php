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

		$method_args = $this->config['method_args'];
		if ( current_user_can( $this->capability ) ) {
			?>
			<div class="wrap">
				<h1><?php $method_args['page_title']; ?></h1>
				<?php settings_errors(); ?>
				<form method="POST" action="edit.php?action=<?php echo $this->option_group; ?>">
					<?php
						settings_fields( $this->option_group );

						do_settings_sections( $this->menu_slug );
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
	public function add_fields() {

        foreach ( $this->config['sections'] as $section ) {

            $section_id = $section['section'];
            $fields     = $section['fields'];

            foreach ( $fields as $field ) {

                switch( $field ) {
                    case 'text':
                        new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\TextField( $field, $this->menu_slug, $section_id, $this->option_group );
                        break;
                    default:
                        break;
                }

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
