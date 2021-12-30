<?php

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
		'function'   => null,
		'icon_url'   => 'dashicons-admin-settings',
		'position'   => 2,
	),
	'description'  => 'A thoughtful settings page description.',
	'option_group' => 'thoughtful_settings',
	'network'      => false,
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
					'desc'        => 'My text field description',
					'placeholder' => 'my placeholder',
					'data_args'   => array(
						'default'       => 'A thoughtful, optional, default value',
						'data-lpignore' => 'true',
						'size'          => '40',
					),
				),
				array(
					'label'     => 'My Color Field',
					'id'        => 'unique_color_field',
					'type'      => 'color',
					'desc'      => 'My color field description',
					'data_args' => array(
						'default' => '#000000',
					),
				),
				array(
					'label'       => 'My Textarea Field',
					'id'          => 'unique_textarea_field',
					'type'        => 'textarea',
					'desc'        => 'My textarea field',
					'placeholder' => 'my placeholder',
				),
				array(
					'label'     => 'My Checkbox Field',
					'id'        => 'unique_checkbox_field',
					'type'      => 'checkbox',
					'desc'      => 'My checkbox field description',
					'choice'    => array(
						'1' => 'My Choice',
					),
					'data_args' => array(
						'default' => array(
							'1' => 'My Choice',
						),
					),
				),
				array(
					'label'     => 'My Checkbox Fields',
					'id'        => 'unique_checkbox_fields',
					'type'      => 'checkbox',
					'desc'      => 'My checkbox fields description',
					'choices'   => array(
						'option_one'   => 'Option 1',
						'option_two'   => 'Option 2',
						'option_three' => 'Option 3',
					),
					'data_args' => array(
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
					'label'   => 'My WP Editor Field',
					'id'      => 'unique_wp_editor_field',
					'type'    => 'wp_editor',
					'desc'    => 'My WP Editor field description',
					'default' => 'my placeholder',
				),
				array(
					'label'       => 'My Decimal Number Field',
					'id'          => 'unique_decimal_number_field',
					'type'        => 'number',
					'desc'        => 'My number field description',
					'placeholder' => 'Multiple of 0.1',
					'data_args'   => array(
						'step' => '0.1',
						'min'  => '0',
						'max'  => '10',
					),
				),
				array(
					'label'       => 'My Negative Number Field',
					'id'          => 'unique_negative_number_field',
					'type'        => 'number',
					'desc'        => 'My negative number field description',
					'placeholder' => 'Multiple of -1',
					'data_args'   => array(
						'step' => '1',
						'min'  => '-10',
						'max'  => '0',
					),
				),
				array(
					'label'   => 'My Radio Field',
					'id'      => 'unique_radio_field',
					'type'    => 'radio',
					'choices' => array(
						'option_one'   => 'Option 1',
						'option_two'   => 'Option 2',
						'option_three' => 'Option 3',
					),
					'desc'    => 'My radio field description',
				),
				array(
					'label'   => 'My Select Field',
					'id'      => 'unique_select_field',
					'type'    => 'select',
					'choices' => array(
						'option_one'   => 'Option 1',
						'option_two'   => 'Option 2',
						'option_three' => 'Option 3',
					),
					'desc'    => 'My select field description',
				),
				array(
					'label'   => 'My Multi-select Field',
					'id'      => 'unique_multiselect_field',
					'type'    => 'multiselect',
					'choices' => array(
						'option_one'   => 'Option 1',
						'option_two'   => 'Option 2',
						'option_three' => 'Option 3',
					),
					'desc'    => 'My multi-select field description',
				),
				array(
					'label'       => 'My Media Field',
					'id'          => 'unique_media_field',
					'type'        => 'media',
					'returnvalue' => 'id',
					'desc'        => 'My media field description',
				),
				array(
					'label'       => 'My Email Field',
					'id'          => 'unique_email_field',
					'type'        => 'email',
					'desc'        => 'My email field description',
					'placeholder' => 'my placeholder',
				),
			),
		),
	),
);
