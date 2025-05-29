<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Date;
use Altum\Models\MicrositesThemes;
use Altum\Models\Domain;
use Altum\Response;


defined('ALTUMCODE') || die();

class LinkAjax extends Controller {
    public $links_types = null;

    public function index() {
        \Altum\Authentication::guard();

        if(!empty($_POST) && (\Altum\Csrf::check('token') || \Altum\Csrf::check('global_token')) && isset($_POST['request_type'])) {

            $this->links_types = require APP_PATH . 'includes/links_types.php';

            switch($_POST['request_type']) {

                /* Status toggle */
                case 'is_enabled_toggle': $this->is_enabled_toggle(); break;

                /* Create */
                case 'create': $this->create(); break;

                /* Update */
                case 'update': $this->update(); break;

                /* Delete */
                case 'delete': $this->delete(); break;

                /* Duplicate */
                case 'duplicate': $this->duplicate(); break;

            }

        }

        die();
    }

    private function is_enabled_toggle() {
        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['link_id'] = (int) $_POST['link_id'];

        /* Get the current status */
        $link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links', ['link_id', 'is_enabled']);

        if($link) {
            $new_is_enabled = (int) !$link->is_enabled;

            db()->where('link_id', $link->link_id)->update('links', ['is_enabled' => $new_is_enabled]);

            /* Clear the cache */
            cache()->deleteItem('link?link_id=' . $_POST['link_id']);
            cache()->deleteItem('microsite_blocks?link_id=' . $_POST['link_id']);
            cache()->deleteItemsByTag('link_id=' . $_POST['link_id']);

            Response::json(l('global.success_message.create2'), 'success');
        }
    }

    private function create() {
        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['type'] = trim(query_clean($_POST['type']));

        /* Check for possible errors */
        if(!array_key_exists($_POST['type'], $this->links_types)) {
            die();
        }

        $this->route_to_link_handler($_POST['type'], 'create');
    }

    private function update() {
        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        if(empty($_POST)) {
            die();
        }

        /* Check for possible errors */
        if(!array_key_exists($_POST['type'], $this->links_types)) {
            die();
        }

        $this->route_to_link_handler($_POST['type'], 'update');
    }

    /**
     * Route requests to appropriate link handlers
     */
    private function route_to_link_handler($link_type, $action) {
        // Define mapping of link types to handler classes
        $link_handlers = [
            'link' => 'LinkHandler',
            'microsite' => 'MicrositeHandler', 
            'file' => 'FileHandler',
            'event' => 'EventHandler',
            'static' => 'StaticHandler',
        ];

        // Check if handler exists for this link type
        if (!isset($link_handlers[$link_type])) {
            Response::json(l('global.error_message.basic'), 'error');
        }

        $handler_class = $link_handlers[$link_type];
        
        // Load dependencies in correct order
        require_once APP_PATH . 'controllers/link-handlers/interfaces/LinkHandlerInterface.php';
        require_once APP_PATH . 'controllers/link-handlers/BaseLinkHandler.php';
        require_once APP_PATH . 'controllers/link-handlers/handlers/' . $handler_class . '.php';

        // Create handler instance with proper namespace
        $handler_class_full = '\\Altum\\Controllers\\LinkHandlers\\Handlers\\' . $handler_class;
        $handler = new $handler_class_full();
        
        // Set user context
        $handler->user = $this->user;
        
        // Execute the action
        $handler->$action($link_type);
    }

    private function delete() {
        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['link_id'] = (int) $_POST['link_id'];

        /* Check for possible errors */
        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links', ['link_id', 'type'])) {
            die();
        }

        (new \Altum\Models\Link())->delete($link->link_id);

        Response::json(l('global.success_message.delete2'), 'success', ['url' => url('links?type=' . $link->type)]);
    }

    public function duplicate() {
        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.links')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('links');
        }

        $_POST['link_id'] = (int) $_POST['link_id'];

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('links');
        }

        /* Get the link data */
        $link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links');

        if(!$link) {
            redirect('links');
        }

        /* Make sure that the user didn't exceed the limit */
        if($link->type == 'link') {
            if(!settings()->links->shortener_is_enabled) {
                Response::json(l('global.error_message.basic'), 'error');
            }

            $user_total_links = database()->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `user_id` = {$this->user->user_id} AND `type` = 'link'")->fetch_object()->total;
            if($this->user->plan_settings->links_limit != -1 && $user_total_links >= $this->user->plan_settings->links_limit) {
                Alerts::add_error(l('global.info_message.plan_feature_limit'));
            }
        }

        elseif($link->type == 'microsite') {
            if(!settings()->links->microsites_is_enabled) {
                Response::json(l('global.error_message.basic'), 'error');
            }

            $user_total_microsites = database()->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `user_id` = {$this->user->user_id} AND `type` = 'microsite'")->fetch_object()->total;
            if($this->user->plan_settings->microsites_limit != -1 && $user_total_microsites >= $this->user->plan_settings->microsites_limit) {
                Alerts::add_error(l('global.info_message.plan_feature_limit'));
            }
        }

        elseif($link->type == 'file') {
            if(!settings()->links->files_is_enabled) {
                Response::json(l('global.error_message.basic'), 'error');
            }

            $user_total_files = database()->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `user_id` = {$this->user->user_id} AND `type` = 'file'")->fetch_object()->total;
            if($this->user->plan_settings->files_limit != -1 && $user_total_files >= $this->user->plan_settings->files_limit) {
                Alerts::add_error(l('global.info_message.plan_feature_limit'));
            }
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Duplicate the link */
            $link->settings = json_decode($link->settings ?? '');

            if($link->type == 'microsite') {
                $link->settings->seo->image = \Altum\Uploads::copy_uploaded_file($link->settings->seo->image, 'block_images/', 'block_images/', 'json_error');
                $link->settings->favicon = \Altum\Uploads::copy_uploaded_file($link->settings->favicon, 'favicons/', 'favicons/', 'json_error');
                if($link->settings->background_type == 'image') $link->settings->background = \Altum\Uploads::copy_uploaded_file($link->settings->background, 'backgrounds/', 'backgrounds/', 'json_error');
                $link->settings->pwa_is_enabled = false;
            }

            if($link->type == 'file') {
                $link->settings->file = \Altum\Uploads::copy_uploaded_file($link->settings->file, \Altum\Uploads::get_path('files'), \Altum\Uploads::get_path('files'), 'json_error');
            }

            /* Generate random url if not specified */
            $url = mb_strtolower(string_generate(settings()->links->random_url_length ?? 7));
            while (db()->where('url', $url)->where('domain_id', $link->domain_id)->getValue('links', 'link_id')) {
                $url = mb_strtolower(string_generate(settings()->links->random_url_length ?? 7));
            }

            /* Database query */
            $link_id = db()->insert('links', [
                'user_id' => $this->user->user_id,
                'project_id' => $link->project_id,
                'microsite_theme_id' => $link->microsite_theme_id,
                'domain_id' => $link->domain_id,
                'pixels_ids' => $link->pixels_ids,
                'type' => $link->type,
                'url' => $url,
                'location_url' => $link->location_url,
                'settings' => json_encode($link->settings),
                'additional' => $link->additional ?? '',
                'start_date' => $link->start_date,
                'end_date' => $link->end_date,
                'is_verified' => 0,
                'is_enabled' => $link->is_enabled,
                'datetime' => get_date(),
            ]);

            /* Duplicate the microsite blocks */
            if($link->type == 'microsite') {
                /* Get all microsite blocks if needed */
                $microsite_blocks = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->get('microsites_blocks');

                foreach($microsite_blocks as $microsite_block) {
                    $microsite_block->settings = json_decode($microsite_block->settings ?? '');

                    if(is_array($microsite_block->settings)) {
                        $microsite_block->settings = (object) $microsite_block->settings;
                    }

                    /* Duplication of resources */
                    switch($microsite_block->type) {
                        case 'file':
                        case 'audio':
                        case 'video':
                        case 'pdf_document':
                        case 'powerpoint_presentation':
                        case 'excel_spreadsheet':
                            $microsite_block->settings->file = \Altum\Uploads::copy_uploaded_file($microsite_block->settings->file, \Altum\Uploads::get_path('files'), \Altum\Uploads::get_path('files'), 'json_error');
                            break;

                        case 'review':
                            $microsite_block->settings->image = \Altum\Uploads::copy_uploaded_file($microsite_block->settings->image, \Altum\Uploads::get_path('block_images'), \Altum\Uploads::get_path('block_images'), 'json_error');
                            break;

                        case 'avatar':
                            $microsite_block->settings->image = \Altum\Uploads::copy_uploaded_file($microsite_block->settings->image, 'avatars/', 'avatars/', 'json_error');
                            break;

                        case 'header':
                            $microsite_block->settings->avatar = \Altum\Uploads::copy_uploaded_file($microsite_block->settings->avatar, 'avatars/', 'avatars/', 'json_error');
                            $microsite_block->settings->background = \Altum\Uploads::copy_uploaded_file($microsite_block->settings->background, 'backgrounds/', 'backgrounds/', 'json_error');
                            break;

                        case 'image':
                        case 'image_grid':
                            $microsite_block->settings->image = \Altum\Uploads::copy_uploaded_file($microsite_block->settings->image, 'block_images/', 'block_images/', 'json_error');
                            break;

                        case 'heading':
                            $microsite_block->settings->verified_location = '';
                            break;

                        case 'image_slider':
                            $microsite_block->settings->items = (array) $microsite_block->settings->items;
                            foreach($microsite_block->settings->items as $key => $item) {
                                $microsite_block->settings->items[$key]->image = \Altum\Uploads::copy_uploaded_file($microsite_block->settings->items[$key]->image, 'block_images/', 'block_images/', 'json_error');
                            }

                            break;

                        default:
                            $microsite_block->settings->image = \Altum\Uploads::copy_uploaded_file($microsite_block->settings->image, 'block_thumbnail_images/', 'block_thumbnail_images/', 'json_error');
                            break;
                    }

                    /* Database query */
                    db()->insert('microsites_blocks', [
                        'user_id' => $this->user->user_id,
                        'link_id' => $link_id,
                        'type' => $microsite_block->type,
                        'location_url' => $microsite_block->location_url,
                        'settings' => json_encode($microsite_block->settings),
                        'order' => $microsite_block->order,
                        'start_date' => $microsite_block->start_date,
                        'end_date' => $microsite_block->end_date,
                        'is_enabled' => $microsite_block->is_enabled,
                        'datetime' => get_date(),
                    ]);
                }
            }

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.create2'));

            /* Redirect */
            redirect('link/' . $link_id);
        }

        redirect('links');
    }
}
