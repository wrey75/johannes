# Behaviours

The rendering is based on Mustache. This has many advantages. But
we also added some little things.


## HTML escaping

The HTML escaping relies on `std::html()` rather than `htmlentities()`
or `htmlspecialchars()`. This choice has been made to keep compatible
with the original rendering of the koikonfait website.

## Helpers

Some helpers are available "out-of-the-box". 

### Upper and lower case

We provide 4 fileters:

- `case.upper` will convert all the characters in upper case. This operation
can also be done through the CSS.

- `case.lower` same as `case.upper` but for lowering the characters.

- `case.capitalize` will capitalize (set to uppercase) the first letter of
the text.

- `case.capitalizeAll` has the same behaviour but applies on each word. Note
this is a CSS equivalent (`text-transform: capitalize;`).

Using the CSS capabilities is a good option (rather than doing it on the server-side)
but it's up to you.

## Carriage returns

- `br` will transform you carriage returns into `<br>` tags. Note this is necessary to
output in HTML (if not, the text "`<br>`" will appear!). Using this filter protects your
text because it applies after encoding to HTML.

- `stream` will remove all the carriage returns. Works both on HTML and plain text.

## Special stuff

Note the term "crypt" is not correct. These filters are NOT intended to crypt your data.

- `crypt.base64` will convert the text in base 64.

- `crypt.md5` will convert the text in a MD5 32-characters value (hexadecimal).  

- `crypt.password` will convert the text in a password using the `password_hash()`
PHP function. Note a new password is generated each time (for the same text,
the resulting one will differ).

- `crypt.hide` will convert the text to "`***`". The number of asterisk is related
to the length of the original value.

## Lambdas

The lambdas are an effective way to enhance the performances of the rendering. Rather than
trying to get the data and then to render it, you can pass some information as "lambdas"
meaning the rendering can start immediatly and the page will be completed later.

Another way to do is you render a page through Javascript. That's mean the rendering is made
by Javascript by calling your data rather than rendering directly the template. This behaviour
is quite hard to achieve with a PHP rendering but very easy to obtain with the CMSEngine (thanks
to Mustache).


One way to do is to check if the request comes from a robot (means a SEO engine) or a regular
user. One very simple way to figure out is to check if the text "bot" is included in the 
user-agent. Basically the main search engines are OK with this. Google, Bing, Yahoo, Yandex and
some other relies on this.

If you know that it is not a search-engine, you can set the AJAX rendering to TRUE via the
`setRemoteRendering()` method. This will call your page with the same URL but adding a variable
named "`_remote_`" with the name of the rendering vriable we need. You don't have to deal
with the stuff: evrything is already coded.

The AJAX rendering is only used when you provide a "callback" data for the variable. In addition,
you MUST use the `pushRemote()` method rather than the basic `push()`. Refrain to push everything
remote because the display can become "flicky".

When you push something remotely and the remote rendering has been activated, the same page is called
a second time with the `_remote_` variable set. You just generate your page as if nothing was changed.
Johannes will do the stuff to generate the correct data for inserting in the HTML page. 

NOTE: only HTML pages with javascript running can have this behaviour running (that's why search
engines are excluded).

BE CAREFUL: if the variable _remote_ has been set, even if the CMS renderer has not been set as
a remote renderer, it will generate only a part of the page with a "text/plain" content type.
This text is directly injected in the page.

GOOD QUESTION: is there a security issue? It should not. Because the same server is called back
and _only_ the generated data is outputted, we do not suffer confidential issue (in any case, the 
data will be outputted) and only a variable having a callback specified (through the `pushRemote()`)
can be generated. Then I think there is no issue.


 

This behaviour is for version 1.1 and NOT yet implemented. BUT the methods are already available.

 
 



    
 


