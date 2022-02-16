<?php
/**
 * The file that extends the Field class into a WP Editor Field for the Settings API.
 *
 * @package    ThoughtfulWeb\SettingsPageWP
 * @subpackage Field
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/settings-page-wp/blob/main/src/Settings/Field/WP_Editor.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\SettingsPageWP\Settings\Field;

use \ThoughtfulWeb\SettingsPageWP\Settings\Field;

/**
 * The WP_Editor Field class.
 *
 * @since 0.1.0
 */
class WP_Editor extends Field {

	/**
	 * The default values for required $field members.
	 *
	 * @var array $default The default field parameter member values.
	 */
	protected $default_field = array(
		'type'      => 'wp_editor',
		'data_args' => array(
			'type'              => 'string',
			'sanitize_callback' => true,
			'show_in_rest'      => false,
			'description'       => '',
		),
	);

	/**
	 * The allowed data arguments for configuration.
	 */
	protected $allowed_html_args = array();

	/**
	 * Allowed HTML. Defined during construction.
	 *
	 * @var array $allowed_html The allowed HTML for the element produced by this class.
	 */
	protected $allowed_html;

	/**
	 * Constructor for the WP_Editor Field class.
	 *
	 * @param array  $field {
	 *     The field registration arguments.
	 *
	 *     @type string $label       Formatted title of the field. Shown as the label for the field during output. Required.
	 *     @type string $id          Slug-name to identify the field. Used in the 'id' attribute of tags. Required.
	 *     @type string $type        The type attribute. Required.
	 *     @type string $desc        The description. Optional.
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
	 * @param string $capability   The capability needed to update the option.
	 */
	public function __construct( $field, $menu_slug, $section_id, $option_group, $capability ) {

		// Call the Field::construct() method.
		parent::__construct( $field, $menu_slug, $section_id, $option_group, $capability );

		// Define the allowed HTML.
		$this->allowed_html = wp_kses_allowed_html( 'post' );

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
		$value     = get_option( $args['id'] );
		$content   = stripslashes( $value );
		$editor_id = $args['id'];
		$settings  = array(
			'textarea_name' => $args['id'],
		);

		// Render the form field output.
		$settings_default = array(
			'tinymce'        => array(
				'toolbar1'                     => 'formatselect,bold,italic,underline,bullist,numlist,blockquote,hr,separator,alignleft,aligncenter,alignright,alignjustify,indent,outdent,charmap,link,unlink,undo,redo,fullscreen,wp_help',
				'toolbar2'                     => '',
				'paste_remove_styles'          => true,
				'paste_remove_spans'           => true,
				'paste_strip_class_attributes' => 'all',
			),
			'default_editor' => '',
			'textarea_rows'  => 10,
			'editor_css'     => '<style>body{background-color:#FFF;}</style>',
		);
		$settings         = array_merge( $settings_default, $settings );

		wp_editor( $content, $editor_id, $settings );

		// Render the description text.
		$this->output_description( $args );

	}
}
