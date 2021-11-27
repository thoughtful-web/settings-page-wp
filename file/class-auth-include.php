<?php
/**
 * File include helper class.
 * Use this within a file to be included by others.
 * Example: new \ThoughtfulWeb\Library\File\Auth_Include( __FILE__, __DIR__ . '/thoughtfulweb/library/file/class-auth-include.php', $_SERVER, 'ABSPATH' );
 *
 * @package    ThoughtfulWeb\Library
 * @subpackage File
 * @copyright  Zachary Watkins 2021
 * @author     Zachary Watkins <watkinza@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0-or-later
 * @link       https://github.com/zachwatkins/wordpress-plugin-name/blob/master/thoughtfulweb/library/admin/class-page-template.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\Library\File;

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

		header( 'HTTP/1.0 404 Not Found', true, 404 );

		/* choose the appropriate page to redirect users */
		header( 'location: /404.php' );

		die();

	}
}
