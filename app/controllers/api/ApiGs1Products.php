<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers;

use SeeGap\Response;

defined('SEEGAP') || die();

class ApiGs1Products extends Controller {

    public function index() {

        $this->verify_request();

        /* Decide what to continue with */
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':

                /* Detect if we only need an object, or the whole list */
                if(isset($this->params[0])) {
                    $this->get();
                } else {
                    $this->get_all();
                }

                break;

            case 'POST':
                $this->post();
                break;

            case 'PATCH':
                $this->patch();
                break;

            case 'DELETE':
                $this->delete();
                break;
        }

        $this->return_404();
    }

    private function get_all() {

        /* Prepare the filtering system */
        $filters = (new \SeeGap\Filters(['is_enabled', 'project_id'], ['product_name', 'gtin', 'brand_name'], ['last_datetime', 'datetime', 'product_name', 'gtin']));
        $filters->set_default_order_by('product_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `products` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \SeeGap\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/gs1-products?' . $filters->get_get() . '&page=%d')));

        /* Get the products list for the user */
        $products = [];
        $products_result = database()->query("
            SELECT 
                `product_id`, 
                `gtin`, 
                `product_name`, 
                `brand_name`, 
                `category`, 
                `subcategory`,
                `manufacturer`,
                `target_url`,
                `project_id`,
                `gs1_link_id`,
                `is_enabled`,
                `carbon_footprint`,
                `water_usage`,
                `renewable_energy_percentage`,
                `recyclability_score`,
                `sustainability_certifications`,
                `supply_chain_transparency`,
                `ethical_sourcing`,
                `blockchain_verified`,
                `passport_public`,
                `passport_seo`,
                `data_verification_status`,
                `passport_last_updated`,
                `datetime`,
                `last_datetime`
            FROM `products` 
            WHERE `user_id` = {$this->user->user_id} 
            {$filters->get_sql_where()} 
            {$filters->get_sql_order_by()} 
            {$paginator->get_sql_limit()}
        ");
        while($row = $products_result->fetch_object()) {

            /* Generate the full URL */
            $row->full_url = url('gs1-product/' . $row->product_id);
            $row->passport_url = $row->passport_public ? url('gs1-product/' . $row->product_id . '/passport') : null;

            $products[] = $row;
        }

        /* Prepare the data */
        $meta = [
            'page' => $_GET['page'] ?? 1,
            'total_pages' => $paginator->getNumPages(),
            'results_per_page' => $filters->get_results_per_page(),
            'total_results' => (int) $total_rows,
        ];

        $data = [
            'products' => $products,
            'meta' => $meta,
            'filters' => $filters->get_output(),
        ];

        Response::json('', 'success', $data);
    }

    private function get() {

        $product_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource */
        $product = db()->where('product_id', $product_id)->where('user_id', $this->user->user_id)->getOne('products');

        if(!$product) {
            $this->return_404();
        }

        /* Generate additional URLs */
        $product->full_url = url('gs1-product/' . $product->product_id);
        $product->passport_url = $product->passport_public ? url('gs1-product/' . $product->product_id . '/passport') : null;

        /* Prepare the data */
        $data = [
            'product' => $product,
        ];

        Response::json('', 'success', $data);
    }

    private function post() {

        $gtin = query_clean($_POST['gtin'] ?? '');
        $product_name = query_clean($_POST['product_name'] ?? '');
        $brand_name = query_clean($_POST['brand_name'] ?? '');
        $category = query_clean($_POST['category'] ?? '');
        $subcategory = query_clean($_POST['subcategory'] ?? '');
        $manufacturer = query_clean($_POST['manufacturer'] ?? '');
        $target_url = query_clean($_POST['target_url'] ?? '');
        $project_id = !empty($_POST['project_id']) ? (int) $_POST['project_id'] : null;
        $gs1_link_id = !empty($_POST['gs1_link_id']) ? (int) $_POST['gs1_link_id'] : null;
        $is_enabled = (int) isset($_POST['is_enabled']);

        /* Check for any errors */
        $required_fields = ['gtin', 'product_name'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_field'), 'error');
            }
        }

        /* Check for duplicate GTIN */
        if(db()->where('gtin', $gtin)->where('user_id', $this->user->user_id)->has('products')) {
            Response::json(l('products.error_message.gtin_exists'), 'error');
        }

        /* Validate target URL */
        if(!empty($target_url) && !filter_var($target_url, FILTER_VALIDATE_URL)) {
            Response::json(l('global.error_message.invalid_url'), 'error');
        }

        /* Check if project exists and belongs to user */
        if($project_id && !db()->where('project_id', $project_id)->where('user_id', $this->user->user_id)->has('projects')) {
            Response::json(l('projects.error.project_not_found'), 'error');
        }

        /* Check if GS1 link exists and belongs to user */
        if($gs1_link_id && !db()->where('gs1_link_id', $gs1_link_id)->where('user_id', $this->user->user_id)->has('gs1_links')) {
            Response::json(l('gs1_links.error.gs1_link_not_found'), 'error');
        }

        /* Check for the plan limit */
        $user_total_products = database()->query("SELECT COUNT(*) AS `total` FROM `products` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;
        if($this->user->plan_settings->products_limit != -1 && $user_total_products >= $this->user->plan_settings->products_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Prepare optional fields */
        $product_description = input_clean($_POST['product_description'] ?? '');
        $carbon_footprint = !empty($_POST['carbon_footprint']) ? (float) $_POST['carbon_footprint'] : null;
        $water_usage = !empty($_POST['water_usage']) ? (float) $_POST['water_usage'] : null;
        $renewable_energy_percentage = !empty($_POST['renewable_energy_percentage']) ? (int) $_POST['renewable_energy_percentage'] : null;
        $recyclability_score = query_clean($_POST['recyclability_score'] ?? '');
        $sustainability_certifications = input_clean($_POST['sustainability_certifications'] ?? '');
        $supply_chain_transparency = query_clean($_POST['supply_chain_transparency'] ?? '');
        $ethical_sourcing = query_clean($_POST['ethical_sourcing'] ?? '');
        $blockchain_verified = (int) isset($_POST['blockchain_verified']);
        $passport_public = (int) isset($_POST['passport_public']);
        $passport_seo = (int) isset($_POST['passport_seo']);
        $data_verification_status = query_clean($_POST['data_verification_status'] ?? 'unverified');

        /* Database query */
        $product_id = db()->insert('products', [
            'user_id' => $this->user->user_id,
            'project_id' => $project_id,
            'gs1_link_id' => $gs1_link_id,
            'gtin' => $gtin,
            'product_name' => $product_name,
            'brand_name' => $brand_name,
            'product_description' => $product_description,
            'category' => $category,
            'subcategory' => $subcategory,
            'manufacturer' => $manufacturer,
            'target_url' => $target_url,
            'carbon_footprint' => $carbon_footprint,
            'water_usage' => $water_usage,
            'renewable_energy_percentage' => $renewable_energy_percentage,
            'recyclability_score' => $recyclability_score,
            'sustainability_certifications' => $sustainability_certifications,
            'supply_chain_transparency' => $supply_chain_transparency,
            'ethical_sourcing' => $ethical_sourcing,
            'blockchain_verified' => $blockchain_verified,
            'passport_public' => $passport_public,
            'passport_seo' => $passport_seo,
            'data_verification_status' => $data_verification_status,
            'passport_last_updated' => \SeeGap\Date::$date,
            'is_enabled' => $is_enabled,
            'datetime' => \SeeGap\Date::$date,
        ]);

        /* Clear the cache */
        cache()->deleteItemsByTag('user_id=' . $this->user->user_id);

        /* Prepare the data */
        $data = [
            'id' => $product_id
        ];

        Response::json(l('products.success.created'), 'success', $data);
    }

    private function patch() {

        $product_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource */
        $product = db()->where('product_id', $product_id)->where('user_id', $this->user->user_id)->getOne('products');

        if(!$product) {
            $this->return_404();
        }

        $gtin = query_clean($_POST['gtin'] ?? $product->gtin);
        $product_name = query_clean($_POST['product_name'] ?? $product->product_name);
        $brand_name = query_clean($_POST['brand_name'] ?? $product->brand_name);
        $category = query_clean($_POST['category'] ?? $product->category);
        $subcategory = query_clean($_POST['subcategory'] ?? $product->subcategory);
        $manufacturer = query_clean($_POST['manufacturer'] ?? $product->manufacturer);
        $target_url = query_clean($_POST['target_url'] ?? $product->target_url);
        $project_id = isset($_POST['project_id']) ? (!empty($_POST['project_id']) ? (int) $_POST['project_id'] : null) : $product->project_id;
        $gs1_link_id = isset($_POST['gs1_link_id']) ? (!empty($_POST['gs1_link_id']) ? (int) $_POST['gs1_link_id'] : null) : $product->gs1_link_id;
        $is_enabled = isset($_POST['is_enabled']) ? (int) $_POST['is_enabled'] : $product->is_enabled;

        /* Check for duplicate GTIN */
        if($gtin != $product->gtin && db()->where('gtin', $gtin)->where('user_id', $this->user->user_id)->has('products')) {
            Response::json(l('products.error_message.gtin_exists'), 'error');
        }

        /* Validate target URL */
        if(!empty($target_url) && !filter_var($target_url, FILTER_VALIDATE_URL)) {
            Response::json(l('global.error_message.invalid_url'), 'error');
        }

        /* Check if project exists and belongs to user */
        if($project_id && !db()->where('project_id', $project_id)->where('user_id', $this->user->user_id)->has('projects')) {
            Response::json(l('projects.error.project_not_found'), 'error');
        }

        /* Check if GS1 link exists and belongs to user */
        if($gs1_link_id && !db()->where('gs1_link_id', $gs1_link_id)->where('user_id', $this->user->user_id)->has('gs1_links')) {
            Response::json(l('gs1_links.error.gs1_link_not_found'), 'error');
        }

        /* Prepare optional fields */
        $product_description = isset($_POST['product_description']) ? input_clean($_POST['product_description']) : $product->product_description;
        $carbon_footprint = isset($_POST['carbon_footprint']) ? (!empty($_POST['carbon_footprint']) ? (float) $_POST['carbon_footprint'] : null) : $product->carbon_footprint;
        $water_usage = isset($_POST['water_usage']) ? (!empty($_POST['water_usage']) ? (float) $_POST['water_usage'] : null) : $product->water_usage;
        $renewable_energy_percentage = isset($_POST['renewable_energy_percentage']) ? (!empty($_POST['renewable_energy_percentage']) ? (int) $_POST['renewable_energy_percentage'] : null) : $product->renewable_energy_percentage;
        $recyclability_score = isset($_POST['recyclability_score']) ? query_clean($_POST['recyclability_score']) : $product->recyclability_score;
        $sustainability_certifications = isset($_POST['sustainability_certifications']) ? input_clean($_POST['sustainability_certifications']) : $product->sustainability_certifications;
        $supply_chain_transparency = isset($_POST['supply_chain_transparency']) ? query_clean($_POST['supply_chain_transparency']) : $product->supply_chain_transparency;
        $ethical_sourcing = isset($_POST['ethical_sourcing']) ? query_clean($_POST['ethical_sourcing']) : $product->ethical_sourcing;
        $blockchain_verified = isset($_POST['blockchain_verified']) ? (int) $_POST['blockchain_verified'] : $product->blockchain_verified;
        $passport_public = isset($_POST['passport_public']) ? (int) $_POST['passport_public'] : $product->passport_public;
        $passport_seo = isset($_POST['passport_seo']) ? (int) $_POST['passport_seo'] : $product->passport_seo;
        $data_verification_status = isset($_POST['data_verification_status']) ? query_clean($_POST['data_verification_status']) : $product->data_verification_status;

        /* Database query */
        db()->where('product_id', $product->product_id)->update('products', [
            'project_id' => $project_id,
            'gs1_link_id' => $gs1_link_id,
            'gtin' => $gtin,
            'product_name' => $product_name,
            'brand_name' => $brand_name,
            'product_description' => $product_description,
            'category' => $category,
            'subcategory' => $subcategory,
            'manufacturer' => $manufacturer,
            'target_url' => $target_url,
            'carbon_footprint' => $carbon_footprint,
            'water_usage' => $water_usage,
            'renewable_energy_percentage' => $renewable_energy_percentage,
            'recyclability_score' => $recyclability_score,
            'sustainability_certifications' => $sustainability_certifications,
            'supply_chain_transparency' => $supply_chain_transparency,
            'ethical_sourcing' => $ethical_sourcing,
            'blockchain_verified' => $blockchain_verified,
            'passport_public' => $passport_public,
            'passport_seo' => $passport_seo,
            'data_verification_status' => $data_verification_status,
            'passport_last_updated' => \SeeGap\Date::$date,
            'is_enabled' => $is_enabled,
            'last_datetime' => \SeeGap\Date::$date,
        ]);

        /* Clear the cache */
        cache()->deleteItemsByTag('user_id=' . $this->user->user_id);
        cache()->deleteItem('product?product_id=' . $product->product_id);

        /* Prepare the data */
        $data = [
            'id' => $product->product_id
        ];

        Response::json(l('products.success.updated'), 'success', $data);
    }

    private function delete() {

        $product_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource */
        $product = db()->where('product_id', $product_id)->where('user_id', $this->user->user_id)->getOne('products');

        if(!$product) {
            $this->return_404();
        }

        /* Delete the product */
        db()->where('product_id', $product_id)->delete('products');

        /* Clear cache */
        cache()->deleteItemsByTag('user_id=' . $this->user->user_id);
        cache()->deleteItem('product?product_id=' . $product_id);

        Response::json(l('products.success.deleted'), 'success');
    }

}
