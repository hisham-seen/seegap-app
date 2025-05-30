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

defined('ALTUMCODE') || die();

class ReportView extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        $report_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$report_id) {
            redirect('reports');
        }

        /* Get the report */
        $report = (new \Altum\Models\Report())->get_report_by_id($report_id, $this->user->user_id);

        if(!$report) {
            redirect('reports');
        }

        /* Set page title */
        \Altum\Title::set(sprintf(l('report_view.title'), $report->name));

        /* Prepare the view */
        $data = [
            'report' => $report
        ];

        $view = new \Altum\View('report-view/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
