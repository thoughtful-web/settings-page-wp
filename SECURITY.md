# Security Policy

## Disclosure

Even though I give no warranties or guarantees to the security of this library, it is still something I strive to achieve. If you feel that there is a flaw or inefficiency in this library's sanitization of database options you may provide a boolean false value to each field's data arguments via the sanitization_callback key value to disable its sanitization step.

## Supported Versions

The latest minor and major versions are supported with security updates.

| Version | Supported          |
| ------- | ------------------ |
| 0.9.x   | :white_check_mark: |
| 0.8.x   | :x:                |

## Reporting a Vulnerability

To report a vulnerability with the project please send an email to zachwatkins@tapfuel.io with the subject line starting with [SECURITY]. You can expect a quicker response to security issues because I take the security of this library very seriously.

I will report known vulnerabilities on the Github repo in some way and will try to learn how to do this in a way that allows users to subscribe to this kind of notice.

## Contributing to Security

At this time I welcome pull requests to improve the sanitization of Options by default, with conditions. This feature can be disabled by users, but one of my goals with the library is to provide a secure default state through default sanitization of the field data.

Conditions:

1. No external dependencies through package managers. I want this to be a dependency-free library for production use.
