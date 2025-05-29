<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\models;

defined('ALTUMCODE') || die();

class MicrositesTemplates extends Model {

    public function get_microsites_templates() {

        /* Get the user pixels */
        $microsites_templates = [];

        /* Try to check if the user exists via the cache */
        $cache_instance = cache()->getItem('microsites_templates');

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $microsites_templates_result = database()->query("SELECT * FROM `microsites_templates` WHERE `is_enabled` = 1 ORDER BY `order`");
            while($row = $microsites_templates_result->fetch_object()) {
                $row->settings = json_decode($row->settings ?? '');
                $microsites_templates[$row->microsite_template_id] = $row;
            }

            cache()->save(
                $cache_instance->set($microsites_templates)->expiresAfter(CACHE_DEFAULT_SECONDS)
            );

        } else {

            /* Get cache */
            $microsites_templates = $cache_instance->get();

        }

        return $microsites_templates;

    }

}
