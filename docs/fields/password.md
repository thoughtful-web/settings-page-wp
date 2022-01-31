# Password

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Password*

The Password field supports a "copy_button" configuration value for providing a button to use to copy the text in the password field and control the text in that button. Omit the key to not provide a button (default). Use the configuration below as a guide for implementation.

## Basic Config

```php
...
array(
	'id'        => 'api_key',
	'label'     => 'API Key',
	'type'      => 'password',
	'data_args' => array(
		'copy_button' => 'Copy API key',
	),
),
...
```

## Supported data_args

Data arguments for HTML attributes are listed below. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/password).

* autocomplete
* inputmode
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
