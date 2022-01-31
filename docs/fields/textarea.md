# Textarea

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Textarea*

The Textarea field is very simple.

## Basic Config

```php
...
array(
	'label' => 'My Textarea Field',
	'id'    => 'unique_textarea_field',
	'type'  => 'textarea',
),
...
```

Example with a default value:

```php
...
array(
	'label'     => 'My Textarea Field',
	'id'        => 'unique_textarea_field',
	'type'      => 'textarea',
	'data_args' => array(
		'default' => 'My default value',
	),
),
...
```

## Supported data_args

Data arguments for HTML attributes are listed below. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea).

* class
* cols
* data-*
* disabled
* autocomplete
* disabled
* maxlength
* minlength
* placeholder
* readonly
* required
* rows
* spellcheck
* wrap
