#!/usr/bin/env pwsh
# Create the release package.
$tag=@(git describe)
tar -a -cf "settings-page-wp-$tag.zip" config src composer.json LICENSE README.md
