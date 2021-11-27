<?php
/**
 * The file that extends WP_Error notification capabilities.
 *
 * @package    ThoughtfulWeb\Library
 * @subpackage Admin
 * @copyright  Zachary Kendall Watkins 2021
 * @author     Zachary Kendall Watkins <watkinza@gmail.com> (https://github.com/zachwatkins/)
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0-or-later
 * @link       https://github.com/zachwatkins/wordpress-plugin-name/blob/master/admin/class-settings-page.php
 * @since      0.1.0
 */

namespace HomeRanch\Src;

use ThoughtfulWeb\Library\Admin\Settings_Page as TWL_Settings_Page;
use ThoughtfulWeb\Library\Admin\ACF_Fieldset as TWL_ACF_Fieldset;

class Admin_Page {

	/**
	 * Root directory of the plugin or theme.
     * This will be corrected at runtime but due to potential misuse
     * of this class we must declare something here. That is why we
     * declare a relative path pointing to the parent directory of
     * the library.
	 *
	 * @var string $basedir The root directory of the project.
	 */
	private $basedir = __DIR__ . '../../../';

    /**
     * Add menu function arguments.
     *
     * @var array $menu_args The default menu arguments.
     */
    private $menu_args = array(
        'position'   => 0,
        'capability' => 'manage_options',
        'menu_title' => 'H&R Settings',
        'menu_slug'  => 'hr-settings',
        'page_title' => 'Home & Ranch Theme Settings',
        'icon_url'   => 'dashicons-admin-generic',
    );

    /**
     * Advanced Custom Fields file.
     *
     * @var string $acf_file The Advanced Custom Fields file to load.
     */
    private $acf_file = './fields/settings.php';

	/**
	 * Admin settings class constructor.
	 *
	 * @param array $args The constructor arguments.
	 */
	public function __construct( $menu_args = array() ) {

        // Update path-related class properties.
        $this->basedir  = realpath( $this->basedir );
        $this->acf_file = $this->basedir . preg_replace( '/^\.\//', '/', $this->acf_file );
        error_log( $this->acf_file );

        /**
         * Load the Thoughtful Web Library Settings Page.
         */
        new TWL_Settings_Page( $this->menu_args );

        /**
         * Load the Advanced Custom Fields file.
         *
         * @param string $
         */
		new TWL_ACF_Fieldset( $this->acf_file, 'admin_notice' );

        if ( is_admin() ) {

            add_action( 'acf/init', array( $this, 'acf_init' ) );

			$this->default_option = $default_site_options[ $this->option_key ];

            if ( is_multisite() ) {

                // Advanced Custom Fields cannot add a network-level admin menu.
                add_action( 'network_admin_menu', array( $this, 'add_menu' ) );
                add_action( 'network_admin_edit_' . $this->settings_group_slug, array( $this, 'save_site_option' ) );

            } else {

                // Todo: Confirm single-site support.
                add_action( 'admin_menu', array( $this, 'add_menu' ) );
                add_action( 'admin_edit_' . $this->settings_group_slug, array( $this, 'save_site_option' ) );

            }

            add_action( 'admin_init', array( $this, 'register_settings' ) );
		}


	}

    /**
     * ACF initialization action hook.
     *
     * @return void
     */
    public function acf_init() {

        if ( file_exists( $this->acf_file ) ) {
            require $this->acf_file;
        } else {
            array_push(
                $this->admin_notices,
                array(
                    'hr_file_error',
                    array(
                        'line'     => intval(__LINE__) - 8, // Pointer to X lines above.
                        'message'  => $error_conf['message'],
                        'acf_file' => $this->acf_file,
                        'class'    => get_class($this),
                        'file'     => $file,
                    ),
                )
            );

            $this->configure_admin_notice( 'hr_acf_error', $this->admin );
            add_action('admin_notices', array( $this, 'error_admin_notice' ) );
        }

    }

    /**
     * Set class properties so the admin notice has the data it needs.
     *
     * @param array $args The array of configuration arguments.
     *
     * @return void
     */
    public function configure_admin_notice( $code, $args ) {

        $error_code = 'hr_acf_error';
        $error_conf = $this->error_config( $error_code );
        $error_msg  = sprintf(
            $error_conf['message'],
            $this->acf_file,
            get_class($this),
            __FILE__,
            $error_line
        );
        $this->acf_file = new \WP_Error(
            'hr_acf_error',
            $error_msg
        );

    }

	public function add_menu() {

		$permission = is_multisite() ? 'manage_network_options' : 'manage_options';
	  add_submenu_page(
       $this->submenu_parent_slug,
       'Sandbox Settings',
       'Sandbox',
       $permission,
       $this->page_slug,
       array( $this, 'create_admin_page' )
	  );
	}

	/**
	 * Options page callback
	 *
	 * @return void
	 */
	public function create_admin_page() {

		?>
	<div class="wrap">
	  <h1>Network Sandbox Settings</h1>
	  <form method="post" action="edit.php?action=<?php echo $this->settings_group_slug; ?>">
		<?php
		// This prints out all hidden setting fields.
		settings_fields( $this->settings_group_slug );
		do_settings_sections( $this->page_slug );
		submit_button();
		?>
	  </form>
	</div>
		<?php

	}

	/**
	 * Initialize the admin settings.
	 *
	 * @return void
	 */
	public function register_settings() {

		/**
		 * Register the Policy fields.
		 */
		register_setting(
			$this->settings_group_slug,
			$this->option_key,
			array( $this, 'sanitize_option' )
		);

		add_settings_section(
			$this->settings_group_slug . '_setting_section',
			'',
			array( $this, 'print_section_info' ),
			$this->page_slug
		);

		add_settings_field(
			'sandbox_show_link',
			'Show Sandbox Link',
			array( $this, 'checkbox_field' ),
			$this->page_slug,
			$this->settings_group_slug . '_setting_section',
			array(
				'option_name' => $this->option_key,
				'field_name'  => 'sandbox_show_link',
			)
		);

		add_settings_field(
			'sandbox_link_text',
			'Sandbox Link Text',
			array( $this, 'text_field' ),
			$this->page_slug,
			$this->settings_group_slug . '_setting_section',
			array(
				'option_name' => $this->option_key,
				'field_name'  => 'sandbox_link_text',
			)
		);

		add_settings_field(
			'sandbox_url',
			'Sandbox URL',
			array( $this, 'text_field' ),
			$this->page_slug,
			$this->settings_group_slug . '_setting_section',
			array(
				'option_name' => $this->option_key,
				'field_name'  => 'sandbox_url',
			)
		);

		add_settings_field(
			'live_show_link',
			'Show Live Link',
			array( $this, 'checkbox_field' ),
			$this->page_slug,
			$this->settings_group_slug . '_setting_section',
			array(
				'option_name' => $this->option_key,
				'field_name'  => 'live_show_link',
			)
		);

		add_settings_field(
			'live_link_text',
			'Live Link Text',
			array( $this, 'text_field' ),
			$this->page_slug,
			$this->settings_group_slug . '_setting_section',
			array(
				'option_name' => $this->option_key,
				'field_name'  => 'live_link_text',
			)
		);

		add_settings_field(
			'live_url',
			'Live URL',
			array( $this, 'text_field' ),
			$this->page_slug,
			$this->settings_group_slug . '_setting_section',
			array(
				'option_name' => $this->option_key,
				'field_name'  => 'live_url',
			)
		);

	}

	/**
	 * Print the Section text
	 */
	public function print_section_info() {

		print 'The network sandbox is a place where features, fixes, and changes to the network and its sites can be tested before introducing them into the production environment.';

	}
}
