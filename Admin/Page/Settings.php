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
			'page_title' => 'A Thoughtful Settings Page',
			'menu_title' => 'Thoughtful Settings',
			'capability' => 'manage_options',
			'menu_slug'  => 'thoughtful-settings',
			'function'   => null,
			'icon_url'   => 'dashicons-admin-settings',
			'position'   => 2,
		),
		'network'     => false,
		'description' => 'A thoughtful settings page description.',
		'heading'     => array(),
		'fieldsets'   => array(
			array(
				'section' => 'thoughtful-section-1',
				'title'   => '',
				'fields'  => array(
					array(
						'label'       => 'Text Field',
						'id'          => 'my_text_field',
						'type'        => 'text',
						'section'     => 'thoughtful-settings_section',
						'desc'        => 'Description',
						'placeholder' => 'placeholder',
						'label_for'   => null,
						'class'       => null,
					),
				),
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

		if ( isset( $this->params['network'] && true === $this->params['network'] ) {
			add_action( 'network_admin_menu', array( $this, 'add_settings' ) );
		} else {
			add_action( 'admin_menu', array( $this, 'add_settings' ) );
		}
		add_action( 'admin_init', array( $this, 'add_sections' ) );
		add_action( 'admin_init', array( $this, 'add_fields' ) );

		// add_action( 'admin_init', array( $this, 'settings_init' ) );
	}

	public function add_settings() {
		add_menu_page(
			$this->params['method_args']['page_title'],
			$this->params['method_args']['menu_title'],
			$this->params['method_args']['capability'],
			$this->params['method_args']['menu_slug'],
			array( $this, 'add_settings_content' ),
			$this->params['method_args']['icon_url'],
			$this->params['method_args']['position']
		);
	}

	public function add_settings_content() { ?>
		<div class="wrap">
			<h1><?php $this->params['method_args']['page_title']; ?></h1>
			<?php settings_errors(); ?>
			<form method="POST" action="options.php">
				<?php
					$fieldsets = $this->params['fieldsets'];
					foreach ( $fieldsets as $key => $fieldset ) {
						settings_fields( $fieldset['section'] );
					}
					do_settings_sections( $this->params['method_args']['menu_slug'] );
					submit_button();
				?>
			</form>
		</div> <?php
	}

	/**
	 * Add settings sections.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function add_sections() {

		$fieldsets = $this->params['fieldsets'];
		foreach ( $fieldsets as $key => $fieldset ) {
			add_settings_section(
				$fieldset['section'],
				$fieldset['title'],
				array(),
				$this->params['method_args']['menu_slug']
			);
		}
	}

	public function add_fields() {
		$menu_slug = $this->params['method_args']['menu_slug'];
		$fieldsets = $this->params['fieldsets'];
		foreach( $fieldsets as $fieldset ){
			$section = $fieldset['section'];
			$fields  = $fieldset['fields'];
			foreach ( $fields as $field ) {
				add_settings_field(
					$field['id'],
					$field['label'],
					array( $this, 'field_callback' ),
					$menu_slug,
					$section,
					$field
				);
				register_setting( $this->params['method_args']['menu_slug'], $field['id'] );
			}
		}
	}

	public function field_callback( $field ) {
		$value = get_option( $field['id'] );
		$placeholder = '';
		if ( isset( $field['placeholder'] ) ) {
			$placeholder = $field['placeholder'];
		}
		switch ( $field['type'] ) {
			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$placeholder,
					$value
				);
		}
		if( isset($field['desc']) ) {
			if( $desc = $field['desc'] ) {
				printf( '<p class="description">%s </p>', $desc );
			}
		}
	}
}
