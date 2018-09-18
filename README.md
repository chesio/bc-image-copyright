# BC Image Copyright

Display and manage copyright info of your (image) image files.

## Requirements

* [PHP](https://secure.php.net/) 7.0 or newer
* [WordPress](https://wordpress.org/) 4.9 or newer

## Front-end usage

Use `bc_image_copyright` function to display or fetch copyright information of particular image:

```php
// Display copyright information of image (attachment) with ID = 123
echo bc_image_copyright(123);

// Display copyright information of current featured image.
echo bc_image_copyright(get_post_thumbnail_id());

// Display only manually entered copyright information (if any), do not read image file metadata as fallback.
echo bc_image_copyright(123, true);
```
