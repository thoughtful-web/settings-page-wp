<?php
/**
 * The WordPress admin notice generator class file.
 *
 * Tips for helpful notices:
 *     Pointer to current line in file: __LINE__
 *     Get the current class name:      get_class($this)
 *
 * @package    Thoughtful_Web\Library_WP
 * @subpackage Admin
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/class-notice.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace Thoughtful_Web\Library_WP\Admin;

/**
 * The Notice class.
 */
class Notice {

	/**
	 * Default class constructor arguments.
	 *
	 * @var array $default_args The default argument values for the class constructor's "args" parameter.
	 */
	private $default_args = array(
		'level'   => 'error',
		'dismiss' => true,
		'permit'  => array(
			'cap' => 'read',
		),
	);

	/**
	 * The admin notice store.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/admin_notices/
	 *
	 * @since 0.1.0
	 *
	 * @var array $notices {
	 *     All notices registered by the class instance.
	 *
	 *     @key array The notice slug. {
	 *         The notice configuration parameters.
	 *
	 *         @key string $slug    The slug used by core processes.
	 *         @key string $message The notice message.
	 *         @key array  $args    {
	 *             Extra arguments for configuring the notice.
	 *
	 *             @key string   $level   The notice level. Accepts success, warning, or error.
	 *                                    Default is error.
	 *             @key bool     $dismiss If the notice can be dismissed.
	 *             @key string[] $usercan Parameters to check against `current_user_can` before
	 *                                    showing the notice. Must pass all checks.
	 *         }
	 *     }
	 * }
	 */
	private $notices = array();

	/**
	 * Construct a new admin notice. Must be instantiated before the 'admin_notices' hook.
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since 0.1.0
	 *
	 * @param string $slug    The slug used by core processes.
	 * @param string $message The notice message template string.
	 * @param array  $args    {
	 *     Extra arguments for configuring the notice.
	 *
	 *     @key string   $level   The notice level. Accepts success, warning, or error. Default is
	 *                            error.
	 *     @key bool     $dismiss If the notice can be dismissed.
	 *     @key string[] $usercan Parameters to check against `current_user_can` before showing the
	 *                            notice. Must pass all checks.
	 * }
	 *
	 * @return void
	 */
	public function __construct( $slug, $message, $args = array() ) {

		$this->set( $slug, $message, $args );

	}

	/**
	 * Register a new admin notice to the notices class property.
	 * Must be instantiated before the 'admin_notices' hook.
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since 0.1.0
	 *
	 * @param string $slug    The slug used by core processes.
	 * @param string $message The notice message template string.
	 * @param array  $args    {
	 *     Extra arguments for configuring the notice.
	 *
	 *     @key string   $level   The notice level. Accepts success, warning, or error. Default is
	 *                            error.
	 *     @key bool     $dismiss If the notice can be dismissed.
	 *     @key string[] $usercan Parameters to check against `current_user_can` before showing the
	 *                            notice. Must pass all checks.
	 * }
	 *
	 * @return void
	 */
	public function set( $slug, $message, $args = array() ) {

		// First time setting a notice for this instance of the class.
		if ( empty( $this->notices ) ) {
			$this->add_hooks();
		}

		$this->notices[ $slug ] = array(
			'slug'    => $slug,
			'message' => $message,
			'args'    => $args,
		);
	}

	/**
	 * Add WordPress action and filter hooks as necessary.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function add_hooks() {

		add_action( 'admin_notices', array( $this, 'display_notices' ) );

	}

	/**
	 * Display the admin notices.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function display_notices() {

		// Exit early if no notices found.
		$notices = apply_filters( 'twlib_admin_notices', $this->notices );
		if ( empty( $notices ) ) {
			return;
		}

		$output = '';

		foreach ( $notices as $slug => $notice ) {
			// Check user against permissions.
			$user_can = $this->user_can_view( $notice['usercan'] );
			if ( false === $user_can ) {
				continue;
			}

			// Extract first word from notice level.
			$notice_level = $notice['args']['level'];
			$notice_level = esc_attr( $notice_level );
			$notice_level = explode( ' ', $notice_level )[0];
			// Render output.
			$output .= sprintf(
				'<div id="%s" class="notice notice-%s"><p>',
				$slug,
				$notice_level
			);
			$output .= wp_kses_post( $notice['message'] );
			$output .= '</p></div>';
		}

		/* translators: Translations must occur before passing the message to the class constructor or `add` method. */
		echo wp_kses_post( $output );
	}

	/**
	 * Test user roles and capabilities against a notice's configurations.
	 *
	 * @param array $permits The roles and/or capabilities a user must have.
	 *
	 * @return boolean
	 */
	public function user_can_view( $usercan ) {

		foreach ( $usercan as $value ) {
			if ( ! current_user_can( $value ) ) {
				return false;
			}
		}

		return true;
	}
}
