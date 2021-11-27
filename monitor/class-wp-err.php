<?php
/**
 * The file that extends WP_Error notification capabilities.
 *
 * @package    ThoughtfulWeb\Library
 * @subpackage Monitor
 * @copyright  Zachary Watkins 2021
 * @author     Zachary Watkins <watkinza@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0-or-later
 * @link       https://github.com/zachwatkins/wordpress-plugin-name/blob/master/util/class-alert.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\Library\Monitor;

/**
 * The class that monitors WP Errors and pushes notifications to channels.
 *
 * @see   https://www.php.net/manual/en/language.oop5.basic.php
 * @since 0.1.0
 */
class WP_Err {

	/**
	 * Supported channels of communicating WP_Error events.
	 *
	 * @var array $channels The configurations of the event monitor. Accepts 'die', 'error_log', and 'email'.
	 */
	private $supported_channels = array( 'die', 'error_log', 'email' );

	/**
	 * The WP_Error object.
	 *
	 * @var WP_Error $error The WP_Error object generated by the constructor method.
	 */
	private $wp_error;

	/**
	 * The Event monitor channel for communicating this instance's events.
	 *
	 * @var array $channels The configurations of the event monitor.
	 */
	private $channels;

	/**
	 * Channel configuration options.
	 *
	 * @var array $channel_args {
	 *     Channel configurations needed for some channels. Optional.
	 *
	 *     @key string $email_address  The email address to send the message to.
	 *     @key string $email_template The email template string passed to `sprintf` before sending.
	 *     @key string $error_log      The error log file destination.
	 *     @key bool   $email_log      Log emails.
	 * }
	 */
	private static $channel_args;

	/**
	 * The class constructor.
	 *
	 * @todo Implement $channel options for email, error log, webhook, WP Admin alert, etc.
	 *
	 * @param string|int|WP_Error $code         Error code.
	 * @param string              $message      Error message.
	 * @param mixed               $data         Optional. Error data. Default is empty array.
	 * @param string[]            $channels     Optional. How the event is communicated. Default 'die'.
	 *                                          See $supported_channels property for accepted values.
	 *                                          Suggested implementation options in todo above.
	 * @param null|array          $channel_args {
	 *     Channel configurations needed for some channels. Optional.
	 *
	 *     @key string $email_address  The email address to send the message to.
	 *     @key string $email_template The email template string passed to `sprintf` before sending.
	 * }
	 */
	public function __construct( $code, $message = '', $data = '', $channels = 'die', $channel_args = null ) {

		if ( is_wp_error( $code ) ) {
			$wp_error = $code;
			$code     = $wp_error->get_error_code();
			$message  = $wp_error->get_error_message( $code );
			$data     = $wp_error->get_error_data( $code );
		}

		// Validate and register channel names.
		$this->channels = $this->sanitize_channels_arg( $channels );

		// Validate the channel arguments before storing.
		if ( $channel_args && is_array( $channel_args ) ) {
			$this->channel_args = $channel_args;
		}

		// Register channel action hooks.
		$this->add_channel_hooks();

		// Generate a new error object.
		$this->wp_error = new \WP_Error( $code, $message, $data );

	}

	/**
	 * Sanitize the channels parameter.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $channels Channel options passed to the class constructor.
	 *
	 * @return array
	 */
	private function sanitize_channels_arg( $channels ) {

		// Declare the channels where this WP_Error will push to.
		$channels = is_string( $channels ) ? array( $channels ) : $channels;

		if ( is_array( $channels ) && ! empty( $channels ) ) {
			// Remove unsupported channels.
			$channels = array_filter( $channels, array( $this, 'filter_channels' ) );
			$channels = array_values( $channels );
		} else {
			$channels = array();
		}

		return $channels;
	}

	/**
	 * Filter supported channels.
	 *
	 * @since 0.1.0
	 *
	 * @param string[] $channel The submitted channels.
	 *
	 * @return string[]
	 */
	private function filter_channels( $channel ) {

		return in_array( $channel, $this->supported_channels, true );

	}

	/**
	 * Add channel event hooks.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/wp_mail_failed/
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function add_channel_hooks() {

		// Monitor the error in question.
		// Use get_called_class() to make static class methods callable.
		foreach ( $this->channels as $channel ) {
			add_action( 'wp_error_added', array( get_called_class(), "{$channel}_error_added" ), 10, 4 );
		}

		// Monitor failed email deliveries.
		if ( isset( $this->channels['email'] ) ) {
			add_action( 'wp_mail_failed', array( $this, 'log_mailer_errors' ), 10, 1 );
		}

	}

	/**
	 * Send the error message to the "die" channel.
	 *
	 * @see   https://developer.wordpress.org/reference/functions/wp_die/
	 * @see   https://github.com/WordPress/WordPress-Coding-Standards/wiki/Escaping-a-WP_Error-object
	 *
	 *  @since 0.1.0
	 *
	 * @param string|int $code     Error code.
	 * @param string     $message  Error message.
	 * @param mixed      $data     Error data. Might be empty.
	 * @param WP_Error   $wp_error The WP_Error object.
	 *
	 * @return void
	 */
	public static function die_error_added( $code, $message, $data, $wp_error ) {

		wp_die( self::escape_wp_error( $wp_error ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $alert is escaped before being passed in.

	}

	/**
	 * Send the error message to the "log" channel.
	 *
	 * @see https://www.php.net/manual/en/function.error-log.php
	 *
	 * @since 0.1.0
	 *
	 * @param string|int $code     Error code.
	 * @param string     $message  Error message.
	 * @param mixed      $data     Error data. Might be empty.
	 * @param WP_Error   $wp_error The WP_Error object.
	 *
	 * @return void
	 */
	public static function error_log_error_added( $code, $message, $data, $wp_error ) {

		// Determine the destination for the email error log entry.
		$wp_debug_log = defined( 'WP_DEBUG_LOG' ) ? WP_DEBUG_LOG : true;
		if ( ini_get( 'error_log' ) ) {
			$log_path = ini_get( 'error_log' );
		} elseif ( in_array( strtolower( (string) $wp_debug_log ), array( 'true', '1' ), true ) ) {
			$log_path = WP_CONTENT_DIR . '/debug.log';
		} elseif ( is_string( $wp_debug_log ) ) {
			$log_path = $wp_debug_log;
		} else {
			return;
		}

		if ( is_string( $log_path ) && file_exists( $log_path ) ) {
			error_log( $message, 0, $log_path );
		}

	}

	/**
	 * Send the error message to the "log" channel.
	 * Note: This function is only available after the `plugins_loaded` action hook.
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_mail/
	 *
	 * @since 0.1.0
	 *
	 * @param string|int $code     Error code.
	 * @param string     $message  Error message.
	 * @param mixed      $data     Error data. Might be empty.
	 * @param WP_Error   $wp_error The WP_Error object.
	 *
	 * @return void
	 */
	public static function email_error_added( $code, $message, $data, $wp_error ) {

		// Determine the email recipient.
		$args        = self::$channel_args;
		$admin_email = get_site_option( 'admin_email' );
		$recipient   = isset( $args['email_address'] ) ? $args['email_address'] : $admin_email;

		// If the recipient cannot be found, exit.
		if ( false === $admin_email || ! isset( $args['email_title'] ) ) {
			return;
		}

		// Determine the remaining mailer arguments.
		$title   = $args['email_title'];
		$message = isset( $args['email_template'] ) ? sprintf( $args['email_template'], $message ) : $message;
		$headers = array(
			'From: Site Admin <' . $admin_email . '>',
			'Content-Type: text/html; charset=UTF-8',
		);

		// Send the email.
		wp_mail( $recipient, $title, $message, $headers );

	}

	/**
	 * Log failed email deliveries.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Error $wp_error The WP_Error object created by the failed email delivery.
	 *
	 * @return void
	 */
	public function log_mailer_errors( $wp_error ) {

		// Determine the destination for the email error log entry.
		$wp_debug_log = defined( 'WP_DEBUG_LOG' ) ? WP_DEBUG_LOG : true;
		if ( ini_get( 'mail.log' ) ) {
			$log_path = ini_get( 'mail.log' );
		} elseif ( in_array( strtolower( (string) $wp_debug_log ), array( 'true', '1' ), true ) ) {
			$log_path = WP_CONTENT_DIR . '/debug.log';
		} elseif ( is_string( $wp_debug_log ) ) {
			$log_path = $wp_debug_log;
		} else {
			return;
		}

		if ( is_string( $log_path ) && file_exists( $log_path ) ) {
			// Execute the logging action.
			error_log( $wp_error->get_error_message(), 0, $log_path );
		}
	}

	/**
	 * Taken from WordPress Coding Standards. Authors below.
	 * Last edited by Juliette on Dec 26, 2018.
	 *
	 * Escape a WP_Error object for passing directly to wp_die().
	 *
	 * The wp_die() function accepts an WP_Error object as the first parameter, but it
	 * does not escape it's contents before printing it out to the user. By passing
	 * the object through this function before giving it to wp_die(), the potential for
	 * XSS should be avoided.
	 *
	 * @author J.D. Grimes <jdg@codesymphony.co> (https://github.com/JDGrimes)
	 * @author Juliette <info@adviesenzo.nl> (https://github.com/jrfnl)
	 * @author Gary Jones (https://github.com/GaryJones)
	 *
	 * @link https://github.com/WordPress/WordPress-Coding-Standards/wiki/Escaping-a-WP_Error-object
	 *
	 * @param WP_Error $error The error to escape.
	 *
	 * @return WP_Error The escaped error.
	 */
	private static function escape_wp_error( $error ) {

		$code = $error->get_error_code();

		$error_data = $error->error_data;

		if ( isset( $error_data[ $code ]['title'] ) ) {
			$error_data[ $code ]['title'] = wp_kses(
				$error->error_data[ $code ]['title'],
				'escape_wp_error_title'
			);
			$error->error_data            = $error_data;
		}

		$all_errors = $error->errors;

		foreach ( $all_errors as $code => $errors ) {
			foreach ( $errors as $key => $message ) {
				$all_errors[ $code ][ $key ] = wp_kses(
					$message,
					'escape_wp_error_message'
				);
			}
		}

		$error->errors = $all_errors;

		return $error;

	}
}
