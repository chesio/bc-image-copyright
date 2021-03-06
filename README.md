# BC Image Copyright

Display and manage copyright information of your (image) media files.

## Requirements

* [PHP](https://secure.php.net/) 7.1 or newer
* [WordPress](https://wordpress.org/) 4.9 or newer

## Front-end usage

Use `bc_image_copyright` function to display or fetch copyright information of particular image:

```php
// Display copyright information of image (attachment) with ID = 123
echo bc_image_copyright(123);

// Display copyright information of current featured image.
if (has_post_thumbnail()) {
  echo bc_image_copyright(get_post_thumbnail_id());
}

// Display only manually entered copyright information (if any), do not read image file metadata as fallback.
echo bc_image_copyright(123, true);
```
