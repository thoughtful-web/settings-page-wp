# Password

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Password*

The Password field supports a "copy_button" configuration value for providing a button to use to copy the text in the password field and control the text in that button. Omit the key to not provide a button (default). Use the configuration below as a guide for implementation.

## Basic Config

Example with required attributes:

```php
...
array(
	'id'    => 'api_key',
	'label' => 'API Key',
	'type'  => 'password',
),
...
```

Example with default value and copy_button value:

```php
...
array(
	'id'        => 'api_key',
	'label'     => 'API Key',
	'type'      => 'password',
	'data_args' => array(
		'copy_button' => 'Copy API key',
		'default'     => 'myapikey',
	),
),
...
```

## Supported data_args

**HTML Attributes**  
(string)(boolean true)

Data arguments for HTML attributes are listed below. Providing a string value to an attribute data argument adds the attribute with a value. Providing a boolean true value to an attribute data argument adds the attribute without a value. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/password).

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

**Settings API Arguments**

* sanitize_callback  
  (boolean|callable)
* show_in_rest  
  (boolean)
* type  
  (string)
* description
  (string)

[Back to top](#password)
