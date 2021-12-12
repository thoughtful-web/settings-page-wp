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
class Config {

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
	public function compile( $params, $defaults ) {

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
		$params = $this->merge_parameters( $params, $defaults );

		// Configure fieldsets.
		$params = $this->associate_fieldsets( $params );

		return $params;

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

		$params = $this->configure_missing_parameters( $params );

		return $params;

	}

	/**
	 * Configure parameters absent from both defaults and user defined parameters.
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	private function configure_missing_parameters( $params ) {

		// Configure the option_group name where database settings values will be stored.
		if ( ! array_key_exists( 'option_group', $params ) ) {
			$params['option_group'] = str_replace( '-', '_', sanitize_key( $params['method_args']['menu_slug'] ) );
		}

		return $params;

	}

	/**
	 * Configure each fieldset in the fieldsets settings parameter.
	 *
	 * @since 0.1.0
	 *
	 * @param array $params The settings parameters.
	 *
	 * @return array
	 */
	private function associate_fieldsets ( $params ) {

		$results = array();

		foreach ( $params['fieldsets'] as $fieldset ) {
			$section_id = $fieldset['section'];
			$results[ $section_id ] = $fieldset;
		}

		$params['fieldsets'] = $results;

		return $params;

	}
}
