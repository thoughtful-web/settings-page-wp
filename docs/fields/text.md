# Text

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Text*

The Text field is the simplest field to implement. Required values are: label, id, type.

## Basic Config

Example with required attributes:

```php
...
array(
	'label' => 'My Text Field', // Required.
	'id'    => 'unique_text_field',
	'type'  => 'text',
),
...
```

Example with default value:

```php
...
array(
	'label'     => 'My Text Field', // Required.
	'id'        => 'unique_text_field',
	'type'      => 'text',
	'data_args' => array(
		'default' => 'My default value',
	),
),
...
```

## Supported data_args

**HTML Attributes**  
(string)(boolean true)

Data arguments for HTML attributes are listed below. Providing a string value to an attribute data argument adds the attribute with a value. Providing a boolean true value to an attribute data argument adds the attribute without a value. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/text).

* class
* data-*
* autocomplete
* disabled
* list
* maxlength
* minlength
* pattern
* placeholder
* readonly
* required
* size
* spellcheck

**Settings API Arguments**

* sanitize_callback  
  (boolean|callable)
* show_in_rest  
  (boolean)
* type  
  (string)
* description
  (string)

[Back to top](#text)
