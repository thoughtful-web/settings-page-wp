# Roadmap

*[Home](../README.md) / Roadmap*

## Table of Contents

1. [Features](#features)
2. [Bug Fixes](#bug-fixes)

## Features

These are new features I have thought about implementing. Bug fixes will be implemented and enhancements might be implemented.

1. Continue developing the documentation.
2. Create a form to generate a configuration file with.
3. Consider adding a network admin settings page configuration.
4. Consider adding a filter to the Select Field configuration to enable populating choices with data like:  
   a) Users  
   b) User Roles  
   c) Post Types  
   d) Taxonomies  
   e) Image Sizes  
5. Allow disabling or removing "choices" from Fields with the above value in case we need to scope access to updating these values. Perhaps this should be a filter for the configuration preprocessor.
6. Consider implementing a File field.
7. Check this plugin to see if we can scope admin-only actions within action hooks to minimize its impact on site performance. Currently known supported action hook is 'wp_loaded'.
8. Consider adding support to the Color field for rgb and rgba color formats.
9. Allow data_arg HTML attribute arguments to receive an array that maps different attributes to different choices.

[Back to top](#roadmap)

## Bug Fixes

* Ensure the Select field handles get_option return types correctly when multiselect is enabled.
* Configure REST value type when the return value of the option is an array.
* Change data_args processing for Fields to disallow arguments like "checked" from being configured but still allow them to be output. Add a 'allowed_data_args' class constant to each Field.
* Apply the 'disabled' and 'readonly' HTML attribute arguments on the server during the sanitization step to disallow updating an option only if an attempt to do so is done from the Settings page (which is redirected to options.php during the POST request).
* Apply the 'required' HTML attribute on the server during the sanitization step only if an attempt to do so is done from the Settings page (which is redirected to options.php during the POST request).
* Add REST API support. "If you plan to use your setting in the REST API, use both the rest_api_init and admin_init hooks when calling register_setting() instead of just admin_init. The show_in_rest argument is ineffective when hooked into admin_init alone." https://developer.wordpress.org/reference/functions/register_setting/#comment-content-3094

[Back to top](#roadmap)
