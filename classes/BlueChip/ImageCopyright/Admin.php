<?php
/**
 * @package BC_Image_Copyright
 */

namespace BlueChip\ImageCopyright;

class Admin
{
    /**
     * @var string Name of the form input field.
     */
    const FORM_FIELD_KEY = 'copyright';


    /**
     * Initialize back-end integration.
     */
    public function init()
    {
        add_filter('attachment_fields_to_edit', [$this, 'addCopyrightField'], 5, 2);
        add_filter('attachment_fields_to_save', [$this, 'saveCopyrightField'], 10, 2);
    }


    /**
     * @filter https://developer.wordpress.org/reference/hooks/attachment_fields_to_edit/
     *
     * @param array $form_fields
     * @param \WP_Post $post
     * @return array
     */
    public function addCopyrightField(array $form_fields, \WP_Post $post): array
    {
        if (Utils::isImageMimeType($post->post_mime_type)) {
            $form_fields[self::FORM_FIELD_KEY] = [
                'label' => __('Copyright', 'bc-image-copyright'),
                'helps' => __('The copyright information for this image', 'bc-image-copyright'),
                'show_in_edit' => true,
                'show_in_modal' => true,
                'input' => 'copyright-field',
                'copyright-field' => sprintf(
                    '<input id="%s" name="%s" type="text" value="%s" class="widefat" placeholder="%s" />',
                    'attachments-' . $post->ID . '-' . self::FORM_FIELD_KEY, // id
                    'attachments[' . $post->ID . '][' . self::FORM_FIELD_KEY . ']', // class
                    esc_attr(Attachment::getCopyrightInfo($post->ID)), // value
                    esc_attr(Attachment::getDefaultCopyright($post->ID)) // placeholder
                ),
            ];
        }

        return $form_fields;
    }


    /**
     * @filter https://developer.wordpress.org/reference/hooks/attachment_fields_to_save/
     *
     * @param array $post_data
     * @param array $attachment_data
     * @return array
     */
    public function saveCopyrightField(array $post_data, array $attachment_data): array
    {
        if (Utils::isImageMimeType($post_data['post_mime_type']) && isset($attachment_data[self::FORM_FIELD_KEY])) {
            Attachment::setCopyrightInfo($post_data['ID'], $attachment_data[self::FORM_FIELD_KEY]);
        }

        return $post_data;
    }
}
