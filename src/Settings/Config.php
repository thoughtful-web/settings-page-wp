<?php
/**
 * The file that extends WP_Error notification capabilities.
 *
 * @package    ThoughtfulWeb\SettingsPageWP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/settings-page-wp/blob/main/src/Settings/Config.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\SettingsPageWP\Settings;

/**
 * The Settings Page Parameter Compiler Class.
 *
 * @since 0.1.0
 */
class Config {

	/**
	 * Default parameters.
	 *
	 * @var array defaults The default values for the settings page registration parameters.
	 */
	private $defaults = array(
		'method_args'  => array(
			'page_title' => 'A Thoughtful Settings Page',
			'menu_title' => 'Thoughtful Settings',
			'capability' => 'manage_options',
			'menu_slug'  => 'thoughtful-settings',
			'icon_url'   => 'dashicons-admin-settings',
			'position'   => null,
		),
		'option_group' => 'options',
		'description'  => '',
	);

	/**
	 * The configuration associative array.
	 *
	 * @var array $config The associative array storing the final configuration state.
	 */
	private $config;

	/**
	 * Constructor for the Compile class.
	 *
	 * @param array $config The Settings page configuration parameters. Either a configuration file or an array.
	 *
	 * @return void
	 */
	public function __construct( $config = array() ) {

		$config = $this->maybe_autoload_file( $config );

		$this->config = $this->preprocess( $config );

	}

	/**
	 * Detect and load a config file if given an empty config variable.
	 *
	 * @param array $config The Settings page configuration parameters.
	 *
	 * @return array
	 */
	public function maybe_autoload_file( $config ) {

		$try_loading_file = empty( $config ) || is_string( $config ) ? true : false;
		if ( $try_loading_file ) {
			// Legacy support for previous required path.
			$paths_from_subfolder = array(
				dirname( __FILE__, 6 ) . '/config/thoughtful-web/',
				dirname( __FILE__, 6 ) . '/config/thoughtful-web/settings/',
			);
			foreach ( $paths_from_subfolder as $path_from_subfolder ) {
				$is_json = false;
				if ( is_string( $config ) && ! empty( $config ) && preg_match( '/(\.php|\.json)$/', $config ) ) {
					// Load a file from the path provided by the user.
					$is_json = preg_match( '/\.json$/', $config );
					// If only a file name is provided, it must be in the config directory.
					// If a file path is provided, it must be a complete file path.
					$file_path_pre = preg_match( '/\//', $config ) ? '' : $path_from_subfolder;
				} elseif ( empty( $config ) ) {
					// If no parameter is provided then assume the file name is just "settings.json|php".
					$file_path_pre = $path_from_subfolder;
					$is_json       = file_exists( "{$path_from_subfolder}settings.json" );
					$filename      = $is_json ? 'settings.json' : 'settings.php';
				}
				// Check for JSON, then PHP.
				$file_path = "{$file_path_pre}{$filename}";
				if ( file_exists( $file_path ) ) {
					if ( $is_json ) {
						$str    = file_get_contents( $file_path );
						$config = json_decode( $str, true );
					} else {
						$config = include $file_path;
					}
					break;
				}
			}
		}

		return $config;

	}

	/**
	 * Get the compiler results.
	 *
	 * @param array $config The Settings page configuration parameters.
	 *
	 * @return void
	 */
	private function preprocess( $config ) {

		// Apply default values to the parameters.
		$config = $this->merge_defaults( $config );

		// Configure sections.
		$config = $this->associate_sections( $config );

		// Associate labels.
		$config = $this->associate_label_for( $config );

		return $config;

	}

	/**
	 * Return the configuration array.
	 *
	 * @return array
	 */
	public function get() {

		return $this->config;

	}

	/**
	 * Merge the default parameters with the user defined parameters.
	 * Only 2 levels deep.
	 *
	 * @param array $params User defined parameters.
	 *
	 * @return array
	 */
	private function merge_defaults( $params ) {

		foreach ( $this->defaults as $key => $default ) {
			if ( is_array( $default ) ) {
				if ( array_key_exists( $key, $params ) ) {
					foreach ( $default as $key2 => $default2 ) {
						if ( ! array_key_exists( $key2, $params[ $key ] ) ) {
							$params[ $key ][ $key2 ] = $default2;
						}
					}
				} else {
					$params[ $key ] = $default;
				}
			} else {
				if ( ! array_key_exists( $key, $params ) ) {
					$params[ $key ] = $default;
				}
			}
		}

		return $params;

	}

	/**
	 * Configure each section to be associated with a section ID key.
	 *
	 * @since 0.1.0
	 *
	 * @param array $config The settings configuration.
	 *
	 * @return array
	 */
	private function associate_sections( $config ) {

		foreach ( $config['sections'] as $key => $section ) {

			// Get this section's ID.
			$section_id = $section['section'];

			// Create an associative key assignment for the section.
			// This is done for succinct code elsewhere.
			$config['sections'][ $section_id ] = $section;

			// Remove the old numeric-indexed key assignment.
			unset( $config['sections'][ $key ] );

		}

		return $config;

	}

	/**
	 * Configure each missing label_for value to be populated by the ID value.
	 *
	 * @since 0.1.0
	 *
	 * @param array $config The settings configuration.
	 *
	 * @return array
	 */
	private function associate_label_for( $config ) {

		foreach ( $config['sections'] as $section_key => $section ) {
			if ( array_key_exists( 'fields', $section ) ) {
				foreach ( $section['fields'] as $field_key => $field ) {
					if ( ! array_key_exists( 'data_args', $field ) ) {
						$config['sections'][ $section_key ]['fields'][ $field_key ]['data_args'] = array(
							'label_for' => $field['id'],
						);
					} elseif ( ! array_key_exists( 'label_for', $field['data_args'] ) ) {
						$config['sections'][ $section_key ]['fields'][ $field_key ]['data_args']['label_for'] = $field['id'];
					}
				}
			}
		}

		return $config;

	}
}
