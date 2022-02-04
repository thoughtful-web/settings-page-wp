# Field Configuration

*[Home](../README.md) / Field Configuration*

## Table of Contents

1. [Fields](#fields)
2. [Shared Parameters](#shared-parameters)

## Fields

Listed below are documents describing how to implement each Field into your configuration file.

1. [Checkbox](docs/fields/checkbox.md)
2. [Color](docs/fields/color.md)
3. [Email](docs/fields/email.md)
4. [Number](docs/fields/number.md)
5. [Password](docs/fields/password.md)
5. [Phone](docs/fields/phone.md)
6. [Radio](docs/fields/radio.md)
7. [Select](docs/fields/select.md)
8. [Text](docs/fields/text.md)
9. [Textarea](docs/fields/textarea.md)
10. [URL](docs/fields/url.md)
11. [WP Editor](docs/fields/wp-editor.md)

[Back to top](#field-configuration)

## Shared Parameters

Field configuration parameters common to all Field types are described below. Some parameters of `data_args` are passed to the Core WordPress function register_setting(), although the 'sanitize_callback' is preprocessed before being passed to this function. See https://developer.wordpress.org/reference/functions/register_setting/.

```php
...
array(
	'id'          => 'my_option_id',
	'type'        => 'checkbox',
	'description' => 'What this checkbox is for.',
	'data_args'   => array(
    'default'           => '',
		'description'       => '',
		'sanitize_callback' => true,
		'show_in_rest'      => false,
		'type'              => 'string',
	),
),
...
```

* __'id'__  
  (string) (Required)  
  The name of an option to sanitize and save. It is recommended to namespace your option or take other measures 
* __'type'__    
  (string) (Required)  
  The type of Field the Settings Page will provide to control the Option. Valid values are 'checkbox', 'color', 'email', 'number', 'password', 'tel', 'radio', 'select', 'text', 'textarea', 'url', and 'wp_editor'.
* __'description'__  
  (string) (Optional)  
  A field description for the form to include below it.
* __'data_args'__    
  (array) (Optional)  
  Data used to configure the setting's use by WordPress Core APIs, this library, and the Settings Page HTML attributes of its form element.  
  * __'default'__  
    (mixed) (Optional)  
    "Default value when calling get_option()." [[1]](#sources) Provide a string if the field is configured to provide a single choice. Provide an array if the field is configured to allow the user to enable more than once choice in a field, such as with multiple checkboxes or a multi-select dropdown.  
  * __'description'__  
    (string) (Optional) (Default: '')  
    Used by the REST API. "A description of the data attached to this setting." [[1]](#sources)
  * __'sanitize_callback'__  
    (bool | callable) (Optional) (Default: true)  
    Accepts true, false, or a callable function in string or array format. Default true, which enables the default sanitization operations provided by this library. A value of false disables the default sanitization. A value of callable hooks your own function to the sanitization step.
  * __'show_in_rest'__  
    (boolean) (Optional) (Default: false)  
    "Whether data associated with this setting should be included in the REST API. When registering complex settings, this argument may optionally be an array with a 'schema' key." [[1]](#sources)
  * __'type'__  
    (string) (Optional) (Default: 'string')  
    "Only used by the REST API to define the schema associated with the setting and to implement sanitization over the REST API." [[2]](#sources) "The type of data associated with this setting. Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'." [[1]](#sources)

## Sources

1. https://developer.wordpress.org/reference/functions/register_setting/
2. https://developer.wordpress.org/reference/functions/register_setting/#div-comment-3050

[Back to top](#field-configuration)
