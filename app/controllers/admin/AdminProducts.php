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

class AdminProducts extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \SeeGap\Filters(['is_enabled', 'user_id'], ['gtin', 'product_name', 'brand_name'], ['product_id', 'last_datetime', 'datetime', 'gtin', 'product_name', 'brand_name', 'category']));
        $filters->set_default_order_by('product_id', settings()->main->default_order_type);
        $filters->set_default_results_per_page(settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `products` WHERE 1=1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \SeeGap\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/products?' . $filters->get_get() . '&page=%d')));

        /* Get the products list */
        $products_result = database()->query("
            SELECT 
                `products`.*, 
                `users`.`name` as `user_name`,
                `users`.`email` as `user_email`,
                `projects`.`name` as `project_name`, 
                `projects`.`color` as `project_color`
            FROM 
                `products`
            LEFT JOIN 
                `users` ON `products`.`user_id` = `users`.`user_id`
            LEFT JOIN 
                `projects` ON `products`.`project_id` = `projects`.`project_id`
            WHERE 
                1=1 
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

        /* Export handler */
        process_export_csv($products, 'include', ['product_id', 'user_id', 'project_id', 'gtin', 'brand_name', 'product_name', 'product_description', 'category', 'subcategory', 'manufacturer', 'country_of_origin', 'net_weight', 'dimensions', 'ingredients', 'nutritional_info', 'allergen_info', 'certifications', 'packaging_info', 'storage_instructions', 'usage_instructions', 'target_url', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('admin_products.title')));
        process_export_json($products, 'include', ['product_id', 'user_id', 'project_id', 'gtin', 'brand_name', 'product_name', 'product_description', 'category', 'subcategory', 'manufacturer', 'country_of_origin', 'net_weight', 'dimensions', 'ingredients', 'nutritional_info', 'allergen_info', 'certifications', 'product_images', 'packaging_info', 'storage_instructions', 'usage_instructions', 'target_url', 'settings', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('admin_products.title')));

        /* Prepare the pagination view */
        $pagination = (new \SeeGap\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Set a custom title */
        Title::set(l('admin_products.title'));

        /* Main View */
        $data = [
            'products' => $products,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \SeeGap\View('admin/products/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/products');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/products');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/products');
        }

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $product_id) {
                        /* Delete the resource */
                        (new \SeeGap\Models\Product())->delete($product_id);
                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/products');
    }

    public function delete() {

        if(empty($_POST)) {
            redirect('admin/products');
        }

        $product_id = (int) query_clean($_POST['product_id']);

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('admin/products');
        }

        /* Make sure the product exists */
        if(!$product = db()->where('product_id', $product_id)->getOne('products', ['product_id', 'product_name'])) {
            redirect('admin/products');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            (new \SeeGap\Models\Product())->delete($product->product_id);

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.delete2'));

            redirect('admin/products');

        }

        redirect('admin/products');
    }

}
