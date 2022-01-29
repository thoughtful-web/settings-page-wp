# Password

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Password*

The Password field supports a "copy_button" configuration value for providing a button to use to copy the text in the password field and control the text in that button. Omit the key to not provide a button (default). Use the configuration below as a guide for implementation.

```php
array(
	'id'          => 'api_key',
	'label'       => 'API Key',
	'type'        => 'password',
	'description' => 'An API key used to access data.',
	'data_args'   => array(
		'copy_button' => 'Copy API key',
	),
),
```
