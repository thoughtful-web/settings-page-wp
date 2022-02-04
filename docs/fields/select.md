# Select

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Select*

This Field supports single and multiselect configurations (shown below). The Select field also supports a "prompt" configuration value for customizing the first `<option>` element's label to describe what a user should do. The default value is "Please choose an option".

## Basic Config

Example with required attributes:

```php
...
array(
	'label'   => 'My Select Field',
	'id'      => 'unique_select_field',
	'type'    => 'select',
	'choices' => array(
		'option_one'   => 'Option 1',
		'option_two'   => 'Option 2',
		'option_three' => 'Option 3',
	),
),
...
```

Example with default value and prompt:

```php
...
array(
	'label'     => 'My Select Field',
	'id'        => 'unique_select_field',
	'type'      => 'select',
	'prompt'    => 'Please choose an option',
	'choices'   => array(
		'option_one'   => 'Option 1',
		'option_two'   => 'Option 2',
		'option_three' => 'Option 3',
	),
	'data_args' => array(
		'default' => 'option_one',
	)
),
...
```

## Multiselect

If you configure the field as a multiselect field, and choose to configure a default value, then you must declare the default value as an array of values.

Example:

```php
...
array(
	'label'     => 'My Multi-select Field',
	'id'        => 'unique_multiselect_field',
	'type'      => 'select',
	'choices'   => array(
		'option_one'   => 'Option 1',
		'option_two'   => 'Option 2',
		'option_three' => 'Option 3',
	),
	'data_args' => array(
		'multiple' => true,
		'default'  => array('option_one', 'option_two'),
	),
),
...
```

## Supported Data Arguments

### HTML Attributes

Supported data arguments for HTML attributes are listed below. Provide a **string** value to add the attribute with a value. Provide a **boolean true** value to add the attribute without any value. To learn how to use these attributes see [MDN's select documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/select).

* __'class'__  
  (string) Add a "class" attribute to all checkbox elements.
* __'data-*'__  
  (true | string) Add a data attribute to all checkbox elements.
* __'readonly'__  
  (true | string) Disallow changes to the setting's value on both the settings page and the server during the sanitization step if `update_option()` is used.
* __'multiple'__  
  (true | string) Whether to allow multiple values.
* __'size'__  
  (string) "If the control is presented as a scrolling list box (e.g. when multiple is specified), this attribute represents the number of rows in the list that should be visible at one time. Browsers are not required to present a select element as a scrolled list box. The default value is 0." [[1](#sources)]

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

1. https://developer.mozilla.org/en-US/docs/Web/HTML/Element/select
2. https://developer.wordpress.org/reference/functions/register_setting/
3. https://developer.wordpress.org/reference/functions/register_setting/#div-comment-3050

[Back to top](#select)
