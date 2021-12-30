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
class Color extends Field {

	/**
	 * The default values for required $field members.
	 *
	 * @var array $default The default field parameter member values.
	 */
	protected $default_field = array(
		'type'        => 'color',
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
			'class'       => true,
			'data-*'      => true,
			'disabled'    => true,
			'id'          => true,
			'list'        => true,
			'name'        => true,
			'placeholder' => true,
			'readonly'    => true,
			'required'    => true,
			'size'        => true,
			'type'        => 'text',
			'value'       => true,
		),
	);

	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	public function __construct() {

		// Call the Field::construct() method.
		parent::__construct();

		// Queue the color picker scripts if they aren't already.
		if ( ! wp_script_is( 'wp-color-picker', 'queue' ) || ! wp_style_is( 'wp-color-picker', 'queue' ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_color_picker_js' ) );
		}

	}

	/**
	 * Enqueue the WordPress Color Picker plugin files.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function enqueue_color_picker_js() {

		if ( ! wp_script_is( 'wp-color-picker', 'queue' ) ) {

			wp_enqueue_script( 'wp-color-picker' );
			wp_add_inline_script( 'wp-color-picker', "jQuery('input[data-wp-color-picker]').wpColorPicker({
				width:400,
				change: function(event, ui) {
					var label = jQuery(event.target).closest('.wp-picker-container').find('.wp-color-result-text');
					label.html( 'Select Color: ' + ui.color.toString() );
				}});" );

		}

		if ( ! wp_style_is( 'wp-color-picker', 'queue' ) ) {

			wp_enqueue_style( 'wp-color-picker' );

		}

	}

	/**
	 * Sanitize the text field value.
	 *
	 * @param string $value The unsanitized option value.
	 *
	 * @return string
	 */
	public function sanitize( $value ) {

		$original_value = $value;
		$value          = sanitize_text_field( $value );
		if ( $value !== $original_value ) {
			$value = get_site_option( $this->field['id'], $this->field['data_args']['default'] );
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
			'<input type="text" data-wp-color-picker id="%1$s" name="%2$s" value="%3$s" %4$s/>',
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
