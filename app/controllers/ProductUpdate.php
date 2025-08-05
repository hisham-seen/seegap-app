<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers;

use SeeGap\Alerts;
use SeeGap\Title;

defined('SEEGAP') || die();

class ProductUpdate extends Controller {

    public function index() {

        \SeeGap\Authentication::guard();

        /* Check if products feature is enabled */
        if(!settings()->products->products_is_enabled) {
            redirect('dashboard');
        }

        $product_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$product_id) {
            redirect('products');
        }

        /* Get the product details */
        $product = (new \SeeGap\Models\Product())->get_product_by_id($product_id, $this->user->user_id);

        if(!$product) {
            redirect('products');
        }

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.products')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('products');
        }

        /* Existing projects */
        $projects = (new \SeeGap\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Get existing GS1 links for linking */
        $gs1_links = [];
        if(settings()->gs1_links->gs1_links_is_enabled) {
            $gs1_links_result = database()->query("SELECT `gs1_link_id`, `gtin`, `title` FROM `gs1_links` WHERE `user_id` = {$this->user->user_id} ORDER BY `gs1_link_id` DESC");
            while($row = $gs1_links_result->fetch_object()) {
                $gs1_links[] = $row;
            }
        }

        if(!empty($_POST)) {
            $_POST['gtin'] = query_clean($_POST['gtin']);
            $_POST['brand_name'] = query_clean($_POST['brand_name'] ?? '');
            $_POST['product_name'] = query_clean($_POST['product_name']);
            $_POST['product_description'] = input_clean($_POST['product_description'] ?? '');
            $_POST['category'] = query_clean($_POST['category'] ?? '');
            $_POST['subcategory'] = query_clean($_POST['subcategory'] ?? '');
            $_POST['manufacturer'] = query_clean($_POST['manufacturer'] ?? '');
            $_POST['country_of_origin'] = query_clean($_POST['country_of_origin'] ?? '');
            $_POST['net_weight'] = query_clean($_POST['net_weight'] ?? '');
            $_POST['dimensions'] = query_clean($_POST['dimensions'] ?? '');
            $_POST['ingredients'] = input_clean($_POST['ingredients'] ?? '');
            $_POST['nutritional_info'] = input_clean($_POST['nutritional_info'] ?? '');
            $_POST['allergen_info'] = input_clean($_POST['allergen_info'] ?? '');
            $_POST['certifications'] = input_clean($_POST['certifications'] ?? '');
            $_POST['packaging_info'] = input_clean($_POST['packaging_info'] ?? '');
            $_POST['storage_instructions'] = input_clean($_POST['storage_instructions'] ?? '');
            $_POST['usage_instructions'] = input_clean($_POST['usage_instructions'] ?? '');
            $_POST['target_url'] = query_clean($_POST['target_url'] ?? '');
            $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;
            $_POST['gs1_link_id'] = !empty($_POST['gs1_link_id']) ? (int) $_POST['gs1_link_id'] : null;
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

            //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['gtin', 'product_name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\SeeGap\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Check for duplicate GTIN */
            if(!empty($_POST['gtin'])) {
                $gtin = preg_replace('/[^0-9]/', '', $_POST['gtin']);
                if($gtin != $product->gtin && db()->where('gtin', $gtin)->where('user_id', $this->user->user_id)->has('products')) {
                    Alerts::add_field_error('gtin', l('products.error_message.gtin_exists'));
                }
            }

            /* Check target URL if provided */
            if(!empty($_POST['target_url']) && !filter_var($_POST['target_url'], FILTER_VALIDATE_URL)) {
                Alerts::add_field_error('target_url', l('global.error_message.invalid_url'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                $product_data = [
                    'gtin' => $_POST['gtin'],
                    'brand_name' => $_POST['brand_name'],
                    'product_name' => $_POST['product_name'],
                    'product_description' => $_POST['product_description'],
                    'category' => $_POST['category'],
                    'subcategory' => $_POST['subcategory'],
                    'manufacturer' => $_POST['manufacturer'],
                    'country_of_origin' => $_POST['country_of_origin'],
                    'net_weight' => $_POST['net_weight'],
                    'dimensions' => $_POST['dimensions'],
                    'ingredients' => $_POST['ingredients'],
                    'nutritional_info' => $_POST['nutritional_info'],
                    'allergen_info' => $_POST['allergen_info'],
                    'certifications' => $_POST['certifications'],
                    'packaging_info' => $_POST['packaging_info'],
                    'storage_instructions' => $_POST['storage_instructions'],
                    'usage_instructions' => $_POST['usage_instructions'],
                    'target_url' => $_POST['target_url'],
                    'project_id' => $_POST['project_id'],
                    'gs1_link_id' => $_POST['gs1_link_id'],
                    'is_enabled' => $_POST['is_enabled']
                ];

                $product_model = new \SeeGap\Models\Product();
                $updated = $product_model->update_product($product_id, $product_data, $this->user->user_id);

                if($updated) {
                    /* Set a nice success message */
                    Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['product_name'] . '</strong>'));

                    /* Send webhook notification if needed */
                    \SeeGap\Webhooks::send_webhook('product_update', [
                        'user_id' => $this->user->user_id,
                        'product_id' => $product_id,
                        'gtin' => $_POST['gtin'],
                        'product_name' => $_POST['product_name']
                    ]);

                    /* Clear the cache */
                    cache()->deleteItem('product?product_id=' . $product_id);
                    cache()->deleteItemsByTag('product_id=' . $product_id);

                    redirect('product-update/' . $product_id);
                } else {
                    Alerts::add_error(l('products.error_message.update_failed'));
                }
            }
        }

        /* Set a custom title */
        Title::set(sprintf(l('products.update.title'), $product->product_name));

        /* Prepare the View */
        $data = [
            'product' => $product,
            'projects' => $projects,
            'gs1_links' => $gs1_links,
        ];

        $view = new \SeeGap\View('product-update/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

}
