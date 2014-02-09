drumbs
===========

On-demand image manipulation for PHP 5.3+ (with Laravel 4 support)

![demo](https://raw.github.com/mjolnic/drumbs/master/demo/images/demo.jpg)

This tool generates thumbnails (drum\*b\*s) or/and adds effects to an image, on the fly by request,
using the WideImage library.

The image is always sent over the HTTP server for better performance.

## Setup

You can either use it in standalone mode like in the demo folder, or as a Laravel 4 package:

In the `require` key of `composer.json` file add the following

    "mjolnic/drumbs": "dev-master"

Run the Composer update comand

    $ composer update

In your `config/app.php` add `'Mjolnic\Drumbs\DrumbsServiceProvider'` to the end of the `$providers` array
it will bind the required route for you.

```php
'providers' => array(

    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    'Illuminate\Auth\AuthServiceProvider',
    ...
    'Mjolnic\Drumbs\DrumbsServiceProvider',

),
```

## How it works

Considering you have an image located in /demo/images/demo.jpg, you can
generate various versions on the fly by adding another segment before the filename.

This segment is the task name that will be used, and the parameters are separated
by underscores.

Tasks names must have a prefix, e.g. th_, thumb_, th-something_, etc.

When the file does not exist, the .htaccess (or the router in laravel) automatically binds the request to *drumbs*,
where the new image will be generated if the task name is valid (and allowed) and the original
file exists in the parent folder.

When it's done *drumbs* will refresh the request so the HTTP server can handle and send the new image.
If there's some errors or the request is not valid, a 404 error with an empty body is sent.

Check the demo/drumbs.php file and **navigate to the demo/index.html page** to see the possibilities.

Drumbs comes with many predefined actions, but you can create your own.

Of course, the 'demo/images' folder is only for demonstration purposes, you can use drumbs with any folders.
