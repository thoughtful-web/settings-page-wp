# Text

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Text*

The Text field is the simplest field to implement. Required values are: label, id, type.

```php
array(
	'label'       => 'My Text Field', // Required.
	'id'          => 'unique_text_field',
	'type'        => 'text',
	'description' => 'My text field description',
	'data_args'   => array(
		'placeholder'   => 'my placeholder',
		'default'       => 'A thoughtful, optional, default value',
		'data-lpignore' => 'true',
		'size'          => '40',
	),
),
```
