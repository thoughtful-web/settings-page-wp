<?php
/**
 * The file that sends WordPress Email activity to [a] log file[s].
 *
 * @package    Thoughtful_Web\Library_WP
 * @subpackage Monitor
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/src/monitor/email.php
 * @uses       https://github.com/Seldaek/monolog
 * @since      0.1.0
 */
namespace Thoughtful_Web\Library_WP\Monitor;

class Email {

	/**
	 * The email log file.
	 *
	 * @var string $log The mail log file. This will be recalculated on class construction to point
	 *                  to the ABSPATH parent directory.
	 */
	$log = 'wp-mail.log';

	public function __construct() {

		$this->log       = dirname( ABSPATH, 2 ) . '/' . $this->log;

		$this->add_hooks();

	}

	public function add_hooks() {
		// add the action
		add_action( 'wp_mail_failed', array( $this, 'action_wp_mail_failed' ), 10, 1 );
		add_action( 'phpmailer_init', array( $this, 'action_phpmailer_init' ) );
		add_action( 'init', function(){
			if ( ! wp_doing_cron() && ! wp_doing_ajax() ) {
				error_log('init');
				// Error.
				wp_mail( 'asdf@#.com', 'Test', 'This is a terrible test of monolog and email logging to a file.' );
				// Success.
				wp_mail( 'admin@homeandranch.local', 'Test', 'This is a terrible test of monolog and email logging to a file.' );
			}
		});
	}

	/**
	 * Assemble email recipients string from array.
	 *
	 * @param array $recipients {
	 *     Email recipients.
	 *
	 *     @key array $to  {
	 *         The "To" recipients.
	 *         @index string Required. The email address.
	 *         @index string The email recipient name. Optional.
	 *     }
	 *     @key array $cc  {
	 *         The "Cc" recipients.
	 *         @index string Required. The email address.
	 *         @index string The email recipient name. Optional.
	 *     }
	 *     @key array $bcc {
	 *         The "Bcc" recipients.
	 *         @index string Required. The email address.
	 *         @index string The email recipient name. Optional.
	 *     }
	 * }
	 * @return void
	 */
	private function assemble_recipient_str( $recipients ) {

		$str     = '';
		$message = array();
		foreach ( $recipients as $type => $addresses ) {
			$address_strings = array();
			foreach ( $addresses as $address ) {
				$address_strings[] = $address[1] ? "{$address[1]} <{$address[0]}>" : $address[0];
			}
			$message[] = ucwords( $type ) . ': ' . implode( ', ', $address_strings );
		}
		$str = implode( '; ', $message );

		return $str;

	}

	private function get_email_log_str( $subject, $to, $cc, $bcc, $body ) {

		$recipients_arr = array(
			'to'  => $to,
			'cc'  => $cc,
			'bcc' => $bcc,
		);
		$recipients = $this->assemble_recipient_str( $recipients_arr );
		return "Subject: {$subject}; {$recipients}; Body: {$body}";

	}

	private function get_timestamp() {

		$date = new \DateTime(strtotime(time()));
		$date->setTimezone(new \DateTimeZone('America/Chicago'));
		$timestamp = $date->format("F j, Y, g:i a");
		return '[' . $timestamp . ']';

	}

	private function phpmailer_entry( $phpmailer ) {

		// Get error messages.
		$error_messages = $wp_error->get_error_messages();
		$error_data     = $wp_error->get_error_data( 'wp_mail_failed' );

		$messages  = 'Errors: ';
		$messages .= implode( '; ', $error_messages );
		$messages .= '; ' . serialize( $error_data );

		return $messages;

	}

	/**
	 * Create a log message entry for wp_error objects associated with failed email delivery.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Error $wp_error The WP_Error object.
	 *
	 * @return string
	 */
	private function wp_error_message( $wp_error ) {

		// Get variables for assembly into an error log message.
		$error_data     = $wp_error->get_error_data( 'wp_mail_failed' )[0];
		$subject        = $error_data['subject'];
		$body           = $error_data['message'];
		$recipients     = array(
			'to' => '',
			'cc' => '',
			'bcc' => '',
		);
		foreach ( array_keys( $recipients ) as $type ) {
			if ( array_key_exists( $type, $error_data ) ) {
				$recipients[ $type ] = $error_data[ $type ];
			}
		}

		// Build the message output.
		$message .= $this->get_email_log_str(
			$subject,
			$recipients['to'],
			$recipients['cc'],
			$recipients['bcc'],
			$body
		);
		$message .= '; ';
		$message .= 'Errors: ';
		$message .= implode( '; ', $wp_error->get_error_messages() );
		if ( array_key_exists( 'phpmailer_exception_code', $error_data ) ) {
			$message .= "PHPMailer Exception Code #{$error_data['phpmailer_exception_code']}";
		}

		return $message;

	}

	/**
	 * Log an email-related message to an error log file.
	 *
	 * @since 0.1.0
	 *
	 * @param bool   $error   If the message is for an error or not.
	 * @param string $message The message to add to the log file.
	 * @param string $log     The log file path.
	 *
	 * @return bool
	 */
	private function log_message( $error, $message, $log ) {

		if ( ! file_exists( $log ) ) {
			if ( ! is_writable( $log ) ) {
				$log = $alt_log;
				if ( ! is_writable( $log ) ) {
					return false;
				}
			}
			$handle = fopen( $log, 'a' );
			fclose( $handle );
		}

		$messages  = $this->get_timestamp();
		$messages .= $error ? ' [!] Failed: ' : ' [+] Sent: ';
		$messages .= $message;
		$messages .= PHP_EOL;

		$log_filename = basename( $log );
		$alt_log = ABSPATH . '/' . $log_filename;

		error_log( $messages, 3, $log );

	}

	/**
	 * The wp_mail_failed callback.
	 *
	 * @param WP_Error $wp_error The WP_Error object.
	 * @return void
	 */
	public function action_wp_mail_failed( $wp_error ) {

		$message = $this->wp_error_message( $wp_error );

		$this->log_message( true, $message, $this->log );

	}

	public function action_phpmailer_init( $phpmailer ) {

		$log   = dirname( ABSPATH, 2 ) . '/wp-mail.log';
		$alt_log = ABSPATH . '/wp-mail.log';

		$messages  = $this->get_timestamp();
		$messages .= ' [!] Sending: ';
		$messages .= $this->get_email_log_str(
			$phpmailer->Subject,
			$phpmailer->getToAddresses(),
			$phpmailer->getCcAddresses(),
			$phpmailer->getBccAddresses(),
			$phpmailer->Body
		);
		$messages .= "\r\n";

		if ( ! file_exists( $log ) ) {
			if ( ! is_writable( $log ) ) {
				$log = $altlog;
			}
			$handle = fopen( $log, 'a' );
			fclose( $handle );
		}

		error_log( $messages, 3, $log );

	}
}
