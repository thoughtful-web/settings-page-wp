<?php
/**
 * The file that creates a callable function for sanitizing Field values. Hooked to the filter
 * "sanitize_option_{$option_name}" with $option_name equal to the field's $id value. The hook is
 * run by the "sanitize_option()" function which is executed within Settings API functions like
 * "add_option", "update_option", etc.
 *
 * @todo Make error messages configurable via a file with sprintf-compatible string template characters.
 *
 * @package    ThoughtfulWeb\SettingsPageWP
 * @subpackage Settings
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  2022 Zachary Kendall Watkins
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/settings-page-wp/blob/main/src/Settings/Sanitize.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\SettingsPageWP\Settings;

/**
 * The Sanitize class, which ensures safe and correct values are achieved when the Option value is updated.
 */
class Sanitize {

	/**
	 * Validation settings.
	 *
	 * @var $default_field
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
	 * User capability
	 * 
	 * @var string $capability Capability needed to update the option.
	 */
	protected $capability = 'manage_options';

	/**
	 * Instantiate the class and assign properties from the parameters.
	 *
	 * @param string $field The parameters used to register a field.
	 */
	public function __construct( $field, $capability ) {

		$this->field      = $this->apply_defaults( $field );
		$this->capability = array_key_exists( 'capability', $this->field ) ? $this->field['capability'] : $capability;

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
				foreach ( $default_value as $data_key => $default_data_value ) {
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

		// Reject users without permission.
		if ( ! current_user_can( $this->capability ) ) {
			return $option_value;
		}

		// Reject attempts to change a readonly or disabled field value.
		if (
			( array_key_exists( 'readonly', $data_args ) && false !== $data_args['readonly'] )
			|| ( array_key_exists( 'disabled', $data_args ) && false !== $data_args['disabled'] )
		) {
			$value = $option_value;
		}

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
							$error = sprintf(
								// translators: The regular expression pattern.
								__(
									'The email address entered did not match the pattern "%s". Please enter a valid email address.',
									'thoughtful-web'
								),
								esc_html( $data_args['pattern'] )
							);
						}
					}
				}
				break;
			case 'number':
				// Detect if the option is trying to be set to an empty value.
				if ( empty( $value ) ) {
					$value = '';
					break;
				}
				// Remove surrounding whitespace.
				if ( is_string( $value ) ) {
					$value = trim( $value );
					// Ensure the number is either a float or an integer.
					$is_float = strval( floatval( $value ) ) === $value;
					$is_int   = strval( intval( $value ) ) === $value;
					if ( $is_float ) {
						$value = floatval( $value );
					} elseif ( $is_int ) {
						$value = intval( $value );
					}
				}
				// Detect invalid circumstances and then fall back to the previous value.
				if ( ! is_numeric( $value ) ) {
					// The easiest rejection to make.
					$error = __( 'The number value entered is not numeric. Please enter a valid number.', 'thoughtful-web' );
				} else {
					if ( ! $is_float && ! $is_int ) {
						$error = __( 'The number value entered is neither a float nor an integer. Please enter a valid number.', 'thoughtful-web' );
					} else {
						// Get the field's numeric schema.
						$schema         = $this->field['data_args'];
						$schema['type'] = $is_float ? 'float' : 'int';
						$schema['nval'] = $is_float ? floatval( $value ) : intval( $value );
						if ( $schema['min'] && $schema['nval'] < $schema['min'] ) {
							// Validate minimum value.
							$error = sprintf(
								// translators: The minimum value.
								__(
									'The number value entered is less than the minimum allowed value of %s. Please enter a valid number.',
									'thoughtful-web'
								),
								strval( $schema['min'] )
							);
						} elseif ( $schema['max'] && $schema['nval'] > $schema['max'] ) {
							// Validate maximum value.
							$error = sprintf(
								// translators: The maximum value.
								__(
									'The number value entered is greater than the maximum allowed value of %s. Please enter a valid number.',
									'thoughtful-web'
								),
								strval( $schema['max'] )
							);
						} elseif ( $schema['step'] ) {
							// Validate the "step" attribute using an alternative to the "fmod" function.
							if ( strval( floatval( $value ) ) === $schema['step'] ) {
								$step_nval = floatval( $schema['step'] );
							} else {
								$step_nval = intval( $value );
							}
							if ( 0.0 !== floatval( $schema['nval'] - intval( $schema['nval'] / $step_nval ) * $step_nval ) ) {
								$error = sprintf(
									// translators: The step value of the number field.
									__(
										'The number value entered is not a multiple of the "step" value of %s. Please enter a valid number.',
										'thoughtful-web'
									),
									strval( $schema['step'] )
								);
							}
						}
					}
				}
				break;
			case 'tel':
				$value = trim( $value );
				$value = sanitize_text_field( $value );
				if ( array_key_exists( 'pattern', $data_args ) && ! empty( $data_args['pattern'] ) ) {
					$match = preg_match( '#' . str_replace( '#', '\#', $data_args['pattern'] ) . '#i', $value );
					if ( ! $match ) {
						$error = sprintf(
							// translators: The regular expression pattern.
							__(
								'The phone number value entered does not match the pattern of "%s". Please enter a valid phone number.',
								'thoughtful-web'
							),
							esc_html( $data_args['pattern'] )
						);
					}
				}
				break;
			case 'text':
				$email_pattern = '/(<)([^>@\s]+@[^>\s]+\.[a-zA-Z]+[^\/]+)(>)/';
				$replaced      = preg_replace( $email_pattern, '{l__}$2{__r}', $value );
				$value         = sanitize_text_field( $replaced );
				$value         = str_replace( '{l__}', '<', $value );
				$value         = str_replace( '{__r}', '>', $value );
				break;
			case 'textarea':
				$value = sanitize_textarea_field( $value );
				break;
			case 'url':
				$value = $wpdb->strip_invalid_text_for_column( $wpdb->options, 'option_value', $value );
				if ( is_wp_error( $value ) ) {
					$error = $value->get_error_message();
				} elseif ( array_key_exists( 'pattern', $data_args ) && ! empty( $data_args['pattern'] ) ) {
					if ( preg_match( '/' . str_replace( '/', '\/', $data_args['pattern'] ) . '/i', $value ) ) {
						$value = esc_url_raw( $value );
					} elseif ( ! empty( $value ) ) {
						$error = sprintf(
							// translators: The regular expression pattern.
							__(
								'The URL you entered does not match the pattern of "%s". Please enter a valid URL.',
								'thoughtful-web'
							),
							esc_html( $data_args['pattern'] )
						);
					}
				}
				break;
			case 'wp_editor':
				$value = wp_kses_post( $value );
				break;
			case 'checkbox':
				// Detect if this is for a singular checkbox field or not.
				$is_singular = array_key_exists( 'choice', $this->field );
				// Allow empty arrays and strings.
				if ( empty( $value ) ) {
					// Handle passed null values.
					if ( is_null( $value ) ) {
						$value = $is_singular ? '' : array();
					}
					// pass on empty values.
					if ( is_string( $value ) || is_array( $value ) ) {
						break;
					}
				}
				if ( $is_singular ) {
					// Detect if the option is trying to be set to an empty value.
					$possible_values = array( strval( array_key_first( $this->field['choice'] ) ), '' );
					if ( ! in_array( $value, $possible_values ) ) {
						// The value is not the preconfigured value.
						$error = __( 'The value submitted is not a choice. Please provide the preconfigured value or an empty string.', 'thoughtful-web' );
					}
				} else {
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
				$is_multiselect = array_key_exists( 'multiple', $data_args ) && false !== $data_args['multiple'] ? true : false;

				// Detect if the option is trying to be set to an empty value.
				if ( empty( $value ) ) {
					break;
				}

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
		if ( array_key_exists( 'required', $data_args ) && false !== $value && empty( $value ) ) {
			$error = 'A value is required. Please enter a value.';
		}

		if ( ! empty( $error ) ) {
			$value = $option_value;
			if ( function_exists( 'add_settings_error' ) ) {
				// Prepend the settings field label to the error message.
				$error = sprintf(
					// translators: The field label.
					__( 'The %s field encountered an error: ', 'thoughtful-web' ) . $error,
					$this->field['label']
				);
				// Add the error.
				add_settings_error( $option, "invalid_{$option}", $error );
			}
		} elseif ( empty( $value ) && array_key_exists( 'default', $data_args ) ) {
			// Return the default value.
			// I spent a lot of time searching through the WordPress Core "option.php" file to figure out how best to restore
			// the default value when it is emptied. The update_option function does not call the default_option_$option filter
			// hook as of this writing, but it does call the sanitize_option function. This is why I consider applying the
			// default value to an empty value a sanitization step.
			$value = apply_filters( "default_option_{$option}", $data_args['default'], $option, false );
			// Add a notice.
			add_settings_error( $option, "default_{$option}", "Restoring the default value for {$this->field['label']}." );
		}

		return $value;

	}

}
