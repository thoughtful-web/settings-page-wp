# Select

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Select*

This Field supports single and multiselect configurations (shown below). The Select field also supports a "prompt" configuration value for customizing the first `<option>` element's label to describe what a user should do. The default value is "Please choose an option".

## Basic Config

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

## Supported data_args

Data arguments for HTML attributes are listed below. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/select).

* autocomplete
* class
* data-*
* disabled
* multiple
* required
* size
