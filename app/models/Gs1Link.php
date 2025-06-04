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

class Gs1Link extends Model {

    public function get_gs1_links_by_user_id($user_id, $filters = []) {
        $where_conditions = ['user_id' => $user_id];
        
        if (!empty($filters['project_id'])) {
            $where_conditions['project_id'] = $filters['project_id'];
        }
        
        if (!empty($filters['is_enabled'])) {
            $where_conditions['is_enabled'] = $filters['is_enabled'];
        }
        
        $order_by = $filters['order_by'] ?? 'gs1_link_id';
        $order_type = $filters['order_type'] ?? 'DESC';
        $limit = $filters['limit'] ?? null;
        
        $query = "SELECT `gs1_links`.*, `domains`.`scheme`, `domains`.`host` 
                  FROM `gs1_links` 
                  LEFT JOIN `domains` ON `gs1_links`.`domain_id` = `domains`.`domain_id` 
                  WHERE " . $this->array_to_sql_where($where_conditions) . "
                  ORDER BY `{$order_by}` {$order_type}";
        
        if ($limit) {
            $query .= " LIMIT {$limit}";
        }
        
        $result = database()->query($query);
        $gs1_links = [];
        
        while ($row = $result->fetch_object()) {
            $row->full_url = $this->get_full_gs1_url($row);
            $row->settings = json_decode($row->settings ?? '{}');
            $row->pixels_ids = json_decode($row->pixels_ids ?? '[]');
            $gs1_links[] = $row;
        }
        
        return $gs1_links;
    }

    public function get_gs1_link_by_id($gs1_link_id, $user_id = null) {
        $where_conditions = ['gs1_link_id' => $gs1_link_id];
        
        if ($user_id) {
            $where_conditions['user_id'] = $user_id;
        }
        
        $query = "SELECT `gs1_links`.*, `domains`.`scheme`, `domains`.`host` 
                  FROM `gs1_links` 
                  LEFT JOIN `domains` ON `gs1_links`.`domain_id` = `domains`.`domain_id` 
                  WHERE " . $this->array_to_sql_where($where_conditions);
        
        $result = database()->query($query);
        
        if ($result && ($gs1_link = $result->fetch_object())) {
            $gs1_link->full_url = $this->get_full_gs1_url($gs1_link);
            $gs1_link->settings = json_decode($gs1_link->settings ?? '{}');
            $gs1_link->pixels_ids = json_decode($gs1_link->pixels_ids ?? '[]');
            return $gs1_link;
        }
        
        return null;
    }

    public function get_gs1_link_by_gtin($gtin, $domain_id = 0) {
        // Clean GTIN - just remove non-numeric characters
        $gtin = preg_replace('/[^0-9]/', '', $gtin);
        
        if (empty($gtin)) {
            return null;
        }
        
        $query = "SELECT `gs1_links`.*, `domains`.`scheme`, `domains`.`host` 
                  FROM `gs1_links` 
                  LEFT JOIN `domains` ON `gs1_links`.`domain_id` = `domains`.`domain_id` 
                  WHERE `gs1_links`.`gtin` = '{$gtin}' AND `gs1_links`.`domain_id` = {$domain_id} AND `gs1_links`.`is_enabled` = 1";
        
        $result = database()->query($query);
        
        if ($result && ($gs1_link = $result->fetch_object())) {
            $gs1_link->full_url = $this->get_full_gs1_url($gs1_link);
            $gs1_link->settings = json_decode($gs1_link->settings ?? '{}');
            $gs1_link->pixels_ids = json_decode($gs1_link->pixels_ids ?? '[]');
            return $gs1_link;
        }
        
        return null;
    }

    public function create_gs1_link($data) {
        // Clean GTIN - just remove non-numeric characters
        $gtin = preg_replace('/[^0-9]/', '', $data['gtin']);
        
        if (empty($gtin)) {
            return false;
        }
        
        // Apply default target URL if not provided and not required
        if(empty($data['target_url']) && !settings()->gs1_links->require_target_url && !empty(settings()->gs1_links->default_target_url)) {
            $data['target_url'] = settings()->gs1_links->default_target_url;
        }
        
        // Check if target URL is required by admin settings
        if(settings()->gs1_links->require_target_url && empty($data['target_url'])) {
            return false; // Target URL is required but not provided
        }
        
        // Apply GTIN validation based on format validation setting
        if(settings()->gs1_links->gtin_validation_is_enabled && settings()->gs1_links->gtin_format_validation !== 'disabled') {
            $gtin_length = strlen($gtin);
            $valid_lengths = [8, 12, 13, 14];
            
            // Length validation for both lenient and strict modes
            if(!in_array($gtin_length, $valid_lengths)) {
                return false; // Invalid length
            }
            
            // Strict validation includes checksum validation
            if(settings()->gs1_links->gtin_format_validation === 'strict') {
                if(!$this->validate_gtin_checksum($gtin)) {
                    return false; // Invalid checksum
                }
            }
            // Lenient mode only does length validation (already done above)
        }
        
        // Apply additional validation rules if GTIN validation is enabled
        if(settings()->gs1_links->gtin_validation_is_enabled) {
            // Check blacklisted GTINs
            if(!empty(settings()->gs1_links->blacklisted_gtins) && in_array($gtin, settings()->gs1_links->blacklisted_gtins)) {
                return false; // Blacklisted GTIN
            }
            
            // Check allowed GTIN prefixes
            if(!empty(settings()->gs1_links->allowed_gtin_prefixes)) {
                $prefix_allowed = false;
                foreach(settings()->gs1_links->allowed_gtin_prefixes as $prefix) {
                    if(str_starts_with($gtin, $prefix)) {
                        $prefix_allowed = true;
                        break;
                    }
                }
                if(!$prefix_allowed) {
                    return false; // Prefix not allowed
                }
            }
        }
        
        // Check if GTIN already exists for this domain
        if ($this->get_gs1_link_by_gtin($gtin, $data['domain_id'] ?? 0)) {
            return false; // GTIN already exists
        }
        
        $settings = json_encode($data['settings'] ?? []);
        $pixels_ids = json_encode($data['pixels_ids'] ?? []);
        
        $query = "INSERT INTO `gs1_links` 
                  (`user_id`, `project_id`, `domain_id`, `gtin`, `target_url`, `title`, `description`, `settings`, `pixels_ids`, `is_enabled`, `datetime`) 
                  VALUES 
                  ({$data['user_id']}, " . 
                  ($data['project_id'] ? $data['project_id'] : 'NULL') . ", " .
                  ($data['domain_id'] ?? 0) . ", " .
                  "'{$gtin}', " .
                  "'" . db()->escape($data['target_url']) . "', " .
                  "'" . db()->escape($data['title'] ?? '') . "', " .
                  "'" . db()->escape($data['description'] ?? '') . "', " .
                  "'{$settings}', " .
                  "'{$pixels_ids}', " .
                  (int)($data['is_enabled'] ?? 1) . ", " .
                  "NOW())";
        
        database()->query($query);
        
        return database()->insert_id;
    }

    public function update_gs1_link($gs1_link_id, $data, $user_id) {
        $set_clauses = [];
        
        if (isset($data['gtin'])) {
            // Clean GTIN - just remove non-numeric characters
            $gtin = preg_replace('/[^0-9]/', '', $data['gtin']);
            
            if (empty($gtin)) {
                return false;
            }
            
            // Check if new GTIN conflicts with existing ones (excluding current link)
            $existing = database()->query("SELECT gs1_link_id FROM gs1_links WHERE gtin = '{$gtin}' AND domain_id = " . ($data['domain_id'] ?? 0) . " AND gs1_link_id != {$gs1_link_id}")->fetch_object();
            if ($existing) {
                return false;
            }
            
            $set_clauses[] = "`gtin` = '{$gtin}'";
        }
        
        if (isset($data['target_url'])) {
            $set_clauses[] = "`target_url` = '" . db()->escape($data['target_url']) . "'";
        }
        
        if (isset($data['title'])) {
            $set_clauses[] = "`title` = '" . db()->escape($data['title']) . "'";
        }
        
        if (isset($data['description'])) {
            $set_clauses[] = "`description` = '" . db()->escape($data['description']) . "'";
        }
        
        if (isset($data['project_id'])) {
            $set_clauses[] = "`project_id` = " . ($data['project_id'] ? $data['project_id'] : 'NULL');
        }
        
        if (isset($data['domain_id'])) {
            $set_clauses[] = "`domain_id` = " . ($data['domain_id'] ?? 0);
        }
        
        if (isset($data['settings'])) {
            $settings = json_encode($data['settings']);
            $set_clauses[] = "`settings` = '{$settings}'";
        }
        
        if (isset($data['pixels_ids'])) {
            $pixels_ids = json_encode($data['pixels_ids']);
            $set_clauses[] = "`pixels_ids` = '{$pixels_ids}'";
        }
        
        if (isset($data['is_enabled'])) {
            $set_clauses[] = "`is_enabled` = " . (int)$data['is_enabled'];
        }
        
        if (empty($set_clauses)) {
            return false;
        }
        
        $set_clauses[] = "`last_datetime` = NOW()";
        
        $query = "UPDATE `gs1_links` SET " . implode(', ', $set_clauses) . " WHERE `gs1_link_id` = {$gs1_link_id} AND `user_id` = {$user_id}";
        
        database()->query($query);
        
        return database()->affected_rows > 0;
    }

    public function delete_gs1_link($gs1_link_id, $user_id) {
        // Delete tracking data first
        database()->query("DELETE FROM `track_gs1_links` WHERE `gs1_link_id` = {$gs1_link_id}");
        
        // Delete the GS1 link
        database()->query("DELETE FROM `gs1_links` WHERE `gs1_link_id` = {$gs1_link_id} AND `user_id` = {$user_id}");
        
        return database()->affected_rows > 0;
    }

    public function increment_click($gs1_link_id) {
        database()->query("UPDATE `gs1_links` SET `clicks` = `clicks` + 1, `last_datetime` = NOW() WHERE `gs1_link_id` = {$gs1_link_id}");
    }

    public function get_gs1_links_count_by_user_id($user_id, $filters = []) {
        $where_conditions = ['user_id' => $user_id];
        
        if (!empty($filters['project_id'])) {
            $where_conditions['project_id'] = $filters['project_id'];
        }
        
        if (!empty($filters['is_enabled'])) {
            $where_conditions['is_enabled'] = $filters['is_enabled'];
        }
        
        $query = "SELECT COUNT(*) as total FROM `gs1_links` WHERE " . $this->array_to_sql_where($where_conditions);
        
        $result = database()->query($query);
        
        return $result->fetch_object()->total ?? 0;
    }

    public function get_analytics_data($gs1_link_id, $start_date, $end_date) {
        $query = "SELECT 
                    COUNT(*) as total_clicks,
                    COUNT(DISTINCT CASE WHEN is_unique = 1 THEN id END) as unique_clicks,
                    COUNT(DISTINCT country_code) as countries,
                    COUNT(DISTINCT device_type) as device_types
                  FROM `track_gs1_links` 
                  WHERE `gs1_link_id` = {$gs1_link_id} 
                  AND `datetime` BETWEEN '{$start_date}' AND '{$end_date}'";
        
        $result = database()->query($query);
        
        return $result->fetch_object();
    }

    private function get_full_gs1_url($gs1_link) {
        if ($gs1_link->domain_id && isset($gs1_link->scheme) && isset($gs1_link->host)) {
            $domain = $gs1_link->scheme . $gs1_link->host;
        } else {
            $domain = SITE_URL;
        }
        
        return generate_gs1_digital_link($domain, $gs1_link->gtin);
    }

    private function array_to_sql_where($conditions) {
        $where_parts = [];
        
        foreach ($conditions as $key => $value) {
            if (is_null($value)) {
                $where_parts[] = "`gs1_links`.`{$key}` IS NULL";
            } else {
                $where_parts[] = "`gs1_links`.`{$key}` = '" . db()->escape($value) . "'";
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
}
