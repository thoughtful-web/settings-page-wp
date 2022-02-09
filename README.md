# Create Settings Pages for WordPress

>Free open source software under the GNU GPL-2.0+ License.  
>Copyright Zachary Kendall Watkins 2021-2022.  

## Introduction

This PHP library uses your configuration file to create a settings page and sanitized database options for your WordPress plugin or theme. Each field you configure is a registered WordPress [Option](https://developer.wordpress.org/plugins/settings/options-api/), so [filters and actions](docs/action-and-filter-reference.md) can observe them.

## Table of Contents

1. [Features](#features)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Quick Start](#quick-start)
6. [Fields](#fields)
7. [Additional Documentation](#additional-documentation)
8. [Contributing](#contributing)
9. [References](#references)

## Features

1. Settings page generation from a configuration file (*.php, *.json).
2. Validation on the server by default.
3. No external libraries beyond Iris for the color field.

[Back to top](#introduction)

## Requirements

1. WordPress 5.4 and above.
2. PHP 7.3.5 and above.
3. A configuration file or PHP array (*see [Configuration File](#configuration-file)*)
4. This library must exist two directory levels below the plugin or theme's root directory. Examples:  
   a. *./vendor/thoughtful-web/settings-page-wp*  
   b. *./lib/thoughtful-web/settings-page-wp*  

[Back to top](#introduction)

## Installation

If you are familiar with the command line, you may install this module using Composer. https://getcomposer.org/

```command-line
$ composer require thoughtful-web/settings-page-wp
```

You may download it to a different directory in your plugin or theme while still meeting requirement #4. You can use a [release](https://github.com/thoughtful-web/settings-page-wp/releases), the [source code](https://github.com/thoughtful-web/settings-page-wp), or the command line:

```command-line
$ mkdir lib/thoughtful-web
$ cd lib/thoughtful-web
$ git clone https://github.com/thoughtful-web/settings-page-wp
```

To clone a specific tagged version:

```command-line
$ git clone --depth 1 --branch v0.9.11 https://github.com/thoughtful-web/settings-page-wp
```

[Back to top](#introduction)

## Quick Start

*Note: For full specification details on the configuration file values and other possible locations for a configuration file, see here: [docs/config-file.md](./docs/config-file.md)*

**Step 1:** Download the composer module.

```command-line
$ composer require thoughtful-web/settings-page-wp
```

**Step 2:** Include the library in your plugin or theme using the Composer autoloader:

```php
require __DIR__ . '/vendor/autoload.php;

new \ThoughtfulWeb\SettingsPageWP\Page();
```

**Step 3:** Copy the [example configuration file](./config/thoughtful-web/settings/settings.example.php) to your plugin or theme's directory at `./config/thoughtful-web/settings/settings.php`.

**Step 4:** Visit your new settings page at https://your-domain.com/wp-admin/admin.php?page=thoughtful-settings and fill it in as you wish.

**Step 5:** Retrieve an option from the database using WordPress's [`get_option()`](https://developer.wordpress.org/reference/functions/get_option/) function:

```php
$the_option = get_option( 'unique_text_field' );
```

[Back to top](#introduction)

## Fields

The following Fields are available and link to their full configuration instructions.

1. [Checkboxes](docs/fields/checkbox.md)
2. [Color](docs/fields/color.md)
3. [Email](docs/fields/email.md)
4. [Number](docs/fields/number.md)
5. [Phone](docs/fields/phone.md)
6. [Radio](docs/fields/radio.md)
7. [Select](docs/fields/select.md)
8. [Text](docs/fields/text.md)
9. [Textarea](docs/fields/textarea.md)
10. [URL](docs/fields/url.md)
11. [WP Editor](docs/fields/wp-editor.md)

[Back to top](#introduction)

## Additional Documentation
1. [Field Configuration](./docs/field-configuration.md)
2. [Action and Filter Reference](./docs/action-and-filter-reference.md)
3. [Roadmap](./docs/roadmap.md)
4. [Development Installation and Notes](./docs/development.md)

[Back to top](#introduction)

## Contributing

I welcome questions and discussion and have opened up Github's features to create a space for this. Please see the [contribution guidelines](./Contributing.md) for details.

[Back to top](#introduction)
## References

1. WordPress Developer Resources; Function: add_menu_page()  
   *https://developer.wordpress.org/reference/functions/add_menu_page/*
2. WordPress Developer Resources; Function: add_submenu_page()  
   *https://developer.wordpress.org/reference/functions/add_submenu_page/*
3. WordPress Developer Resources; Function: register_setting()  
   *https://developer.wordpress.org/reference/functions/register_setting/*
4. WordPress Developer Resources; Comment on Function: register_setting()  
   *https://developer.wordpress.org/reference/functions/register_setting/#div-comment-3050*
5. WordPress Developer Resources; Function: add_settings_field()  
   *https://developer.wordpress.org/reference/functions/add_settings_field/*
6. WordPress Plugin Handbook; Settings API  
   *https://developer.wordpress.org/plugins/settings/settings-api/*
7. WordPress Plugin Handbook; Options API  
   *https://developer.wordpress.org/plugins/settings/options-api/*
8. Composer: A Dependency Manager for PHP  
   *https://getcomposer.org/*

[Back to top](#introduction)
