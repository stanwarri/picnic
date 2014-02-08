drumbs
===========

On demand image manipulation for PHP 5.3+

This tool generates images on the fly by request.
The image is always sent over the HTTP server for better performance.

## How it works

Considering you have an image located in /drumbs/uploads/demo.jpg, you can
generate various versions on the fly by adding another segment before the filename.

The format segment has 3 parts separated by 'x': width, height and filter.
Width and height can be either a positive number greater than 0, A (for auto) or N (for none).

There are 2 reserved filter parameters: C (cover) and R (resize), but a custom filter
can have a combination of 2 alphanumeric capital characters.

When the file does not exist, the .htaccess automatically binds the request to *drumbs*,
where the new image will be generated if the format is valid (and allowed) and the original
file exists in the parent folder.

When it's done *drumbs* will refresh the request so Apache can handle and send the new image.
If there's some errors or the request is not valid, a 404 error with an empty body is sent.

Check the drumbs/config.php and filters.php to see the possibilities.

Of course, the 'uploads' folder is only for demonstration purposes, you can use drumbs with any folders.

## Examples:

* Cover (resize, center and crop)

<pre>http://localhost/drumbs/uploads/200x500xR/demo.jpg</pre>

* Fit inside (resize)

<pre>http://localhost/drumbs/uploads/200x500xC/demo.jpg</pre>

* Gray scale (custom filter)

<pre>http://localhost/drumbs/uploads/NxNxBW/demo.jpg</pre>