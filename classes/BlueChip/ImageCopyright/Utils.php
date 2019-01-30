<?php
/**
 * @package BC_Image_Copyright
 */

namespace BlueChip\ImageCopyright;

abstract class Utils
{
    /**
     * Translate attachment URL (like http://www.example.com/wp-content/uploads/2019/01/example-150x150.jpg) into attachment ID.
     *
     * @link https://philipnewcomer.net/2012/11/get-the-attachment-id-from-an-image-url-in-wordpress/
     *
     * @global \wpdb $wpdb
     * @param string $attachment_url
     * @return int|null Attachment ID on success or null on failure (eg. invalid URL).
     */
    public static function getAttachmentIdFromUrl(string $attachment_url): ?int
    {
        /** @var \wpdb */
        global $wpdb;

        // Get the upload directory paths.
        $upload_dir_paths = wp_upload_dir();
        if ($upload_dir_paths['error'] !== false) {
            // Something failed, sorry.
            return null;
        }

        // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image.
        if (strpos($attachment_url, $upload_dir_paths['baseurl']) === false) {
            return null;
        }

        // If this is URL of an auto-generated thumbnail, get URL of the original image.
        $original_attachment_url = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url);

        // Remove the upload path base directory from the attachment URL.
        $relative_attachment_url = str_replace($upload_dir_paths['baseurl'] . '/', '', $original_attachment_url);

        // Finally, run a custom database query to get the attachment ID from the modified attachment URL.
        $attachment_id = $wpdb->get_var(
            $wpdb->prepare("SELECT posts.ID FROM {$wpdb->posts} posts, {$wpdb->postmeta} postmeta WHERE posts.ID = postmeta.post_id AND postmeta.meta_key = '_wp_attached_file' AND postmeta.meta_value = %s AND posts.post_type = 'attachment'", $relative_attachment_url)
        );

        return is_null($attachment_id) ? null : intval($attachment_id);
    }


    /**
     * @param string $mime_type
     * @return bool
     */
    public static function isImageMimeType(string $mime_type): bool
    {
        return strpos($mime_type, 'image/') === 0;
    }
}
