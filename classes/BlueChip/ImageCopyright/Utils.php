<?php
/**
 * @package BC_Image_Copyright
 */

namespace BlueChip\ImageCopyright;

abstract class Utils
{
    /**
     * @param string $mime_type
     * @return bool
     */
    public static function isImageMimeType(string $mime_type): bool
    {
        return strpos($mime_type, 'image/') === 0;
    }
}
