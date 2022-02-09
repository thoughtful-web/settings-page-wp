# How to contribute

I'm glad you're interested in contributing to this library! Time does not currently allow for me to accept pull requests unrelated to security. Priorities for development are ranked as follows:

1. Fixing security issues, particularly with the sanitization of Options in [./src/Settings/Sanitize.php](./src/Settings/Sanitize.php).
2. Fixing bugs
3. Improving the developer experience for existing features
4. New features

Please be patient and courteous. I created and contribute to this library outside of working hours, so my communications and contributions will almost always be limited to the evening (Central Standard Time, US).

## Roadmap

The library's [roadmap](./docs/roadmap.md) lists features and bug fixes which are currently prioritized.

## Discussion

This library uses [Github Discussions](https://github.com/thoughtful-web/settings-page-wp/discussions) to provide a space for users to ask questions and discuss the library. A code of conduct will be added soon.

## Issues

This library uses [Github Issues](https://github.com/thoughtful-web/settings-page-wp/issues) to manage bug reports. Before reporting a bug please search existing reports to see if there is an existing report you can contribute discussion to. If I am able to address an issue by modifying the documentation or the code then I will prioritise it. If an issue does not have an acceptance criteria that is achievable

## Coding Standards and Practices

This library's code follows certain standards and practices.

1. WordPress Coding Standards, with the exception that PHP class file names follow the PSR-4 format.
3. Principles:  
   a. Secure defaults  
   b. No production dependencies  
   b. Minimal time to implement  
   c. Simplicity where possible

See [Development Installation and Notes](./docs/development.md) for information related to setting up the library for development purposes.

Thank you!  
*Zach Watkins*