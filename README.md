#OpenGovernment BusinessUSA Website

##Installation Instructions

1.  Deflate the os-db.sql.gz file found in the root of this repository to obtain a os-db.sql file. On Linux you can do this with the "gunzip os-db.sql.gz" command. In Windows, you can use [7-Zip](http://www.7-zip.org/) for this.
2.  Now that you have a os-db.sql file, import this into your database.
3.  Clone the repository and set your webserver to point to ~/webapp
4.  Make sure to create your settings file under OpenGovernment-BusinessUSA-website/trunk/webapp/sites/default/settings.php - you can follow the instructions [here](https://www.drupal.org/documentation/install/settings-file): [Drupal settings.php instructions](https://www.drupal.org/documentation/install/settings-file)
5.  Apply the security settings detailed [here](https://www.drupal.org/node/244924): [Securing file permissions and ownership](https://www.drupal.org/node/244924)
6. Go to /user in your browser, log in with the credentials "admin"/"Password1"
7. Now that you are logged in as the Administrator, go to /admin/config/development/performance in your browser, and click the "Clear all caches" button
8. After the cache flush, you are done. Go to the front page to start viewing the site. You can go to /admin/index for a full administration interface.

##Developer Notes
### The sites/all/pages directory
The sites/all/pages directory was our solution to avoid putting basic pages with PHP code in the database.  It's much easier to find code in a file versus finding code in a database.  In order to create a new page for http://site-url/page-example, you will need to create two files - page-example.php and page-example.info.  Here is an example of an info file: 
```
; All lines that start with a semicolon in this document are comments.
; The syntax for all *.page.info files is the INI syntax - refer to: http://en.wikipedia.org/wiki/INI_file

; "render mode" usage:
;     normal = Render the file normal, as if it was a Basic Page
;     body = The HTML/[PHP] in this file will be the ONLY markup between the <body> and </body> tag. ALL other markup within the <body> tag is destroyed. Note that this option preserves the <head> tag.
;     solo = Drupal's rendering engine with no be used. ONLY the HTML/[PHP] in this file will be returned to the client-browser. Note, this requieres the usage of the drupal_output_buffering module.

title = Page Example
index in solr = true
inject dev note = true
render mode = normal
```
Your page-example.php file will simply contain the code to create the desired output content of the page after the title.  

Similarily, the same pattern applies to directories.  For example, if you created sites/all/pages/page-example and created sites/all/pages/page-example/.page.info and sites/all/pages/page-example/.page.php - you would have your page accessible through http://site-url/page-example.  Also, if you created sites/all/pages/page-example/dir-example.info and sites/all/pages/page-example/dir-example.php - you would then see your page at http://site-url/page-example/dir-example

### The sites/all/modules/custom/hooks_reaction directory
The sites/all/modules/custom/hooks_reaction directory contains code that will be included on the Drupal init process.  This concept was implemented to avoid having a huge general .module file with all of our various hooks.  With this structore, we can have smaller files with descriptive names containing the desired functionality. 
