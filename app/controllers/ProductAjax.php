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

defined('SEEGAP') || die();

class ProductAjax extends Controller {

    public function index() {

        \SeeGap\Authentication::guard();

        if(!empty($_POST) && (Alerts::has_field_errors() || Alerts::has_errors())) {

            /* Output errors */
            response_json_error(Alerts::output_field_error() . Alerts::output_error());
        }

        die();
    }

    public function is_enabled_toggle() {

        \SeeGap\Authentication::guard();

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.products')) {
            response_json_error(l('global.info_message.team_no_access'));
        }

        if(empty($_POST)) {
            die();
        }

        $product_id = (int) query_clean($_POST['product_id']);

        //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) response_json_error('Please create an account on the demo to test out this function.');

        if(!\SeeGap\Csrf::check()) {
            response_json_error(l('global.error_message.invalid_csrf_token'));
        }

        /* Get the product details */
        if(!$product = db()->where('product_id', $product_id)->where('user_id', $this->user->user_id)->getOne('products')) {
            die();
        }

        $new_is_enabled = (int) !$product->is_enabled;

        /* Database query */
        db()->where('product_id', $product_id)->update('products', [
            'is_enabled' => $new_is_enabled,
            'last_datetime' => \SeeGap\Date::$date
        ]);

        /* Clear the cache */
        cache()->deleteItem('product?product_id=' . $product_id);
        cache()->deleteItemsByTag('product_id=' . $product_id);

        response_json_success('', [
            'is_enabled' => $new_is_enabled
        ]);

    }

    public function duplicate() {

        \SeeGap\Authentication::guard();

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.products')) {
            response_json_error(l('global.info_message.team_no_access'));
        }

        if(empty($_POST)) {
            die();
        }

        $product_id = (int) query_clean($_POST['product_id']);

        //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) response_json_error('Please create an account on the demo to test out this function.');

        if(!\SeeGap\Csrf::check()) {
            response_json_error(l('global.error_message.invalid_csrf_token'));
        }

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `products` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if(($this->user->plan_settings->products_limit ?? -1) != -1 && $total_rows >= ($this->user->plan_settings->products_limit ?? 0)) {
            response_json_error(l('global.info_message.plan_feature_limit'));
        }

        /* Get the product details */
        if(!$product = db()->where('product_id', $product_id)->where('user_id', $this->user->user_id)->getOne('products')) {
            die();
        }

        /* Generate a new unique GTIN by appending a suffix */
        $base_gtin = preg_replace('/[^0-9]/', '', $product->gtin);
        $new_gtin = $base_gtin;
        $suffix = 1;
        
        while(db()->where('gtin', $new_gtin)->where('user_id', $this->user->user_id)->has('products')) {
            $new_gtin = $base_gtin . $suffix;
            $suffix++;
        }

        /* Duplicate the product */
        $product_data = [
            'user_id' => $this->user->user_id,
            'project_id' => $product->project_id,
            'gtin' => $new_gtin,
            'brand_name' => $product->brand_name,
            'product_name' => $product->product_name . ' (Copy)',
            'product_description' => $product->product_description,
            'category' => $product->category,
            'subcategory' => $product->subcategory,
            'manufacturer' => $product->manufacturer,
            'country_of_origin' => $product->country_of_origin,
            'net_weight' => $product->net_weight,
            'dimensions' => $product->dimensions,
            'ingredients' => $product->ingredients,
            'nutritional_info' => $product->nutritional_info,
            'allergen_info' => $product->allergen_info,
            'certifications' => $product->certifications,
            'product_images' => json_decode($product->product_images ?? '[]', true),
            'packaging_info' => $product->packaging_info,
            'storage_instructions' => $product->storage_instructions,
            'usage_instructions' => $product->usage_instructions,
            'target_url' => $product->target_url,
            'gs1_link_id' => null, // Don't duplicate GS1 link
            'settings' => json_decode($product->settings ?? '{}', true),
            'is_enabled' => 0 // Start disabled
        ];

        $product_model = new \SeeGap\Models\Product();
        $new_product_id = $product_model->create_product($product_data);

        if($new_product_id) {
            response_json_success(sprintf(l('global.success_message.create1'), '<strong>' . $product_data['product_name'] . '</strong>'), [
                'url' => url('product-update/' . $new_product_id)
            ]);
        } else {
            response_json_error(l('products.error_message.creation_failed'));
        }

    }

}
