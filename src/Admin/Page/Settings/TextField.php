<?php
/**
 * The file that wraps the WordPress Settings API in a file-configurable framework.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/page/settings/field/textfield.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Admin\Page\Settings;

/**
 * The TextField class.
 *
 * @since 0.1.0
 */
class TextField {

	/**
	 * The default values for required $field members.
	 *
	 * @var array $default The default field parameter member values.
	 */
	private $default_field = array(
		'type'        => 'text',
		'desc'        => '',
		'placeholder' => '',
		'data_args'   => array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => false,
			'type'              => 'string',
			'description'       => '',
		)
	);

	/**
	 * Stored field value.
	 *
	 * @var array $field The registered field arguments.
	 */
	private $field;

	/**
	 * The option group variable.
	 *
	 * @var string $option_group The option group identifier.
	 */
	private $option_group;

	/**
	 * Constructor for the Field class.
	 *
	 * @param array $field {
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
	 * @param string $option_group The option group slug.
	 * @param bool   $network      Whether the plugin is activated at the network level or not.
	 */
	public function __construct( $field, $menu_slug, $section_id, $option_group, $network ) {

		$this->option_group = $option_group;
		$this->network      = $network;
		$this->default_field['data_args']['sanitize_callback'] = array( $this, 'sanitize' );

		// Merge user-defined field values with default values.
		foreach ( $this->default_field as $key => $default_value ) {
			if ( 'data_args' === $key ) {
				foreach( $default_value as $data_key => $default_data_value ) {
					if ( ! array_key_exists( $data_key, $field[ $key ] ) ) {
						$field[ $key ][ $data_key ] = $default_data_value;
					}
				}
			} elseif ( ! array_key_exists( $key, $field ) ) {
				$field[ $key ] = $default_value;
			}
		}

		// Assign the new merged field value.
		$this->field = $field;

		// Register the settings field output.
		add_settings_field(
			$field['id'],
			$field['label'],
			array( $this, 'output' ),
			$menu_slug,
			$section_id,
			$field
		);

	}

	/**
	 * Sanitizes a text field string.
	 *
	 * @param string $value          The unsanitized option value.
	 * @param string $option         The option name.
	 * @param string $original_value The original value passed to the function.
	 *
	 * @return string
	 */
	public function sanitize( $value, $option, $original_value ) {

		$default_value = '';
		$value         = sanitize_text_field( $value );
		if ( ! $value ) {
			if (
				isset( $this->field['data_args'] )
				&& isset( $this->field['data_args']['default'] )
			) {
				$default_value = $this->field['data_args']['default'];
			}

			$value = get_site_option( $option, $default_value );
		}

		return $value;

	}

	/**
	* Get the settings option array and print one of its values.
	*
	* @param array $args The arguments needed to render the setting field.
	*
	* @return void
	*/
	public function output( $args ) {

		// Assemble the variables necessary to output the form field from settings.
		$data_args     = $args['data_args'];
		$default_value = $this->field['data_args']['default'];
		$placeholder   = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
		$option        = get_site_option( $this->option_group );
		$value         = isset( $option[ $data_args['label_for'] ] ) ? $option[ $data_args['label_for'] ] : $default_value;
		$allowed_html  = array(
			'input' => array(
				'type' => 'text',
				'id'   => true,
				'name' => true,
				'class' => true,
				'data-lpignore' => true,
				'size' => true,
				'placeholder' => true,
				'value' => true,
			),
		);

		// Render the form field output.
		$output = sprintf(
			'<input type="text" id="%1$s" name="%2$s[%1$s]" class="settings-text" data-lpignore="true" size="40" placeholder="%3$s" value="%4$s" />',
			esc_attr( $data_args['label_for'] ),
			$this->option_group,
			$placeholder,
			$value,
		);
		echo wp_kses( $output, $allowed_html );

		// Render the description text.
		if ( isset( $data_args['description'] ) && $data_args['description'] ) {
			echo wp_kses_post( "<p class=\"description\">{$data_args['description']}</p>" );
		}

	}
}
