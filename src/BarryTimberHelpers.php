<?php

namespace BarryTimberHelpers;

require_once __DIR__ . '/functions.php';

use Timber\Twig_Function;
use Timber\Timber;

class BarryTimberHelpers {
    public static function init() {
        add_filter('timber/twig', [self::class, 'add_custom_twig_functions']);
    }

    public static function add_custom_twig_functions($twig) {
        $twig->addFunction(new Twig_Function('has_class_name', 'has_class_name'));
        $twig->addFunction(new Twig_Function('is_svg', 'is_svg'));
        $twig->addFunction(new Twig_Function('add_class_to_svg', 'add_class_to_svg'));
        $twig->addFunction(new Twig_Function('post_meta', 'post_meta'));
        $twig->addFunction(new Twig_Function('list_acf_fields_by_post_id', 'list_acf_fields_by_post_id'));
        $twig->addFunction(new Twig_Function('get_acf_block_by_post_id', 'get_acf_block_by_post_id'));
        $twig->addFunction(new Twig_Function('get_featured_image_by_post_id', 'get_featured_image_by_post_id'));
        $twig->addFunction(new Twig_Function('get_url_by_post_id', 'get_url_by_post_id'));

        return $twig;
    }
}

// Initialize the class when WordPress is loaded
add_action('after_setup_theme', [BarryTimberHelpers::class, 'init']);
