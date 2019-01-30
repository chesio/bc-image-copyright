<?php
/**
 * @package BC_Image_Copyright
 */

namespace BlueChip\ImageCopyright;

abstract class Hooks
{
    /**
     * @var string Name of filter that allows to activate additional copyright fields (author and link).
     */
    const ADDITIONAL_FIELDS = 'bc-image-copyright/filter:additional-fields';
}
