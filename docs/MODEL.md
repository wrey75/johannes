# The model

Johannes is based on a template-rendering. You have a template specified in a theme
like for Wordpress and the `CMSEngine` will render it.

## Theme

The theme is the central part of the rendering. The themes can be exchanged at a low
expense of modifications. That's means you cna change a theme quite easily because
we have, like for Wordpress, some rules when developper creates a theme.

Inside a theme, there are templates. Templates are related to the display of
a page. The template is specific to the page you like to dispolay: typically, if
you want to display a post from a blog or a list of events, or things the customer
can buy.

The theme is the way the template is displayed. I can give you two references. If you
are familiar with HTML, the template is related to the CSS part and the template to
the HTML part. If you are familiar with Wordpress: this is the same concept: see
[this page](https://developer.wordpress.org/themes/basics/template-hierarchy/) for
details about Wordpress hierarchy.

Then you have a file repository (in version 1, it must be physical files) where themes
are stored. This information is stored in the "path.themes" configuration value you
pass to the CMSEngine at initialisation time.

The theme you want to use is given through the `theme`

## Internationalization

Wordpress and many PHP websites use the "gettext" extension and are based on the
gettext Unix standard. And CMSEditor follow the rules.

When you use the PHP files, the translation is offered out-of-the box with the
magic function "_" (you just have to write `_("My accounts")` to have a translation
capability). 

Unfortunatly, Mustache templates do NOT implement the internationalization. Version
2 will offer a special load of the template that will permit to use language-suffixed 
naming. The technique comes from Apache and the principle is simple.
Based on the language negotiated by the browser (via the "Accept-Language" header),
Johannes will try to find a page "page.fr-FR.html", then a page "page.fr.html" then
a page "page.html".

That's mean the template will be adpated based on the language and, when possible
the country. This is a very useful way to manipulate the themes in order to have
pages based on the user's culture. See the "TODO List" to check the current
implementation.


Nevertheless, translating is a big work especially for templates using limited parts of text
(like when we have "Previous" and "Next" for navigating between pages). One solution is 
to use icons rather than text but it is a bad idea. It does not help the crawlers and
visually impaired users.

That's why because you can use directly the gettext() capabilities. On the Mustache
side, we provide a "_" variable for translations. Here a template part:

	<a href="previous_page.php">{{_.previous}}</a>
	<a href="next_page.php">{{_.next}}</a>

In the model, just put the translation. Like this:


	$cms = new CMSEditor( $CMS_CONFIG );
	
	$cmd->i18n("previous", _("Previous"));
	$cmd->i18n("next", _("Next"));



Then you have a translated text in the template. I recognize the effort is more or
less important depending of the text you have to translate.

The "i18n" works with keys, not with the text itself (you have to provide the
key in the PHP). This is due to the fact gettext() is not compatible with mustache.
Note you can have other approach for the same problem. One approach is to have
a Mustache replacement for the entire button (by providing HTML rather than pure
text). See a proposal below:


	{{{previousButton}}}
	{{{nextButton}}}

In the model, create the buttons like this

	$cms = new CMSEditor( $CMS_CONFIG );

	$cmd->put("previousButton", std::link("previous_page.php", _("Previous")));
	$cmd->put("nextButton", std::link("next_page.php", _("Next")));


This means the responsability of the button is now entirely in PHP. This is
an interesting approach. And my preference goes to the second solution. The
object "std" is provided by the [Concerto](https://github.com/wrey75/concerto) library included by Johannes.

In any case, Johannes will use the `gexttext()` library. But it is the default
behaviour. If you have other
needs (like storing translation in the database), it's just up to you. You can
also use the pipe ("`|`") oerator for doing this.  


# Variable scopes

Because of the existence of themes, templates, plugins and user-defined
variables (like the title of the page), we use scopes. That's mean we
expect the theme uses its own variables without interfering with user
data.

The variable scope is then defined in several parts:

- The "theme" scope is

- The `config` scope is used internally and can NOT be used except if
exported by the theme.

- The `default` scope.







