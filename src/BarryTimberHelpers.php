<?php

namespace BarryTimberHelpers;

require_once __DIR__ . '/functions.php';

use Timber\Twig_Function;

class BarryTimberHelpers {
    public static function init() {
        // Delay the Timber function registration until Timber is loaded
        add_action('after_setup_theme', [self::class, 'add_custom_twig_functions'], 15);
    }

    public static function add_custom_twig_functions() {
        // Check if Timber is available before adding functions
        if (!class_exists('Timber\Timber')) {
            return;
        }

        add_filter('timber/twig', function ($twig) {
            $twig->addFunction(new Twig_Function('has_class_name', 'has_class_name'));
            $twig->addFunction(new Twig_Function('is_svg', 'is_svg'));
            $twig->addFunction(new Twig_Function('add_class_to_svg', 'add_class_to_svg'));
            $twig->addFunction(new Twig_Function('post_meta', 'post_meta'));
            $twig->addFunction(new Twig_Function('list_acf_fields_by_post_id', 'list_acf_fields_by_post_id'));
            $twig->addFunction(new Twig_Function('get_acf_block_by_post_id', 'get_acf_block_by_post_id'));
            $twig->addFunction(new Twig_Function('get_featured_image_by_post_id', 'get_featured_image_by_post_id'));
            $twig->addFunction(new Twig_Function('get_url_by_post_id', 'get_url_by_post_id'));

            return $twig;
        });
    }
}

// Initialize only after WordPress and Timber are ready
add_action('after_setup_theme', [BarryTimberHelpers::class, 'init'], 10);
