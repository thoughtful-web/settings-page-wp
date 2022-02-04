# Action and Filter Reference

*[Home](../README.md) / Action and Filter Reference*

## Table of Contents

1. [Actions](#actions)
2. [Filters](#filters)

Since each Option is an individual database entry, you can easily target them using wildcard actions and filters. I have listed them here with links to official documentation on their use. Replace `{$option}` with the ID of an option you wish to observe or manipulate.

## Actions

1. [`add_option_{$option}`](https://developer.wordpress.org/reference/hooks/add_option_option/)
   * Fires after a specific option has been added.
2. [`update_option_{$option}`](https://developer.wordpress.org/reference/hooks/update_option_option/)
   * Fires after the value of a specific option has been successfully updated.
3. [`delete_option_{$option}`](https://developer.wordpress.org/reference/hooks/delete_option_option/)
   * Fires after a specific option has been deleted. 

## Filters

1. [`option_{$option}`](https://developer.wordpress.org/reference/hooks/option_option/)
   * Filter an option after database lookup.
2. [`pre_option_{$option}`](https://developer.wordpress.org/reference/hooks/pre_option_option/)
   * Filter the value of an option during the rendering of a page without changing it permanently in the database.
2. [`pre_update_option_{$option}`](https://developer.wordpress.org/reference/hooks/pre_update_option_option/)
   * This filter is applied to the option value before being saved to the database

[Back to top](#action-and-filter-reference)
