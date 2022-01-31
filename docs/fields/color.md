# Color

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Color*

The Color field uses Iris from WordPress's script files to render a color picker. It requires a hexidecimal color code value.

## Basic Config

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

Data arguments for HTML attributes are listed below. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/color).

* class
* data-*
* disabled
* list
* placeholder
* readonly
* required
* size
