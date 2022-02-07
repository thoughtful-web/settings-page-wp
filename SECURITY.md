# Security Policy

## Notice

Even though I give no warranties or guarantees to the security of this library, it is still something I strive to achieve. If you feel that there is a flaw in this library's sanitization of database options you may provide your own sanitization functions by defining your callable variable to the `sanitization_callback` value in a field's `data_args` parameter. You may disable all sanitization by providing a boolean false value to this parameter as well.

## Supported Versions

The latest minor and major versions are supported with security updates.

| Version | Supported          |
| ------- | ------------------ |
| 0.9.x   | yes                |
| 0.8.x   | no                 |

## Reporting a Vulnerability

To report a vulnerability with the project please send an email to zachwatkins@tapfuel.io with [SECURITY] at the beginning of the subject line. You can expect a quicker response to security issues because I take the security of this library very seriously.

I will notify users via Github in some way when a vulnerability is found. If there is a way through Github that allows users to subscribe to this kind of notice I will make note of that here.

## Contributing to Security

At this time I welcome pull requests to improve the sanitization of fields. This code can be found in [./src/Settings/Sanitize.php](./src/Settings/Sanitize.php) Please read the [contribution guidelines](./CONTRIBUTING.md) before writing your code for a pull request. This feature can be disabled by users, but one of my goals with the library is to provide a secure default state through default sanitization of the field data.
