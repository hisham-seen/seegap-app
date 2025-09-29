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
        $section = isset($this->params[1]) ? query_clean($this->params[1]) : 'general';

        if(!$product_id) {
            redirect('products');
        }

        /* Validate section */
        $valid_sections = ['general', 'gs1-identifiers', 'attributes', 'measurements', 'logistics', 'content', 'digital', 'media'];
        if(!in_array($section, $valid_sections)) {
            $section = 'general';
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
            // Clean and validate all form inputs based on section
            $this->process_form_data($section);

            //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Validate form data based on section */
            $this->validate_form_data($section, $product);

            if(!\SeeGap\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Prepare product data for update */
                $product_data = $this->prepare_product_data($section);

                $product_model = new \SeeGap\Models\Product();
                $updated = $product_model->update_product($product_id, $product_data, $this->user->user_id);

                if($updated) {
                    /* Set a nice success message */
                    $product_name = $_POST['product_name'] ?? $product->product_name;
                    Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $product_name . '</strong>'));

                    /* Clear the cache */
                    cache()->deleteItem('product?product_id=' . $product_id);
                    cache()->deleteItemsByTag('product_id=' . $product_id);

                    redirect('product-update/' . $product_id . '/' . $section);
                } else {
                    Alerts::add_error(l('products.error_message.update_failed'));
                }
            }
        }

        /* Set a custom title */
        Title::set(sprintf(l('products.update.title'), $product->product_name));

        /* Prepare the View Data */
        $data = [
            'product' => $product,
            'projects' => $projects,
            'gs1_links' => $gs1_links,
            'section' => $section,
            'valid_sections' => $valid_sections,
        ];

        /* Secondary Sidebar View */
        $secondary_sidebar_config = [
            'mobile_select_name' => 'product_settings_menu',
            'mobile_select_class' => 'custom-select',
            'desktop_class' => 'product-settings-sidebar',
            'items' => [
                [
                    'type' => 'link',
                    'url' => url('product-update/' . $product->product_id . '/general'),
                    'icon' => 'fas fa-fw fa-sm fa-info-circle mr-2',
                    'label' => l('products.sections.general'),
                    'active' => $section == 'general',
                    'mobile_emoji' => '📋'
                ],
                [
                    'type' => 'link',
                    'url' => url('product-update/' . $product->product_id . '/gs1-identifiers'),
                    'icon' => 'fas fa-fw fa-sm fa-barcode mr-2',
                    'label' => l('products.sections.gs1_identifiers'),
                    'active' => $section == 'gs1-identifiers',
                    'mobile_emoji' => '🏷️'
                ],
                [
                    'type' => 'link',
                    'url' => url('product-update/' . $product->product_id . '/attributes'),
                    'icon' => 'fas fa-fw fa-sm fa-tags mr-2',
                    'label' => l('products.sections.gs1_attributes'),
                    'active' => $section == 'attributes',
                    'mobile_emoji' => '🏭'
                ],
                [
                    'type' => 'divider'
                ],
                [
                    'type' => 'link',
                    'url' => url('product-update/' . $product->product_id . '/measurements'),
                    'icon' => 'fas fa-fw fa-sm fa-ruler-combined mr-2',
                    'label' => l('products.sections.gs1_measurements'),
                    'active' => $section == 'measurements',
                    'mobile_emoji' => '📏'
                ],
                [
                    'type' => 'link',
                    'url' => url('product-update/' . $product->product_id . '/logistics'),
                    'icon' => 'fas fa-fw fa-sm fa-shipping-fast mr-2',
                    'label' => l('products.sections.gs1_logistics'),
                    'active' => $section == 'logistics',
                    'mobile_emoji' => '🚚'
                ],
                [
                    'type' => 'divider'
                ],
                [
                    'type' => 'link',
                    'url' => url('product-update/' . $product->product_id . '/content'),
                    'icon' => 'fas fa-fw fa-sm fa-list-ul mr-2',
                    'label' => l('products.sections.content_compliance'),
                    'active' => $section == 'content',
                    'mobile_emoji' => '📝'
                ],
                [
                    'type' => 'link',
                    'url' => url('product-update/' . $product->product_id . '/digital'),
                    'icon' => 'fas fa-fw fa-sm fa-link mr-2',
                    'label' => l('products.sections.digital_integration'),
                    'active' => $section == 'digital',
                    'mobile_emoji' => '🔗'
                ],
                [
                    'type' => 'link',
                    'url' => url('product-update/' . $product->product_id . '/media'),
                    'icon' => 'fas fa-fw fa-sm fa-images mr-2',
                    'label' => l('products.sections.media_images'),
                    'active' => $section == 'media',
                    'mobile_emoji' => '🖼️'
                ]
            ]
        ];
        
        // Use output buffering to capture the sidebar output
        ob_start();
        $config = $secondary_sidebar_config; // Make config available to the included file
        include THEME_PATH . 'views/partials/product_secondary_sidebar.php';
        $sidebar_content = ob_get_clean();
        
        $this->add_view_content('secondary_sidebar', $sidebar_content);

        /* Main View */
        $view = new \SeeGap\View('product-update/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

    /**
     * Process form data based on section
     */
    private function process_form_data($section) {
        // Common fields across all sections
        $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);
        $_POST['project_id'] = !empty($_POST['project_id']) ? (int) $_POST['project_id'] : null;
        $_POST['gs1_link_id'] = !empty($_POST['gs1_link_id']) ? (int) $_POST['gs1_link_id'] : null;

        switch($section) {
            case 'general':
                $_POST['gtin'] = query_clean($_POST['gtin'] ?? '');
                $_POST['brand_name'] = query_clean($_POST['brand_name'] ?? '');
                $_POST['product_name'] = query_clean($_POST['product_name'] ?? '');
                $_POST['product_description'] = input_clean($_POST['product_description'] ?? '');
                $_POST['category'] = query_clean($_POST['category'] ?? '');
                $_POST['subcategory'] = query_clean($_POST['subcategory'] ?? '');
                $_POST['manufacturer'] = query_clean($_POST['manufacturer'] ?? '');
                $_POST['target_url'] = query_clean($_POST['target_url'] ?? '');
                break;

            case 'gs1-identifiers':
                $_POST['gln'] = query_clean($_POST['gln'] ?? '');
                $_POST['variant'] = query_clean($_POST['variant'] ?? '');
                $_POST['batch_lot'] = query_clean($_POST['batch_lot'] ?? '');
                $_POST['serial'] = query_clean($_POST['serial'] ?? '');
                $_POST['cpid'] = query_clean($_POST['cpid'] ?? '');
                $_POST['additional_id'] = query_clean($_POST['additional_id'] ?? '');
                break;

            case 'attributes':
                $_POST['production_date'] = query_clean($_POST['production_date'] ?? '');
                $_POST['due_date'] = query_clean($_POST['due_date'] ?? '');
                $_POST['packaging_date'] = query_clean($_POST['packaging_date'] ?? '');
                $_POST['best_before_date'] = query_clean($_POST['best_before_date'] ?? '');
                $_POST['sell_by_date'] = query_clean($_POST['sell_by_date'] ?? '');
                $_POST['expiration_date'] = query_clean($_POST['expiration_date'] ?? '');
                $_POST['customer_part_number'] = query_clean($_POST['customer_part_number'] ?? '');
                $_POST['made_to_order_variation'] = query_clean($_POST['made_to_order_variation'] ?? '');
                $_POST['packaging_configuration'] = query_clean($_POST['packaging_configuration'] ?? '');
                $_POST['secondary_serial'] = query_clean($_POST['secondary_serial'] ?? '');
                $_POST['reference_to_source'] = query_clean($_POST['reference_to_source'] ?? '');
                $_POST['global_document_type_id'] = query_clean($_POST['global_document_type_id'] ?? '');
                break;

            case 'measurements':
                $_POST['net_weight_kg'] = query_clean($_POST['net_weight_kg'] ?? '');
                $_POST['length_m'] = query_clean($_POST['length_m'] ?? '');
                $_POST['width_m'] = query_clean($_POST['width_m'] ?? '');
                $_POST['height_m'] = query_clean($_POST['height_m'] ?? '');
                $_POST['area_m2'] = query_clean($_POST['area_m2'] ?? '');
                $_POST['net_volume_l'] = query_clean($_POST['net_volume_l'] ?? '');
                $_POST['gross_weight_kg'] = query_clean($_POST['gross_weight_kg'] ?? '');
                $_POST['logistic_weight_kg'] = query_clean($_POST['logistic_weight_kg'] ?? '');
                $_POST['logistic_length_m'] = query_clean($_POST['logistic_length_m'] ?? '');
                $_POST['logistic_width_m'] = query_clean($_POST['logistic_width_m'] ?? '');
                $_POST['logistic_height_m'] = query_clean($_POST['logistic_height_m'] ?? '');
                $_POST['logistic_area_m2'] = query_clean($_POST['logistic_area_m2'] ?? '');
                $_POST['logistic_volume_l'] = query_clean($_POST['logistic_volume_l'] ?? '');
                break;

            case 'logistics':
                $_POST['ship_to_loc'] = query_clean($_POST['ship_to_loc'] ?? '');
                $_POST['bill_to'] = query_clean($_POST['bill_to'] ?? '');
                $_POST['purchased_from'] = query_clean($_POST['purchased_from'] ?? '');
                $_POST['ship_for_loc'] = query_clean($_POST['ship_for_loc'] ?? '');
                $_POST['phy_loc'] = query_clean($_POST['phy_loc'] ?? '');
                $_POST['rti_loc'] = query_clean($_POST['rti_loc'] ?? '');
                $_POST['ship_to_post'] = query_clean($_POST['ship_to_post'] ?? '');
                $_POST['ship_to_post_iso'] = query_clean($_POST['ship_to_post_iso'] ?? '');
                $_POST['origin'] = query_clean($_POST['origin'] ?? '');
                $_POST['country_initial_process'] = query_clean($_POST['country_initial_process'] ?? '');
                $_POST['country_process'] = query_clean($_POST['country_process'] ?? '');
                $_POST['country_disassembly'] = query_clean($_POST['country_disassembly'] ?? '');
                $_POST['country_full_process'] = query_clean($_POST['country_full_process'] ?? '');
                break;

            case 'content':
                $_POST['product_description'] = input_clean($_POST['product_description'] ?? '');
                $_POST['ingredients'] = input_clean($_POST['ingredients'] ?? '');
                $_POST['allergen_info'] = input_clean($_POST['allergen_info'] ?? '');
                $_POST['nutritional_info'] = input_clean($_POST['nutritional_info'] ?? '');
                $_POST['organic_certification'] = query_clean($_POST['organic_certification'] ?? '');
                $_POST['fair_trade_certification'] = query_clean($_POST['fair_trade_certification'] ?? '');
                $_POST['halal_certified'] = (int) isset($_POST['halal_certified']);
                $_POST['kosher_certified'] = (int) isset($_POST['kosher_certified']);
                $_POST['gluten_free'] = (int) isset($_POST['gluten_free']);
                $_POST['vegan'] = (int) isset($_POST['vegan']);
                $_POST['vegetarian'] = (int) isset($_POST['vegetarian']);
                $_POST['non_gmo'] = (int) isset($_POST['non_gmo']);
                $_POST['usage_instructions'] = input_clean($_POST['usage_instructions'] ?? '');
                $_POST['care_instructions'] = input_clean($_POST['care_instructions'] ?? '');
                $_POST['storage_instructions'] = input_clean($_POST['storage_instructions'] ?? '');
                $_POST['warning_info'] = input_clean($_POST['warning_info'] ?? '');
                break;

            case 'digital':
                $_POST['product_url'] = query_clean($_POST['product_url'] ?? '');
                $_POST['manufacturer_url'] = query_clean($_POST['manufacturer_url'] ?? '');
                $_POST['product_info_url'] = query_clean($_POST['product_info_url'] ?? '');
                $_POST['sustainability_url'] = query_clean($_POST['sustainability_url'] ?? '');
                $_POST['recycling_url'] = query_clean($_POST['recycling_url'] ?? '');
                $_POST['safety_url'] = query_clean($_POST['safety_url'] ?? '');
                $_POST['facebook_url'] = query_clean($_POST['facebook_url'] ?? '');
                $_POST['instagram_url'] = query_clean($_POST['instagram_url'] ?? '');
                $_POST['twitter_url'] = query_clean($_POST['twitter_url'] ?? '');
                $_POST['youtube_url'] = query_clean($_POST['youtube_url'] ?? '');
                $_POST['purchase_url'] = query_clean($_POST['purchase_url'] ?? '');
                $_POST['amazon_asin'] = query_clean($_POST['amazon_asin'] ?? '');
                $_POST['ebay_item_id'] = query_clean($_POST['ebay_item_id'] ?? '');
                $_POST['price_comparison_url'] = query_clean($_POST['price_comparison_url'] ?? '');
                $_POST['manual_url'] = query_clean($_POST['manual_url'] ?? '');
                $_POST['support_url'] = query_clean($_POST['support_url'] ?? '');
                $_POST['faq_url'] = query_clean($_POST['faq_url'] ?? '');
                $_POST['tutorial_url'] = query_clean($_POST['tutorial_url'] ?? '');
                $_POST['api_endpoint'] = query_clean($_POST['api_endpoint'] ?? '');
                $_POST['webhook_url'] = query_clean($_POST['webhook_url'] ?? '');
                break;

            case 'media':
                $_POST['youtube_video_id'] = query_clean($_POST['youtube_video_id'] ?? '');
                $_POST['image_quality'] = query_clean($_POST['image_quality'] ?? 'high');
                $_POST['auto_resize_images'] = (int) isset($_POST['auto_resize_images']);
                $_POST['generate_thumbnails'] = (int) isset($_POST['generate_thumbnails']);
                $_POST['watermark_images'] = (int) isset($_POST['watermark_images']);
                break;
        }
    }

    /**
     * Validate form data based on section
     */
    private function validate_form_data($section, $product) {
        switch($section) {
            case 'general':
                // Required fields for general section
                $required_fields = ['gtin', 'product_name'];
                foreach($required_fields as $field) {
                    if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                        Alerts::add_field_error($field, l('global.error_message.empty_field'));
                    }
                }

                // Check for duplicate GTIN
                if(!empty($_POST['gtin'])) {
                    $gtin = preg_replace('/[^0-9]/', '', $_POST['gtin']);
                    if($gtin != $product->gtin && db()->where('gtin', $gtin)->where('user_id', $this->user->user_id)->has('products')) {
                        Alerts::add_field_error('gtin', l('products.error_message.gtin_exists'));
                    }
                }

                // Validate target URL
                if(!empty($_POST['target_url']) && !filter_var($_POST['target_url'], FILTER_VALIDATE_URL)) {
                    Alerts::add_field_error('target_url', l('global.error_message.invalid_url'));
                }
                break;

            case 'measurements':
                // Validate numeric fields
                $numeric_fields = ['net_weight_kg', 'length_m', 'width_m', 'height_m', 'area_m2', 'net_volume_l', 'gross_weight_kg', 'logistic_weight_kg', 'logistic_length_m', 'logistic_width_m', 'logistic_height_m', 'logistic_area_m2', 'logistic_volume_l'];
                foreach($numeric_fields as $field) {
                    if(!empty($_POST[$field]) && !is_numeric($_POST[$field])) {
                        Alerts::add_field_error($field, l('global.error_message.invalid_number'));
                    }
                }
                break;

            case 'digital':
                // Validate URLs
                $url_fields = ['product_url', 'manufacturer_url', 'product_info_url', 'sustainability_url', 'recycling_url', 'safety_url', 'facebook_url', 'instagram_url', 'twitter_url', 'youtube_url', 'purchase_url', 'price_comparison_url', 'manual_url', 'support_url', 'faq_url', 'tutorial_url', 'api_endpoint', 'webhook_url'];
                foreach($url_fields as $field) {
                    if(!empty($_POST[$field]) && !filter_var($_POST[$field], FILTER_VALIDATE_URL)) {
                        Alerts::add_field_error($field, l('global.error_message.invalid_url'));
                    }
                }
                break;
        }
    }

    /**
     * Prepare product data for database update
     */
    private function prepare_product_data($section) {
        $product_data = [];

        // Always include common fields
        $product_data['is_enabled'] = $_POST['is_enabled'];
        $product_data['project_id'] = $_POST['project_id'];
        $product_data['gs1_link_id'] = $_POST['gs1_link_id'];

        switch($section) {
            case 'general':
                $product_data = array_merge($product_data, [
                    'gtin' => $_POST['gtin'],
                    'brand_name' => $_POST['brand_name'],
                    'product_name' => $_POST['product_name'],
                    'product_description' => $_POST['product_description'],
                    'category' => $_POST['category'],
                    'subcategory' => $_POST['subcategory'],
                    'manufacturer' => $_POST['manufacturer'],
                    'target_url' => $_POST['target_url']
                ]);
                break;

            case 'gs1-identifiers':
                $product_data = array_merge($product_data, [
                    'gln' => $_POST['gln'],
                    'variant' => $_POST['variant'],
                    'batch_lot' => $_POST['batch_lot'],
                    'serial' => $_POST['serial'],
                    'cpid' => $_POST['cpid'],
                    'additional_id' => $_POST['additional_id']
                ]);
                break;

            case 'attributes':
                $product_data = array_merge($product_data, [
                    'production_date' => $_POST['production_date'],
                    'due_date' => $_POST['due_date'],
                    'packaging_date' => $_POST['packaging_date'],
                    'best_before_date' => $_POST['best_before_date'],
                    'sell_by_date' => $_POST['sell_by_date'],
                    'expiration_date' => $_POST['expiration_date'],
                    'customer_part_number' => $_POST['customer_part_number'],
                    'made_to_order_variation' => $_POST['made_to_order_variation'],
                    'packaging_configuration' => $_POST['packaging_configuration'],
                    'secondary_serial' => $_POST['secondary_serial'],
                    'reference_to_source' => $_POST['reference_to_source'],
                    'global_document_type_id' => $_POST['global_document_type_id']
                ]);
                break;

            case 'measurements':
                $product_data = array_merge($product_data, [
                    'net_weight_kg' => $_POST['net_weight_kg'],
                    'length_m' => $_POST['length_m'],
                    'width_m' => $_POST['width_m'],
                    'height_m' => $_POST['height_m'],
                    'area_m2' => $_POST['area_m2'],
                    'net_volume_l' => $_POST['net_volume_l'],
                    'gross_weight_kg' => $_POST['gross_weight_kg'],
                    'logistic_weight_kg' => $_POST['logistic_weight_kg'],
                    'logistic_length_m' => $_POST['logistic_length_m'],
                    'logistic_width_m' => $_POST['logistic_width_m'],
                    'logistic_height_m' => $_POST['logistic_height_m'],
                    'logistic_area_m2' => $_POST['logistic_area_m2'],
                    'logistic_volume_l' => $_POST['logistic_volume_l']
                ]);
                break;

            case 'logistics':
                $product_data = array_merge($product_data, [
                    'ship_to_loc' => $_POST['ship_to_loc'],
                    'bill_to' => $_POST['bill_to'],
                    'purchased_from' => $_POST['purchased_from'],
                    'ship_for_loc' => $_POST['ship_for_loc'],
                    'phy_loc' => $_POST['phy_loc'],
                    'rti_loc' => $_POST['rti_loc'],
                    'ship_to_post' => $_POST['ship_to_post'],
                    'ship_to_post_iso' => $_POST['ship_to_post_iso'],
                    'origin' => $_POST['origin'],
                    'country_initial_process' => $_POST['country_initial_process'],
                    'country_process' => $_POST['country_process'],
                    'country_disassembly' => $_POST['country_disassembly'],
                    'country_full_process' => $_POST['country_full_process']
                ]);
                break;

            case 'content':
                $product_data = array_merge($product_data, [
                    'product_description' => $_POST['product_description'],
                    'ingredients' => $_POST['ingredients'],
                    'allergen_info' => $_POST['allergen_info'],
                    'nutritional_info' => $_POST['nutritional_info'],
                    'organic_certification' => $_POST['organic_certification'],
                    'fair_trade_certification' => $_POST['fair_trade_certification'],
                    'halal_certified' => $_POST['halal_certified'],
                    'kosher_certified' => $_POST['kosher_certified'],
                    'gluten_free' => $_POST['gluten_free'],
                    'vegan' => $_POST['vegan'],
                    'vegetarian' => $_POST['vegetarian'],
                    'non_gmo' => $_POST['non_gmo'],
                    'usage_instructions' => $_POST['usage_instructions'],
                    'care_instructions' => $_POST['care_instructions'],
                    'storage_instructions' => $_POST['storage_instructions'],
                    'warning_info' => $_POST['warning_info']
                ]);
                break;

            case 'digital':
                $product_data = array_merge($product_data, [
                    'product_url' => $_POST['product_url'],
                    'manufacturer_url' => $_POST['manufacturer_url'],
                    'product_info_url' => $_POST['product_info_url'],
                    'sustainability_url' => $_POST['sustainability_url'],
                    'recycling_url' => $_POST['recycling_url'],
                    'safety_url' => $_POST['safety_url'],
                    'facebook_url' => $_POST['facebook_url'],
                    'instagram_url' => $_POST['instagram_url'],
                    'twitter_url' => $_POST['twitter_url'],
                    'youtube_url' => $_POST['youtube_url'],
                    'purchase_url' => $_POST['purchase_url'],
                    'amazon_asin' => $_POST['amazon_asin'],
                    'ebay_item_id' => $_POST['ebay_item_id'],
                    'price_comparison_url' => $_POST['price_comparison_url'],
                    'manual_url' => $_POST['manual_url'],
                    'support_url' => $_POST['support_url'],
                    'faq_url' => $_POST['faq_url'],
                    'tutorial_url' => $_POST['tutorial_url'],
                    'api_endpoint' => $_POST['api_endpoint'],
                    'webhook_url' => $_POST['webhook_url']
                ]);
                break;

            case 'media':
                $product_data = array_merge($product_data, [
                    'youtube_video_id' => $_POST['youtube_video_id'],
                    'image_quality' => $_POST['image_quality'],
                    'auto_resize_images' => $_POST['auto_resize_images'],
                    'generate_thumbnails' => $_POST['generate_thumbnails'],
                    'watermark_images' => $_POST['watermark_images']
                ]);
                break;
        }

        return $product_data;
    }

}
