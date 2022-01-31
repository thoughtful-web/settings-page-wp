# Color

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Color*

The Color field uses Iris from WordPress's script files to render a color picker. It requires a hexidecimal color code value.

## Basic Config

Example with required attributes:

```php
...
array(
	'label' => 'My Color Field',
	'id'    => 'unique_color_field',
	'type'  => 'color',
),
...
```

Example with a default value:

```php
...
array(
	'label'     => 'My Color Field',
	'id'        => 'unique_color_field',
	'type'      => 'color',
	'data_args' => array(
		'default' => '#000000',
	),
),
...
```

## Supported data_args

**HTML Attributes**  
(string)(boolean true)

Data arguments for HTML attributes are listed below. Providing a string value to an attribute data argument adds the attribute with a value. Providing a boolean true value to an attribute data argument adds the attribute without a value. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/color).

* class
* data-*
* disabled
* list
* placeholder
* readonly
* required
* size

**Settings API Arguments**

* sanitize_callback  
  (boolean|callable)
* show_in_rest  
  (boolean)
* type  
  (string)
* description
  (string)

[Back to top](#color)
