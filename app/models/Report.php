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

class Report extends Model {

    public function get_reports_by_user_id($user_id) {
        $reports = [];

        $reports_result = database()->query("
            SELECT 
                *
            FROM 
                `reports` 
            WHERE 
                (`user_id` = {$user_id} OR JSON_CONTAINS(`assigned_user_ids`, '{$user_id}', '$'))
                AND `is_enabled` = 1
            ORDER BY 
                `datetime` DESC
        ");

        while($row = $reports_result->fetch_object()) {
            $row->assigned_user_ids = json_decode($row->assigned_user_ids);
            $reports[] = $row;
        }

        return $reports;
    }

    public function get_report_by_id($report_id, $user_id = null) {
        $where_clause = "`report_id` = {$report_id}";
        
        if($user_id) {
            $where_clause .= " AND (`user_id` = {$user_id} OR JSON_CONTAINS(`assigned_user_ids`, '{$user_id}', '$'))";
        }

        $report = database()->query("
            SELECT 
                *
            FROM 
                `reports` 
            WHERE 
                {$where_clause}
                AND `is_enabled` = 1
        ")->fetch_object();

        if($report) {
            $report->assigned_user_ids = json_decode($report->assigned_user_ids);
        }

        return $report;
    }

    public function get_all_reports() {
        $reports = [];

        $reports_result = database()->query("
            SELECT 
                r.*,
                u.name as user_name,
                u.email as user_email
            FROM 
                `reports` r
            LEFT JOIN 
                `users` u ON r.user_id = u.user_id
            ORDER BY 
                r.datetime DESC
        ");

        while($row = $reports_result->fetch_object()) {
            $row->assigned_user_ids = json_decode($row->assigned_user_ids);
            $reports[] = $row;
        }

        return $reports;
    }

    public function delete($report_id) {
        if(!$report = db()->where('report_id', $report_id)->getOne('reports', ['report_id'])) {
            return false;
        }

        db()->where('report_id', $report_id)->delete('reports');

        return true;
    }

}
