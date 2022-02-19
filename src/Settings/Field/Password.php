<?php
/**
 * The file that extends the Field class into a Phone Field for the Settings API.
 *
 * @package    ThoughtfulWeb\SettingsPageWP
 * @subpackage Field
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/settings-page-wp/blob/main/src/Settings/Field/Password.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\SettingsPageWP\Settings\Field;

use \ThoughtfulWeb\SettingsPageWP\Settings\Field;

/**
 * The Phone Field class.
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/url
 *
 * @since 0.1.0
 */
class Password extends Field {

	/**
	 * The default values for required $field members.
	 *
	 * @var array $default The default field parameter member values.
	 */
	protected $default_field = array(
		'type'        => 'password',
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
		'readonly',
		'required',
		'size',
		'list',
		'placeholder',
		'pattern',
		'disabled',
		'inputmode',
	);

	/**
	 * Allowed HTML.
	 *
	 * @var array $allowed_html The allowed HTML for the element produced by this class.
	 */
	protected $allowed_html = array(
		'input' => array(
			'autocomplete' => true,
			'inputmode'    => true,
			'class'        => true,
			'data-*'       => true,
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
			'type'         => 'password',
			'value'        => true,
		),
	);

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/password
	 *
	 * @param array $args The arguments needed to render the setting field.
	 *
	 * @return void
	 */
	public function output( $args ) {

		// Assemble the variables necessary to output the form field from settings.
		$value = get_option( $args['id'] );
		if ( empty( $value ) && array_key_exists( 'default', $args['data_args'] ) ) {
			$value = $args['data_args']['default'];
		}
		$extra_attrs = $this->get_optional_attributes( $args );

		// Render the form field output.
		$output = sprintf(
			'<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" %5$s/>',
			esc_attr( $args['type'] ),
			esc_attr( $args['id'] ),
			esc_attr( $args['data_args']['label_for'] ),
			esc_attr( $value ),
			$extra_attrs
		);
		echo wp_kses( $output, $this->allowed_html );

		// Render the "copy to clipboard" button.
		if ( array_key_exists( 'copy_button', $args['data_args'] ) ) {
			if ( empty( $args['data_args']['copy_button'] ) ) {
				$args['data_args']['copy_button'] = 'Copy';
			}
			$allowed_html = array(
				'input' => array(
					'type'    => 'button',
					'value'   => true,
					'onclick' => true,
					'onblur'  => true,
				),
			);
			$output = sprintf(
				'<input type="button" value="%1$s" onclick="navigator.clipboard.writeText(this.previousSibling.value);this.value=\'Copied\';" onblur="this.value=\'%1$s\';" />',
				esc_attr( $args['data_args']['copy_button'] )
			);
			echo wp_kses( $output, $allowed_html );
		}

		// Render the description text.
		$this->output_description( $args );

	}
}
