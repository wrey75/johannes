# quickms

QuickMS is an idea to create a CMS based on Mustache and PHP.

The basic idea is to have a "driven" data CMS. As for Wordpress, QuickMS will provide capabilities based 
on integrating information in templates and providing plug-ins and some other stuff (like authentication)
but in a very light way.

The template will scan the Mustache template to inject the data. This is quite the same idea than WordPress
but it is more "programmer" oriented. The idea is to create a very easy way to give information. Mainly
because the goal is to be more or less language agnostic.

For the beginning, I will use PHP as the rendering engine because it is quite easy to have a PHP system
but I think node.js could be a better choice. I will see it during development.

As a database, I selected MongoDB for 2 main reasons: there is no schema and it is JSON based which could
help a lot to put information into Mustache.

NOTE: this is a really "work in progress". If you want to participate, feel free. I will try to give a
documentation for developpers.

