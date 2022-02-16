# Create Settings Pages for WordPress

This PHP library provides a quick, easy way to add a settings page to your plugin or theme. One configuration file defines both your settings and the page that manages them.

## Table of Contents

1. [Features](#features)
3. [Requirements](#installation)
4. [Quick Start](#quick-start)
5. [Documentation](#documentation)
8. [Contributing](#contributing)

## Features

1. Settings page generation from a configuration file (*.php or *.json).
2. Validation on the server by default.
3. No external libraries beyond WordPress's Iris library for color fields.
4. Supports these form field types:  
   1. Checkboxes
   2. Color
   3. Email
   4. Number
   5. Phone
   6. Radio
   7. Select
   8. Text
   9. Textarea
   10. URL
   11. WP Editor

## Requirements

### System Requirements

1. WordPress 5.4 and above.
2. PHP 7.3.5 and above.

### Installation Requirements

If you are familiar with the command line, you may install it using [Composer](https://getcomposer.org) or Git. Otherwise, you may [download a release from Github](https://github.com/thoughtful-web/settings-page-wp/releases). 

In any case, this library must exist two directory levels below the plugin or theme's root directory. Example: *./vendor/thoughtful-web/settings-page-wp/*.

## Quick Start

*Note: For full specification details on the configuration file values and other possible locations for a configuration file, see here: [docs/config-file.md](./docs/config-file.md)*

**Step 1**  
> Download the composer module.

```command-line
$ composer require thoughtful-web/settings-page-wp
```

**Step 2**  
Include the library in your plugin or theme using the Composer autoloader:

```php
require __DIR__ . '/vendor/autoload.php;

new \ThoughtfulWeb\SettingsPageWP\Page();
```

**Step 3**  
Copy the [example configuration file](./config/thoughtful-web/settings.example.php) to your plugin or theme's directory at `./config/thoughtful-web/settings.php`.

**Step 4**  
Visit your new settings page at https://your-domain.com/wp-admin/admin.php?page=thoughtful-settings and fill it in as you wish.

**Step 5**  
Retrieve an option from the database using WordPress's [`get_option()`](https://developer.wordpress.org/reference/functions/get_option/) function:

```php
$the_option = get_option( 'unique_text_field' );
```

## Documentation

1. [Configuration File Reference](./docs/config-file.md)
2. [Field Configuration Reference](./docs/field-configuration.md)
3. [Action and Filter Reference](./docs/action-and-filter-reference.md)
4. [Roadmap](./docs/roadmap.md)
5. WordPress Developer Reference: [Options API](https://developer.wordpress.org/plugins/settings/options-api/)

## Contributing

I welcome questions and discussion and have opened up Github's features to create a space for this: https://github.com/thoughtful-web/settings-page-wp/discussions. If you find an issue, please report it here: https://github.com/thoughtful-web/settings-page-wp/issues. I am currently only accepting pull requests for security improvements. Please see the [contribution guidelines](./Contributing.md) for details.

[Back to top](#introduction)

---
*Free open source software under the GNU GPL-2.0+ License.*  
*Copyright Zachary Kendall Watkins 2021-2022.*
