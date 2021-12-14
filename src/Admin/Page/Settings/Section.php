<?php
/**
 * The file that creates a Settings page section.
 *
 * @package    Thoughtful_Web\Library_WP
 * @subpackage Section
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/page/settings/section.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace Thoughtful_Web\Library_WP\Admin\Page\Settings;

class Section {

	/**
	 * The section description.
	 *
	 * @var string $description The section description.
	 */
	private $description;

	/**
	 * The settings capability.
	 *
	 * @var string $capability The settings capability.
	 */
	private $capability;

	public function __construct( $id, $title, $description, $page, $capability ) {

		$this->description = $description;
		$this->capability  = $capability;

		add_settings_section( $id, $title, array( $this, 'description' ), $page );

	}

	/**
	 * Add a section description.
	 *
	 * @since 0.1.0
	 *
	 * @param array $args {
	 *     The section arguments.
	 *
	 *     @key string $id       The section ID.
	 *     @key string $title    The section title.
	 *     @key string $callback This function's name.
	 * }
	 *
	 * @return void
	 */
	public function description( $args ) {

		if (
			! empty( $this->description )
			&& current_user_can( $this->capability )
		) {
			echo wp_kses_post( $this->description );
		}

	}
}
