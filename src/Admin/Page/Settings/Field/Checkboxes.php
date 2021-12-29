<?php
/**
 * The file that extends the Field class into a Checkbox Field with multiple checkboxes for the Settings API.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/page/settings/field/checkboxes.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Field;

use \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Field;

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
		'type'        => 'checkbox',
		'desc'        => '',
		'placeholder' => '',
		'data_args'   => array(
			'default'      => array(),
			'show_in_rest' => false,
			'type'         => 'string',
			'description'  => '',
		),
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
	 * Sanitize the text field value.
	 *
	 * @param array $value The unsanitized option value.
	 *
	 * @return array
	 */
	public function sanitize( $value ) {

		// Get the predefined choices from the configuration variable.
		$config_choices = array_keys( $this->field['choices'] );
		$final_choices  = array();
		foreach ( $value as $key => $choice ) {
			// If the choice value is present in the configuration, continue.
			if ( in_array( $choice, $config_choices, true ) ) {
				// Ensure the choice can only be passed once.
				$final_choices[ $choice ] = $choice;
				continue;
			} else {
				// Value is falsified.
				// Get the default choice values.
				$default = isset( $this->field['data_args']['default'] ) ? $this->field['data_args']['default'] : array();
				// Get the database choices and fall back to the default configured value.
				$final_choices = get_site_option( $this->option_group, $default );
				break;
			}
		}

		return $final_choices;

	}

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
		$value       = get_site_option( $args['id'], $args['data_args']['default'] );
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
		$output = implode( '<br />', $output );
		echo wp_kses( $output, $this->allowed_html );

		// Render the description text.
		$this->output_description( $args );

	}
}
