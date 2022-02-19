<?php
/**
 * The file that extends the Field class into a Checkbox Field for the Settings API.
 *
 * @package    ThoughtfulWeb\SettingsPageWP
 * @subpackage Field
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/settings-page-wp/blob/main/src/Settings/Field/Checkbox.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\SettingsPageWP\Settings\Field;

use \ThoughtfulWeb\SettingsPageWP\Settings\Field;

/**
 * The Checkbox Field class.
 *
 * @since 0.1.0
 */
class Checkbox extends Field {

	/**
	 * The default values for required $field members.
	 *
	 * @var array $default The default field parameter member values.
	 */
	protected $default_field = array(
		'type'        => 'checkbox',
		'data_args'   => array(
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
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea
	 *
	 * @param array $args The arguments needed to render the setting field.
	 *
	 * @return void
	 */
	public function output( $args ) {

		// Assemble the variables necessary to output the form field from settings.
		$value            = get_option( $args['id'] );
		$value_if_checked = strval( array_key_first( $args['choice'] ) );
		$extra_attrs      = $this->get_optional_attributes( $args );

		// Render the form field output.
		$output  = array();
		$checked = '';
		if ( $value === $value_if_checked ) {
			$checked = 'checked ';
		}
		// Render the output.
		$output = sprintf(
			'<input type="checkbox" id="%1$s" name="%1$s" value="%2$s" %3$s%4$s/> <label for="%1$s" />%5$s</label>',
			esc_attr( $args['id'] ),
			esc_attr( strval( array_key_first( $args['choice'] ) ) ),
			$checked,
			$extra_attrs,
			array_values( $args['choice'] )[0]
		);
		echo wp_kses( $output, $this->allowed_html );

		// Render the description text.
		$this->output_description( $args );

	}
}
