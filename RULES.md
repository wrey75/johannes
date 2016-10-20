# Rules

## Convention over configuration

Configuration is quite long, everybody tries
to do configuration with some different rules.
At the end, it is quite complicated to understand
a project versus another one.

QuickMS is based on "conventions". If you don't like
them, see other CMS.

## Language agnostic

Based on PHP, the idea is to have an agnostic CMS. This is
why Mustache had been promoted for templating. It is not
the best but quite interesting and simple to use. The other
advantage is to have this language also available in other
platforms like nodeJS.

## Database

I selected MongoDB as the prefered database due to its
capability to store documents. And especially JSON documents.
This will help to get the information. But I will try to
give an agnostic database access. But, in any case, do not
rely on SQL databases: there are not the best choice for
content related stuff.

# WordPress inspired

The QuickMS is directly inspired by the Wordpress philosophy
and we will follow some of the best practises offered by this
amazing system:

 - themes: we keep the themes to easily have a new display
	when you want to change the visual of your blog. It is
	more about menus, side bars and footers.
 - templates: templates are for "one" page only and should
	extend the theme. The idea is to be able, for any page,
	to change the content of the main data displayed.
 - plugins: they are a powerful mechanism to add features.
	I kept the name "plugin".

I have created dedicated directories for each of them. These
are stored as plain files. But, we can imagine a way to store
them in the database to help. Using the GridFS of MongoDB
could be a very powerful way to handle that.

In WordPress, there are some drwbacks I would like to avoid:

- too much user oriented. Even very useful, this can be a mess
when you want to develop a new feature and add it.
- Use directly the calls to PHP functions which can be a problem
in some cases.
- What about international sites using more than one language
and more than one locale. 
- Having some quick design and A/B testing to tests visits
depending of the display.
- Include a basic cache using the MongoDB capabilities.
- Storing multiple versions and having JSON direct access.






