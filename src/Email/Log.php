<?php
/**
 * The file that sends WordPress Email activity to a log file.
 *
 * @package    Thoughtful_Web\Library_WP
 * @subpackage Email
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/src/email/log.php
 * @since      0.1.0
 */
namespace Thoughtful_Web\Library_WP\Email;

class Log {

	/**
	 * The email log file.
	 *
	 * @var string $log The mail log file. This will be recalculated on class construction to point
	 *                  to the ABSPATH parent directory.
	 */
	private $log = 'wp-mail.log';

	/**
	 * The alternate email log file.
	 *
	 * @var string $alt_log The mail log file. This will be recalculated on class construction to point
	 *                      to the ABSPATH parent directory.
	 */
	private $alt_log = 'wp-mail.log';

	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	public function __construct() {

		$this->log = dirname( ABSPATH, 2 ) . '/' . $this->log;

		$this->alt_log = ABSPATH . '/' . $this->log;

		$this->add_hooks();

	}

	/**
	 * Add action hooks to use for monitoring attempted and failed email deliveries.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function add_hooks() {

		add_action( 'wp_mail_failed', array( $this, 'action_wp_mail_failed' ) );
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
		$recipients['to'][] = 'asdf@local.dev';

		foreach ( $recipients as $type => $addresses ) {

			$message[ $type] = ucwords( $type ) . ': ';

			if ( empty ( $addresses ) ) {
				continue;
			}

			$address_strings = array();

			foreach ( $addresses as $address ) {
				$address_strings[] = $address[1] ? "{$address[1]} <{$address[0]}>" : $address[0];
			}

			$message[ $type ] = implode( ', ', $address_strings );
		}

		$str = implode( '; ', $message );

		return $str;

	}

	/**
	 * Get the timestamp in a format acceptable for the error log.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	private function get_timestamp() {

		$date = new \DateTime(strtotime(time()));
		$date->setTimezone(new \DateTimeZone('America/Chicago'));
		$timestamp = $date->format("F j, Y, g:i a");
		return '[' . $timestamp . ']';

	}

	/**
	 * Get the email log message string.
	 *
	 * @since 0.1.0
	 *
	 * @param string $subject The email subject.
	 * @param string $to      The email to entry.
	 * @param string $cc      The email cc entry.
	 * @param string $bcc     The email bcc entry.
	 * @param string $body    The email body.
	 *
	 * @return void
	 */
	private function get_email_log_str( $subject, $to, $cc, $bcc, $body ) {

		$recipients_arr = array(
			'to'  => $to,
			'cc'  => $cc,
			'bcc' => $bcc,
		);
		$recipients = $this->assemble_recipient_str( $recipients_arr );
		return "Subject: {$subject}; {$recipients}; Body: {$body}";

	}

	/**
	 * Get the PHPMailer object's log message.
	 *
	 * @since 0.1.0
	 *
	 * @param PHPMailer $phpmailer The PHPMailer object
	 *
	 * @return void
	 */
	private function phpmailer_message( $phpmailer ) {

		$message = $this->get_email_log_str(
			$phpmailer->Subject,
			$phpmailer->getToAddresses(),
			$phpmailer->getCcAddresses(),
			$phpmailer->getBccAddresses(),
			$phpmailer->Body
		);

		return $message;

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
		$error_data     = $wp_error->get_error_data( 'wp_mail_failed' );
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
		$message  = $this->get_email_log_str(
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
	 * @param bool   $code    If the message is for an error or not. 400+ is error, else is not.
	 * @param string $message The message to add to the log file.
	 * @param string $log     The log file path.
	 *
	 * @return bool
	 */
	private function log_message( $code, $message ) {

		$log = $this->log;
		if ( ! file_exists( $log ) ) {
			if ( ! is_writable( $log ) ) {
				$log = $this->alt_log;
				if ( ! is_writable( $log ) ) {
					return false;
				}
			}
			$handle = fopen( $log, 'a' );
			fclose( $handle );
		}

		$messages  = $this->get_timestamp();
		$messages .= 400 <= $error ? ' [!] Failed: ' : ' [+] Sent: ';
		$messages .= $message;
		$messages .= PHP_EOL;

		$log_filename = basename( $log );
		$alt_log = ABSPATH . '/' . $log_filename;

		error_log( $messages, 3, $log );

	}

	/**
	 * The wp_mail_failed callback.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Error $wp_error The WP_Error object.
	 *
	 * @return void
	 */
	public function action_wp_mail_failed( $wp_error ) {

		$message = $this->wp_error_message( $wp_error );

		$this->log_message( 400, $message );

	}

	/**
	 * The phpmailer_init callback.
	 *
	 * @since 0.1.0
	 *
	 * @param PHPMailer $phpmailer The PHPMailer object.
	 *
	 * @return void
	 */
	public function action_phpmailer_init( $phpmailer ) {

		$message = $this->phpmailer_message( $phpmailer );

		$this->log_message( 200, $message );

	}
}
