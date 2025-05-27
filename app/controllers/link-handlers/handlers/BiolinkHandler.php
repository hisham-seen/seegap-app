<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Controllers\LinkHandlers\Handlers;

use Altum\Controllers\LinkHandlers\BaseLinkHandler;
use Altum\Response;
use Altum\Models\BiolinksThemes;

defined('ALTUMCODE') || die();

/**
 * Biolink Handler
 * 
 * Handles the creation and updating of biolink page links.
 */
class BiolinkHandler extends BaseLinkHandler {
    
    public function getSupportedTypes() {
        return ['biolink'];
    }
    
    public function create($type) {
        if(!settings()->links->biolinks_is_enabled) {
            Response::json(l('global.error_message.basic'), 'error');
        }

        $_POST['url'] = !empty($_POST['url']) && $this->user->plan_settings->custom_url ? get_slug($_POST['url'], '-', false) : null;
        $_POST['biolink_template_id'] = isset($_POST['biolink_template_id']) && in_array($_POST['biolink_template_id'], $this->user->plan_settings->biolinks_templates ?? []) ? (int) $_POST['biolink_template_id'] : null;

        /* Check for a default template id */
        if(!$_POST['biolink_template_id'] && settings()->links->default_biolink_template_id) {
            $_POST['biolink_template_id'] = settings()->links->default_biolink_template_id;
        }

        $this->process_common_post_data();

        /* Check if custom domain is set */
        $domain_id = $this->get_domain_id($_POST['domain_id'] ?? false);

        /* Make sure that the user didn't exceed the limit */
        $user_total_biolinks = database()->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `user_id` = {$this->user->user_id} AND `type` = 'biolink'")->fetch_object()->total;
        if($this->user->plan_settings->biolinks_limit != -1 && $user_total_biolinks >= $this->user->plan_settings->biolinks_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Check for duplicate url if needed */
        $this->check_duplicate_url($_POST['url'], $domain_id);

        /* Start the creation process */
        $url = $_POST['url'] ? $_POST['url'] : $this->generate_random_url($domain_id);
        $type = 'biolink';
        $settings = [
            'pwa_file_name' => null,
            'pwa_is_enabled' => false,
            'pwa_display_install_bar' => false,
            'pwa_display_install_bar_delay' => 3,
            'pwa_theme_color' => '#000000',
            'pwa_icon' => null,

            'verified_location' => 'top',
            'favicon' => null,
            'background_type' => 'preset',
            'background' => 'zero',
            'background_attachment' => 'scroll',
            'background_blur' => 0,
            'background_brightness' => 100,
            'text_color' => '#ffffff',
            'display_branding' => true,
            'branding' => [
                'url' => '',
                'name' => ''
            ],
            'seo' => [
                'block' => false,
                'title' => '',
                'meta_description' => '',
                'meta_keywords' => '',
                'image' => '',
            ],
            'utm' => [
                'medium' => '',
                'source' => '',
            ],
            'font' => 'default',
            'font_size' => 16,
            'width' => 8,
            'block_spacing' => 2,
            'hover_animation' => 'smooth',
            'password' => null,
            'sensitive_content' => false,
            'leap_link' => null,
            'custom_css' => null,
            'custom_js' => null,
            'share_is_enabled' => true,
            'scroll_buttons_is_enabled' => true,
        ];

        $this->check_url($_POST['url']);

        $additional = null;
        $biolink_theme_id = null;

        /* Check for biolink templates */
        if($_POST['biolink_template_id']) {
            $biolinks_templates = (new \Altum\Models\BiolinksTemplates())->get_biolinks_templates();

            if(array_key_exists($_POST['biolink_template_id'], $biolinks_templates)) {
                $biolink_template = $biolinks_templates[$_POST['biolink_template_id']];

                /* Get the details of the biolink page */
                $biolink = db()->where('link_id', $biolink_template->link_id)->getOne('links');

                if($biolink) {
                    /* Get all the biolink blocks as well */
                    $biolink->settings = json_decode($biolink->settings ?? '');
                    $biolink->settings->seo->image = \Altum\Uploads::copy_uploaded_file($biolink->settings->seo->image, 'block_images/', 'block_images/', 'json_error');
                    $biolink->settings->favicon = \Altum\Uploads::copy_uploaded_file($biolink->settings->favicon, 'favicons/', 'favicons/', 'json_error');
                    if($biolink->settings->background_type == 'image') $biolink->settings->background = \Altum\Uploads::copy_uploaded_file($biolink->settings->background, 'backgrounds/', 'backgrounds/', 'json_error');
                    $biolink->settings->pwa_is_enabled = false;
                    $biolink->settings->pwa_icon = null;
                    $additional = $biolink->additional;
                    $biolink_theme_id = $biolink->biolink_theme_id;

                    /* Overwrite default settings with the settings of the template */
                    $settings = $biolink->settings;

                    /* Database query */
                    db()->where('biolink_template_id', $biolink_template->biolink_template_id)->update('biolinks_templates', [
                        'total_usage' => db()->inc()
                    ]);
                }
            }
        }

        /* Check for a default theme id */
        if(!$_POST['biolink_template_id'] && settings()->links->default_biolink_theme_id) {
            $biolink_theme_id = settings()->links->default_biolink_theme_id;

            /* Get available themes */
            $biolinks_themes = (new BiolinksThemes())->get_biolinks_themes();
            $biolink_theme_id = isset($biolink_theme_id) && array_key_exists($biolink_theme_id, $biolinks_themes) ? $biolink_theme_id : null;

            if($biolink_theme_id) {
                $biolink_theme = $biolinks_themes[$biolink_theme_id];

                /* Save settings for biolink page */
                $settings = array_merge($settings, (array) $biolink_theme->settings->biolink);

                /* Save the additional settings */
                $additional = json_encode($biolink_theme->settings->additional);
            }
        }

        $settings = json_encode($settings);

        /* Insert to database */
        $link_id = db()->insert('links', [
            'user_id' => $this->user->user_id,
            'domain_id' => $domain_id,
            'biolink_theme_id' => $biolink_theme_id ?? null,
            'type' => $type,
            'url' => $url,
            'settings' => $settings,
            'additional' => $additional,
            'datetime' => get_date(),
        ]);

        /* Check for a template usage */
        if(isset($biolink_template)) {
            /* Get all biolink blocks if needed */
            $biolink_blocks = db()->where('link_id', $biolink_template->link_id)->get('biolinks_blocks');

            foreach($biolink_blocks as $biolink_block) {
                $biolink_block->settings = json_decode($biolink_block->settings ?? '');

                if(is_array($biolink_block->settings)) {
                    $biolink_block->settings = (object) $biolink_block->settings;
                }

                /* Duplication of resources */
                switch($biolink_block->type) {
                    case 'file':
                    case 'audio':
                    case 'video':
                    case 'pdf_document':
                    case 'powerpoint_presentation':
                    case 'excel_spreadsheet':
                        $biolink_block->settings->file = \Altum\Uploads::copy_uploaded_file($biolink_block->settings->file, \Altum\Uploads::get_path('files'), \Altum\Uploads::get_path('files'), 'json_error');
                        break;

                    case 'review':
                        $biolink_block->settings->image = \Altum\Uploads::copy_uploaded_file($biolink_block->settings->image, \Altum\Uploads::get_path('block_images'), \Altum\Uploads::get_path('block_images'), 'json_error');
                        break;

                    case 'avatar':
                        $biolink_block->settings->image = \Altum\Uploads::copy_uploaded_file($biolink_block->settings->image, 'avatars/', 'avatars/', 'json_error');
                        break;

                    case 'header':
                        $biolink_block->settings->avatar = \Altum\Uploads::copy_uploaded_file($biolink_block->settings->avatar, 'avatars/', 'avatars/', 'json_error');
                        $biolink_block->settings->background = \Altum\Uploads::copy_uploaded_file($biolink_block->settings->background, 'backgrounds/', 'backgrounds/', 'json_error');
                        break;

                    case 'image':
                    case 'image_grid':
                        $biolink_block->settings->image = \Altum\Uploads::copy_uploaded_file($biolink_block->settings->image, 'block_images/', 'block_images/', 'json_error');
                        break;

                    case 'heading':
                        $biolink_block->settings->verified_location = '';
                        break;

                    case 'image_slider':
                        $biolink_block->settings->items = (array) $biolink_block->settings->items;

                        foreach($biolink_block->settings->items as $key => $item) {
                            $biolink_block->settings->items[$key]->image = \Altum\Uploads::copy_uploaded_file($biolink_block->settings->items[$key]->image, 'block_images/', 'block_images/', 'json_error');
                        }
                        break;

                    default:
                        $biolink_block->settings->image = \Altum\Uploads::copy_uploaded_file($biolink_block->settings->image, 'block_thumbnail_images/', 'block_thumbnail_images/', 'json_error');
                        break;
                }

                /* Database query */
                db()->insert('biolinks_blocks', [
                    'user_id' => $this->user->user_id,
                    'link_id' => $link_id,
                    'type' => $biolink_block->type,
                    'location_url' => $biolink_block->location_url,
                    'settings' => json_encode($biolink_block->settings),
                    'order' => $biolink_block->order,
                    'start_date' => $biolink_block->start_date,
                    'end_date' => $biolink_block->end_date,
                    'is_enabled' => $biolink_block->is_enabled,
                    'datetime' => get_date(),
                ]);
            }
        }

        /* Clear the cache */
        $this->clear_link_cache($link_id, $type, $this->user->user_id);

        Response::json(l('global.success_message.create2'), 'success', ['url' => url('link/' . $link_id)]);
    }
    
    public function update($type) {
        if(!settings()->links->biolinks_is_enabled) {
            Response::json(l('global.error_message.basic'), 'error');
        }

        $this->process_common_post_data();

        /* Get domains */
        $domains = $this->get_available_domains();

        /* Check if custom domain is set */
        $domain_id = isset($domains[$_POST['domain_id']]) ? $_POST['domain_id'] : 0;

        /* Exclusivity check */
        $_POST['is_main_link'] = isset($_POST['is_main_link']) && $domain_id && $domains[$_POST['domain_id']]->type == 0;

        /* Check for any errors */
        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $this->process_projects_and_splash_pages();

        $link->settings = json_decode($link->settings ?? '');

        /* Get available themes */
        $biolinks_themes = (new BiolinksThemes())->get_biolinks_themes();
        $_POST['biolink_theme_id'] = isset($_POST['biolink_theme_id']) && array_key_exists($_POST['biolink_theme_id'], $biolinks_themes) ? (int) $_POST['biolink_theme_id'] : null;

        /* Make sure theme is accessible via plan */
        $_POST['biolink_theme_id'] = $_POST['biolink_theme_id'] && in_array($_POST['biolink_theme_id'], $this->user->plan_settings->biolinks_themes ?? []) ? $_POST['biolink_theme_id'] : null;

        /* Existing pixels */
        $this->process_pixels_data();

        if($_POST['url'] == $link->url) {
            $url = $link->url;

            if($link->domain_id != $domain_id) {
                $this->check_duplicate_url($_POST['url'], $domain_id, $link->link_id);
            }
        } else {
            $url = $_POST['url'] ? $_POST['url'] : $this->generate_random_url($domain_id);

            $this->check_duplicate_url($_POST['url'], $domain_id, $link->link_id);

            $this->check_url($_POST['url']);
        }

        /* Image uploads */
        $image_allowed_extensions = [
            'pwa_icon' => \Altum\Uploads::get_whitelisted_file_extensions('app_icon'),
            'seo_image' => \Altum\Uploads::get_whitelisted_file_extensions('biolink_seo_image'),
            'favicon' => \Altum\Uploads::get_whitelisted_file_extensions('favicons'),
            'background' => \Altum\Uploads::get_whitelisted_file_extensions('biolink_background'),
        ];
        $image = [
            'pwa_icon' => !empty($_FILES['pwa_icon']['name']) && !isset($_POST['pwa_icon_remove']),
            'seo_image' => !empty($_FILES['seo_image']['name']) && !isset($_POST['seo_image_remove']),
            'favicon' => !empty($_FILES['favicon']['name']) && !isset($_POST['favicon_remove']),
            'background' => !empty($_FILES['background']['name']) && !isset($_POST['background_remove']),
        ];
        $image_upload_path = [
            'pwa_icon' => \Altum\Uploads::get_path('app_icon'),
            'seo_image' => \Altum\Uploads::get_path('biolink_seo_image'),
            'favicon' => \Altum\Uploads::get_path('favicons'),
            'background' => \Altum\Uploads::get_path('biolink_background'),
        ];
        $image_uploaded_file = [
            'pwa_icon' => $link->settings->pwa_icon,
            'seo_image' => $link->settings->seo->image,
            'favicon' => $link->settings->favicon,
        ];
        $image_url = [
            'pwa_icon' => null,
            'seo_image' => null,
            'favicon' => null,
            'background' => null,
        ];

        foreach(['favicon', 'seo_image', 'pwa_icon'] as $image_key) {
            if($image[$image_key]) {
                $file_name = $_FILES[$image_key]['name'];
                $file_extension = explode('.', $file_name);
                $file_extension = mb_strtolower(end($file_extension));
                $file_temp = $_FILES[$image_key]['tmp_name'];

                if($_FILES[$image_key]['error'] == UPLOAD_ERR_INI_SIZE) {
                    Response::json(sprintf(l('global.error_message.file_size_limit'), settings()->links->{$image_key . '_size_limit'}), 'error');
                }

                if($_FILES[$image_key]['error'] && $_FILES[$image_key]['error'] != UPLOAD_ERR_INI_SIZE) {
                    Response::json(l('global.error_message.file_upload'), 'error');
                }

                if(!in_array($file_extension, $image_allowed_extensions[$image_key])) {
                    Response::json(l('global.error_message.invalid_file_type'), 'error');
                }

                if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                    if(!is_writable(UPLOADS_PATH . $image_upload_path[$image_key])) {
                        Response::json(sprintf(l('global.error_message.directory_not_writable'), UPLOADS_PATH . $image_upload_path[$image_key]), 'error');
                    }
                }

                if($_FILES[$image_key]['size'] > settings()->links->{$image_key . '_size_limit'} * 1000000) {
                    Response::json(sprintf(l('global.error_message.file_size_limit'), settings()->links->{$image_key . '_size_limit'}), 'error');
                }

                /* Generate new name for image */
                $image_new_name = md5(time() . rand()) . '.' . $file_extension;

                /* Try to compress the image */
                if(\Altum\Plugin::is_active('image-optimizer')) {
                    \Altum\Plugin\ImageOptimizer::optimize($file_temp, $image_new_name);
                }

                /* Offload uploading */
                if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                    try {
                        $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                        /* Delete current image */
                        $s3->deleteObject([
                            'Bucket' => settings()->offload->storage_name,
                            'Key' => 'uploads/' . $image_upload_path[$image_key] . $image_uploaded_file[$image_key],
                        ]);

                        /* Upload image */
                        $result = $s3->putObject([
                            'Bucket' => settings()->offload->storage_name,
                            'Key' => 'uploads/' . $image_upload_path[$image_key] . $image_new_name,
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
                    /* Delete current image */
                    if(!empty($image_uploaded_file[$image_key]) && file_exists(UPLOADS_PATH . $image_upload_path[$image_key] . $image_uploaded_file[$image_key])) {
                        unlink(UPLOADS_PATH . $image_upload_path[$image_key] . $image_uploaded_file[$image_key]);
                    }

                    /* Upload the original */
                    move_uploaded_file($file_temp, UPLOADS_PATH . $image_upload_path[$image_key] . $image_new_name);
                }

                $image_uploaded_file[$image_key] = $image_new_name;
            }

            /* Check for the removal of the already uploaded file */
            if(isset($_POST[$image_key . '_remove'])) {

                /* Offload deleting */
                if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                    $s3 = new \Aws\S3\S3Client(get_aws_s3_config());
                    $s3->deleteObject([
                        'Bucket' => settings()->offload->storage_name,
                        'Key' => 'uploads/' . $image_upload_path[$image_key] . $image_uploaded_file[$image_key],
                    ]);
                }

                /* Local deleting */
                else {
                    /* Delete current file */
                    if(!empty($image_uploaded_file[$image_key]) && file_exists(UPLOADS_PATH . $image_upload_path[$image_key] . $image_uploaded_file[$image_key])) {
                        unlink(UPLOADS_PATH . $image_upload_path[$image_key] . $image_uploaded_file[$image_key]);
                    }
                }

                $image_uploaded_file[$image_key] = null;
            }

            $image_url[$image_key] = $image_uploaded_file[$image_key] ? UPLOADS_FULL_URL . $image_upload_path[$image_key] . $image_uploaded_file[$image_key] : null;
        }

        $biolink_backgrounds = require APP_PATH . 'includes/biolink_backgrounds.php';
        $_POST['background_type'] = array_key_exists($_POST['background_type'], $biolink_backgrounds) ? $_POST['background_type'] : 'preset';
        $_POST['background_attachment'] = isset($_POST['background_attachment']) && in_array($_POST['background_attachment'], ['scroll', 'fixed']) ? $_POST['background_attachment'] : 'scroll';
        $_POST['background_blur'] = isset($_POST['background_blur']) && in_array((int) $_POST['background_attachment'], range(0, 30)) ? (int) $_POST['background_blur'] : 0;
        $_POST['background_brightness'] = isset($_POST['background_brightness']) && in_array((int) $_POST['background_attachment'], range(0, 150)) ? (int) $_POST['background_brightness'] : 0;

        switch($_POST['background_type']) {
            case 'preset':
            case 'preset_abstract':
                $background = array_key_exists($_POST['background'], $biolink_backgrounds[$_POST['background_type']]) ? $_POST['background'] : 'zero';
                break;

            case 'color':
                $background = !verify_hex_color($_POST['background']) ? '#000000' : $_POST['background'];
                break;

            case 'gradient':
                $background_color_one = !verify_hex_color($_POST['background_color_one']) ? '#000000' : $_POST['background_color_one'];
                $background_color_two = !verify_hex_color($_POST['background_color_two']) ? '#000000' : $_POST['background_color_two'];
                break;

            case 'image':
                /* Background processing */
                if($image['background']) {
                    $background_file_extension = explode('.', $_FILES['background']['name']);
                    $background_file_extension = mb_strtolower(end($background_file_extension));
                    $background_file_temp = $_FILES['background']['tmp_name'];

                    if($_FILES['background']['error'] == UPLOAD_ERR_INI_SIZE) {
                        Response::json(sprintf(l('global.error_message.file_size_limit'), settings()->links->background_size_limit), 'error');
                    }

                    if($_FILES['background']['error'] && $_FILES['background']['error'] != UPLOAD_ERR_INI_SIZE) {
                        Response::json(l('global.error_message.file_upload'), 'error');
                    }

                    if(!is_writable(UPLOADS_PATH . $image_upload_path['background'])) {
                        Response::json(sprintf(l('global.error_message.directory_not_writable'), UPLOADS_PATH . $image_upload_path['background']), 'error');
                    }

                    if(!in_array($background_file_extension, $image_allowed_extensions['background'])) {
                        Response::json(l('global.error_message.invalid_file_type'), 'error');
                    }

                    if($_FILES['background']['size'] > settings()->links->background_size_limit * 1000000) {
                        Response::json(sprintf(l('global.error_message.file_size_limit'), settings()->links->background_size_limit), 'error');
                    }

                    /* Generate new name */
                    $background_new_name = md5(time() . rand()) . '.' . $background_file_extension;

                    /* Try to compress the image */
                    if(\Altum\Plugin::is_active('image-optimizer')) {
                        \Altum\Plugin\ImageOptimizer::optimize($background_file_temp, $background_new_name);
                    }

                    /* Offload uploading */
                    if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                        try {
                            $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                            /* Delete current image */
                            if(!$link->biolink_theme_id && is_string($link->settings->background)) {
                                $s3->deleteObject([
                                    'Bucket' => settings()->offload->storage_name,
                                    'Key' => 'uploads/backgrounds/' . $link->settings->background,
                                ]);
                            }

                            /* Upload image */
                            $result = $s3->putObject([
                                'Bucket' => settings()->offload->storage_name,
                                'Key' => 'uploads/backgrounds/' . $background_new_name,
                                'ContentType' => mime_content_type($background_file_temp),
                                'SourceFile' => $background_file_temp,
                                'ACL' => 'public-read'
                            ]);
                        } catch (\Exception $exception) {
                            Response::json($exception->getMessage(), 'error');
                        }
                    }

                    /* Local uploading */
                    else {
                        /* Delete current file */
                        if(!$link->biolink_theme_id && is_string($link->settings->background) && !empty($link->settings->background) && file_exists(UPLOADS_PATH . $image_upload_path['background'] . $link->settings->background)) {
                            unlink(UPLOADS_PATH . $image_upload_path['background'] . $link->settings->background);
                        }

                        /* Upload the original */
                        move_uploaded_file($background_file_temp, UPLOADS_PATH . $image_upload_path['background'] . $background_new_name);
                    }

                    $background = $background_new_name;
                }
                break;
        }

        /* Delete existing background file if needed */
        if(
            $link->settings->background_type == 'image'
            && ($image['background'] || ($_POST['biolink_theme_id'] && $link->biolink_theme_id != $_POST['biolink_theme_id']) || $_POST['background_type'] != $link->settings->background_type)
            && is_string($link->settings->background)
            && (!$link->biolink_theme_id)
        )
        {
            /* Offload deleting */
            if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                $s3 = new \Aws\S3\S3Client(get_aws_s3_config());
                $s3->deleteObject([
                    'Bucket' => settings()->offload->storage_name,
                    'Key' => 'uploads/backgrounds/' . $link->settings->background,
                ]);
            }

            /* Local deleting */
            else {
                /* Delete current file */
                if(!empty($link->settings->background) && file_exists(UPLOADS_PATH . $image_upload_path['background'] . $link->settings->background)) {
                    unlink(UPLOADS_PATH . $image_upload_path['background'] . $link->settings->background);
                }
            }
        }

        $_POST['text_color'] = !verify_hex_color($_POST['text_color']) ? '#ffffff' : $_POST['text_color'];
        $_POST['display_branding'] = (int) isset($_POST['display_branding']);
        $_POST['verified_location'] = in_array($_POST['verified_location'], ['', 'top', 'bottom']) ? query_clean($_POST['verified_location']) : 'top';
        $_POST['branding_name'] = mb_substr(trim(query_clean($_POST['branding_name'])), 0, 128);
        $_POST['branding_url'] = get_url($_POST['branding_url']);
        $_POST['seo_block'] = (int) isset($_POST['seo_block']);
        $_POST['seo_title'] = trim(query_clean(mb_substr($_POST['seo_title'], 0, 70)));
        $_POST['seo_meta_description'] = trim(query_clean(mb_substr($_POST['seo_meta_description'], 0, 160)));
        $_POST['seo_meta_keywords'] = trim(query_clean(mb_substr($_POST['seo_meta_keywords'], 0, 160)));
        $_POST['utm_medium'] = input_clean($_POST['utm_medium'], 128);
        $_POST['utm_source'] = input_clean($_POST['utm_source'], 128);
        $_POST['password'] = $this->process_password($link->settings->password);
        $_POST['sensitive_content'] = (int) isset($_POST['sensitive_content']);
        $_POST['custom_css'] = mb_substr(trim($_POST['custom_css']), 0, 10000);
        $_POST['custom_js'] = mb_substr(trim($_POST['custom_js']), 0, 10000);
        $_POST['leap_link'] = get_url($_POST['leap_link'] ?? null);
        $_POST['share_is_enabled'] = (int) isset($_POST['share_is_enabled']);
        $_POST['scroll_buttons_is_enabled'] = (int) isset($_POST['scroll_buttons_is_enabled']);
        $_POST['directory_is_enabled'] = (int) isset($_POST['directory_is_enabled']);
        $this->check_location_url($_POST['leap_link'], true);

        /* Make sure the font is ok */
        $biolink_fonts = require APP_PATH . 'includes/biolink_fonts.php';
        $_POST['font'] = !array_key_exists($_POST['font'], $biolink_fonts) ? false : query_clean($_POST['font']);
        $_POST['font_size'] = (int) $_POST['font_size'] < 14 || (int) $_POST['font_size'] > 22 ? 16 : (int) $_POST['font_size'];

        /* Width */
        $_POST['width'] = isset($_POST['width']) && in_array($_POST['width'], [6, 8, 10, 12]) ? (int) $_POST['width'] : 8;

        /* Block spacing */
        $_POST['block_spacing'] = isset($_POST['block_spacing']) && in_array($_POST['block_spacing'], [1, 2, 3,]) ? (int) $_POST['block_spacing'] : 2;

        /* Link hover animation */
        $_POST['hover_animation'] = isset($_POST['hover_animation']) && in_array($_POST['hover_animation'], ['false', 'smooth', 'instant',]) ? input_clean($_POST['hover_animation']) : 'smooth';

        /* PWA generation */
        $_POST['pwa_is_enabled'] = (int) isset($_POST['pwa_is_enabled']);
        $_POST['pwa_display_install_bar'] = (int) isset($_POST['pwa_display_install_bar']);
        $_POST['pwa_display_install_bar_delay'] = max(1, (int) $_POST['pwa_display_install_bar_delay'] ?? 3);
        $_POST['pwa_theme_color'] = isset($_POST['pwa_theme_color']) && verify_hex_color($_POST['pwa_theme_color']) ? $_POST['pwa_theme_color'] : '#000000';

        if(\Altum\Plugin::is_active('pwa') && settings()->pwa->is_enabled && $this->user->plan_settings->custom_pwa_is_enabled && $_POST['pwa_is_enabled']) {
            $pwa_file_name = $link->settings->pwa_file_name ?? 'biolinks-' . md5(time() . rand() . rand());

            $full_url = $domain_id ? $domains[$_POST['domain_id']]->scheme . $domains[$_POST['domain_id']]->host . '/' . ($_POST['is_main_link'] ? null : $_POST['url']) : SITE_URL . $_POST['url'];

            /* Add UTM tracking params */
            $full_url = $full_url . '?' . http_build_query([
                'utm_source' => 'pwa',
                'utm_medium' => 'web-app',
                'utm_campaign' => 'install-or-pwa-launch',
            ]);

            /* Generate the manifest file */
            $manifest = pwa_generate_manifest([
                'name' => $_POST['seo_title'] ?: $_POST['url'] . ' - ' . settings()->main->title,
                'short_name' => $_POST['url'],
                'description' => $_POST['seo_meta_description'] ?: $_POST['url'],
                'theme_color' => $_POST['pwa_theme_color'],
                'app_icon_url' => $image_uploaded_file['pwa_icon'] ? \Altum\Uploads::get_full_url('app_icon') . $image_uploaded_file['pwa_icon'] : (settings()->pwa->app_icon ? \Altum\Uploads::get_full_url('app_icon') . settings()->pwa->app_icon : null),
                'app_icon_maskable_url' => $image_uploaded_file['pwa_icon'] ? \Altum\Uploads::get_full_url('app_icon') . $image_uploaded_file['pwa_icon'] : (settings()->pwa->app_icon_maskable ? \Altum\Uploads::get_full_url('app_icon') . settings()->pwa->app_icon_maskable : null),
                'start_url' => $full_url,
                'scope' => $full_url,
                'mobile_screenshots' => [],
                'desktop_screenshots' => [],
                'shortcuts' => [],
            ]);
            pwa_save_manifest($manifest, $pwa_file_name);
        }

        /* Set the new settings variable */
        $settings = [
            'pwa_file_name' => $pwa_file_name ?? null,
            'pwa_is_enabled' => $_POST['pwa_is_enabled'],
            'pwa_display_install_bar' => $_POST['pwa_display_install_bar'],
            'pwa_display_install_bar_delay' => $_POST['pwa_display_install_bar_delay'],
            'pwa_theme_color' => $_POST['pwa_theme_color'],
            'pwa_icon' => $image_uploaded_file['pwa_icon'],

            'verified_location' => $_POST['verified_location'],
            'background_type' => $_POST['background_type'],
            'background_attachment' => $_POST['background_attachment'],
            'background_blur' => $_POST['background_blur'],
            'background_brightness' => $_POST['background_brightness'],
            'background' => $background ?? $link->settings->background,
            'background_color_one' => $background_color_one ?? null,
            'background_color_two' => $background_color_two ?? null,
            'favicon' => $image_uploaded_file['favicon'],
            'text_color' => $_POST['text_color'],
            'display_branding' => $_POST['display_branding'],
            'branding' => [
                'name' => $_POST['branding_name'],
                'url' => $_POST['branding_url'],
            ],
            'seo' => [
                'block' => $_POST['seo_block'],
                'title' => $_POST['seo_title'],
                'meta_description' => $_POST['seo_meta_description'],
                'meta_keywords' => $_POST['seo_meta_keywords'],
                'image' => $image_uploaded_file['seo_image'],
            ],
            'utm' => [
                'medium' => $_POST['utm_medium'],
                'source' => $_POST['utm_source'],
            ],
            'font' => $_POST['font'],
            'width' => $_POST['width'],
            'block_spacing' => $_POST['block_spacing'],
            'hover_animation' => $_POST['hover_animation'],
            'font_size' => $_POST['font_size'],
            'password' => $_POST['password'],
            'sensitive_content' => $_POST['sensitive_content'],
            'leap_link' => $_POST['leap_link'],
            'custom_css' => $_POST['custom_css'],
            'custom_js' => $_POST['custom_js'],
            'share_is_enabled' => $_POST['share_is_enabled'],
            'scroll_buttons_is_enabled' => $_POST['scroll_buttons_is_enabled'],
        ];

        /* Check if we need to override defaults for a new theme */
        $additional = null;
        if($_POST['biolink_theme_id'] && $link->biolink_theme_id != $_POST['biolink_theme_id']) {
            $biolink_theme = $biolinks_themes[$_POST['biolink_theme_id']];

            /* Save settings for biolink page */
            $settings = array_merge($settings, (array) $biolink_theme->settings->biolink);

            /* Save the additional settings */
            $additional = json_encode($biolink_theme->settings->additional ?? '');

            /* Save settings for all existing blocks */
            $biolink_blocks = require APP_PATH . 'includes/biolink_blocks.php';
            $themable_blocks = [];
            foreach($biolink_blocks as $key => $value) {
                if($value['themable']) $themable_blocks[] = $key;
            }
            $themable_blocks_sql = "'" . implode('\', \'', $themable_blocks) . "'";

            $biolink_blocks_result = database()->query("SELECT `biolink_block_id`, `type`, `settings` FROM `biolinks_blocks` WHERE `link_id` = {$link->link_id} AND `type` IN ({$themable_blocks_sql})");
            while($biolink_block = $biolink_blocks_result->fetch_object()) {
                $biolink_block->settings = json_decode($biolink_block->settings ?? '');

                switch($biolink_block->type) {
                    case 'socials':
                        $biolink_block->settings = (object) array_merge((array) $biolink_block->settings, (array) $biolink_theme->settings->biolink_block_socials ?? []);
                        break;

                    case 'heading':
                        $biolink_block->settings = (object) array_merge((array) $biolink_block->settings, (array) $biolink_theme->settings->biolink_block_heading ?? []);
                        break;

                    case 'paragraph':
                        $biolink_block->settings = (object) array_merge((array) $biolink_block->settings, (array) $biolink_theme->settings->biolink_block_paragraph ?? []);
                        break;

                    default:
                        $biolink_block->settings = (object) array_merge((array) $biolink_block->settings, (array) $biolink_theme->settings->biolink_block ?? []);
                        break;
                }

                $new_biolink_block_settings = json_encode($biolink_block->settings);

                db()->where('biolink_block_id', $biolink_block->biolink_block_id)->update('biolinks_blocks', [
                    'settings' => $new_biolink_block_settings,
                ]);
            }

            /* Clear the cache */
            $this->clear_link_cache($link->link_id, 'biolink', $this->user->user_id);
        }

        /* Prepare background url if needed */
        $image_url['background'] = $settings['background_type'] == 'image' && $settings['background'] ?  UPLOADS_FULL_URL . $image_upload_path['background'] . $settings['background'] : null;

        /* Prepare settings for JSON insertion */
        $settings = json_encode($settings);

        /* Update the record */
        db()->where('link_id', $link->link_id)->update('links', [
            'project_id' => $_POST['project_id'],
            'splash_page_id' => $_POST['splash_page_id'],
            'domain_id' => $domain_id,
            'biolink_theme_id' => $_POST['biolink_theme_id'],
            'pixels_ids' => $_POST['pixels_ids'],
            'url' => $url,
            'settings' => $settings,
            'additional' => $additional,
            'directory_is_enabled' => $_POST['directory_is_enabled'],
            'last_datetime' => get_date(),
        ]);

        $this->process_is_main_link_domain($link, $domains);

        $url = $domain_id && $_POST['is_main_link'] ? '' : $url;

        /* Clear the cache */
        $this->clear_link_cache($link->link_id, 'biolink', $this->user->user_id);

        Response::json(l('global.success_message.update2'), 'success', [
            'url' => $url,
            'images' => [
                'seo_image' => $image_url['seo_image'],
                'favicon' => $image_url['favicon'],
                'background' => $image_url['background'],
                'pwa_icon' => $image_url['pwa_icon']
            ],
        ]);
    }
    
    public function validate($type, $data = []) {
        return true;
    }
}
