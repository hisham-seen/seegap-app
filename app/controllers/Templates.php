<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers;

defined('SEEGAP') || die();

class Templates extends Controller {

    public function index() {
        \SeeGap\Authentication::guard();

        if(!\SeeGap\Plugin::is_active('aix') || !settings()->aix->documents_is_enabled) {
            redirect('not-found');
        }

        /* Get available templates categories */
        $templates_categories = (new \SeeGap\Models\TemplatesCategories())->get_templates_categories();

        /* Templates */
        $templates = (new \SeeGap\Models\Templates())->get_templates();

        /* Prepare the view */
        $data = [
            'templates' => $templates,
            'templates_categories' => $templates_categories,
        ];

        $view = new \SeeGap\View(\SeeGap\Plugin::get('aix')->path . 'views/templates/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

}
