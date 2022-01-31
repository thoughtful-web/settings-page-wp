# Radio

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Radio*

A Radio field can be declared in a manner very similar to the Select field.

## Basic Config

```php
...
array(
	'label'   => 'My Radio Field',
	'id'      => 'unique_radio_field',
	'type'    => 'radio',
	'choices' => array(
		'option_one'   => 'Option 1',
		'option_two'   => 'Option 2',
		'option_three' => 'Option 3',
	),
),
...
```

## Supported data_args

Data arguments for HTML attributes are listed below. To learn how to use these attributes see the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/radio).

* checked
* class
* data-*
* disabled
* readonly
* required
