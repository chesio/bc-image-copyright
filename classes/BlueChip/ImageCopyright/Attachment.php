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
     * @var string Key under which (explicit) copyright info is stored in database.
     */
    const POST_META_KEY = 'copyright';


    /**
     * @return bool
     */
    public static function purgeCopyrightData(): bool
    {
        return delete_post_meta_by_key(self::POST_META_KEY);
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
     * Get copyright information (that have been set manually).
     *
     * @param int $attachment_id
     * @return string
     */
    public static function getCopyrightInfo(int $attachment_id): string
    {
        return get_post_meta($attachment_id, self::POST_META_KEY, true) ?: '';
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
            return delete_post_meta($attachment_id, self::POST_META_KEY);
        } else {
            return update_post_meta($attachment_id, self::POST_META_KEY, sanitize_text_field($copyright_info)) !== false;
        }
    }
}
