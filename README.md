# Thoughtful Web Settings Pages for WordPress

>Copyright Zachary Kendall Watkins 2022.  
>Free open source software under the GNU GPL-2.0+ License.  

This library generates both a Settings page and fully qualified Options for each of its fields from a single configuration file.

All HTML attributes for form fields are supported in the configuration and "pattern" attributes are validated for both the form and in the Option's sanitization filter hook. Each Field is a separate Option and all WordPress filters and actions which apply to Options can be used for them.

## Requirements

1. WordPress 5.4 and above.
2. PHP 7.3.5 and above.

## Installation

To install this module from Github using Composer, add it as a repository to the composer.json file:

```
{
    "name": "thoughtful-web/settings-page-wp",
    "description": "WordPress Settings page generator and validator released as free open source software under GNU GPL-2.0+ License",
	"repositories": [
		{
			"type": "vcs",
			"url": "https://github.com/thoughtful-web/settings-page-wp"
		}
	],
	"require": {
		"thoughtful-web/settings-page-wp": "dev-main"
	}
}
```

Then either use Composer's autoloader or require the file directly in your PHP.

## Simplest Implementation

The simplest implementation of this module is to include it with the autoloader or by direct file reference and add a configuration file at `./config/thoughtful-web/settings/settings.php` or `./config/thoughtful-web/settings/settings.json`. Then declare the Settings from that configuration file by creating a new instance of the Settings page in your Plugin's main file like this:  

```
new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings();
```

## Implementing The Class

To load the Settings class with (or without) a configuration parameter you should know the accepted values:

```
@param string|array $config (Optional) The Settings page configuration parameters.
                            Either a configuration file name, file path, or array.
```

This class will load a file using an `include` statement if it is a PHP file or using `file_read_contents` it is a JSON file. Here is an explanation of the possible values for this parameter:

1. **No parameter** assumes there is a configuration file located here: `./config/thoughtful-web/settings/settings.php`. Example:  
   i. `new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings();`  
2. **File name** accepts a PHP or JSON file name and requires the file to be in the plugin's root directory at `./config/thoughtful-web/settings/`. Examples:  
   i. `new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings( 'filename.php' );`  
   ii. `new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings( 'filename.json' );`  
3. **File path** can be any location on your server, as long as the `./src/Admin/Page/Settings/Config.php` class file has read access to it. Examples:  
   i. `new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings( '/config/settings.php' );`  
   i. `new \ThoughtfulWeb\LibraryWP\Admin\Page\Settings( '/public_html/wp-content/plugins/wordpress-plugin-name/settings.json' );`  
4. **Array** The configuration parameters in their final state.

**Note:** Call the class without an action hook or within an action hook early enough in the execution order to not skip the WordPress actions, filters, and functions used in this feature's class files. It is yet to be determined which action hooks are compatible with this class's instantiation.

## Creating the Config File

I am still writing this documentation and Configuration file instructions are next on my list.

For now, please use the example configuration file(s) at `./config/thoughtful-web/settings/settings.example.php` as a guide for how to declare the parameters. Refer to the class variables of each **Field** class file to determine what HTML attributes they support - these must be configured in a Field's `data_args` array member in `(string) key : (string) value` format.
