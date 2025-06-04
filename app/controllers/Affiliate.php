<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers;

use SeeGap\Models\Plan;

defined('SEEGAP') || die();

class Affiliate extends Controller {

    public function index() {

        if(!\SeeGap\Plugin::is_active('affiliate') || (\SeeGap\Plugin::is_active('affiliate') && !settings()->affiliate->is_enabled)) {
            redirect('not-found');
        }

        /* Get the min & max of commission for affiliates */
        $plans = (new \SeeGap\Models\Plan())->get_plans();
        $plans['free'] = (new Plan())->get_plan_by_id('free');
        $plans['custom'] = (new Plan())->get_plan_by_id('custom');
        unset($plans['guest']);

        $minimum_commission = 100;
        $maximum_commission = 0;

        foreach($plans as $plan) {
            if($plan->settings->affiliate_commission_percentage > $maximum_commission) $maximum_commission = $plan->settings->affiliate_commission_percentage;
            if($plan->settings->affiliate_commission_percentage < $minimum_commission) $minimum_commission = $plan->settings->affiliate_commission_percentage;
        }

        

        /* Prepare the view */
        $data = [
            'minimum_commission' => $minimum_commission,
            'maximum_commission' => $maximum_commission,
        ];

        $view = new \SeeGap\View('affiliate/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}


