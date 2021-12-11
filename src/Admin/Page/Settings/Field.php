<?php
/**
 * The file that extends WP_Error notification capabilities.
 *
 * @package    Thoughtful_Web\Library_WP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/page/settings/field.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace Thoughtful_Web\Library_WP\Admin\Page\Settings;

class Field {

	/**
	 * Field registration parameters.
	 * @var array $params The field registration parameters.
	 */
	private $params;

	/**
	 * HTML field to PHP variable type translation.
	 *
	 * @var array $type_assoc Field and variable type associations.
	 */
	private $type_assoc = array(
		'text'        => 'string',
		'textarea'    => 'string',
		'wysiwyg'     => 'string',
		'checkbox'    => 'boolean',
		'radio'       => 'array',
		'select'      => 'string',
		'multiselect' => 'array',
		'media'       => 'string',
		'email'       => 'string',
	);

	/**
	 * Simple sanitization functions.
	 *
	 * @var array $sanitizers Single sanitization functions for saving options to the database.
	 */
	private $sanitizers = array(
		'text'        => 'sanitize_text_field',
		'textarea'    => 'sanitize_textarea_field',
		'wysiwyg'     => 'wp_filter_post_kses',
		'checkbox'    => null,
		'radio'       => null,
		'select'      => null,
		'multiselect' => null,
		'media'       => null,
		'email'       => 'sanitize_email',
	);

	/**
	 * Constructor for the Field class.
	 *
	 * @param array $field {
	 *     The field registration arguments.
	 *
	 *     @type string $label       Formatted title of the field. Shown as the label for the field during output.
	 *     @type string $id          Slug-name to identify the field. Used in the 'id' attribute of tags.
	 *     @type string $type        The type attribute.
	 *     @type string $desc        The description.
	 *     @type mixed  $placeholder The placeholder text, if applicable.
	 *     @type mixed  $label_for   When supplied, the setting title will be wrapped in a `<label>` element, its `for` attribute populated with this value.
	 *     @type mixed  $class       CSS Class to be added to the `<tr>` element when the field is output.
	 *     @type array  $data_args {
	 *         Data used to describe the setting when registered.
	 *
	 *         @type string     $type              The type of data associated with this setting.
	 *                                             Valid values are 'string', 'boolean', 'integer',
	 *                                             'number', 'array', and 'object'.
	 *         @type string     $description       A description of the data attached to this setting.
	 *         @type callable   $sanitize_callback A callback function that sanitizes the option's value.
	 *         @type bool|array $show_in_rest      Whether data associated with this setting should be
	 *                                             included in the REST API. When registering complex
	 *                                             settings, this argument may optionally be an array
	 *                                             with a 'schema' key.
	 *         @type mixed      $default           Default value when calling `get_option()`.
	 *     }
	 * }
	 * @param callable $callback Function that fills the field with the desired form inputs. The function should echo its output. Callable. Required.
	 * @param string   $page     The slug-name of the settings page on which to show the section (general, reading, writing, ...).
	 * @param string   $section  The slug-name of the section of the settings page in which to show the box.
	 */
	public function __construct( $field, $page, $section ) {

		// Assign sanitization filters defined in this class but unable to be defined during build time.
		$this->sanitizers['checkbox']    = array( $this, 'sanitize_booleanish' );
		$this->sanitizers['radio']       = array( $this, 'sanitize_choices' );
		$this->sanitizers['select']      = array( $this, 'sanitize_choices' );
		$this->sanitizers['multiselect'] = array( $this, 'sanitize_choices' );
		$this->sanitizers['media']       = array( $this, 'sanitize_file_name' );

		$params = $this->compile_settings_params( $field, $page );

		// Store the compiled registration parameters.
		$this->params = array(
			'field'         => $field,
			'page'          => $page,
			'section'       => $section,
			'option_group'  => $params['option_group'],
			'option_name'   => $field['id'],
			'settings_args' => $params['args'],
		);

		// Register the settings field output.
		add_settings_field( $field['id'], $field['label'], array( $this, 'field_output_callback' ), $page, $section, $field );

		// Register the settings field database entry.
		register_setting( $params['option_group'], $field['id'], $params['args'] );

	}

	/**
	 * Compile the settings arguments.
	 *
	 * @since 0.1.0
	 *
	 * @param array  $field The field arguments.
	 * @param string $page  The page slug.
	 *
	 * @return array
	 */
	private function compile_settings_params( $field, $page ) {

		$results = array();

		// Register the database settings field.
		$option_group = sanitize_key( $page );
		$option_group = str_replace('-', '_');

		// Known blacklist of database option names.
		$blacklist = array( 'privacy', 'misc' );
		if ( in_array( $option_group, $blacklist, true ) ) {
			$option_group .= '_option';
		}
		$results['option_group'] = $option_group;

		// Assemble the remaining data registration arguments.
		$args = array(
			'type'              => $this->type_assoc[ $field['type'] ],
			'description'       => null,
			'sanitize_callback' => array( $this, $this->sanitizers[ $field['type'] ] ),
		);
		if ( array_key_exists( 'data_args', $field ) && array_key_exists( 'default', $field['data_args'] ) ) {
			$args['default'] = $field['data_args']['default'];
		}
		// This library does not fully support REST access, but some may choose to try using it and I don't want to stop them.
		if ( array_key_exists( 'data_args', $field ) && array_key_exists( 'show_in_rest', $field['data_args'] ) ) {
			$args['show_in_rest'] = $field['data_args']['show_in_rest'];
		}

		// Return the compiled arguments.
		$results['args'] = $args;

		return $results;

	}

	/**
	 * The field HTML rendering callback function.
	 *
	 * @param array $field The field arguments.
	 *
	 * @return void
	 */
	public function field_output_callback( $field ) {

		$value = get_option( $field['id'] );
		$placeholder = '';
		if ( isset( $field['placeholder'] ) ) {
			$placeholder = $field['placeholder'];
		}
		switch ( $field['type'] ) {

			case 'text':
			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$placeholder,
					$value
				);
		}

	}

	/**
	 * Sanitizes a boolean option value.
	 *
	 * @param string $values         The unsanitized option value.
	 * @param string $option         The option name.
	 * @param string $original_value The original value passed to the function.
	 *
	 * @return string
	 */
	public function sanitize_booleanish( $values, $option, $original_value ) {

		// Truthy values in a format we interpret later.
		// Setting array keys to non-string values can have unintended effects.
		$results  = array( false );
		$bool_map = array(
			'^true' => false,
			'true'  => 'false',
			'TRUE'  => 'FALSE',
			'yes'   => 'no',
			'YES'   => 'NO',
			'^1'    => 0,
			'1'     => '0'
		);

		// Assume values might be an array, sometimes.
		// If the value was not an array remember to restore it to a non-array value at the end.
		$was_array = true;
		if ( ! is_array( $values ) ) {
			$was_array = false;
			$values = array( $values );
		}

		// Check each value for presence in the truthy array.
		foreach ( $values as $value ) {
			// Convert the value to a string but remember if it was not a string.
			$ovalue = $value;
			$value  = is_string( $value ) ? $value : '^' . strval( $value );
			if ( array_key_exists( $value, $bool_map ) ) {
				$results[] = $ovalue;
			} else {
				$results[] = 'no';
			}
		}

		// Restore non-array state if necessary.
		if ( ! $was_array ) {
			$results = $results[0];
		}

		return $results;

	}

	/**
	 * Sanitize title array.
	 *
	 * @since 0.1.0
	 *
	 * @param string $value          The unsanitized option value.
	 * @param string $option         The option name.
	 * @param string $original_value The original value passed to the function.
	 *
	 * @return string
	 */
	public function sanitize_choices( $value ) {

		// The valid choices.
		$choices = $this->params['field']['choices'];
		$value   = sanitize_title( $value );
		if ( ! array_key_exists( $value, $choices ) ) {
			return '';
		}

		return $value;

	}

	/**
	 * Sanitize and validate media upload's file name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $value The media file's URL.
	 *
	 * @return void
	 */
	public function sanitize_file_name( $value ) {

		$value = sanitize_file_name( $value );
		if ( ! $value ) {
			return;
		}

		$parsed = parse_url( $value );
		$valid  = parse_url( get_admin_url() );
		if (
			$parsed['scheme'] === $valid['scheme']
			&& $parsed['host'] === $valid['host']
		) {
			return $value;
		}
	}
}
