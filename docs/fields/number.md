# Number

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Number*

You may declare a number field if you wish to ensure the option is always a numeric value. This field does not support scientific notation, so if you need that then use a Text field instead.

## Basic Config

```php
...
array(
	'label' => 'My Decimal Number Field',
	'id'    => 'unique_decimal_number_field',
	'type'  => 'number',
),
...
```

Example with default value:

```php
...
array(
	'label'     => 'My Decimal Number Field',
	'id'        => 'unique_decimal_number_field',
	'type'      => 'number',
	'data_args' => array(
		'default' => '24',
	),
),
...
```

Decimal number example:

```php
...
array(
	'label'     => 'My Decimal Number Field',
	'id'        => 'unique_decimal_number_field',
	'type'      => 'number',
	'data_args' => array(
		'placeholder' => 'Multiple of 0.1',
		'step'        => '0.1',
		'min'         => '0',
		'max'         => '10',
	),
),
...
```

Negative number example:

```php
...
array(
	'label'       => 'My Negative Number Field',
	'id'          => 'unique_negative_number_field',
	'type'        => 'number',
	'data_args'   => array(
		'placeholder' => 'Multiple of -1',
		'step'        => '1',
		'min'         => '-10',
		'max'         => '0',
	),
),
...
```

## Supported data_args

Data arguments for HTML attributes are listed below. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/number).

* class
* data-*
* autocomplete
* disabled
* list
* max
* min
* placeholder
* readonly
* required
* step
