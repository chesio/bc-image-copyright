<?php
/**
 * @package BC_Image_Copyright
 */

namespace BlueChip\ImageCopyright;

class Admin
{
    /**
     * @var string Name of the form input field for general information.
     */
    const COPYRIGHT_FORM_FIELD = 'copyright';

    /**
     * @var string Name of the form input field for author name.
     */
    const COPYRIGHT_AUTHOR_FORM_FIELD = 'copyright-author';

    /**
     * @var string Name of the form input field for external link.
     */
    const COPYRIGHT_LINK_FORM_FIELD = 'copyright-link';


    /**
     * Initialize back-end integration.
     */
    public function init()
    {
        add_filter('attachment_fields_to_edit', [$this, 'addCopyrightFields'], 5, 2);
        add_filter('attachment_fields_to_save', [$this, 'saveCopyrightFields'], 10, 2);
    }


    /**
     * @filter https://developer.wordpress.org/reference/hooks/attachment_fields_to_edit/
     *
     * @param array $form_fields
     * @param \WP_Post $post
     * @return array
     */
    public function addCopyrightFields(array $form_fields, \WP_Post $post): array
    {
        if (Utils::isImageMimeType($post->post_mime_type)) {
            // General copyright is always present.
            $form_fields[self::COPYRIGHT_FORM_FIELD] = [
                'label' => __('Copyright', 'bc-image-copyright'),
                'helps' => __('General copyright information for this image', 'bc-image-copyright'),
                'show_in_edit' => true,
                'show_in_modal' => true,
                'input' => 'copyright-field',
                'copyright-field' => sprintf(
                    '<input id="%s" name="%s" type="text" value="%s" class="widefat" placeholder="%s" />',
                    'attachments-' . $post->ID . '-' . self::COPYRIGHT_FORM_FIELD, // id
                    'attachments[' . $post->ID . '][' . self::COPYRIGHT_FORM_FIELD . ']', // class
                    esc_attr(Attachment::getCopyrightInfo($post->ID)), // value
                    esc_attr(Attachment::getDefaultCopyright($post->ID)) // placeholder
                ),
            ];

            // Additional fields (author and link) must be activated via filter.
            $additional_fields = apply_filters(Hooks::ADDITIONAL_FIELDS, []);

            if (in_array(self::COPYRIGHT_AUTHOR_FORM_FIELD, $additional_fields, true)) {
                $form_fields[self::COPYRIGHT_AUTHOR_FORM_FIELD] = [
                    'label' => __('Copyright author', 'bc-image-copyright'),
                    'helps' => __('Author of this image', 'bc-image-copyright'),
                    'show_in_edit' => true,
                    'show_in_modal' => true,
                    'input' => 'copyright-author-field',
                    'copyright-author-field' => sprintf(
                        '<input id="%s" name="%s" type="text" value="%s" class="widefat" />',
                        'attachments-' . $post->ID . '-' . self::COPYRIGHT_AUTHOR_FORM_FIELD, // id
                        'attachments[' . $post->ID . '][' . self::COPYRIGHT_AUTHOR_FORM_FIELD . ']', // class
                        esc_attr(Attachment::getCopyrightAuthor($post->ID)) // value
                    ),
                ];
            }

            if (in_array(self::COPYRIGHT_LINK_FORM_FIELD, $additional_fields, true)) {
                $form_fields[self::COPYRIGHT_LINK_FORM_FIELD] = [
                    'label' => __('Copyright link', 'bc-image-copyright'),
                    'helps' => __('Link to public location of this image', 'bc-image-copyright'),
                    'show_in_edit' => true,
                    'show_in_modal' => true,
                    'input' => 'copyright-link-field',
                    'copyright-link-field' => sprintf(
                        '<input id="%s" name="%s" type="text" value="%s" class="widefat" />',
                        'attachments-' . $post->ID . '-' . self::COPYRIGHT_LINK_FORM_FIELD, // id
                        'attachments[' . $post->ID . '][' . self::COPYRIGHT_LINK_FORM_FIELD . ']', // class
                        esc_attr(Attachment::getCopyrightLink($post->ID)) // value
                    ),
                ];
            }
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
    public function saveCopyrightFields(array $post_data, array $attachment_data): array
    {
        if (Utils::isImageMimeType($post_data['post_mime_type']) && isset($attachment_data[self::COPYRIGHT_FORM_FIELD])) {
            Attachment::setCopyrightInfo($post_data['ID'], $attachment_data[self::COPYRIGHT_FORM_FIELD]);

            // Additional fields (author and link) must be activated via filter.
            $additional_fields = apply_filters(Hooks::ADDITIONAL_FIELDS, []);

            if (in_array(self::COPYRIGHT_AUTHOR_FORM_FIELD, $additional_fields, true)) {
                Attachment::setCopyrightAuthor($post_data['ID'], $attachment_data[self::COPYRIGHT_AUTHOR_FORM_FIELD]);
            }

            if (in_array(self::COPYRIGHT_LINK_FORM_FIELD, $additional_fields, true)) {
                Attachment::setCopyrightLink($post_data['ID'], $attachment_data[self::COPYRIGHT_LINK_FORM_FIELD]);
            }
        }

        return $post_data;
    }
}
