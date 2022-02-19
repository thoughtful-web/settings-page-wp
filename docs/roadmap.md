# Roadmap

*[Home](../README.md) / Roadmap*

These are new features I plan to or have thought about implementing.

1. Check this library to see if we can scope admin-only actions within action hooks to minimize its impact on site performance. Currently known supported action hook is 'wp_loaded'.
2. Improve REST API support, including configuring a field's REST value type when the return value of the option is an array. "If you plan to use your setting in the REST API, use both the rest_api_init and admin_init hooks when calling register_setting() instead of just admin_init. The show_in_rest argument is ineffective when hooked into admin_init alone." https://developer.wordpress.org/reference/functions/register_setting/#comment-content-3094
3. Consider adding a filter to the Select Field configuration to enable populating choices with data like:  
   a) Users  
   b) User Roles  
   c) Post Types  
   d) Taxonomies  
   e) Image Sizes  
4. Consider creating a form to allow people to generate a configuration file.
5. Allow disabling or removing "choices" from Fields with the above value in case we need to scope access to updating these values. Perhaps this should be a filter for the configuration preprocessor.
6. Consider implementing a File field.

[Back to top](#roadmap)
