# WP Editor

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / WP Editor*

WP Editor fields provide a Core WordPress rich content editor via the [`wp_editor()`](https://developer.wordpress.org/reference/functions/wp_editor/) function. You may use this to create HTML that will be inserted into an option's value.

## Basic Config

```php
...
array(
	'label' => 'My WP Editor Field',
	'id'    => 'unique_wp_editor_field',
	'type'  => 'wp_editor',
),
...
```

Example with a default attribute:

```php
...
array(
	'label'       => 'My WP Editor Field',
	'id'          => 'unique_wp_editor_field',
	'type'        => 'wp_editor',
	'description' => 'My WP Editor field description',
	'data_args'   => array(
		'default' => 'my default value',
	),
),
...
```

## Supported data_args

Data arguments for HTML attributes are not supported.
