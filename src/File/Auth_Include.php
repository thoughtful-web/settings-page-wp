<?php
/**
 * File include helper class.
 * Use this within a file to be included by others.
 * Example: new \ThoughtfulWeb\LibraryWP\File\Auth_Include( __FILE__, __DIR__ . '/thoughtfulweb/library/file/class-auth-include.php', $_SERVER, 'ABSPATH' );
 *
 * @package    Thoughtful_Web\Library_WP
 * @subpackage File
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/file/auth_include.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace Thoughtful_Web\Library_WP\File;

/**
 * The File Require class.
 *
 * @see   https://www.php.net/manual/en/language.oop5.basic.php
 * @since 0.1.0
 */
class Auth_Include {

	/**
	 * Load file.
	 *
	 * @since 0.1.0
	 *
	 * @param string       $file            The directory path to the file being included.
	 * @param string|array $allowed_scripts Directory path to files allowed to include the file.
	 * @param array        $server          The server definition array.
	 * @param bool|string  $constant        The constant which must be defined to authorize the file.
	 *
	 * @return mixed
	 */
	public function __construct( $file = '', $allowed_scripts, $server, $constant ) {

		$this->authorize( $file, $allowed_scripts, $server, $constant );

	}

	/**
	 * Authorize the file execution attempt.
	 *
	 * If the requesting script file name is defined,
	 * and the requesting script is in the allowed script list,
	 * and the requesting script is not the file itself,
	 * and the ABSPATH constant is defined,
	 * and the request method is GET,
	 * then load the file.
	 *
	 * @param string       $file            The directory path to the file being included.
	 * @param string|array $allowed_scripts Directory path to files allowed to include the file.
	 * @param array        $server          The server definition array.
	 * @param bool|string  $constant        The constant which must be defined to authorize the file.
	 *
	 * @return void
	 */
	public static function authorize( $file = '', $allowed_scripts = '', $server = array(), $constant = false ) {

		if ( is_string( $allowed_scripts ) ) {
			$allowed_scripts = array( $allowed_scripts );
		}

		if (
			! isset( $server['SCRIPT_FILENAME'] )
			|| ! in_array( realpath( $server['SCRIPT_FILENAME'] ), $allowed_scripts, true )
			|| realpath( $file ) === realpath( $server['SCRIPT_FILENAME'] )
			|| ! is_string( $constant )
			|| ! defined( $constant )
			|| 'GET' !== $server['REQUEST_METHOD']
		) {
			self::error_404();
		}
	}

	/**
	 * Emit 404 error headers and end script execution.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private static function error_404() {

		if ( ! defined( 'ABSPATH' ) ) {
			// The WordPress runtime environment is not available.
			$wp_config = dirname( dirname( dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . '/wp-config.php';
			if ( file_exists( $wp_config ) ) {
				include_once $wp_config;

				global $wp_query;
				$wp_query->set_404();
				status_header( 404 );
				get_template_part( 404 );
				exit();
			}
		}

		header( 'HTTP/1.0 404 Not Found', true, 404 );

		/* choose the appropriate page to redirect users */
		header( 'location: /404.php' );

		exit();

	}
}
