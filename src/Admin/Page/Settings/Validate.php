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

class Validate {

	/**
	 * Validation settings.
	 *
	 * @access private
	 */
	private $settings = array();

	/**
	 * Instantiate the class and assign properties from the parameters.
	 *
	 * @param string $setting The setting parameters to use when validating.
	 */
	public function __construct( $settings ) {

		$this->settings = array_merge( $this->settings, $settings );

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
			'message' => 'The ' . $label . ' field is not empty.'
		);

		// Validate.
		if ( is_string( $input ) && ( empty( $input ) || empty( trim( $input ) ) ) ) {

			$valid['status']  = true;
			$valid['message'] = apply_filters( 'twl_validate_string_empty', 'The ' . $label . ' setting is empty.', $label, $this->settings );

		} elseif ( is_array( $input ) && empty( $input ) ) {

			$valid['status']  = true;
			$valid['message'] = apply_filters( 'twl_validate_array_empty', 'The ' . $label . ' setting does not have an option selected.', $label, $this->settings );

		}

		return $valid;

	}

	/**
	 * Notify the user of the validation status.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_settings_error/
	 *
	 * @param string $message (Required) The formatted message text to display to the user (will be
	 *                        shown inside styled <div> and <p> tags).
	 * @param string $type    (Optional) Message type, controls HTML class. Possible values include
	 *                        'error', 'success', 'warning', 'info'. Default value: 'error'.
	 * @return void
	 */
	public function notify( $message, $type ) {

		$setting = $this->setting['id'];
		$code    = 'notice_' . $this->setting['id'];
		if ( $type ) {
			$code .= "_$type";
		}
		$code .= '_' . uniqid();
		$code  = esc_attr( $code );

		if ( $type ) {
			add_settings_error( $setting, $code, $message, $type );
		} else {
			add_settings_error( $setting, $code, $message );
		}

	}

}
