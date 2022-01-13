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
	 * Validate the input value and return any error messages.
	 *
	 * @param string $input The input value.
	 * @return void
	 */
	public function validate( $input ) {

		$valid = array(
			'status'   => true,
			'messages' => array(),
		);

		// If the input is empty but required.
		$is_empty = $this->is_empty( $input );
		if ( ! empty( $this->settings['data_args']['required'] ) ) {
			if ( true === $is_empty['status'] ) {
				$valid['status']              = false;
				$valid['message']['is_empty'] = 'The value cannot be empty.';
			}
		}

		// If the input must follow a pattern.
		$pattern = $this->settings['data_args']['pattern'];
		if ( ! empty( $pattern ) ) {
			$is_pattern = $this->is_pattern( $input );
			if ( false === $is_pattern['status'] ) {
				$valid['status']                 = false;
				$valid['message']['not_pattern'] = "The value must follow the pattern \"{$pattern}\"";
			}
		}

		// If the input must follow length requirements.
		$is_length = $this->is_length( $input );
		if ( false === $is_length['status'] ) {
			$valid['status']                = false;
			$valid['message']['not_length'] = $is_length['message'];
		}

		// If the input has script tags.
		$has_script_tag = $this->has_script_tag( $input );
		if ( true === $has_script_tag['status'] ) {
			$valid['status']                    = false;
			$valid['message']['has_script_tag'] = $has_script_tag['message'];
		}

		// If the input has script tags.
		$has_php_tag = $this->has_php_tag( $input );
		if ( true === $has_php_tag['status'] ) {
			$valid['status']                 = false;
			$valid['message']['has_php_tag'] = $has_php_tag['message'];
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
			'message' => "The $label input is a valid length.",
		);

		// I suspect 16mb is a common max string length for SQL database insertion on shared hosting for WordPress.
		if ( ! defined( 'TWL_SETTING_MAX_STRING_LENGTH' ) ) {
			define( 'TWL_SETTING_MAX_STRING_LENGTH', 16777216 );
		}
		$minlength = 0;
		$maxlength = TWL_SETTING_MAX_STRING_LENGTH;
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
				$valid['message'] = "The value is {$strlen} characters long and must be more than {$minlength} characters long.";
			} elseif ( $true_max !== $maxlength ) {
				$valid['status']  = false;
				$valid['message'] = "The value is {$strlen} characters long and must be between {$minlength} and {$maxlength} characters long.";
			} else {
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

	/**
	 * Validate the emptiness of the input.
	 *
	 * @param  string $input The setting input value.
	 * @return array
	 */
	public function is_empty( $input ) {

		$label = $this->settings['label'];
		$valid = array(
			'status'  => false,
			'message' => "The $label value is not empty.",
		);

		// Validate.
		if ( empty( $input ) || empty( trim( $input ) ) ) {

			$valid['status']  = true;
			$valid['message'] = "The $label value is empty.";

		}

		return $valid;

	}

	/**
	 * Sanitize the value of script tags.
	 *
	 * @param string $input The input value to sanitize.
	 * @return void
	 */
	public function has_script_tag( $input ) {

		$valid = array(
			'status'  => true,
			'message' => 'Script tag(s) not found',
		);

		// Remove JavaScript.
		preg_match( '/<script\b[^>]*>(.*?)<\/script>/is', '', $input, $matches );
		if ( $matches ) {
			$valid['status'] = false;
		}

		preg_match( '~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is', '', $input, $matches );
		if ( $matches ) {
			$valid['status'] = false;
		}

		preg_match( '/\/\*\*\/script|s\/\*\*\/cript|sc\/\*\*\/ript|scr\/\*\*\/ipt|scri\/\*\*\/pt|scrip\/\*\*\/t|script\/\*\*\//', '', $input, $matches );
		if ( $matches ) {
			$valid['status'] = false;
		}

		if ( false === $valid['status'] ) {
			$valid['message'] = 'Script tag(s) found.';
		}

		return $valid;

	}

	/**
	 * Sanitize the value of php tags.
	 *
	 * @param string $input The input value to sanitize.
	 * @return void
	 */
	public function has_php_tag( $input ) {

		$valid = array(
			'status'  => true,
			'message' => 'PHP tag(s) not found',
		);

		preg_match( '/<\?php(.*?);?\s*\?>/', '', $input, $matches );
		if ( $matches ) {
			$valid['status'] = false;
		}

		if ( false === $valid['status'] ) {
			$valid['message'] = 'PHP tag(s) found.';
		}

		return $valid;

	}

}
