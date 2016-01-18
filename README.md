# catmis
Automatically exported from code.google.com/p/

Catmis is an open source content management system based on PHP and MySQL.

Some of the core features of Catmis are:

    Easy installation guide script that can create database tables and setup the default system
    Easy editing of pages using a rich-text editor supporting uploading of images and files
    Support for multiple users with different access permissions
    Support for modules and themes.
    Blog module that supports multiple blogs, comments, rss and much more
    Revisioning system
    Cache system 
    
    Installation

Execute the following steps to install Catmis on your webserver.

    Upload the php-files located in the root directory.
    Upload the folders 'admin', 'blog', 'data', 'files', 'include', 'install', 'javascript', 'theme'.
    Chmod the folders 'data/cache' and 'data/uploads' to 777 to be able to upload files.
    Chmod the file 'include/config.php' to 777 to be able to use install script.
    Go to http://yoururl and follow the installation guide.
    After the system has been installed set file permissions on 'include/config.php' to 755.
    Delete the folder 'install'.
    Goto http://yoururl/admin/index.php to start administrating your website with the user you created during installation. 
