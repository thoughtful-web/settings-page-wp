# Radio

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Radio*

A Radio field can be declared in a manner very similar to the Select field.

Example:

```php
...
array(
	'label'       => 'My Radio Field',
	'id'          => 'unique_radio_field',
	'type'        => 'radio',
	'choices'     => array(
		'option_one'   => 'Option 1',
		'option_two'   => 'Option 2',
		'option_three' => 'Option 3',
	),
),
...
```
