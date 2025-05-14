<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Models;

defined('ALTUMCODE') || die();

class BiolinksThemes extends Model {

    public function get_biolinks_themes() {
        if(!settings()->links->biolinks_themes_is_enabled) return [];

        $biolinks_themes = [];

        /* Try to check if the user exists via the cache */
        $cache_instance = cache()->getItem('biolinks_themes');

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $biolinks_themes_result = database()->query("SELECT * FROM `biolinks_themes` WHERE `is_enabled` = 1 ORDER BY `order`");
            while($row = $biolinks_themes_result->fetch_object()) {
                $row->settings = json_decode($row->settings ?? '');
                $biolinks_themes[$row->biolink_theme_id] = $row;
            }

            cache()->save(
                $cache_instance->set($biolinks_themes)->expiresAfter(CACHE_DEFAULT_SECONDS)
            );

        } else {

            /* Get cache */
            $biolinks_themes = $cache_instance->get();

        }

        return $biolinks_themes;

    }

}
