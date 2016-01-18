# Installation #

Execute the following steps to install Catmis on your webserver.

  * Upload the php-files located in the root directory.
  * Upload the folders 'admin', 'blog', 'data', 'files', 'include', 'install', 'javascript', 'theme'.
  * Chmod the folders 'data/cache' and 'data/uploads' to 777 to be able to upload files.
  * Chmod the file 'include/config.php' to 777 to be able to use install script.
  * Go to http://yoururl and follow the installation guide.
  * After the system has been installed set file permissions on 'include/config.php' to 755.
  * Delete the folder 'install'.
  * Goto http://yoururl/admin/index.php to start administrating your website with the user you created during installation.