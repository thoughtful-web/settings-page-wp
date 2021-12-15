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
			'description' => '',
			'title'       => '',
			'fields'      => array(
				array(
					'label'       => 'My Text Field',
					'id'          => 'unique_text_field',
					'type'        => 'text',
					'desc'        => 'My text field description',
					'placeholder' => 'my placeholder',
					'data_args'   => array(
						'default' => 'A thoughtful, optional, default value',
					)
				),
				array(
					'label'       => 'My Textarea Field',
					'id'          => 'unique_textarea_field',
					'type'        => 'textarea',
					'desc'        => 'My textarea field',
					'placeholder' => 'my placeholder',
				),
				array(
					'label'       => 'My Editor Field',
					'id'          => 'unique_editor_field',
					'type'        => 'wp_editor',
					'desc'        => 'My editor field description',
					'placeholder' => 'my placeholder',
				),
			),
		),
		array(
			'section'     => 'unique-section-id-2',
			'title'       => '',
			'description' => '',
			'fields'      => array(
				array(
					'label'   => 'My Checkbox Field',
					'id'      => 'unique_checkbox_field',
					'type'    => 'checkbox',
					'desc'    => 'My checkbox field description',
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
