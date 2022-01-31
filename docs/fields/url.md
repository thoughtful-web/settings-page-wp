# URL

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / URL*

The URL Field supports the "pattern" data argument to enforce a regular expression against the value.

## Basic Config

```php
...
array(
	'label' => 'My URL Field',
	'id'    => 'unique_url_field',
	'type'  => 'url',
),
...
```

Configure the pattern attribute to force HTTPS:

```php
...
array(
	'label'       => 'My URL Field',
	'id'          => 'unique_url_field',
	'type'        => 'url',
	'data_args'   => array(
		'placeholder' => 'https://example.com/',
		'pattern'     => 'https://.*',
	),
),
...
```

## Supported data_args

Data arguments for HTML attributes are listed below. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/url).

* class
* data-*
* disabled
* list
* maxlength
* minlength
* pattern
* placeholder
* readonly
* required
* size
