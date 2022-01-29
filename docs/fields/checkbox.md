# Checkbox

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Checkbox*

The Checkbox field uses the "choice" value to configure a single checkbox field whose value is input into the database as a string. Multiple checkboxes may be configured using "choices" instead of "choice". Each choice follows a "value => label" format. The "default" data_args value of a singular Checkbox configuration accepts a string and the multiple Checkbox configuration accepts an array of choice values. Required values are: label, id, type, choice.

```php
array(
	'label'       => 'My Checkbox Field', // Required.
	'id'          => 'unique_checkbox_field', // Required.
	'type'        => 'checkbox', // Required.
	'description' => 'My checkbox field description',
	'choice'      => array( // Required.
		'1' => 'My Choice',
	),
	'data_args'   => array(
		'default' => '1',
	),
),
```

Multiple checkboxes are configured as shown below:

```php
array(
	'label'       => 'My Checkbox Fields',
	'id'          => 'unique_checkbox_fields',
	'type'        => 'checkbox',
	'description' => 'My checkbox fields description',
	'choices'     => array(
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
```
