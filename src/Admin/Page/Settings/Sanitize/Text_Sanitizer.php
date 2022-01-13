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

		$input = esc_attr( $input );

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

		$input = wp_kses_post( $input );

		return $input;

	}

}
