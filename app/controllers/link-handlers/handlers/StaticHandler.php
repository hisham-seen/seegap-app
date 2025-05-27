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

defined('ALTUMCODE') || die();

/**
 * Static Handler
 * 
 * Handles the creation and updating of static website hosting links.
 */
class StaticHandler extends BaseLinkHandler {
    
    public function getSupportedTypes() {
        return ['static'];
    }
    
    public function create($type) {
        if(!settings()->links->static_is_enabled) {
            Response::json(l('global.error_message.basic'), 'error');
        }

        $_POST['url'] = !empty($_POST['url']) && $this->user->plan_settings->custom_url ? get_slug($_POST['url'], '-', false) : null;

        $this->process_common_post_data();

        /* Make sure that the user didn't exceed the limit */
        $user_total_files = database()->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `user_id` = {$this->user->user_id} AND `type` = 'static'")->fetch_object()->total;
        if($this->user->plan_settings->static_limit != -1 && $user_total_files >= $this->user->plan_settings->static_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Check if custom domain is set */
        $domain_id = $this->get_domain_id($_POST['domain_id'] ?? false);

        /* Check for duplicate url if needed */
        $this->check_duplicate_url($_POST['url'], $domain_id);

        /* Start processing the uploaded file */
        if(!empty($_FILES['file']['name'])) {
            $file_extension = explode('.', $_FILES['file']['name']);
            $file_extension = mb_strtolower(end($file_extension));
            $file_temp = $_FILES['file']['tmp_name'];

            if($_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE) {
                Response::json(sprintf(l('global.error_message.file_size_limit'), get_max_upload()), 'error');
            }

            if($_FILES['file']['error'] && $_FILES['file']['error'] != UPLOAD_ERR_INI_SIZE) {
                Response::json(l('global.error_message.file_upload'), 'error');
            }

            if(!in_array($file_extension, \Altum\Uploads::get_whitelisted_file_extensions('static'))) {
                Response::json(l('global.error_message.invalid_file_type'), 'error');
            }

            if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                if(!is_writable(UPLOADS_PATH . \Altum\Uploads::get_path('static'))) {
                    Response::json(sprintf(l('global.error_message.directory_not_writable'), UPLOADS_PATH . \Altum\Uploads::get_path('static')), 'error');
                }
            }

            if(settings()->links->static_size_limit && $_FILES['file']['size'] > settings()->links->static_size_limit * 1000000) {
                Response::json(sprintf(l('global.error_message.file_size_limit'), settings()->links->static_size_limit), 'error');
            }
        }

        /* Create the new folder */
        $static_folder_name = md5($file_temp . time() . rand() . rand());
        mkdir(\Altum\Uploads::get_full_path('static') . $static_folder_name, 0777);

        /* Files array */
        $files = [];
        $folders = [];

        /* If it's a single HTML file */
        if($file_extension == 'html') {
            /* Upload the original */
            move_uploaded_file($file_temp, \Altum\Uploads::get_full_path('static') . $static_folder_name . '/index.html');

            $files[] = 'index.html';
        }

        /* If it's a zip that needs to be unzipped */
        if($file_extension == 'zip') {
            $zip = new \ZipArchive;
            if($zip->open($file_temp) === true) {

                /* Create folders */
                for($i = 0; $i < $zip->numFiles; $i++) {
                    $OnlyFileName = $zip->getNameIndex($i);
                    $FullFileName = $zip->statIndex($i);

                    if($FullFileName['name'][strlen($FullFileName['name'])-1] == "/" && !str_contains($FullFileName['name'], '__MACOSX')) {
                        @mkdir(\Altum\Uploads::get_full_path('static') . $static_folder_name . '/' . $FullFileName['name'],0777,true);
                        $folders[] = $FullFileName['name'];
                    }
                }

                /* Unzip into created folders, only the allowed file types */
                for($i = 0; $i < $zip->numFiles; $i++) {
                    $OnlyFileName = $zip->getNameIndex($i);
                    $FullFileName = $zip->statIndex($i);
                    $OnlyFileNameExtension = explode('.', $OnlyFileName);
                    $OnlyFileNameExtension = mb_strtolower(end($OnlyFileNameExtension));

                    if(!($FullFileName['name'][strlen($FullFileName['name'])-1] == "/") && !str_contains($FullFileName['name'], '__MACOSX')) {
                        if(in_array($OnlyFileNameExtension, \Altum\Uploads::$uploads['static']['inside_zip_whitelisted_file_extensions'])) {
                            copy('zip://'. $file_temp . '#' . $OnlyFileName , \Altum\Uploads::get_full_path('static') . $static_folder_name . '/' . $FullFileName['name']);
                            $files[] = $FullFileName['name'];
                        }
                    }
                }

                $zip->close();
            } else {
                Response::json(l('global.error_message.basic'), 'error');
            }
        }

        /* Start the creation process */
        $url = $_POST['url'] ?? $this->generate_random_url($domain_id);
        $type = 'static';
        $settings = json_encode([
            'files' => $files,
            'folders' => $folders,
            'static_folder' => $static_folder_name,
            'password' => null,
            'sensitive_content' => false,
            'clicks_limit' => null,
            'expiration_url' => null,
        ]);

        $this->check_url($_POST['url']);

        /* Insert to database */
        $link_id = db()->insert('links', [
            'user_id' => $this->user->user_id,
            'domain_id' => $domain_id,
            'type' => $type,
            'url' => $url,
            'settings' => $settings,
            'datetime' => get_date(),
        ]);

        /* Clear the cache */
        $this->clear_link_cache($link_id, $type, $this->user->user_id);

        Response::json(l('global.success_message.create2'), 'success', ['url' => url('link/' . $link_id)]);
    }
    
    public function update($type) {
        if(!settings()->links->static_is_enabled) {
            Response::json(l('global.error_message.basic'), 'error');
        }

        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['expiration_url'] = get_url($_POST['expiration_url']);
        $_POST['clicks_limit'] = empty($_POST['clicks_limit']) ? null : (int) $_POST['clicks_limit'];
        $_POST['sensitive_content'] = (int) isset($_POST['sensitive_content']);

        $this->process_common_post_data();
        $this->process_schedule_data();
        $this->process_pixels_data();
        $this->process_projects_and_splash_pages();

        $this->check_location_url($_POST['expiration_url'], true);

        /* Get domains */
        $domains = $this->get_available_domains();

        /* Check if custom domain is set */
        $domain_id = isset($domains[$_POST['domain_id']]) ? $_POST['domain_id'] : 0;

        /* Exclusivity check */
        $_POST['is_main_link'] = isset($_POST['is_main_link']) && $domain_id && $domains[$_POST['domain_id']]->type == 0;

        $this->check_url($_POST['url']);

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $link->settings = json_decode($link->settings ?? '');

        /* Check for a password set */
        $_POST['password'] = $this->process_password($link->settings->password);

        /* Check for duplicate url if needed */
        if($_POST['url'] && ($_POST['url'] != $link->url || $domain_id != $link->domain_id)) {
            $this->check_duplicate_url($_POST['url'], $domain_id, $link->link_id);
        }

        $url = $_POST['url'];
        if(empty($_POST['url'])) {
            $url = $this->generate_random_url($domain_id);
        }

        /* Start processing the uploaded file */
        if(!empty($_FILES['file']['name'])) {
            $file_extension = explode('.', $_FILES['file']['name']);
            $file_extension = mb_strtolower(end($file_extension));
            $file_temp = $_FILES['file']['tmp_name'];

            if($_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE) {
                Response::json(sprintf(l('global.error_message.file_size_limit'), get_max_upload()), 'error');
            }

            if($_FILES['file']['error'] && $_FILES['file']['error'] != UPLOAD_ERR_INI_SIZE) {
                Response::json(l('global.error_message.file_upload'), 'error');
            }

            if(!in_array($file_extension, \Altum\Uploads::get_whitelisted_file_extensions('static'))) {
                Response::json(l('global.error_message.invalid_file_type'), 'error');
            }

            if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                if(!is_writable(UPLOADS_PATH . \Altum\Uploads::get_path('static'))) {
                    Response::json(sprintf(l('global.error_message.directory_not_writable'), UPLOADS_PATH . \Altum\Uploads::get_path('static')), 'error');
                }
            }

            if(settings()->links->static_size_limit && $_FILES['file']['size'] > settings()->links->static_size_limit * 1000000) {
                Response::json(sprintf(l('global.error_message.file_size_limit'), settings()->links->static_size_limit), 'error');
            }

            /* Create a potentially temporary new folder */
            $static_folder_name = $link->settings->static_folder;

            /* Clear the already existing folder and contents */
            remove_directory_and_contents(\Altum\Uploads::get_full_path('static') . $static_folder_name);

            /* Create the new folder */
            mkdir(\Altum\Uploads::get_full_path('static') . $static_folder_name, 0777);

            /* Files array */
            $files = [];
            $folders = [];

            /* If it's a single HTML file */
            if($file_extension == 'html') {
                /* Upload the original */
                move_uploaded_file($file_temp, \Altum\Uploads::get_full_path('static') . $static_folder_name . '/index.html');

                $files[] = 'index.html';
            }

            /* If it's a zip that needs to be unzipped */
            if($file_extension == 'zip') {
                $zip = new \ZipArchive;
                if($zip->open($file_temp) === true) {

                    /* Create folders */
                    for($i = 0; $i < $zip->numFiles; $i++) {
                        $OnlyFileName = $zip->getNameIndex($i);
                        $FullFileName = $zip->statIndex($i);

                        if($FullFileName['name'][strlen($FullFileName['name'])-1] == "/" && !str_contains($FullFileName['name'], '__MACOSX')) {
                            @mkdir(\Altum\Uploads::get_full_path('static') . $static_folder_name . '/' . $FullFileName['name'],0777,true);
                            $folders[] = $FullFileName['name'];
                        }
                    }

                    /* Unzip into created folders, only the allowed file types */
                    for($i = 0; $i < $zip->numFiles; $i++) {
                        $OnlyFileName = $zip->getNameIndex($i);
                        $FullFileName = $zip->statIndex($i);
                        $OnlyFileNameExtension = explode('.', $OnlyFileName);
                        $OnlyFileNameExtension = mb_strtolower(end($OnlyFileNameExtension));

                        if(!($FullFileName['name'][strlen($FullFileName['name'])-1] == "/") && !str_contains($FullFileName['name'], '__MACOSX')) {
                            if(in_array($OnlyFileNameExtension, \Altum\Uploads::$uploads['static']['inside_zip_whitelisted_file_extensions'])) {
                                copy('zip://'. $file_temp . '#' . $OnlyFileName , \Altum\Uploads::get_full_path('static') . $static_folder_name . '/' . $FullFileName['name']);
                                $files[] = $FullFileName['name'];
                            }
                        }
                    }

                    $zip->close();
                } else {
                    Response::json(l('global.error_message.basic'), 'error');
                }
            }
        }

        $settings = [
            'files' => $files ?? $link->settings->files,
            'folders' => $folders ?? $link->settings->folders,
            'static_folder' => $link->settings->static_folder,
            'schedule' => $_POST['schedule'],
            'clicks_limit' => $_POST['clicks_limit'],
            'expiration_url' => $_POST['expiration_url'],
            'password' => $_POST['password'],
            'sensitive_content' => $_POST['sensitive_content'],
        ];

        $settings = json_encode($settings);

        db()->where('link_id', $_POST['link_id'])->update('links', [
            'project_id' => $_POST['project_id'],
            'splash_page_id' => $_POST['splash_page_id'],
            'domain_id' => $domain_id,
            'pixels_ids' => $_POST['pixels_ids'],
            'url' => $url,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'settings' => $settings,
            'last_datetime' => get_date(),
        ]);

        $this->process_is_main_link_domain($link, $domains);

        $url = $domain_id && $_POST['is_main_link'] ? '' : $url;

        /* Clear the cache */
        $this->clear_link_cache($link->link_id, 'static', $this->user->user_id);

        Response::json(l('global.success_message.update2'), 'success', ['url' => $url]);
    }
    
    public function validate($type, $data = []) {
        return true;
    }
}
