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

use \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Sanitize\Text_Sanitizer;

class Url_Sanitizer extends Text_Sanitizer {

	/**
	 * Sanitize the string for database purposes.
	 *
	 * @see https://developer.wordpress.org/reference/functions/esc_url_raw/
	 *
	 * @param string $input The input value to sanitize.
	 *
	 * @return string
	 */
	public function sanitize_db( $input ) {

		$input = esc_url_raw( $input );

		return $input;

	}

	/**
	 * Sanitize the value for HTML attribute purposes.
	 *
	 * @see https://developer.wordpress.org/reference/functions/esc_url/
	 *
	 * @param string $input The input value to sanitize.
	 *
	 * @return string
	 */
	public function sanitize_attr( $input ) {

		$input = esc_url( $input );
		return $input;

	}

	/**
	 * Sanitize the value for presentation purposes.
	 *
	 * @see https://developer.wordpress.org/reference/functions/esc_url/
	 *
	 * @param string $input The input value to sanitize.
	 *
	 * @return string
	 */
	public function sanitize_display( $input ) {

		$input = esc_url( $input );

		return $input;

	}

}
