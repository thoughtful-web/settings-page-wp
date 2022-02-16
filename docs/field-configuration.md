# Field Configuration

*[Home](../README.md) / Field Configuration*

## Table of Contents

1. [Fields](#fields)
2. [Shared Parameters](#shared-parameters)

## Fields

Listed below are documents describing how to implement each Field into your configuration file.

1. [Checkbox](./fields/checkbox.md)
2. [Color](./fields/color.md)
3. [Email](./fields/email.md)
4. [Number](./fields/number.md)
5. [Password](./fields/password.md)
5. [Phone](./fields/phone.md)
6. [Radio](./fields/radio.md)
7. [Select](./fields/select.md)
8. [Text](./fields/text.md)
9. [Textarea](./fields/textarea.md)
10. [URL](./fields/url.md)
11. [WP Editor](./fields/wp-editor.md)

[Back to top](#field-configuration)

## Shared Parameters

Field configuration parameters common to all Field types are described below. Some parameters of `data_args` are passed to the Core WordPress function register_setting(), although the 'sanitize_callback' is preprocessed before being passed to this function. See https://developer.wordpress.org/reference/functions/register_setting/.

```php
...
array(
	'id'          => 'my_option_id',
	'type'        => 'checkbox',
	'description' => 'What this checkbox is for.',
	'data_args'   => array(
    'default'           => '',
		'description'       => '',
		'sanitize_callback' => true,
		'show_in_rest'      => false,
		'type'              => 'string',
	),
),
...
```

* __'label'__  
  *(string) (Required)*
  "$title [...] Formatted title of the field. Shown as the label for the field during output." [3]
* __'id'__  
  *(string) (Required)*
  "Slug-name to identify the field. Used in the 'id' attribute of tags." Also the database option table key. **NOTE: It is recommended to namespace your options or take other measures to ensure you do not override a pre-existing database option.** [3]
* __'type'__  
  *(string) (Required)*
  The logic to apply to the presentation and validation of this Field. Accepts 'checkbox', 'color', 'email', 'number', 'password', 'tel', 'radio', 'select', 'text', 'textarea', 'url', or 'wp_editor'.
* __'description'__  
  *(string) (Optional)*
  The HTML rendered on the Settings Page after the form element which represents this Field.
* __'data_args'__  
  *(array) (Optional)*
  Data used to configure the setting's use by WordPress Core APIs, this library, and the Settings Page HTML attributes of its form element. Accepts the following keys.
  * __'default'__  
      *(mixed) (Optional)*
      "Default value when calling get_option()." [1] Provide a string if the field is configured to provide a single choice. Provide an array if the field is configured to allow the user to enable more than once choice in a field, like with multiple checkboxes or a multi-select dropdown.  
  * __'description'__  
      *(string) (Optional)*
      "A description of the data attached to this setting." [1] "Only used by the REST API." [2]  
      *Default value: ''*
    * __'sanitize_callback'__  
      *(bool | callable) (Optional)*
      Accepts true, false, or a callable function in string or array format. Default true, which enables the default sanitization operations provided by this library. A value of false disables the default sanitization. A value of callable hooks your own function to the sanitization step.  
      *Default value: true*
  * __'show_in_rest'__  
      *(boolean) (Optional)*
      "Whether data associated with this setting should be included in the REST API. When registering complex settings, this argument may optionally be an array with a 'schema' key." [1]  
      *Default value: false*
    * __'type'__  
      *(string) (Optional)*
      "Only used by the REST API to define the schema associated with the setting and to implement sanitization over the REST API." [2] "The type of data associated with this setting. Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'." [1]  
      *Default value: 'string'*

## Sources

1. WordPress Developer Resources; Function: register_setting()  
   *https://developer.wordpress.org/reference/functions/register_setting/*
2. WordPress Developer Resources; Comment on Function: register_setting()  
   *https://developer.wordpress.org/reference/functions/register_setting/#div-comment-3050*
3. WordPress Developer Resources; Function: add_settings_field()  
   *https://developer.wordpress.org/reference/functions/add_settings_field/*

[Back to top](#field-configuration)
