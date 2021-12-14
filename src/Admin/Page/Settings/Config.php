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
	 * The configuration associative array.
	 *
	 * @var array $config The associative array storing the final configuration state.
	 */
	private $config;

	/**
	 * Constructor for the Compile class.
	 *
	 * @param array $params   The Settings page configuration parameters.
	 * @param array $defaults The default Settings page configuration parameters.
	 *
	 * @return array
	 */
	public function construct( $params, $defaults ) {

		$this->build( $params, $defaults );

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
	 * Get the compiler results.
	 *
	 * @param array $params   The Settings page configuration parameters.
	 * @param array $defaults The default Settings page configuration parameters.
	 *
	 * @return array
	 */
	private function build( $params, $defaults ) {

		if ( is_string( $params ) ) {
			$config_path = $this->validate_file_path( $params );
			if ( $config_path ) {
				$params = include $config_path;
			} else {
				return array();
			}
		} elseif ( empty( $params ) ) {
			return array();
		}

		// Apply default values to the parameters.
		echo "
merge";
		$params = $this->merge_parameters( $params, $defaults );

		// Configure sections.
		$params = $this->associate_sections( $params );

		$this->config = $params;

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
	 * Configure each fieldset in the sections settings parameter.
	 *
	 * @since 0.1.0
	 *
	 * @param array $params The settings parameters.
	 *
	 * @return array
	 */
	private function associate_sections( $params ) {

		$results = array();

		foreach ( $params['sections'] as $section ) {
			$section_id             = $section['section'];
			$results[ $section_id ] = $section;
		}

		$params['sections'] = $results;

		return $params;

	}
}
