<?php
/**
 * The file that extends the Field class into a Checkbox Field with multiple checkboxes for the Settings API.
 *
 * @package    ThoughtfulWeb\SettingsPageWP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/settings-page-wp/blob/main/src/Settings/Field/Checkboxes.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\SettingsPageWP\Settings\Field;

use \ThoughtfulWeb\SettingsPageWP\Settings\Field;

/**
 * The Checkboxes Field class.
 *
 * @since 0.1.0
 */
class Checkboxes extends Field {

	/**
	 * The default values for required $field members.
	 *
	 * @var array $default The default field parameter member values.
	 */
	protected $default_field = array(
		'type'      => 'checkbox',
		'data_args' => array(
			'sanitize_callback' => true,
			'show_in_rest'      => false,
			'type'              => 'string',
			'description'       => '',
		),
	);

	/**
	 * The allowed data arguments for configuration.
	 */
	protected $allowed_html_args = array(
		'class',
		'data-*',
		'disabled',
		'readonly',
		'required',
	);

	/**
	 * Allowed HTML.
	 *
	 * @var array $allowed_html The allowed HTML for the element produced by this class.
	 */
	protected $allowed_html = array(
		'input' => array(
			'checked'  => true,
			'class'    => true,
			'data-*'   => true,
			'disabled' => true,
			'id'       => true,
			'name'     => true,
			'readonly' => true,
			'required' => true,
			'type'     => 'checkbox',
			'value'    => true,
		),
		'label' => array(
			'for' => true,
		),
		'br'    => true,
	);

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/checkbox
	 *
	 * @param array $args The arguments needed to render the setting field.
	 *
	 * @return void
	 */
	public function output( $args ) {

		// Assemble the variables necessary to output the form field from settings.
		$value       = get_option( $args['id'] );
		$extra_attrs = $this->get_optional_attributes( $args );

		// Render the form field output.
		$output = array();
		foreach ( $args['choices'] as $choice_value => $choice_label ) {
			$checked = '';
			if ( $value && in_array( $choice_value, $value, true )
				|| is_string( $value ) && $value === $choice_value ) {
				$checked = 'checked ';
			}
			$output[] = sprintf(
				'<input type="checkbox" id="%1$s__%2$s" name="%1$s[]" value="%2$s" %3$s%4$s/> <label for="%1$s__%2$s" />%5$s</label>',
				esc_attr( $args['id'] ),
				$choice_value,
				$checked,
				$extra_attrs,
				$choice_label
			);

		}
		$output = implode( "<br />", $output );
		echo wp_kses( $output, $this->allowed_html );

		// Render the description text.
		$this->output_description( $args );

	}
}
