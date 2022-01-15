<?php
/**
 * The file that creates a callable function for sanitizing Field values. Hooked to the filter
 * "sanitize_option_{$option_name}" with $option_name equal to the field's $id value. The hook is
 * run by the "sanitize_option()" function which is executed within Settings API functions like
 * "add_option", "update_option", etc.
 *
 * @todo Make error messages configurable via a file with sprintf-compatible string template characters.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <zachwatkins@tapfuel.io>
 * @copyright  2022 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/page/settings/sanitize.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Admin\Page\Settings;

class Sanitize {

	/**
	 * Validation settings.
	 *
	 * @access private
	 */
	protected $default_field = array(
		'data_args' => array(),
	);

	/**
	 * Stored field value.
	 *
	 * @var array $field The registered field arguments.
	 */
	protected $field;

	/**
	 * Instantiate the class and assign properties from the parameters.
	 *
	 * @param string $field The parameters used to register a field.
	 */
	public function __construct( $field ) {

		$this->field = $this->apply_defaults( $field );

	}

	/**
	 * Merge user-defined field values with default values.
	 *
	 * @since 0.1.0
	 *
	 * @param array $field The field registration arguments.
	 *
	 * @return array
	 */
	private function apply_defaults( $field ) {

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

		return $field;

	}

	/**
	 * Sanitize the string for presentation purposes.
	 *
	 * @param string $value The sanitized option value.
	 *
	 * @return string
	 */
	public function sanitize( $value ) {

		global $wpdb;

		$option       = $this->field['id'];
		$option_value = get_option( $option );
		$error        = '';
		$data_args    = $this->field['data_args'];

		switch ( $this->field['type'] ) {
			case 'color':
				$match = preg_match( '#^\#[a-zA-Z0-9]{6}$#i', $value );
				if ( ! $match ) {
					$error = __( 'The color value entered is not in hexadecimal format. Please enter a valid hexadecimal color value.', 'thoughtful-web' );
				}
				break;
			case 'email':
				$value = $wpdb->strip_invalid_text_for_column( $wpdb->options, 'option_value', $value );
				if ( is_wp_error( $value ) ) {
					$error = $value->get_error_message();
				} else {
					$value = sanitize_email( $value );
					if ( ! empty( trim( $value ) ) && ! is_email( $value ) ) {
						$error = __( 'The email address entered did not appear to be a valid email address. Please enter a valid email address.', 'thoughtful-web' );
					} else {
						$match = 1;
						if ( array_key_exists( 'pattern', $data_args ) && $data_args['pattern'] ) {
							$match = preg_match( '#' . str_replace( '#', '\#', $data_args['pattern'] ) . '#i', $value );
						}
						if ( ! $match ) {
							$error = __( 'The email address entered did not match the pattern "' . esc_html( $data_args['pattern'] ) . '". Please enter a valid email address.', 'thoughtful-web' );
						}
					}
				}
				break;
			case 'number':
				// Remove surrounding whitespace.
				$value = trim( $value );
				// Detect invalid circumstances and then fall back to the previous value.
				if ( ! is_numeric( $value ) ) {
					// The easiest rejection to make.
					$error = __( 'The number value entered is not numeric. Please enter a valid number.', 'thoughtful-web' );
				} else {
					// Ensure the number is either a float or an integer.
					$is_float = strval( floatval( $value ) ) === $value;
					$is_int   = strval( intval( $value ) ) === $value;
					if ( ! $is_float && ! $is_int ) {
						$error = __( 'The number value entered is neither a float nor an integer. Please enter a valid number.', 'thoughtful-web' );
					} else {
						// Get the field's numeric schema.
						$schema         = $this->field['data_args'];
						$schema['type'] = $is_float ? 'float' : 'int';
						$schema['nval'] = $is_float ? floatval( $value ) : intval( $value );
						if ( $schema['min'] && $schema['nval'] < $schema['min'] ) {
							// Validate minimum value.
							$error = __( 'The number value entered is less than the minimum allowed value of ' . $schema['min'] . '. Please enter a valid number.', 'thoughtful-web' );
						} elseif ( $schema['max'] && $schema['nval'] > $schema['max'] ) {
							// Validate maximum value.
							$error = __( 'The number value entered is greater than the maximum allowed value of ' . $schema['max'] . '. Please enter a valid number.', 'thoughtful-web' );
						} elseif ( $schema['step'] ) {
							// Validate the "step" attribute using an alternative to the "fmod" function.
							if ( strval( floatval( $value ) ) === $schema['step'] ) {
								$step_nval = floatval( $schema['step'] );
							} else {
								$step_nval = intval( $value );
							}
							if ( 0.0 !== floatval( $schema['nval'] - intval( $schema['nval'] / $step_nval ) * $step_nval ) ) {
								$error = __( 'The number value entered is not a multiple of the "step" value of ' . $schema['step'] . '. Please enter a valid number.', 'thoughtful-web' );
							}
						}
					}
				}
				break;
			case 'phone':
				$value = trim( $value );
				$value = sanitize_text_field( $value );
				if ( array_key_exists( 'pattern', $data_args ) && ! empty( $data_args['pattern'] ) ) {
					$match = preg_match( $data_args['pattern'], $value );
					if ( ! $match ) {
						$error = __( 'The phone number value entered does not match the pattern of "' . esc_html( $data_args['pattern'] ) . '". Please enter a valid phone number.', 'thoughtful-web' );
					}
				}
				break;
			case 'text':
				$value = sanitize_text_field( $value );
				break;
			case 'textarea':
				$value = sanitize_textarea_field( $value );
				break;
			case 'url':
				$value = $wpdb->strip_invalid_text_for_column( $wpdb->options, 'option_value', $value );
				if ( is_wp_error( $value ) ) {
					$error = $value->get_error_message();
				} elseif ( array_key_exists( 'pattern', $data_args ) && ! empty( $data_args['pattern'] ) ) {
					if ( preg_match( '#' . str_replace( '#', '\#', $data_args['pattern'] ) . '#i', $value ) ) {
						$value = esc_url_raw( $value );
					} else {
						$error = __( 'The URL you entered does not match the pattern of "' . esc_html( $data_args['pattern'] ) . '". Please enter a valid URL.', 'thoughtful-web' );
					}
				}
				break;
			case 'wp_editor':
				$value = wp_kses_post( $value );
				break;
			case 'checkbox':
				if ( array_key_exists( 'choice', $this->field ) ) {
					// If the choice value is present in the configuration and it is not a configured choice then it is a falsified choice.
					if ( empty( $value ) ) {
						$value = array();
					} elseif ( array_key_first( $this->field['choice'] ) !== $value ) {
						// Value is falsified.
						$error = __( 'The value submitted is not the preconfigured value. Please use the preconfigured value or an empty string.', 'thoughtful-web' );
					}
				} elseif ( array_key_exists( 'choices', $this->field ) ) {
					// Get the predefined choices from the configuration variable.
					$config_choices = array_keys( $this->field['choices'] );
					$final_choices  = array();
					if ( ! is_array( $value ) ) {
						$value = array( $value );
					}
					foreach ( $value as $key => $choice ) {
						// If the choice value is present in the configuration, continue.
						if ( in_array( $choice, $config_choices, true ) ) {
							// Ensure the choice can only be passed once.
							$final_choices[ $choice ] = $choice;
							continue;
						} else {
							// A value is falsified.
							$error = __( 'One or more values were not among the available choices. Please choose a valid option.', 'thoughtful-web' );
							break;
						}
					}
				}
				break;
			case 'radio':
				// Get the predefined choices from the configuration variable.
				$config_choices = array_keys( $this->field['choices'] );
				// If the choice value is present in the configuration, continue.
				if ( ! in_array( $value, $config_choices, true ) ) {
					$error = __( 'The value chosen is not among the available choices. Please choose a valid option.', 'thoughtful-web' );
				}
				break;
			case 'select':
				// Detect if this is a multiselect field.
				$is_multiselect = array_key_exists( 'multiple', $data_args ) && false !== boolval( $data_args['multiple'] ) ? true : false;

				// Detect if the correct value format is provided.
				if ( $is_multiselect && ! is_array( $value ) ) {
					$error = __( 'The value submitted is not an array. Please submit an array value.', 'thoughtful-web' );
				} elseif ( ! $is_multiselect && is_array( $value ) ) {
					$error = __( 'More than one choice is not permitted. Please submit a single value.', 'thoughtful-web' );
				} else {
					// If this is not a multiselect field then convert the value to an array temporarily.
					$restore_later = false;
					if ( ! $is_multiselect && ! is_array( $value ) ) {
						$restore_later = true;
						$value         = array( $value );
					}
					// Get the predefined choices from the configuration variable.
					$config_choices = array_keys( $this->field['choices'] );
					foreach ( $value as $choice ) {
						// If the choice value is present in the configuration, continue.
						if ( ! in_array( $choice, $config_choices, true ) ) {
							// A value is falsified.
							$error = __( 'One or more values were not among the available choices. Please choose a valid option.', 'thoughtful-web' );
							break;
						}
					}
					if ( $restore_later ) {
						$value = $value[0];
					}
				}
				break;
		}

		// Check for "required" data argument and emit an error if the value is empty while required.
		if ( array_key_exists( 'required', $data_args ) && $data_args['required'] && empty( $value ) && empty( $option_value ) ) {
			$error = __( 'A value is required. Please enter a value.' );
		}

		if ( ! empty( $error ) ) {
			$value = $option_value;
			if ( function_exists( 'add_settings_error' ) ) {
				// Prepend the settings field label to the error message.
				$error = __( 'The ' . $this->field['label'] . ' field encountered an error: ', 'thoughtful-web' ) . $error;
				// Add the error.
				add_settings_error( $option, "invalid_{$option}", $error );
			}
		}

		return $value;

	}

}
