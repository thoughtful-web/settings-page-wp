<?php
/**
 * The file that extends the Field class into a Select Field with multiselect support for the Settings API.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/page/settings/field/select.php
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
class Select extends Field {

	/**
	 * The default values for required $field members.
	 *
	 * @var array $default The default field parameter member values.
	 */
	protected $default_field = array(
		'type'      => 'select',
		'desc'      => '',
		'prompt'    => 'Please choose an option',
		'data_args' => array(
			'default'           => '',
			'show_in_rest'      => false,
			'sanitize_callback' => true,
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
	 * Constructor for the Color Field class.
	 *
	 * @param array  $field {
	 *     The field registration arguments.
	 *
	 *     @type string $label       Formatted title of the field. Shown as the label for the field during output. Required.
	 *     @type string $id          Slug-name to identify the field. Used in the 'id' attribute of tags. Required.
	 *     @type string $type        The type attribute. Required.
	 *     @type string $desc        The description. Optional.
	 *     @type mixed  $placeholder The placeholder text, if applicable. Optional.
	 *     @type string $default     The default value. Optional.
	 *     @type mixed  $label_for   When supplied, the setting title will be wrapped in a `<label>` element, its `for` attribute populated with this value. Optional.
	 *     @type mixed  $class       CSS Class to be added to the `<tr>` element when the field is output. Optional.
	 *     @type array  $data_args {
	 *         Data used to describe the setting when registered. Required.
	 *
	 *         @type string     $option_name       The option name. If not provided, will default to the ID attribute of the HTML element. Optional.
	 *         @type mixed      $default           Default value when calling `get_option()`. Optional.
	 *         @type callable   $sanitize_callback A callback function that sanitizes the option's value. Optional.
	 *         @type bool|array $show_in_rest      Whether data associated with this setting should be included in the REST API. When registering complex settings, this argument may optionally be an array with a 'schema' key.
	 *         @type string     $type              The type of data associated with this setting. Only used for the REST API. Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'.
	 *         @type string     $description       A description of the data attached to this setting. Only used for the REST API.
	 *     }
	 * }
	 * @param string $menu_slug         The slug-name of the settings page on which to show the section (general, reading, writing, ...).
	 * @param string $section_id   The slug-name of the section of the settings page in which to show the box.
	 * @param string $option_group Name the group of database options which the fields represent.
	 */
	public function __construct( $field, $menu_slug, $section_id, $option_group ) {

		// Call the Field::construct() method.
		parent::__construct( $field, $menu_slug, $section_id, $option_group );

		// Ensure the correct default is present.
		if (
			array_key_exists( 'multiple', $this->field['data_args'] )
			&& array_key_exists( 'default', $this->field['data_args'] )
			&& ! is_array( $this->field['data_args']['default'] )
		) {
			$this->field['data_args']['default'] = array( $this->field['data_args']['default'] );
		}

	}

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
