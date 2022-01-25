#!/usr/bin/env pwsh
# Create the release package.
tar -a -cf settings-page-wp.zip config src composer.json LICENSE README.md
