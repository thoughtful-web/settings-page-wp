# Color

*[Home](../../README.md) / [Field Configuration](../field-configuration.md) / Color*

The Color field uses Iris from WordPress's script files to render a color picker. It requires a hexidecimal color code.

```php
array(
	'label'       => 'My Color Field',
	'id'          => 'unique_color_field',
	'type'        => 'color',
	'description' => 'My color field description',
	'data_args'   => array(
		'default' => '#000000',
	),
),
```
