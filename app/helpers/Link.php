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

class Link {

    public static function get_processed_background_style($settings) {
        $style = '';

        switch($settings->background_type) {
            case 'image':

                $style = 'background: url(\'' . \SeeGap\Uploads::get_full_url('backgrounds') . $settings->background . '\');';

                break;

            case 'gradient':

                $style = 'background-image: linear-gradient(135deg, ' . $settings->background_color_one . ' 10%, ' . $settings->background_color_two . ' 100%);';

                break;

            case 'color':

                $style = 'background: ' . $settings->background . ';';

                break;

            case 'preset':
            case 'preset_abstract':
                $microsite_backgrounds = require APP_PATH . 'includes/microsite_backgrounds.php';
                $style = $microsite_backgrounds[$settings->background_type][$settings->background];

                break;
        }

        /* Background attachment */
        $style .= 'background-attachment: ' . ($settings->background_attachment ?? 'scroll') . ';';

        return $style;
    }

    public static function get_processed_backdrop_style($settings) {
        $style = '';

        /* Background blur */
        if((isset($settings->background_blur) && $settings->background_blur != 0) || isset($settings->background_brightness) && $settings->background_brightness != 100) {
            $style .= 'backdrop-filter: blur(' . $settings->background_blur .'px) brightness(' . $settings->background_brightness . '%);-webkit-backdrop-filter: blur(' . $settings->background_blur .'px) brightness(' . $settings->background_brightness . '%);';
        }

        return $style;
    }

    public static function get_processed_link_style($settings) {
        $class = '';
        $style =
            'background: ' . $settings->background_color . ';'
            . 'color: ' . $settings->text_color . ';'
            . 'border-width: ' . ($settings->border_width ?? '1') . 'px;'
            . 'border-color: ' . ($settings->border_color ?? 'transparent') . ';'
            . 'border-style: ' . ($settings->border_style ?? 'solid') . ';'
            . 'box-shadow: ' . ($settings->border_shadow_offset_x ?? '0') . 'px ' . ($settings->border_shadow_offset_y ?? '0') . 'px ' . ($settings->border_shadow_blur ?? '20') . 'px ' . ($settings->border_shadow_spread ?? '0') . 'px ' . ($settings->border_shadow_color ?? '#00000010') . ';'
            . 'text-align: ' . ($settings->text_alignment ?? 'center') . ';';

        /* Animation */
        if(isset($settings->animation)) {
            $class .= ' animate__animated animate__' . $settings->animation_runs . ' animate__' . $settings->animation . ' animate__delay-2s';
        }

        return ['class' => $class, 'style' => $style];
    }

    public static function get_microsite($tthis, $link, $user = null, $microsite_blocks = null) {

        /* Determine the background of the microsite */
        $link->design = new \StdClass();
        $link->design->background_class = '';
        $link->design->background_style = '';

        if(isset($tthis->microsite_theme) && $tthis->microsite_theme) {
            $link->settings = (object) array_merge((array) $link->settings, (array) $tthis->microsite_theme->settings->microsite);
        }

        $link->design->background_style = self::get_processed_background_style($link->settings);
        $link->design->backdrop_style = self::get_processed_backdrop_style($link->settings);

        /* Determine the color of the header text */
        $link->design->text_style = 'color: ' . $link->settings->text_color;

        /* Determine the notification branding settings */
        if($user && !$user->plan_settings->removable_branding && !$link->settings->display_branding) {
            $link->settings->display_branding = true;
        }

        if($user && $user->plan_settings->removable_branding && !$link->settings->display_branding) {
            $link->settings->display_branding = false;
        }

        /* Check if we can show the custom branding if available */
        if(isset($link->settings->branding, $link->settings->branding->name, $link->settings->branding->url) && !$user->plan_settings->custom_branding) {
            $link->settings->branding = false;
        }

        /* Prepare the view */
        $data = [
            'link'  => $link,
            'user'  => $user,
            'microsite_blocks' => $microsite_blocks
        ];

        $view = new \SeeGap\View('l/partials/microsite', (array) $tthis);

        return $view->run($data);

    }

    public static function get_microsite_link($link, $user = null, $microsite_theme = null, $microsite = null) {

        $data = [];

        $microsite_blocks = require APP_PATH . 'includes/enabled_microsite_blocks.php';

        if(!array_key_exists($link->type, $microsite_blocks)) {
            return null;
        }

        /* Apply theme if needed */
        if($microsite_theme && $microsite_blocks[$link->type]['themable']) {
            switch($link->type) {
                case 'socials':
                    $link->settings = (object) array_merge((array) $link->settings, (array) $microsite_theme->settings->microsite_block_socials ?? []);
                    break;

                case 'heading':
                    $link->settings = (object) array_merge((array) $link->settings, (array) $microsite_theme->settings->microsite_block_heading ?? []);
                    break;

                case 'paragraph':
                    $link->settings = (object) array_merge((array) $link->settings, (array) $microsite_theme->settings->microsite_block_paragraph ?? []);
                    break;

                default:
                    $link->settings = (object) array_merge((array) $link->settings, (array) $microsite_theme->settings->microsite_block ?? []);
                    break;
            }
        }

        /* Require different files for different types of links available */
        switch($link->type) {
            case 'link':
            case 'big_link':
            case 'email_collector':
            case 'contact_collector':
            case 'feedback_collector':
            case 'file':
            case 'pdf_document':
            case 'powerpoint_presentation':
            case 'excel_spreadsheet':
            case 'cta':
            case 'share':
            case 'coupon':
            case 'youtube_feed':
            case 'paypal':
            case 'phone_collector':
            case 'donation':
            case 'product':
            case 'service':
            case 'faq':
            case 'list':
            case 'alert':

                /* Determine the css and styling of the button */
                $link_style = self::get_processed_link_style($link->settings);

                $link->design = new \StdClass();
                $link->design->link_class = $link_style['class'];
                $link->design->link_style = $link_style['style'];

                /* UTM Parameters */
                $link->utm_query = null;
                if($user->plan_settings->utm) {
                    $utm_parameters = [];
                    if($link->utm->source ?? null) $utm_parameters['source'] = $link->utm->source;
                    if($link->utm->medium ?? null) $utm_parameters['medium'] = $link->utm->medium;
                    if($link->settings->name ?? null) $utm_parameters['campaign'] = $link->settings->name;

                    if(count($utm_parameters) > 1) {
                        $append_query = http_build_query($utm_parameters);

                        $link->utm_query = '?' . $append_query;
                    }
                }

                /* Call to action custom link */
                if($link->type == 'cta') {
                    switch($link->settings->type) {
                        case 'email':
                            $link->location_url = 'mailto:' . $link->settings->value;
                            break;
                        case 'call':
                            $link->location_url = 'tel:' . $link->settings->value;
                            break;
                        case 'sms':
                            $link->location_url = 'sms:' . $link->settings->value;
                            break;
                        case 'facetime':
                            $link->location_url = 'facetime:' . $link->settings->value;
                            break;
                    }
                }

                /* Generate paypal payment link */
                if($link->type == 'paypal') {
                    $paypal_type = [
                        'buy_now' => '_xclick',
                        'add_to_cart' => '_cart',
                        'donation' => '_donations'
                    ];

                    if($link->settings->type == 'add_to_cart') {
                        $link->location_url = sprintf('https://www.paypal.com/cgi-bin/webscr?business=%s&cmd=%s&currency_code=%s&amount=%s&item_name=%s&button_subtype=products&add=1&return=%s&cancel_return=%s', $link->settings->email, $paypal_type[$link->settings->type], $link->settings->currency, $link->settings->price, $link->settings->title, $link->settings->thank_you_url, $link->settings->cancel_url);
                    } else {
                        $link->location_url = sprintf('https://www.paypal.com/cgi-bin/webscr?business=%s&cmd=%s&currency_code=%s&amount=%s&item_name=%s&return=%s&cancel_return=%s', $link->settings->email, $paypal_type[$link->settings->type], $link->settings->currency, $link->settings->price, $link->settings->title, $link->settings->thank_you_url, $link->settings->cancel_url);
                    }
                }

                /* Get payment processors */
                if(in_array($link->type, ['donation', 'product', 'service'])) {
                    $data['payment_processors'] = (new \SeeGap\Models\PaymentProcessor())->get_payment_processors_by_user_id($user->user_id);
                }

                if($microsite_blocks[$link->type]['type'] == 'default') {
                    $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                } else {
                    $view_path = \SeeGap\Plugin::get($microsite_blocks[$link->type]['type'] . '-blocks')->path . 'views/l/microsite_blocks/' . $link->type . '.php';
                }

                break;

            case 'heading':
            case 'paragraph':

                $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';

                break;

            case 'socials':
                $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                break;

            case 'avatar':
            case 'image':
            case 'image_grid':
            case 'map':
            case 'image_slider':

                /* UTM Parameters */
                $link->utm_query = null;
                if($user->plan_settings->utm && $link->utm->medium && $link->utm->source) {
                    $link->utm_query = '?utm_medium=' . $link->utm->medium . '&utm_source=' . $link->utm->source . '&utm_campaign=' . $link->settings->name;
                }

                $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';

                break;

            case 'header':

                /* UTM Parameters */
                $link->utm_query = null;
                if($user->plan_settings->utm && $link->utm->medium && $link->utm->source) {
                    $link->utm_query = '?utm_medium=' . $link->utm->medium . '&utm_source=' . $link->utm->source . '&utm_campaign=' . $link->settings->name;
                }

                preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|shorts\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?/', $link->settings->video_url ?? '', $match);

                $data['embed'] = $match[1] ?? null;

                $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';

                break;

            case 'youtube':
                    preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|shorts\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?/', $link->location_url, $match);

                    $data['embed'] = $match[1] ?? null;

                    if($data['embed']) {
                        $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                    }

                break;

            case 'threads':

                if(preg_match('/(threads\.net)/', $link->location_url)) {
                    $data['embed'] = $link->location_url;

                    $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                }

                break;


            case 'soundcloud':

                if(preg_match('/(soundcloud\.com)/', $link->location_url)) {
                    $data['embed'] = $link->location_url;

                    $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                }

                break;

            case 'vimeo':

                if(preg_match('/https:\/\/(player\.)?vimeo\.com(\/video)?\/(\d+)/', $link->location_url, $match)) {
                    $data['embed'] = $match[3];

                    $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                }

                break;

            case 'twitch':

                if(preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:twitch\.tv\/(?:(?P<channel>[a-zA-Z0-9_]+)\/clip\/(?P<clip_slug>[a-zA-Z0-9_-]+)|videos\/(?P<video_id>\d+)|(?P<channel_only>[a-zA-Z0-9_]+))|clips\.twitch\.tv\/(?P<clip_direct>[a-zA-Z0-9_-]+))$/', $link->location_url, $match)) {

                    if(!empty($match['video_id'])) {
                        $data['embed'] = $match['video_id'];
                        $data['embed_type'] = 'video';
                    } elseif(!empty($match['clip_slug'])) {
                        $data['embed'] = $match['clip_slug'];
                        $data['embed_type'] = 'clip';
                    } elseif(!empty($match['clip_direct'])) {
                        $data['embed'] = $match['clip_direct'];
                        $data['embed_type'] = 'clip';
                    } else {
                        $data['embed'] = $match['channel_only'];
                        $data['embed_type'] = 'channel';
                    }

                    $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                }

                break;


            case 'spotify':

                if(preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:open\.)?(?:spotify\.com\/)(?:intl-.+\/)*(album|track|show|episode|playlist)+\/(.+)$/', $link->location_url, $match)) {
                    $data['embed_type'] = $match[1];
                    $data['embed_value'] = $match[2];

                    $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                }

                break;

            case 'tiktok_video':

                if(preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:tiktok\.com\/.+\/)(.+)$/', $link->location_url, $match)) {
                    $data['embed'] = $match[1];

                    $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                }

                break;

            case 'tiktok_profile':

                if(preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:tiktok\.com\/@)([^\/\?]+)/', $link->location_url, $match)) {
                    $data['embed'] = $match[1];

                    $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                }

                break;


            case 'twitter_profile':

                $link->location_url = str_replace('https://x.com/', 'https://twitter.com/', $link->location_url);

                if(preg_match('/(https:\/\/twitter\.com)/', $link->location_url)) {
                    $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                }

                break;

            case 'twitter_tweet':

                $link->location_url = str_replace('https://x.com/', 'https://twitter.com/', $link->location_url);

                if(preg_match('/(https:\/\/twitter\.com)/', $link->location_url)) {
                    $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                }

                break;

            case 'twitter_video':

                $link->location_url = str_replace('https://x.com/', 'https://twitter.com/', $link->location_url);

                if(preg_match('/(https:\/\/twitter\.com)/', $link->location_url)) {
                    $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                }

                break;

            case 'instagram_media':

                $instagram_url = $link->settings->instagram_media_url ?? $link->location_url ?? '';
                if(preg_match('/(https:\/\/www.instagram\.com)/', $instagram_url)) {
                    $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                }

                break;

            case 'typeform':

                if(preg_match('/https:\/\/.+.typeform\.com\/to\/([a-zA-Z0-9]+)/', $link->location_url, $match)) {
                    $data['embed'] = $match[1];

                    $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                }

                break;

            case 'calendly':

                if(preg_match('/(https:\/\/calendly\.com)/', $link->location_url)) {
                    $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';
                }

                break;

            case 'custom_html':
            case 'divider':

                $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';

                break;

            case 'facebook':
            case 'countdown':
            case 'timeline':
            case 'review':
            case 'markdown':
            case 'iframe':

                $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';

                break;

            case 'external_item':

                /* Determine the css and styling of the button */
                $link->design = new \StdClass();
                $link->design->card_class = '';
                $link->design->card_style = 'background: ' . $link->settings->background_color . ';border-width: ' . $link->settings->border_width . 'px; border-color: ' . $link->settings->border_color . ';border-style: ' . $link->settings->border_style . ';';

                /* Animation */
                if($link->settings->animation) {
                    $link->design->card_class .= ' animate__animated animate__' . $link->settings->animation_runs . ' animate__' . $link->settings->animation . ' animate__delay-2s';
                }

                /* UTM Parameters */
                $link->utm_query = null;
                if($user->plan_settings->utm && $link->utm->medium && $link->utm->source) {
                    $link->utm_query = '?utm_medium=' . $link->utm->medium . '&utm_source=' . $link->utm->source . '&utm_campaign=' . $link->settings->name;
                }

                $view_path = THEME_PATH . 'views/l/microsite_blocks/' . $link->type . '.php';

                break;

        }

        if(!isset($view_path)) return null;

        /* Prepare the view */
        $data = array_merge($data, [
            'link'      => $link,
            'user'      => $user,
            'microsite'   => $microsite,
        ]);

        return include_view($view_path, $data);

    }
}
