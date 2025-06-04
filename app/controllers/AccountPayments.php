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

class AccountPayments extends Controller {

    public function index() {

        \SeeGap\Authentication::guard();

        if(!settings()->payment->is_enabled) {
            redirect('not-found');
        }

        $payment_processors = require APP_PATH . 'includes/payment_processors.php';

        /* Prepare the filtering system */
        $filters = (new \SeeGap\Filters(['processor', 'type', 'frequency'], [], ['id', 'total_amount', 'datetime']));
        $filters->set_default_order_by('id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `payments` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \SeeGap\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('account-payments?' . $filters->get_get() . '&page=%d')));

        /* Get the payments list for the user */
        $payments = [];
        $payments_result = database()->query("SELECT `payments`.*, `plans`.`name`  AS `plan_name`, `plans`.`translations` FROM `payments` LEFT JOIN plans ON `payments`.plan_id = plans.plan_id WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where('payments')} {$filters->get_sql_order_by('payments')} {$paginator->get_sql_limit()}");
        while($row = $payments_result->fetch_object()) {
            $row->translations = json_decode($row->translations ?? '');
            $payments[] = $row;
        }

        /* Export handler */
        process_export_json($payments, 'include', ['id', 'user_id', 'plan_id', 'payment_id', 'email', 'name', 'processor', 'type', 'frequency', 'billing', 'taxes_ids', 'base_amount', 'code', 'discount_amount', 'total_amount', 'currency', 'status', 'datetime']);
        process_export_csv($payments, 'include', ['id', 'user_id', 'plan_id', 'payment_id', 'email', 'name', 'processor', 'type', 'frequency', 'base_amount', 'code', 'discount_amount', 'total_amount', 'currency', 'status', 'datetime']);

        /* Prepare the pagination view */
        $pagination = (new \SeeGap\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Get the account header menu */
        $menu = new \SeeGap\View('partials/account_header_menu', (array) $this);
        $this->add_view_content('account_header_menu', $menu->run());

        /* Prepare the view */
        $data = [
            'payments' => $payments,
            'pagination' => $pagination,
            'filters' => $filters,
            'payment_processors' => $payment_processors,
        ];

        $view = new \SeeGap\View('account-payments/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
