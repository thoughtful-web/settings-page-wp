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

	public function __construct() {

		$this->add_hooks();

	}

	public function add_hooks() {
		// add the action
		add_action( 'wp_mail_failed', array( $this, 'action_wp_mail_failed' ), 10, 1 );
		add_action( 'phpmailer_init', array( $this, 'action_phpmailer_init' ) );
	}

	/**
	 * The wp_mail_failed callback.
	 *
	 * @param WP_Error $wp_error The WP_Error object.
	 * @return void
	 */
	public function action_wp_mail_failed( $wp_error ) {

		$date = new \DateTime(strtotime(time()));
		$date->setTimezone(new \DateTimeZone('America/Chicago'));
		$timestamp = $date->format("F j, Y, g:i a");

		$log     = dirname( ABSPATH, 2 ) . '/error-wp-mail.log';
		$alt_log = ABSPATH . '/error-wp-mail.log';

		// Get error messages.
		$error_messages = $wp_error->get_error_messages();
		$error_data     = $wp_error->get_error_data( 'wp_mail_failed' );

		$messages  = '[' . $timestamp . '] Delivery Failed: ';
		$messages .= implode( '; ', $error_messages );
		$messages .= '; ' . serialize( $error_data );
		$messages .= "\r\n";

		if ( ! file_exists( $log ) ) {
			if ( ! is_writable( $log ) ) {
				$log = $alt_log;
			}
			$handle = fopen( $log, 'a' );
			fclose( $handle );
		}

		error_log( $messages, 3, $log );

	}

	public function action_phpmailer_init( $phpmailer ) {

		$date = new \DateTime(strtotime(time()));
		$date->setTimezone(new \DateTimeZone('America/Chicago'));
		$timestamp = $date->format("F j, Y, g:i a");

		$log   = dirname( ABSPATH, 2 ) . '/wp-mail.log';
		$alt_log = ABSPATH . '/wp-mail.log';

		$template = array();
		$template['subject']      = $phpmailer->Subject;
		$template['body']         = $phpmailer->Body;

		// Convert phpmailer recipients to array by receipt category.
		$template['recipients'] = array(
			'to'  => $phpmailer->getToAddresses(),
			'cc'  => $phpmailer->getCcAddresses(),
			'bcc' => $phpmailer->getBccAddresses(),
		);
		$message                = array();
		foreach ( $template['recipients'] as $type => $addresses ) {
			$address_strings = array();
			foreach ( $addresses as $address ) {
				$address_strings[] = $address[1] ? "{$address[1]} <{$address[0]}>" : $address[0];
			}
			$message[] = ucwords( $type ) . ': ' . implode( ', ', $address_strings );
		}
		$template['recipient_message'] = implode( '; ', $message );

		$message  = '[' . $timestamp . '] ';
		$message .= "Subject: {$template['subject']}; {$template['recipient_message']}; Body: {$template['body']}" . "\r\n";

		if ( ! file_exists( $log ) ) {
			if ( ! is_writable( $log ) ) {
				$log = $altlog;
			}
			$handle = fopen( $log, 'a' );
			fclose( $handle );
		}

		error_log( $message, 3, $log );

	}
}
