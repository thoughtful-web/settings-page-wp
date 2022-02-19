# Select

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Select*

## Table of Contents

1. [Basic Configuration](#basic-configuration)
2. [Multiselect](#multiselect)
3. [Supported Data Arguments](#supported-data-arguments)  
   a. [HTML Attributes](#html-attributes)  
   b. [Settings API Parameters](#settings-api-parameters)

This Field supports single and multiselect configurations (shown below). The Select field also supports a "prompt" configuration value for customizing the first `<option>` element's label to describe what a user should do. The default value is "Please choose an option".

## Basic Configuration

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

[Back to top](#select)

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

[Back to top](#select)

## Supported Data Arguments

### HTML Attributes

Supported data arguments for HTML attributes are listed below. Provide a **string** value to add the attribute with a value. Provide a **boolean true** value to add the attribute without any value. To learn how to use these attributes see [MDN's select documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/select).

* __'class'__  
  *(string)*
  Add a "class" attribute to all checkbox elements.
* __'data-*'__  
  *(true | string)*
  Add a data attribute to all checkbox elements.
* __'disabled'__  
  *(true | string)*
  On the settings page, this argument prevents the element from being changed and excludes its value from the form's submitted values. On the server, this argument uses the option sanitization filter to disallow changes to the value when performed using `update_option()`.
* __'multiple'__  
  *(true | string)*
  Whether to allow multiple values.
* __'readonly'__  
  *(true | string)*
  On the settings page, this argument prevents the element from being changed but includes its value with the form's submitted values. On the server, this argument uses the option sanitization filter to disallow changes to the value when performed using `update_option()`.
* __'required'__  
  *(true)*
  On the settings page, this argument prevents the element from being empty when the form is submitted. On the server, this argument uses the option sanitization filter to disallow an empty string, array, or null value from being assigned to the option when performed using `update_option()`.
* __'size'__  
  *(string)*
  "If the control is presented as a scrolling list box (e.g. when multiple is specified), this attribute represents the number of rows in the list that should be visible at one time. Browsers are not required to present a select element as a scrolled list box. The default value is 0." [[1](#sources)]

[Back to top](#select)

### Settings API Parameters

These arguments are passed to the Core WordPress function register_setting(), although the 'sanitize_callback' is preprocessed before being passed to this function. See https://developer.wordpress.org/reference/functions/register_setting/.

* __'default'__  
  *(mixed) (Optional)* 
  "Default value when calling get_option()." [[2]](#sources) Provide a string if the field is configured to provide a single choice. Provide an array if the field is configured to allow the user to enable more than once choice in a field, such as with multiple checkboxes or a multi-select dropdown.
* __'description'__  
  *(string) (Optional)* 
  Used by the REST API. "A description of the data attached to this setting." [[2]](#sources)  
  *Default: ''*
* __'sanitize_callback'__  
  *(bool | callable) (Optional)* 
  Accepts true, false, or a callable function in string or array format. Default true, which enables the default sanitization operations provided by this library. A value of false disables the default sanitization. A value of callable hooks your own function to the sanitization step.  
  *Default: true*
* __'show_in_rest'__  
  *(boolean) (Optional)* 
  "Whether data associated with this setting should be included in the REST API. When registering complex settings, this argument may optionally be an array with a 'schema' key." [[2]](#sources)  
  *Default: false*
* __'type'__  
  *(string) (Optional)* 
  "Only used by the REST API to define the schema associated with the setting and to implement sanitization over the REST API." [[3]](#sources) "The type of data associated with this setting. Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'." [[2]](#sources)  
  *Default: 'string'*

[Back to top](#select)

### Other Arguments

* __'capability'__  
  *(string) (Required)* 
  The user capability required for this field's control to be displayed to a user visiting the settings page.  
  *Default: The value of the method_args capability argument.*

[Back to top](#select)

## Sources

1. https://developer.mozilla.org/en-US/docs/Web/HTML/Element/select
2. https://developer.wordpress.org/reference/functions/register_setting/
3. https://developer.wordpress.org/reference/functions/register_setting/#div-comment-3050

[Back to top](#select)
