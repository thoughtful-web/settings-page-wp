# Textarea

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Textarea*

## Table of Contents

1. [Basic Configuration](#basic-configuration)
2. [Supported Data Arguments](#supported-data-arguments)  
   a. [HTML Attributes](#html-attributes)  
   b. [Settings API Parameters](#settings-api-parameters)

The Textarea field is very simple.

## Basic Configuration

Example with required attributes:

```php
...
array(
	'label' => 'My Textarea Field',
	'id'    => 'unique_textarea_field',
	'type'  => 'textarea',
),
...
```

Example with a default value:

```php
...
array(
	'label'     => 'My Textarea Field',
	'id'        => 'unique_textarea_field',
	'type'      => 'textarea',
	'data_args' => array(
		'default' => 'My default value',
	),
),
...
```

[Back to top](#textarea)

## Supported Data Arguments

### HTML Attributes

Supported data arguments for HTML attributes are listed below. Provide a **string** value to add the attribute with a value. Provide a **boolean true** value to add the attribute without any value. To learn how to use these attributes see [MDN's textarea documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea).

* __'class'__  
  (string) Add a "class" attribute to all checkbox elements.
* __'data-*'__  
  (true | string) Add a data attribute to all checkbox elements.
* __'readonly'__  
  (true | string) Disallow changes to the setting's value on both the settings page and the server during the sanitization step if `update_option()` is used. "This Boolean attribute indicates that the user cannot modify the value of the control. Unlike the disabled attribute, the readonly attribute does not prevent the user from clicking or selecting in the control. The value of a read-only control is still submitted with the form." [[1]](#sources)
* __'placeholder'__  
  (string) "Text that appears in the form control when it has no value set." [[1]](#sources)
* __'cols'__  
  (string) The number of characters displayed on each line of the element.
* __'rows'__  
  (string) The number of rows shown at one time in the element's viewing pane.
* __'wrap'__  
  (string) "Indicates how the control wraps text. Possible values are:"  
  * "__'hard'__: The browser automatically inserts line breaks (CR+LF) so that each line has no more than the width of the control; the cols attribute must also be specified for this to take effect."  
  * "__'soft'__: The browser ensures that all line breaks in the value consist of a CR+LF pair, but does not insert any additional line breaks."  
  * "__'off'__: Like soft but changes appearance to white-space: pre so line segments exceeding cols are not wrapped and the `textarea` becomes horizontally scrollable."
  
  "If this attribute is not specified, soft is its default behavior." [[1]](#sources)

[Back to top](#textarea)

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

[Back to top](#textarea)

## Sources

1. https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea
2. https://developer.wordpress.org/reference/functions/register_setting/
3. https://developer.wordpress.org/reference/functions/register_setting/#div-comment-3050

[Back to top](#textarea)
