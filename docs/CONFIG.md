# Configuration

The configuration is passed to the CMSEngine at initialisation time. When
possible, store the configuration information outside the "www" directory
(the directory exposed to the WEB).

## the root for Johannes

We consider `$JOHANNES_ROOT`, the root directory for all the Johannes
related files (themes, plugins, etc.). This directory is _not_ related
to the Johannes classes (which are stored in the `vendor` directory).

The `$JOHANNES_ROOT` should be available in the `www` folder which is
the root of the visible part. By default, the `$JOHANNES_ROOT` is
defined as `$DOCUMENT_ROOT/johannes` where `$DOCUMENT_ROOT` is the
root PHP directory of your website (usually the "www" directory, you
can check with `phpinfos()`).


## The different paths

Here the different paths:

- `dir.root` (the ROOT_DIR): the main directory for everything related to the
CMS. Defaults to `$DOCUMENT_ROOT/cms` where the `$DOCUMENT_ROOT` is the PHP
document root.

- `dir.themes`, the directory where are save the themes. By default,
`${dir.root}/themes` (`$DOCUMENT_ROOT/cms/themes`). Note 
this part SHOULD BE available in the
"www" directory because the themes keeps assets to be loaded by
the browser (images, fonts, scripts and style files).

## Theme

- `theme`: the theme to use (defaulted to 'basic'). A basic theme is
planned to be provided. But the current version DO NOT INCLUDE IT. YOU
MUST PROVIDE A THEME.




