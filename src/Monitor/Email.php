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

use Monolog\Logger as Logger;
use Monolog\Handler\StreamHandler as StreamHandler;

class Email {

	private $log_stream;
	private $wp_mail_logger;
	private $error_log_stream;
	private $wp_mail_failed_logger;

	public function __construct() {

		require dirname( __FILE__, 5 ) . '/monolog/monolog/src/Logger.php';
		require dirname( __FILE__, 5 ) . '/monolog/monolog/src/Handler/StreamHandler.php';

		// Create the email error logger.
		$this->log_stream     = pushHandler(new StreamHandler( dirname( ABSPATH, 2 ) . 'wp.email.log', Logger::INFO));
		$this->wp_mail_logger = new Logger('wp_mail');
		$this->wp_mail_logger->pushHandler( $this->log_stream );

		// Create the email error logger.
		$this->error_log_stream      = pushHandler(new StreamHandler( dirname( ABSPATH, 2 ) . 'error.wp.email.log', Logger::ERROR));
		$this->wp_mail_failed_logger = new Logger('wp_mail_failed');
		$this->wp_mail_failed_logger->pushHandler( $this->error_log_stream );

	}

	public function add_hooks() {
		// add the action
		add_action( 'wp_mail_failed', array( $this, 'action_wp_mail_failed' ), 10, 1 );
		add_action( 'phpmailer_init', array( $this, 'action_phpmailer_init' ) );
		add_action( 'admin_init', function(){
			// Error.
			wp_mail( 'asdf@#.#', 'Test', 'This is a terrible test of monolog and email logging to a file.' );
			// Success.
			wp_mail( 'admin@' . $_SERVER['HTTP_HOST'], 'Test', 'This is a terrible test of monolog and email logging to a file.' );
		});
	}

	/**
	 * The wp_mail_failed callback.
	 *
	 * @param WP_Error $wp_error The WP_Error object.
	 * @return void
	 */
	public function action_wp_mail_failed( $wp_error ) {
		// create a log channel
		$messages = implode( "\r\n", $wp_error->get_error_messages() );
		$this->wp_mail_failed_logger->error( $messages );
	}

	public function action_phpmailer_init( $phpmailer ) {

		$template = array();
		$template['subject']      = $phpmailer->Subject;
		$template['body']         = $phpmailer->Body;
		// Convert phpmailer recipients to array by receipt category.
		$template['recipients']   = array();
		foreach ( $phpmailer->RecipientsQueue as $address => $params ) {
			$type    = $param[0];
			$address = $param[1];
			$name    = $param[2];
			if ( ! array_key_exists( $type, $template['recipients'] ) ) {
				$template['recipients'][ $type ] = array();
			}
			$full_address                      = $name ? "{$name} <{$address}>" : $address;
			$template['recipients'][ $type ][] = $full_address;
		}
		// Create the recipient message string.
		$recipient_messages = array();
		foreach ( $template['recipients'] as $type => $subrecipients ) {
			$recipient_messages[] = ucfirst( $type ) . ': ' . implode( ', ', $subrecipients );
		}
		$template['recipient_message'] = implode( '; ', $recipient_messages );

		$message = "Subject: \"$template['subject']\"; \"$template['recipient_message']\"; Body: $template['body']";

		$this->wp_mail_logger->info( $message );

	}
}
