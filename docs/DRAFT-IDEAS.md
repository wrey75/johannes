# Helpers

Helpers are simple things you can access. There are 
two different things: the helpers provided by the plugins
and the system helpers.



# Installation

You can deploy with composer.


# The model

Johannes is based on a template-rendering. 

# Johannes -- A tutorial.

Johannes is a "Keep it Stupid and Simple" comcept.

Basically, you just have to:

- Create a Johannes `CMSObject` object. This object will
store all the information.

- Put the data in the CMSObject model. The model is used for
rendering and it can be JSON objects (PHP arrays) or PHP
objects.

- Provide a template.

- Render the page.

## Plugins

Johannes can (and should use) plugins. Un plugin is something
which give more power to the CMS.


## Barebone components

Like Wordpress, a template is made of parts. Basically a page
is made of an header, its contents and the footer. In Wordpress
but also in Johannes, you can add visual components (also known
as _widgets_).

Everything is basically displayed throuh a "push through Mustache".
This is to give more security.

## Menus

You usually have a menu

## Administration

Wordpress has an administrative site. This helps the user to add
some new themes, plugins, checks for basically everything. This
is a goal for Johannes. But, for now, there is no administration
part.









# Themes



# The model

The model must be fixed at some point. This is true for the 
scripts (the JS files), the menus and some other parts of
the WEB site.

## Menus

The variable "`menus`" will store all the menus and sub-menus
linked to the application. As usual, a menu can be deployed
or just stored in a PHP instance and created "on-the fly".

The menus entry is divided in menus. Some of them are
reserved. The "main" is the top bar menu.

A menu itself must be a `IMenu` instance (you must implement
the IMenu class) or simply a JSON description. There is NO
control based on the instance you provide.

A menu can be an _ìtem_, a _menu_ or a _separator_. Some information
can be omitted or exists.

Thanks to Mustache, you can provide for a menu a renderer (it's called
a lambda function in Mustache) or simply the 

### Using the menu renderer.

The menu is rendered by a IMenuRenderer class. A default menu renderer
is provided as an implemented stuff. The AbstractMenuRenderer is a basic
renderer which render a menu based on a data array.

The IMenuRenderer is a simple interface which is mandatory to render
a menu. It is quite simple to render a menu but we could need some
different way with different HTML tags for this (especially if your
try to use web components like Angular2 or some other frameworks
suggest).


## Add CSS files

Adding CSS files is simple. Just add the files in the CMS using
the "addCSSFile()" method provided.

The CSS files will be added in the HEAD using the `head.css_files`
call. Note, because the CSS files are added in the head part of
the HTML file, you should refrain to generate the HTML too fast.


## Add JS files

The JS files can be added by calling addScriptFile() method. The
scripts are added at the end of the page where possible. The
scripts are kept in the order they are added.

In the version 1, no ordering is possible. But it should be easy to add
in future version if needed.


















