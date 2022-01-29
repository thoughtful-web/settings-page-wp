# Field Configuration

*[Home](../../README.md) / Field Configuration*

Here is a guide for implementing each Field type. You may also wish to refer to the source code for each Field which has its own documentation in the files.



## Text

The Text field is the simplest field to implement. Required values are: label, id, type.

```php
array(
	'label'       => 'My Text Field', // Required.
	'id'          => 'unique_text_field',
	'type'        => 'text',
	'description' => 'My text field description',
	'data_args'   => array(
		'placeholder'   => 'my placeholder',
		'default'       => 'A thoughtful, optional, default value',
		'data-lpignore' => 'true',
		'size'          => '40',
	),
),
```

## Color

The Color field uses Iris from WordPress's script files to render a color picker. Required values are: label, id, type.

```php
array(
	'label'       => 'My Color Field',
	'id'          => 'unique_color_field',
	'type'        => 'color',
	'description' => 'My color field description',
	'data_args'   => array(
		'default' => '#000000',
	),
),
```

## Select

The Select field supports a "prompt" configuration value for customizing the first `<option>` element's label to describe what a user should do. The default value is "Please choose an option".

**Multiselect**

If you configure the field as a multiselect field, and choose to configure a default value, then you must declare the default value as an array of values.

```php
array(
	'label'       => 'My Select Field',
	'id'          => 'unique_select_field',
	'type'        => 'select',
	'prompt'      => 'Select an option',
	'description' => 'My select field description',
	'choices'     => array(
		'option_one'   => 'Option 1',
		'option_two'   => 'Option 2',
		'option_three' => 'Option 3',
	),
	'data_args'   => array(
		'default' => 'option_one',
	)
),
```

## Password

The Password field supports a "copy_button" configuration value for providing a button to use to copy the text in the password field and control the text in that button. Omit the key to not provide a button (default). Use the configuration below as a guide for implementation.

```php
array(
	'id'          => 'api_key',
	'label'       => 'API Key',
	'type'        => 'password',
	'description' => 'An API key used to access data.',
	'data_args'   => array(
		'copy_button' => 'Copy API key',
	),
),
```
