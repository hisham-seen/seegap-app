<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap;

defined('SEEGAP') || die();

class Meta {
    public static $link_alternate = true;
    public static $description = null;
    public static $keywords = null;
    public static $canonical = null;
    public static $robots = null;
    public static $opengraph = [
        'og:type' => 'website',
        'og:url' => null,
        'og:title' => null,
        'og:description' => null,
        'og:image' => null,
    ];
    public static $twitter = [
        /* Twitter */
        'twitter:card' => 'summary_large_image',
        'twitter:site' => null,
        'twitter:url' => null,
        'twitter:title' => null,
        'twitter:description' => null,
        'twitter:image' => null
    ];

    public static function initialize() {

        /* Add the prefix if needed */
        $language_key = preg_replace('/-/', '_', \SeeGap\Router::$controller_key);

        if(\SeeGap\Router::$path != '') {
            $language_key = \SeeGap\Router::$path . '_' . $language_key;
        }

        /* Check if the default is viable and use it */
        self::$description = l($language_key . '.meta_description', null, true);
        self::$keywords = l($language_key . '.meta_keywords', null, true);

        /* Set title */
        self::set_social_title(\SeeGap\Title::get());

        /* Opengraph image */
        if(settings()->main->opengraph) {
            self::set_social_image(\SeeGap\Uploads::get_full_url('opengraph') . settings()->main->opengraph);
        }

        /* Canonical automation */
        self::set_canonical_url();

        /* Twitter */
        self::$twitter['twitter:site'] = settings()->socials->x ? '@' . settings()->socials->x : null;
    }

    public static function set_description($value) {
        self::$description = $value;
        self::set_social_description($value);
    }

    public static function set_keywords($value) {
        self::$keywords = $value;
    }

    public static function set_social_url($value) {
        self::$opengraph['og:url'] = $value;
        self::$twitter['twitter:url'] = $value;
    }

    public static function set_social_title($value) {
        self::$opengraph['og:title'] = $value;
        self::$twitter['twitter:title'] = $value;
    }

    public static function set_social_description($value) {
        self::$opengraph['og:description'] = $value;
        self::$twitter['twitter:description'] = $value;
    }

    public static function set_social_image($value) {
        self::$opengraph['og:image'] = $value;
        self::$twitter['twitter:image'] = $value;
    }

    public static function set_canonical_url($value = null) {
        self::$canonical = $value ?? url(\SeeGap\Router::$original_request);
    }

    public static function set_robots($value) {
        self::$robots = $value;
    }

    public static function output() {
        self::$opengraph['og:site_name'] = settings()->main->title;
        self::$opengraph['og:url'] = self::$opengraph['og:url'] ?: url(\SeeGap\Router::$original_request);
        self::$twitter['twitter:url'] = self::$twitter['twitter:url'] ?: url(\SeeGap\Router::$original_request);

        echo '<!-- Open graph / Twitter markup -->' . "\n";
        foreach(\SeeGap\Meta::$opengraph as $key => $value) {
            if($value) {
                echo '<meta property="' . $key . '" content="' . $value . '" />' . "\n";
            }
        }

        foreach(\SeeGap\Meta::$twitter as $key => $value) {
            if($value) {
                echo '<meta name="' . $key . '" content="' . $value . '" />' . "\n";
            }
        }
    }

    public static function set_link_alternate($value) {
        self::$link_alternate = $value;
    }
}
