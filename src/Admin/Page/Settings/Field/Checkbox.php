<?php
/**
 * The file that wraps the WordPress Settings API in a file-configurable framework.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Field
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/page/settings/field/textareafield.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Field;

/**
 * The TextField class.
 *
 * @since 0.1.0
 */
class Checkbox {

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
	 * @param string $value The unsanitized option value.
	 *
	 * @return string
	 */
	public function sanitize( $value ) {

		// If the choice value is present in the configuration and it is not a configured choice then it is a falsified choice.
		if ( ! empty( $value ) && $value !== $this->field['choice'] ) {
			// Value is falsified.
			// Get the default choice values.
			$default = isset( $this->field['data_args']['default'] ) ? array_keys( $this->field['data_args']['default'] ) : array();
			// Get the database choice and fall back to the default configured value.
			$value = get_site_option( $this->option_group, $default );
		}

		return $value;

	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea
	 *
	 * @param array $args The arguments needed to render the setting field.
	 *
	 * @return void
	 */
	public function output( $args ) {

		// Assemble the variables necessary to output the form field from settings.
		$value       = get_site_option( $args['id'], array_keys( $args['data_args']['default'] ) );
		$extra_attrs = $this->get_optional_attributes( $args );

		// Render the form field output.
		$output = array();
		foreach ( $args['choice'] as $choice_value => $choice_label ) {
			$checked = '';
			if ( $value && in_array( $choice_value, $value, true ) ) {
				$checked = 'checked ';
			} elseif ( is_string( $value ) && $value === $choice_value ) {
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
