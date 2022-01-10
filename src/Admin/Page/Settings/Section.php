<?php
/**
 * The file that creates a Settings page section.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Section
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/page/settings/section.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Admin\Page\Settings;

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

	/**
	 * The settings section.
	 *
	 * @var array $section The settings section configuration array.
	 */

	public function __construct( $id, $title, $description, $page, $capability, $section ) {

		$this->description = $description;
		$this->capability  = $capability;
		$this->section     = $section;

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

		if ( current_user_can( $this->capability ) ) {
			echo wp_kses_post( $this->description );
		}

		if ( array_key_exists( 'include', $this->section ) && ! empty( $this->section['include'] ) ) {
			include $this->section['include'];
		}

	}
}
