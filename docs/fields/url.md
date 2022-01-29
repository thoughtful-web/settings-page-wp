# URL

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / URL*

The URL Field supports the "pattern" data argument to enforce a regular expression against the value.

Example:

```php
...
array(
	'label'       => 'My URL Field',
	'id'          => 'unique_url_field',
	'type'        => 'url',
	'description' => 'Must have the "https" protocol.',
	'data_args'   => array(
		'placeholder' => 'https://example.com/',
		'pattern'     => 'https://.*',
	),
),
...
```
