README
======

Using the autoloader
--------------------

    require 'pit/load.php';

This will register the autoloader, allowing you to use any Pit classes. The
autoloader can be used for your own PHP files as well: just add your source
directories to the include path.

    PitIncludePath::prepend('path/to/classes');

The autoloader will look for *.php and *.class.php files.

Using the router
----------------

    Pit::loadRoutes('path/to/router.conf');
    $router = Pit::getRouter();

Pit makes it very easy to initalialize the most frequently used components.
This specific invocation will create a PitRouter object and load the speicified
configuration file. The very concise format of this configuration file is
demonstrated in doc/router.conf-example.

