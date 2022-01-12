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
	 * Instantiate the class and assign properties from the parameters.
	 *
	 * @param string $setting The setting parameters to use when validating.
	 */
	public function __construct( $settings ) {

		$this->settings = array_merge( $this->settings, $settings );

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
