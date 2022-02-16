<?php
/**
 * The file that extends the Field class into a Select Field with multiselect support for the Settings API.
 *
 * @package    ThoughtfulWeb\SettingsPageWP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/settings-page-wp/blob/main/src/Settings/Field/Select.php
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
class Select extends Field {

	/**
	 * The default values for required $field members.
	 *
	 * @var array $default The default field parameter member values.
	 */
	protected $default_field = array(
		'type'      => 'select',
		'prompt'    => 'Please choose an option',
		'data_args' => array(
			'show_in_rest'      => false,
			'sanitize_callback' => true,
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
		'multiple',
		'size',
		'disabled',
	);

	/**
	 * Allowed HTML.
	 *
	 * @var array $allowed_html The allowed HTML for the element produced by this class.
	 */
	protected $allowed_html = array(
		'select' => array(
			'autocomplete' => true,
			'class'        => true,
			'data-*'       => true,
			'disabled'     => true,
			'multiple'     => true,
			'id'           => true,
			'name'         => true,
			'required'     => true,
			'size'         => true,
		),
		'option' => array(
			'disabled' => true,
			'label'    => true,
			'selected' => true,
			'value'    => true,
		),
		'label'  => array(
			'for' => true,
		),
		'br'     => true,
	);

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/select
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/option
	 *
	 * @param array $args The arguments needed to render the setting field.
	 *
	 * @return void
	 */
	public function output( $args ) {

		// Assemble the variables necessary to output the form field from settings.
		$value       = get_option( $args['id'] );
		$value_arr   = is_array( $value ) ? $value : array( $value );
		$extra_attrs = $this->get_optional_attributes( $args );
		$multi_mod   = array_key_exists( 'multiple', $args['data_args'] ) ? '[]' : '';

		// Render the form field output.
		$output   = array();
		$output[] = sprintf(
			'<select id="%1$s" name="%1$s%2$s" %3$s>',
			esc_attr( $args['id'] ),
			$multi_mod,
			$extra_attrs
		);
		if ( $args['prompt'] ) {
			$output[] = sprintf(
				'<option value="">%1$s</option>',
				$args['prompt']
			);
		}
		foreach ( $args['choices'] as $option_value => $option_text ) {
			$selected = '';
			if ( $value && in_array( $option_value, $value_arr, true ) ) {
				$selected = 'selected ';
			}
			$extra_attrs = $this->get_optional_attributes( $args );
			$output[]    = sprintf(
				'<option value="%1$s" %2$s%3$s/>%4$s</option>',
				$option_value,
				$selected,
				$extra_attrs,
				$option_text
			);

		}
		$output[] = '</select>';
		$output = implode( '', $output );
		echo wp_kses( $output, $this->allowed_html );

		// Render the description text.
		$this->output_description( $args );

	}
}
