# Field Configuration

*[Home](../../README.md) / Field Configuration*

Listed below are documents describing how to implement each Field into your configuration file. You may also wish to refer to the source code for each Field which has its own documentation in the files.

## Fields

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

## Shared Configuration Parameters

Field configuration parameters common to all Field types are described below. Some are only necessary when using the WordPress REST API. See the official [`register_settings()`](https://developer.wordpress.org/reference/functions/register_setting/) code reference for full documentation.

```php
'id'        => 'my_option_id',
'type'      => 'checkbox',
'data_args' => array(
	'sanitize_callback' => true,
	'show_in_rest'      => false,
	'type'              => 'string',
	'description'       => '',
),
```

**id**
(string) (Required) The name of an option to sanitize and save. It is recommended to namespace your option or take other measures 

**type**
(string) (Required) The type of Field the form will provide to control the Option. Valid values are 'checkbox', 'color', 'email', 'number', 'password', 'tel', 'radio', 'select', 'text', 'textarea', 'url', and 'wp_editor'.

**description**
(string) (Optional) A field description for the form to include below it.

**data_args**  
(array) (Optional) Data used to describe the setting when registered.
* 'type'  
  (string) The type of data associated with this setting. Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'.  
* 'description'  
  (string) A description of the data attached to this setting.  
* 'sanitize_callback'  
  (callable) A callback function that sanitizes the option's value.  
* 'show_in_rest'  
  (bool|array) Whether data associated with this setting should be included in the REST API. When registering complex settings, this argument may optionally be an array with a 'schema' key.  
* 'default'  
  (mixed) Default value when calling get_option().  
