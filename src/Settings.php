<?php
/**
 * The file that extends WP_Error notification capabilities.
 *
 * @package    ThoughtfulWeb\SettingsPageWP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/settings-page-wp/blob/main/src/Settings.php
 * @since      0.9.8
 */

declare(strict_types=1);
namespace ThoughtfulWeb\SettingsPageWP;

use \ThoughtfulWeb\SettingsPageWP\Settings\Section;
use \ThoughtfulWeb\SettingsPageWP\Settings\Config;

/**
 * The Admin Settings Page Class.
 *
 * @since 0.1.0
 */
class Settings {

	/**
	 * User capability requirement for accessing the settings page.
	 *
	 * @var string $capability The user capability string.
	 */
	private $capability = 'manage_options';

	/**
	 * Name the group of database options which the fields represent.
	 *
	 * @var string $option_group The database option group name. Lowercase letters and underscores only. If not configured it will default  to the menu_slug method argument with hyphens replaced with underscores.
	 */
	private $option_group = 'options';

	/**
	 * The menu page slug.
	 *
	 * @var string $menu_slug The settings page slug for a URL.
	 */
	private $menu_slug;

	/**
	 * Settings page and field Parameters.
	 *
	 * @var array $config The Settings page and fieldset parameters.
	 */
	private $config = array();

	public function __construct( $config ) {
	
		// Store the configuration and its key parameters for this file.
		$this->config       = $config;
		$this->capability   = $config['method_args']['capability'];
		$this->menu_slug    = $config['method_args']['menu_slug'];
		$this->option_group = $config['option_group'];

		add_action( 'admin_init', array( $this, 'settings_init' ) );
	
	}

	/**
	 * Register settings, add sections, and add fields to those sections.
	 *
	 * @since 0.9.8
	 *
	 * @return void
	 */
	public function settings_init() {

		$this->add_sections();
		$this->add_fields();

	}

	/**
	 * Add settings sections.
	 *
	 * @since 0.9.8
	 *
	 * @return void
	 */
	private function add_sections() {

		foreach ( $this->config['sections'] as $id => $section ) {
			$description = isset( $section['description'] ) ? $section['description'] : '';
			new \ThoughtfulWeb\SettingsPageWP\Settings\Section(
				$id,
				$section['title'],
				$description,
				$this->menu_slug,
				$this->capability,
				$section,
			);
		}

	}

	/**
	 * Add each settings field to the page.
	 *
	 * @since 0.9.8
	 *
	 * @return void
	 */
	public function add_fields() {

		foreach ( $this->config['sections'] as $section ) {

			// Skip this section if it is missing fields.
			if ( ! array_key_exists( 'fields', $section ) ) {
				continue;
			}

			$section_id = $section['section'];
			$fields     = $section['fields'];

			foreach ( $fields as $field ) {

				switch ( $field['type'] ) {
					case 'text':
						new \ThoughtfulWeb\SettingsPageWP\Settings\Field\Text(
							$field,
							$this->menu_slug,
							$section_id,
							$this->option_group,
							$this->capability
						);
						break;
					case 'textarea':
						new \ThoughtfulWeb\SettingsPageWP\Settings\Field\Textarea(
							$field,
							$this->menu_slug,
							$section_id,
							$this->option_group,
							$this->capability
						);
						break;
					case 'number':
						new \ThoughtfulWeb\SettingsPageWP\Settings\Field\Number(
							$field,
							$this->menu_slug,
							$section_id,
							$this->option_group,
							$this->capability
						);
						break;
					case 'checkbox':
						if ( array_key_exists( 'choice', $field ) ) {
							new \ThoughtfulWeb\SettingsPageWP\Settings\Field\Checkbox(
								$field,
								$this->menu_slug,
								$section_id,
								$this->option_group,
								$this->capability
							);
						} elseif ( array_key_exists( 'choices', $field ) ) {
							new \ThoughtfulWeb\SettingsPageWP\Settings\Field\Checkboxes(
								$field,
								$this->menu_slug,
								$section_id,
								$this->option_group,
								$this->capability
							);
						}
						break;
					case 'wp_editor':
						new \ThoughtfulWeb\SettingsPageWP\Settings\Field\WP_Editor(
							$field,
							$this->menu_slug,
							$section_id,
							$this->option_group,
							$this->capability
						);
						break;
					case 'color':
						new \ThoughtfulWeb\SettingsPageWP\Settings\Field\Color(
							$field,
							$this->menu_slug,
							$section_id,
							$this->option_group,
							$this->capability
						);
						break;
					case 'email':
						new \ThoughtfulWeb\SettingsPageWP\Settings\Field\Email(
							$field,
							$this->menu_slug,
							$section_id,
							$this->option_group,
							$this->capability
						);
						break;
					case 'select':
						new \ThoughtfulWeb\SettingsPageWP\Settings\Field\Select(
							$field,
							$this->menu_slug,
							$section_id,
							$this->option_group,
							$this->capability
						);
						break;
					case 'tel':
						new \ThoughtfulWeb\SettingsPageWP\Settings\Field\Phone(
							$field,
							$this->menu_slug,
							$section_id,
							$this->option_group,
							$this->capability
						);
						break;
					case 'url':
						new \ThoughtfulWeb\SettingsPageWP\Settings\Field\Url(
							$field,
							$this->menu_slug,
							$section_id,
							$this->option_group,
							$this->capability
						);
						break;
					case 'password':
						new \ThoughtfulWeb\SettingsPageWP\Settings\Field\Password(
							$field,
							$this->menu_slug,
							$section_id,
							$this->option_group,
							$this->capability
						);
						break;
					default:
						break;
				}
			}
		}
	}

	/**
	 * Add content to the Admin settings page.
	 *
	 * @since 0.9.8
	 *
	 * @return void
	 */
	public function site_options_form() {

		if ( ! current_user_can( $this->capability ) ) {
			return;
		}

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<?php settings_errors(); ?>
			<form action="options.php" method="post">
				<?php
					// Output security fields for the registered setting.
					settings_fields( $this->option_group );
					// Output setting sections and their fields.
					// (Sections are registered for "$this->menu_slug", each field is registered to a specific section).
					do_settings_sections( $this->menu_slug );
					// Output save settings button.
					submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php

	}

	/**
	 * Return the Settings page form in callable format.
	 * 
	 * @since 0.9.8
	 *
	 * @return void
	 */
	public function settings_form_callable() {

		return array( $this, 'site_options_form' );

	}
}