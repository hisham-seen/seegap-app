<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers\MicrositeBlocks;

use SeeGap\Response;
use SeeGap\Date;
use SeeGap\Models\MicrositesThemes;

defined('SEEGAP') || die();

/**
 * Base Block Handler
 * 
 * Provides shared functionality for all microsite block handlers.
 */
abstract class BaseBlockHandler implements Interfaces\BlockHandlerInterface {
    
    public $user;
    public $total_microsite_blocks = 0;
    
    /**
     * Process display settings from POST data
     */
    protected function process_display_settings() {
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

    /**
     * Process microsite theme settings
     */
    protected function process_microsite_theme_id_settings($link, $settings, $type) {
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

    /**
     * Handle file uploads with comprehensive validation and processing
     */
    protected function handle_file_upload($already_existing_file, $file_name, $file_name_remove, $allowed_extensions, $upload_folder, $size_limit) {
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
        if(isset($_POST[$file_name_remove])) {
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

    /**
     * Handle image uploads specifically
     */
    protected function handle_image_upload($uploaded_image, $upload_folder, $size_limit) {
        return $this->handle_file_upload($uploaded_image, 'image', 'image_remove', ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'], $upload_folder, $size_limit);
    }

    /**
     * Validate and check location URLs
     */
    protected function check_location_url($url, $can_be_empty = false) {
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

    /**
     * Validate required fields
     */
    protected function validate_required_fields($required_fields) {
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
                break 1;
            }
        }
    }

    /**
     * Get common display settings array
     */
    protected function get_display_settings() {
        return [
            'display_continents' => $_POST['display_continents'],
            'display_countries' => $_POST['display_countries'],
            'display_cities' => $_POST['display_cities'],
            'display_devices' => $_POST['display_devices'],
            'display_languages' => $_POST['display_languages'],
            'display_operating_systems' => $_POST['display_operating_systems'],
            'display_browsers' => $_POST['display_browsers'],
        ];
    }

    /**
     * Get empty display settings for new blocks
     */
    protected function get_empty_display_settings() {
        return [
            'display_continents' => [],
            'display_countries' => [],
            'display_cities' => [],
            'display_devices' => [],
            'display_languages' => [],
            'display_operating_systems' => [],
            'display_browsers' => [],
        ];
    }
}
