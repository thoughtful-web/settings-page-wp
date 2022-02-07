# Create Settings Pages for WordPress

>Free open source software under the GNU GPL-2.0+ License.  
>Copyright Zachary Kendall Watkins 2021-2022.  

## Introduction

This PHP library uses your configuration file to create a settings page and sanitized database options for your WordPress plugin or theme. Each field you configure is a registered WordPress [Option](https://developer.wordpress.org/plugins/settings/options-api/), so [filters and actions](docs/action-and-filter-reference.md) can observe them.

## Table of Contents

1. [Features](#features)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Implementation](#implementation)  
   a. [Simple Method](#simple)  
   b. [All Methods](#full)  
5. [Configuration File](#configuration-file)
6. [Fields](#fields)
7. [Additional Documentation](#additional-documentation)
8. [Contributing](#contributing)
9. [References](#references)

## Features

1. Settings page generation from a configuration file using Core WordPress [Settings](https://developer.wordpress.org/plugins/settings/settings-api/) and [Options](https://developer.wordpress.org/plugins/settings/options-api/) APIs.
2. Each Field creates and updates a database option which allows you to hook and filter them individually.
3. Each Field is validated on the server in a manner similar to Core WordPress options. Failed server-side validation emits a Settings Page error notice at the top of the page.
4. Further validate a field using regular expressions when its settings page form element supports the `pattern` attribute. This works on the settings page and when a script calls the WordPress [`update_option()`](https://developer.wordpress.org/reference/functions/update_option/) function.
5. Configure a stylesheet and/or script file for the settings page.
6. Configure default Field values to automatically load them into the database. If the field is ever emptied these values will be added again.
7. Zero production dependencies beyond PHP, WordPress, and WordPress included JavaScript (Iris) for the color picker field.
8. Configure and create pages or subpages.

[Back to top](#introduction)

## Requirements

1. WordPress 5.4 and above.
2. PHP 7.3.5 and above.
3. A configuration file or PHP array (*see [Configuration File](#configuration-file)*)
4. This library must exist two directory levels below the plugin or theme's root directory. Examples:  
   a. *./vendor/thoughtful-web/settings-page-wp*  
   b. *./lib/thoughtful-web/settings-page-wp*  

[Back to top](#introduction)

## Installation

If you are familiar with the command line, you may install this module using Composer. https://getcomposer.org/

```command-line
$ composer require thoughtful-web/settings-page-wp
```

You may download it to a different directory in your plugin or theme while still meeting requirement #4. You can use a [release](https://github.com/thoughtful-web/settings-page-wp/releases), the [source code](https://github.com/thoughtful-web/settings-page-wp), or the command line:

```command-line
$ mkdir lib/thoughtful-web
$ cd lib/thoughtful-web
$ git clone https://github.com/thoughtful-web/settings-page-wp
```

To clone a specific tagged version:

```command-line
$ git clone --depth 1 --branch v0.9.11 https://github.com/thoughtful-web/settings-page-wp
```

[Back to top](#introduction)

## Implementation

### Simple Method

The simplest implementation of this module is to include it with the Composer autoloader and add a configuration file at *./config/thoughtful-web/settings/settings.php* or *./config/thoughtful-web/settings/settings.json*. Then declare the Settings from that configuration file by creating a new instance of the Settings page in your Plugin's main file like this:  

```php
require __DIR__ . '/vendor/autoload.php;
new \ThoughtfulWeb\SettingsPageWP\Page();
```

Retrieve an option from the database using the WordPress [`get_option()`](https://developer.wordpress.org/reference/functions/get_option/) function:

```php
$my_option = get_option( 'my_option' );
```

[Back to top](#introduction)

### All Methods

To load the Settings class with (or without) a configuration parameter you should know the accepted values:

```php
...
@param string|array $config (Optional) The Settings page configuration parameters.
                            Either a configuration file name, file path, or array.
...
```

This class will load a file using an `include` statement if it is a PHP file or using the `file_read_contents()` function if it is a JSON file. Here is an explanation of the possible values for this parameter:

1. **No parameter** assumes there is a configuration file located at *./config/thoughtful-web/settings/settings.php* or *./config/thoughtful-web/settings/settings.json*. Example:  
   a. `new \ThoughtfulWeb\SettingsPageWP\Page();`  

2. **File name** accepts a PHP or JSON file name and requires the file to be in the directory *./config/thoughtful-web/settings/*. Examples:  
   a. `new \ThoughtfulWeb\SettingsPageWP\Page( 'filename.php' );`  
   b. `new \ThoughtfulWeb\SettingsPageWP\Page( 'filename.json' );`  

3. **File path** can be any location on your server, as long as the `./src/Settings/Config.php` class file has read access to it. Examples:  
   a. `new \ThoughtfulWeb\SettingsPageWP\Page( __DIR__ . '/config/settings.json' );`  
   b. `new \ThoughtfulWeb\SettingsPageWP\Page( '/home/website/settings.php' );`  

4. **Array** is the configuration array in its final state. Use this to generate values dynamically.

**Note:** Call the class without an action hook or within an action hook early enough in the execution order to not skip the WordPress actions, filters, and functions used in this feature's class files. It is yet to be determined which action hooks are compatible with this class's instantiation.

[Back to top](#introduction)

## Configuration File

An example configuration file is included below to give you an idea of what yours should look like. Following this is a complete list of each configuration parameter, its accepted values, its default value, and a description. It is recommended to refer to the example configuration files at [*./config/thoughtful-web/settings/settings.example.php*](config/thoughtful-web/settings/settings.example.php) and [*./config/thoughtful-web/settings/settings.example.json*](config/thoughtful-web/settings/settings.example.json). See [Fields](#fields) for configuration options for each Field type.

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
			'section' => 'unique_section_one_id',
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

Specifications for the configuration file are included below. Text in quotations followed by a number in braces serve as a citation from the WordPress Developer Reference at https://developer.wordpress.org. These citations indicate how a configuration parameter is used by a WordPress function.

* __'method_args'__  
  *(array) (Required)* The "method_args" value is applied to the WordPress [add_menu_page function](https://developer.wordpress.org/reference/functions/add_menu_page/), or the [add_submenu_page function](https://developer.wordpress.org/reference/functions/add_submenu_page/) if you provide a "parent_slug" key value instead of an "icon_url" parameter. Accepts the following keys.
  * __'page_title'__  
    *(string) (Required)* "The text to be displayed in the title tags of the page when the menu is selected." [1]  
	*Default: 'A Thoughtful Settings Page'*
  * __'menu_title'__  
    *(string) (Required)* "The text to be used for the menu." [1]  
	*Default: 'Thoughtful Settings'*
  * __'capability'__  
    *(string) (Required)* "The capability required for this menu to be displayed to the user." [1]  
	*Default: 'manage_options'*
  * __'menu_slug'__  
    *(string) (Required)* "The slug name to refer to this menu by. Should be unique for this menu page and only include lowercase alphanumeric, dashes, and underscores characters to be compatible with sanitize_key()." [1]  
	*Default: 'thoughtful-settings'*
  * __'icon_url'__  
    *(string) (Optional)* "The URL to the icon to be used for this menu. [...] Pass a base64-encoded SVG using a data URI, which will be colored to match the color scheme. This should begin with 'data:image/svg+xml;base64,'. [...] Pass the name of a Dashicons helper class to use a font icon, e.g. 'dashicons-chart-pie'. [...] Pass 'none' to leave div.wp-menu-image empty so an icon can be added via CSS." [1]  
	*Default: 'dashicons-admin-settings'*
  * __'position'__  
    *(int) (Optional)* "The position in the menu order this item should appear." [1]  
	*Default value: null*
  * __'parent_slug'__  
    *(string) (Optional)* "The slug name for the parent menu (or the file name of a standard WordPress admin page)." [2]
* __'option_group'__  
  *(string) (Required)* "A settings group name. Should correspond to an allowed option key name. Default allowed option key names include 'general', 'discussion', 'media', 'reading', 'writing', and 'options'." [3]  
  *Default value: 'options'*
* __'description'__  
  *(string) (Optional)* A description of the menu page which appears just below the title.  
  *Default value: ''*
* __'stylesheet'__  
  *(array) (Optional)* Register and enqueue a stylesheet file on the Settings Page.
  * __'file'__  
    *(string)* Either a file name to look for in *./config/thoughtful-web/settings/* or the absolute path to a CSS file.
  * __'deps'__  
    *(string[]) (Optional)*
	Dependencies that must be loaded before the registered stylesheet is loaded.  
	*Default value: empty array*
* __'script'__  
  *(array) (Optional)* Register and enqueue a javascript file on the Settings Page.
  * __'file'__  
    *(string)* Either a file name to look for in *./config/thoughtful-web/settings/* or the absolute path to a CSS file.
  * __'deps'__  
    *(string[]) (Optional)*
	Dependencies that must be loaded before the registered stylesheet is loaded.  
	*Default value: empty array*
* __'sections'__  
  *(array[]) (Required)* Accepts one or more arrays with Section configurations. Each Section configuration accepts the following keys.
  * __'section'__  
    *(string) (Required)* A unique section ID.
  * __'title'__  
    *(string) (Required)* A section title to display to the user.
  * __'description'__  
    *(string) (Optional)* Descriptive HTML rendered below the section title.   
	*Default value: ''*
  * __'include'__  
    *(string) (Optional)* An absolute file path to load after the description and before the fields.
  * __'fields'__  
    *(array[]) (Optional)* Arrays which configure database options that are rendered to a user as settings page fields. Each field configuration array accepts the following keys.
	* __'label'__  
	  *(string) (Required)* "$title [...] Formatted title of the field. Shown as the label for the field during output." [5]
	* __'id'__  
	  *(string) (Required)* "Slug-name to identify the field. Used in the 'id' attribute of tags." [5] Also the database option table key. **NOTE: It is recommended to namespace your options or take other measures to ensure you do not override a pre-existing database option.**
	* __'type'__  
	  *(string) (Required)* The logic to apply to the presentation and validation of this Field. Accepts 'checkbox', 'color', 'email', 'number', 'password', 'tel', 'radio', 'select', 'text', 'textarea', 'url', or 'wp_editor'.
	* __'description'__  
	  *(string) (Optional)* The HTML rendered on the Settings Page after the form element which represents this Field.
	* __'data_args'__  
	  *(array) (Optional)* Data used to configure the setting's use by WordPress Core APIs, this library, and the Settings Page HTML attributes of its form element. Accepts the following keys.
	  * __'default'__  
        *(mixed) (Optional)* "Default value when calling get_option()." [3] Provide a string if the field is configured to provide a single choice. Provide an array if the field is configured to allow the user to enable more than once choice in a field, like with multiple checkboxes or a multi-select dropdown.  
	  * __'description'__  
        *(string) (Optional)* "A description of the data attached to this setting." [3] "Only used by the REST API." [4]  
	    *Default value: ''*
      * __'sanitize_callback'__  
        *(bool | callable) (Optional)*  
        Accepts true, false, or a callable function in string or array format. Default true, which enables the default sanitization operations provided by this library. A value of false disables the default sanitization. A value of callable hooks your own function to the sanitization step.  
		*Default value: true*
	  * __'show_in_rest'__  
        *(boolean) (Optional)*  
        "Whether data associated with this setting should be included in the REST API. When registering complex settings, this argument may optionally be an array with a 'schema' key." [3]  
		*Default value: false*
      * __'type'__  
        *(string) (Optional)*  
        "Only used by the REST API to define the schema associated with the setting and to implement sanitization over the REST API." [4] "The type of data associated with this setting. Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'." [3]  
		*Default value: 'string'*

[Back to top](#introduction)

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

[Back to top](#introduction)

## Additional Documentation
1. [Field Configuration](./docs/field-configuration.md)
2. [Action and Filter Reference](./docs/action-and-filter-reference.md)
3. [Roadmap](./docs/roadmap.md)
4. [Development Installation and Notes](./docs/development.md)

[Back to top](#introduction)

## Contributing

I welcome questions and discussion and have opened up Github's features to create a space for this. Please see the [contribution guidelines](./Contributing.md) for details.

[Back to top](#introduction)
## References

1. WordPress Developer Resources; Function: add_menu_page()  
   *https://developer.wordpress.org/reference/functions/add_menu_page/*
2. WordPress Developer Resources; Function: add_submenu_page()  
   *https://developer.wordpress.org/reference/functions/add_submenu_page/*
3. WordPress Developer Resources; Function: register_setting()  
   *https://developer.wordpress.org/reference/functions/register_setting/*
4. WordPress Developer Resources; Comment on Function: register_setting()  
   *https://developer.wordpress.org/reference/functions/register_setting/#div-comment-3050*
5. WordPress Developer Resources; Function: add_settings_field()  
   *https://developer.wordpress.org/reference/functions/add_settings_field/*
6. WordPress Plugin Handbook; Settings API  
   *https://developer.wordpress.org/plugins/settings/settings-api/*
7. WordPress Plugin Handbook; Options API  
   *https://developer.wordpress.org/plugins/settings/options-api/*
8. Composer: A Dependency Manager for PHP  
   *https://getcomposer.org/*

[Back to top](#introduction)
