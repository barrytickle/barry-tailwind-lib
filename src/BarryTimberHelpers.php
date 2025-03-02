<?php

namespace BarryTimberHelpers;

require_once __DIR__ . '/functions.php';

use Twig\TwigFunction; // Use the correct namespace for Timber 2.x

class BarryTimberHelpers {
    public static function init() {
        // Delay the function registration until Timber is fully loaded
        add_action('after_setup_theme', [self::class, 'add_custom_twig_functions'], 15);
    }

    public static function add_custom_twig_functions() {
        // Check if Timber is available
        if (!class_exists('Timber\Timber')) {
            return;
        }

        add_filter('timber/twig', function ($twig) {
            $twig->addFunction(new TwigFunction('has_class_name', 'has_class_name'));
            $twig->addFunction(new TwigFunction('is_svg', 'is_svg'));
            $twig->addFunction(new TwigFunction('add_class_to_svg', 'add_class_to_svg'));
            $twig->addFunction(new TwigFunction('post_meta', 'post_meta'));
            $twig->addFunction(new TwigFunction('list_acf_fields_by_post_id', 'list_acf_fields_by_post_id'));
            $twig->addFunction(new TwigFunction('get_acf_block_by_post_id', 'get_acf_block_by_post_id'));
            $twig->addFunction(new TwigFunction('get_featured_image_by_post_id', 'get_featured_image_by_post_id'));
            $twig->addFunction(new TwigFunction('get_url_by_post_id', 'get_url_by_post_id'));
            $twig->addFunction(new TwigFunction('new_line_text', 'new_line_text'));
            return $twig;
        });
    }
}

// Initialize only after WordPress and Timber are ready
add_action('after_setup_theme', [BarryTimberHelpers::class, 'init'], 10);
