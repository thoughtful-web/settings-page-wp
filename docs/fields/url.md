# URL

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / URL*

## Basic Config

Example with required attributes:

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
	'label'     => 'My URL Field',
	'id'        => 'unique_url_field',
	'type'      => 'url',
	'data_args' => array(
		'placeholder' => 'https://example.com/',
		'pattern'     => 'https://.*',
	),
),
...
```

## Supported data_args

**HTML Attributes**  
(string)(boolean true)

Data arguments for HTML attributes are listed below. Providing a string value to an attribute data argument adds the attribute with a value. Providing a boolean true value to an attribute data argument adds the attribute without a value. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/url).

* class
* data-*
* disabled
* list
* maxlength
* minlength
* pattern  
  The URL Field supports the "pattern" data argument to enforce a regular expression against the value.
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

[Back to top](#url)
