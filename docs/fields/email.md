# Email

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Email*

The Email field supports a single email value. Sanitization is incomplete because support for the format `My Name <email@domain.com>` needs modification. It can be used, but extreme edge cases may exist.

Example:

```php
...
array(
	'label'       => 'My Email Field',
	'id'          => 'unique_email_field',
	'type'        => 'email',
),
...
```
