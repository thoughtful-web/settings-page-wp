# Select

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Select*

The Select field supports a "prompt" configuration value for customizing the first `<option>` element's label to describe what a user should do. The default value is "Please choose an option".

**Multiselect**

If you configure the field as a multiselect field, and choose to configure a default value, then you must declare the default value as an array of values.

```php
array(
	'label'       => 'My Select Field',
	'id'          => 'unique_select_field',
	'type'        => 'select',
	'prompt'      => 'Select an option',
	'description' => 'My select field description',
	'choices'     => array(
		'option_one'   => 'Option 1',
		'option_two'   => 'Option 2',
		'option_three' => 'Option 3',
	),
	'data_args'   => array(
		'default' => 'option_one',
	)
),
```
