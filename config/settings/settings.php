<?php
/**
 * An example settings file to be consumed by \Thoughtful_Web\Library_WP\Admin\Page\Settings();
 */
if ( ! defined( 'ABSPATH' ) ) {
	include dirname( __DIR__ ) . '/file/auth_include.php';
	\Thoughtful_Web\Library_WP\File\Auth_Include::error_404();
}

return array(
	'method'      => 'add_menu_page',
	'method_args' => array(
		'page_title' => 'A Thoughtful Settings Page',
		'menu_title' => 'Thoughtful Settings',
		'capability' => 'manage_options',
		'menu_slug'  => 'thoughtful-settings',
		'function'   => null,
		'icon_url'   => 'dashicons-admin-settings',
		'position'   => 2,
	),
	'description' => 'A thoughtful settings page description.',
	'heading'     => array(),
	'fieldsets'   => array(
		array(
			'section' => 'thoughtful-section-1',
			'title'   => '',
			'fields'  => array(
				array(
					'label'       => 'Text Field',
					'id'          => 'my_text_field',
					'type'        => 'text',
					'section'     => 'thoughtful-settings_section',
					'desc'        => 'Description',
					'placeholder' => 'placeholder',
					'label_for'   => null,
					'class'       => null,
				),
			),
		),
	),
);
