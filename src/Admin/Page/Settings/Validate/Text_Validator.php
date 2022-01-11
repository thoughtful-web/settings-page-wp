<?php
/**
 * The file that creates a Settings page section.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2022 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/page/settings/validate.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Admin\Page\Settings;

use \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Validate;

class Text_Validator extends Validate {

	/**
	 * Validation settings.
	 *
	 * @access private
	 */
	private $settings = array(
		'data_args' => array(
			'pattern'  => '',
			'required' => 'false',
		),
	);

	/**
	 * Validate the input text using the following requirements:
	 * 1. Is not empty if it is a required field.
	 * 2. Matches a pattern if present.
	 * 3. Is within the min and max lengths. 16mb is the default max length.
	 *
	 * @param  string $input The string to validate.
	 * @return array
	 */
	public function is_valid( $input ) {

		$label = $this->settings['label'];
		$valid = array(
			'status'  => true,
			'message' => array(),
		);

		// If the input is empty but required.
		$is_empty = $this->is_empty( $input );
		if ( ! empty( $this->settings['data_args']['required'] ) ) {
			if ( true === $is_empty['status'] ) {
				$valid['status']    = false;
				$valid['message'][] = 'The value cannot be empty.';
			}
		}

		// If the input must follow a pattern.
		if ( ! empty( $this->settings['data_args']['pattern'] ) ) {
			$is_pattern = $this->is_pattern( $input );
			if ( false === $is_pattern['status'] ) {
				$valid['status']    = false;
				$valid['message'][] = 'The value must follow the pattern "' . $this->settings['data_args']['pattern'] . '"';
			}
		}

		// If the input must follow length requirements.
		$is_length = $this->is_length( $input );
		if ( false === $is_length['status'] ) {
			$valid['status']    = false;
			$valid['message'][] = $is_length['message'];
		}

		// Combine error messages.
		if ( ! empty( $valid['message'] ) ) {
			// Insert the description at the beginning of the array.
			$description = apply_filters( 'twl_settings_validate_text', 'The ' . $label . ' setting has one or more validation errors:', $label, $this->settings );
			array_unshift( $valid['message'], $description );
			// Concatenate the validation message string.
			$valid['message'] = implode( ' ', $valid['message'] );
		}

		return $valid;

	}

	/**
	 * Validate string length.
	 *
	 * @param string $input The input value.
	 * @return array
	 */
	public function is_length( $input ) {

		$label = $this->settings['label'];
		$valid = array(
			'status'  => true,
			'message' => 'The ' . $label . ' input is a valid length.',
		);

		// I suspect 16mb is a common max string length for SQL database insertion on shared hosting for WordPress.
		$true_max  = 16777216;
		$minlength = 0;
		$maxlength = apply_filters( 'twl_settings_max_string_length', $true_max );
		$strlen    = strlen( $input );
		if ( ! empty( $this->settings['data_args']['minlength'] ) ) {
			$minlength = $this->settings['data_args']['minlength'];
		}
		if ( ! empty( $this->settings['data_args']['maxlength'] ) ) {
			$maxlength = intval( $this->settings['data_args']['maxlength'] );
		}
		if ( $strlen < $minlength || $strlen > $maxlength ) {
			if ( $true_max === $maxlength && $minlength > 0 ) {
				$valid['status']  = false;
				$valid['message'] = 'The value is ' . strlen( $input ) . ' characters long and must be more than ' . $minlength . ' characters long.';
			} elseif ( $true_max !== $maxlength ) {
				$valid['status']  = false;
				$valid['message'] = 'The value is ' . strlen( $input ) . ' characters long and must be between ' . $minlength . ' and ' . $maxlength . ' characters long.';
			} elseif ( $minlength <= 0 ) {
				// We already handle empty strings.
			}
		}

		return $valid;

	}

	/**
	 * Validate a setting pattern.
	 *
	 * @param  string $input The string to validate.
	 * @return array
	 */
	public function is_pattern( $input ) {

		$label   = $this->settings['label'];
		$pattern = $this->settings['data_args']['pattern'];
		$valid   = array(
			'status'  => true,
			'message' => 'The ' . $label . ' field matches the given pattern of "' . $pattern . '".'
		);
		// Format the pattern for PHP.
		$pattern = str_replace( '/', '\/', $pattern );
		$pattern = "/$pattern/";
		preg_match( $pattern, $input, $matches );
		if ( empty( $matches ) ) {
			$valid['status']  = false;
			$valid['message'] = str_replace( 'matches', 'value of "' . $input . '" does not match', $valid['message'] );
		}

		return $valid;
	}

}
