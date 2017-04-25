#Themes 

The themes are a key part of the CMS. This are based on the
idea that themes generates the page.

## Create a theme.

A theme must have an identifier (identifiers are just letters
and digits also a "-" is authorized ONLY when inside the
identifier). And it must be stored in the "themes" directory.
Basically, theme names MUST be unique and should be only in
lowercase to ensure there is no issue with some file systems.
(we rely on the name of the directory: on Unix systems, any
character is authorized and there is a difference between
upper and lower case).


## The package.json file

A theme MUST have a "package.json" file to describe the 
theme. You can store different things in this file. Basically,
you just have to describe the theme as you do for a 
composer library. The presence of this file is checked when you
select a theme.


### Required libraries

The "require" key is a map of required dependencies.
This key is the same as the key used in the Composer
system. The basic idea is to load this information
and to inject the needs to the "composer.json" of
your application to load PHP dependencies.

### Theme name

The key "name" is used to setup the name of your theme.

The key "version" is used to specify the version of the
theme. Note the version is interpreted as a integers: that
means a version worded "1.1" corresponds to the version
"1.01", not "1.10". You can have up to 1000 values (from
0 to 999). The version is internally analysed to create
an integer version based on the first three values.
The version must be numeric. But suffixed with text
(like "-beta").


The key "description" is to describe your theme. For
themes have multiple languages, you can use a suffix
using the [ISO 639-1](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes)
for language specific description.

For example "description.fr" is the french version of
the description. This can be important for international
websites.

## The "index.php"

The `index.php` file contains the PHP code for the theme. Thisis fully 
mandatory to have this file. The file can include other files
if needed. But note that the code must declare a class having the 
same capitalized name than the package followed by the word "Theme".

For example, if the theme is named "tiny", the class will be named 
"TinyTheme". This is required. If not the theme will not work (and
errors will be outputted).

This other requirement for this class is to implement the `ITheme`
interface or extend the `BasicTheme` class. 



