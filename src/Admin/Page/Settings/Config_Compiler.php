<?php
/**
 * The file that extends WP_Error notification capabilities.
 *
 * @package    Thoughtful_Web\Library_WP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/main/Admin/Page/Settings/Config_Compiler.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace Thoughtful_Web\Library_WP\Admin\Page\Settings;

/**
 * The Settings Page Parameter Compiler Class.
 *
 * @since 0.1.0
 */
class Config_Compiler {

	/**
	 * The settings configuration parameters.
	 *
	 * @var array $params The settings configuration parameters.
	 */
	private $params;

	/**
	 * The settings configuration defaults.
	 *
	 * @var array $defaults The settings configuration defaults.
	 */
	private $defaults;

	/**
	 * Constructor for the Compile class.
	 *
	 * @return array
	 */
	public function construct() {}

	/**
	 * Get the compiler results.
	 *
	 * @param array $params   The Settings page configuration parameters.
	 * @param array $defaults The default Settings page configuration parameters.
	 *
	 * @return array
	 */
	public function get_results( $params, $defaults ) {

		$this->params   = $params;
		$this->defaults = $defaults;

		if ( is_string( $params ) ) {
			$fieldset_file_path = $this->validate_file_path( $params );
			if ( $fieldset_file_path ) {
				$params = include $fieldset_file_path;
			} else {
				return array();
			}
		} elseif ( empty( $params ) ) {
			return array();
		}

		// Apply default values to the parameters.
		print_r($params);
		print_r($defaults);
		$params = $this->merge_parameters( $params, $defaults );
		print_r($params);

		// Configure fieldsets.
		$fieldsets       = array_map( array( $this, 'configure_fieldsets' ), $params['fieldsets'] );
		$section_ids     = array_column( $fieldsets, 'section' );
		$keyed_fieldsets = array_fill_keys( $section_ids, $fieldsets );
		// Assign the configurations.
		$params['fieldsets'] = $keyed_fieldsets;

		return $params;

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
	private function validate_file_path( $file_path = '' ) {

		// Discern the correct path to the file.
		$file_path = realpath( $file_path );

		return file_exists( $file_path ) ? $file_path : false;

	}

	/**
	 * Merge the default parameters with the user defined parameters.
	 * Only 2 levels deep.
	 *
	 * @param array $params   User defined parameters.
	 * @param array $defaults Default parameters.
	 *
	 * @return array
	 */
	private function merge_parameters( $params, $defaults ) {

		foreach ( $defaults as $key => $default ) {
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
	 * Configure each fieldset in the fieldsets settings parameter.
	 *
	 * @since 0.1.0
	 *
	 * @param array $settings The settings parameters.
	 *
	 * @return array
	 */
	private function configure_fieldsets( $fieldset ) {

		$section_id = $fieldset['section'];
		foreach ( $fieldset['fields'] as $key2 => $field ) {
			// Assign the section ID to each field in the fieldset.
			$fieldset['fields'][ $key2 ]['section'] = $section_id;
		}
		return $fieldset;
	}
}
