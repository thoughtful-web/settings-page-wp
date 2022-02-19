<?php
/**
 * The example settings configuration file.
 *
 * @package    ThoughtfulWeb\SettingsPageWP
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/settings-page-wp/blob/main/config/thoughtful-web/settings.example.php
 * @since      0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {

	http_response_code( 404 );
	?><html><head><title>HTTP 404 Not Found</title></head><body><p>The requested page does not exist.</p></body></html>
	<?php
	die();

}

return array(
	'method_args'  => array(
		'page_title' => 'A Thoughtful Settings Page',
		'menu_title' => 'Thoughtful Settings',
		'capability' => 'manage_options',
		'menu_slug'  => 'thoughtful-settings',
		'icon_url'   => 'dashicons-admin-settings',
		'position'   => 2,
	),
	'option_group' => 'thoughtful_settings',
	'description'  => 'A thoughtful settings page description.',
	'stylesheet'   => array(
		'file' => '/css/settings.css',
		'deps' => array(),
	),
	'script'       => array(
		'file'      => '/scripts/settings.js',
		'deps'      => array(),
		'in_footer' => true,
	),
	'sections'     => array(
		array(
			'section'     => 'unique-section-id-1',
			'title'       => 'Section One',
			'description' => 'A description for Section One',
			'fields'      => array(
				array(
					'label'       => 'My Text Field',
					'id'          => 'unique_text_field',
					'type'        => 'text',
					'description' => 'My text field description',
					'data_args'   => array(
						'placeholder'   => 'my placeholder',
						'default'       => 'A thoughtful, optional, default value',
						'data-lpignore' => 'true',
						'size'          => '40',
					),
				),
				array(
					'label'       => 'My Color Field',
					'id'          => 'unique_color_field',
					'type'        => 'color',
					'description' => 'My color field description',
					'data_args'   => array(
						'default' => '#000000',
					),
				),
				array(
					'label'       => 'My Textarea Field',
					'id'          => 'unique_textarea_field',
					'type'        => 'textarea',
					'description' => 'My textarea field',
					'data_args'   => array(
						'placeholder' => 'my placeholder',
					)
				),
				array(
					'label'       => 'My Checkbox Field',
					'id'          => 'unique_checkbox_field',
					'type'        => 'checkbox',
					'description' => 'My checkbox field description',
					'choice'      => array(
						'1' => 'My Choice',
					),
					'data_args'   => array(
						'default' => '1',
					),
				),
				array(
					'label'       => 'My Checkbox Fields',
					'id'          => 'unique_checkbox_fields',
					'type'        => 'checkbox',
					'description' => 'My checkbox fields description',
					'choices'     => array(
						'option_one'   => 'Option 1',
						'option_two'   => 'Option 2',
						'option_three' => 'Option 3',
					),
					'data_args'   => array(
						'default' => array(
							'option_one',
							'option_two',
						),
					),
				),
			),
		),
		array(
			'section'     => 'unique-section-id-2',
			'title'       => 'Section Two',
			'description' => 'Section Two description text',
			'fields'      => array(
				array(
					'label'       => 'My WP Editor Field',
					'id'          => 'unique_wp_editor_field',
					'type'        => 'wp_editor',
					'description' => 'My WP Editor field description',
					'data_args'   => array(
						'default' => 'my default value',
					),
				),
				array(
					'label'       => 'My Decimal Number Field',
					'id'          => 'unique_decimal_number_field',
					'type'        => 'number',
					'description' => 'My number field description',
					'data_args'   => array(
						'placeholder' => 'Multiple of 0.1',
						'step'        => '0.1',
						'min'         => '0',
						'max'         => '10',
					),
				),
				array(
					'label'       => 'My Negative Number Field',
					'id'          => 'unique_negative_number_field',
					'type'        => 'number',
					'description' => 'My negative number field description',
					'data_args'   => array(
						'placeholder' => 'Multiple of -1',
						'step'        => '1',
						'min'         => '-10',
						'max'         => '0',
					),
				),
				array(
					'label'       => 'My Radio Field',
					'id'          => 'unique_radio_field',
					'type'        => 'radio',
					'description' => 'My radio field description',
					'choices'     => array(
						'option_one'   => 'Option 1',
						'option_two'   => 'Option 2',
						'option_three' => 'Option 3',
					),
				),
				array(
					'label'       => 'My Select Field',
					'id'          => 'unique_select_field',
					'type'        => 'select',
					'description' => 'My select field description',
					'choices'     => array(
						'option_one'   => 'Option 1',
						'option_two'   => 'Option 2',
						'option_three' => 'Option 3',
					),
				),
				array(
					'label'       => 'My Multi-select Field',
					'id'          => 'unique_multiselect_field',
					'type'        => 'select',
					'description' => 'My multi-select field description',
					'choices'     => array(
						'option_one'   => 'Option 1',
						'option_two'   => 'Option 2',
						'option_three' => 'Option 3',
					),
					'data_args'   => array(
						'multiple' => true,
						'default'  => array('option_one', 'option_two'),
					),
				),
				array(
					'label'       => 'My Email Field',
					'id'          => 'unique_email_field',
					'type'        => 'email',
					'description' => 'My email field description',
					'data_args'   => array(
						'placeholder' => 'my placeholder',
					)
				),
				array(
					'label'       => 'My Phone Field',
					'id'          => 'unique_phone_field',
					'type'        => 'tel',
					'description' => 'Example: 555-555-5555',
					'data_args'   => array(
						'placeholder' => '555-555-5555',
						'pattern'     => '[0-9]{3}-[0-9]{3}-[0-9]{4}',
					),
				),
				array(
					'label'       => 'My URL Field',
					'id'          => 'unique_url_field',
					'type'        => 'url',
					'description' => 'Must have the "https" protocol. Example: https://example.com/',
					'data_args'   => array(
						'placeholder' => 'https://example.com/',
						'pattern'     => 'https://.*',
					),
				),
				array(
					'id'          => 'api_key',
					'label'       => 'API Key',
					'type'        => 'password',
					'description' => 'An API key used to access data.',
					'data_args'   => array(
						'copy_button' => 'Copy API key',
					),
				),
			),
		),
	),
);
