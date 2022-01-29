# Phone

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Phone*

The Phone (tel) field supports the "pattern" data argument (shown below) to enforce the format you wish to use for the phone number.

```php
...
array(
	'label'       => 'My Phone Field',
	'id'          => 'unique_phone_field',
	'type'        => 'tel',
	'data_args'   => array(
		'placeholder' => '555-555-5555',
		'pattern'     => '[0-9]{3}-[0-9]{3}-[0-9]{4}',
	),
),
...
```
