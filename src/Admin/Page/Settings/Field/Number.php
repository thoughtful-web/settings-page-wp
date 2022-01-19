<?php
/**
 * The file that extends the Field class into a Number Field for the Settings API.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Field
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/settings-page-wp/blob/main/src/Admin/Page/Settings/Field/Number.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Field;

use \ThoughtfulWeb\LibraryWP\Admin\Page\Settings\Field;

/**
 * The Number Field class.
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
		'placeholder' => '',
		'data_args'   => array(
			'sanitize_callback' => true,
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
}
