The Drupal QueryPath Module

This module provides a standard Drupal interface to the QueryPath 
library.

This module provides a stock distribution of QueryPath 2. If you want
to use a different version, you may do so.

What Is QueryPath?
==================

QueryPath is a PHP library designed to ease the task of working with 
HTML and XML documents. Using a library that implements many of the 
same methods as jQuery, web developers can quickly write robust 
applications that make use of a wealth of available XML data.

Imagine writing a Twitter viewer in less than 20 lines of code, or 
writing a Flickr app in 20 minutes. Need to import XML data? This 
tool provides a simple mechanism. Want to incrementally build an 
HTML document, adding to it as you process data? That is 
QueryPath's strong point. QueryPath can be used with RDF, SVG 
graphics, RSS and Atom feeds, and just about any XML format you can
imagine.

To learn more about the basics of QueryPath, you may want to check 
out one of these websites:

http://QueryPath.org
https://github.com/technosophos/querypath

QueryPath is dual-licensed under the LGPL and the MIT license (your
choice). The LGPL license is 100% compatible with Drupal's GPL 
license. (This module is licensed under the GPL to comply with 
Drupal licensing policy.)

The Drupal Module
=================

The Drupal QueryPath module provides a Drupal-centered wrapper 
around QueryPath. Along with providing simple access to the library
itself, this module defines a custom QueryPath extension that allows
you to use Drupal's database layer from within QueryPath.

That means you can do things like easily extract XML content into a 
database table, or (conversely) easily populate HTML or XML 
structures with database information.

Having trouble visualizing? Imagine selecting the comments of a 
table and having the contents be automatically formatted into an 
HTML table. That's one of the things this module can do.

How Do I Get Started?
=====================

Install the module. This is done in the usual way:
 - Copy the module to the desired directory.
 - Go to Administer > Site building > Modules
 - Enable QueryPath
 
Right now, you also need to manually fetch and install the QueryPath
library from http://querypath.org. This library can go anywhere on 
PHP's loader path. You might want to put it with your core PHP 
libraries. Alternately, you can install it in the module's directory.
For example, if your module is installed in 

sites/all/modules/querypath

Then the QueryPath library should go inside of this directory:

sites/all/modules/querypath/QueryPath

From there, you should be able to use the module.

Replacing QueryPath
===================

If you do not want to use the version of QueryPath provided in this module,
you can replace it by removing the querypath/QueryPath directory and adding
your own.