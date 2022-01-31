# Radio

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Radio*

A Radio field can be declared in a manner very similar to the Select field.

## Basic Config

Example with required attributes:

```php
...
array(
	'label'   => 'My Radio Field',
	'id'      => 'unique_radio_field',
	'type'    => 'radio',
	'choices' => array(
		'option_one'   => 'Option 1',
		'option_two'   => 'Option 2',
		'option_three' => 'Option 3',
	),
),
...
```

Example with default value:

```php
...
array(
	'label'     => 'My Radio Field',
	'id'        => 'unique_radio_field',
	'type'      => 'radio',
	'choices'   => array(
		'option_one'   => 'Option 1',
		'option_two'   => 'Option 2',
		'option_three' => 'Option 3',
	),
	'data_args' => array(
		'default' => 'option_one',
	),
),
...
```

## Supported data_args

**HTML Attributes**  
(string)(boolean true)

Data arguments for HTML attributes are listed below. Providing a string value to an attribute data argument adds the attribute with a value. Providing a boolean true value to an attribute data argument adds the attribute without a value. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/radio).

* checked
* class
* data-*
* disabled
* readonly
* required

**Settings API Arguments**

* sanitize_callback  
  (boolean|callable)
* show_in_rest  
  (boolean)
* type  
  (string)
* description
  (string)

[Back to top](#radio)
