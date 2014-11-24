; $Id$ 
================================
Shell (D7 version)
================================
Richard Peacock - richard@richardpeacock.com

This module is intended for drupal sites running on shared hosting environments,
or any server where the user does not have access to an SSH shell, but would like one.
It provides an ajaxy web-based pseudo-shell, letting web users more comfortable 
with a shell environment perform operations like wget, patch, gzip, rm, etc.

Important Note! This module works by simply passing what the user types 
through PHP to the server, and capturing the output.  Be VERY careful with 
what users you allow to access this module.  Authorized users will be 
able to edit and delete files from your web server!

======================
Features
======================

+ Most Linux/Unix commands are available and work as expected.  Note: commands
  or programs which require user interaction (like emacs or vi, for example)
  will not work correctly.  Luckily:

+ There is a built-in file editor which will be available to the user when they
  type "vi, vim, emacs, or simply edit" and a file name.
  
+ The "man" command will provide a link to an off-site web based man page.

+ Features a tab-autocomplete, just like most real shells.

+ Can be loaded into a popup (or multiple popups).

======================
Directions
======================

- Unpack the module files into /sites/all/modules/shell.

- Visit your modules page in Drupal and enable the module.

- Visit your permissions page and give authorized users the
  "execute shell commands" permission, if desired.  (Otherwise, only the
  admin user will be able to use it).

- Visit yoursite.com/shell to access the shell.

======================
Notes
======================

This project was inspired by the phpterm project by bzrundi,
located here: http://phpterm.sourceforge.net/
 