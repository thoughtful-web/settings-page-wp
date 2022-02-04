<?php
/**
 * The file that creates a Section for the Settings page.
 *
 * @package    ThoughtfulWeb\SettingsPageWP
 * @subpackage Section
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/settings-page-wp/blob/main/src/Settings/Section.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\SettingsPageWP\Settings;

/**
 * The Section class, which registers the section and outputs a description.
 */
class Section {

	/**
	 * The section description.
	 *
	 * @var string $description The section description.
	 */
	private $description = '';

	/**
	 * The settings capability.
	 *
	 * @var string $capability The settings capability.
	 */
	private $capability;

	/**
	 * The settings section.
	 *
	 * @param string $id          (Required) Slug-name to identify the section. Used in the 'id' attribute of tags.
	 * @param string $title       (Required) Formatted title of the section. Shown as the heading for the section.
	 * @param string $description (Optional) The description for the section.
	 * @param string $page        (Required) The slug-name of the settings page on which to show the section. Built-in
	 *                            pages include 'general', 'reading', 'writing', 'discussion', 'media', etc. Create
	 *                            your own using add_options_page().
	 * @param string $capability  The capability required to display.
	 * @param array  $section     (Required) The settings section configuration array.
	 *
	 * @return void
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
			if ( ! empty( $this->description ) ) {
				echo wp_kses_post( $this->description );
			}

			if ( array_key_exists( 'include', $this->section ) && ! empty( $this->section['include'] ) ) {
				include $this->section['include'];
			}
		}

	}
}
