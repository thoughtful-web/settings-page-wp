# Configuration File

*[Home](../README.md) / Configuration File*

## Table of Contents

1. [Example File](#example-file)
2. [File Location](#file-location)
3. [Specification](#specification)

## Example File

An example configuration file is included below to give you an idea of what yours should look like. It is recommended to refer to the example configuration files at [*./config/thoughtful-web/settings.example.php*](config/thoughtful-web/settings.example.php) and [*./config/thoughtful-web/settings.example.json*](config/thoughtful-web/settings.example.json). Some field types have a different setup than the others - see [field-configuration.md](./field-configuration.md) for configuration options for each Field type.

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

[Back to top](#configuration-file)

## File Location

There are several places where you may keep your configuration file. Tell the `Page()` class where it is using the options below:

1. Provide no parameter to the class, and it assumes there is a configuration file located at *./config/thoughtful-web/settings.php* or *./config/thoughtful-web/settings.json*. Example:  
   a. `new \ThoughtfulWeb\SettingsPageWP\Page();`  

2. Provide a file name to the class, and it accepts a PHP or JSON file name from the directory *./config/thoughtful-web/*. Examples:  
   a. `new \ThoughtfulWeb\SettingsPageWP\Page( 'filename.php' );`  
   b. `new \ThoughtfulWeb\SettingsPageWP\Page( 'filename.json' );`  

3. Provide a file path to the class from anywhere on your server, as long as the *./src/Settings/Config.php* class file has read access to it. Examples:  
   a. `new \ThoughtfulWeb\SettingsPageWP\Page( __DIR__ . '/config/settings.json' );`  
   b. `new \ThoughtfulWeb\SettingsPageWP\Page( '/home/website/settings.php' );`  

4. Provide a PHP array to the class which contains the configuration array in its final state. This option allows the most flexibility.

**Note:** Call the class without an action hook or within an action hook early enough in the execution order to not skip the WordPress actions, filters, and functions used in this feature's class files. It is yet to be determined which action hooks are compatible with this class's instantiation.

[Back to top](#configuration-file)

## Specification

Specifications for the configuration file are included below. Text in quotations followed by a number in braces serve as a citation from the WordPress Developer Reference at https://developer.wordpress.org. These citations indicate how a configuration parameter is used by a WordPress function.

* __'method_args'__  
  *(array) (Required)* The "method_args" value is applied to the WordPress [add_menu_page function](https://developer.wordpress.org/reference/functions/add_menu_page/), or the [add_submenu_page function](https://developer.wordpress.org/reference/functions/add_submenu_page/) if you provide a "parent_slug" key value instead of an "icon_url" parameter. Accepts the following keys.
  * __'page_title'__  
    *(string) (Required)*
	"The text to be displayed in the title tags of the page when the menu is selected." [1]  
	*Default: 'A Thoughtful Settings Page'*
  * __'menu_title'__  
    *(string) (Required)*
	"The text to be used for the menu." [1]  
	*Default: 'Thoughtful Settings'*
  * __'capability'__  
    *(string) (Required)*
	"The capability required for this menu to be displayed to the user." [1]  
	*Default: 'manage_options'*
  * __'menu_slug'__  
    *(string) (Required)*
	"The slug name to refer to this menu by. Should be unique for this menu page and only include lowercase alphanumeric, dashes, and underscores characters to be compatible with sanitize_key()." [1]  
	*Default: 'thoughtful-settings'*
  * __'icon_url'__  
    *(string) (Optional)*
	"The URL to the icon to be used for this menu. [...] Pass a base64-encoded SVG using a data URI, which will be colored to match the color scheme. This should begin with 'data:image/svg+xml;base64,'. [...] Pass the name of a Dashicons helper class to use a font icon, e.g. 'dashicons-chart-pie'. [...] Pass 'none' to leave div.wp-menu-image empty so an icon can be added via CSS." [1]  
	*Default: 'dashicons-admin-settings'*
  * __'position'__  
    *(int) (Optional)*
	"The position in the menu order this item should appear." [1]  
	*Default value: null*
  * __'parent_slug'__  
    *(string) (Optional)*
	"The slug name for the parent menu (or the file name of a standard WordPress admin page)." [2]
* __'option_group'__  
  *(string) (Required)*
  "A settings group name. Should correspond to an allowed option key name. Default allowed option key names include 'general', 'discussion', 'media', 'reading', 'writing', and 'options'." [3]  
  *Default value: 'options'*
* __'description'__  
  *(string) (Optional)*
  A description of the menu page which appears just below the title.  
  *Default value: ''*
* __'stylesheet'__  
  *(array) (Optional)*
  Register and enqueue a stylesheet file on the Settings Page.
  * __'file'__  
    *(string)*
	A file path from your plugin or theme's root directory. Must begin with a slash. Example: */scripts/settings.js*
  * __'deps'__  
    *(string[]) (Optional)*
	Dependencies that must be loaded before the registered stylesheet is loaded.  
	*Default value: empty array*
* __'script'__  
  *(array) (Optional)*
  Register and enqueue a javascript file on the Settings Page.
  * __'file'__  
    *(string)*
	A file path from your plugin or theme's root directory. Must begin with a slash. Example: */styles/settings.css*
  * __'deps'__  
    *(string[]) (Optional)*
	Dependencies that must be loaded before the registered stylesheet is loaded.  
	*Default value: empty array*
* __'sections'__  
  *(array[]) (Required)*
  Accepts one or more arrays with Section configurations. Each Section configuration accepts the following keys.
  * __'section'__  
    *(string) (Required)*
	A unique section ID.
  * __'title'__  
    *(string) (Required)*
	A section title to display to the user.
  * __'description'__  
    *(string) (Optional)*
	Descriptive HTML rendered below the section title.   
	*Default value: ''*
  * __'include'__  
    *(string) (Optional)*
	An absolute file path to load after the description and before the fields.
  * __'fields'__  
    *(array[]) (Optional)*
	Arrays which configure database options that are rendered to a user as settings page fields. Each field configuration array accepts the following keys.
	* __'label'__  
	  *(string) (Required)*
	  "$title [...] Formatted title of the field. Shown as the label for the field during output." [5]
	* __'id'__  
	  *(string) (Required)*
	  "Slug-name to identify the field. Used in the 'id' attribute of tags." [5] Also the database option table key. **NOTE: It is recommended to namespace your options or take other measures to ensure you do not override a pre-existing database option.**
	* __'type'__  
	  *(string) (Required)*
	  The logic to apply to the presentation and validation of this Field. Accepts 'checkbox', 'color', 'email', 'number', 'password', 'tel', 'radio', 'select', 'text', 'textarea', 'url', or 'wp_editor'.
	* __'description'__  
	  *(string) (Optional)*
	  The HTML rendered on the Settings Page after the form element which represents this Field.
	* __'data_args'__  
	  *(array) (Optional)*
	  Data used to configure the setting's use by WordPress Core APIs, this library, and the Settings Page HTML attributes of its form element. Accepts the following keys.
	  * __'default'__  
        *(mixed) (Optional)*
		"Default value when calling get_option()." [3] Provide a string if the field is configured to provide a single choice. Provide an array if the field is configured to allow the user to enable more than once choice in a field, like with multiple checkboxes or a multi-select dropdown.  
	  * __'description'__  
        *(string) (Optional)*
		"A description of the data attached to this setting." [3] "Only used by the REST API." [4]  
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

[Back to top](#configuration-file)
