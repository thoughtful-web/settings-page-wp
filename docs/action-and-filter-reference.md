# Actions and Filters

*[Home](../README.md) / Actions and Filters*

Since each Option is an individual database entry, you can easily target them using wildcard action and filter hooks. I have listed them here with links to official documentation on their use. Replace `{$option}` with the 'id' of a field you want to use.

## Table of Contents

1. [Actions](#actions)
2. [Filters](#filters)

## Actions

1. `"add_option_{$option}"`  
   "Fires after a specific option has been added."  
   *https://developer.wordpress.org/reference/hooks/add_option_option/*
2. `"update_option_{$option}"`  
   "Fires after the value of a specific option has been successfully updated."
   *https://developer.wordpress.org/reference/hooks/update_option_option/*
3. `"delete_option_{$option}"`  
   "Fires after a specific option has been deleted."
   *https://developer.wordpress.org/reference/hooks/delete_option_option/*

[Back to top](#actions-and-filters)

## Filters

1. `"option_{$option}"`  
   "Filters the value of an existing option. [...] This hook allows you to filter any option after database lookup."  
   *https://developer.wordpress.org/reference/hooks/option_option/*
2. `"pre_option_{$option}"`  
   "Filters the value of an existing option before it is retrieved. [...] Returning a truthy value from the filter will effectively short-circuit retrieval and return the passed value instead."  
   *https://developer.wordpress.org/reference/hooks/pre_option_option/*
2. `"pre_update_option_{$option}"`  
   "Filters a specific option before its value is (maybe) serialized and updated."  
   *https://developer.wordpress.org/reference/hooks/pre_update_option_option/*

[Back to top](#actions-and-filters)
