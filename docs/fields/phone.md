# Phone

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Phone*

The Phone (tel) field supports the "pattern" data argument (shown below) to enforce the format you wish to use for the phone number.

## Basic Config

Example with required attributes:

```php
...
array(
	'label' => 'My Phone Field',
	'id'    => 'unique_phone_field',
	'type'  => 'tel',
),
...
```

Example with default value:

```php
...
array(
	'label'     => 'My Phone Field',
	'id'        => 'unique_phone_field',
	'type'      => 'tel',
	'data_args' => array(
		'placeholder' => '555-555-5555',
		'pattern'     => '[0-9]{3}-[0-9]{3}-[0-9]{4}',
	),
),
...
```

## Supported data_args

**HTML Attributes**  
(string)(boolean true)

Data arguments for HTML attributes are listed below. Providing a string value to an attribute data argument adds the attribute with a value. Providing a boolean true value to an attribute data argument adds the attribute without a value. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/tel).

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

[Back to top](#phone)
