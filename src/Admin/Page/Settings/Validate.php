<?php
/**
 * The file that creates a Settings page section.
 * @todo Make error messages configurable via a file with sprintf-compatible string template characters.
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

class Validate {

	/**
	 * Validation settings.
	 *
	 * @access private
	 */
	private $settings = array(
		'data_args' => array(),
	);

	/**
	 * Validation messages.
	 */

	/**
	 * Instantiate the class and assign properties from the parameters.
	 *
	 * @param string $setting The setting parameters to use when validating.
	 */
	public function __construct( $settings ) {

		$this->settings = array_merge( $this->settings, $settings );

	}

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

		// Declare initial variables.
		$initial_value = $input;
		$label         = $this->settings['label'];
		// Declare success based variables.
		$valid = array(
			'status'   => true,
			'messages' => array( 'success' => "The {$label} value is valid." ),
		);

		// Sanitize the input for various purposes.
		$validated = $this->validate( $input );

		// Detect if the value was modified by the sanitization process.
		if ( false === $validated['status'] ) {
			$valid['status']   = false;
			$valid['messages'] = array( 'fail' => "The {$label} value is invalid:", );
			$valid['messages'] = array_merge( $valid['messages'], $validated['messages'] );
		}

		// Convert the messages to a single string.
		$valid['message'] = implode( ' ', $valid['messages'] );

		return $valid;

	}

	/**
	 * Validate the input value and return any error messages.
	 * Must be implemented by other classes.
	 *
	 * @param string $input The input value.
	 * @return void
	 */
	public function validate( $input ) {

		$valid = array(
			'status'   => true,
			'messages' => array(),
		);

		return $valid;

	}

	/**
	 * Notify the user of the validation status.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_settings_error/
	 *
	 * @param array  $is_valid {
	 *                   (Required) The formatted message parameters to display to the user (will
	 *                   be shown inside styled <div> and <p> tags).
	 *                   @key boolean  $status   The status of the sanitization.
	 *                   @key string[] $messages The associative array of sanitization messages.
	 *                   @key string   $message  The concatenated $messages string.
	 *               }
	 * @param string $type     (Optional) Message type, controls HTML class. Possible values
	 *                         include 'error', 'success', 'warning', 'info'. Default behavior
	 *                         emits an error. Default value: null.
	 * @return void
	 */
	public function notify( $is_valid, $type = null ) {

		$setting  = $this->settings['id'];
		$code     = 'notice_validate_' . $this->settings['id'];
		$code    .= $type ? "_$type" : '_error';
		$code    .= '_' . uniqid();
		$code     = esc_attr( $code );

		$is_valid = apply_filters( 'notice_validate_' . $this->settings['type'], $is_valid, $type, $this->settings );
		$message  = apply_filters( $code, $is_valid['message'], $is_valid, $type, $this->settings );

		if ( $type ) {
			add_settings_error( $setting, $code, $message, $type );
		} else {
			add_settings_error( $setting, $code, $message );
		}

	}

}
