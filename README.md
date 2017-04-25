# johannes

*johannes* is a new CMS based on some ideas provided by Wordpress. But quite different. Written in PHP but
it is conceived to be agnostic and mustache-centric. It means that the system is driven by templates
written in Mustache.

Product in development. This project is NOT for production. If you want to help, free to you. If you
need help, please open an issue.

## Why johannes?

The name is based on the first name of [Gutenberg](https://en.wikipedia.org/wiki/Johannes_Gutenberg),
the inventor of the modern printing. Usually I do not try to waste time on findinng names for my projects.

## Why PHP? Why Mustache? Why not Wordpress?

Because, my main projects are in PHP. That's why, I decided to use this language. But, basically,
I admit nodeJS or other language are quite good to develop such system. PHP is well suitable for
installation everywhere including very small servers.

The usage of [Mustache](https://mustache.github.io/) is to protect the system of the
XSS injection. Basically, everything is HTML escaped when you use the PHP mustache engine.
This is a very good security for PHP programmers. From the beginning, PHP is outputting
plain HTML. If you have bad data in the database, you can inject scripts directly on your page.
This can be very dangerous. On the other hand, HTML can be easily printed with the triple
bracket. That's a technical big advantage.

Mustache has a big advantage over all the other systems: it is agnostic but providing
all I need to render a page. That's fine. The fact the template exists in many other
languages like Javascript, Java, Ruby and many others is a good insurance to move from
PHP to another language with a limited number of changes in the renderer. 

Wordpress is very good to create blogs and other stuff. You can do everything you want 
with a good facility but the plugins and themes rely too much on programming. I mean,
we use everytime PHP calls with high risks to have piracy. If you use themes and already
done templates (including plugins), you could be blocked at some point. You take the risk 
to use old stuff. Even on paid templates, you can find missing translations (I work in French
mainly).

Having a basic renderer using templates and quite fast is a good way to start a website.
The power of administrating users is delegated to another project (not an open source, sorry).
Then the main usage of Johannes is to render pages quickly and with minimum coding. Reducing the
code of my pages to the minimum. 

Wordpress relies on MySQL (or MariaDB) and I love programming in MongoDB. That's why Wordpress
was not my main choice. But I have respect for this product because it's just amazing to see
the power of this CMS.

# How it works?

As for WordPress, the project relies on themes (stored in a dedicated repository called
"themes"), and plugins (you will find them in the directory "plugins"). Then, you
can add or change themes easily.

Using a theme, you rely on templates to render the page. Then, as for Wordpress, you can have
different templates. Wordpress relies mainly on a limited number of pages (blogs, normal pages,
archives...). Same on Johannes. We use templating with some rules.

Mustache is the driver for rendering pages. This is the main difference between WordPress
and Johannes. Wordpress is relying on pure PHP rendering. That's a very good idea but writing
themes is more complicated. Writing themes for Johannes should be kept very simple.

Note the code for running Johannes is very light. You just have to create a PHP page and adding
this stuff:

```php

//
// In a dedicated init.php file
//

$cms = new Johannes\CMSEngine(); // Create the engine
$cms->init();  // init the engine
$cms->setTheme("brave"); // select the theme

$cms->useTemplate( "basic" ); // The basic page
$cms->setTitle( _("Welcome") ); // Set title...
 	
$cms->push("app", $app ); // Add data (can be instances of classes, anonymous functions or arrays)
$cms->run(); // render the page

```

# The database

That is another point: WordPress relies on MySQL only to store its information.
Johannes doesn't rely on a database. Everything is file-related. But, of course,
you can use any database if needed because the code is not on the CMS side (in
the first version at least).

Due to Mustache, everything is really JSON-oriented. It means a MongoDB database is
really the best choice. But redis or simply JSON written files are also a possibility.

The fact the database is NOT part of the CMS is based on the fact this CMS has been
written to render the pages of the [office240.com](https://www.office240.com) website.
This website relies on API calls and on business-oriented classes (rather than the
classic databases requests). Then evrything is data-realted and we can inject the
code directly.

# Dependencies

We tried to limit the dependencies with other project. But we intensively rely on
the [Concerto](https://github.com/wrey75/concerto) project which is already in
production for another website (having about 1000 visits per day). And, of course,
on the Mustache PHP project.

# Frequently Asked Questions
 
When I try a new product, I have several questions. Here my answers.

## Is it working?

Yes. The project is _already_ in production on office240.com, then you can use it.
And we think to develop it.

## Is it complicated to configure

Not really. You just have to specify the `ROOT` directory dedicated to Johannes
(to store the themes, the templates, the plugins). By default, it is based
on `$DOCUMENT_ROOT/cms` where `$DOCUMENT_ROOT` is the root of your pages.

NOTE: you must store the files inside the visible part of your website to ensure
a correct access for assets (CSS, javascript and images files).
   


 

