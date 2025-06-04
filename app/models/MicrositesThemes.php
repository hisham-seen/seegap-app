<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Models;

defined('SEEGAP') || die();

class MicrositesThemes extends Model {

    public function get_microsites_themes() {
        if(!settings()->links->microsites_themes_is_enabled) return [];

        $microsites_themes = [];

        /* Try to check if the user exists via the cache */
        $cache_instance = cache()->getItem('microsites_themes');

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $microsites_themes_result = database()->query("SELECT * FROM `microsites_themes` WHERE `is_enabled` = 1 ORDER BY `order`");
            while($row = $microsites_themes_result->fetch_object()) {
                $row->settings = json_decode($row->settings ?? '');
                $microsites_themes[$row->microsite_theme_id] = $row;
            }

            cache()->save(
                $cache_instance->set($microsites_themes)->expiresAfter(CACHE_DEFAULT_SECONDS)
            );

        } else {

            /* Get cache */
            $microsites_themes = $cache_instance->get();

        }

        return $microsites_themes;

    }

}
