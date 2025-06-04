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

class TemplatesCategories extends Model {

    public function get_templates_categories() {

        /* Get the user projects */
        $templates_categories = [];

        /* Try to check if the user posts exists via the cache */
        $cache_instance = cache()->getItem('templates_categories');

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $templates_categories_result = database()->query("SELECT * FROM `templates_categories`  WHERE `is_enabled` = 1 ORDER BY `order`");
            while($row = $templates_categories_result->fetch_object()) {
                $row->settings = json_decode($row->settings ?? '');
                $templates_categories[$row->template_category_id] = $row;
            }

            cache()->save(
                $cache_instance->set($templates_categories)->expiresAfter(CACHE_DEFAULT_SECONDS)
            );

        } else {

            /* Get cache */
            $templates_categories = $cache_instance->get();

        }

        return $templates_categories;

    }

}
