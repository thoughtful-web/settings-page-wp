#!/usr/bin/env pwsh
# Create the release package.
$tag=@(git describe --abbrev=0 --tags)
tar -a -cf "settings-page-wp-$tag.zip" config src composer.json AUTHORS LICENSE README.md SECURITY.md 
