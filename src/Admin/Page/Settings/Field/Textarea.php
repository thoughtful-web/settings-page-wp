<?php
/**
 * The file that extends the Field constructor into a Textarea Field for the Settings API.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Field
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/page/settings/field/textarea.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Field;

use \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Field;

/**
 * The TextField class.
 *
 * @since 0.1.0
 */
class Textarea extends Field {

	/**
	 * The default values for required $field members.
	 *
	 * @var array $default The default field parameter member values.
	 */
	protected $default_field = array(
		'type'        => 'textarea',
		'desc'        => '',
		'placeholder' => '',
		'data_args'   => array(
			'default'           => '',
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_textarea_field',
			'show_in_rest'      => false,
			'description'       => '',
		),
	);

	/**
	 * Allowed HTML.
	 *
	 * @var array $allowed_html The allowed HTML for the element produced by this class.
	 */
	protected $allowed_html = array(
		'textarea' => array(
			'class'        => true,
			'cols'         => true,
			'data-*'       => true,
			'disabled'     => true,
			'autocomplete' => true,
			'disabled'     => true,
			'id'           => true,
			'maxlength'    => true,
			'minlength'    => true,
			'name'         => true,
			'placeholder'  => true,
			'readonly'     => true,
			'required'     => true,
			'rows'         => true,
			'spellcheck'   => true,
			'wrap'         => true,
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

		$value = sanitize_textarea_field( $value );
		if ( empty( $value ) ) {
			$value = get_site_option( $this->option_group, $this->field['data_args']['default'] );
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
		$value       = get_site_option( $args['id'], $args['data_args']['default'] );
		$extra_attrs = $this->get_optional_attributes( $args );

		// Render the form field output.
		$output = sprintf(
			'<textarea id="%1$s" name="%2$s" %4$s>%3$s</textarea>',
			esc_attr( $args['id'] ),
			esc_attr( $args['data_args']['label_for'] ),
			esc_textarea( $value ),
			$extra_attrs
		);
		echo wp_kses( $output, $this->allowed_html );

		// Render the description text.
		$this->output_description( $args );

	}
}
