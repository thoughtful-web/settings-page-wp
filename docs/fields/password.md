# Password

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Password*

The Password field supports a "copy_button" configuration value for providing a button to use to copy the text in the password field and control the text in that button. Omit the key to not provide a button (default). Use the configuration below as a guide for implementation.

## Basic Config

Example with required attributes:

```php
...
array(
	'id'    => 'api_key',
	'label' => 'API Key',
	'type'  => 'password',
),
...
```

Example with default value and copy_button value:

```php
...
array(
	'id'        => 'api_key',
	'label'     => 'API Key',
	'type'      => 'password',
	'data_args' => array(
		'copy_button' => 'Copy API key',
		'default'     => 'myapikey',
	),
),
...
```

## Supported Data Arguments

### HTML Attributes

Supported data arguments for HTML attributes are listed below. Provide a **string** value to add the attribute with a value. Provide a **boolean true** value to add the attribute without any value. To learn how to use these attributes see [MDN's password input documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/password).

* autocomplete
* inputmode
* class
* data-*
* disabled
* list
* maxlength
* minlength
* pattern
* placeholder
* readonly
* required
* size

### Settings API Parameters

These arguments are passed to the Core WordPress function register_setting(), although the 'sanitize_callback' is preprocessed before being passed to this function. See https://developer.wordpress.org/reference/functions/register_setting/.

* __'default'__  
  (mixed) (Optional)  
  "Default value when calling get_option()." [[2]](#sources) Provide a string if the field is configured to provide a single choice. Provide an array if the field is configured to allow the user to enable more than once choice in a field, such as with multiple checkboxes or a multi-select dropdown.
* __'description'__  
  (string) (Optional) (Default: '')  
  Used by the REST API. "A description of the data attached to this setting." [[2]](#sources)
* __'sanitize_callback'__  
  (bool | callable) (Optional) (Default: true)  
  Accepts true, false, or a callable function in string or array format. Default true, which enables the default sanitization operations provided by this library. A value of false disables the default sanitization. A value of callable hooks your own function to the sanitization step.
* __'show_in_rest'__  
  (boolean) (Optional) (Default: false)  
  "Whether data associated with this setting should be included in the REST API. When registering complex settings, this argument may optionally be an array with a 'schema' key." [[2]](#sources)
* __'type'__  
  (string) (Optional) (Default: 'string')  
  "Only used by the REST API to define the schema associated with the setting and to implement sanitization over the REST API." [[3]](#sources) "The type of data associated with this setting. Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'." [[2]](#sources)

## Sources

1. https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input
2. https://developer.wordpress.org/reference/functions/register_setting/
3. https://developer.wordpress.org/reference/functions/register_setting/#div-comment-3050

[Back to top](#password)
