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
use SeeGap\Models\Domain;
use SeeGap\Title;

defined('SEEGAP') || die();

class Products extends Controller {

    public function index() {

        \SeeGap\Authentication::guard();

        /* Check if products feature is enabled */
        if(!settings()->products->products_is_enabled) {
            redirect('dashboard');
        }

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('read.products')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        /* Prepare the filtering system */
        $filters = (new \SeeGap\Filters(['is_enabled', 'project_id', 'category', 'brand_name'], ['gtin', 'product_name', 'brand_name'], ['product_id', 'last_datetime', 'datetime', 'gtin', 'product_name', 'brand_name', 'category']));
        $filters->set_default_order_by('product_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `products` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \SeeGap\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('products?' . $filters->get_get() . '&page=%d')));

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
                `products`.`user_id` = {$this->user->user_id} 
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
        process_export_csv($products, 'include', ['product_id', 'user_id', 'project_id', 'gtin', 'brand_name', 'product_name', 'product_description', 'category', 'subcategory', 'manufacturer', 'country_of_origin', 'net_weight', 'dimensions', 'ingredients', 'nutritional_info', 'allergen_info', 'certifications', 'packaging_info', 'storage_instructions', 'usage_instructions', 'target_url', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('products.title')));
        process_export_json($products, 'include', ['product_id', 'user_id', 'project_id', 'gtin', 'brand_name', 'product_name', 'product_description', 'category', 'subcategory', 'manufacturer', 'country_of_origin', 'net_weight', 'dimensions', 'ingredients', 'nutritional_info', 'allergen_info', 'certifications', 'product_images', 'packaging_info', 'storage_instructions', 'usage_instructions', 'target_url', 'settings', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('products.title')));

        /* Prepare the pagination view */
        $pagination = (new \SeeGap\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Existing projects */
        $projects = (new \SeeGap\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Get unique categories and brands for filtering */
        $product_model = new \SeeGap\Models\Product();
        $categories = $product_model->get_unique_categories($this->user->user_id);
        $brands = $product_model->get_unique_brands($this->user->user_id);

        /* Set a custom title */
        Title::set(l('products.title'));

        /* Prepare the Products Content View */
        $data = [
            'products'          => $products,
            'pagination'        => $pagination,
            'filters'           => $filters,
            'projects'          => $projects,
            'categories'        => $categories,
            'brands'            => $brands,
        ];

        $view = new \SeeGap\View('products/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        \SeeGap\Authentication::guard();

        //SEEGAP:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('products');
        }

        if(empty($_POST['selected'])) {
            redirect('products');
        }

        if(!isset($_POST['type'])) {
            redirect('products');
        }

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            switch($_POST['type']) {
                case 'delete':

                    /* Team checks */
                    if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.products')) {
                        Alerts::add_info(l('global.info_message.team_no_access'));
                        redirect('products');
                    }

                    foreach($_POST['selected'] as $product_id) {
                        if($product = db()->where('product_id', $product_id)->where('user_id', $this->user->user_id)->getOne('products', ['product_id'])) {
                            /* Delete the resource */
                            (new \SeeGap\Models\Product())->delete($product->product_id);
                        }
                    }

                    break;

            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('products');
    }

    public function delete() {
        \SeeGap\Authentication::guard();

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.products')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('products');
        }

        if(empty($_POST)) {
            redirect('products');
        }

        $product_id = (int) query_clean($_POST['product_id']);

        //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('products');
        }

        /* Make sure the product id is created by the logged in user */
        if(!$product = db()->where('product_id', $product_id)->where('user_id', $this->user->user_id)->getOne('products', ['product_id'])) {
            redirect('products');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            (new \SeeGap\Models\Product())->delete($product->product_id);

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.delete2'));

            redirect('products');

        }

        redirect('products');
    }

}
