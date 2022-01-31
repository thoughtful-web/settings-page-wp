# Checkbox

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Checkbox*

The Checkbox field uses the "choice" value to configure a single checkbox field whose value is input into the database as a string. Multiple checkboxes may be configured using "choices" instead of "choice". Each choice follows a "value => label" format. The "default" data_args value of a singular Checkbox configuration accepts a string and the multiple Checkbox configuration accepts an array of choice values. Required values are: label, id, type, choice.

## Basic Config

See below for a basic example of how to configure this field. Examples with default values are shown in the next sections.

```php
...
array(
	'label'  => 'My Checkbox Field',
	'id'     => 'unique_checkbox_field',
	'type'   => 'checkbox',
	'choice' => array(
		'1' => 'My Choice',
	),
),
...
```

## Singular Checkboxes

To use a single checkbox that sets the option value to a string, use the "choice" key. Set the "default" data_args key to the key of the choice, as shown below.

```php
...
array(
	'label'     => 'My Checkbox Field',
	'id'        => 'unique_checkbox_field',
	'type'      => 'checkbox',
	'choice'    => array(
		'1' => 'My Choice',
	),
	'data_args' => array(
		'default' => '1',
	),
),
...
```

## Multiple Checkboxes

To use a multiple checkboxes that set the option value to an array, use the "choices" key. Set the "default" data_args key to an array of keys used in the "choices" value, as shown below.

```php
...
array(
	'label'     => 'My Checkbox Fields',
	'id'        => 'unique_checkbox_fields',
	'type'      => 'checkbox',
	'choices'   => array(
		'option_one'   => 'Option 1',
		'option_two'   => 'Option 2',
		'option_three' => 'Option 3',
	),
	'data_args' => array(
		'default' => array(
			'option_one',
			'option_two',
		),
	),
),
...
```

## Supported data_args

Data arguments for HTML attributes are listed below. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/checkbox).

* checked  
* class
* data-*
* default
* disabled
* readonly
* required
