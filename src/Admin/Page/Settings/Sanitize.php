<?php
/**
 * The file that creates a Settings page section.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2022 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/page/settings/sanitize.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Admin\Page\Settings;

class Sanitize {

	/**
	 * Validation settings.
	 *
	 * @access private
	 */
	private $settings = array(
		'data_args' => array(),
	);

	/**
	 * Instantiate the class and assign properties from the parameters.
	 *
	 * @param string $setting The setting parameters to use when validating.
	 */
	public function __construct( $settings ) {

		$this->settings = array_merge( $this->settings, $settings );

	}

	/**
	 * Get the fully sanitized value.
	 *
	 * @param string $input The input value to sanitize.
	 * @param string $mode  (Optional) The purpose for sanitizing the value. Default is 'attribute'.
	 *
	 * @return string
	 */
	public function sanitize( $input, $mode = 'attribute' ) {

		if ( 'attribute' === $mode ) {
			$input = esc_attr( $input );
		}
		return $input;

	}

	/**
	 * Detect if the value is sanitary.
	 *
	 * @param string $input The input value to sanitize.
	 * @param string $mode  (Optional) The purpose for sanitizing the value. Default is 'attribute'.
	 *
	 * @return array
	 */
	public function is_sanitary( $input, $mode = 'attribute' ) {

		$label         = $this->settings['label'];
		$initial_value = $input;
		$sanitized     = $this->sanitize( $input, $mode );
		$sanitary      = array(
			'status'  => true,
			'message' => 'The ' . $label . ' field is sanitary.'
		);
		if ( $sanitized !== $initial_value ) {
			$sanitary['status']  = false;
			$sanitary['message'] = 'The ' . $label . ' field has invalid characters.';
		}

		return $sanitary;

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
		$code    = 'notice_sanitize_' . $this->setting['id'];
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
