<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers;

use SeeGap\Models\Product;

defined('SEEGAP') || die();

class ApiProducts extends Controller {

    public function index() {

        $user = $this->get_user();

        /* Check if products feature is enabled */
        if(!settings()->products->products_is_enabled) {
            $this->response_error(l('global.error_message.basic'), 403);
        }

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('read.products')) {
            $this->response_error(l('global.error_message.team_no_access'), 403);
        }

        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $results_per_page = $this->get_results_per_page();

        /* Prepare the filtering system */
        $filters = (new \SeeGap\Filters(['is_enabled', 'project_id', 'category', 'brand_name'], ['gtin', 'product_name', 'brand_name'], ['product_id', 'last_datetime', 'datetime', 'gtin', 'product_name', 'brand_name', 'category']));
        $filters->set_default_order_by('product_id', $user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `products` WHERE `user_id` = {$user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \SeeGap\Paginator($total_rows, $filters->get_results_per_page(), $page, url('api/products?' . $filters->get_get() . '&page=%d')));

        /* Get the products list for the user */
        $products_result = database()->query("
            SELECT 
                `products`.*, 
                `projects`.`name` as `project_name`, 
                `projects`.`color` as `project_color`
            FROM 
                `products`
            LEFT JOIN 
                `projects` ON `products`.`project_id` = `projects`.`project_id`
            WHERE 
                `products`.`user_id` = {$user->user_id} 
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");

        /* Iterate over the products */
        $products = [];

        while($row = $products_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '{}');
            $row->product_images = json_decode($row->product_images ?? '[]');
            $products[] = $row;
        }

        /* Prepare the data */
        $data = [
            'products' => $products,
            'pagination' => [
                'current_page' => $paginator->get_current_page(),
                'total_pages' => $paginator->get_total_pages(),
                'total_items' => (int) $total_rows,
                'results_per_page' => $filters->get_results_per_page()
            ]
        ];

        $this->response_ok($data);
    }

    public function read() {

        $user = $this->get_user();

        /* Check if products feature is enabled */
        if(!settings()->products->products_is_enabled) {
            $this->response_error(l('global.error_message.basic'), 403);
        }

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('read.products')) {
            $this->response_error(l('global.error_message.team_no_access'), 403);
        }

        $product_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$product_id) {
            $this->response_error(l('global.error_message.not_found'), 404);
        }

        /* Get the product details */
        $product = (new Product())->get_product_by_id($product_id, $user->user_id);

        if(!$product) {
            $this->response_error(l('global.error_message.not_found'), 404);
        }

        $this->response_ok($product);
    }

    public function create() {

        $user = $this->get_user();

        /* Check if products feature is enabled */
        if(!settings()->products->products_is_enabled) {
            $this->response_error(l('global.error_message.basic'), 403);
        }

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.products')) {
            $this->response_error(l('global.error_message.team_no_access'), 403);
        }

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `products` WHERE `user_id` = {$user->user_id}")->fetch_object()->total ?? 0;

        if(($user->plan_settings->products_limit ?? -1) != -1 && $total_rows >= ($user->plan_settings->products_limit ?? 0)) {
            $this->response_error(l('global.info_message.plan_feature_limit'), 401);
        }

        /* Get the projects available to the user */
        $projects = (new \SeeGap\Models\Projects())->get_projects_by_user_id($user->user_id);

        $gtin = query_clean($_POST['gtin'] ?? '');
        $brand_name = query_clean($_POST['brand_name'] ?? '');
        $product_name = query_clean($_POST['product_name'] ?? '');
        $product_description = input_clean($_POST['product_description'] ?? '');
        $category = query_clean($_POST['category'] ?? '');
        $subcategory = query_clean($_POST['subcategory'] ?? '');
        $manufacturer = query_clean($_POST['manufacturer'] ?? '');
        $country_of_origin = query_clean($_POST['country_of_origin'] ?? '');
        $net_weight = query_clean($_POST['net_weight'] ?? '');
        $dimensions = query_clean($_POST['dimensions'] ?? '');
        $ingredients = input_clean($_POST['ingredients'] ?? '');
        $nutritional_info = input_clean($_POST['nutritional_info'] ?? '');
        $allergen_info = input_clean($_POST['allergen_info'] ?? '');
        $certifications = input_clean($_POST['certifications'] ?? '');
        $packaging_info = input_clean($_POST['packaging_info'] ?? '');
        $storage_instructions = input_clean($_POST['storage_instructions'] ?? '');
        $usage_instructions = input_clean($_POST['usage_instructions'] ?? '');
        $target_url = query_clean($_POST['target_url'] ?? '');
        $project_id = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;
        $gs1_link_id = !empty($_POST['gs1_link_id']) ? (int) $_POST['gs1_link_id'] : null;

        /* Check for any errors */
        $required_fields = ['gtin', 'product_name'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                $this->response_error(l('global.error_message.empty_field') . ' (' . $field . ')', 400);
            }
        }

        /* Check for duplicate GTIN */
        if(!empty($gtin)) {
            $clean_gtin = preg_replace('/[^0-9]/', '', $gtin);
            if(db()->where('gtin', $clean_gtin)->where('user_id', $user->user_id)->has('products')) {
                $this->response_error(l('products.error_message.gtin_exists'), 400);
            }
        }

        /* Check target URL if provided */
        if(!empty($target_url) && !filter_var($target_url, FILTER_VALIDATE_URL)) {
            $this->response_error(l('global.error_message.invalid_url'), 400);
        }

        /* Database query */
        $product_data = [
            'user_id' => $user->user_id,
            'project_id' => $project_id,
            'gtin' => $gtin,
            'brand_name' => $brand_name,
            'product_name' => $product_name,
            'product_description' => $product_description,
            'category' => $category,
            'subcategory' => $subcategory,
            'manufacturer' => $manufacturer,
            'country_of_origin' => $country_of_origin,
            'net_weight' => $net_weight,
            'dimensions' => $dimensions,
            'ingredients' => $ingredients,
            'nutritional_info' => $nutritional_info,
            'allergen_info' => $allergen_info,
            'certifications' => $certifications,
            'packaging_info' => $packaging_info,
            'storage_instructions' => $storage_instructions,
            'usage_instructions' => $usage_instructions,
            'target_url' => $target_url,
            'gs1_link_id' => $gs1_link_id,
            'product_images' => [],
            'settings' => [],
            'is_enabled' => 1
        ];

        $product_model = new Product();
        $product_id = $product_model->create_product($product_data);

        if($product_id) {
            /* Auto-generate GS1 link if enabled */
            if(settings()->products->auto_generate_gs1_links && !$gs1_link_id) {
                $product_model->create_gs1_link_for_product($product_id, $user->user_id);
            }

            /* Get the created product */
            $product = $product_model->get_product_by_id($product_id, $user->user_id);

            $this->response_ok($product, 201);
        } else {
            $this->response_error(l('products.error_message.creation_failed'), 500);
        }
    }

    public function update() {

        $user = $this->get_user();

        /* Check if products feature is enabled */
        if(!settings()->products->products_is_enabled) {
            $this->response_error(l('global.error_message.basic'), 403);
        }

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.products')) {
            $this->response_error(l('global.error_message.team_no_access'), 403);
        }

        $product_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$product_id) {
            $this->response_error(l('global.error_message.not_found'), 404);
        }

        /* Get the product details */
        $product = (new Product())->get_product_by_id($product_id, $user->user_id);

        if(!$product) {
            $this->response_error(l('global.error_message.not_found'), 404);
        }

        /* Get the projects available to the user */
        $projects = (new \SeeGap\Models\Projects())->get_projects_by_user_id($user->user_id);

        $gtin = query_clean($_POST['gtin'] ?? $product->gtin);
        $brand_name = query_clean($_POST['brand_name'] ?? $product->brand_name);
        $product_name = query_clean($_POST['product_name'] ?? $product->product_name);
        $product_description = input_clean($_POST['product_description'] ?? $product->product_description);
        $category = query_clean($_POST['category'] ?? $product->category);
        $subcategory = query_clean($_POST['subcategory'] ?? $product->subcategory);
        $manufacturer = query_clean($_POST['manufacturer'] ?? $product->manufacturer);
        $country_of_origin = query_clean($_POST['country_of_origin'] ?? $product->country_of_origin);
        $net_weight = query_clean($_POST['net_weight'] ?? $product->net_weight);
        $dimensions = query_clean($_POST['dimensions'] ?? $product->dimensions);
        $ingredients = input_clean($_POST['ingredients'] ?? $product->ingredients);
        $nutritional_info = input_clean($_POST['nutritional_info'] ?? $product->nutritional_info);
        $allergen_info = input_clean($_POST['allergen_info'] ?? $product->allergen_info);
        $certifications = input_clean($_POST['certifications'] ?? $product->certifications);
        $packaging_info = input_clean($_POST['packaging_info'] ?? $product->packaging_info);
        $storage_instructions = input_clean($_POST['storage_instructions'] ?? $product->storage_instructions);
        $usage_instructions = input_clean($_POST['usage_instructions'] ?? $product->usage_instructions);
        $target_url = query_clean($_POST['target_url'] ?? $product->target_url);
        $project_id = isset($_POST['project_id']) ? (!empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null) : $product->project_id;
        $gs1_link_id = isset($_POST['gs1_link_id']) ? (!empty($_POST['gs1_link_id']) ? (int) $_POST['gs1_link_id'] : null) : $product->gs1_link_id;
        $is_enabled = isset($_POST['is_enabled']) ? (int) $_POST['is_enabled'] : $product->is_enabled;

        /* Check for any errors */
        $required_fields = ['gtin', 'product_name'];
        foreach($required_fields as $field) {
            if(!isset($$field) || (isset($$field) && empty($$field) && $$field != '0')) {
                $this->response_error(l('global.error_message.empty_field') . ' (' . $field . ')', 400);
            }
        }

        /* Check for duplicate GTIN */
        if(!empty($gtin)) {
            $clean_gtin = preg_replace('/[^0-9]/', '', $gtin);
            if($clean_gtin != $product->gtin && db()->where('gtin', $clean_gtin)->where('user_id', $user->user_id)->has('products')) {
                $this->response_error(l('products.error_message.gtin_exists'), 400);
            }
        }

        /* Check target URL if provided */
        if(!empty($target_url) && !filter_var($target_url, FILTER_VALIDATE_URL)) {
            $this->response_error(l('global.error_message.invalid_url'), 400);
        }

        /* Database query */
        $product_data = [
            'gtin' => $gtin,
            'brand_name' => $brand_name,
            'product_name' => $product_name,
            'product_description' => $product_description,
            'category' => $category,
            'subcategory' => $subcategory,
            'manufacturer' => $manufacturer,
            'country_of_origin' => $country_of_origin,
            'net_weight' => $net_weight,
            'dimensions' => $dimensions,
            'ingredients' => $ingredients,
            'nutritional_info' => $nutritional_info,
            'allergen_info' => $allergen_info,
            'certifications' => $certifications,
            'packaging_info' => $packaging_info,
            'storage_instructions' => $storage_instructions,
            'usage_instructions' => $usage_instructions,
            'target_url' => $target_url,
            'project_id' => $project_id,
            'gs1_link_id' => $gs1_link_id,
            'is_enabled' => $is_enabled
        ];

        $product_model = new Product();
        $updated = $product_model->update_product($product_id, $product_data, $user->user_id);

        if($updated) {
            /* Clear the cache */
            cache()->deleteItem('product?product_id=' . $product_id);
            cache()->deleteItemsByTag('product_id=' . $product_id);

            /* Get the updated product */
            $product = $product_model->get_product_by_id($product_id, $user->user_id);

            $this->response_ok($product);
        } else {
            $this->response_error(l('products.error_message.update_failed'), 500);
        }
    }

    public function delete() {

        $user = $this->get_user();

        /* Check if products feature is enabled */
        if(!settings()->products->products_is_enabled) {
            $this->response_error(l('global.error_message.basic'), 403);
        }

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.products')) {
            $this->response_error(l('global.error_message.team_no_access'), 403);
        }

        $product_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$product_id) {
            $this->response_error(l('global.error_message.not_found'), 404);
        }

        /* Get the product details */
        $product = (new Product())->get_product_by_id($product_id, $user->user_id);

        if(!$product) {
            $this->response_error(l('global.error_message.not_found'), 404);
        }

        /* Delete the resource */
        $deleted = (new Product())->delete($product_id);

        if($deleted) {
            $this->response_ok();
        } else {
            $this->response_error(l('global.error_message.basic'), 500);
        }
    }

}
