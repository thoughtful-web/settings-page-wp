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

use \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Sanitize;

class Text_Sanitizer extends Sanitize {

	/**
	 * Get the fully sanitized value.
	 *
	 * @param string   $input The input value to sanitize.
	 *
	 * @return string
	 */
	public function sanitize_attr( $input ) {

		$input = esc_attr( $input );

		return $input;

	}

	/**
	 * Detect if the value is sanitary.
	 *
	 * @param string          $input The input value to sanitize.
	 * @param string|string[] $mode  (Optional) The purpose for sanitizing the value. Default is
	 *                               'attribute'.
	 *
	 * @return array
	 */
	public function is_sanitary( $input, $mode = 'attribute' ) {

		// Ensure the mode is an array.
		if ( ! is_array( $mode ) ) {
			$mode = array( $mode );
		}

		// Declare initial variables.
		$initial_value = $input;
		$label         = $this->settings['label'];
		// Declare success based variables.
		$success  = 'The ' . $label . ' value is free of unsafe characters.';
		$failure  = 'The ' . $label . ' value was modified:';
		$sanitary = array(
			'status'  => true,
			'message' => array(),
		);

		// Sanitize the input for HTML attributes.
		if ( in_array( 'attribute', $mode, true ) ) {
			$input = $this->sanitize_attr( $input, $mode );
			// Detect if the value was modified by the sanitization process.
			if ( $input !== $initial_value ) {
				$sanitary['status'] = false;
				// Assign the message to a key for filter assistance.
				$sanitary['message']['sanitize_attr'] = 'The ' . $label . ' field has unsafe HTML attribute characters.';
			}
		}

		// Load the initial value of the results message.
		if ( $input === $initial_value ) {
			// Set the success message.
			$sanitary['message']['success'] = $success;
		} else {
			// Set the failure message preface.
			array_unshift( $sanitary['message'], $failure );
		}

		// Apply user filters to the return value. Typically used to customize messages.
		$sanitary = apply_filters( 'twl_settings_sanitize_text', $sanitary, $label, $this->settings );

		// Convert the message to a single string.
		$sanitary['message'] = implode( ' ', $sanitary['message'] );

		return $sanitary;

	}

}
