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
use SeeGap\Models\Product;
use SeeGap\Models\Gs1Link;
use SeeGap\Title;

defined('SEEGAP') || die();

class Gs1Product extends Controller {

    public function index() {
        
        /* Check if we have GS1 Digital Link format (AI + value) or just GTIN */
        if (isset($this->params[1])) {
            /* GS1 Digital Link format: /01/05678901234567 */
            $this->handleGs1DigitalLink();
        } else {
            /* Legacy GTIN format: /gs1-product/05678901234567 */
            $this->handleLegacyGtin();
        }
    }
    
    /**
     * Handle GS1 Digital Link format with AI and value
     */
    private function handleGs1DigitalLink() {
        /* Get the GS1 identifier from the URL */
        $gs1_identifier = $this->params[0] ?? null;
        $gs1_value = $this->params[1] ?? null;
        
        if(!$gs1_identifier || !$gs1_value) {
            redirect('not-found');
        }
        
        /* Find the product with this GS1 identifier */
        $product = null;
        
        switch($gs1_identifier) {
            case '01': // GTIN
                $product = db()->where('gtin', $gs1_value)->where('is_enabled', 1)->getOne('products');
                break;
            case '414': // GLN
                $product = db()->where('gln', $gs1_value)->where('is_enabled', 1)->getOne('products');
                break;
            case '00': // SSCC
                $product = db()->where('sscc', $gs1_value)->where('is_enabled', 1)->getOne('products');
                break;
            case '8003': // GRAI
                $product = db()->where('grai', $gs1_value)->where('is_enabled', 1)->getOne('products');
                break;
            case '8004': // GIAI
                $product = db()->where('giai', $gs1_value)->where('is_enabled', 1)->getOne('products');
                break;
            case '8018': // GSRN
                $product = db()->where('gsrn', $gs1_value)->where('is_enabled', 1)->getOne('products');
                break;
            case '253': // GDTI
                $product = db()->where('gdti', $gs1_value)->where('is_enabled', 1)->getOne('products');
                break;
            case '255': // GCN
                $product = db()->where('gcn', $gs1_value)->where('is_enabled', 1)->getOne('products');
                break;
            case '401': // GINC
                $product = db()->where('ginc', $gs1_value)->where('is_enabled', 1)->getOne('products');
                break;
            case '402': // GSIN
                $product = db()->where('gsin', $gs1_value)->where('is_enabled', 1)->getOne('products');
                break;
            default:
                redirect('not-found');
        }
        
        if(!$product) {
            redirect('not-found');
        }
        
        /* Handle the redirect based on the content type if GS1 Digital Link is configured */
        if(isset($product->gs1_digital_link_enabled) && $product->gs1_digital_link_enabled) {
            switch($product->gs1_digital_link_content_type) {
                case 'microsite':
                    if($product->gs1_digital_link_microsite_id) {
                        /* Get the microsite link */
                        $link = db()->where('link_id', $product->gs1_digital_link_microsite_id)->where('user_id', $product->user_id)->where('type', 'microsite')->getOne('links');
                        if($link) {
                            redirect($link->url);
                        }
                    }
                    break;
                    
                case 'file':
                    if($product->gs1_digital_link_file_id) {
                        /* Get the file link */
                        $link = db()->where('link_id', $product->gs1_digital_link_file_id)->where('user_id', $product->user_id)->where('type', 'file')->getOne('links');
                        if($link) {
                            redirect($link->url);
                        }
                    }
                    break;
                    
                case 'external_url':
                    if($product->gs1_digital_link_external_url) {
                        redirect($product->gs1_digital_link_external_url);
                    }
                    break;
            }
        }
        
        /* Default behavior: Show the GS1 product page directly (maintaining GS1 compliant URL) */
        $this->renderGs1ProductPage($product, $gs1_value);
    }
    
    /**
     * Handle legacy GTIN format
     */
    private function handleLegacyGtin() {
        /* Get GTIN from URL parameters */
        $gtin = isset($this->params[0]) ? $this->params[0] : null;
        
        if (!$gtin) {
            /* Redirect to 404 if no GTIN provided */
            redirect('404');
        }
        
        /* Clean GTIN - remove non-numeric characters */
        $gtin = preg_replace('/[^0-9]/', '', $gtin);
        
        if (empty($gtin)) {
            redirect('404');
        }
        
        /* Try to find product by GTIN */
        $product = $this->find_product_by_gtin($gtin);
        
        if (!$product) {
            /* If no product found, try to find GS1 link */
            $gs1_link = $this->find_gs1_link_by_gtin($gtin);
            
            if ($gs1_link && $gs1_link->target_url) {
                /* Track the click */
                $this->track_gs1_link_click($gs1_link->gs1_link_id);
                
                /* Redirect to target URL */
                redirect($gs1_link->target_url);
            } else {
                /* No product or GS1 link found */
                redirect('404');
            }
        }
        
        /* Render the GS1 product page */
        $this->renderGs1ProductPage($product, $gtin);
    }
    
    /**
     * Render the GS1 product page directly
     */
    private function renderGs1ProductPage($product, $identifier_value) {
        /* Check if product's digital passport is public */
        if (!($product->passport_public ?? true)) {
            redirect('not-found');
        }
        
        /* Track page view if GS1 link exists */
        if ($product->gs1_link_id) {
            $this->track_gs1_link_click($product->gs1_link_id, 'page_view');
        }
        
        /* Set custom title for SEO */
        $title = $product->product_name ?? $product->gtin;
        Title::set($title . ' - ' . l('products.gs1.digital_passport'));
        
        /* Prepare product data */
        $product->product_images = json_decode($product->product_images ?? '[]');
        $product->settings = json_decode($product->settings ?? '{}');
        
        /* Check if product has custom GS1 default page design configuration */
        $design_config = null;
        $use_custom_design = false;
        
        if ($product->gs1_link_enabled && $product->gs1_link_type === 'default' && 
            isset($product->settings->gs1_default_page_design)) {
            $design_config = $product->settings->gs1_default_page_design;
            $use_custom_design = true;
        }
        
        /* Prepare the view data */
        $data = [
            'product' => $product,
            'design_config' => $design_config,
            'use_custom_design' => $use_custom_design,
        ];
        
        /* Render the appropriate GS1 product page */
        if ($use_custom_design && $design_config) {
            /* Use custom template based on design configuration */
            $template_name = $design_config->template ?? 'modern';
            $view_path = 'gs1-product/templates/' . $template_name;
            
            /* Fallback to default if template doesn't exist */
            if (!file_exists(THEME_PATH . 'views/' . $view_path . '.php')) {
                $view_path = 'gs1-product/index';
            }
            
            $view = new \SeeGap\View($view_path, (array) $this);
        } else {
            /* Use default template */
            $view = new \SeeGap\View('gs1-product/index', (array) $this);
        }
        
        $this->add_view_content('content', $view->run($data));
    }
    
    /**
     * Find product by GTIN across all users (for public access)
     */
    private function find_product_by_gtin($gtin) {
        $query = "SELECT `products`.*, `users`.`name` as `user_name`
                  FROM `products` 
                  LEFT JOIN `users` ON `products`.`user_id` = `users`.`user_id`
                  WHERE `products`.`gtin` = '{$gtin}' 
                  AND `products`.`is_enabled` = 1
                  AND (`products`.`passport_public` IS NULL OR `products`.`passport_public` = 1)
                  ORDER BY `products`.`product_id` DESC
                  LIMIT 1";
        
        $result = database()->query($query);
        
        if ($result && ($product = $result->fetch_object())) {
            return $product;
        }
        
        return null;
    }
    
    /**
     * Find GS1 link by GTIN (fallback if no product found)
     */
    private function find_gs1_link_by_gtin($gtin) {
        $gs1_link_model = new Gs1Link();
        
        /* Try to find GS1 link by GTIN across all domains */
        $query = "SELECT * FROM `gs1_links` 
                  WHERE `gtin` = '{$gtin}' 
                  AND `is_enabled` = 1
                  ORDER BY `gs1_link_id` DESC
                  LIMIT 1";
        
        $result = database()->query($query);
        
        if ($result && ($gs1_link = $result->fetch_object())) {
            $gs1_link->settings = json_decode($gs1_link->settings ?? '{}');
            return $gs1_link;
        }
        
        return null;
    }
    
    /**
     * Track GS1 link click/view for analytics
     */
    private function track_gs1_link_click($gs1_link_id, $type = 'click') {
        try {
            /* Get visitor information */
            $ip = get_ip();
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $referrer = $_SERVER['HTTP_REFERER'] ?? '';
            $country_code = get_country_from_ip($ip);
            
            /* Insert click/view record */
            $query = "INSERT INTO `gs1_links_statistics` 
                      (`gs1_link_id`, `ip`, `country_code`, `referrer`, `user_agent`, `type`, `datetime`) 
                      VALUES 
                      ({$gs1_link_id}, '{$ip}', '{$country_code}', '" . db()->escape($referrer) . "', '" . db()->escape($user_agent) . "', '{$type}', NOW())";
            
            database()->query($query);
            
            /* Update total clicks counter */
            if ($type === 'click') {
                database()->query("UPDATE `gs1_links` SET `clicks` = `clicks` + 1 WHERE `gs1_link_id` = {$gs1_link_id}");
            }
            
        } catch (Exception $e) {
            /* Silently fail - tracking is not critical */
            error_log('GS1 tracking error: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate preview for GS1 product page (used by canvas preview)
     */
    public function preview() {
        /* Only allow POST requests */
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }
        
        /* Get product ID from URL */
        $product_id = isset($this->params[0]) ? (int) $this->params[0] : 0;
        
        if (!$product_id) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid product ID']);
            exit;
        }
        
        /* Get preview configuration from POST data */
        $input = json_decode(file_get_contents('php://input'), true);
        $template = $input['template'] ?? 'modern';
        $theme = $input['theme'] ?? 'blue';
        $enabled_datapoints = $input['enabled_datapoints'] ?? [];
        
        /* Validate template */
        $valid_templates = ['modern', 'classic', 'minimal', 'detailed'];
        if (!in_array($template, $valid_templates)) {
            $template = 'modern';
        }
        
        /* Validate theme */
        $valid_themes = ['blue', 'green', 'purple', 'orange', 'red', 'gray'];
        if (!in_array($theme, $valid_themes)) {
            $theme = 'blue';
        }
        
        /* Find the product */
        $product = db()->where('product_id', $product_id)->getOne('products');
        
        if (!$product) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Product not found']);
            exit;
        }
        
        /* Prepare product data exactly like the public view */
        $product->product_images = json_decode($product->product_images ?? '[]');
        $product->settings = json_decode($product->settings ?? '{}');
        
        /* Create custom design configuration for preview */
        $design_config = (object) [
            'template' => $template,
            'theme' => $theme,
            'enabled_datapoints' => $enabled_datapoints,
            'last_updated' => date('Y-m-d H:i:s')
        ];
        
        /* Set custom title for SEO (same as public view) */
        $title = $product->product_name ?? $product->gtin;
        
        /* Prepare the view data */
        $data = [
            'product' => $product,
            'design_config' => $design_config,
            'use_custom_design' => true,
            'is_preview' => true
        ];
        
        /* Render the appropriate template */
        $view_path = 'gs1-product/templates/' . $template;
        
        /* Fallback to default if template doesn't exist */
        if (!file_exists(THEME_PATH . 'views/' . $view_path . '.php')) {
            $view_path = 'gs1-product/index';
        }
        
        try {
            /* Start output buffering to capture any unwanted output */
            ob_start();
            
            /* Create view instance with minimal context to avoid app wrapper */
            $view_context = [
                'params' => $this->params,
                'method' => $_SERVER['REQUEST_METHOD'] ?? 'POST'
            ];
            
            $view = new \SeeGap\View($view_path, $view_context);
            $content_html = $view->run($data);
            
            /* Clean any unwanted output */
            ob_end_clean();
            
            /* Create a simplified HTML document that matches the public view styling */
            $preview_html = '<!DOCTYPE html>
<html lang="' . \SeeGap\Language::$code . '" dir="' . l('direction') . '">
<head>
    <title>' . htmlspecialchars($title . ' - ' . l('products.gs1.digital_passport')) . '</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Theme CSS -->
    <link href="' . ASSETS_FULL_URL . 'css/' . \SeeGap\ThemeStyle::get_file() . '?v=' . PRODUCT_CODE . '" rel="stylesheet">
    <link href="' . ASSETS_FULL_URL . 'css/custom.css?v=' . PRODUCT_CODE . '" rel="stylesheet">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .seegap-animate {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        /* Ensure proper mobile scaling */
        @media (max-width: 768px) {
            body { font-size: 14px; }
            .container { padding-left: 15px; padding-right: 15px; }
        }
    </style>
</head>
<body class="' . (l('direction') == 'rtl' ? 'rtl' : '') . ' ' . (\SeeGap\ThemeStyle::get() == 'dark' ? 'cc--darkmode' : '') . '" data-theme-style="' . \SeeGap\ThemeStyle::get() . '">
    <main class="seegap-animate seegap-animate-fill-none seegap-animate-fade-in">
        ' . $content_html . '
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    
    <!-- Custom JS for interactive elements -->
    <script>
        // Initialize any interactive elements that might be in the template
        document.addEventListener("DOMContentLoaded", function() {
            // QR Code generation if element exists
            const qrCodeElement = document.getElementById("qrcode");
            if (qrCodeElement && typeof QRCode !== "undefined") {
                QRCode.toCanvas(qrCodeElement, window.location.href, {
                    width: 200,
                    height: 200,
                    margin: 2
                }, function (error) {
                    if (error) {
                        qrCodeElement.innerHTML = \'<div class="text-muted"><i class="fas fa-qrcode fa-3x"></i><br>QR Code</div>\';
                    }
                });
            }
        });
    </script>
</body>
</html>';
            
            /* Return the complete rendered HTML with proper JSON encoding */
            header('Content-Type: application/json; charset=utf-8');
            
            $response = [
                'success' => true,
                'html' => $preview_html,
                'template' => $template,
                'theme' => $theme,
                'datapoints_count' => count($enabled_datapoints)
            ];
            
            echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
            
        } catch (Exception $e) {
            /* Clean any output buffer */
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Failed to render preview',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            exit;
        }
    }
    
    /**
     * Handle API endpoint for tracking (called via JavaScript)
     */
    public function track() {
        /* Only allow POST requests */
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        /* Get GS1 link ID from URL */
        $gs1_link_id = isset($this->params[0]) ? (int) $this->params[0] : 0;
        
        if (!$gs1_link_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid GS1 link ID']);
            return;
        }
        
        /* Get request data */
        $input = json_decode(file_get_contents('php://input'), true);
        $type = $input['type'] ?? 'page_view';
        
        /* Validate type */
        if (!in_array($type, ['click', 'page_view', 'share', 'qr_scan'])) {
            $type = 'page_view';
        }
        
        /* Track the event */
        $this->track_gs1_link_click($gs1_link_id, $type);
        
        /* Return success response */
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }
}

/**
 * Helper function to get visitor's country from IP
 */
function get_country_from_ip($ip) {
    /* Simple IP to country detection - you might want to use a more sophisticated service */
    try {
        /* Use a free IP geolocation service */
        $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode");
        if ($response) {
            $data = json_decode($response, true);
            return $data['countryCode'] ?? 'Unknown';
        }
    } catch (Exception $e) {
        /* Silently fail */
    }
    
    return 'Unknown';
}

/**
 * Helper function to get visitor's IP address
 */
function get_ip() {
    /* Check for various headers that might contain the real IP */
    $headers = [
        'HTTP_CF_CONNECTING_IP',     // Cloudflare
        'HTTP_CLIENT_IP',            // Proxy
        'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
        'HTTP_X_FORWARDED',          // Proxy
        'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
        'HTTP_FORWARDED_FOR',        // Proxy
        'HTTP_FORWARDED',            // Proxy
        'REMOTE_ADDR'                // Standard
    ];
    
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ips = explode(',', $_SERVER[$header]);
            $ip = trim($ips[0]);
            
            /* Validate IP address */
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }
    }
    
    /* Fallback to REMOTE_ADDR */
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}
