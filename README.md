# Thoughtful Web Settings Pages for WordPress

>Copyright Zachary Kendall Watkins 2022.  
>Free open source software under the GNU GPL-2.0+ License.  

This library generates both a Settings page and fully qualified Options for each of its fields from a single configuration file.

All HTML attributes for form fields are supported in the configuration and "pattern" attributes are validated for both the form and in the Option's sanitization filter hook. Each Field is a separate Option and all WordPress filters and actions which apply to Options can be used for them.

## Features

1. Settings page generation from a configuration file.
2. Wrapped around the Core WordPress Settings and Options APIs.
3. Each Field creates and updates an individual database Option, which has advantages when you use hooks and filters.
4. Each Field is validated in a manner similar to Core WordPress options.
5. If a Field type supports it you can add the "pattern" attribute to further validate against a regular expression in both the page and the server whenever calling `update_option`.
6. Include stylesheet and/or script file parameters in the configuration file.
7. Include default Field values to automatically load them into the database. If the field is ever emptied these values will load again.
8. Zero dependencies beyond WordPress itself.
9. Configure and generate multiple pages or subpages.

## Requirements

1. WordPress 5.4 and above.
2. PHP 7.3.5 and above.

## Installation

`composer require thoughtful-web/settings-page-wp`

To install this module from Github using Composer, add it as a repository to the composer.json file:

```
{
    "name": "thoughtful-web/settings-page-wp",
    "description": "WordPress Settings page generator and validator released as free open source software under GNU GPL-2.0+ License",
	"repositories": [
		{
			"type": "vcs",
			"url": "https://github.com/thoughtful-web/settings-page-wp"
		}
	],
	"require": {
		"thoughtful-web/settings-page-wp": "dev-main"
	}
}
```

Then either use Composer's autoloader or require the file directly in your PHP.

## Simplest Implementation

The simplest implementation of this module is to include it with the autoloader or by direct file reference and add a configuration file at `./config/thoughtful-web/settings/settings.php` or `./config/thoughtful-web/settings/settings.json`. Then declare the Settings from that configuration file by creating a new instance of the Settings page in your Plugin's main file like this:  

```
new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings();
```

## Implementing The Class

To load the Settings class with (or without) a configuration parameter you should know the accepted values:

```
@param string|array $config (Optional) The Settings page configuration parameters.
                            Either a configuration file name, file path, or array.
```

This class will load a file using an `include` statement if it is a PHP file or using `file_read_contents` it is a JSON file. Here is an explanation of the possible values for this parameter:

1. **No parameter** assumes there is a configuration file located here: `./config/thoughtful-web/settings/settings.php`. Example:  
   i. `new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings();`  

2. **File name** accepts a PHP or JSON file name and requires the file to be in the plugin's root directory at `./config/thoughtful-web/settings/`. Examples:  
   i. `new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings( 'filename.php' );`  
   ii. `new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings( 'filename.json' );`  

3. **File path** can be any location on your server, as long as the `./src/Admin/Page/Settings/Config.php` class file has read access to it. Examples:  
   i. `new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings( '/config/settings.php' );`  
   i. `new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings( '/public_html/wp-content/plugins/wordpress-plugin-name/settings.json' );`  

4. **Array** The configuration parameters in their final state.

**Note:** Call the class without an action hook or within an action hook early enough in the execution order to not skip the WordPress actions, filters, and functions used in this feature's class files. It is yet to be determined which action hooks are compatible with this class's instantiation.

## Creating the Config File

I am still writing this documentation and Configuration file instructions are on my list.

Some documentation for creating a configuration file can be found below. You should check out the example configuration file(s) at `./config/thoughtful-web/settings/settings.example.php`. Consider checking out the class variables of each **Field** class file to see which HTML attributes they support - these must be configured in a Field's `data_args` array member in `(string) key : (string) value` format.

## Topmost Configuration

The topmost configuration array accepts six parameters: method_args, description, option_group, stylesheet, script, and sections.

### method_args

The "method_args" parameter applies its parameters to the add_menu_page function, or the add_submenu_page function if instead of an "icon_url" parameter you provide a "parent_slug" parameter.

### description

The "description" parameter is a text description of the menu page and appears just below the title.

### option_group

The "option_group" parameter is the slug name of the option group which settings are registered to.

### stylesheet

The "stylesheet" parameter allows you register and enqueue your stylesheet file for the Settings page.

### stylesheet

The "stylesheet" parameter allows you to register and enqueue your stylesheet file for the Settings page.

### script

The "script" parameter allows you to register and enqueue your javascript file for the Settings page.

```
array(
	'method_args'  => array(
		'page_title'  => __( 'My Settings', 'thoughtful-web' ),
		'menu_title'  => __( 'My Settings', 'thoughtful-web' ),
		'capability'  => 'manage_options',
		'menu_slug'   => 'my-settings',
		'icon_url'    => 'dashicons-admin-settings',
		'position'    => 1,
	),
	'description'  => 'Settings for error monitoring features.',
	'option_group' => 'debug_settings',
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
			'section' => 'section_error_logs',
			'title'   => __( 'Error Logs', 'thoughtful-web' ),
			'fields'  => array(
				array(
					'label' => 'My text field',
					'id'    => 'unique_text_field',
					'type'  => 'text',
			),
		),
	),
);
```

## Sections

A Section declaration requires the "section" and "title" attributes and either a "fields" or "include" parameter. Example:

```
array(
	'section' => 'section_error_logs',
	'title'   => __( 'Error Logs', 'thoughtful-web' ),
	'fields'  => array(
		array(
			'label' => 'My text field',
			'id'    => 'unique_text_field',
			'type'  => 'text',
	),
),
```

You may include a file by path reference in the Section configuration using the "include" property, which accepts an absolute file path string. Example:

```
array(
	'section'     => 'section_error_logs',
	'title'       => __( 'Error Logs', 'thoughtful-web' ),
	'description' => __( 'Displaying error logs.', 'thoughtful-web' ),
	'include'     => __DIR__ . '/views/file.php',
),
```

## Fields

Here is the most basic field declaration:

```
array(
	'label' => 'My Text Field',
	'id'    => 'unique_text_field_option',
	'type'  => 'text',
)
```

Here is an example field declaration using optional parameters:

```
array(
	'label'       => 'My Text Field',
	'id'          => 'unique_text_field_option',
	'type'        => 'text',
	'description' => 'My text field description',
	'placeholder' => 'my placeholder',
	'data_args'   => array( // Meaning you don't have to declare the data_args at all.
		'default'       => 'A default value',
		'data-lpignore' => 'true', // Accepts any data attribute. LastPass ignores fields with this data attribute.
		'size'          => '40', // HTML "size" attribute.
	),
),
```

The following Field types are supported. Notes on each Field type's configuration and behavior follow. Refer to their class files to see supported HTML attributes which, if declared, must be in the "data_args" member of the field's configuration.

1. Checkbox
2. Checkboxes
3. Color
4. Email
5. Number
6. Phone
7. Radio
8. Select
9. Text
10. Textarea
11. URL
12. WP Editor (WYSIWYG editor)

## Field Configuration

Here is a guide for implementing each Field type. You may also wish to refer to the source code for each Field which has its own documentation in the files.

### Checkbox

The Checkbox field uses the "choice" declaration to configure a single checkbox field whose value is input into the database as a string. Multiple checkboxes may be declared using "choices" instead of "choice". Each choice therein follows a value => label format. The "default" data argument of the singular Checkbox configuration will be changed soon to imitate the multiple Checkbox declaration. Required values are: label, id, type, choice.

```
array(
	'label'       => 'My Checkbox Field', // Required.
	'id'          => 'unique_checkbox_field', // Required.
	'type'        => 'checkbox', // Required.
	'description' => 'My checkbox field description',
	'choice'      => array( // Required.
		'1' => 'My Choice',
	),
	'data_args'   => array(
		'default' => array(
			'1' => 'My Choice',
		),
	),
),
```

Multiple checkboxes are declared as shown below:

```
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
	'data_args' => array(
		'default' => array(
			'option_one',
			'option_two',
		),
	),
),
```

### Text

The Text field is the simplest field to implement. Required values are: label, id, type.

```
array(
	'label'       => 'My Text Field', // Required.
	'id'          => 'unique_text_field',
	'type'        => 'text',
	'description' => 'My text field description',
	'placeholder' => 'my placeholder',
	'data_args'   => array(
		'default'       => 'A thoughtful, optional, default value',
		'data-lpignore' => 'true',
		'size'          => '40',
	),
),
```

### Color

The Color field uses Iris from WordPress's script files to render a color picker. Required values are: label, id, type.

```
array(
	'label'       => 'My Color Field',
	'id'          => 'unique_color_field',
	'type'        => 'color',
	'description' => 'My color field description',
	'data_args'   => array(
		'default' => '#000000',
	),
),
```

## Roadmap

Features, changes, and fixes which I plan on implementing:

1. Continue developing the documentation.
