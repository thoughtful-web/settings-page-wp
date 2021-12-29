<?php
/**
 * The file that extends the Field class into a Text Field for the Settings API.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Field
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/page/settings/field/text.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Field;

use \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Field;

/**
 * The Text Field class.
 *
 * @since 0.1.0
 */
class Text extends Field {

	/**
	 * The default values for required $field members.
	 *
	 * @var array $default The default field parameter member values.
	 */
	protected $default_field = array(
		'type'        => 'text',
		'desc'        => '',
		'placeholder' => '',
		'data_args'   => array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => false,
			'type'              => 'string',
			'description'       => '',
		),
	);

	/**
	 * Allowed HTML.
	 *
	 * @var array $allowed_html The allowed HTML for the element produced by this class.
	 */
	protected $allowed_html = array(
		'input' => array(
			'class'        => true,
			'data-*'       => true,
			'autocomplete' => true,
			'disabled'     => true,
			'id'           => true,
			'list'         => true,
			'maxlength'    => true,
			'minlength'    => true,
			'name'         => true,
			'pattern'      => true,
			'placeholder'  => true,
			'readonly'     => true,
			'required'     => true,
			'size'         => true,
			'spellcheck'   => true,
			'type'         => 'text',
			'value'        => true,
		),
	);

	/**
	 * Sanitize the text field value.
	 *
	 * @param string $value The unsanitized option value.
	 *
	 * @return string
	 */
	public function sanitize( $value ) {

		$value = sanitize_text_field( $value );
		if ( empty( $value ) ) {
			$value = get_site_option( $this->option_group, $this->field['data_args']['default'] );
		}

		return $value;

	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/text
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
		$output = sprintf(
			'<input type="text" id="%1$s" name="%2$s" value="%3$s" %4$s/>',
			esc_attr( $args['id'] ),
			esc_attr( $args['data_args']['label_for'] ),
			esc_attr( $value ),
			$extra_attrs
		);
		echo wp_kses( $output, $this->allowed_html );

		// Render the description text.
		$this->output_description( $args );

	}
}
