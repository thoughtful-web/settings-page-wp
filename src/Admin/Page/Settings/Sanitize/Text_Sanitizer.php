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
			$sanitary['status']   = false;
			$san_key              = 'db' === $mode ? 'sanitize_db' : 'sanitize';
			$sanitary['messages'] = array(
				'fail'   => "The {$label} value was modified:",
				$san_key => 'unsafe content was found.'
			);
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

		if ( 'db' === $mode ) {
			$input = $this->sanitize_db( $input );
		} elseif ( 'attribute' === $mode ) {
			$input = $this->sanitize_attr( $input );
		} else {
			$input = $this->sanitize_display( $input );
		}

		return $input;

	}

	/**
	 * Sanitize the string for database purposes.
	 *
	 * @param string $input The input value to sanitize.
	 *
	 * @return string
	 */
	public function sanitize_db( $input ) {

		$input = $this->sanitize_script_tags( $input );
		$input = $this->sanitize_php_tags( $input );

		return $input;

	}

	/**
	 * Sanitize the value for HTML attribute purposes.
	 *
	 * @param string $input The input value to sanitize.
	 *
	 * @return string
	 */
	public function sanitize_attr( $input ) {

		$input = preg_replace( '"', '', $input );
		$input = $this->sanitize_script_tags( $input );
		$input = $this->sanitize_php_tags( $input );

		return $input;

	}

	/**
	 * Sanitize the value for presentation purposes.
	 *
	 * @param string $input The input value to sanitize.
	 *
	 * @return string
	 */
	public function sanitize_display( $input ) {

		$input = $this->sanitize_script_tags( $input );
		$input = $this->sanitize_php_tags( $input );

		return $input;

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

		return $input;

	}

	/**
	 * Sanitize the value of php tags.
	 *
	 * @param string $input The input value to sanitize.
	 * @return void
	 */
	public function sanitize_php_tags( $input ) {

		$input = preg_replace( '/<\?php(.*?);?\s*\?>/', '', $input );

		return $input;

	}

}
