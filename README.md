# johannes

*johannes* is a new CMS based on some ideas provided by Wordpress. But quite different. Written in PHP but
it is conceived to be agnostic and mustache-centric. It means that the system is driven by templates
written in Mustache.

Product in development. This project is NOT for production. If you want to help, free to you.

## Why johannes?

The name is based on the first name of [Gutenberg](https://en.wikipedia.org/wiki/Johannes_Gutenberg),
the inventor of the modern printing. Usually I try not waste time on findinng names for my projects. 

## Why PHP? Why Mustache? Why not Wordpress?

Because, my main projects are in PHP. That's why, I decided to use this language. But, basically,
I admit nodeJS or other language are quite good to develop such system. PHP is well suitable for
installation everywhere including very small servers.

The usage of [Mustache](https://mustache.github.io/) is to protect the system of the
XSS injection. Basiucally, everything is HTML escaped when you use the PHP mustache engine.
This is a very good security for PHP programmers. From the beginning, PHP is outputting
plain HTML. If you have bad data in the database, you can inject scripts directly on your page.
This can be very dangerous. On the other hand, HTML can be easily printed with the triple
bracket. That's a technical big advantage.

Wordpress is very good to create blogs and other stuff. You can do everything you want 
with a good facility but the plugins and themes rely too much on programming. I mean,
we use everytime PHP calls with high risks to have piracy. This is one issue this
project should correct. The other problem I see with WordPress, it is the usage of
MySQL in many parts. I don't think SQL is a good solution anymore.

# How it works?

As for WordPress, the project relies on themes (stored in a dedicated repository called
"themes"), and plugins (you will find them in the directory "plugins"). Then, you
add or change themes easily.

Mustache is the driver for rendering pages. This is the main difference between WordPress
and Johannes.

# The database

That is another point: WordPress relies on MySQL only to store its information.
Johannes relies on a "repository" to store the data. The system provides a key/value
engine. That's mean data can be save on any database even simple file. But the
requests are fair simple.

Due to Mustache, everything is really JSON-oriented. It means a MongoDB database is
really the best choice.

We rely also on the SLIM Framework wich makes the "proof of concept" simple.







