<?php

function new_line_text($text) {
    return str_replace("\n", "<br>", $text);
}

function register_my_menu() {
    register_nav_menu('header-menu', __('Header Menu'));
}
add_action('init', 'register_my_menu');

function has_class_name($object, $class_name) {
    return in_array($class_name, $object);
}

function post_meta($post_id, $meta_key){
    return get_post_meta($post_id, $meta_key, true);
}

function list_acf_fields_by_post_id($post_id) {
    $post = get_post($post_id);
    if (!$post) {
        return false; // Post doesn't exist
    }

    $blocks = parse_blocks($post->post_content);
    $acf_fields = [];

    foreach ($blocks as $block) {
        if (isset($block['blockName']) && strpos($block['blockName'], 'acf/') === 0) {
            // Extract ACF block fields
            $acf_fields[$block['blockName']] = $block['attrs']['data'] ?? [];
        }
    }

    return $acf_fields;
}

function get_url_by_post_id($post_id) {
    $post = get_post($post_id);
    if (!$post) {
        return false; // Post doesn't exist
    }
    return get_permalink($post_id);
}

function get_acf_block_by_post_id($post_id, $block_name) {
    $post = get_post($post_id);
    if (!$post) {
        return false; // Post doesn't exist
    }

    $blocks = parse_blocks($post->post_content);

    foreach ($blocks as $block) {
        if (isset($block['blockName']) && $block['blockName'] === 'acf/' . $block_name) {
            // Retrieve stored ACF fields
            $acf_fields = $block['attrs']['data'] ?? [];

            // Manually resolve dynamic fields by rendering the block
            if (function_exists('render_block')) {
                $rendered_block = render_block($block); // Fully renders the block
                $acf_fields['rendered_content'] = $rendered_block;
            }

            return $acf_fields;
        }
    }

    return false; // Block not found
}

function get_featured_image_by_post_id($post_id) {
    $thumbnail_id = get_post_thumbnail_id($post_id);
    if (!$thumbnail_id) {
        return false; // No featured image
    }

    $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'full');
    return $thumbnail_url ? $thumbnail_url[0] : false;
}



function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function is_svg($url){
    $path = parse_url($url, PHP_URL_PATH);
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if($ext !== 'svg') {
        return array (
            'is_svg' => false,
            'contents' => $url
        );
    }else{
        return array (
            'is_svg' => true,
            'contents' => file_get_contents($url)
        );
    }    
}

function add_class_to_svg($svg, $class){
    $svg = str_replace('<svg', '<svg class="'.$class.'"', $svg);
    return $svg;
}


function wrap_non_acf_blocks($content) {
    $blocks = parse_blocks($content); // Efficient parsing of blocks
    $wrapped_blocks = []; // Use an array instead of direct string concatenation
    $buffer = []; // Buffer for non-ACF blocks

    foreach ($blocks as $block) {
        // Check if block is an ACF block
        if (isset($block['blockName']) && strpos($block['blockName'], 'acf/') === 0) {
            // If there are buffered non-ACF blocks, wrap and push them first
            if (!empty($buffer)) {
                $wrapped_blocks[] = '<div class="wrapped-content">' . implode('', $buffer) . '</div>';
                $buffer = []; // Reset buffer
            }
            // Directly add the ACF block without extra processing
            $wrapped_blocks[] = render_block($block);
        } else {
            // Collect non-ACF blocks in buffer
            $buffer[] = render_block($block);
        }
    }

    // If there are any leftover non-ACF blocks, wrap them
    if (!empty($buffer)) {
        $wrapped_blocks[] = '<div class="wrapped-content">' . implode('', $buffer) . '</div>';
    }

    return implode('', $wrapped_blocks); // Convert array to string efficiently
}

