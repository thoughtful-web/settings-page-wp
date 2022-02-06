# Actions and Filters

*[Home](../README.md) / Actions and Filters*

Since each Option is an individual database entry, you can easily target them using wildcard action and filter hooks. I have listed them here with links to official documentation on their use. Replace `{$option}` with the 'id' of a field you want to use.

## Table of Contents

1. [Actions](#actions)
2. [Filters](#filters)
3. [Sources](#sources)

## Actions

1. `add_action( "add_option_{$option}", function( $option, $value ){} );`  
   Fires after a specific option has been added.  
   * `$option`  
     (string) Name of the option to add.
   * `$value`  
     (mixed) Value of the option.  
   
   Source: [*WordPress Developer Resources; Action Hooks: add_option_{$option}*](https://developer.wordpress.org/reference/hooks/add_option_option/)
2. `add_action( "update_option_{$option}", function( $old_value, $value, $option ){} );`  
   Fires after the value of a specific option has been successfully updated.  
   * `$old_value`  
     (mixed) The old option value.
   * `$value`  
     (mixed) The new option value.
   * `$option`  
     (string) Option name.

   Source: [*WordPress Developer Resources; Action Hooks: update_option_{$option}*](https://developer.wordpress.org/reference/hooks/update_option_option/)
3. `add_action( "delete_option_{$option}", function( $option ){} );`  
   Fires after a specific option has been deleted.
   * `$option`  
     (string) Option name.
   
   Source: [*WordPress Developer Resources; Action Hooks: delete_option_{$option}*](https://developer.wordpress.org/reference/hooks/delete_option_option/)

[Back to top](#actions-and-filters)

## Filters

1. [`option_{$option}`](https://developer.wordpress.org/reference/hooks/option_option/)
   * Filter an option after database lookup.
2. [`pre_option_{$option}`](https://developer.wordpress.org/reference/hooks/pre_option_option/)
   * Filter the value of an option during the rendering of a page without changing it permanently in the database.
2. [`pre_update_option_{$option}`](https://developer.wordpress.org/reference/hooks/pre_update_option_option/)
   * This filter is applied to the option value before being saved to the database

[Back to top](#actions-and-filters)

## Sources

1. WordPress Developer Resources; Action Hooks: add_option_{$option}  
   *https://developer.wordpress.org/reference/hooks/add_option_option/*
2. WordPress Developer Resources; Action Hooks: update_option_{$option}  
   *https://developer.wordpress.org/reference/hooks/update_option_option/*
3. WordPress Developer Resources; Action Hooks: delete_option_{$option}  
   *https://developer.wordpress.org/reference/hooks/delete_option_option/*
4. https://developer.wordpress.org/reference/hooks/option_option/
5. https://developer.wordpress.org/reference/hooks/pre_option_option/
6. https://developer.wordpress.org/reference/hooks/pre_update_option_option/