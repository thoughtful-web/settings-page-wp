<?php
/**
 * The file that extends the Field class into a Phone Field for the Settings API.
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
use \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Validate\Text_Validator;
use \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Validate\Text_Sanitizer;

/**
 * The Phone Field class.
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/url
 *
 * @since 0.1.0
 */
class Url extends Field {

	/**
	 * The default values for required $field members.
	 *
	 * @var array $default The default field parameter member values.
	 */
	protected $default_field = array(
		'type'        => 'url',
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
			'type'         => 'text',
			'value'        => true,
		),
	);

	/**
	 * Sanitize the text field value.
	 *
	 * @see https://developer.wordpress.org/reference/functions/esc_url_raw/
	 *
	 * @param string $value The unsanitized option value.
	 *
	 * @return string
	 */
	public function sanitize( $value ) {

		// Validate the value.
		$validate = new Text_Validator( $this->field );
		$is_valid = $validate->is_valid( $value );
		if ( ! $is_valid['status'] ) {
			$validate->notify( $is_valid['message'] );
			return get_site_option( $this->field['id'], $this->field['data_args']['default'] );
		}

		// Sanitize the valid value.
		$sanitizer  = new Text_Sanitizer( $this->field );
		$sanitation = $sanitizer->is_sanitary( $value );
		if ( false === $sanitation['status'] ) {
			$value = $sanitizer->sanitize( $value, 'db' );
			$sanitizer->notify( $sanitation['message'], 'warning' );
		}

		return $value;

	}
}
