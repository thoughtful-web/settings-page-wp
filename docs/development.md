# Development Installation and Notes

*[Home](../README.md) / Development Installation and Notes*

These notes are for anyone who wishes to contribute to the project and needs to install a development version of the project.

Run `$ git config core.hooksPath hooks` to enable the git hook script.

To add a new git hook file, run this:

```command-line
$ git add --chmod=+x hooks/<hook-file-name> && git commit -m "Add git hook"
$ git config core.hooksPath hooks
```

For testing the development branch from Github using Composer, add it as a repository to the composer.json file:

```json
{
    ...
	"repositories": [
		{
			"type": "vcs",
			"url": "https://github.com/thoughtful-web/settings-page-wp"
		}
	],
	"require": {
		"thoughtful-web/settings-page-wp": "dev-develop"
	}
	...
}
```

[Back to top](#development-installation-and-notes)
