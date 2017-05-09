
# Johannes

Johannes is a replacement for WordPress. It is conceived to remove the defaults of the
giant and provide a first-grade CMS.

- Links are "absolute" (including their own links) resulting in SQL modification
to resolve it.

- PHP direct coding of the templates are good for time rendering but not
easy to develop and risky in terms of security.

- WordPress relies _only_ on MySQL databases. There is no way to use other databases
like MongoDB (or even PostgreSQL).

- Adding plugins can suffer of an "overload".

- Not oriented for programming users (need to create a plugin which can have bad impacts
on existing theme).

- Files are installed directly in the "htdocs" environement. Some plugins or themes are
not protected from an external call of the URL.

- There is no real layer of security. Especially, the data is outputted without escaping the
HTML (except when specified).

- Some plugins can have bad behaviours and can be also piracy code (with base64 encoded
cryptography inside).

- Only first-class programmers can really be sure the delivered code on Wordpress has
no security hole.

- Avoid to rely intensively on a cache to display the images (or their parts). Even if 
a cache could be added in cache of heavy requests (especially concerning the retrieving
of data through SQL).

- Having a repository like Composer (or simply "composer") for the storage of the
templates and packages.



# The proposal

I started the project johannes to give more flexibity to my website (koikonfait.com).

Currently, my pages renders quite well. I didn't tested yet with. That's simply
mean I compare "plain PHP" code with a templating system. I don't expect better results
but not big differences.

Johannes is a "template renderer". As WordPress rely on templates written directly in
PHP (with the famous `<?php` ... `?>` quoting everywhere, the templates are just basic
HTML. Or, to be more precise, mustache templates.

I choose mustache fot its agnostic implementation. Mustache works both on PHP, JAVA and 
Javascript. That's mean, the theme could be ported to a Johannes written in another language
with just some little effort.

The first choice was Mustache but I was unaware of HandleBars. I think I will adopt
this one in case I see better performance results.



# The principles

## A composer package

Johannes is a simple composer package you can install. I haven't yet developped a 
full-stack solution as it has been made with WordPress (a website to upload to a server
and ready to run). But I will do an effort to provide an auto-installable CMS when
it will work as a package.



## The configuration 

For Johannes, we need some information we provide as a PHP array to the information
relative to your environment. If you want, you can pass it as a decoded JSON file
(giving an easy way to promote different environments).


A basic hierarchy for a PHP project is the following:

  APACHE
  +--- composer.json
  +--- composer.lock
  +--- vendor
  ¦    +--- (packages)
  +--- htdocs
  ¦    +--- .htaccess
  |    +--- static
  ¦         +--- (files)
  |--- lib
  |    +--- (user's classes)
  +--- tmp
       +--- (temporary files)


In a managed environment, the `$APACHE` is the parent directory for all files
an user can access. But the serveur itself can only render pages or PHP scripts
inside the `$APACHE/htdocs` directory.

This is a good idea to store all the sensible information outside of the 
`$APACHE/htdocs` directory. In case your server fails to serve the PHP scripts
and just show the source code, you do not create a breach on your server
(such issues can happen during a version upgrade). In WordPress, you can store
your `wp-config.php` file containing the information in the `$APACHE` folder
rather than the `$APACHE/htdocs` one (where resides the subfolder `wp-content`
for example).



The `$APACHE/vendor` folder is to store the composer packages. You can find
the files `composer.json` and `composer.lock` in `$APACHE`directory. To use
the johannes package, you must include the `$APACHE/vendor/autoload.php` file
when needed.

The `$APACHE/tmp` folder is a temporary folder where the server is authorized
to write. It will use this directory as a cache and for other reasons.

The `$APACHE/lib` folder is for the user's classes and libraries, not available
as composer packages.


## Configuration file

The configuration file is made of hierarchical data. It should be expressed more
ore less like this (in PHP)

  <?php

  $config = [
	'dirs' => [
		'root' => "$DOCUMENT_ROOT"
		'theme' => "$DOCUMENT_ROOT/../themes",
		],
	'runtime' => [
		'debug' => TRUE,
		],
	'url' => [
		'root' => "/"
		]
	];

  $cms = new Johannes\CMSEditor($conf);


Note the configuration cumulates with the default configuration. The default
configuration is an oriented standard that avoids push your own configuration.
For example: if the server address (found through the "`SERVER_ADDR`" variable
provided by Apache is 127.0.0.1 or starting with "`10.*`" or "`192.168.*`", we
consider the editor is in mode "DEBUG". In other case, the "PRODUCTION" mode
is activated.

Also note that the CMS can recognize the protocols are can use files not available
locally (for exemple, Amazon provides a S3 protocole for streaming directly
on ther system), then you can do the same.

### Section 'runtime'

The runtime section will store the variables that relates on the runtime machine.

The options are:

- *debug*: set to TRUE if you want to debug your application. In this case, use the
section "debug" for more information. Set by default based
on the IP address of the server.

- *i18n*: if the server should serve _only_ one language, this variable must be
set to FALSE. By default, every website using Johannes are conceived to server
more than one language. Setting this value to FALSE could generate a workload
if you plan to go into a muti-language site. You can keep it "TRUE" even if your
website serves one language.

- *cache*: used for caching service. You should not provide any information here.
The value is calculated to provide a cache system when applicable.


### Section 'debug'

This section is only used in case the "runtime.debug" is set to TRUE.

- <to be completed>

### Section 'dirs'

The section which describes the directory to use. Note the path given should be
absolute. In case you give a relative path, PHP will rely on the `getcwd()` 
returned value. Better is to create an directory based on an absolute one
(the `$DOCUMENT_ROOT` coming from PHP is a good one).

- *htdocs*: the file directory for the WEB pages. Defined by default using the
PHP document root (`$_SERVER['DOCUMENT_ROOT']`).

