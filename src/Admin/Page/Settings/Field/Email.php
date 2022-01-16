<?php
/**
 * The file that extends the Field class into an Email Field for the Settings API.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Field
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/admin/page/settings/field/email.php
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
class Email extends Field {

	/**
	 * The default values for required $field members.
	 *
	 * @var array $default The default field parameter member values.
	 */
	protected $default_field = array(
		'type'        => 'email',
		'placeholder' => '',
		'data_args'   => array(
			'sanitize_callback' => true,
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
			'maxlength'   => true,
			'minlength'   => true,
			'multiple'    => true,
			'name'        => true,
			'pattern'     => true,
			'placeholder' => true,
			'readonly'    => true,
			'required'    => true,
			'size'        => true,
			'type'        => 'email',
			'value'       => true,
		),
	);
}
