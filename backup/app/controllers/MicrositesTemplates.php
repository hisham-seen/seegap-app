<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 * 🌍 View all other existing AltumCode projects via https://altumcode.com/
 * 📧 Get in touch for support or general queries via https://altumcode.com/contact
 * 📤 Download the latest version via https://altumcode.com/downloads
 *
 * 🐦 X/Twitter: https://x.com/AltumCode
 * 📘 Facebook: https://facebook.com/altumcode
 * 📸 Instagram: https://instagram.com/altumcode
 */

namespace Altum\Controllers;

use Altum\Models\Domain;

defined('ALTUMCODE') || die();

class MicrositesTemplates extends Controller {

    public function index() {

        if(!settings()->links->microsites_is_enabled || !settings()->links->microsites_templates_is_enabled) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], ['name'], ['microsite_template_id', 'order', 'name']));
        $filters->set_default_order_by('order', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `microsites_templates` WHERE `is_enabled` = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('microsites-templates?' . $filters->get_get() . '&page=%d')));

        /* Get the links list for the project */
        $result = database()->query("
            SELECT 
                *
            FROM 
                `microsites_templates`
            WHERE 
                `is_enabled` = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");

        /* Iterate over the links */
        $microsites_templates = [];

        while($row = $result->fetch_object()) {
            $microsites_templates[] = $row;
        }

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Get domains */
        $domains = (new Domain())->get_available_domains_by_user($this->user);

        /* Create Link Modal */
        $view = new \Altum\View('links/create_link_modals', (array) $this);
        \Altum\Event::add_content($view->run(['domains' => $domains]), 'modals');

        /* Prepare the view */
        $data = [
            'microsites_templates' => $microsites_templates,
            'domains'            => $domains,
            'pagination'         => $pagination,
            'filters'            => $filters,
        ];

        $view = new \Altum\View('microsites-templates/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}


