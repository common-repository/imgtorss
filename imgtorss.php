<?php
/*
Plugin Name: Img To RSS
Plugin URI: https://www.nofrillsplugins.com/imgtorss
description: Adds an Image Field to an RSS feed
Version: 1.0.3
Author: No Frills Plugins
Author URI: https://www.nofrillsplugins.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Get the MIME type of an image by analyzing its path.
 *
 * @param string $image_path The path to the image file.
 * @return bool|string The MIME type if detectable, otherwise false.
 */
function nf_imgtorss_get_mime_type($image_path)
{
    $mimes = array(
        IMAGETYPE_GIF => "image/gif",
        IMAGETYPE_JPEG => "image/jpeg",
        IMAGETYPE_PNG => "image/png",
        IMAGETYPE_BMP => "image/bmp",
        IMAGETYPE_TIFF_II => "image/tiff",
        IMAGETYPE_TIFF_MM => "image/tiff",
        IMAGETYPE_ICO => "image/x-icon"
    );

    $image_type = exif_imagetype($image_path);
    return ($image_type && array_key_exists($image_type, $mimes)) ? $mimes[$image_type] : false;
}

/**
 * Echoes the RSS image element.
 */
function nf_imgtorss_add_rss_image() {
    global $post;

    if ( has_post_thumbnail($post->ID) ) {
        $thumbnail_ID = get_post_thumbnail_id($post->ID);
        $thumbnail = wp_get_attachment_image_src($thumbnail_ID, 'large');
        $mime = $thumbnail ? nf_imgtorss_get_mime_type($thumbnail[0]) : 'image/jpeg';

        $output = '<media:content xmlns:media="http://search.yahoo.com/mrss/" medium="image" type="' . esc_attr($mime) . '"';
        $output .= ' url="' . esc_url($thumbnail[0]) . '"';
        $output .= ' width="' . intval($thumbnail[1]) . '"';
        $output .= ' height="' . intval($thumbnail[2]) . '"></media:content>';

        echo $output;
    }
}

add_action('rss2_item', 'nf_imgtorss_add_rss_image');
