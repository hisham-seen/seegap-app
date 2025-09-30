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

defined('SEEGAP') || die();

class MicrositeBlockAjax extends Controller {

    public function index() {

        \SeeGap\Authentication::guard();

        if(!empty($_POST) && (Alerts::has_field_errors() || Alerts::has_errors()) == false) {

            switch($_POST['type']) {

                /* Create */
                case 'create':

                    $link_id = (int) $_POST['link_id'];
                    $type = query_clean($_POST['block_type']);

                    //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

                    /* Check for any errors */
                    $required_fields = ['link_id', 'block_type'];
                    foreach($required_fields as $field) {
                        if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                            Alerts::add_field_error($field, l('global.error_message.empty_field'));
                        }
                    }

                    if(!\SeeGap\Csrf::check()) {
                        Alerts::add_error(l('global.error_message.invalid_csrf_token'));
                    }

                    /* Make sure that the user didn't exceed the limit */
                    $user_total_microsite_blocks = database()->query("SELECT COUNT(*) AS `total` FROM `microsite_blocks` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;
                    if($this->user->plan_settings->microsite_blocks_limit != -1 && $user_total_microsite_blocks >= $this->user->plan_settings->microsite_blocks_limit) {
                        Alerts::add_error(l('global.info_message.plan_feature_limit'));
                    }

                    /* Make sure the link exists and is accessible to the user */
                    $link = db()->where('link_id', $link_id)->where('user_id', $this->user->user_id)->getOne('links');
                    if(!$link) {
                        die();
                    }

                    /* Make sure the microsite block type is correct */
                    if(!array_key_exists($type, get_microsite_blocks())) {
                        die();
                    }

                    if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                        $settings = json_encode([
                            'name' => $_POST['name'],
                            'text_color' => 'black',
                            'text_alignment' => 'center',
                            'background_color' => 'white',
                            'border_width' => 0,
                            'border_color' => 'white',
                            'border_radius' => 'rounded',
                            'border_style' => 'solid',
                            'animation' => false,
                            'animation_type' => 'fadeIn',
                            'animation_duration' => 1000,
                        ]);

                        $microsite_block_id = db()->insert('microsite_blocks', [
                            'user_id' => $this->user->user_id,
                            'link_id' => $link_id,
                            'type' => $type,
                            'settings' => $settings,
                            'start_date' => $_POST['start_date'],
                            'end_date' => $_POST['end_date'],
                            'is_enabled' => 1,
                            'datetime' => \SeeGap\Date::$date,
                        ]);

                        /* Clear the cache */
                        cache()->deleteItemsByTag('link_id=' . $link_id);

                        Response::json('', 'success', ['url' => url('link/' . $link_id . '?tab=blocks')]);
                    }

                    break;

                /* Update */
                case 'update':

                    $microsite_block_id = (int) $_POST['microsite_block_id'];
                    $link_id = (int) $_POST['link_id'];

                    //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

                    /* Check for any errors */
                    $required_fields = ['microsite_block_id'];
                    foreach($required_fields as $field) {
                        if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                            Alerts::add_field_error($field, l('global.error_message.empty_field'));
                        }
                    }

                    if(!\SeeGap\Csrf::check()) {
                        Alerts::add_error(l('global.error_message.invalid_csrf_token'));
                    }

                    /* Make sure the microsite block exists and is accessible to the user */
                    $microsite_block = db()->where('microsite_block_id', $microsite_block_id)->where('user_id', $this->user->user_id)->getOne('microsite_blocks');
                    if(!$microsite_block) {
                        die();
                    }

                    $microsite_block->settings = json_decode($microsite_block->settings ?? '');

                    if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                        /* Process the microsite block */
                        $microsite_blocks_handler = new \SeeGap\Controllers\IndividualBlocksHandler($microsite_block, $this->user, $_POST);
                        $microsite_blocks_handler->process();

                        /* Clear the cache */
                        cache()->deleteItemsByTag('link_id=' . $microsite_block->link_id);

                        Response::json('', 'success', ['url' => url('link/' . $microsite_block->link_id . '?tab=blocks')]);
                    }

                    break;

                /* Delete */
                case 'delete':

                    $microsite_block_id = (int) $_POST['microsite_block_id'];

                    //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

                    if(!\SeeGap\Csrf::check()) {
                        Alerts::add_error(l('global.error_message.invalid_csrf_token'));
                    }

                    /* Make sure the microsite block exists and is accessible to the user */
                    $microsite_block = db()->where('microsite_block_id', $microsite_block_id)->where('user_id', $this->user->user_id)->getOne('microsite_blocks');
                    if(!$microsite_block) {
                        die();
                    }

                    if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                        /* Delete the microsite block */
                        db()->where('microsite_block_id', $microsite_block_id)->delete('microsite_blocks');

                        /* Clear the cache */
                        cache()->deleteItemsByTag('link_id=' . $microsite_block->link_id);

                        Response::json('', 'success', ['url' => url('link/' . $microsite_block->link_id . '?tab=blocks')]);

                    }

                    break;

                /* Duplicate */
                case 'duplicate':

                    $microsite_block_id = (int) $_POST['microsite_block_id'];

                    //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

                    if(!\SeeGap\Csrf::check()) {
                        Alerts::add_error(l('global.error_message.invalid_csrf_token'));
                    }

                    /* Make sure that the user didn't exceed the limit */
                    $user_total_microsite_blocks = database()->query("SELECT COUNT(*) AS `total` FROM `microsite_blocks` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;
                    if($this->user->plan_settings->microsite_blocks_limit != -1 && $user_total_microsite_blocks >= $this->user->plan_settings->microsite_blocks_limit) {
                        Alerts::add_error(l('global.info_message.plan_feature_limit'));
                    }

                    /* Make sure the microsite block exists and is accessible to the user */
                    $microsite_block = db()->where('microsite_block_id', $microsite_block_id)->where('user_id', $this->user->user_id)->getOne('microsite_blocks');
                    if(!$microsite_block) {
                        die();
                    }

                    if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                        $microsite_block_id = db()->insert('microsite_blocks', [
                            'user_id' => $this->user->user_id,
                            'link_id' => $microsite_block->link_id,
                            'type' => $microsite_block->type,
                            'settings' => $microsite_block->settings,
                            'start_date' => $microsite_block->start_date,
                            'end_date' => $microsite_block->end_date,
                            'is_enabled' => $microsite_block->is_enabled,
                            'datetime' => \SeeGap\Date::$date,
                        ]);

                        /* Clear the cache */
                        cache()->deleteItemsByTag('link_id=' . $microsite_block->link_id);

                        Response::json('', 'success', ['url' => url('link/' . $microsite_block->link_id . '?tab=blocks')]);

                    }

                    break;

                /* Order */
                case 'order':

                    if(!\SeeGap\Csrf::check()) {
                        Alerts::add_error(l('global.error_message.invalid_csrf_token'));
                    }

                    foreach($_POST['microsite_blocks'] as $microsite_block) {
                        $microsite_block_id = (int) $microsite_block['microsite_block_id'];
                        $order = (int) $microsite_block['order'];

                        /* Update the microsite block order */
                        db()->where('microsite_block_id', $microsite_block_id)->where('user_id', $this->user->user_id)->update('microsite_blocks', ['order' => $order]);
                    }

                    if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                        /* Clear the cache */
                        if(isset($_POST['link_id'])) {
                            cache()->deleteItemsByTag('link_id=' . (int) $_POST['link_id']);
                        }

                        Response::json('', 'success');

                    }

                    break;

            }

        }

        die();
    }

}
