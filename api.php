<?php
/**
 * Public API
 *
 * @package BC_Image_Copyright
 */

use BlueChip\ImageCopyright\Attachment;

/**
 * Display copyright information for attachment with given $attachment_id.
 *
 * @param int $attachment_id
 * @param bool $only_explicit
 * @return string
 */
function bc_image_copyright(int $attachment_id, bool $only_explicit = false): string
{
    $explicit = Attachment::getCopyrightInfo($attachment_id);

    return ($explicit || $only_explicit) ? $explicit : Attachment::getDefaultCopyright($attachment_id);
}
