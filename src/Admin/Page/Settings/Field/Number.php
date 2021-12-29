<?php
/**
 * The file that wraps the WordPress Settings API in a file-configurable framework.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Field
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2021 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/page/settings/field/number.php
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
class Number extends Field {

	/**
	 * The default values for required $field members.
	 *
	 * @var array $default The default field parameter member values.
	 */
	protected $default_field = array(
		'type'        => 'number',
		'desc'        => '',
		'placeholder' => '',
		'data_args'   => array(
			'default'           => '',
			'sanitize_callback' => 'floatval',
			'show_in_rest'      => false,
			'type'              => 'string',
			'description'       => '',
			'step'              => false,
			'min'               => false,
			'max'               => false,
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
			'max'          => true,
			'min'          => true,
			'name'         => true,
			'placeholder'  => true,
			'readonly'     => true,
			'required'     => true,
			'step'         => true,
			'type'         => 'number',
			'value'        => true,
		),
	);

	/**
	 * Sanitize the field value.
	 * Validates the value against the following conditions:
	 * 1. Is numeric.
	 * 2. Is no greater than 16mb in string length.
	 * 3. Is a float or integer.
	 * 4. Is constrained to the configured minimum and maximum values.
	 * 5. Is constrained to the step configuration.
	 *
	 * @param string $value The unsanitized option value.
	 *
	 * @return string
	 */
	public function sanitize( $value ) {

		// Assume valid and detect invalid scenarios.
		$valid = true;
		// Remove surrounding whitespace.
		$value = trim( $value );
		// Store original value for comparison.
		$original_value = $value;
		// Detect invalid circumstances and then fall back to the previous value.
		if ( ! is_numeric( $value ) ) {
			// The easiest rejection to make.
			$valid = false;
		} elseif ( strlen( $value ) > 16777216 ) {
			// Max length of a transactional string set by most web hosts for SQL is 16mb.
			$valid = false;
		} else {
			// Ensure the number is either a float or an integer.
			$is_float = $value === strval( floatval( $value ) );
			$is_int   = $value === strval( intval( $value ) );
			if ( ! $is_float || ! $is_int ) {
				$valid = false;
			} else {
				// Get the field's numeric schema.
				$schema         = $this->field['data_args'];
				$schema['type'] = $is_float ? 'float' : 'int';
				$schema['nval'] = $is_float ? floatval( $value ) : intval( $value );
				if ( $schema['min'] && $schema['nval'] < $schema['min'] ) {
					// Validate minimum value.
					$valid = false;
				} elseif ( $schema['max'] && $schema['nval'] > $schema['max'] ) {
					// Validate maximum value.
					$valid = false;
				} elseif ( $schema['step'] ) {
					// Validate step using an alternative to the "fmod" function.
					// https://www.php.net/manual/en/function.fmod.php#122782
					if ( $schema['step'] === strval( floatval( $value ) ) ) {
						$step_nval = floatval( $schema['step'] );
					} else {
						$step_nval = intval( $value );
					}
					if ( 0.0 !== floatval( $schema['nval'] - intval( $schema['nval'] / $step_nval ) * $step_nval ) ) {
						$valid = false;
					}
				}
			}
		}
		if ( ! $valid ) {
			$default = isset( $this->field['data_args']['default'] ) ? $this->field['data_args']['default'] : '';
			$value = get_site_option( $this->option_group, $default );
		}

		return $value;

	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/number
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
			'<input type="number" id="%1$s" name="%2$s" value="%3$s" %4$s/>',
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
