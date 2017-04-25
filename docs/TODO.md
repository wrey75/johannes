# The TODO List

This list gives extensions and possibilities to add
to the Johannes CMS. This list is a memo and should be transferred
to Issues in the GitHub environment.

## Compression

PHP can gzip the pages. It is very simple. Currently this is not
in the scope. You should use the Apache mime-type compression
or, if you prefer, activate the PHP gzip compression. The `CMSEngine`
is agnostic (you do what you want).


## Authentication

Currently, the authentication is not managed by the `CMSEngine`
which is not a real solution for the future.

## Plugins

Plugins are planned but no specification exists right now (feb. 2017).


## Internationalization (based on templates)

The templates will try to load the pages having the correct language
extension. If the language given to the CMSEngine is "fr-BE" (french
speaking in belgium) and the page "404.mustache",
Johannes will try to load "404.fr-be.mustache", if not found
will try "404.fr.mustache", then if not successfull will use the
default one ("404.mustache").





