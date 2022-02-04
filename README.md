# Create Settings Pages for WordPress

>Free open source software under the GNU GPL-2.0+ License.  
>Copyright Zachary Kendall Watkins 2021-2022.  

This library generates both a Settings page and fully qualified Options for each of its fields from a single configuration file.

All HTML attributes for supported form fields are allowed and "pattern" attributes are validated on the client and server. Each Field is a separate [Option](https://developer.wordpress.org/plugins/settings/options-api/) and Core WordPress filters and actions apply.

## Table of Contents

1. [Features](#features)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Simple Implementation](#simple-implementation)
5. [Implementation](#implementation)
6. [Creating the Config File](#creating-the-config-file)
7. [Sections](#sections)
8. [Fields Overview](#fields-overview)
9. [Field Types](#field-types-supported)
10. [Additional Documentation](#additional-documentation)

## Features

1. Settings page generation from a configuration file using Core WordPress [Settings](https://developer.wordpress.org/plugins/settings/settings-api/) and [Options](https://developer.wordpress.org/plugins/settings/options-api/) APIs.
2. Each Field creates and updates an database Option, allowing you to hook and filter them individually.
3. Each Field is validated on the server in a manner similar to Core WordPress options. Failed server-side validation emits a Settings Page [error notice](https://developer.wordpress.org/reference/functions/add_settings_error/).
4. Further validate a field using regular expressions on the client and server by adding the [pattern attribute](https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes/pattern) if a Field's HTML form element supports it. This works on the Settings page and also when a script calls [`update_option()`](https://developer.wordpress.org/reference/functions/update_option/).
5. Configure a stylesheet and/or script file for the page.
6. Configure default Field values to automatically load them into the database. If the field is ever emptied these values will be added again.
7. Zero production dependencies beyond PHP, WordPress, and WordPress included JavaScript (Iris) for the color picker field.
8. Configure and create pages or subpages.

[Back to top](#table-of-contents)

## Requirements

1. WordPress 5.4 and above.
2. PHP 7.3.5 and above.
3. This library existing two directory levels below your plugin's root directory. Examples:  
   a. `vendor/thoughtful-web/activation-requirements-wp`  
   b. `lib/thoughtful-web/activation-requirements-wp`  
4. A configuration file or PHP array (*see [Creating the Config File](#creating-the-config-file)*)

[Back to top](#table-of-contents)

## Installation

If you are familiar with the command line, you may install this module using Composer. https://getcomposer.org/

```command-line
$ composer require thoughtful-web/settings-page-wp
```

You may also download it to a different directory in your project to meet requirement #3. You can use a [release](https://github.com/thoughtful-web/settings-page-wp/releases), the [source code](https://github.com/thoughtful-web/settings-page-wp), or the command line:

```command-line
$ mkdir lib/thoughtful-web
$ cd lib/thoughtful-web
$ git clone https://github.com/thoughtful-web/settings-page-wp
```

To clone a specific tagged version:

```command-line
$ git clone --depth 1 --branch v0.9.10 https://github.com/thoughtful-web/settings-page-wp
```

[Back to top](#table-of-contents)

## Simple Implementation

The simplest implementation of this module is to include it with the Composer autoloader and add a configuration file at `./config/thoughtful-web/settings/settings.php` or `./config/thoughtful-web/settings/settings.json`. Then declare the Settings from that configuration file by creating a new instance of the Settings page in your Plugin's main file like this:  

```php
require __DIR__ . '/vendor/autoload.php;
new \ThoughtfulWeb\SettingsPageWP\Page();
```

Retrieving an option from the database is as simple as [`get_option()`](https://developer.wordpress.org/reference/functions/get_option/):

```php
$my_option = get_option( 'my_option' );
```

[Back to top](#table-of-contents)

## Implementation

To load the Settings class with (or without) a configuration parameter you should know the accepted values:

```php
@param string|array $config (Optional) The Settings page configuration parameters.
                            Either a configuration file name, file path, or array.
```

This class will load a file using an `include` statement if it is a PHP file or using `file_read_contents` it is a JSON file. Here is an explanation of the possible values for this parameter:

1. **No parameter** assumes there is a configuration file located here: `./config/thoughtful-web/settings/settings.php`. Example:  
   a. `new \ThoughtfulWeb\SettingsPageWP\Page();`  

2. **File name** accepts a PHP or JSON file name and requires the file to be in the directory `./config/thoughtful-web/settings/{file}`. Examples:  
   a. `new \ThoughtfulWeb\SettingsPageWP\Page( 'filename.php' );`  
   b. `new \ThoughtfulWeb\SettingsPageWP\Page( 'filename.json' );`  

3. **File path** can be any location on your server, as long as the `./src/Settings/Config.php` class file has read access to it. Examples:  
   a. `new \ThoughtfulWeb\SettingsPageWP\Page( __DIR__ . '/config/settings.json' );`  
   b. `new \ThoughtfulWeb\SettingsPageWP\Page( '/home/website/settings.php' );`  

4. **Array** The configuration values in their final state.

**Note:** Call the class without an action hook or within an action hook early enough in the execution order to not skip the WordPress actions, filters, and functions used in this feature's class files. It is yet to be determined which action hooks are compatible with this class's instantiation.

[Back to top](#table-of-contents)

## Creating the Config File

Documentation for this framework is a work in progress. Some documentation for creating a configuration file can be found below. It is recommended to refer to the example configuration files at [`./config/thoughtful-web/settings/settings.example.php`](config/thoughtful-web/settings/settings.example.php) and [./config/thoughtful-web/settings/settings.example.json](config/thoughtful-web/settings/settings.example.json). See [Fields](#fields) for configuration options for each Field type.

```php
return array(
	'method_args'  => array(
		'page_title'  => __( 'My Plugin Settings', 'thoughtful-web' ),
		'menu_title'  => __( 'My Settings', 'thoughtful-web' ),
		'capability'  => 'manage_options',
		'menu_slug'   => 'my-plugin-settings',
		'icon_url'    => 'dashicons-admin-settings',
		'position'    => 1,
	),
	'option_group' => 'my_plugin_settings',
	'description'  => 'Settings for my awesome plugin.',
	'stylesheet'   => array(
		'file' => 'settings.css',
		'deps' => array(),
	),
	'script'       => array(
		'file'      => 'settings.js',
		'deps'      => array(),
		'in_footer' => true,
	),
	'sections'     => array(
		array(
			'section' => 'unique_section_id',
			'title'   => __( 'My Section', 'thoughtful-web' ),
			'fields'  => array(
				array(
					'label' => 'My text field',
					'id'    => 'unique_text_field',
					'type'  => 'text',
				),
			),
		),
		array(
			'section'     => 'unique_section_two_id',
			'title'       => __( 'Included File', 'thoughtful-web' ),
			'description' => __( 'Displaying a helpful file.', 'thoughtful-web' ),
			'include'     => __DIR__ . '/views/file.php',
		),
	),
);
```

The topmost configuration array accepts six parameters: method_args, description, option_group, stylesheet, script, and sections.

### method_args

The "method_args" key is an array and applies its values to the add_menu_page function, or the add_submenu_page function if instead of an "icon_url" parameter you provide a "parent_slug" parameter.

Documentation:
1. https://developer.wordpress.org/reference/functions/add_menu_page/
2. https://developer.wordpress.org/reference/functions/add_submenu_page/

### description

The "description" key is a text description of the menu page and appears just below the title.

### option_group

The "option_group" key is the slug name of the option group which settings are registered to.

### stylesheet

The "stylesheet" key allows you to register and enqueue your stylesheet file for the Settings page.

### script

The "script" key allows you to register and enqueue your javascript file for the Settings page.

### sections

The "sections" key accepts an array of Section configurations, each with either an "include" or "fields" key to determine their main content.

[Back to top](#table-of-contents)

## Sections

A Section requires a "section" and "title" value and either a "fields" or "include" value. Example:

You may include a file by path reference in the Section configuration using the "include" value, which accepts an absolute file path string. Example:

```php
...
array(
	'section'     => 'section_error_logs',
	'title'       => __( 'Error Logs', 'thoughtful-web' ),
	'description' => __( 'Displaying error logs.', 'thoughtful-web' ),
	'include'     => __DIR__ . '/views/file.php', // Optional
),
...
```

[Back to top](#table-of-contents)

## Fields

The following Fields are available and link to their full configuration instructions.

1. [Checkboxes](docs/fields/checkbox.md)
2. [Color](docs/fields/color.md)
3. [Email](docs/fields/email.md)
4. [Number](docs/fields/number.md)
5. [Phone](docs/fields/phone.md)
6. [Radio](docs/fields/radio.md)
7. [Select](docs/fields/select.md)
8. [Text](docs/fields/text.md)
9. [Textarea](docs/fields/textarea.md)
10. [URL](docs/fields/url.md)
11. [WP Editor](docs/fields/wp-editor.md)

This is the most basic field configuration:

```php
...
array(
	'label' => 'My Text Field',
	'id'    => 'unique_text_field_option',
	'type'  => 'text',
),
...
```

[Back to top](#table-of-contents)

## Additional Documentation
1. [Field Configuration](docs/field-configuration.md)
2. [Action and Filter Reference](docs/action-and-filter-reference.md)
3. [Roadmap](docs/roadmap.md)
4. [Development Installation and Notes](docs/development.md)