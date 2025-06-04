<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers;

use SeeGap\Alerts;
use SeeGap\Date;
use SeeGap\Models\MicrositesThemes;
use SeeGap\Response;
use Unirest\Request;

defined('SEEGAP') || die();

class MicrositeBlockAjax extends Controller {
    public $microsite_blocks = null;
    public $total_microsite_blocks = 0;

    public function index() {
        \SeeGap\Authentication::guard();

        if(!empty($_POST) && (\SeeGap\Csrf::check('token') || \SeeGap\Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Status toggle */
                case 'is_enabled_toggle': $this->is_enabled_toggle(); break;

                /* Duplicate link */
                case 'duplicate': $this->duplicate(); break;

                /* Order links */
                case 'order': $this->order(); break;

                /* Create */
                case 'create': $this->create(); break;

                /* Update */
                case 'update': $this->update(); break;

                /* Delete */
                case 'delete': $this->delete(); break;

            }

        }

        die();
    }

    private function is_enabled_toggle() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.microsites_blocks')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['microsite_block_id'] = (int) $_POST['microsite_block_id'];

        /* Get the current status */
        $microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks', ['microsite_block_id', 'link_id', 'is_enabled']);

        if($microsite_block) {
            $new_is_enabled = (int) !$microsite_block->is_enabled;

            db()->where('microsite_block_id', $microsite_block->microsite_block_id)->update('microsites_blocks', ['is_enabled' => $new_is_enabled]);

            /* Clear the cache */
            cache()->deleteItem('microsite_blocks?link_id=' . $microsite_block->link_id);

            Response::json('', 'success');
        }
    }

    public function duplicate() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.microsites_blocks')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['microsite_block_id'] = (int) $_POST['microsite_block_id'];

        //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('links');
        }

        /* Get the link data */
        $microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks');

        if(!$microsite_block) {
            redirect('links');
        }

        /* Make sure that the user didn't exceed the limit */
        $this->total_microsite_blocks = database()->query("SELECT COUNT(*) AS `total` FROM `microsites_blocks` WHERE `user_id` = {$this->user->user_id} AND `link_id` = {$microsite_block->link_id}")->fetch_object()->total;
        if($this->user->plan_settings->microsite_blocks_limit != -1 && $this->total_microsite_blocks >= $this->user->plan_settings->microsite_blocks_limit) {
            Alerts::add_error(l('global.info_message.plan_feature_limit'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
            $microsite_block->settings = json_decode($microsite_block->settings ?? '');

            /* Duplication of resources */
            switch($microsite_block->type) {
                case 'file':
                case 'audio':
                case 'video':
                case 'pdf_document':
                case 'powerpoint_presentation':
                case 'excel_spreadsheet':
                    $microsite_block->settings->file = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->file, \SeeGap\Uploads::get_path('files'), \SeeGap\Uploads::get_path('files'), 'json_error');
                    break;

                case 'review':
                    $microsite_block->settings->image = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->image, \SeeGap\Uploads::get_path('block_images'), \SeeGap\Uploads::get_path('block_images'), 'json_error');
                    break;

                case 'avatar':
                    $microsite_block->settings->image = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->image, 'avatars/', 'avatars/', 'json_error');
                    break;

                case 'header':
                    $microsite_block->settings->avatar = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->avatar, 'avatars/', 'avatars/', 'json_error');
                    $microsite_block->settings->background = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->background, 'backgrounds/', 'backgrounds/', 'json_error');
                    break;

                case 'image':
                case 'image_grid':
                    $microsite_block->settings->image = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->image, 'block_images/', 'block_images/', 'json_error');
                    break;

                case 'heading':
                    $microsite_block->settings->verified_location = '';
                    break;

                case 'image_slider':

                    $microsite_block->settings->items = (array) $microsite_block->settings->items;

                    foreach($microsite_block->settings->items as $key => $item) {
                        $microsite_block->settings->items[$key]->image = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->items[$key]->image, 'block_images/', 'block_images/', 'json_error');
                    }

                    break;

                default:
                    $microsite_block->settings->image = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->image, 'block_thumbnail_images/', 'block_thumbnail_images/', 'json_error');
                    break;
            }

            $settings = json_encode($microsite_block->settings ?? '');

            /* Database query */
            db()->insert('microsites_blocks', [
                'user_id' => $this->user->user_id,
                'link_id' => $microsite_block->link_id,
                'type' => $microsite_block->type,
                'location_url' => $microsite_block->location_url,
                'settings' => $settings,
                'order' => $microsite_block->order + 1,
                'start_date' => $microsite_block->start_date,
                'end_date' => $microsite_block->end_date,
                'is_enabled' => $microsite_block->is_enabled,
                'datetime' => get_date(),
            ]);

            /* Clear the cache */
            cache()->deleteItem('microsite_blocks?link_id=' . $microsite_block->link_id);

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.create2'));

            /* Redirect */
            redirect('link/' . $microsite_block->link_id . '?tab=blocks');
        }

        redirect('links');
    }

    private function order() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.microsites_blocks')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        if(isset($_POST['microsite_blocks']) && is_array($_POST['microsite_blocks'])) {
            foreach($_POST['microsite_blocks'] as $link) {
                if(!isset($link['microsite_block_id']) || !isset($link['order'])) {
                    continue;
                }

                $microsite_block = db()->where('microsite_block_id', $link['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks', ['link_id']);

                if(!$microsite_block) {
                    continue;
                }

                $link['microsite_block_id'] = (int) $link['microsite_block_id'];
                $link['order'] = (int) $link['order'];

                /* Update the link order */
                db()->where('microsite_block_id', $link['microsite_block_id'])->where('user_id', $this->user->user_id)->update('microsites_blocks', ['order' => $link['order']]);
            }

            if(isset($microsite_block)) {
                /* Clear the cache */
                cache()->deleteItem('microsite_blocks?link_id=' . $microsite_block->link_id);
            }
        }

        Response::json('', 'success');
    }

    private function create() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.microsites_blocks')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $this->microsite_blocks = require APP_PATH . 'includes/microsite_blocks.php';

        /* Check for available microsite blocks */
        if(isset($_POST['block_type']) && array_key_exists($_POST['block_type'], $this->microsite_blocks)) {
            $_POST['block_type'] = query_clean($_POST['block_type']);
            $_POST['link_id'] = (int) $_POST['link_id'];

            /* Make sure that the user didn't exceed the limit */
            $this->total_microsite_blocks = database()->query("SELECT COUNT(*) AS `total` FROM `microsites_blocks` WHERE `user_id` = {$this->user->user_id} AND `link_id` = {$_POST['link_id']}")->fetch_object()->total;
            if($this->user->plan_settings->microsite_blocks_limit != -1 && $this->total_microsite_blocks >= $this->user->plan_settings->microsite_blocks_limit) {
                Response::json(l('global.info_message.plan_feature_limit'), 'error');
            }

            /* Route to individual block handlers */
            $this->route_to_block_handler($_POST['block_type'], 'create');
        }

        Response::json('', 'success');
    }

    private function update() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.microsites_blocks')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $this->microsite_blocks = require APP_PATH . 'includes/microsite_blocks.php';

        if(!empty($_POST)) {
            /* Check for available microsite blocks */
            if(isset($_POST['block_type']) && array_key_exists($_POST['block_type'], $this->microsite_blocks)) {
                $_POST['block_type'] = query_clean($_POST['block_type']);

                /* Route to individual block handlers */
                $this->route_to_block_handler($_POST['block_type'], 'update');
            }
        }

        die();
    }

    /**
     * Route requests to individual block handlers
     */
    private function route_to_block_handler($block_type, $action) {
        /* Map block types to their handler classes */
        $block_handlers = [
            'link' => 'LinkBlock',
            'heading' => 'HeadingBlock',
            'email_collector' => 'EmailCollectorBlock',
            'big_link' => 'BigLinkBlock',
            'paragraph' => 'ParagraphBlock',
            'image' => 'ImageBlock',
            'divider' => 'DividerBlock',
            'socials' => 'SocialsBlock',
            'audio' => 'AudioBlock',
            'youtube' => 'YoutubeBlock',
            'countdown' => 'CountdownBlock',
            'video' => 'VideoBlock',
            'spotify' => 'SpotifyBlock',
            'paypal' => 'PaypalBlock',
            'list' => 'ListBlock',
            'alert' => 'AlertBlock',
            'faq' => 'FaqBlock',
            'file' => 'FileBlock',
            'vimeo' => 'VimeoBlock',
            'twitch' => 'TwitchBlock',
            'instagram_media' => 'InstagramMediaBlock',
            'phone_collector' => 'PhoneCollectorBlock',
            'contact_collector' => 'ContactCollectorBlock',
            'twitter_tweet' => 'TwitterTweetBlock',
            'tiktok_video' => 'TiktokVideoBlock',
            'product' => 'ProductBlock',
            'map' => 'MapBlock',
            'custom_html' => 'CustomHtmlBlock',
            'iframe' => 'IframeBlock',
            'avatar' => 'AvatarBlock',
            'markdown' => 'MarkdownBlock',
            'calendly' => 'CalendlyBlock',
            'typeform' => 'TypeformBlock',
            'soundcloud' => 'SoundcloudBlock',
            'applemusic' => 'AppleMusicBlock',
            'facebook' => 'FacebookBlock',
            'pdf_document' => 'PdfDocumentBlock',
            'discord' => 'DiscordBlock',
            'telegram' => 'TelegramBlock',
            'reddit' => 'RedditBlock',
            'header' => 'HeaderBlock',
            'image_grid' => 'ImageGridBlock',
            'timeline' => 'TimelineBlock',
            'review' => 'ReviewBlock',
            'cta' => 'CtaBlock',
            'external_item' => 'ExternalItemBlock',
            'share' => 'ShareBlock',
            'coupon' => 'CouponBlock',
            'youtube_feed' => 'YoutubeFeedBlock',
            'feedback_collector' => 'FeedbackCollectorBlock',
            'donation' => 'DonationBlock',
            'service' => 'ServiceBlock',
            'image_slider' => 'ImageSliderBlock',
            'powerpoint_presentation' => 'PowerpointPresentationBlock',
            'excel_spreadsheet' => 'ExcelSpreadsheetBlock',
            'anchor' => 'AnchorBlock',
            'threads' => 'ThreadsBlock',
            'snapchat' => 'SnapchatBlock',
            'tidal' => 'TidalBlock',
            'mixcloud' => 'MixcloudBlock',
            'kick' => 'KickBlock',
            'twitter_video' => 'TwitterVideoBlock',
            'twitter_profile' => 'TwitterProfileBlock',
            'pinterest_profile' => 'PinterestProfileBlock',
        ];

        /* Check if we have a handler for this block type */
        if(!isset($block_handlers[$block_type])) {
            Response::json(l('global.error_message.invalid_request'), 'error');
            return;
        }

        $handler_class = $block_handlers[$block_type];
        $handler_file = APP_PATH . 'controllers/microsite-blocks/blocks/' . $handler_class . '.php';

        /* Check if the handler file exists */
        if(!file_exists($handler_file)) {
            Response::json(l('global.error_message.invalid_request'), 'error');
            return;
        }

        /* Include the BaseBlockHandler and interface first */
        require_once APP_PATH . 'controllers/microsite-blocks/interfaces/BlockHandlerInterface.php';
        require_once APP_PATH . 'controllers/microsite-blocks/BaseBlockHandler.php';
        
        /* Include and instantiate the handler */
        require_once $handler_file;
        $full_class_name = '\\SeeGap\\Controllers\\MicrositeBlocks\\Blocks\\' . $handler_class;
        
        if(!class_exists($full_class_name)) {
            Response::json(l('global.error_message.invalid_request'), 'error');
            return;
        }

        $handler = new $full_class_name();
        
        /* Set required properties from this controller */
        $handler->user = $this->user;
        $handler->total_microsite_blocks = $this->total_microsite_blocks;

        /* Execute the action */
        if(method_exists($handler, $action)) {
            $handler->$action($block_type);
        } else {
            Response::json(l('global.error_message.invalid_request'), 'error');
        }
    }

    private function delete() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.microsites_blocks')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['microsite_block_id'] = (int) $_POST['microsite_block_id'];

        /* Check for possible errors */
        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }

        (new \SeeGap\Models\MicrositeBlock())->delete($microsite_block->microsite_block_id);

        Response::json(l('global.success_message.delete2'), 'success', ['url' => url('link/' . $microsite_block->link_id . '?tab=blocks')]);
    }

    /* Legacy shared methods - kept for backward compatibility and shared functionality */

    public function handle_file_upload($already_existing_file, $file_name, $file_name_remove, $allowed_extensions, $upload_folder, $size_limit) {
        /* File upload */
        $file = (bool) !empty($_FILES[$file_name]['name']) && !isset($_POST[$file_name_remove]);
        $db_file = $already_existing_file;

        if($file) {
            $file_extension = explode('.', $_FILES[$file_name]['name']);
            $file_extension = mb_strtolower(end($file_extension));
            $file_temp = $_FILES[$file_name]['tmp_name'];

            if($_FILES[$file_name]['error'] == UPLOAD_ERR_INI_SIZE) {
                Response::json(sprintf(l('global.error_message.file_size_limit'), $size_limit), 'error');
            }

            if($_FILES[$file_name]['error'] && $_FILES[$file_name]['error'] != UPLOAD_ERR_INI_SIZE) {
                Response::json(l('global.error_message.file_upload'), 'error');
            }

            if(!is_writable(UPLOADS_PATH . $upload_folder)) {
                Response::json(sprintf(l('global.error_message.directory_not_writable'), UPLOADS_PATH . $upload_folder), 'error');
            }

            if(!in_array($file_extension, $allowed_extensions)) {
                Response::json(l('global.error_message.invalid_file_type'), 'error');
            }

            if($_FILES[$file_name]['size'] > $size_limit * 1000000) {
                Response::json(sprintf(l('global.error_message.file_size_limit'), $size_limit), 'error');
            }

            /* Generate new name for the file */
            $file_new_name = md5(time() . rand()) . '.' . $file_extension;

            /* Try to compress the image */
            if(\SeeGap\Plugin::is_active('image-optimizer')) {
                \SeeGap\Plugin\ImageOptimizer::optimize($file_temp, $file_new_name);
            }

            /* Sanitize SVG uploads */
            if($file_extension == 'svg') {
                $svg_sanitizer = new \enshrined\svgSanitize\Sanitizer();
                $dirty_svg = file_get_contents($file_temp);
                $clean_svg = $svg_sanitizer->sanitize($dirty_svg);
                file_put_contents($file_temp, $clean_svg);
            }

            /* Offload uploading */
            if(\SeeGap\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                try {
                    $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                    /* Delete current image */
                    if(!empty($already_existing_file)) {
                        $s3->deleteObject([
                            'Bucket' => settings()->offload->storage_name,
                            'Key' => UPLOADS_URL_PATH . $upload_folder . $already_existing_file,
                        ]);
                    }

                    /* Upload image */
                    $result = $s3->putObject([
                        'Bucket' => settings()->offload->storage_name,
                        'Key' => UPLOADS_URL_PATH . $upload_folder . $file_new_name,
                        'ContentType' => mime_content_type($file_temp),
                        'SourceFile' => $file_temp,
                        'ACL' => 'public-read'
                    ]);
                } catch (\Exception $exception) {
                    Response::json($exception->getMessage(), 'error');
                }
            }

            /* Local uploading */
            else {
                /* Delete current file */
                if(!empty($already_existing_file) && file_exists(UPLOADS_PATH . $upload_folder . $already_existing_file)) {
                    unlink(UPLOADS_PATH . $upload_folder . $already_existing_file);
                }

                /* Upload the original */
                move_uploaded_file($file_temp, UPLOADS_PATH . $upload_folder . $file_new_name);
            }

            $db_file = $file_new_name;
        }

        /* Check for the removal of the already uploaded file */
        if(isset($_POST['image_remove'])) {
            /* Offload deleting */
            if(\SeeGap\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                $s3 = new \Aws\S3\S3Client(get_aws_s3_config());
                $s3->deleteObject([
                    'Bucket' => settings()->offload->storage_name,
                    'Key' => UPLOADS_URL_PATH . $upload_folder . $already_existing_file,
                ]);
            }

            /* Local deleting */
            else {
                /* Delete current file */
                if(!empty($db_file) && file_exists(UPLOADS_PATH . $upload_folder . $db_file)) {
                    unlink(UPLOADS_PATH . $upload_folder . $db_file);
                }
            }
            $db_file = null;
        }

        return $db_file;
    }

    private function handle_image_upload($uploaded_image, $upload_folder, $size_limit) {
        return $this->handle_file_upload($uploaded_image, 'image', 'image_remove', ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'], $upload_folder, $size_limit);
    }

    /* Function to bundle together all the checks of an url */
    private function check_location_url($url, $can_be_empty = false) {

        if(empty(trim($url ?? '')) && $can_be_empty) {
            return;
        }

        if(empty(trim($url))) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        $url_details = parse_url($url);

        if(!isset($url_details['scheme'])) {
            Response::json(l('link.error_message.invalid_location_url'), 'error');
        }

        if(!$this->user->plan_settings->deep_links && !in_array($url_details['scheme'], ['http', 'https'])) {
            Response::json(l('link.error_message.invalid_location_url'), 'error');
        }

        /* Make sure the domain is not blacklisted */
        $domain = get_domain_from_url($url);

        if($domain && in_array($domain, settings()->links->blacklisted_domains)) {
            Response::json(l('link.error_message.blacklisted_domain'), 'error');
        }

        /* Check the url with google safe browsing to make sure it is a safe website */
        if(settings()->links->google_safe_browsing_is_enabled) {
            if(google_safe_browsing_check($url, settings()->links->google_safe_browsing_api_key)) {
                Response::json(l('link.error_message.blacklisted_location_url'), 'error');
            }
        }
    }

    private function process_display_settings() {
        $_POST['schedule'] = (int) isset($_POST['schedule']);
        if($_POST['schedule'] && !empty($_POST['start_date']) && !empty($_POST['end_date']) && Date::validate($_POST['start_date'], 'Y-m-d H:i:s') && Date::validate($_POST['end_date'], 'Y-m-d H:i:s')) {
            $_POST['start_date'] = (new \DateTime($_POST['start_date'], new \DateTimeZone($this->user->timezone)))->setTimezone(new \DateTimeZone(\SeeGap\Date::$default_timezone))->format('Y-m-d H:i:s');
            $_POST['end_date'] = (new \DateTime($_POST['end_date'], new \DateTimeZone($this->user->timezone)))->setTimezone(new \DateTimeZone(\SeeGap\Date::$default_timezone))->format('Y-m-d H:i:s');
        } else {
            $_POST['start_date'] = $_POST['end_date'] = null;
        }

        $_POST['display_continents'] = array_filter($_POST['display_continents'] ?? [], function($country) {
            return array_key_exists($country, get_continents_array());
        });

        $_POST['display_countries'] = array_filter($_POST['display_countries'] ?? [], function($country) {
            return array_key_exists($country, get_countries_array());
        });

        $_POST['display_cities'] = explode(',', $_POST['display_cities']);
        if(count($_POST['display_cities'])) {
            $_POST['display_cities'] = array_map(function($city) {
                return query_clean($city);
            }, $_POST['display_cities']);

            $_POST['display_cities'] = array_filter($_POST['display_cities'], function($city) {
                return $city !== '';
            });

            $_POST['display_cities'] = array_unique($_POST['display_cities']);
        }

        $_POST['display_devices'] = array_filter($_POST['display_devices'] ?? [], function($device_type) {
            return in_array($device_type, ['desktop', 'tablet', 'mobile']);
        });

        $_POST['display_languages'] = array_filter($_POST['display_languages'] ?? [], function($locale) {
            return array_key_exists($locale, get_locale_languages_array());
        });

        $_POST['display_operating_systems'] = array_filter($_POST['display_operating_systems'] ?? [], function($os_name) {
            return in_array($os_name, ['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS']);
        });

        $_POST['display_browsers'] = array_filter($_POST['display_browsers'] ?? [], function($browser_name) {
            return in_array($browser_name, ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet']);
        });
    }

    private function process_microsite_theme_id_settings($link, $settings, $type) {
        /* Make sure the block is themable */
        $themable_blocks = [
            'pdf_document',
            'socials',
            'powerpoint_presentation',
            'excel_spreadsheet',
            'review',
            'big_link',
            'link',
            'email_collector',
            'paypal',
            'phone_collector',
            'contact_collector',
            'feedback_collector',
            'cta',
            'youtube_feed',
            'share',
            'coupon',
            'file',
            'product',
            'donation',
            'service',
            'paragraph',
            'markdown'
        ];

        if(!in_array($type, $themable_blocks)) {
            return $settings;
        }

        if(!$link->microsite_theme_id) {
            return $settings;
        }

        /* Get available themes */
        $microsites_themes = (new MicrositesThemes())->get_microsites_themes();

        /* Check if we need to override defaults for a new theme */
        $microsite_theme = $microsites_themes[$link->microsite_theme_id] ?? null;

        if(!$microsite_theme) {
            return $settings;
        }

        $settings = json_decode($settings);

        /* Save new themed settings */
        switch($type) {
            case 'socials':
                $new_settings = json_encode(array_merge((array) $settings, (array) $microsite_theme->settings->microsite_block_socials ?? []));
                break;

            case 'heading':
                $new_settings = json_encode(array_merge((array) $settings, (array) $microsite_theme->settings->microsite_block_heading ?? []));
                break;

            case 'paragraph':
                $new_settings = json_encode(array_merge((array) $settings, (array) $microsite_theme->settings->microsite_block_paragraph ?? []));
                break;

            default:
                $new_settings = json_encode(array_merge((array) $settings, (array) $microsite_theme->settings->microsite_block ?? []));
                break;
        }

        return $new_settings;
    }

}
