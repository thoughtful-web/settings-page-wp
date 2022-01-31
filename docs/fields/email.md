# Email

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Email*

The Email field supports a single email value. Sanitization is incomplete because support for the format `My Name <email@domain.com>` needs modification. It can be used, but extreme edge cases may exist.

## Basic Config

Example with required attributes:

```php
...
array(
	'label' => 'My Email Field',
	'id'    => 'unique_email_field',
	'type'  => 'email',
),
...
```

Example with a default value:

```php
...
array(
	'label'     => 'My Email Field',
	'id'        => 'unique_email_field',
	'type'      => 'email',
	'data_args' => array(
		'default' => 'contact@domain.com',
	),
),
...
```

## Supported data_args

**HTML Attributes**  
(string)(boolean true)

Data arguments for HTML attributes are listed below. Providing a string value to an attribute data argument adds the attribute with a value. Providing a boolean true value to an attribute data argument adds the attribute without a value. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/email).

* class
* data-*
* disabled
* list
* maxlength
* minlength
* multiple
* pattern
* placeholder'
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

[Back to top](#email)
