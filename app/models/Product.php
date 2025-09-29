<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Models;

defined('SEEGAP') || die();

class Product extends Model {

    public function get_products_by_user_id($user_id, $filters = []) {
        $where_conditions = ['user_id' => $user_id];
        
        if (!empty($filters['project_id'])) {
            $where_conditions['project_id'] = $filters['project_id'];
        }
        
        if (!empty($filters['is_enabled'])) {
            $where_conditions['is_enabled'] = $filters['is_enabled'];
        }
        
        if (!empty($filters['category'])) {
            $where_conditions['category'] = $filters['category'];
        }
        
        if (!empty($filters['brand_name'])) {
            $where_conditions['brand_name'] = $filters['brand_name'];
        }
        
        $order_by = $filters['order_by'] ?? 'product_id';
        $order_type = $filters['order_type'] ?? 'DESC';
        $limit = $filters['limit'] ?? null;
        
        $query = "SELECT `products`.*, `projects`.`name` as `project_name`, `projects`.`color` as `project_color`
                  FROM `products` 
                  LEFT JOIN `projects` ON `products`.`project_id` = `projects`.`project_id` 
                  WHERE " . $this->array_to_sql_where($where_conditions) . "
                  ORDER BY `{$order_by}` {$order_type}";
        
        if ($limit) {
            $query .= " LIMIT {$limit}";
        }
        
        $result = database()->query($query);
        $products = [];
        
        while ($row = $result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '{}');
            $row->product_images = json_decode($row->product_images ?? '[]');
            $products[] = $row;
        }
        
        return $products;
    }

    public function get_product_by_id($product_id, $user_id = null) {
        $where_conditions = ['product_id' => $product_id];
        
        if ($user_id) {
            $where_conditions['user_id'] = $user_id;
        }
        
        $query = "SELECT `products`.*, `projects`.`name` as `project_name`, `projects`.`color` as `project_color`,
                         `gs1_links`.`target_url` as `gs1_target_url`, `gs1_links`.`clicks` as `gs1_clicks`
                  FROM `products` 
                  LEFT JOIN `projects` ON `products`.`project_id` = `projects`.`project_id`
                  LEFT JOIN `gs1_links` ON `products`.`gs1_link_id` = `gs1_links`.`gs1_link_id`
                  WHERE " . $this->array_to_sql_where($where_conditions);
        
        $result = database()->query($query);
        
        if ($result && ($product = $result->fetch_object())) {
            $product->settings = json_decode($product->settings ?? '{}');
            $product->product_images = json_decode($product->product_images ?? '[]');
            return $product;
        }
        
        return null;
    }

    public function get_product_by_gtin($gtin, $user_id) {
        // Clean GTIN - just remove non-numeric characters
        $gtin = preg_replace('/[^0-9]/', '', $gtin);
        
        if (empty($gtin)) {
            return null;
        }
        
        $query = "SELECT `products`.*, `projects`.`name` as `project_name`, `projects`.`color` as `project_color`
                  FROM `products` 
                  LEFT JOIN `projects` ON `products`.`project_id` = `projects`.`project_id`
                  WHERE `products`.`gtin` = '{$gtin}' AND `products`.`user_id` = {$user_id}";
        
        $result = database()->query($query);
        
        if ($result && ($product = $result->fetch_object())) {
            $product->settings = json_decode($product->settings ?? '{}');
            $product->product_images = json_decode($product->product_images ?? '[]');
            return $product;
        }
        
        return null;
    }

    public function create_product($data) {
        // Clean GTIN - just remove non-numeric characters
        $gtin = preg_replace('/[^0-9]/', '', $data['gtin']);
        
        // If no GTIN provided, generate a new one
        if (empty($gtin)) {
            // Get the last GTIN used by this user
            $last_product = database()->query("SELECT gtin FROM products WHERE user_id = {$data['user_id']} ORDER BY product_id DESC LIMIT 1")->fetch_object();
            if ($last_product) {
                // Increment the last GTIN
                $gtin = (string)((int)$last_product->gtin + 1);
            } else {
                // Start with a default GTIN if no products exist
                $gtin = '100000000001';
            }
        }
        
        // Validate required fields based on settings
        if (settings()->products->require_product_name && empty($data['product_name'])) {
            return false;
        }
        
        if (settings()->products->require_brand_name && empty($data['brand_name'])) {
            return false;
        }
        
        // Apply GTIN validation if enabled
        if (settings()->products->gtin_validation_is_enabled && settings()->products->gtin_format_validation !== 'disabled') {
            $gtin_length = strlen($gtin);
            $valid_lengths = [8, 12, 13, 14];
            
            // Length validation for both lenient and strict modes
            if (!in_array($gtin_length, $valid_lengths)) {
                return ['error' => 'gtin_invalid_length', 'message' => l('products.error_message.gtin_invalid_format')];
            }
            
            // Strict validation includes checksum validation
            if (settings()->products->gtin_format_validation === 'strict') {
                if (!$this->validate_gtin_checksum($gtin)) {
                    return ['error' => 'gtin_invalid_checksum', 'message' => l('products.error_message.gtin_invalid_checksum')];
                }
            }
        }
        
        // Check if GTIN already exists for this user
        if ($this->get_product_by_gtin($gtin, $data['user_id'])) {
            return ['error' => 'gtin_exists', 'message' => l('products.error_message.gtin_exists')];
        }
        
        $settings = json_encode($data['settings'] ?? []);
        $product_images = json_encode($data['product_images'] ?? []);
        
        $query = "INSERT INTO `products` 
                  (`user_id`, `project_id`, `gtin`, `brand_name`, `product_name`, `product_description`, 
                   `category`, `subcategory`, `manufacturer`, `country_of_origin`, `net_weight`, `dimensions`,
                   `ingredients`, `nutritional_info`, `allergen_info`, `certifications`, `product_images`,
                   `packaging_info`, `storage_instructions`, `usage_instructions`, `target_url`, `gs1_link_id`,
                   `settings`, `is_enabled`, `datetime`) 
                  VALUES 
                  ({$data['user_id']}, " . 
                  ($data['project_id'] ? $data['project_id'] : 'NULL') . ", " .
                  "'{$gtin}', " .
                  "'" . db()->escape($data['brand_name'] ?? '') . "', " .
                  "'" . db()->escape($data['product_name']) . "', " .
                  "'" . db()->escape($data['product_description'] ?? '') . "', " .
                  "'" . db()->escape($data['category'] ?? '') . "', " .
                  "'" . db()->escape($data['subcategory'] ?? '') . "', " .
                  "'" . db()->escape($data['manufacturer'] ?? '') . "', " .
                  "'" . db()->escape($data['country_of_origin'] ?? '') . "', " .
                  "'" . db()->escape($data['net_weight'] ?? '') . "', " .
                  "'" . db()->escape($data['dimensions'] ?? '') . "', " .
                  "'" . db()->escape($data['ingredients'] ?? '') . "', " .
                  "'" . db()->escape($data['nutritional_info'] ?? '') . "', " .
                  "'" . db()->escape($data['allergen_info'] ?? '') . "', " .
                  "'" . db()->escape($data['certifications'] ?? '') . "', " .
                  "'{$product_images}', " .
                  "'" . db()->escape($data['packaging_info'] ?? '') . "', " .
                  "'" . db()->escape($data['storage_instructions'] ?? '') . "', " .
                  "'" . db()->escape($data['usage_instructions'] ?? '') . "', " .
                  "'" . db()->escape($data['target_url'] ?? '') . "', " .
                  ($data['gs1_link_id'] ? $data['gs1_link_id'] : 'NULL') . ", " .
                  "'{$settings}', " .
                  (int)($data['is_enabled'] ?? 1) . ", " .
                  "NOW())";
        
        database()->query($query);
        
        return database()->insert_id;
    }

    public function update_product($product_id, $data, $user_id) {
        $set_clauses = [];
        
        // Define all possible fields that can be updated
        $updatable_fields = [
            // Basic fields
            'gtin', 'brand_name', 'product_name', 'product_description', 'category', 'subcategory', 
            'manufacturer', 'country_of_origin', 'net_weight', 'dimensions', 'ingredients', 
            'nutritional_info', 'allergen_info', 'certifications', 'packaging_info', 
            'storage_instructions', 'usage_instructions', 'target_url', 'project_id', 'gs1_link_id', 
            'settings', 'is_enabled',
            
            // GS1 Identifiers (AI codes)
            'gln', 'variant', 'batch_lot', 'serial', 'cpid', 'additional_id',
            
            // GS1 Attributes (dates and additional info)
            'production_date', 'due_date', 'packaging_date', 'best_before_date', 'sell_by_date', 
            'expiration_date', 'customer_part_number', 'made_to_order_variation', 'packaging_configuration',
            'secondary_serial', 'reference_to_source', 'global_document_type_id',
            
            // GS1 Measurements (variable measures)
            'net_weight_kg', 'length_m', 'width_m', 'height_m', 'area_m2', 'net_volume_l', 'gross_weight_kg',
            'logistic_weight_kg', 'logistic_length_m', 'logistic_width_m', 'logistic_height_m', 
            'logistic_area_m2', 'logistic_volume_l',
            
            // GS1 Logistics
            'ship_to_loc', 'bill_to', 'purchased_from', 'ship_for_loc', 'phy_loc', 'rti_loc',
            'ship_to_post', 'ship_to_post_iso', 'origin', 'country_initial_process', 'country_process',
            'country_disassembly', 'country_full_process',
            
            // Content & Compliance
            'organic_certification', 'fair_trade_certification', 'halal_certified', 'kosher_certified',
            'gluten_free', 'vegan', 'vegetarian', 'non_gmo', 'care_instructions', 'warning_info',
            
            // Digital Integration
            'product_url', 'manufacturer_url', 'product_info_url', 'sustainability_url', 'recycling_url',
            'safety_url', 'facebook_url', 'instagram_url', 'twitter_url', 'youtube_url', 'purchase_url',
            'amazon_asin', 'ebay_item_id', 'price_comparison_url', 'manual_url', 'support_url',
            'faq_url', 'tutorial_url', 'api_endpoint', 'webhook_url',
            
            // Media & Images
            'youtube_video_id', 'image_quality', 'auto_resize_images', 'generate_thumbnails', 'watermark_images'
        ];
        
        // Special handling for GTIN
        if (isset($data['gtin'])) {
            // Clean GTIN - just remove non-numeric characters
            $gtin = preg_replace('/[^0-9]/', '', $data['gtin']);
            
            if (empty($gtin)) {
                return false;
            }
            
            // Check if new GTIN conflicts with existing ones (excluding current product)
            $existing = database()->query("SELECT product_id FROM products WHERE gtin = '{$gtin}' AND user_id = {$user_id} AND product_id != {$product_id}")->fetch_object();
            if ($existing) {
                return false;
            }
            
            $set_clauses[] = "`gtin` = '{$gtin}'";
        }
        
        // Process all other fields
        foreach ($updatable_fields as $field) {
            if (isset($data[$field]) && $field !== 'gtin') {
                if (in_array($field, ['project_id', 'gs1_link_id'])) {
                    // Handle nullable integer fields
                    $set_clauses[] = "`{$field}` = " . ($data[$field] ? (int)$data[$field] : 'NULL');
                } elseif (in_array($field, ['is_enabled', 'halal_certified', 'kosher_certified', 'gluten_free', 'vegan', 'vegetarian', 'non_gmo', 'auto_resize_images', 'generate_thumbnails', 'watermark_images'])) {
                    // Handle boolean fields
                    $set_clauses[] = "`{$field}` = " . (int)$data[$field];
                } elseif ($field === 'settings') {
                    // Handle JSON fields
                    $settings = json_encode($data['settings']);
                    $set_clauses[] = "`settings` = '{$settings}'";
                } elseif ($field === 'product_images') {
                    // Handle JSON fields
                    $product_images = json_encode($data['product_images']);
                    $set_clauses[] = "`product_images` = '{$product_images}'";
                } else {
                    // Handle string fields
                    $set_clauses[] = "`{$field}` = '" . db()->escape($data[$field]) . "'";
                }
            }
        }
        
        if (empty($set_clauses)) {
            return false;
        }
        
        $set_clauses[] = "`last_datetime` = NOW()";
        
        $query = "UPDATE `products` SET " . implode(', ', $set_clauses) . " WHERE `product_id` = {$product_id} AND `user_id` = {$user_id}";
        
        database()->query($query);
        
        return database()->affected_rows > 0;
    }

    public function delete_product($product_id, $user_id) {
        // Delete the product
        database()->query("DELETE FROM `products` WHERE `product_id` = {$product_id} AND `user_id` = {$user_id}");
        
        return database()->affected_rows > 0;
    }

    public function get_products_count_by_user_id($user_id, $filters = []) {
        $where_conditions = ['user_id' => $user_id];
        
        if (!empty($filters['project_id'])) {
            $where_conditions['project_id'] = $filters['project_id'];
        }
        
        if (!empty($filters['is_enabled'])) {
            $where_conditions['is_enabled'] = $filters['is_enabled'];
        }
        
        if (!empty($filters['category'])) {
            $where_conditions['category'] = $filters['category'];
        }
        
        if (!empty($filters['brand_name'])) {
            $where_conditions['brand_name'] = $filters['brand_name'];
        }
        
        $query = "SELECT COUNT(*) as total FROM `products` WHERE " . $this->array_to_sql_where($where_conditions);
        
        $result = database()->query($query);
        
        return $result->fetch_object()->total ?? 0;
    }

    public function get_unique_categories($user_id) {
        $query = "SELECT DISTINCT `category` FROM `products` WHERE `user_id` = {$user_id} AND `category` IS NOT NULL AND `category` != '' ORDER BY `category`";
        
        $result = database()->query($query);
        $categories = [];
        
        while ($row = $result->fetch_object()) {
            $categories[] = $row->category;
        }
        
        return $categories;
    }

    public function get_unique_brands($user_id) {
        $query = "SELECT DISTINCT `brand_name` FROM `products` WHERE `user_id` = {$user_id} AND `brand_name` IS NOT NULL AND `brand_name` != '' ORDER BY `brand_name`";
        
        $result = database()->query($query);
        $brands = [];
        
        while ($row = $result->fetch_object()) {
            $brands[] = $row->brand_name;
        }
        
        return $brands;
    }

    private function array_to_sql_where($conditions) {
        $where_parts = [];
        
        foreach ($conditions as $key => $value) {
            if (is_null($value)) {
                $where_parts[] = "`products`.`{$key}` IS NULL";
            } else {
                $where_parts[] = "`products`.`{$key}` = '" . db()->escape($value) . "'";
            }
        }
        
        return implode(' AND ', $where_parts);
    }
    
    /**
     * Validate GTIN checksum using the standard algorithm
     */
    private function validate_gtin_checksum($gtin) {
        $gtin = str_pad($gtin, 14, '0', STR_PAD_LEFT);
        
        if (strlen($gtin) !== 14) {
            return false;
        }
        
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $digit = (int) $gtin[$i];
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }
        
        $checksum = (10 - ($sum % 10)) % 10;
        return $checksum === (int) $gtin[13];
    }

    /**
     * Delete method wrapper for backward compatibility
     */
    public function delete($product_id) {
        // Get the user_id for the product to ensure proper authorization
        $product = database()->query("SELECT user_id FROM products WHERE product_id = {$product_id}")->fetch_object();
        
        if (!$product) {
            return false;
        }
        
        return $this->delete_product($product_id, $product->user_id);
    }

    /**
     * Create GS1 link for product if auto-generation is enabled
     */
    public function create_gs1_link_for_product($product_id, $user_id) {
        if (!settings()->products->auto_generate_gs1_links) {
            return null;
        }
        
        $product = $this->get_product_by_id($product_id, $user_id);
        if (!$product) {
            return null;
        }
        
        // Check if GS1 link already exists
        if ($product->gs1_link_id) {
            return $product->gs1_link_id;
        }
        
        // Create GS1 link
        $gs1_link_model = new \SeeGap\Models\Gs1Link();
        $gs1_link_data = [
            'user_id' => $user_id,
            'project_id' => $product->project_id,
            'domain_id' => 0,
            'gtin' => $product->gtin,
            'target_url' => $product->target_url ?: '',
            'title' => $product->product_name,
            'description' => $product->product_description,
            'settings' => [],
            'pixels_ids' => [],
            'is_enabled' => $product->is_enabled
        ];
        
        $gs1_link_id = $gs1_link_model->create_gs1_link($gs1_link_data);
        
        if ($gs1_link_id) {
            // Update product with GS1 link ID
            $this->update_product($product_id, ['gs1_link_id' => $gs1_link_id], $user_id);
            return $gs1_link_id;
        }
        
        return null;
    }
}
