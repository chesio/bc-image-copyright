<?php
/**
 * @package BC_Image_Copyright
 */

namespace BlueChip\ImageCopyright;

/**
 * Copyright info retrieval and storage.
 */
abstract class Attachment
{
    /**
     * @var string Key under which (explicit) copyright information is stored in database (wp_postmeta table).
     */
    const COPYRIGHT_POST_META_KEY = 'copyright';

    /**
     * @var string Key under which copyright author is stored in database (wp_postmeta table).
     */
    const COPYRIGHT_AUTHOR_POST_META_KEY = 'copyright-author';

    /**
     * @var string Key under which copyright link is stored in database (wp_postmeta table).
     */
    const COPYRIGHT_LINK_POST_META_KEY = 'copyright-link';


    /**
     * @return bool
     */
    public static function purgeCopyrightData(): bool
    {
        $copyrights_deleted = delete_post_meta_by_key(self::COPYRIGHT_POST_META_KEY);
        $copyright_authors_deleted = delete_post_meta_by_key(self::COPYRIGHT_AUTHOR_POST_META_KEY);
        $copyright_links_deleted = delete_post_meta_by_key(self::COPYRIGHT_LINK_POST_META_KEY);

        return $copyrights_deleted && $copyright_authors_deleted && $copyright_links_deleted;
    }


    /**
     * Get copyright information that have been extracted from image file.
     *
     * @param int $attachment_id
     * @return string
     */
    public static function getDefaultCopyright(int $attachment_id): string
    {
        $metadata = wp_get_attachment_metadata($attachment_id, true);

        return $metadata['image_meta']['copyright'] ?? '';
    }


    /**
     * Get general copyright information (that have been set manually).
     *
     * @param int $attachment_id
     * @return string
     */
    public static function getCopyrightInfo(int $attachment_id): string
    {
        return get_post_meta($attachment_id, self::COPYRIGHT_POST_META_KEY, true) ?: '';
    }


    /**
     * Get copyright author.
     *
     * @param int $attachment_id
     * @return string
     */
    public static function getCopyrightAuthor(int $attachment_id): string
    {
        return get_post_meta($attachment_id, self::COPYRIGHT_AUTHOR_POST_META_KEY, true) ?: '';
    }


    /**
     * Get copyright link.
     *
     * @param int $attachment_id
     * @return string
     */
    public static function getCopyrightLink(int $attachment_id): string
    {
        return get_post_meta($attachment_id, self::COPYRIGHT_LINK_POST_META_KEY, true) ?: '';
    }


    /**
     * Set copyright information.
     *
     * @param int $attachment_id
     * @param string $copyright_info New copyright information, may be empty.
     * @return bool True on success, false on failure.
     */
    public static function setCopyrightInfo(int $attachment_id, string $copyright_info): bool
    {
        if (empty($copyright_info)) {
            return delete_post_meta($attachment_id, self::COPYRIGHT_POST_META_KEY);
        } else {
            return update_post_meta($attachment_id, self::COPYRIGHT_POST_META_KEY, sanitize_text_field($copyright_info)) !== false;
        }
    }


    /**
     * Set copyright author.
     *
     * @param int $attachment_id
     * @param string $copyright_author New copyright author, may be empty.
     * @return bool True on success, false on failure.
     */
    public static function setCopyrightAuthor(int $attachment_id, string $copyright_author): bool
    {
        if (empty($copyright_author)) {
            return delete_post_meta($attachment_id, self::COPYRIGHT_AUTHOR_POST_META_KEY);
        } else {
            return update_post_meta($attachment_id, self::COPYRIGHT_AUTHOR_POST_META_KEY, sanitize_text_field($copyright_author)) !== false;
        }
    }


    /**
     * Set copyright link.
     *
     * @param int $attachment_id
     * @param string $copyright_link New copyright link, may be empty.
     * @return bool True on success, false on failure.
     */
    public static function setCopyrightLink(int $attachment_id, string $copyright_link): bool
    {
        if (empty($copyright_link)) {
            return delete_post_meta($attachment_id, self::COPYRIGHT_LINK_POST_META_KEY);
        } else {
            return update_post_meta($attachment_id, self::COPYRIGHT_LINK_POST_META_KEY, filter_var($copyright_link, FILTER_SANITIZE_URL)) !== false;
        }
    }
}
