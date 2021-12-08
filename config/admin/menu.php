<?php

if ( ! defined( 'ABSPATH' ) ) {

	http_response_code( 404 );
	?><html><head><title>HTTP 404 Not Found</title></head><body><p>The requested page does not exist.</p></body></html>
	<?php
	die();

}

return array(
	'menu'   => array(
		'hook'       => 'admin_menu',
		'page_title' => 'My Settings',
		'menu_title' => 'My Settings',
		'capability' => 'manage_options',
		'menu_slug'  => 'my-settings',
		'icon_url'   => 'dashicons-admin-settings',
		'position'   => 2,
	),
	'fields' => array(
		array(
			'label'       => 'My Text Field',
			'id'          => 'my_text_field',
			'type'        => 'text',
			'section'     => 'mysettings_section',
			'desc'        => 'My text field description',
			'placeholder' => 'my placeholder',
		),
		array(
			'label'       => 'My Textarea Field',
			'id'          => 'my_textarea_field',
			'type'        => 'textarea',
			'section'     => 'mysettings_section',
			'desc'        => 'My textarea field',
			'placeholder' => 'my placeholder',
		),
		array(
			'label'       => 'My Editor Field',
			'id'          => 'my_editor_field',
			'type'        => 'wysiwyg',
			'section'     => 'mysettings_section',
			'desc'        => 'My editor field description',
			'placeholder' => 'my placeholder',
		),
		array(
			'label'   => 'My Checkbox Field',
			'id'      => 'my_checkbox_field',
			'type'    => 'checkbox',
			'section' => 'mysettings_section',
			'desc'    => 'My checkbox field description',
		),
		array(
			'label'   => 'My Radio Field',
			'id'      => 'my_radio_field',
			'type'    => 'radio',
			'section' => 'mysettings_section',
			'options' => array(
				'option_one'   => 'Option 1',
				'option_two'   => 'Option 2',
				'option_three' => 'Option 3',
			),
			'desc'    => 'My radio field description',
		),
		array(
			'label'   => 'My Select Field',
			'id'      => 'my_select_field',
			'type'    => 'select',
			'section' => 'mysettings_section',
			'options' => array(
				'option_one'   => 'Option 1',
				'option_two'   => 'Option 2',
				'option_three' => 'Option 3',
			),
			'desc'    => 'My select field description',
		),
		array(
			'label'   => 'My Multi-select Field',
			'id'      => 'my_multiselect_field',
			'type'    => 'multiselect',
			'section' => 'mysettings_section',
			'options' => array(
				'option_one'   => 'Option 1',
				'option_two'   => 'Option 2',
				'option_three' => 'Option 3',
			),
			'desc'    => 'My multi-select field description',
		),
		array(
			'label'       => 'My Media Field',
			'id'          => 'my_media_field',
			'type'        => 'media',
			'section'     => 'mysettings_section',
			'returnvalue' => 'id',
			'desc'        => 'My media field description',
		),
		array(
			'label'       => 'My Email Field',
			'id'          => 'my_email_field',
			'type'        => 'email',
			'section'     => 'mysettings_section',
			'desc'        => 'My email field description',
			'placeholder' => 'my placeholder',
		),
	),
);
