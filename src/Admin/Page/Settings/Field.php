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
		'wp_editor'   => 'string',
		'checkbox'    => 'boolean',
		'radio'       => 'array',
		'select'      => 'string',
		'multiselect' => 'array',
		'media'       => 'string',
		'email'       => 'string',
	);

	/**
	 * Simple sanitization functions.
	 * Null values are set at runtime.
	 *
	 * @var array $sanitize Single sanitization functions for saving options to the database.
	 */
	private $sanitize = array(
		'text'        => 'sanitize_text_field',
		'textarea'    => 'sanitize_textarea_field',
		'wp_editor'   => 'wp_filter_post_kses',
		'email'       => 'sanitize_email',
		'checkbox'    => null,
		'radio'       => null,
		'select'      => null,
		'multiselect' => null,
		'media'       => null,
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
	 *         @type mixed      $default           Default value when calling `get_option()`. Optional.
	 *         @type callable   $sanitize_callback A callback function that sanitizes the option's value. Optional.
	 *         @type bool|array $show_in_rest      Whether data associated with this setting should be included in the REST API. When registering complex settings, this argument may optionally be an array with a 'schema' key.
	 *         @type string     $type              The type of data associated with this setting. Only used for the REST API. Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'.
	 *         @type string     $description       A description of the data attached to this setting. Only used for the REST API.
	 *     }
	 * }
	 * @param callable $callback Function that fills the field with the desired form inputs. The function should echo its output. Callable. Required.
	 * @param string   $page     The slug-name of the settings page on which to show the section (general, reading, writing, ...).
	 * @param string   $section  The slug-name of the section of the settings page in which to show the box.
	 */
	public function __construct( $field, $page, $section ) {

		// Assign sanitization filters defined in this class but unable to be defined during build time.
		$this->sanitize['checkbox']    = array( $this, 'sanitize_booleanish' );
		$this->sanitize['radio']       = array( $this, 'sanitize_choices' );
		$this->sanitize['select']      = array( $this, 'sanitize_choices' );
		$this->sanitize['multiselect'] = array( $this, 'sanitize_choices' );
		$this->sanitize['media']       = array( $this, 'sanitize_file_name' );

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
		$option_group = str_replace( '-', '_', sanitize_key( $page ) );

		// Known blacklist of database option names.
		if ( in_array( $option_group, array( 'privacy', 'misc' ), true ) ) {
			$option_group .= '_option';
		}
		$results['option_group'] = $option_group;

		// Assemble the remaining data registration arguments.
		$args = array(
			'type'              => $this->type_assoc[ $field['type'] ],
			'description'       => null,
			'sanitize_callback' => array( $this, $this->sanitize[ $field['type'] ] ),
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

		$output = '';

		switch ( $field['type'] ) {
			case 'checkbox':

				break;
			case 'text':
			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$placeholder,
					$value
				);
				break;
		}

	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @param array $args The arguments needed to render the setting field.
	 *
	 * @return void
	 */
	public function checkbox_field( $args ) {

		$option_name   = $args['option_name'];
		$field_name    = $args['field_name'];
		$default_value = $this->default_option[ $field_name ];
		$option        = get_site_option( $option_name );
		$is_checked    = isset( $option[ $field_name ] ) ? $option[ $field_name ] : $default_value;
		$checked       = 'on' === $is_checked ? ' checked' : '';
		echo "<input type=\"checkbox\" name=\"{$option_name}[{$field_name}]\" id=\"{$option_name}[{$field_name}]\" class=\"settings-checkbox\"{$checked} />";

	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @param array $args The arguments needed to render the setting field.
	 *
	 * @return void
	 */
	public function text_field( $args ) {

		$option_name   = $args['option_name'];
		$field_name    = $args['field_name'];
		$default_value = $this->default_option[ $field_name ];
		$option        = get_site_option( $option_name );
		$value         = isset( $option[ $field_name ] ) ? $option[ $field_name ] : $default_value;
		echo "<input type=\"text\" name=\"{$option_name}[{$field_name}]\" id=\"{$option_name}[{$field_name}]\" class=\"settings-text\" value=\"{$value}\" data-lpignore=\"true\" size=\"40\" />";
		if ( isset( $args['after'] ) ) {
			echo $args['after'];
		}

	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @param array $args The arguments needed to render the setting field.
	 *
	 * @return void
	 */
	public function textarea_field( $args ) {

		$option_name   = $args['option_name'];
		$field_name    = $args['field_name'];
		$default_value = $this->default_option[ $field_name ];
		$option        = get_site_option( $option_name );
		$value         = isset( $option[ $field_name ] ) ? $option[ $field_name ] : $default_value;
		echo "<textarea name=\"{$option_name}[{$field_name}]\" id=\"{$option_name}[{$field_name}]\" class=\"settings-textarea\" rows=\"5\">{$value}</textarea>";

	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @param array $args The arguments needed to render the setting field.
	 *
	 * @return void
	 */
	public function wp_editor_field( $args ) {

		$option_name   = $args['option_name'];
		$field_name    = $args['field_name'];
		$default_value = $this->default_option[ $field_name ];
		$editor_args   = array(
			'textarea_name' => "{$option_name}[{$field_name}]",
			'tinymce'       => array(
				'toolbar1' => 'formatselect,bold,italic,underline,bullist,numlist,blockquote,hr,separator,alignleft,aligncenter,alignright,alignjustify,indent,outdent,charmap,link,unlink,undo,redo,fullscreen,wp_help',
				'toolbar2' => '',
				'paste_remove_styles' => true,
				'paste_remove_spans' => true,
				'paste_strip_class_attributes' => 'all',
				'content_css' => '',
			),
			'default_editor' => '',
		);
		if ( isset( $args['editor_args'] ) ) {
			$editor_args = array_merge( $editor_args, $args['editor_args'] );
		}

		$option  = get_site_option( $option_name );
		$content = isset( $option[ $field_name ] ) && $option[ $field_name ] ? $option[ $field_name ] : $default_value;
		$content = stripslashes( $content );

		add_filter( 'quicktags_settings', function( $qtInit ){ $qtInit['buttons'] = ','; return $qtInit; });
		wp_editor( $content, $field_name, $editor_args );

	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @param array $args The arguments needed to render the setting field.
	 *
	 * @return void
	 */
	public function number_field( $args ) {

		$option_name   = $args['option_name'];
		$field_name    = $args['field_name'];
		$default_value = $this->default_option[ $field_name ];
		$option        = get_site_option( $option_name );
		$value         = isset( $option[ $field_name ] ) ? $option[ $field_name ] : $default_value;
		echo "<input type=\"number\" min=\"1\" name=\"{$option_name}[{$field_name}]\" id=\"{$option_name}[{$field_name}]\" class=\"settings-number\" value=\"{$value}\" data-lpignore=\"true\" />";
		if ( isset( $args['after'] ) ) {
			echo $args['after'];
		}

	}
}
