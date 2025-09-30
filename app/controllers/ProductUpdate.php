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
        $valid_sections = ['general', 'gs1-identifiers', 'attributes', 'measurements', 'logistics', 'content', 'digital', 'media', 'gs1-digital-link', 'gs1-default-page-design', 'gs1-digital-passport'];
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
            // Clear any existing field errors from previous submissions
            Alerts::clear_field_errors();
            
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

                    /* Handle AJAX requests */
                    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        header('Content-Type: application/json');
                        echo json_encode([
                            'status' => 'success',
                            'message' => sprintf(l('global.success_message.update1'), $product_name)
                        ]);
                        exit;
                    }

                    redirect('product-update/' . $product_id . '/' . $section);
                } else {
                    Alerts::add_error(l('products.error_message.update_failed'));
                    
                    /* Handle AJAX error response */
                    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        header('Content-Type: application/json');
                        echo json_encode([
                            'status' => 'error',
                            'message' => l('products.error_message.update_failed')
                        ]);
                        exit;
                    }
                }
            } else {
                /* Handle AJAX validation error response */
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    $errors = [];
                    
                    // Get field errors
                    if(Alerts::has_field_errors()) {
                        $field_errors = Alerts::get_field_errors();
                        foreach($field_errors as $field => $error) {
                            $errors[] = $field . ': ' . $error;
                        }
                    }
                    
                    // Get general errors
                    if(Alerts::has_errors()) {
                        $general_errors = Alerts::get_errors();
                        $errors = array_merge($errors, $general_errors);
                    }
                    
                    echo json_encode([
                        'status' => 'error',
                        'message' => implode(', ', $errors)
                    ]);
                    exit;
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
                ],
                [
                    'type' => 'divider'
                ],
                [
                    'type' => 'link',
                    'url' => url('product-update/' . $product->product_id . '/gs1-digital-link'),
                    'icon' => 'fas fa-fw fa-sm fa-qrcode mr-2',
                    'label' => l('products.sections.gs1_digital_link'),
                    'active' => $section == 'gs1-digital-link',
                    'mobile_emoji' => '🔗'
                ],
                [
                    'type' => 'link',
                    'url' => url('product-update/' . $product->product_id . '/gs1-default-page-design'),
                    'icon' => 'fas fa-fw fa-sm fa-palette mr-2',
                    'label' => l('products.sections.gs1_default_page_design'),
                    'active' => $section == 'gs1-default-page-design',
                    'mobile_emoji' => '🎨'
                ],
                [
                    'type' => 'link',
                    'url' => url('product-update/' . $product->product_id . '/gs1-digital-passport'),
                    'icon' => 'fas fa-fw fa-sm fa-passport mr-2',
                    'label' => l('products.sections.gs1_digital_passport'),
                    'active' => $section == 'gs1-digital-passport',
                    'mobile_emoji' => '📋'
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
        // Common fields across all sections (but not is_enabled - that's section-specific)
        $_POST['project_id'] = !empty($_POST['project_id']) ? (int) $_POST['project_id'] : null;
        $_POST['gs1_link_id'] = !empty($_POST['gs1_link_id']) ? (int) $_POST['gs1_link_id'] : null;

        switch($section) {
            case 'general':
                // Process is_enabled for general section only
                $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);
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
                $_POST['batch_lot_number'] = query_clean($_POST['batch_lot_number'] ?? '');
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

            case 'gs1-digital-link':
                // Process is_enabled for gs1-digital-link section
                $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);
                $_POST['gs1_link_enabled'] = (int) isset($_POST['gs1_link_enabled']);
                $_POST['gs1_link_type'] = query_clean($_POST['gs1_link_type'] ?? 'custom');
                $_POST['gs1_target_url'] = query_clean($_POST['gs1_target_url'] ?? '');
                $_POST['gs1_existing_content_id'] = !empty($_POST['gs1_existing_content_id']) ? (int) $_POST['gs1_existing_content_id'] : null;
                $_POST['gs1_link_title'] = query_clean($_POST['gs1_link_title'] ?? '');
                $_POST['gs1_link_description'] = input_clean($_POST['gs1_link_description'] ?? '');
                $_POST['auto_generate_qr'] = (int) isset($_POST['auto_generate_qr']);
                $_POST['qr_code_size'] = query_clean($_POST['qr_code_size'] ?? '500');
                $_POST['qr_code_ecc'] = query_clean($_POST['qr_code_ecc'] ?? 'M');
                $_POST['gs1_schedule_enabled'] = (int) isset($_POST['gs1_schedule_enabled']);
                $_POST['gs1_start_date'] = query_clean($_POST['gs1_start_date'] ?? '');
                $_POST['gs1_end_date'] = query_clean($_POST['gs1_end_date'] ?? '');
                $_POST['gs1_clicks_limit'] = !empty($_POST['gs1_clicks_limit']) ? (int) $_POST['gs1_clicks_limit'] : null;
                $_POST['gs1_expiration_url'] = query_clean($_POST['gs1_expiration_url'] ?? '');
                $_POST['gs1_utm_source'] = query_clean($_POST['gs1_utm_source'] ?? '');
                $_POST['gs1_utm_medium'] = query_clean($_POST['gs1_utm_medium'] ?? '');
                $_POST['gs1_utm_campaign'] = query_clean($_POST['gs1_utm_campaign'] ?? '');
                break;

            case 'gs1-default-page-design':
                // GS1 Default Page Design Configuration - does NOT process is_enabled
                // This section only updates template settings, not product status
                $_POST['gs1_template'] = query_clean($_POST['gs1_template'] ?? 'modern');
                $_POST['gs1_theme'] = query_clean($_POST['gs1_theme'] ?? 'blue');
                $_POST['gs1_enabled_datapoints'] = $_POST['gs1_enabled_datapoints'] ?? [];
                break;

            case 'gs1-digital-passport':
                // Sustainability Information
                $_POST['carbon_footprint'] = !empty($_POST['carbon_footprint']) ? (float) $_POST['carbon_footprint'] : null;
                $_POST['water_usage'] = !empty($_POST['water_usage']) ? (float) $_POST['water_usage'] : null;
                $_POST['renewable_energy_percentage'] = !empty($_POST['renewable_energy_percentage']) ? (int) $_POST['renewable_energy_percentage'] : null;
                $_POST['recyclability_score'] = query_clean($_POST['recyclability_score'] ?? '');
                $_POST['sustainability_certifications'] = input_clean($_POST['sustainability_certifications'] ?? '');
                
                // Supply Chain & Traceability
                $_POST['supply_chain_transparency'] = query_clean($_POST['supply_chain_transparency'] ?? '');
                $_POST['ethical_sourcing'] = query_clean($_POST['ethical_sourcing'] ?? '');
                $_POST['key_suppliers'] = input_clean($_POST['key_suppliers'] ?? '');
                $_POST['blockchain_verified'] = (int) isset($_POST['blockchain_verified']);
                
                // Compliance & Safety
                $_POST['regulatory_compliance'] = input_clean($_POST['regulatory_compliance'] ?? '');
                $_POST['safety_standards'] = input_clean($_POST['safety_standards'] ?? '');
                $_POST['quality_certifications'] = input_clean($_POST['quality_certifications'] ?? '');
                
                // Product Lifecycle
                $_POST['lifecycle_stage'] = query_clean($_POST['lifecycle_stage'] ?? '');
                $_POST['expected_lifespan'] = !empty($_POST['expected_lifespan']) ? (int) $_POST['expected_lifespan'] : null;
                $_POST['lifespan_unit'] = query_clean($_POST['lifespan_unit'] ?? 'years');
                $_POST['end_of_life_instructions'] = input_clean($_POST['end_of_life_instructions'] ?? '');
                
                // Digital Passport Settings
                $_POST['passport_public'] = (int) isset($_POST['passport_public']);
                $_POST['passport_seo'] = (int) isset($_POST['passport_seo']);
                $_POST['passport_last_updated'] = !empty($_POST['passport_last_updated']) ? $_POST['passport_last_updated'] : null;
                $_POST['data_verification_status'] = query_clean($_POST['data_verification_status'] ?? 'unverified');
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

            case 'gs1-digital-link':
                // Validate GS1 Digital Link URLs
                $gs1_url_fields = ['gs1_target_url', 'gs1_expiration_url'];
                foreach($gs1_url_fields as $field) {
                    if(!empty($_POST[$field]) && !filter_var($_POST[$field], FILTER_VALIDATE_URL)) {
                        Alerts::add_field_error($field, l('global.error_message.invalid_url'));
                    }
                }

                // Validate required fields when GS1 link is enabled
                if(!empty($_POST['gs1_link_enabled'])) {
                    if($_POST['gs1_link_type'] === 'custom' && empty($_POST['gs1_target_url'])) {
                        Alerts::add_field_error('gs1_target_url', l('global.error_message.empty_field'));
                    }
                    
                    if($_POST['gs1_link_type'] !== 'custom' && $_POST['gs1_link_type'] !== 'default' && empty($_POST['gs1_existing_content_id'])) {
                        Alerts::add_field_error('gs1_existing_content_id', l('global.error_message.empty_field'));
                    }
                }

                // Validate date fields
                if(!empty($_POST['gs1_start_date']) && !empty($_POST['gs1_end_date'])) {
                    $start_date = strtotime($_POST['gs1_start_date']);
                    $end_date = strtotime($_POST['gs1_end_date']);
                    
                    if($start_date && $end_date && $start_date >= $end_date) {
                        Alerts::add_field_error('gs1_end_date', 'End date must be after start date.');
                    }
                }

                // Validate clicks limit
                if(!empty($_POST['gs1_clicks_limit']) && (!is_numeric($_POST['gs1_clicks_limit']) || (int)$_POST['gs1_clicks_limit'] < 1)) {
                    Alerts::add_field_error('gs1_clicks_limit', 'Clicks limit must be a positive number.');
                }

                // Validate QR code size
                if(!empty($_POST['qr_code_size']) && !in_array($_POST['qr_code_size'], ['200', '300', '500', '800', '1000'])) {
                    Alerts::add_field_error('qr_code_size', 'Invalid QR code size selected.');
                }

                // Validate QR code error correction level
                if(!empty($_POST['qr_code_ecc']) && !in_array($_POST['qr_code_ecc'], ['L', 'M', 'Q', 'H'])) {
                    Alerts::add_field_error('qr_code_ecc', 'Invalid QR code error correction level.');
                }
                break;

            case 'gs1-default-page-design':
                // Validate template selection
                $valid_templates = ['modern', 'classic', 'minimal', 'detailed'];
                if(!empty($_POST['gs1_template']) && !in_array($_POST['gs1_template'], $valid_templates)) {
                    Alerts::add_field_error('gs1_template', 'Invalid template selected.');
                }
                
                // Validate theme selection
                $valid_themes = ['blue', 'green', 'purple', 'orange', 'red', 'gray'];
                if(!empty($_POST['gs1_theme']) && !in_array($_POST['gs1_theme'], $valid_themes)) {
                    Alerts::add_field_error('gs1_theme', 'Invalid theme selected.');
                }
                break;

            case 'gs1-digital-passport':
                // Validate numeric fields
                if(!empty($_POST['carbon_footprint']) && !is_numeric($_POST['carbon_footprint'])) {
                    Alerts::add_field_error('carbon_footprint', 'Carbon footprint must be a valid number.');
                }
                
                if(!empty($_POST['water_usage']) && !is_numeric($_POST['water_usage'])) {
                    Alerts::add_field_error('water_usage', 'Water usage must be a valid number.');
                }
                
                if(!empty($_POST['renewable_energy_percentage']) && (!is_numeric($_POST['renewable_energy_percentage']) || (int)$_POST['renewable_energy_percentage'] < 0 || (int)$_POST['renewable_energy_percentage'] > 100)) {
                    Alerts::add_field_error('renewable_energy_percentage', 'Renewable energy percentage must be between 0 and 100.');
                }
                
                if(!empty($_POST['expected_lifespan']) && (!is_numeric($_POST['expected_lifespan']) || (int)$_POST['expected_lifespan'] < 1)) {
                    Alerts::add_field_error('expected_lifespan', 'Expected lifespan must be a positive number.');
                }
                
                // Validate lifecycle stage options
                if(!empty($_POST['lifecycle_stage']) && !in_array($_POST['lifecycle_stage'], ['development', 'production', 'active', 'mature', 'declining', 'discontinued'])) {
                    Alerts::add_field_error('lifecycle_stage', 'Invalid lifecycle stage selected.');
                }
                
                // Validate data verification status
                if(!empty($_POST['data_verification_status']) && !in_array($_POST['data_verification_status'], ['unverified', 'self_verified', 'third_party_verified', 'certified'])) {
                    Alerts::add_field_error('data_verification_status', 'Invalid data verification status.');
                }
                break;
        }
    }

    /**
     * Prepare product data for database update
     */
    private function prepare_product_data($section) {
        $product_data = [];

        // Only include is_enabled if it was processed for this section
        if (isset($_POST['is_enabled'])) {
            $product_data['is_enabled'] = $_POST['is_enabled'];
        }
        
        // Always include common fields
        $product_data['project_id'] = $_POST['project_id'];
        $product_data['gs1_link_id'] = $_POST['gs1_link_id'];
        
        // Get current product for settings merging
        $product_id = isset($this->params[0]) ? (int) $this->params[0] : null;
        $product = null;
        if ($product_id) {
            $product = (new \SeeGap\Models\Product())->get_product_by_id($product_id, $this->user->user_id);
        }

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
                    'batch_lot_number' => $_POST['batch_lot_number'],
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

            case 'gs1-digital-link':
                // For GS1 Digital Link, we need to handle all the GS1 fields
                $product_data = array_merge($product_data, [
                    'gs1_link_enabled' => $_POST['gs1_link_enabled'],
                    'gs1_link_type' => $_POST['gs1_link_type'],
                    'gs1_target_url' => $_POST['gs1_target_url'],
                    'gs1_existing_content_id' => $_POST['gs1_existing_content_id'],
                    'gs1_link_title' => $_POST['gs1_link_title'],
                    'gs1_link_description' => $_POST['gs1_link_description'],
                    'auto_generate_qr' => $_POST['auto_generate_qr'],
                    'qr_code_size' => $_POST['qr_code_size'],
                    'qr_code_ecc' => $_POST['qr_code_ecc'],
                    'gs1_schedule_enabled' => $_POST['gs1_schedule_enabled'],
                    'gs1_start_date' => $_POST['gs1_start_date'],
                    'gs1_end_date' => $_POST['gs1_end_date'],
                    'gs1_clicks_limit' => $_POST['gs1_clicks_limit'],
                    'gs1_expiration_url' => $_POST['gs1_expiration_url'],
                    'gs1_utm_source' => $_POST['gs1_utm_source'],
                    'gs1_utm_medium' => $_POST['gs1_utm_medium'],
                    'gs1_utm_campaign' => $_POST['gs1_utm_campaign'],
                    // Also update the main target_url for backward compatibility
                    'target_url' => $_POST['gs1_target_url']
                ]);
                break;

            case 'gs1-default-page-design':
                // Handle GS1 Default Page Design configuration
                // This section ONLY updates settings, not product status or other fields
                
                // Get existing settings and merge with new design config
                $existing_settings = [];
                if (!empty($product->settings)) {
                    if (is_string($product->settings)) {
                        $existing_settings = json_decode($product->settings, true) ?? [];
                    } elseif (is_object($product->settings)) {
                        $existing_settings = json_decode(json_encode($product->settings), true) ?? [];
                    } elseif (is_array($product->settings)) {
                        $existing_settings = $product->settings;
                    }
                }
                
                $gs1_design_config = [
                    'template' => $_POST['gs1_template'],
                    'theme' => $_POST['gs1_theme'],
                    'enabled_datapoints' => $_POST['gs1_enabled_datapoints'],
                    'last_updated' => date('Y-m-d H:i:s')
                ];
                
                // Debug logging
                error_log("DEBUG: Controller - Existing settings: " . print_r($existing_settings, true));
                error_log("DEBUG: Controller - New GS1 config: " . print_r($gs1_design_config, true));
                
                // Merge with existing settings
                $existing_settings['gs1_default_page_design'] = $gs1_design_config;
                
                // For GS1 template section, we ONLY return the settings field
                // This prevents any other product fields from being modified
                return ['settings' => $existing_settings];

            case 'gs1-digital-passport':
                $product_data = array_merge($product_data, [
                    // Sustainability Information
                    'carbon_footprint' => $_POST['carbon_footprint'],
                    'water_usage' => $_POST['water_usage'],
                    'renewable_energy_percentage' => $_POST['renewable_energy_percentage'],
                    'recyclability_score' => $_POST['recyclability_score'],
                    'sustainability_certifications' => $_POST['sustainability_certifications'],
                    
                    // Supply Chain & Traceability
                    'supply_chain_transparency' => $_POST['supply_chain_transparency'],
                    'ethical_sourcing' => $_POST['ethical_sourcing'],
                    'key_suppliers' => $_POST['key_suppliers'],
                    'blockchain_verified' => $_POST['blockchain_verified'],
                    
                    // Compliance & Safety
                    'regulatory_compliance' => $_POST['regulatory_compliance'],
                    'safety_standards' => $_POST['safety_standards'],
                    'quality_certifications' => $_POST['quality_certifications'],
                    
                    // Product Lifecycle
                    'lifecycle_stage' => $_POST['lifecycle_stage'],
                    'expected_lifespan' => $_POST['expected_lifespan'],
                    'lifespan_unit' => $_POST['lifespan_unit'],
                    'end_of_life_instructions' => $_POST['end_of_life_instructions'],
                    
                    // Digital Passport Settings
                    'passport_public' => $_POST['passport_public'],
                    'passport_seo' => $_POST['passport_seo'],
                    'passport_last_updated' => $_POST['passport_last_updated'],
                    'data_verification_status' => $_POST['data_verification_status']
                ]);
                break;
        }

        return $product_data;
    }

}
