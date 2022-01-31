# Textarea

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Textarea*

The Textarea field is very simple.

## Basic Config

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

## Supported data_args

**HTML Attributes**  
(string)(boolean true)

Data arguments for HTML attributes are listed below. Providing a string value to an attribute data argument adds the attribute with a value. Providing a boolean true value to an attribute data argument adds the attribute without a value. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea).

* class
* cols
* data-*
* disabled
* autocomplete
* disabled
* maxlength
* minlength
* placeholder
* readonly
* required
* rows
* spellcheck
* wrap

**Settings API Arguments**

* sanitize_callback  
  (boolean|callable)
* show_in_rest  
  (boolean)
* type  
  (string)
* description
  (string)

[Back to top](#textarea)
