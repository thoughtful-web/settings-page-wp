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
	 * Detect if the value is sanitary.
	 *
	 * @param string $input The input value to sanitize.
	 * @param string $mode  (Optional) The mode of transport for the string. Accepts 'db', 'form',
	 *                      or 'attribute'. Default is null.
	 *
	 * @return array
	 */
	public function is_sanitary( $input, $mode = null ) {

		// Declare initial variables.
		$initial_value = $input;
		$label         = $this->settings['label'];
		// Declare success based variables.
		$sanitary = array(
			'status'   => true,
			'messages' => array( 'success' => "The {$label} value is safe." ),
		);

		// Sanitize the input for various purposes.
		$input = $this->sanitize( $input, $mode );

		// Detect if the value was modified by the sanitization process.
		if ( $input !== $initial_value ) {
			$sanitary['status']                       = false;
			$sanitary['messages']['fail']             = "The {$label} value was modified.";
			$sanitary['messages']["sanitize_{$mode}"] = "Unsafe content was found.";
		}

		// Convert the messages to a single string.
		$sanitary['message'] = implode( ' ', $sanitary['messages'] );

		return $sanitary;

	}

	/**
	 * Sanitize the string for presentation purposes.
	 *
	 * @param string $input The input value to sanitize.
	 * @param string $mode  (Optional) The mode of transport for the string. Accepts 'db' or
	 *                      'attribute'. Default is null.
	 *
	 * @return string
	 */
	public function sanitize( $input, $mode = null ) {

		if ( is_string( $input ) ) {
			$input = $this->sanitize_script_tags( $input );
		} else {
			foreach( $input as $key => $value ) {
				if ( is_string( $value ) ) {
					$input[ $key ] = $this->sanitize_script_tags( $input );
				}
			}
		}

		return $input;

	}

	/**
	 * Notify the user of the validation status.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_settings_error/
	 *
	 * @param array  $is_sanitary {
	 *                   (Required) The formatted message parameters to display to the user (will
	 *                   be shown inside styled <div> and <p> tags).
	 *                   @key boolean  $status   The status of the sanitization.
	 *                   @key string[] $messages The associative array of sanitization messages.
	 *                   @key string   $message  The concatenated $messages string.
	 *               }
	 * @param string $type        (Optional) Message type, controls HTML class. Possible values
	 *                            include 'error', 'success', 'warning', 'info'. Default behavior
	 *                            emits an error. Default value: null.
	 * @return void
	 */
	public function notify( $is_sanitary, $type = null ) {

		$setting  = $this->settings['id'];
		$code     = 'notice_sanitize_' . $this->settings['id'];
		$code    .= $type ? "_$type" : '_error';
		$code    .= '_' . uniqid();
		$code     = esc_attr( $code );
		$types    = array( 'error', 'success', 'warning', 'info' );

		$is_sanitary = apply_filters( 'notice_sanitize_' . $this->settings['type'], $is_sanitary, $type, $this->settings );
		$message     = apply_filters( $code, $is_sanitary['message'], $is_sanitary, $type, $this->settings );

		if ( in_array( $type, $types, true ) ) {
			add_settings_error( $setting, $code, $message, $type );
		} else {
			add_settings_error( $setting, $code, $message );
		}

	}

	/**
	 * Sanitize the value of script tags.
	 *
	 * @param string $input The input value to sanitize.
	 * @return void
	 */
	public function sanitize_script_tags( $input ) {

		// Remove JavaScript.
		$input = preg_replace( '/<script\b[^>]*>(.*?)<\/script>/is', '', $input );
		$input = preg_replace( '~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is', '', $input );
		$input = preg_replace( '/\/\*\*\/script|s\/\*\*\/cript|sc\/\*\*\/ript|scr\/\*\*\/ipt|scri\/\*\*\/pt|scrip\/\*\*\/t|script\/\*\*\//', '', $input );
		// Remove PHP.
		$input = preg_replace( '/<\?php(.*?);?\s*\?>/', '', $input );

		return $input;

	}

}
