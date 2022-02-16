# WP Editor

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / WP Editor*

## Table of Contents

1. [Basic Configuration](#basic-configuration)
2. [Supported Data Arguments](#supported-data-arguments)  
   a. [HTML Attributes](#html-attributes)  
   b. [Settings API Parameters](#settings-api-parameters)

WP Editor fields provide a Core WordPress rich content editor via the [`wp_editor()`](https://developer.wordpress.org/reference/functions/wp_editor/) function. You may use this to create HTML that will be inserted into an option's value.

## Basic Configuration

Example with required attributes:

```php
...
array(
	'label' => 'My WP Editor Field',
	'id'    => 'unique_wp_editor_field',
	'type'  => 'wp_editor',
),
...
```

Example with a default value:

```php
...
array(
	'label'     => 'My WP Editor Field',
	'id'        => 'unique_wp_editor_field',
	'type'      => 'wp_editor',
	'data_args' => array(
		'default' => 'my default value',
	),
),
...
```

[Back to top](#wp-editor)

## Supported Data Arguments

### HTML Attributes

Data arguments for HTML attributes are not supported.

### Settings API Parameters

These arguments are passed to the Core WordPress function register_setting(), although the 'sanitize_callback' is preprocessed before being passed to this function. See https://developer.wordpress.org/reference/functions/register_setting/.

* __'default'__  
  *(mixed) (Optional)* 
  "Default value when calling get_option()." [[1]](#sources) Provide a string if the field is configured to provide a single choice. Provide an array if the field is configured to allow the user to enable more than once choice in a field, such as with multiple checkboxes or a multi-select dropdown.
* __'description'__  
  *(string) (Optional)* 
  Used by the REST API. "A description of the data attached to this setting." [[1]](#sources)  
  *Default: ''*
* __'sanitize_callback'__  
  *(boolean | callable) (Optional)* 
  Accepts true, false, or a callable function in string or array format. Default true, which enables the default sanitization operations provided by this library. A value of false disables the default sanitization. A value of callable hooks your own function to the sanitization step.  
  *Default: true*
* __'show_in_rest'__  
  *(boolean) (Optional)* 
  "Whether data associated with this setting should be included in the REST API. When registering complex settings, this argument may optionally be an array with a 'schema' key." [[1]](#sources)  
  *Default: false*
* __'type'__  
  *(string) (Optional)* 
  "Only used by the REST API to define the schema associated with the setting and to implement sanitization over the REST API." [[2]](#sources) "The type of data associated with this setting. Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'." [[1]](#sources)  
  *Default: 'string'*

[Back to top](#wp-editor)

### Other Arguments

* __'capability'__  
  *(string) (Required)* 
  The user capability required for this field's control to be displayed to a user visiting the settings page.  
  *Default: The value of the method_args capability argument.*

[Back to top](#wp-editor)

## Sources

1. https://developer.wordpress.org/reference/functions/register_setting/
2. https://developer.wordpress.org/reference/functions/register_setting/#div-comment-3050

[Back to top](#wp-editor)
